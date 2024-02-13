<?php

namespace Urisoft\App;

use Dotenv\Dotenv;
use Exception;
use Symfony\Component\ErrorHandler\Debug;
use Urisoft\App\Traits\ConfigTrait;
use Urisoft\App\Traits\ConstantBuilderTrait;
use Urisoft\App\Traits\EnvironmentSwitch;
use Urisoft\App\Traits\TenantTrait;
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
    use TenantTrait;

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
     * Constructor for initializing the application environment and configuration.
     *
     * Sets up the application path, initializes environment configuration loading with Dotenv,
     * and handles multi-tenancy. It also sets up default environment types and constants.
     *
     * @param string $path           The base directory path for the application.
     * @param array  $env_file_names Optional. Additional environment file names to support.
     * @param bool   $short_circuit  Optional. Whether to stop loading files after the first found. Defaults to true.
     */
    public function __construct( string $path, array $env_file_names = [], bool $short_circuit = true )
    {
        $this->path          = $this->determine_envpath( $path );
        $this->short_circuit = $short_circuit;
        $this->env_files     = array_merge( $this->get_default_file_names(), $env_file_names );

        $this->filter_existing_env_files();
        $this->env_types = EnvTypes::get();
        $this->initialize_dotenv();

        $this->set_constant_map();
    }

    public function get_current_path(): string
    {
        return $this->path;
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
     * Configures application settings.
     *
     * @param null|array|string $environment Configuration settings or environment name.
     * @param null|bool         $setup       Controls the setup process. If null, setup is bypassed.
     *
     * @return self
     */
    public function config( $environment = null, ?bool $setup = true ): ConfigInterface
    {
        // check required vars.
        $this->is_required();

        // set $setup to null allows us to short-circuit and bypass setup for more granular control.
        // Setup::init(__DIR__)->config( 'development', false )->set_environment()>database()->salts()->apply();
        if ( \is_null( $setup ) ) {
            $this->environment = $environment;

            return $this;
        }

        // self::init( __DIR__ )->config('production')
        $environment         = $this->normalize_environment( $environment );
        $this->error_log_dir = $environment['error_log'] ?? false;
        $this->error_handler = $environment['errors'] ?? null;
        $this->environment   = $this->determine_environment( $environment['environment'] );

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
        if ( true === $setup ) {
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
     * Normalizes the environment configuration.
     *
     * @param mixed $environment The provided environment configuration.
     *
     * @return array The normalized configuration array.
     */
    protected function normalize_environment( $environment ): array
    {
        if ( ! \is_array( $environment ) ) {
            $environment = [ 'environment' => $environment ];
        }

        return array_merge(
            [
                'environment' => null,
                'error_log'   => null,
                'debug'       => false,
                'errors'      => false,
            ],
            $environment
        );
    }

    /**
     * Determines the appropriate environment setting.
     *
     * @param mixed $environment The environment setting from the configuration.
     *
     * @return mixed The determined environment value.
     */
    protected function determine_environment( $environment )
    {
        if ( \is_bool( $environment ) || \is_string( $environment ) ) {
            return $environment;
        }

        return trim( (string) $environment );

        return $environment;
    }

    /**
     * Filters out environment files that do not exist to avoid warnings.
     */
    protected function filter_existing_env_files(): void
    {
        foreach ( $this->env_files as $key => $file ) {
            if ( ! file_exists( $this->path . '/' . $file ) ) {
                unset( $this->env_files[ $key ] );
            }
        }
    }

    /**
     * Initializes Dotenv with the set path and environment files.
     * Handles exceptions by using the`wp_terminate` function to exit.
     */
    protected function initialize_dotenv(): void
    {
        $this->dotenv = Dotenv::createImmutable( $this->path, $this->env_files, $this->short_circuit );

        try {
            $this->dotenv->load();
        } catch ( Exception $e ) {
            wp_terminate( $e->getMessage() );
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
