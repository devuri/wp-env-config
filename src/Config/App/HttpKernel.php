<?php

namespace DevUri\Config\App;

use DevUri\Config\Setup;
use Exception;

/**
 * Setup common elemnts.
 *
 * Handles global constants.
 */
class HttpKernel
{
    protected $app_path    = null;
    protected $log_file    = null;
    protected $dir_name    = null;
    protected $app_setup   = null;
    protected static $list = [];
    protected static $args = [
        'web_root'        => 'public',
        'wp_dir_path'     => 'wp',
        'wordpress'       => 'wp',
        'asset_dir'       => 'assets',
        'content_dir'     => 'content',
        'plugin_dir'      => 'plugins',
        'mu_plugin_dir'   => 'mu-plugins',
        'disable_updates' => true,
    ];

    public function __construct( string $app_path, array $args = [] )
    {
        $this->app_path = $app_path;

        $this->log_file = mb_strtolower( gmdate( 'd-m-Y' ) ) . '.log';

        $this->dir_name = [
            'month' => mb_strtolower( gmdate( 'm' ) ),
            'year'  => gmdate( 'Y' ),
        ];

        if ( ! \is_array( $args ) ) {
            throw new Exception( 'Error: args must be of type array ', 1 );
        }

        // @codingStandardsIgnoreLine
        if ( \array_key_exists( 'wordpress', $args ) ) {
            self::$args['wp_dir_path'] = $args['wordpress'];
        }

        self::$args = array_merge( self::$args, $args );
        $app_error  = static::detect_error();

        if ( \is_array( $app_error ) ) {
            throw new Exception( 'Error: ' . $app_error['message'], 2 );
        }

        $this->app_setup = Setup::init( $this->app_path );
    }

    public function get_app_path(): string
    {
        return $this->app_path;
    }

    public function get_args(): array
    {
        return self::$args;
    }

    /**
     * Setup overrides.
     *
     * @param string $file custom file example overrisdes.php
     *
     * @return void
     */
    public function overrides( ?string $file = null ): void
    {
        if ( $file ) {
            $config_override_file = $this->app_path . "/$file.php";
        } else {
            $config_override_file = $this->app_path . '/config.php';
        }

        if ( file_exists( $config_override_file ) ) {
            require_once $config_override_file;
        }
    }

    /**
     * Start the app.
     *
     * @param null|array|false|string $env_type  the enviroment type
     * @param bool                    $constants load up default constants
     */
    public function init( $env_type = null, $constants = true ): void
    {
        if ( \is_array( $env_type ) ) {
            $this->app_setup->config(
                array_merge( $this->get_default_environment(), $env_type )
            );
        } else {
            $this->app_setup->config( $this->get_default_environment() );
        }

        if ( true === $constants ) {
            $this->constants();
        }
    }

    /**
     * Defines constants.
     *
     * @param mixed $dir_path
     *
     * @psalm-suppress UndefinedConstant
     *
     * @return void
     */
    public function constants(): void
    {
        // define app_path.
        $this->define( 'APP_PATH', $this->get_app_path() );

        // define public web root dir.
        $this->define( 'PUBLIC_WEB_DIR', APP_PATH . '/' . self::$args['web_root'] );

        // wp dir path
        $this->define( 'WP_DIR_PATH', PUBLIC_WEB_DIR . '/' . self::$args['wp_dir_path'] );

        // define assets dir.
        $this->define( 'APP_ASSETS_DIR', PUBLIC_WEB_DIR . '/' . self::$args['asset_dir'] );

        // Directory PATH.
        $this->define( 'APP_CONTENT_DIR', '/' . self::$args['content_dir'] );
        $this->define( 'WP_CONTENT_DIR', PUBLIC_WEB_DIR . APP_CONTENT_DIR );

        // Content Directory.
        $this->define( 'CONTENT_DIR', APP_CONTENT_DIR );
        $this->define( 'WP_CONTENT_URL', env( 'WP_HOME' ) . CONTENT_DIR );

        // Plugins.
        $this->define( 'WP_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . self::$args['plugin_dir'] );
        $this->define( 'WP_PLUGIN_URL', env( 'WP_HOME' ) . '/' . self::$args['plugin_dir'] );

        // Must-Use Plugins.
        $this->define( 'WPMU_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . self::$args['mu_plugin_dir'] );
        $this->define( 'WPMU_PLUGIN_URL', env( 'WP_HOME' ) . '/' . self::$args['mu_plugin_dir'] );

        // Disable any kind of automatic upgrade.
        // this will be handled via composer.
        $this->define( 'AUTOMATIC_UPDATER_DISABLED', self::$args['disable_updates'] );
    }

    /**
     * Create constants.
     *
     * @param null|mixed $value
     *
     * @return void
     */
    public function define( string $const, $value = null ): void
    {
        if ( ! \defined( $const ) ) {
            \define( $const, $value );
            static::$list[ $const ] = $value;
        }
    }

    /**
     * Get list of defined constants.
     *
     * @return array constants in constants().
     */
    public function get_defined(): array
    {
        return static::$list;
    }

    /**
     * Set App defaults.
     *
     * @return array
     */
    protected function get_default_environment(): array
    {
        return [
            'environment' => $env_type,
            'error_log'   => $this->app_path . "/storage/logs/wp-errors/debug-$this->log_file",
            'debug'       => false,
            'errors'      => 'symfony',
        ];
    }

    /**
     * Detects the error causing the crash if it should be handled.
     *
     * @since WordPress 5.2.0
     *
     * @return null|array Error information returned by `error_get_last()`, or null
     *                    if none was recorded or the error should not be handled.
     *
     * @see https://github.com/WordPress/wordpress-develop/blob/6.0/src/wp-includes/class-wp-fatal-error-handler.php
     * @see https://www.php.net/manual/en/function.error-get-last.php
     */
    protected static function detect_error(): ?array
    {
        $error = error_get_last();

        if ( null === $error ) {
            return null;
        }

        return $error;
    }
}
