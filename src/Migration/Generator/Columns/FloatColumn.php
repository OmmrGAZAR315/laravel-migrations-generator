<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Columns;

use OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnModifier;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;

class FloatColumn implements ColumnTypeGenerator
{
    private const DEFAULT_PRECISION = 53;

    /**
     * @inheritDoc
     */
    public function generate(Table $table, Column $column): Method
    {
        $precisions = $this->getPrecisions($column);

        $method = new Method($column->getType(), $column->getName(), ...$precisions);

        if ($column->isUnsigned()) {
            $method->chain(ColumnModifier::UNSIGNED);
        }

        return $method;
    }

    /**
     * Get precision.
     * Return empty if precision = {@see self::DEFAULT_PRECISION}.
     *
     * @return array<never, never>|array<int, int|null> "[]|[precision]"
     */
    private function getPrecisions(Column $column): array
    {
        if ($column->getPrecision() === null || $column->getPrecision() === self::DEFAULT_PRECISION) {
            return [];
        }

        return [$column->getPrecision()];
    }
}
