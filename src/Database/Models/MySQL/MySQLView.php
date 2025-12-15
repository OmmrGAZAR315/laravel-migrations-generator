<?php

namespace OmrGz\MigrationsGenerator\Database\Models\MySQL;

use OmrGz\MigrationsGenerator\Database\Models\DatabaseView;

class MySQLView extends DatabaseView
{
    /**
     * @inheritDoc
     */
    public function __construct(array $view)
    {
        parent::__construct($view);

        $this->definition = 'CREATE VIEW ' . $this->quotedName . ' AS ' . $view['definition'];
    }
}
