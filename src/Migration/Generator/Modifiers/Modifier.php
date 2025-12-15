<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Modifiers;

use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;

interface Modifier
{
    /**
     * Chain column modifier.
     */
    public function chain(Method $method, Table $table, Column $column, mixed ...$args): Method;
}
