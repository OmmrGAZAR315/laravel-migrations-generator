<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Modifiers;

use OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnModifier;
use OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnType;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;

class NullableModifier implements Modifier
{
    /**
     * @inheritDoc
     */
    public function chain(Method $method, Table $table, Column $column, mixed ...$args): Method
    {
        if ($column->isNotNull()) {
            if ($this->shouldAddNotNullModifier($column->getType())) {
                $method->chain(ColumnModifier::NULLABLE, false);
            }

            return $method;
        }

        if ($this->shouldAddNullableModifier($column->getType())) {
            $method->chain(ColumnModifier::NULLABLE);
        }

        return $method;
    }

    /**
     * Check if the column type should add nullable.
     * "softDeletes", "softDeletesTz", "rememberToken", and "timestamps" are skipped.
     */
    private function shouldAddNullableModifier(ColumnType $columnType): bool
    {
        return !in_array(
            $columnType,
            [
                ColumnType::SOFT_DELETES,
                ColumnType::SOFT_DELETES_TZ,
                ColumnType::REMEMBER_TOKEN,
                ColumnType::TIMESTAMPS,
            ],
        );
    }

    /**
     * Check if the column type should add nullable(false).
     * Only check "softDeletes", "softDeletesTz", and "rememberToken".
     */
    private function shouldAddNotNullModifier(ColumnType $columnType): bool
    {
        return in_array(
            $columnType,
            [
                ColumnType::SOFT_DELETES,
                ColumnType::SOFT_DELETES_TZ,
                ColumnType::REMEMBER_TOKEN,
            ],
        );
    }
}
