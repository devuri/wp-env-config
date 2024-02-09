<?php

namespace Urisoft\App;

use Dotenv\Dotenv;
use Exception;
use Symfony\Component\ErrorHandler\Debug;
use Urisoft\App\Http\AppHostManager;
use Urisoft\App\Http\HttpFactory;
use Urisoft\App\Traits\ConfigTrait;
use Urisoft\App\Traits\ConstantBuilderTrait;
use Urisoft\App\Traits\EnvironmentSwitch;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Setup WP Config.
 */
class Setup implements ConfigInterface
{
    use ConfigTrait;
    use ConstantBuilderTrait;
    use EnvironmentSwitch;

    /**
     *  Directory $path.
     */
    protected $path;

    /**
     * Setup multi tenant.
     */
    protected $is_multi_tenant;

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
     * Set the http host.
     *
     * @var string
     */
    protected $app_http_host;

    /**
     * Constructor for initializing the application environment and configuration.
     *
     * @param string $path                       Current directory.
     * @param array  $supported_names_or_tenants An array of supported environment names and configuration.
     * @param bool   $short_circuit              Flag to control short-circuiting file loading.
     */
    public function __construct( string $path, array $supported_names_or_tenants = [], bool $short_circuit = true )
    {
        $this->path = $path;

        $this->is_multi_tenant = $this->is_multitenant_app();

        /*
         * Available env type settings.
         *
         * If we cant find a supported env type we will set to production.
         */
        $this->env_types = EnvTypes::get();

        // use multiple filenames.
        if ( $this->is_multi_tenant ) {
            $this->env_files = $supported_names_or_tenants;
        } else {
            $this->env_files = array_merge( $this->get_default_file_names(), $supported_names_or_tenants );
        }

        if ( ! $this->is_multi_tenant ) {
            // Verify files to avoid Dotenv warning.
            foreach ( $this->env_files as $key => $file ) {
                if ( ! file_exists( $this->path . '/' . $file ) ) {
                    unset( $this->env_files[ $key ] );
                }
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
        if ( $this->is_multi_tenant ) {
            /*
             * Load Tenant IDs for the Application.
             *
             * This loads tenant IDs from a JSON file or API response and passed from
             * the `bootstrap.php` file for initializing tenant-specific configurations.
             *
             * The source of the tenant IDs. Can be a file path to a JSON file
             *                      or an API response containing tenant IDs.
             *
             * @example
             * ```php
             * // Example JSON format: ['example.com' => 'd7874918-6e36-11ee-b962-0242ac120002']
             *
             * 'tenants.json'
             * ```
             *
             * @throws Exception If the source is invalid or unable to be loaded or match tenant.
             */
            $this->app_http_host = self::http()->get_http_host();

            if ( ! \array_key_exists( $this->app_http_host, $this->env_files['tenant_ids'] ) ) {
                wp_terminate( 'The website is not defined. Please review the URL and try again.', 403 );
            }

            if ( $this->app_http_host ) {
                $tenant_id = $this->env_files['tenant_ids'][ $this->app_http_host ];
            } else {
                $tenant_id = 0;
            }

            // TODO use main env as tenant env

            /*
             * Start and bootstrap the web application.
             *
             * so that we can $http_app = wpc_app(__DIR__, 'app', ['localhost:8019' => 'd7874918-6e36-11ee-b962-0242ac120002'] );
             */
            $this->dotenv = Dotenv::createImmutable( $this->path, "site/{$tenant_id}/.env" );
        } else {
            $this->dotenv = Dotenv::createImmutable( $this->path, $this->env_files, $short_circuit );
        }// end if

        try {
            $this->dotenv->load();
        } catch ( Exception $e ) {
            wp_terminate( $e->getMessage() );
            exit;
        }

        $this->set_constant_map();
    }

    public function define_multi_tenant(): void
    {
        // set app host.
        \define( 'APP_HTTP_HOST', $this->app_http_host );

        // multi tenant support.
        if ( $this->is_multi_tenant ) {
            if ( ! env( 'APP_TENANT_ID' ) ) {
                wp_terminate( 'no tenant_id defined', 503 );
            }
            \define( 'IS_MULTITENANT', true );
        } else {
            \define( 'IS_MULTITENANT', false );
        }
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

        if ( ! EnvTypes::is_valid( $this->environment ) ) {
            $this->env_production();

            return $this;
        }

        // Switch between different environments
        $this->environment_switch();

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
     * Retrieves the default file names for environment configuration.
     *
     * This protected method is designed to return an array of default file names
     * used for environment configuration in a WordPress environment.
     * These file names include various formats and stages of environment setup,
     * such as production, staging, development, and local environments.
     *
     * @since [version number]
     *
     * @return array An array of default file names for environment configurations.
     *               The array includes the following file names:
     *               - 'env'
     *               - '.env'
     *               - '.env.secure'
     *               - '.env.prod'
     *               - '.env.staging'
     *               - '.env.dev'
     *               - '.env.debug'
     *               - '.env.local'
     *               - 'env.local'
     */
    protected function get_default_file_names(): array
    {
        return [
            'env',
            '.env',
            '.env.secure',
            '.env.prod',
            '.env.staging',
            '.env.dev',
            '.env.debug',
            '.env.local',
            'env.local',
        ];
    }

    protected function is_multitenant_app(): bool
    {
        if ( \defined( 'ALLOW_MULTITENANT' ) && true === ALLOW_MULTITENANT ) {
            return true;
        }

        return false;
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

            // we need to establish if this is a multi tenant app
            $this->dotenv->required( 'IS_MULTITENANT' )->isBoolean();
            $this->dotenv->required( 'IS_MULTITENANT' )->allowedValues( [ 'true', 'false' ] );

            // in most cases application passwords is not needed.
            $this->dotenv->required( 'DISABLE_WP_APPLICATION_PASSWORDS' )->allowedValues( [ 'true', 'false' ] );

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
            wp_terminate( $e->getMessage() );
        }// end try
    }

    protected static function http(): AppHostManager
    {
        return HttpFactory::init();
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
}
