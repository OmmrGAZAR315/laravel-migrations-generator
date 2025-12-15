<?php

namespace OmrGz\MigrationsGenerator\Database\Models\MySQL;

use OmrGz\MigrationsGenerator\Database\Models\DatabaseTable;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Index;
use OmrGz\MigrationsGenerator\Schema\Models\UDTColumn;

class MySQLTable extends DatabaseTable
{
    /**
     * @inheritDoc
     */
    protected function makeColumn(string $table, array $column): Column
    {
        return new MySQLColumn($table, $column);
    }

    /**
     * @inheritDoc
     */
    protected function makeUDTColumn(string $table, array $column): UDTColumn
    {
        return new MySQLUDTColumn($table, $column);
    }

    /**
     * @inheritDoc
     */
    protected function makeIndex(string $table, array $index, bool $hasUDTColumn): Index
    {
        return new MySQLIndex($table, $index);
    }
}
