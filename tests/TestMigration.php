<?php

namespace OmrGz\MigrationsGenerator\Tests;

use Illuminate\Database\Migrations\Migration;
use OmrGz\MigrationsGenerator\Support\AssetNameQuote;

abstract class TestMigration extends Migration
{
    use AssetNameQuote;
}
