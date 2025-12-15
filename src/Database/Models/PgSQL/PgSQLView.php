<?php

namespace OmrGz\MigrationsGenerator\Database\Models\PgSQL;

use OmrGz\MigrationsGenerator\Database\Models\DatabaseView;

class PgSQLView extends DatabaseView
{
    /**
     * @inheritDoc
     */
    public function __construct(array $view)
    {
        parent::__construct($view);

        $this->definition = 'CREATE VIEW ' . $this->quotedName . ' AS ' . trim($view['definition']);
    }
}
