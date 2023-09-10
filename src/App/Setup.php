<?php

namespace Urisoft\App;

use Dotenv\Dotenv;
use Exception;
use Symfony\Component\ErrorHandler\Debug;
use Urisoft\App\Traits\ConstantBuilderTrait;
use Urisoft\App\Traits\CryptTrait;
use Urisoft\App\Traits\EnvironmentSwitch;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Setup WP Config.
 */
class Setup implements ConfigInterface
{
    use ConstantBuilderTrait;
    use CryptTrait;
    use EnvironmentSwitch;

    /**
     * list of constants defined by Setup.
     *
     * @var array
     */
    protected $constant_map = [ 'disabled' ];

    /**
     *  Directory $path.
     */
    protected $path;

    /**
     *  Dotenv $dotenv.
     */
    protected $dotenv;

    /**
     * Private $instance.
     *
     * @var self
     */
    protected static $instance;

    /**
     * The $environment.
     *
     * @var string
     */
    protected $environment;

    /**
     * Symfony error handler.
     *
     * @var bool
     */
    protected $error_handler;

    /**
     * Error log dir.
     *
     * @var string
     */
    protected $error_log_dir;

    /**
     * Env files.
     *
     * @var array
     */
    protected $env_files = [];

    /**
     * short circuit loader.
     *
     * @var bool
     */
    protected $short_circuit;

    /**
     * Set supported env types.
     *
     * @var array
     */
    protected $env_types = [];

    /**
     * Constructor.
     *
     * @param string $path current Directory.
     */
    public function __construct( string $path, ?array $supported_names = null, bool $short_circuit = true )
    {
        $this->path = $path;

        /*
         * Available env type settings.
         *
         * If we cant find a supported env type we will set to production.
         */
        $this->env_types = [ 'secure', 'sec', 'production', 'prod', 'staging', 'development', 'dev', 'debug', 'deb', 'local' ];

        // use multiple filenames.
        if ( $supported_names ) {
            $this->env_files = $supported_names;
        } else {
            $this->env_files = [
                'env',
                '.env',
                '.env.secure',
                '.env.prod',
                '.env.staging',
                '.env.dev',
                '.env.debug',
                '.env.local',
            ];
        }

        // Verify files to avoid Dotenv warning.
        foreach ( $this->env_files as $key => $file ) {
            if ( ! file_exists( $this->path . '/' . $file ) ) {
                unset( $this->env_files[ $key ] );
            }
        }

        /*
         * By default, we'll stop looking for files as soon as we find one.
         *
         * To disable this behaviour, and load all files in order,
         * we can disable the file loading with a new parameter.
         *
         * @link https://github.com/vlucas/phpdotenv/pull/394
         */
        $this->short_circuit = $short_circuit;

        /*
         * Uses multiple file names.
         *
         * Stop looking for files as soon as we find one.
         * If only one file exists, load it
         * If no files exist, crash.
         *
         * @link https://github.com/vlucas/phpdotenv/pull/394
         */
        $this->dotenv = Dotenv::createImmutable( $this->path, $this->env_files, $short_circuit );

        try {
            $this->dotenv->load();
        } catch ( Exception $e ) {
            dump( $e->getMessage() );
            exit;
        }

        $this->set_constant_map();
    }

    /**
     * Singleton.
     *
     * @param $path
     */
    public static function init( string $path ): self
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self( $path );
        }

        return self::$instance;
    }

    /**
     * Runs config setup with default setting.
     *
     * @param null|string[] $environment .
     * @param bool          $setup       .
     *
     * @return static
     */
    public function config( $environment = null, bool $setup = true ): ConfigInterface
    {
        // check required vars.
        $this->is_required();

        // self::init( __DIR__ )->config('production')
        if ( ! \is_array( $environment ) ) {
            $environment = [ 'environment' => $environment ];
        }

        // default setup.
        $environment = array_merge(
            [
                'environment' => null,
                'error_log'   => null,
                'debug'       => false,
                // set error handler framework 'symfony' or 'oops'
                'errors'      => false,
            ],
            $environment
        );

        // set error logs dir.
        $this->error_log_dir = $environment['error_log'] ?? false;

        // symfony error handler.
        $this->error_handler = $environment['errors'];

        // environment.
        if ( \is_bool( $environment['environment'] ) ) {
            $this->environment = $environment['environment'];
        } elseif ( \is_string( $environment['environment'] ) ) {
            $this->environment = trim( (string) $environment['environment'] );
        } else {
            $this->environment = $environment['environment'];
        }

        // set $setup to null allows us to short-circuit and bypass setup for more granular control.
        // Setup::init(__DIR__)->config( 'development', false )->set_environment()>database()->salts()->apply();
        if ( \is_null( $setup ) ) {
            $this->environment = $environment;

            return $this;
        }

        // $setup = false allows for bypass of default setup.
        if ( false === $setup ) {
            $this->set_environment()
                ->debug( $this->error_log_dir )
                ->set_error_handler()
                ->database()
                ->salts();

            return $this;
        }

        // do default setup.
        if ( $setup ) {
            $this->set_environment()
                ->debug( $this->error_log_dir )
                ->set_error_handler()
                ->database()
                ->site_url()
                ->asset_url()
                ->memory()
                ->optimize()
                ->force_ssl()
                ->autosave()
                ->salts();
        }

        return $this;
    }

    /**
     * Setting the environment type.
     *
     * @return static
     */
    public function set_environment(): ConfigInterface
    {
        if ( false === $this->environment && env( 'WP_ENVIRONMENT_TYPE' ) ) {
            $this->define( 'WP_ENVIRONMENT_TYPE', env( 'WP_ENVIRONMENT_TYPE' ) );

            return $this;
        }

        if ( \is_null( $this->environment ) ) {
            $this->define( 'WP_ENVIRONMENT_TYPE', env( 'WP_ENVIRONMENT_TYPE' ) ?? self::const( 'environment' ) );

            return $this;
        }

        $this->define( 'WP_ENVIRONMENT_TYPE', $this->environment );

        return $this;
    }

    /**
     * Get the short_circuit loaded.
     *
     * @return bool
     *
     * @psalm-return bool
     */
    public function get_short_circuit(): bool
    {
        return $this->short_circuit;
    }

    /**
     * Get the Env files loaded.
     *
     * @return string[]
     *
     * @psalm-return string[]
     */
    public function get_env_files(): array
    {
        return $this->env_files;
    }

    /**
     * Get the current Environment setup.
     *
     * @return string
     */
    public function get_environment(): string
    {
        return $this->environment;
    }

    /**
     * Set error handler.
     *
     * @param string $handler override for $this->error_handler
     *
     * @return static
     */
    public function set_error_handler( ?string $handler = null ): ConfigInterface
    {
        if ( ! $this->enable_error_handler() ) {
            return $this;
        }

        if ( \is_null( $this->error_handler ) ) {
            return $this;
        }

        if ( ! \in_array( $this->environment, [ 'debug', 'development', 'dev', 'local' ], true ) ) {
            return $this;
        }

        if ( $handler ) {
            $this->error_handler = $handler;
        }

        if ( 'symfony' === $this->error_handler ) {
            Debug::enable();
        } elseif ( 'oops' === $this->error_handler ) {
            $whoops = new Run();
            $whoops->pushHandler( new PrettyPageHandler() );
            $whoops->register();
        }

        return $this;
    }

    /**
     * Debug Settings.
     *
     * @param false|string $error_log_dir
     *
     * @return static
     */
    public function debug( $error_log_dir ): ConfigInterface
    {
        if ( false === $this->environment && env( 'WP_ENVIRONMENT_TYPE' ) ) {
            $this->reset_environment( env( 'WP_ENVIRONMENT_TYPE' ) );
        }

        if ( \is_null( $this->environment ) && env( 'WP_ENVIRONMENT_TYPE' ) ) {
            $this->reset_environment( env( 'WP_ENVIRONMENT_TYPE' ) );
        }

        if ( ! \in_array( $this->environment, $this->env_types, true ) ) {
            $this->env_production();

            return $this;
        }

        // Switch between different environments
        $this->environment_switch();

        // switch ( $this->environment ) {
        // case 'production':
        // $this->env_production();
        //
        // break;
        // case 'staging':
        // $this->env_staging();
        //
        // break;
        // case 'debug':
        // $this->env_debug();
        //
        // break;
        // case 'development':
        // $this->env_development();
        //
        // break;
        // case 'secure':
        // $this->env_secure();
        //
        // break;
        // default:
        // $this->env_production();
        // }// end switch

        return $this;
    }

    /**
     * Site Url Settings.
     *
     * @return static
     */
    public function site_url(): ConfigInterface
    {
        $this->define( 'WP_HOME', env( 'WP_HOME' ) );
        $this->define( 'WP_SITEURL', env( 'WP_SITEURL' ) );

        return $this;
    }

    /**
     * The Site Asset Url Settings.
     *
     * @return static
     */
    public function asset_url(): ConfigInterface
    {
        $this->define( 'ASSET_URL', env( 'ASSET_URL' ) );

        return $this;
    }

    /**
     * Optimize.
     *
     * @return static
     */
    public function optimize(): ConfigInterface
    {
        $this->define( 'CONCATENATE_SCRIPTS', env( 'CONCATENATE_SCRIPTS' ) ?? self::const( 'optimize' ) );

        return $this;
    }

    /**
     * Memory Settings.
     *
     * @return static
     */
    public function memory(): ConfigInterface
    {
        $this->define( 'WP_MEMORY_LIMIT', env( 'MEMORY_LIMIT' ) ?? self::const( 'memory' ) );
        $this->define( 'WP_MAX_MEMORY_LIMIT', env( 'MAX_MEMORY_LIMIT' ) ?? self::const( 'memory' ) );

        return $this;
    }

    /**
     * SSL.
     *
     * @return static
     */
    public function force_ssl(): ConfigInterface
    {
        $this->define( 'FORCE_SSL_ADMIN', env( 'FORCE_SSL_ADMIN' ) ?? self::const( 'ssl_admin' ) );
        $this->define( 'FORCE_SSL_LOGIN', env( 'FORCE_SSL_LOGIN' ) ?? self::const( 'ssl_login' ) );

        return $this;
    }

    /**
     * AUTOSAVE and REVISIONS.
     *
     * @return static
     */
    public function autosave(): ConfigInterface
    {
        $this->define( 'AUTOSAVE_INTERVAL', env( 'AUTOSAVE_INTERVAL' ) ?? self::const( 'autosave' ) );
        $this->define( 'WP_POST_REVISIONS', env( 'WP_POST_REVISIONS' ) ?? self::const( 'revisions' ) );

        return $this;
    }

    /**
     * DB settings.
     *
     * @return static
     */
    public function database(): ConfigInterface
    {
        $this->define( 'DB_NAME', env( 'DB_NAME' ) );
        $this->define( 'DB_USER', env( 'DB_USER' ) );
        $this->define( 'DB_PASSWORD', env( 'DB_PASSWORD' ) );
        $this->define( 'DB_HOST', env( 'DB_HOST' ) ?? self::const( 'db_host' ) );
        $this->define( 'DB_CHARSET', env( 'DB_CHARSET' ) ?? 'utf8mb4' );
        $this->define( 'DB_COLLATE', env( 'DB_COLLATE' ) ?? '' );

        return $this;
    }


    /**
     * Authentication Unique Keys and Salts.
     *
     * @return static
     */
    public function salts(): ConfigInterface
    {
        $this->define( 'AUTH_KEY', env( 'AUTH_KEY' ) );
        $this->define( 'SECURE_AUTH_KEY', env( 'SECURE_AUTH_KEY' ) );
        $this->define( 'LOGGED_IN_KEY', env( 'LOGGED_IN_KEY' ) );
        $this->define( 'NONCE_KEY', env( 'NONCE_KEY' ) );
        $this->define( 'AUTH_SALT', env( 'AUTH_SALT' ) );
        $this->define( 'SECURE_AUTH_SALT', env( 'SECURE_AUTH_SALT' ) );
        $this->define( 'LOGGED_IN_SALT', env( 'LOGGED_IN_SALT' ) );
        $this->define( 'NONCE_SALT', env( 'NONCE_SALT' ) );

        // Provides an easy way to differentiate a user from other admin users.
        $this->define( 'DEVELOPER_ADMIN', env( 'DEVELOPER_ADMIN' ) ?? '0' );

        return $this;
    }

    /**
     * Ensure that a specific constant is defined and not empty.
     *
     * This method checks if the given constant is defined. If not, it uses the Dotenv library to ensure
     * that the constant is present and not empty in the environment configuration. If the constant is missing
     * or empty, it will throw an exception.
     *
     * @param string $name The name of the constant to check.
     */
    public function required( string $name ): void
    {
        if ( ! \defined( $name ) ) {
            $this->dotenv->required( $name )->notEmpty();
        }
    }

    /**
     * Display a list of constants defined by Setup.
     *
     * Retrieves a list of constants defined by the Setup class,
     * but only if the WP_ENVIRONMENT_TYPE constant is set to 'development', 'debug', or 'staging'.
     * If WP_DEBUG is not defined or is set to false, the function returns ['disabled'].
     *
     * @return string[] Returns an array containing a list of constants defined by Setup, or null if WP_DEBUG is not defined or set to false.
     */
    public function get_constant_map(): array
    {
        return self::encrypt_secret( $this->constant_map, self::env_secrets() );
    }

    /**
     * Switches between different environments based on the value of $this->environment.
     *
     * @return void
     */
    protected function environment_switch(): void
    {
        switch ( $this->environment ) {
            case 'production':
            case 'prod':
                $this->env_production();

                break;
            case 'staging':
                $this->env_staging();

                break;
            case 'deb':
            case 'debug':
            case 'local':
                $this->env_debug();

                break;
            case 'development':
            case 'dev':
                $this->env_development();

                break;
            case 'secure':
            case 'sec':
                $this->env_secure();

                break;
            default:
                $this->env_production();
        }// end switch
    }

    protected function enable_error_handler(): bool
    {
        if ( $this->error_handler ) {
            return true;
        }

        return false;
    }

    // required vars.
    protected function is_required(): void
    {
        try {
            // site url is required but can be overridden in wp-config.php
            $this->required( 'WP_HOME' );
            $this->required( 'WP_SITEURL' );

            // db vars must be defined in .env.
            $this->dotenv->required( 'DB_HOST' )->notEmpty();
            $this->dotenv->required( 'DB_NAME' )->notEmpty();
            $this->dotenv->required( 'DB_USER' )->notEmpty();
            $this->dotenv->required( 'DB_PASSWORD' )->notEmpty();

            // salts must be defined in .env.
            $this->dotenv->required( 'AUTH_KEY' )->notEmpty();
            $this->dotenv->required( 'SECURE_AUTH_KEY' )->notEmpty();
            $this->dotenv->required( 'LOGGED_IN_KEY' )->notEmpty();
            $this->dotenv->required( 'NONCE_KEY' )->notEmpty();
            $this->dotenv->required( 'AUTH_SALT' )->notEmpty();
            $this->dotenv->required( 'SECURE_AUTH_SALT' )->notEmpty();
            $this->dotenv->required( 'LOGGED_IN_SALT' )->notEmpty();
            $this->dotenv->required( 'NONCE_SALT' )->notEmpty();
        } catch ( Exception $e ) {
            exit( $e->getMessage() );
        }// end try
    }

    /**
     * Env defaults.
     *
     * These are some defaults that will apply
     * if they do not exist in .env
     *
     * @param string $key val to retrieve
     *
     * @return mixed
     */
    protected static function const( string $key )
    {
        $constant['environment'] = 'production';
        $constant['debug']       = true;
        $constant['db_host']     = 'localhost';
        $constant['optimize']    = true;
        $constant['memory']      = '256M';
        $constant['ssl_admin']   = true;
        $constant['ssl_login']   = true;
        $constant['autosave']    = 180;
        $constant['revisions']   = 10;

        return $constant[ $key ] ?? null;
    }

    /**
     * Get Env value or return null.
     *
     * @param string $name the env var name.
     *
     * @return mixed
     */
    private static function get_env( string $name )
    {
        if ( \is_null( env( $name ) ) ) {
            return null;
        }

        return env( $name );
    }

    private function reset_environment( $reset ): void
    {
        $this->environment = $reset;
    }

    /**
     * Set the constant map based on environmental conditions.
     *
     * This method determines the constant map based on the presence of WP_DEBUG and the environment type.
     * If WP_DEBUG is not defined or set to false, the constant map will be set to ['disabled'].
     * If the environment type is 'development', 'debug', or 'staging', it will use the static $constants property
     * as the constant map if it's an array; otherwise, it will set the constant map to ['invalid_type_returned'].
     */
    private function set_constant_map(): void
    {
        if ( ! \defined( 'WP_DEBUG' ) ) {
            $this->constant_map = [ 'disabled' ];

            return;
        }

        if ( \defined( 'WP_DEBUG' ) && false === WP_DEBUG ) {
            $this->constant_map = [ 'disabled' ];

            return;
        }

        if ( \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'development', 'debug', 'staging' ], true ) ) {
            $constant_map = static::$constants;

            if ( \is_array( $constant_map ) ) {
                $this->constant_map = $constant_map;
            }

            $this->constant_map = [ 'invalid_type_returned' ];
        }
    }
}
