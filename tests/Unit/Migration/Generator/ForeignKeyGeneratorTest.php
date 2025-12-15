<?php

namespace OmrGz\MigrationsGenerator\Tests\Unit\Migration\Generator;

use OmrGz\MigrationsGenerator\Database\Models\SQLite\SQLiteForeignKey;
use OmrGz\MigrationsGenerator\Enum\Migrations\Method\Foreign;
use OmrGz\MigrationsGenerator\Migration\Generator\ForeignKeyGenerator;
use OmrGz\MigrationsGenerator\Setting;
use OmrGz\MigrationsGenerator\Tests\TestCase;

class ForeignKeyGeneratorTest extends TestCase
{
    public function testGenerateDropWithNullName(): void
    {
        $setting = app(Setting::class);
        $setting->setIgnoreForeignKeyNames(false);

        $foreignKeyGenerator = app(ForeignKeyGenerator::class);

        $method = $foreignKeyGenerator->generateDrop(new SQLiteForeignKey('table', [
            'name'            => null,
            'columns'         => ['column'],
            'foreign_schema'  => null,
            'foreign_table'   => 'foreign_table',
            'foreign_columns' => ['foreign_column'],
            'on_update'       => 'on_update',
            'on_delete'       => 'on_delete',
        ]));

        $this->assertSame($method->getName(), Foreign::DROP_FOREIGN);
        $this->assertEmpty($method->getValues());
    }
}
