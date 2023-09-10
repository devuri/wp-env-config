<?php

namespace Urisoft\App\Traits;

use Urisoft\App\ConfigInterface;

trait ConfigTrait
{
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
}
