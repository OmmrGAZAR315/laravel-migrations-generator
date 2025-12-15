<?php

namespace OmrGz\MigrationsGenerator\Database\Models\SQLite;

use OmrGz\MigrationsGenerator\Database\Models\DatabaseTable;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Index;
use OmrGz\MigrationsGenerator\Schema\Models\UDTColumn;

class SQLiteTable extends DatabaseTable
{
    /**
     * @inheritDoc
     */
    protected function makeColumn(string $table, array $column): Column
    {
        return new SQLiteColumn($table, $column);
    }

    /**
     * @inheritDoc
     */
    protected function makeUDTColumn(string $table, array $column): UDTColumn
    {
        return new SQLiteUDTColumn($table, $column);
    }

    /**
     * @inheritDoc
     */
    protected function makeIndex(string $table, array $index, bool $hasUDTColumn): Index
    {
        return new SQLiteIndex($table, $index);
    }
}
