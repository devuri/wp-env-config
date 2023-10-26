<?php

namespace Urisoft\App\Http;

use function defined;

use Exception;
use InvalidArgumentException;
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
        $this->app_path = $app_path;

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

        $this->tenant_id = env( 'APP_TENANT_ID' );

        if ( env( 'IS_MULTI_TENANT_APP' ) ) {
            $tenant_log_file = mb_strtolower( gmdate( 'm-d-Y' ) ) . '.log';
            $this->log_file  = $this->app_path . "/sites/{$this->tenant_id}/logs/{$tenant_log_file}";
        } else {
            $this->log_file = mb_strtolower( gmdate( 'm-d-Y' ) ) . '.log';
        }

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
     * Setup overrides.
     *
     * @return void
     */
    public function overrides(): void
    {
        if ( env( 'IS_MULTI_TENANT_APP' ) ) {
            $config_override_file = $this->app_path . "sites/{$this->tenant_id}/{$this->config_file}.php";
        } elseif ( $this->config_file ) {
            $config_override_file = $this->app_path . "/{$this->config_file}.php";
        } else {
            $config_override_file = null;
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

		if ( $this->wp_is_not_installed() && \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'secure', 'sec', 'production', 'prod' ], true ) ) {
			exit('wp is not installed change enviroment to run installer');
        }

    }

	protected function wp_is_not_installed(): bool
	{
		if( defined('WP_INSTALLING') && true === WP_INSTALLING ) {
			return true;
		}

		return false;
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

    /**
     * Set App defaults.
     *
     * @return (null|false|string)[]
     *
     * @psalm-return array{environment: null, error_log: string, debug: false, errors: 'symfony'}
     */
    protected function environment_args(): array
    {
        return [
            'environment' => null,
            'error_log'   => $this->app_path . "/storage/logs/wp-errors/debug-$this->log_file",
            'debug'       => false,
            'errors'      => $this->args['error_handler'],
        ];
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
