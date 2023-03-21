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
    protected $env_secret  = [];
    protected static $list = [];
    protected static $args = [
        'web_root'        => 'public',
        'wp_dir_path'     => 'wp',
        'wordpress'       => 'wp',
        'asset_dir'       => 'assets',
        'content_dir'     => 'content',
        'plugin_dir'      => 'plugins',
        'mu_plugin_dir'   => 'mu-plugins',
        'sqlite_dir'      => 'sqlitedb',
        'sqlite_file'     => '.sqlite-wpdatabase',
        'default_theme'   => 'twentytwentythree',
        'disable_updates' => true,
    ];

    /**
     * Setup HttpKernel.
     *
     * @param string   $app_path
     * @param string[] $args
     */
    public function __construct( string $app_path, array $args = [] )
    {
        $this->app_path = $app_path;

        $this->log_file = mb_strtolower( gmdate( 'd-m-Y' ) ) . '.log';

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

        /*
         * By default, Dotenv will stop looking for files as soon as it finds one.
         *
         * To disable this behaviour, and load all files in order,
         * we can disable the file loading with the `short_circuit` bool param.
         *
         * ['env', '.env', '.env.secure', '.env.prod', '.env.staging', '.env.dev', '.env.debug', '.env.local']
         * Since these will load in order we can control our env by simply creating file that matches
         * the enviroment on say staging we would create '.env.staging' since its the only file available
         * those will be the only values loaded.
         *
         * We can use Setup methods `get_short_circuit()` and `get_env_files()`
         * to know how the enviroment is configured.
         *
         * @link https://github.com/vlucas/phpdotenv/pull/394
         */
        $this->app_setup = Setup::init( $this->app_path );
    }

    public function get_app(): Setup
    {
        return $this->app_setup;
    }

    public function get_app_path(): string
    {
        return $this->app_path;
    }

    /**
     * Get args.
     *
     * @return string[]
     */
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

    public function set_env_secret( string $key ): void
    {
        if ( ! isset( $this->env_secret[ $key ] ) ) {
            $this->env_secret[ $key ] = $key;
        }
    }

    public function get_secret()
    {
        return array_keys( $this->env_secret );
    }

    /**
     * Start the app.
     *
     * @param null|false|string|string[] $env_type  the enviroment type
     * @param bool                       $constants load up default constants
     */
    public function init( $env_type = null, $constants = true ): void
    {
        if ( \is_array( $env_type ) ) {
            $this->app_setup->config(
                array_merge( $this->environment_args(), $env_type )
            );
        } else {
            $this->app_setup->config( $this->environment_args() );
        }

        /*
         * Adds support for `aaemnnosttv/wp-sqlite-db`
         *
         * We want to set USE_MYSQL to set MySQL as the default database.
         *
         * @link https://github.com/aaemnnosttv/wp-sqlite-db/blob/master/src/db.php
         */
        $this->define( 'USE_MYSQL', true );

        // make env available.
        $this->define( 'HTTP_ENV_CONFIG', $this->app_setup->get_environment() );

        if ( true === $constants ) {
            $this->constants();
        }
    }

    /**
     * Defines constants.
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

        // SQLite database location and filename.
        $this->define( 'DB_DIR', APP_PATH . '/' . self::$args['sqlite_dir'] );
        $this->define( 'DB_FILE', self::$args['sqlite_file'] );

        /*
         * Slug of the default theme for this installation.
         * Used as the default theme when installing new sites.
         * It will be used as the fallback if the active theme doesn't exist.
         *
         * @see WP_Theme::get_core_default_theme()
         */
        $this->define( 'WP_DEFAULT_THEME', self::$args['default_theme'] );

        // home url md5 value.
        $this->define( 'COOKIEHASH', md5( env( 'WP_HOME' ) ) );

        // Defines cookie-related override for WordPress constants.
        $this->define( 'USER_COOKIE', 'wpc_user_' . COOKIEHASH );
        $this->define( 'PASS_COOKIE', 'wpc_pass_' . COOKIEHASH );
        $this->define( 'AUTH_COOKIE', 'wpc_' . COOKIEHASH );
        $this->define( 'SECURE_AUTH_COOKIE', 'wpc_sec_' . COOKIEHASH );
        $this->define( 'LOGGED_IN_COOKIE', 'wpc_logged_in_' . COOKIEHASH );
        $this->define( 'TEST_COOKIE', md5( 'wpc_test_cookie' . env( 'WP_HOME' ) ) );
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
     * @return string[] constants in constants().
     */
    public function get_defined(): array
    {
        return static::$list;
    }

    /**
     * Set App defaults.
     *
     * @return (false|null|string)[]
     *
     * @psalm-return array{environment: null, error_log: string, debug: false, errors: 'symfony'}
     */
    protected function environment_args(): array
    {
        return [
            'environment' => null,
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
     * @return (int|string)[]|null Error information returned by `error_get_last()`, or null if none was recorded or the error should not be handled.
     *
     * @see https://github.com/WordPress/wordpress-develop/blob/6.0/src/wp-includes/class-wp-fatal-error-handler.php
     * @see https://www.php.net/manual/en/function.error-get-last.php
     *
     * @psalm-return array{type: int, message: string, file: string, line: int}|null
     */
    protected static function detect_error(): ?array
    {
        $error = error_get_last();

        if ( null === $error ) {
            return null;
        }

        return $error;
    }

    /**
     * Retrieve the current month.
     *
     * @return string The current month value (formatted as "01"-"12").
     */
    private function get_current_month()
    {
        return gmdate( 'm' );
    }

    /**
     * Retrieve the current year.
     *
     * @return string The current year value (formatted as "YYYY").
     */
    private function get_current_year()
    {
        return gmdate( 'Y' );
    }
}
