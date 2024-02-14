<?php

namespace Urisoft\App\Http;

use function defined;

use Exception;
use InvalidArgumentException;
use Urisoft\App\EnvTypes;
use Urisoft\App\Setup;
use Urisoft\App\Traits\ConstantBuilderTrait;
use Urisoft\App\Traits\ConstantTrait;

/**
 * Setup common elements.
 *
 * Handles global constants.
 */
class BaseKernel
{
    use ConstantBuilderTrait;
    use ConstantTrait;

    protected $app_path;
    protected $log_file;
    protected $dir_name;
    protected $config_file;
    protected $env_secret  = [];
    protected static $list = [];
    protected $app_setup;
    protected $tenant_id;
    protected $config_dir;

    /**
     * Setup BaseKernel.
     *
     * @param string     $app_path
     * @param string[]   $args
     * @param null|Setup $setup
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __construct( string $app_path, array $args = [], ?Setup $setup = null )
    {
        $this->app_path   = $app_path;
        $this->config_dir = SITE_CONFIG_DIR;

        if ( ! \is_array( $args ) ) {
            throw new InvalidArgumentException( 'Error: args must be of type array', 1 );
        }

        if ( \array_key_exists( 'theme_dir', $args ) ) {
            $this->args['templates_dir'] = $args['theme_dir'];
        }

        // @codingStandardsIgnoreLine
        if (\array_key_exists('wordpress', $args)) {
            $this->args['wp_dir_path'] = $args['wordpress'];
        }

        $this->config_file = $this->args['config_file'];

        $this->args = array_merge( $this->args, $args );

        $this->tenant_id = env_tenant_id();

        /*
         * By default, Dotenv will stop looking for files as soon as it finds one.
         *
         * To disable this behaviour, and load all files in order,
         * we can disable the file loading with the `short_circuit` bool param.
         *
         * ['env', '.env', '.env.secure', '.env.prod', '.env.staging', '.env.dev', '.env.debug', '.env.local']
         * Since these will load in order we can control our env by simply creating file that matches
         * the environment on say staging we would create '.env.staging' since it's the only file available
         * those will be the only values loaded.
         *
         * We can use Setup methods `get_short_circuit()` and `get_env_files()`
         * to know how the enviroment is configured.
         *
         * @link https://github.com/vlucas/phpdotenv/pull/394
         */
        if ( \is_null( $setup ) ) {
            $this->app_setup = Setup::init( $this->app_path );
        } else {
            $this->app_setup = $setup;
        }
    }

    public function get_app(): Setup
    {
        return $this->app_setup;
    }

    public function get_app_security(): array
    {
        return $this->args['security'];
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
        return $this->args;
    }

    /**
     * Get app config args.
     *
     * @return string[]
     */
    public function get_app_config(): array
    {
        return $this->get_args();
    }

    /**
     * Loads tenant-specific or default configuration based on the application's multi-tenant status.
     *
     * This function first checks for a tenant-specific configuration file in multi-tenant mode. If not found,
     * or if not in multi-tenant mode, it falls back to the default configuration file. The configuration is applied
     * by requiring the respective file, if it exists.
     *
     * @return void
     */
    public function overrides(): void
    {
        $config_override_file = null;

        // Check if multi-tenant mode is enabled and a tenant ID is set
        if ( $this->is_multitenant_app() && ! empty( $this->tenant_id ) ) {
            $tenant_config_file = $this->app_path . "/{$this->config_dir}/{$this->tenant_id}/{$this->config_file}.php";

            // Check if the tenant-specific config file exists
            if ( file_exists( $tenant_config_file ) ) {
                $config_override_file = $tenant_config_file;
            }
        }

        // If no tenant-specific file found, use the default config file
        if ( empty( $config_override_file ) ) {
            $default_config_file = $this->app_path . "/{$this->config_file}.php";
            if ( file_exists( $default_config_file ) ) {
                $config_override_file = $default_config_file;
            }
        }

        // If a valid config override file is found, require it
        if ( ! empty( $config_override_file ) ) {
            require_once $config_override_file;
        }
    }

    public function set_env_secret( string $key ): void
    {
        if ( ! isset( $this->env_secret[ $key ] ) ) {
            $this->env_secret[ $key ] = $key;
        }
    }

    /**
     * @return (int|string)[]
     *
     * @psalm-return list<array-key>
     */
    public function get_secret(): array
    {
        return array_keys( $this->env_secret );
    }

    /**
     * Start the app.
     *
     * @param null|false|string|string[] $env_type  the environment type
     * @param bool                       $constants load up default constants
     */
    public function init( $env_type = null, bool $constants = true ): void
    {
        if ( \defined( 'WP_ENVIRONMENT_TYPE' ) && EnvTypes::is_valid( (string) WP_ENVIRONMENT_TYPE ) ) {
            $env_type = [ 'environment' => WP_ENVIRONMENT_TYPE ];
        }

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
            $this->set_config_constants();
        }

        if ( file_exists( PUBLIC_WEB_DIR . '/.maintenance' ) ) {
            wp_terminate( self::get_maintenance_message(), 503 );
        }

        if ( file_exists( $this->app_setup->get_current_path() . '/.maintenance' ) ) {
            wp_terminate( self::get_maintenance_message(), 503 );
        }

        if ( $this->wp_is_not_installed() && \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'secure', 'sec', 'production', 'prod' ], true ) ) {
            wp_terminate( 'wp is not installed change enviroment to run installer' );
        }
    }

    /**
     * Get list of defined constants.
     *
     * @return string[] constants in set_config_constants().
     */
    public function get_defined(): array
    {
        return static::$constants;
    }

    /**
     * Retrieve server environment variables and obfuscate sensitive data.
     *
     * This method retrieves the server environment variables (usually stored in $_ENV). If the application
     * is not in debug mode, it returns null. In debug mode, it collects the environment variables, obfuscates
     * any sensitive data within them using the 'encrypt_secret' method, and returns the resulting array.
     *
     * @return null|array An array of server environment variables with sensitive data obfuscated in debug mode,
     *                    or null if not in debug mode.
     */
    public function get_server_env(): ?array
    {
        if ( ! self::is_debug_mode() ) {
            return null;
        }

        return self::encrypt_secret( $_ENV, self::env_secrets() );
    }

    /**
     * Retrieve user-defined constants and obfuscate sensitive data.
     *
     * This method retrieves an array of user-defined constants. If the application is not in debug mode,
     * it returns null. In debug mode, it collects user-defined constants, obfuscates any sensitive data
     * within them using the 'encrypt_secret' method, and returns the resulting array.
     *
     * @return null|array An array of user-defined constants with sensitive data obfuscated in debug mode,
     *                    or null if not in debug mode.
     */
    public function get_user_constants(): ?array
    {
        if ( ! self::is_debug_mode() ) {
            return null;
        }

        $user_constants = get_defined_constants( true )['user'];

        return self::encrypt_secret( $user_constants, self::env_secrets() );
    }

    protected function wp_is_not_installed(): bool
    {
        if ( \defined( 'WP_INSTALLING' ) && true === WP_INSTALLING ) {
            return true;
        }

        return false;
    }

    /**
     * Generate environment-specific arguments, including customized error log paths.
     *
     * This method constructs the arguments needed for the environment setup,
     * particularly focusing on the error logging mechanism. It differentiates
     * the error log directory based on the presence of a tenant ID, allowing
     * for tenant-specific error logging.
     *
     * @return array The array of environment-specific arguments.
     */
    protected function environment_args(): array
    {
        $this->log_file = mb_strtolower( gmdate( 'm-d-Y' ) ) . '.log';

        // Determine the error logs directory path based on tenant ID presence.
        $error_logs_dir_suffix = $this->tenant_id ? "/{$this->tenant_id}/" : '/';
        $error_logs_dir        = $this->app_path . '/storage/logs/wp-errors' . $error_logs_dir_suffix . "debug-{$this->log_file}";

        return [
            'environment' => null,
            'error_log'   => $error_logs_dir,
            'debug'       => false,
            'errors'      => $this->args['error_handler'],
        ];
    }

    /**
     * Initialize the HTTP client.
     *
     * This method leverages the HttpFactory to create and return an instance
     * of the AppHostManager, which is used to manage HTTP requests and responses.
     *
     * @return AppHostManager An instance of AppHostManager for HTTP operations.
     */
    protected static function http(): AppHostManager
    {
        return HttpFactory::init();
    }

    /**
     * Determines if the application is configured to operate in multi-tenant mode.
     *
     * This is based on the presence and value of the `ALLOW_MULTITENANT` constant.
     * If `ALLOW_MULTITENANT` is defined and set to `true`, the application is
     * considered to be in multi-tenant mode.
     *
     * @return bool Returns `true` if the application is in multi-tenant mode, otherwise `false`.
     */
    private function is_multitenant_app(): bool
    {
        return \defined( 'ALLOW_MULTITENANT' ) && ALLOW_MULTITENANT === true;
    }

    /**
     * Get the maintenance message.
     *
     * This method returns a predefined maintenance message indicating that
     * the server is temporarily unavailable due to maintenance. It's used to
     * inform users about the temporary unavailability of the service.
     *
     * @return string The maintenance message to be displayed to users.
     */
    private static function get_maintenance_message(): string
    {
        return 'Service Unavailable: <br>The server is currently unable to handle the request due to temporary maintenance of the server.';
    }

    /**
     * Retrieve the current month.
     *
     * @return string The current month value (formatted as "01"-"12").
     */
    private function get_current_month(): string
    {
        return gmdate( 'm' );
    }

    /**
     * Retrieve the current year.
     *
     * @return string The current year value (formatted as "YYYY").
     */
    private function get_current_year(): string
    {
        return gmdate( 'Y' );
    }

    private static function is_debug_mode(): bool
    {
        if ( ! \defined( 'WP_DEBUG' ) ) {
            return false;
        }

        if ( \defined( 'WP_DEBUG' ) && false === WP_DEBUG ) {
            return false;
        }

        if ( \defined( 'WP_DEBUG' ) && true === WP_DEBUG ) {
            return true;
        }

        return false;
    }
}
