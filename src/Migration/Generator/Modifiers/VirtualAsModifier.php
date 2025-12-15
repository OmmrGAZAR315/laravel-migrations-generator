<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Modifiers;

use OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnModifier;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;

class VirtualAsModifier implements Modifier
{
    /**
     * @inheritDoc
     */
    public function chain(Method $method, Table $table, Column $column, mixed ...$args): Method
    {
        if ($column->getVirtualDefinition() !== null) {
            $method->chain(ColumnModifier::VIRTUAL_AS, $column->getVirtualDefinition());
        }

        return $method;
    }
}
