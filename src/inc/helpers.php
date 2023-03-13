<?php

use DevUri\Config\App\Asset;

if ( ! \function_exists( 'asset' ) ) {
    /**
     * The Asset url.
     *
     * You can configure the asset URL by setting the ASSET_URL in your .env
     * Or optionally in the main config file.
     *
     * @param string      $asset path to the asset like: "/images/thing.png"
     * @param null|string $path
     *
     * @return string
     */
    function asset( string $asset, ?string $path = null ): string
    {
        return Asset::url( $asset, $path );
    }
}

if ( ! \function_exists( 'asset_url' ) ) {
    /**
     * The Asset url only.
     *
     * @param null|string $path
     *
     * @return string
     */
    function asset_url( ?string $path = null ): string
    {
        return Asset::url( '/', $path );
    }
}

if ( ! \function_exists( 'env' ) ) {
    /**
     * Get the value of an environment variable.
     *
     * @param string $name the environment variable name.
     *
     * @return mixed
     */
    function env( string $name, bool $strtolower = true )
    {
        if ( ! isset( $_ENV[ $name ] ) ) {
            return null;
        }

        if ( is_int_val( $_ENV[ $name ] ) ) {
            return (int) $_ENV[ $name ];
        }

        switch ( strtolower( $_ENV[ $name ] ) ) {
            case 'true':
                return true;

            case 'false':
                return false;

            case 'null':
                return '';
        }

        if ( $strtolower ) {
            return strtolower( $_ENV[ $name ] );
        }

        return $_ENV[ $name ];
    }
}// end if

if ( ! \function_exists( 'is_int_val' ) ) {
    /**
     * Check if a string is an integer value.
     *
     * @param string $str The string to check.
     *
     * @return bool Returns true if the string is an integer value, and false otherwise.
     */
    function is_int_val( string $str )
    {
        return is_numeric( $str ) && \intval( $str ) == $str;
    }
}
