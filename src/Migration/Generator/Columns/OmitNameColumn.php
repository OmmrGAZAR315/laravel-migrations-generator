<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Columns;

use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;

class OmitNameColumn implements ColumnTypeGenerator
{
    /**
     * @inheritDoc
     */
    public function generate(Table $table, Column $column): Method
    {
        return new Method($column->getType());
    }
}
