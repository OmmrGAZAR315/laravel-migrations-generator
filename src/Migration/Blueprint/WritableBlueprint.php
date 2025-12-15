<?php

namespace OmrGz\MigrationsGenerator\Migration\Blueprint;

interface WritableBlueprint
{
    /**
     * Convert the object to its string representation.
     */
    public function toString(): string;
}
