<?php

namespace OmrGz\MigrationsGenerator\Migration\Blueprint;

use OmrGz\MigrationsGenerator\Enum\Migrations\Method\DBBuilder;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Support\MethodStringHelper;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Support\Stringable;

/**
 * Create migration lines with `DB::unprepared`.
 *
 * eg 1:
 * ```
 * DB::statement("CREATE PROCEDURE myProcedure() BEGIN SELECT * from table END");
 * ```
 *
 * eg 2:
 * ```
 * DB::connection('sqlite')->statement("CREATE PROCEDURE myProcedure() BEGIN SELECT * from table END");
 * ```
 */
class DBUnpreparedBlueprint implements WritableBlueprint
{
    use Stringable;
    use MethodStringHelper;

    /**
     * DBStatementBlueprint constructor.
     *
     * @param  string  $sql  The SQL statement.
     */
    public function __construct(private readonly string $sql)
    {
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        $method = $this->connection('DB', DBBuilder::UNPREPARED);
        $query  = $this->escapeDoubleQuote($this->sql);
        return "$method(\"$query\");";
    }
}
