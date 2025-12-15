<?php

namespace OmrGz\MigrationsGenerator\Database;

use Illuminate\Support\Collection;
use OmrGz\MigrationsGenerator\Database\Models\SQLite\SQLiteForeignKey;
use OmrGz\MigrationsGenerator\Database\Models\SQLite\SQLiteTable;
use OmrGz\MigrationsGenerator\Database\Models\SQLite\SQLiteView;
use OmrGz\MigrationsGenerator\Schema\Models\Table;
use OmrGz\MigrationsGenerator\Schema\Models\View;

class SQLiteSchema extends DatabaseSchema
{
    /**
     * @inheritDoc
     */
    public function getTable(string $name): Table
    {
        return new SQLiteTable(
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
        return $this->getSchemaViews()->map(static fn (array $view) => new SQLiteView($view));
    }

    /**
     * @inheritDoc
     */
    public function getProcedures(): Collection
    {
        // Stored procedure does not available.
        // https://sqlite.org/forum/info/78a60bdeec7c1ee9
        return new Collection();
    }

    /**
     * @inheritDoc
     */
    public function getForeignKeys(string $table): Collection
    {
        return $this->getSchemaForeignKeys($table)
            ->map(static fn (array $foreignKey) => new SQLiteForeignKey($table, $foreignKey));
    }
}
