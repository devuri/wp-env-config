<?php

namespace DevUri\Config\App;

use DevUri\Config\Setup;

use function Env\env;

/**
 * Setup common elemnts.
 *
 * Handles global constants.
 */
class HttpKernel
{
    protected $app_path = null;

    public function __construct( string $app_path, $args = [] )
    {
        $this->app_path = $app_path;
    }

    /**
     * Start the app.
     *
     * @param string $env_type  the enviroment type
     * @param bool   $constants load up default constants
     */
    public function init( string $env_type = 'production', $constants = true ): void
    {
        Setup::init( $this->app_path )->config( $env_type );

        if ( true === $constants ) {
            $this->constants();
        }
    }

    /**
     * Defines constants.
     *
     * @param mixed $dir_path
     *
     * @return void
     */
    public function constants(): void
    {
        // define app_path.
        \define( 'APP_PATH', $this->app_path );

        // define public web root dir.
        \define( 'PUBLIC_WEB_DIR', APP_PATH . '/public' );

        // define assets dir.
        \define( 'APP_ASSETS_DIR', PUBLIC_WEB_DIR . '/assets' );

        // Disable any kind of automatic upgrade.
        // this will be handled via composer.
        \define( 'AUTOMATIC_UPDATER_DISABLED', true );

        // Directory PATH.
        \define( 'APP_CONTENT_DIR', '/content' );
        \define( 'WP_CONTENT_DIR', PUBLIC_WEB_DIR . APP_CONTENT_DIR );

        // Content Directory.
        \define( 'CONTENT_DIR', APP_CONTENT_DIR );
        \define( 'WP_CONTENT_URL', env( 'WP_HOME' ) . CONTENT_DIR );

        // Plugins.
        \define( 'WP_PLUGIN_DIR', PUBLIC_WEB_DIR . '/plugins' );
        \define( 'WP_PLUGIN_URL', env( 'WP_HOME' ) . '/plugins' );

        // Must-Use Plugins.
        \define( 'WPMU_PLUGIN_DIR', PUBLIC_WEB_DIR . '/mu-plugins' );
        \define( 'WPMU_PLUGIN_URL', env( 'WP_HOME' ) . '/mu-plugins' );
    }
}
