<?php

use Urisoft\App\Core\Plugin;
use Urisoft\App\Http\App;
use Urisoft\App\Http\Asset;

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
    function env( string $name, bool $strtolower = false )
    {
        if ( ! isset( $_ENV[ $name ] ) ) {
            return null;
        }

        if ( is_int_val( $_ENV[ $name ] ) ) {
            return (int) $_ENV[ $name ];
        }

        if ( \in_array( $_ENV[ $name ], [ 'True', 'true', 'TRUE' ], true ) ) {
            return true;
        }
        if ( \in_array( $_ENV[ $name ], [ 'False', 'false', 'FALSE' ], true ) ) {
            return false;
        }
        if ( \in_array( $_ENV[ $name ], [ 'Null', 'null', 'NULL' ], true ) ) {
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

if ( ! \function_exists( 'get_http_env' ) ) {
    /**
     * Get the current set wp app env.
     *
     * This is used in the compose mu plugin.
     *
     * @return string the current app env set, or null if not defined
     */
    function get_http_env(): ?string
    {
        if ( ! \defined( 'HTTP_ENV_CONFIG' ) ) {
            return null;
        }

        return strtoupper( HTTP_ENV_CONFIG );
    }
}

if ( ! \function_exists( 'wpc_app' ) ) {
    /**
     * Start up and set the Kernel.
     *
     * @param string $app_path The base app path. like __DIR__
     *
     * @return \Urisoft\App\Http\BaseKernel
     */
    function wpc_app( string $app_path ): Urisoft\App\Http\BaseKernel
    {
        $app = new App( $app_path );

        return $app->kernel();
    }
}

if ( ! \function_exists( 'wpc_app_config_core' ) ) {
    /**
     * Start and load core plugin.
     *
     * @return void
     */
    function wpc_app_config_core(): void
    {
        Plugin::init();
    }
}

if ( ! \function_exists( 'wpc_installed_plugins' ) ) {
    /**
     * Start and load core plugin.
     *
     * @return void
     */
	 function wpc_installed_plugins(): array
	 {
	    $plugins = get_plugins();

		$plugin_slugs = array();

		foreach ( $plugins as $key => $plugin ) {
			$slug = explode( '/', $key );

	        $plugin_slugs[] = '"wpackagist-plugin/'.$slug[0].'": "*",'; // Add the slug to the array
	    }

	    return $plugin_slugs;
	 }
}
