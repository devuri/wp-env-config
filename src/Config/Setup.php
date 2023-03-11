<?php

namespace DevUri\Config;

use DevUri\Config\Traits\ConfigTrait;
use DevUri\Config\Traits\Environment;
use Dotenv\Dotenv;
use Exception;
use Symfony\Component\ErrorHandler\Debug;

/**
 * Setup WP Config.
 */
class Setup implements ConfigInterface
{
    use ConfigTrait;
    use Environment;

    /**
     * list of constants defined by Setup.
     *
     * @var array
     */
    protected $config_map = [ 'disabled' ];

    /**
     *  Directory $path.
     */
    protected $path;

    /**
     *  Dotenv $env.
     */
    protected $env;

    /**
     * Private $instance.
     *
     * @var
     */
    protected static $instance;

    /**
     * The $environment.
     *
     * @var array|string
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
     * @var array
     */
    protected $error_log_dir;

    /**
     * Constructor.
     *
     * @param array|string $path current Directory.
     */
    public function __construct( $path )
    {
        $this->path = $path;

        $dotenv    = Dotenv::createImmutable( $this->path );
        $this->env = $dotenv;

        try {
            $dotenv->load();
        } catch ( Exception $e ) {
            exit( $e->getMessage() );
        }

        $this->set_config_map();
    }

    /**
     * Singleton.
     *
     * @param $path
     */
    public static function init( string $path ): ConfigInterface
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self( $path );
        }

        return self::$instance;
    }

    /**
     * Runs config setup with default setting.
     *
     * @param null|array $environment .
     * @param bool       $setup       .
     *
     * @return null|static
     */
    public function config( $environment = null, $setup = true )
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
                'errors'      => 'symfony',
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
            $this->environment = trim( $environment['environment'] );
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
                ->salts()
                ->apply();

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
                ->salts()
                ->apply();
        }
    }

    /**
     * Setting the environment type.
     *
     * @return static
     */
    public function set_environment(): ConfigInterface
    {
        if ( false === $this->environment && env( 'WP_ENVIRONMENT_TYPE' ) ) {
            self::define( 'WP_ENVIRONMENT_TYPE', env( 'WP_ENVIRONMENT_TYPE' ) );

            return $this;
        }

        if ( \is_null( $this->environment ) ) {
            self::define( 'WP_ENVIRONMENT_TYPE', env( 'WP_ENVIRONMENT_TYPE' ) ?? self::const( 'environment' ) );

            return $this;
        }

        self::define( 'WP_ENVIRONMENT_TYPE', $this->environment );

        return $this;
    }

    /**
     * Get the current Environment setup.
     *
     * @return string.
     */
    public function get_environment(): string
    {
        return $this->environment;
    }

    /**
     * Set error handler.
     *
     * @param bool $enable
     *
     * @return static
     */
    public function set_error_handler( ?string $handler = null ): ConfigInterface
    {
        if ( ! $this->enable_error_handler() ) {
            return $this;
        }

        if ( 'debug' !== $this->environment ) {
            return $this;
        }

        if ( 'symfony' === $this->error_handler ) {
            Debug::enable();
        } elseif ( 'oops' ===  $this->error_handler ) {
            $whoops = new \Whoops\Run();
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
            $whoops->register();
        }

        return $this;
    }

    /**
     * Debug Settings.
     *
     * @param mixed $error_log_dir
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

        if ( ! \in_array( $this->environment, self::init_settings(), true ) ) {
            $this->env_production();

            return $this;
        }

        switch ( $this->environment ) {
            case 'production':
                $this->env_production();

                break;
            case 'staging':
                $this->env_staging( $error_log_dir );

                break;
            case 'debug':
                $this->env_debug( $error_log_dir );

                break;
            case 'development':
                $this->env_development( $error_log_dir );

                break;
            case 'secure':
                $this->env_secure( $error_log_dir );

                break;
            default:
                $this->env_production();
        }// end switch

        return $this;
    }

    /**
     * Site Url Settings.
     *
     * @return static
     */
    public function site_url(): ConfigInterface
    {
        self::define( 'WP_HOME', env( 'WP_HOME' ) );
        self::define( 'WP_SITEURL', env( 'WP_SITEURL' ) );

        return $this;
    }

    /**
     * The Site Asset Url Settings.
     *
     * @return static
     */
    public function asset_url(): ConfigInterface
    {
        self::define( 'ASSET_URL', env( 'ASSET_URL' ) );

        return $this;
    }

    /**
     * Optimize.
     *
     * @return static
     */
    public function optimize(): ConfigInterface
    {
        self::define( 'CONCATENATE_SCRIPTS', env( 'CONCATENATE_SCRIPTS' ) ?? self::const( 'optimize' ) );

        return $this;
    }

    /**
     * Memory Settings.
     *
     * @return static
     */
    public function memory(): ConfigInterface
    {
        self::define( 'WP_MEMORY_LIMIT', env( 'MEMORY_LIMIT' ) ?? self::const( 'memory' ) );
        self::define( 'WP_MAX_MEMORY_LIMIT', env( 'MAX_MEMORY_LIMIT' ) ?? self::const( 'memory' ) );

        return $this;
    }

    /**
     * SSL.
     *
     * @return static
     */
    public function force_ssl(): ConfigInterface
    {
        self::define( 'FORCE_SSL_ADMIN', env( 'FORCE_SSL_ADMIN' ) ?? self::const( 'ssl_admin' ) );
        self::define( 'FORCE_SSL_LOGIN', env( 'FORCE_SSL_LOGIN' ) ?? self::const( 'ssl_login' ) );

        return $this;
    }

    /**
     * AUTOSAVE and REVISIONS.
     *
     * @return static
     */
    public function autosave(): ConfigInterface
    {
        self::define( 'AUTOSAVE_INTERVAL', env( 'AUTOSAVE_INTERVAL' ) ?? self::const( 'autosave' ) );
        self::define( 'WP_POST_REVISIONS', env( 'WP_POST_REVISIONS' ) ?? self::const( 'revisions' ) );

        return $this;
    }

    /**
     * DB settings.
     *
     * @return static
     */
    public function database(): ConfigInterface
    {
        self::define( 'DB_NAME', env( 'DB_NAME' ) );
        self::define( 'DB_USER', env( 'DB_USER' ) );
        self::define( 'DB_PASSWORD', env( 'DB_PASSWORD' ) );
        self::define( 'DB_HOST', env( 'DB_HOST' ) ?? self::const( 'db_host' ) );
        self::define( 'DB_CHARSET', env( 'DB_CHARSET' ) ?? 'utf8mb4' );
        self::define( 'DB_COLLATE', env( 'DB_COLLATE' ) ?? '' );

        return $this;
    }


    /**
     * Authentication Unique Keys and Salts.
     *
     * @return static
     */
    public function salts(): ConfigInterface
    {
        self::define( 'AUTH_KEY', env( 'AUTH_KEY' ) );
        self::define( 'SECURE_AUTH_KEY', env( 'SECURE_AUTH_KEY' ) );
        self::define( 'LOGGED_IN_KEY', env( 'LOGGED_IN_KEY' ) );
        self::define( 'NONCE_KEY', env( 'NONCE_KEY' ) );
        self::define( 'AUTH_SALT', env( 'AUTH_SALT' ) );
        self::define( 'SECURE_AUTH_SALT', env( 'SECURE_AUTH_SALT' ) );
        self::define( 'LOGGED_IN_SALT', env( 'LOGGED_IN_SALT' ) );
        self::define( 'NONCE_SALT', env( 'NONCE_SALT' ) );

        // Provides an easy way to differentiate a user from other admin users.
        self::define( 'DEVELOPER_ADMIN', env( 'DEVELOPER_ADMIN' ) ?? '0' );

        return $this;
    }

    /**
     * Available Settings.
     *
     * @return array
     */
    protected static function init_settings(): array
    {
        return [ 'production', 'staging', 'debug', 'development', 'secure' ];
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
            $this->env->required( 'DB_HOST' )->notEmpty();
            $this->env->required( 'DB_NAME' )->notEmpty();
            $this->env->required( 'DB_USER' )->notEmpty();
            $this->env->required( 'DB_PASSWORD' )->notEmpty();

            // salts must be defined in .env.
            $this->env->required( 'AUTH_KEY' )->notEmpty();
            $this->env->required( 'SECURE_AUTH_KEY' )->notEmpty();
            $this->env->required( 'LOGGED_IN_KEY' )->notEmpty();
            $this->env->required( 'NONCE_KEY' )->notEmpty();
            $this->env->required( 'AUTH_SALT' )->notEmpty();
            $this->env->required( 'SECURE_AUTH_SALT' )->notEmpty();
            $this->env->required( 'LOGGED_IN_SALT' )->notEmpty();
            $this->env->required( 'NONCE_SALT' )->notEmpty();
        } catch ( Exception $e ) {
            var_dump( $e->getMessage() );
            exit();
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
