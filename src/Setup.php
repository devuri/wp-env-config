<?php

namespace DevUri\Config;

use function Env\env;

/**
 * Setup WP Config.
 */
class Setup extends EnvConfig
{
    /**
     * Constructor.
     *
     * @param string $path use required __DIR__
     */
    public function __construct( string $path )
    {
        parent::__construct( $path );
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

        // $setup = false allows for bypass of default setup.
        // Setup::init(__DIR__)->config( 'development', false )->environment()>database()->salts()->apply();
        if ( false === $setup ) {
            $this->environment = $environment;

            return $this;
        }

        // self::init( __DIR__ )->config('production')
        if ( ! \is_array( $environment ) ) {
            $environment = [ 'environment' => $environment ];
        }

        // default setup.
        $default     = [
            'environment' => null,
            'debug'       => false,
            'symfony'     => false,
        ];
        $environment = array_merge( $default, $environment );

        // environment.
        $this->environment = $environment['environment'];

        // do default setup.
        if ( $setup ) {
            $this->environment()
                ->debug()
                ->symfony_debug( $environment['symfony'] )
                ->database()
                ->site_url()
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
    public function environment(): ConfigInterface
    {
        if ( \is_null( $this->environment ) ) {
            self::define( 'WP_ENVIRONMENT_TYPE', env( 'WP_ENVIRONMENT_TYPE' ) ?? self::const( 'environment' ) );

            return $this;
        }

        self::define( 'WP_ENVIRONMENT_TYPE', $this->environment );

        return $this;
    }

    /**
     * Debug Settings.
     *
     * @return static
     */
    public function debug(): ConfigInterface
    {
        // Debugger setup based on environment.
        switch ( $this->environment ) {
            case 'production':
                Environment::production();

                break;
            case 'staging':
                Environment::staging();

                break;
            case 'debug':
                Environment::debug();

                break;
            case 'development':
                Environment::development();

                break;
            case 'secure':
                Environment::secure();

                break;
            default:
                Environment::production();
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
}
