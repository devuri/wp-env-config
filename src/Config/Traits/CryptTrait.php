<?php

namespace DevUri\Config\Traits;

use Roots\WPConfig\Config;

trait CryptTrait
{
    /**
     * Encrypts the values of sensitive data in the given configuration array.
     *
     * This method iterates through the provided $config array, checking each key against the list
     * of sensitive keys provided by the optional $secrets parameter. If a key is found in the sensitive list,
     * the value is hashed using SHA-256 before being added to the resulting $encrypted_config array. Non-sensitive
     * values are added to the array without modification.
     *
     * @param array      $config  An associative array containing keys and their corresponding values
     * @param null|array $secrets An optional array of sensitive keys that need to be hashed (defaults to null)
     *
     * @return array $encrypted_config An associative array with sensitive values hashed
     */
    protected function encrypt_secret( array $config, array $secrets = [] ): array
    {
        $encrypted = [];

        foreach ( $config as $key => $value ) {
            if ( \in_array( $key, $secrets, true ) ) {
                $encrypted[ $key ] = hash( 'sha256', $value );
            } else {
                $encrypted[ $key ] = $value;
            }
        }

        return $encrypted;
    }

    /**
     * List of secret values that should always be encrypted.
     *
     * @return (mixed|string)[]
     *
     * @psalm-return array{0: 'DB_USER', 1: 'DB_PASSWORD', 2: 'AUTH_KEY', 3: 'SECURE_AUTH_KEY', 4: 'LOGGED_IN_KEY', 5: 'NONCE_KEY', 6: 'AUTH_SALT', 7: 'SECURE_AUTH_SALT', 8: 'LOGGED_IN_SALT', 9: 'NONCE_SALT'}
     */
    protected static function env_secrets( array $secrets = [] ): array
    {
        return array_merge(
            $secrets,
            [ 'DB_USER', 'DB_PASSWORD', 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ]
        );
    }
}
