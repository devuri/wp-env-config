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

        return $env_array;
    }
}
