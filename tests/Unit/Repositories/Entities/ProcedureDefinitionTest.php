<?php

namespace OmrGz\MigrationsGenerator\Tests\Unit\Repositories\Entities;

use OmrGz\MigrationsGenerator\Repositories\Entities\ProcedureDefinition;
use OmrGz\MigrationsGenerator\Tests\TestCase;

class ProcedureDefinitionTest extends TestCase
{
    public function testProcedureDefinition(): void
    {
        $procedureDefinition = new ProcedureDefinition('name', 'definition');

        $this->assertEquals('name', $procedureDefinition->getName());
        $this->assertEquals('definition', $procedureDefinition->getDefinition());
    }
}
