<?php

namespace DevUri\Config\App\Traits;

use Env\Env;

trait KernelTrait
{
    /**
     * Gets the value of an environment variable.
     *
     * @return mixed
     */
    public static function env( string $name )
    {
        return Env::get( $name );
    }
}
