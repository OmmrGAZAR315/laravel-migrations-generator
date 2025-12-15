<?php

namespace OmrGz\MigrationsGenerator\Tests\Unit\Repositories\Entities\PgSQL;

use OmrGz\MigrationsGenerator\Repositories\Entities\PgSQL\IndexDefinition;
use OmrGz\MigrationsGenerator\Tests\TestCase;

class IndexDefinitionTest extends TestCase
{
    public function testIndexDefinition(): void
    {
        // Generate test
        $indexDefinition = new IndexDefinition('table', 'name', 'def');

        $this->assertEquals('table', $indexDefinition->getTableName());
        $this->assertEquals('name', $indexDefinition->getIndexName());
        $this->assertEquals('def', $indexDefinition->getIndexDef());
    }
}
