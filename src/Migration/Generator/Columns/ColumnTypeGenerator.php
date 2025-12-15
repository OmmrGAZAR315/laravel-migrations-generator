<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Columns;

use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;

interface ColumnTypeGenerator
{
    /**
     * Generate the migration column method.
     */
    public function generate(Table $table, Column $column): Method;
}
