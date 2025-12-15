<?php

namespace OmrGz\MigrationsGenerator\Database;

use Illuminate\Support\Collection;
use OmrGz\MigrationsGenerator\Database\Models\PgSQL\PgSQLProcedure;
use OmrGz\MigrationsGenerator\Database\Models\SQLSrv\SQLSrvForeignKey;
use OmrGz\MigrationsGenerator\Database\Models\SQLSrv\SQLSrvTable;
use OmrGz\MigrationsGenerator\Database\Models\SQLSrv\SQLSrvView;
use OmrGz\MigrationsGenerator\Repositories\Entities\ProcedureDefinition;
use OmrGz\MigrationsGenerator\Repositories\SQLSrvRepository;
use OmrGz\MigrationsGenerator\Schema\Models\Table;
use OmrGz\MigrationsGenerator\Schema\Models\View;

class SQLSrvSchema extends DatabaseSchema
{
    /**
     * @var \Illuminate\Support\Collection<int, string>
     */
    private Collection $userDefinedTypes;

    private bool $ranGetUserDefinedTypes = false;

    public function __construct(private readonly SQLSrvRepository $sqlSrvRepository)
    {
        $this->userDefinedTypes = new Collection();
    }

    /**
     * @inheritDoc
     */
    public function getTable(string $name): Table
    {
        return new SQLSrvTable(
            $this->getSchemaTable($name),
            $this->getSchemaColumns($name),
            $this->getSchemaIndexes($name),
            $this->getUserDefinedTypes(),
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
        return $this->getSchemaViews()
            ->map(static fn (array $view) => new SQLSrvView($view))
            ->filter(static fn (SQLSrvView $view) => $view->getDefinition() !== '');
    }

    /**
     * @inheritDoc
     */
    public function getProcedures(): Collection
    {
        return $this->sqlSrvRepository->getProcedures()
            ->map(static fn (ProcedureDefinition $procedureDefinition) => new PgSQLProcedure($procedureDefinition->getName(), $procedureDefinition->getDefinition()));
    }

    /**
     * @inheritDoc
     */
    public function getForeignKeys(string $table): Collection
    {
        return $this->getSchemaForeignKeys($table)
            ->map(static fn (array $foreignKey) => new SQLSrvForeignKey($table, $foreignKey));
    }

    /**
     * Get user defined types from the database.
     *
     * @return \Illuminate\Support\Collection<int, string>
     */
    private function getUserDefinedTypes(): Collection
    {
        if (!$this->ranGetUserDefinedTypes) {
            $this->userDefinedTypes       = $this->sqlSrvRepository->getUserDefinedTypes();
            $this->ranGetUserDefinedTypes = true;
        }

        return $this->userDefinedTypes;
    }
}
