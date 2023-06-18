<?php

namespace Urisoft\App\Console\Traits;

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
            } else {
                $alphanum_str .= $characters[ rand( 0, 61 ) ];
            }
        }

        return $alphanum_str;
    }

    protected static function htpasswd( string $username, $password ): string
    {
        $salt            = self::rand_str( 16 );
        $salted_password = '$apr1$' . $salt . '$' . md5( $salt . $password . $salt );

        return $username . ':' . $salted_password . ':' . $salt;
    }

    protected static function bcr_htpasswd( string $username, $password ): string
    {
        // Determine the number of hashing rounds (between 4 and 31)
        $cost = 10;

        // Generate a random salt using bcrypt's built-in function
        $salt = sprintf( '$2y$%02d$%s', $cost, substr( strtr( base64_encode( random_bytes( 16 ) ), '+', '.' ), 0, 22 ) );

        return $username . ':' . crypt( $password, $salt );
    }
}
