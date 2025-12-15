<?php

namespace OmrGz\MigrationsGenerator\Tests\Unit\Support;

use OmrGz\MigrationsGenerator\Support\Regex;
use OmrGz\MigrationsGenerator\Tests\TestCase;

class RegexTest extends TestCase
{
    public function testGetTextBetweenFirst(): void
    {
        $this->assertEquals('this is (hot', Regex::getTextBetweenFirst('Hello, (this is (hot) chocolate).'));
        $this->assertEquals('this is [hot', Regex::getTextBetweenFirst('Hello, [this is [hot] chocolate].', '\[', '\]'));
        $this->assertNull(Regex::getTextBetweenFirst('Hello, (this is (hot) chocolate).', '\[', '\]'));
    }

    public function testGetTextBetween(): void
    {
        $this->assertEquals('this is (hot) chocolate', Regex::getTextBetween('Hello, (this is (hot) chocolate).'));
        $this->assertEquals('this is [hot] chocolate', Regex::getTextBetween('Hello, [this is [hot] chocolate].', '\[', '\]'));
        $this->assertNull(Regex::getTextBetween('Hello, (this is (hot) chocolate).', '\[', '\]'));
    }

    public function testGetTextBetweenAll(): void
    {
        $this->assertEquals(['this', 'is', 'hot'], Regex::getTextBetweenAll('Hello, (this) (is) (hot) chocolate).'));
        $this->assertEquals(['this', 'is', 'hot'], Regex::getTextBetweenAll('Hello, [this] [is] [hot] chocolate).', '\[', '\]'));
        $this->assertNull(Regex::getTextBetweenAll('Hello, (this) (is) (hot) chocolate).', '\[', '\]'));
    }
}
