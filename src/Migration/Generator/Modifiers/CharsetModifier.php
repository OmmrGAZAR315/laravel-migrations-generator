<?php

namespace OmrGz\MigrationsGenerator\Migration\Generator\Modifiers;

use Illuminate\Support\Str;
use OmrGz\MigrationsGenerator\Enum\Migrations\Method\ColumnModifier;
use OmrGz\MigrationsGenerator\Migration\Blueprint\Method;
use OmrGz\MigrationsGenerator\Schema\Models\Column;
use OmrGz\MigrationsGenerator\Schema\Models\Table;
use OmrGz\MigrationsGenerator\Setting;

class CharsetModifier implements Modifier
{
    public function __construct(private readonly Setting $setting)
    {
    }

    /**
     * @inheritDoc
     */
    public function chain(Method $method, Table $table, Column $column, mixed ...$args): Method
    {
        if (!$this->setting->isUseDBCollation()) {
            return $method;
        }

        // Collation is not set in PgSQL
        $tableCollation = $table->getCollation() ?? '';
        $tableCharset   = Str::before($tableCollation, '_');

        $charset = $column->getCharset();

        if ($charset !== null && $charset !== $tableCharset) {
            $method->chain(ColumnModifier::CHARSET, $charset);
        }

        return $method;
    }
}
