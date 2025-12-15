<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator;

use Illuminate\Support\Collection;
use OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnType;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Migration\Generator\Modifiers\CharsetModifier;
use OmrGz\MigrationsGenerator\Migration\Generator\Modifiers\CollationModifier;
use OmrGz\MigrationsGenerator\Migration\Generator\Modifiers\CommentModifier;
use OmrGz\MigrationsGenerator\Migration\Generator\Modifiers\DefaultModifier;
use OmrGz\MigrationsGenerator\Migration\Generator\Modifiers\IndexModifier;
use OmrGz\MigrationsGenerator\Migration\Generator\Modifiers\NullableModifier;
use OmrGz\MigrationsGenerator\Migration\Generator\Modifiers\StoredAsModifier;
use OmrGz\MigrationsGenerator\Migration\Generator\Modifiers\VirtualAsModifier;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;

class ColumnGenerator
{
    public function __construct(
        private readonly CharsetModifier $charsetModifier,
        private readonly CollationModifier $collationModifier,
        private readonly CommentModifier $commentModifier,
        private readonly DefaultModifier $defaultModifier,
        private readonly IndexModifier $indexModifier,
        private readonly NullableModifier $nullableModifier,
        private readonly StoredAsModifier $storedAsModifier,
        private readonly VirtualAsModifier $virtualAsModifier,
    ) {
    }

    /**
     * @param  \Illuminate\Support\Collection<string, \OmrGz\MigrationsGenerator\Schema\Models\Index>  $chainableIndexes
     */
    public function generate(Table $table, Column $column, Collection $chainableIndexes): Method
    {
        $method = $this->createMethodFromColumn($table, $column);

        $method = $this->charsetModifier->chain($method, $table, $column);
        $method = $this->collationModifier->chain($method, $table, $column);
        $method = $this->nullableModifier->chain($method, $table, $column);
        $method = $this->defaultModifier->chain($method, $table, $column);
        $method = $this->virtualAsModifier->chain($method, $table, $column);
        $method = $this->storedAsModifier->chain($method, $table, $column);
        $method = $this->indexModifier->chain($method, $table, $column, $chainableIndexes);
        $method = $this->commentModifier->chain($method, $table, $column);

        return $method;
    }

    private function createMethodFromColumn(Table $table, Column $column): Method
    {
        /** @var \OmrGz\MigrationsGenerator\Migration\Generator\Columns\ColumnTypeGenerator $generator */
        $generator = app(ColumnType::class . '\\' . $column->getType()->name);
        return $generator->generate($table, $column);
    }
}
