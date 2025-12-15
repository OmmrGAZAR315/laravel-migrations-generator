<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Columns;

use OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnModifier;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;

class DatetimeColumn implements ColumnTypeGenerator
{
    private const DEFAULT_PRECISION = 0;

    /**
     * @inheritDoc
     */
    public function generate(Table $table, Column $column): Method
    {
        $method = $this->makeMethod($column);

        if ($column->isOnUpdateCurrentTimestamp()) {
            $method->chain(ColumnModifier::USE_CURRENT_ON_UPDATE);
        }

        return $method;
    }

    /**
     * Create a Method instance.
     */
    private function makeMethod(Column $column): Method
    {
        $length = $column->getLength() === self::DEFAULT_PRECISION ? null : $column->getLength();

        if ($length !== null) {
            return new Method($column->getType(), $column->getName(), $length);
        }

        return new Method($column->getType(), $column->getName());
    }
}
