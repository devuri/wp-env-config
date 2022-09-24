<?php

use DevUri\Config\App\Asset;
use Env\Env;

if ( ! \function_exists( 'asset' ) ) {
    /**
     * The Asset url.
     *
     * You can configure the asset URL by setting the ASSET_URL in your .env
     * Or optionally in the main config file.
     *
     * @param string     $asset path to the asset like: "/images/thing.png"
     * @param null|mixed $path
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
     * @see https://github.com/oscarotero/env
     */
    function env( string $name ): string
    {
        return Env::get( $name );
    }
}
