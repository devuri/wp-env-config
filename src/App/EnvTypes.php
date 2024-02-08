<?php

namespace Urisoft\App;

class EnvTypes
{
    protected static $env_types = [ 'secure', 'sec', 'production', 'prod', 'staging', 'development', 'dev', 'debug', 'deb', 'local' ];

    /**
     * Checks if the given type is a valid environment type.
     *
     * @param string $type The environment type to check.
     *
     * @return bool True if valid, false otherwise.
     */
    public static function is_valid( string $type ): bool
    {
        return \in_array( $type, self::$env_types, true );
    }

    /**
     * Get all environment types.
     *
     * @return array The list of environment types.
     */
    public static function get(): array
    {
        return self::$env_types;
    }
}
