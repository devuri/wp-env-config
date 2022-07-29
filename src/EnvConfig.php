<?php

namespace DevUri\Config;

use Dotenv\Dotenv;

use function Env\env;

use Env\Env;
use Exception;
use Symfony\Component\ErrorHandler\Debug;

/**
 * Setup WP Config.
 */
abstract class EnvConfig implements ConfigInterface
{
    use ConfigTrait;

    /**
     *  Directory $path.
     */
    public $path;

    /**
     *  Dotenv $env.
     */
    public $env;

    /**
     * Private $instance.
     *
     * @var
     */
    protected static $instance;

    /**
     * The $environment.
     *
     * @var
     */
    protected $environment;

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

        Env::$options = Env::USE_ENV_ARRAY;
    }

    /**
     * Runs config setup.
     *
     * Define in child class.
     *
     * @param null|array $environment .
     * @param bool       $setup       .
     */
    abstract public function config( $environment = null, $setup = true);

    /**
     * Setting the environment type.
     *
     * @return ConfigInterface
     */
    abstract public function environment(): ConfigInterface;

    /**
     * Get the current Environment setup.
     *
     * @return string.
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * Symfony Debug.
     *
     * @param bool $enable
     *
     * @return self
     */
    public function symfony_debug( bool $enable = false ): ConfigInterface
    {
        if ( false === $enable ) {
            return $this;
        }

        if ( \defined( 'WP_DEBUG' ) && ( true === WP_DEBUG ) ) {
            Debug::enable();
        }

        return $this;
    }

    /**
     * Debug Settings.
     *
     * @return EnvConfig
     */
    abstract public function debug(): ConfigInterface;

    /**
     * Site Url Settings.
     *
     * @return self
     */
    abstract public function site_url(): ConfigInterface;

    /**
     *  DB settings.
     *
     * @return self
     */
    public function database(): ConfigInterface
    {
        self::define( 'DB_NAME', env( 'DB_NAME' ) );
        self::define( 'DB_USER', env( 'DB_USER' ) );
        self::define( 'DB_PASSWORD', env( 'DB_PASSWORD' ) );
        self::define( 'DB_HOST', env( 'DB_HOST' ) ?: self::const( 'db_host' ) );
        self::define( 'DB_CHARSET', 'utf8mb4' );
        self::define( 'DB_COLLATE', '' );

        return $this;
    }

    /**
     * Optimize.
     *
     * @return self
     */
    abstract public function optimize(): ConfigInterface;

    /**
     * Memory Settings.
     *
     * @return self
     */
    abstract public function memory(): ConfigInterface;

    /**
     * SSL.
     *
     * @return self
     */
    abstract public function force_ssl(): ConfigInterface;

    /**
     * AUTOSAVE and REVISIONS.
     *
     * @return self
     */
    abstract public function autosave(): ConfigInterface;

    /**
     * Authentication Unique Keys and Salts.
     *
     * @return self
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
        self::define( 'DEVELOPERADMIN', env( 'DEVELOPERADMIN' ) );

        return $this;
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
        }//end try
    }
}
