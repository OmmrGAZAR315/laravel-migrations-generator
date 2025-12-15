<?php

namespace OmrGz\MigrationsGenerator\Migration;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OmrGz\MigrationsGenerator\Enum\Driver;
use OmrGz\MigrationsGenerator\Enum\Migrations\Method\SchemaBuilder;
use OmrGz\MigrationsGenerator\Enum\Migrations\Method\TableMethod;
use OmrGz\MigrationsGenerator\Enum\Migrations\Property\TableProperty;
use OmrGz\MigrationsGenerator\Migration\Blueprint\DBStatementBlueprint;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Migration\Blueprint\SchemaBlueprint;
use OmrGz\MigrationsGenerator\Migration\Blueprint\TableBlueprint;
use OmrGz\MigrationsGenerator\Migration\Enum\MigrationFileType;
use OmrGz\MigrationsGenerator\Migration\Generator\ColumnGenerator;
use OmrGz\MigrationsGenerator\Migration\Generator\IndexGenerator;
use OmrGz\MigrationsGenerator\Migration\Writer\MigrationWriter;
use OmrGz\MigrationsGenerator\Migration\Writer\SquashWriter;
use OmrGz\MigrationsGenerator\Schema\Models\Index;
use OmrGz\MigrationsGenerator\Schema\Models\Table;
use OmrGz\MigrationsGenerator\Setting;
use OmrGz\MigrationsGenerator\Support\MigrationNameHelper;
use OmrGz\MigrationsGenerator\Support\TableName;

class TableMigration
{
    use TableName;

    public function __construct(
        private readonly ColumnGenerator $columnGenerator,
        private readonly MigrationNameHelper $migrationNameHelper,
        private readonly IndexGenerator $indexGenerator,
        private readonly MigrationWriter $migrationWriter,
        private readonly Setting $setting,
        private readonly SquashWriter $squashWriter,
    ) {
    }

    /**
     * Create table migration.
     *
     * @return string The migration file path.
     */
    public function write(Table $table): string
    {
        $upList = new Collection();
        $upList->push($this->up($table));

        if ($table->getUdtColumns()->isNotEmpty()) {
            foreach ($this->upAdditionalStatements($table) as $statement) {
                $upList->push($statement);
            }
        }

        $down = $this->down($table);

        $this->migrationWriter->writeTo(
            $path = $this->makeMigrationPath($table->getName()),
            $this->setting->getStubPath(),
            $this->makeMigrationClassName($table->getName()),
            $upList,
            new Collection([$down]),
            MigrationFileType::TABLE,
        );

        return $path;
    }

    /**
     * Write table migration into temporary file.
     */
    public function writeToTemp(Table $table): void
    {
        $upList = new Collection();
        $upList->push($this->up($table));

        if ($table->getUdtColumns()->isNotEmpty()) {
            foreach ($this->upAdditionalStatements($table) as $statement) {
                $upList->push($statement);
            }
        }

        $down = $this->down($table);

        $this->squashWriter->writeToTemp($upList, new Collection([$down]));
    }

    /**
     * Generates `up` schema for table.
     */
    private function up(Table $table): SchemaBlueprint
    {
        $up = $this->getSchemaBlueprint($table, SchemaBuilder::CREATE);

        $blueprint = new TableBlueprint();

        if ($this->shouldSetCharset()) {
            $blueprint = $this->setTableCharset($blueprint, $table);
            $blueprint->setLineBreak();
        }

        if ($table->getComment() !== null && $table->getComment() !== '') {
            $blueprint->setMethod(new Method(TableMethod::COMMENT, $table->getComment()));
        }

        $indexes = $table->getIndexes()->filter(static fn (Index $index) => !$index->hasUdtColumn());

        $chainableIndexes    = $this->indexGenerator->getChainableIndexes($table->getName(), $indexes);
        $notChainableIndexes = $this->indexGenerator->getNotChainableIndexes($indexes, $chainableIndexes);

        foreach ($table->getColumns() as $column) {
            $method = $this->columnGenerator->generate($table, $column, $chainableIndexes);
            $blueprint->setMethod($method);
        }

        $blueprint->mergeTimestamps();

        if ($notChainableIndexes->isNotEmpty()) {
            $blueprint->setLineBreak();

            foreach ($notChainableIndexes as $index) {
                $method = $this->indexGenerator->generate($table, $index);
                $blueprint->setMethod($method);
            }
        }

        $up->setBlueprint($blueprint);

        return $up;
    }

    /**
     * Generate custom statements.
     *
     * @return \OmrGz\MigrationsGenerator\Migration\Blueprint\DBStatementBlueprint[]
     */
    private function upAdditionalStatements(Table $table): array
    {
        $statements = [];

        foreach ($table->getUdtColumns() as $column) {
            foreach ($column->getSqls() as $sql) {
                $statements[] = new DBStatementBlueprint($sql);
            }
        }

        $indexes = $table->getIndexes()->filter(static fn (Index $index) => $index->hasUdtColumn());

        foreach ($indexes as $index) {
            foreach ($index->getUDTColumnSqls() as $sql) {
                $statements[] = new DBStatementBlueprint($sql);
            }
        }

        return $statements;
    }

    /**
     * Generates `down` schema for table.
     */
    private function down(Table $table): SchemaBlueprint
    {
        return $this->getSchemaBlueprint($table, SchemaBuilder::DROP_IF_EXISTS);
    }

    /**
     * Makes class name for table migration.
     *
     * @param  string  $table  Table name.
     */
    private function makeMigrationClassName(string $table): string
    {
        $withoutPrefix = $this->stripTablePrefix($table);
        return $this->migrationNameHelper->makeClassName(
            $this->setting->getTableFilename(),
            $withoutPrefix,
        );
    }

    /**
     * Makes file path for table migration.
     *
     * @param  string  $table  Table name.
     */
    private function makeMigrationPath(string $table): string
    {
        $withoutPrefix = $this->stripTablePrefix($table);
        return $this->migrationNameHelper->makeFilename(
            $this->setting->getTableFilename(),
            $this->setting->getDateForMigrationFilename(),
            $withoutPrefix,
        );
    }

    /**
     * Checks should set charset into table.
     */
    private function shouldSetCharset(): bool
    {
        if (!in_array(DB::getDriverName(), [Driver::MARIADB->value, Driver::MYSQL->value])) {
            return false;
        }

        return $this->setting->isUseDBCollation();
    }

    private function setTableCharset(TableBlueprint $blueprint, Table $table): TableBlueprint
    {
        $blueprint->setProperty(
            TableProperty::COLLATION,
            $collation = $table->getCollation(),
        );

        if ($collation === null) {
            return $blueprint;
        }

        $charset = Str::before($collation, '_');
        $blueprint->setProperty(TableProperty::CHARSET, $charset);

        return $blueprint;
    }

    private function getSchemaBlueprint(Table $table, SchemaBuilder $schemaBuilder): SchemaBlueprint
    {
        return new SchemaBlueprint(
            $table->getName(),
            $schemaBuilder,
        );
    }
}
