<?php

namespace OmrGz\MigrationsGenerator\Tests\Unit\Support;

use Illuminate\Support\Facades\DB;
use OmrGz\MigrationsGenerator\Support\AssetNameQuote;
use OmrGz\MigrationsGenerator\Tests\TestCase;

class AssetNameQuoteTest extends TestCase
{
    use AssetNameQuote;

    public function testIsIdentifierQuoted(): void
    {
        $this->assertFalse($this->isIdentifierQuoted(''));
        $this->assertTrue($this->isIdentifierQuoted('`test`'));
        $this->assertTrue($this->isIdentifierQuoted('"test"'));
        $this->assertTrue($this->isIdentifierQuoted('[test]'));
    }

    public function testTrimQuotes(): void
    {
        $this->assertEquals('test', $this->trimQuotes('test'));
        $this->assertEquals('test', $this->trimQuotes('`test`'));
        $this->assertEquals('test', $this->trimQuotes('"test"'));
        $this->assertEquals('test', $this->trimQuotes('[test]'));
    }

    public function testQuoteIdentifier(): void
    {
        DB::shouldReceive('getDriverName')->andReturn('sqlsrv')->twice();
        $this->assertEquals('*', $this->quoteIdentifier('*'));
        $this->assertEquals('[test]', $this->quoteIdentifier('test'));

        DB::shouldReceive('getDriverName')->andReturn('mysql')->twice();
        $this->assertEquals('*', $this->quoteIdentifier('*'));
        $this->assertEquals('`test`', $this->quoteIdentifier('test'));

        DB::shouldReceive('getDriverName')->andReturn('sqlite')->twice();
        $this->assertEquals('*', $this->quoteIdentifier('*'));
        $this->assertEquals('"test"', $this->quoteIdentifier('test'));
    }
}
