<?php

namespace OmrGz\MigrationsGenerator\Database;

use Illuminate\Support\Collection;
use OmrGz\MigrationsGenerator\Database\Models\MySQL\MySQLForeignKey;
use OmrGz\MigrationsGenerator\Database\Models\MySQL\MySQLProcedure;
use OmrGz\MigrationsGenerator\Database\Models\MySQL\MySQLTable;
use OmrGz\MigrationsGenerator\Database\Models\MySQL\MySQLView;
use OmrGz\MigrationsGenerator\Repositories\Entities\ProcedureDefinition;
use OmrGz\MigrationsGenerator\Repositories\MySQLRepository;
use OmrGz\MigrationsGenerator\Schema\Models\Table;
use OmrGz\MigrationsGenerator\Schema\Models\View;
use OmrGz\MigrationsGenerator\Schema\MySQLSchema as MySQLSchemaInterface;

class MySQLSchema extends DatabaseSchema implements MySQLSchemaInterface
{
    public function __construct(private readonly MySQLRepository $mySQLRepository)
    {
    }

    /**
     * @inheritDoc
     */
    public function getTable(string $name): Table
    {
        return new MySQLTable(
            $this->getSchemaTable($name),
            $this->getSchemaColumns($name),
            $this->getSchemaIndexes($name),
            new Collection(),
        );
    }

    /**
     * @inheritDoc
     */
    public function getViewNames(): Collection
    {
        return $this->getViews()->map(static fn (View $view) => $view->getName());
    }

    /**
     * @inheritDoc
     */
    public function getViews(): Collection
    {
        return $this->getSchemaViews()->map(static fn (array $view) => new MySQLView($view));
    }

    /**
     * @inheritDoc
     */
    public function getProcedures(): Collection
    {
        return $this->mySQLRepository->getProcedures()
            ->map(static fn (ProcedureDefinition $procedureDefinition) => new MySQLProcedure($procedureDefinition->getName(), $procedureDefinition->getDefinition()));
    }

    /**
     * @inheritDoc
     */
    public function getForeignKeys(string $table): Collection
    {
        return $this->getSchemaForeignKeys($table)
            ->map(static fn (array $foreignKey) => new MySQLForeignKey($table, $foreignKey));
    }
}
