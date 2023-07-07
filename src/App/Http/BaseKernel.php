<?php

namespace Urisoft\App\Http;

use function defined;

use Exception;
use InvalidArgumentException;
use Urisoft\App\Setup;

/**
 * Setup common elements.
 *
 * Handles global constants.
 */
class BaseKernel
{
    use ConstantTrait;

    protected $app_path;
    protected $log_file;
    protected $dir_name;
    protected $config_file;
    protected $env_secret  = [];
    protected static $list = [];
    protected $app_setup;

    /**
     * Setup BaseKernel.
     *
     * @param string   $app_path
     * @param string[] $args
     *
     * @throws Exception
     */
    public function __construct( string $app_path, array $args = [], ?Setup $setup = null )
    {
        $this->app_path = $app_path;

        $this->log_file = mb_strtolower( gmdate( 'd-m-Y' ) ) . '.log';

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
        if ( $this->config_file ) {
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
            $this->constants();
        }
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
     * Detects the error causing the crash if it should be handled.
     *
     * @since WordPress 5.2.0
     *
     * @return null|(int|string)[] Error information returned by `error_get_last()`, or null if none was recorded or the error should not be handled.
     *
     * @see https://github.com/WordPress/wordpress-develop/blob/6.0/src/wp-includes/class-wp-fatal-error-handler.php
     * @see https://www.php.net/manual/en/function.error-get-last.php
     *
     * @psalm-return array{type: int, message: string, file: string, line: int}|null
     */
    protected static function detect_error(): ?array
    {
        if ( PHP_SAPI === 'cli' ) {
            return null;
        }

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
}
