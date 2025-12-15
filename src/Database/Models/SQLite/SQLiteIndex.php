<?php

namespace OmrGz\MigrationsGenerator\Database\Models\SQLite;

use OmrGz\MigrationsGenerator\Database\Models\DatabaseIndex;
use OmrGz\MigrationsGenerator\Enum\Migrations\Method\IndexType;

class SQLiteIndex extends DatabaseIndex
{
    /**
     * @inheritDoc
     */
    public function __construct(string $table, array $index)
    {
        parent::__construct($table, $index);

        switch ($this->type) {
            case IndexType::PRIMARY:
                // Reset name to empty to indicate use the database platform naming.
                $this->name = '';
                break;

            default:
        }
    }
}
