<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Modifiers;

use OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnModifier;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;
use OmrGz\MigrationsGenerator\Setting;

class CollationModifier implements Modifier
{
    public function __construct(private readonly Setting $setting)
    {
    }

    /**
     * @inheritDoc
     */
    public function chain(Method $method, Table $table, Column $column, mixed ...$args): Method
    {
        if (!$this->setting->isUseDBCollation()) {
            return $method;
        }

        // Collation is not set in PgSQL
        $tableCollation = $table->getCollation();

        $collation = $column->getCollation();

        if ($collation !== null && $collation !== $tableCollation) {
            $method->chain(ColumnModifier::COLLATION, $collation);
        }

        return $method;
    }
}
