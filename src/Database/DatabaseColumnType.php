<?php

namespace OmrGz\MigrationsGenerator\Database;

use OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnType;

abstract class DatabaseColumnType
{
    /**
     * Get ColumnType from database type.
     */
    abstract public static function toColumnType(string $dbType): ColumnType;

    /**
     * @param  array<string, \OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnType>  $map
     */
    protected static function mapToColumnType(array $map, string $dbType): ColumnType
    {
        return $map[strtolower($dbType)] ?? ColumnType::STRING;
    }
}
