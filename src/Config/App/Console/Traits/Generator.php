<?php

namespace DevUri\Config\App\Console\Traits;

use Devuri\UUIDGenerator\UUIDGenerator;

trait Generator
{
    public static function uuid(): string
    {
        return ( new UUIDGenerator() )->generateUUID();
    }
}
