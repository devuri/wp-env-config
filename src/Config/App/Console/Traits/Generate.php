<?php

namespace DevUri\Config\App\Console\Traits;

use Devuri\UUIDGenerator\UUIDGenerator;

trait Generate
{
    public static function uuid(): string
    {
        return ( new UUIDGenerator() )->generateUUID();
    }

    /**
     * Generate a random alphanumeric alphanum_str of a specified length, starting with a letter.
     *
     * @param int $length The length of the alphanum_str to generate.
     *
     * @return string The generated string.
     */
    protected static function rand_str( int $length = 8 ): string
    {
        $characters   = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $alphanum_str = '';
        for ( $i = 0; $i < $length; $i++ ) {
            if ( 0 === $i ) {
                $alphanum_str .= $characters[ rand( 0, 51 ) ];
				// First character must be a letter
            } else {
                $alphanum_str .= $characters[ rand( 0, 61 ) ];
                // Any character
            }
        }

        return $alphanum_str;
    }
}