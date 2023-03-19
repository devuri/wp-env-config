<?php

namespace DevUri\Config\App\Console\Traits;

trait Env
{
    public function get_env_array( string $env_file ): ?array
    {
        if ( ! $this->filesystem->exists( $env_file ) ) {
            return null;
        }

        $env_contents = file_get_contents( $env_file );

        $lines = explode( "\n", $env_contents );

        $env_array = [];

        foreach ( $lines as $line ) {
            if ( '' === $line || '#' === substr( $line, 0, 1 ) ) {
                continue;
            }
            $equals_pos = strpos( $line, '=' );
            if ( false !== $equals_pos ) {
                $key   = substr( $line, 0, $equals_pos );
                $value = substr( $line, $equals_pos + 1 );
                if ( '"' === substr( $value, 0, 1 ) && '"' === substr( $value, -1 ) ) {
                    $value = substr( $value, 1, -1 );
                } elseif ( "'" === substr( $value, 0, 1 ) && "'" === substr( $value, -1 ) ) {
                    $value = substr( $value, 1, -1 );
                }
                $env_array[ $key ] = $value;
            }
        }

        return $this->get_env( $env_array );
    }

    /**
     * Filter config output.
     *
     * @param string[] $config
     *
     * @return string[]
     */
    protected function get_env( array $config ): array
    {
        $env_vars = [];
        foreach ( $config as $key => $value ) {
            if ( \in_array( $key, $this->env_secret(), true ) ) {
                $env_vars[ $key ] = hash( 'sha256', $value );
            } else {
                $env_vars[ $key ] = $value;
            }
        }

        return $env_vars;
    }

    /**
     * @return (mixed|string)[]
     *
     * @psalm-return array{0: 'DB_USER', 1: 'DB_PASSWORD', 2: 'AUTH_KEY', 3: 'SECURE_AUTH_KEY', 4: 'LOGGED_IN_KEY', 5: 'NONCE_KEY', 6: 'AUTH_SALT', 7: 'SECURE_AUTH_SALT', 8: 'LOGGED_IN_SALT', 9: 'NONCE_SALT'}
     */
    protected function env_secret( array $secrets = [] ): array
    {
        return array_merge(
            $secrets,
            [ 'DB_USER', 'DB_PASSWORD', 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ]
        );
    }

    /**
     * @return string[]
     *
     * @psalm-return array<string, string>
     */
    protected function saltToArray(): array
    {
        $salts   = file_get_contents( 'https://api.wordpress.org/secret-key/1.1/salt/' );
        $string  = str_replace( [ "\r", "\n" ], '', $salts );
        $pattern = "/define\('([^']*)',\s*'([^']*)'\);/";
        $result  = [];
        if ( preg_match_all( $pattern, $string, $matches, PREG_SET_ORDER ) ) {
            foreach ( $matches as $match ) {
                $key            = $match[1];
                $val            = $match[2];
                $result[ $key ] = $val;
            }
        } else {
            // Handle invalid input
            $result = [ 'error' => 'Invalid input string' ];
        }

        return $result;
    }

    protected static function saltContent( object $salt ): string
    {
        return <<<END

		AUTH_KEY='$salt->AUTH_KEY'
		SECURE_AUTH_KEY='$salt->SECURE_AUTH_KEY'
		LOGGED_IN_KEY='$salt->LOGGED_IN_KEY'
		NONCE_KEY='$salt->NONCE_KEY'
		AUTH_SALT='$salt->AUTH_SALT'
		SECURE_AUTH_SALT='$salt->SECURE_AUTH_SALT'
		LOGGED_IN_SALT='$salt->LOGGED_IN_SALT'
		NONCE_SALT='$salt->NONCE_SALT'

		END;
    }
}
