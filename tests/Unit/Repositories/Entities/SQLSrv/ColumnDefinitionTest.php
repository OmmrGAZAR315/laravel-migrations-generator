<?php

namespace OmrGz\MigrationsGenerator\Tests\Unit\Repositories\Entities\SQLSrv;

use OmrGz\MigrationsGenerator\Repositories\Entities\SQLSrv\ColumnDefinition;
use OmrGz\MigrationsGenerator\Tests\TestCase;

class ColumnDefinitionTest extends TestCase
{
    public function testColumnDefinition(): void
    {
        $columnDefinition = new ColumnDefinition((object) [
            'Name'          => 'id',
            'type'          => 'int',
            'length'        => 11,
            'notnull'       => true,
            'default'       => 'default',
            'scale'         => 0,
            'precision'     => 10,
            'autoincrement' => true,
            'collation'     => 'collation',
            'comment'       => 'Primary key',
        ]);

        $this->assertEquals('id', $columnDefinition->getName());
        $this->assertEquals('int', $columnDefinition->getType());
        $this->assertEquals(11, $columnDefinition->getLength());
        $this->assertTrue($columnDefinition->isNotnull());
        $this->assertEquals('default', $columnDefinition->getDefault());
        $this->assertEquals(0, $columnDefinition->getScale());
        $this->assertEquals(10, $columnDefinition->getPrecision());
        $this->assertTrue($columnDefinition->isAutoincrement());
        $this->assertEquals('collation', $columnDefinition->getCollation());
        $this->assertEquals('Primary key', $columnDefinition->getComment());
    }
}
