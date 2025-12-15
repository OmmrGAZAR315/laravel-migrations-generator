<?php

namespace OmrGz\MigrationsGenerator\Database\Models\SQLite;

use OmrGz\MigrationsGenerator\Database\Models\DatabaseView;

class SQLiteView extends DatabaseView
{
    /**
     * @inheritDoc
     */
    public function __construct(array $view)
    {
        parent::__construct($view);

        $this->definition = $view['definition'];
    }
}
