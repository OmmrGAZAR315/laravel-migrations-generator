<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Modifiers;

use OmrGz\MigrationsGenerator\Enum\Migrations\Method\IndexType;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;
use OmrGz\MigrationsGenerator\Support\IndexNameHelper;

class IndexModifier implements Modifier
{
    public function __construct(private readonly IndexNameHelper $indexNameHelper)
    {
    }

    /**
     * @inheritDoc
     */
    public function chain(Method $method, Table $table, Column $column, mixed ...$args): Method
    {
        /** @var \Illuminate\Support\Collection<string, \OmrGz\MigrationsGenerator\Schema\Models\Index> $chainableIndexes Key is column name. */
        $chainableIndexes = $args[0];

        if (!$chainableIndexes->has($column->getName())) {
            return $method;
        }

        /** @var \OmrGz\MigrationsGenerator\Schema\Models\Index $index */
        $index = $chainableIndexes->get($column->getName());

        // "increment" will add primary key by default. No need explicitly declare "primary" index here.
        if ($column->isAutoincrement() && $index->getType() === IndexType::PRIMARY) {
            return $method;
        }

        $indexType = $this->adjustIndexType($index->getType());

        if ($this->indexNameHelper->shouldSkipName($table->getName(), $index)) {
            $method->chain($indexType);
            return $method;
        }

        $method->chain($indexType, $index->getName());
        return $method;
    }

    /**
     * FULLTEXT index method name is `fullText` (camelCase) but changed to `fulltext` (lowercase)
     * when used for column chaining.
     */
    private function adjustIndexType(IndexType $indexType): IndexType
    {
        if ($indexType === IndexType::FULLTEXT) {
            return IndexType::FULLTEXT_CHAIN;
        }

        return $indexType;
    }
}
