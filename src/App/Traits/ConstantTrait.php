<?php

namespace Urisoft\App\Traits;

use function define;

trait ConstantTrait
{
    protected $args = [
        'web_root'         => 'public',
        'wp_dir_path'      => 'wp',
        'wordpress'        => 'wp',
        'asset_dir'        => 'assets',
        'content_dir'      => 'content',
        'plugin_dir'       => 'plugins',
        'mu_plugin_dir'    => 'mu-plugins',
        'sqlite_dir'       => 'sqlitedb',
        'sqlite_file'      => '.sqlite-wpdatabase',
        'default_theme'    => 'twentytwentythree',
        'disable_updates'  => true,
        'can_deactivate'   => true,
        'templates_dir'    => null,
        'error_handler'    => 'symfony',
        'config_file'      => 'config',
        'sudo_admin'       => null,
        'sudo_admin_group' => null,
        'sucuri_waf'       => false,
        'redis'            => [],
        'security'         => [],
    ];

    /**
     * Defines constants.
     *
     * @psalm-suppress UndefinedConstant
     *
     * @return void
     */
    public function set_config_constants(): void
    {
        // define app_path.
        $this->define( 'APP_PATH', $this->get_app_path() );

        // set app http host.
        $this->define( 'APP_HTTP_HOST', self::http()->get_http_host() );

        // define public web root dir.
        $this->define( 'PUBLIC_WEB_DIR', APP_PATH . '/' . $this->args['web_root'] );

        // wp dir path
        $this->define( 'WP_DIR_PATH', PUBLIC_WEB_DIR . '/' . $this->args['wp_dir_path'] );

        // define assets dir.
        $this->define( 'APP_ASSETS_DIR', PUBLIC_WEB_DIR . '/' . $this->args['asset_dir'] );

        // Directory PATH.
        $this->define( 'APP_CONTENT_DIR', $this->args['content_dir'] );
        $this->define( 'WP_CONTENT_DIR', PUBLIC_WEB_DIR . '/' . APP_CONTENT_DIR );
        $this->define( 'WP_CONTENT_URL', env( 'WP_HOME' ) . '/' . APP_CONTENT_DIR );

        /*
         * Themes, prefer '/templates'
         *
         * This requires mu-plugin or add `register_theme_directory( APP_THEME_DIR );`
         *
         * path should be a folder within WP_CONTENT_DIR
         *
         * @link https://github.com/devuri/custom-wordpress-theme-dir
         */
        if ( $this->args['templates_dir'] ) {
            $this->define( 'APP_THEME_DIR', $this->args['templates_dir'] );
        }

        // Plugins.
        $this->define( 'WP_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $this->args['plugin_dir'] );
        $this->define( 'WP_PLUGIN_URL', env( 'WP_HOME' ) . '/' . $this->args['plugin_dir'] );

        // Must-Use Plugins.
        $this->define( 'WPMU_PLUGIN_DIR', PUBLIC_WEB_DIR . '/' . $this->args['mu_plugin_dir'] );
        $this->define( 'WPMU_PLUGIN_URL', env( 'WP_HOME' ) . '/' . $this->args['mu_plugin_dir'] );

        // Disable any kind of automatic upgrade.
        // this will be handled via composer.
        $this->define( 'AUTOMATIC_UPDATER_DISABLED', $this->args['disable_updates'] );

        // Sudo admin (granted more privilages uses user ID).
        $this->define( 'WP_SUDO_ADMIN', $this->args['sudo_admin'] );

        // A group of users with higher administrative privileges.
        $this->define( 'SUDO_ADMIN_GROUP', $this->args['sudo_admin_group'] );

        /*
         * Prevent Admin users from deactivating plugins, true or false.
         *
         * @link https://gist.github.com/devuri/034ccb7c833f970192bb64317814da3b
         */
        $this->define( 'CAN_DEACTIVATE_PLUGINS', $this->args['can_deactivate'] );

        // SQLite database location and filename.
        $this->define( 'DB_DIR', APP_PATH . '/' . $this->args['sqlite_dir'] );
        $this->define( 'DB_FILE', $this->args['sqlite_file'] );

        /*
         * Slug of the default theme for this installation.
         * Used as the default theme when installing new sites.
         * It will be used as the fallback if the active theme doesn't exist.
         *
         * @see WP_Theme::get_core_default_theme()
         */
        $this->define( 'WP_DEFAULT_THEME', $this->args['default_theme'] );

        // home url md5 value.
        $this->define( 'COOKIEHASH', md5( env( 'WP_HOME' ) ) );

        // Defines cookie-related override for WordPress constants.
        $this->define( 'USER_COOKIE', 'wpc_user_' . COOKIEHASH );
        $this->define( 'PASS_COOKIE', 'wpc_pass_' . COOKIEHASH );
        $this->define( 'AUTH_COOKIE', 'wpc_' . COOKIEHASH );
        $this->define( 'SECURE_AUTH_COOKIE', 'wpc_sec_' . COOKIEHASH );
        $this->define( 'LOGGED_IN_COOKIE', 'wpc_logged_in_' . COOKIEHASH );
        $this->define( 'TEST_COOKIE', md5( 'wpc_test_cookie' . env( 'WP_HOME' ) ) );

        // SUCURI
        $this->define( 'ENABLE_SUCURI_WAF', $this->args['sucuri_waf'] );
        $this->define( 'SUCURI_DATA_STORAGE', ABSPATH . '../../storage/logs/sucuri' );

        /*
         * Redis cache configuration for the WordPress application.
         *
         * This array contains configuration settings for the Redis cache integration in WordPress.
         * For detailed installation instructions, refer to the documentation at:
         * @link https://github.com/rhubarbgroup/redis-cache/blob/develop/INSTALL.md
         *
         * @return void
         */
        $this->define( 'WP_REDIS_DISABLED', $this->redis( 'disabled' ) );

        $this->define( 'WP_REDIS_PREFIX', $this->redis( 'prefix' ) );
        $this->define( 'WP_REDIS_DATABASE', $this->redis( 'database' ) );
        $this->define( 'WP_REDIS_HOST', $this->redis( 'host' ) );
        $this->define( 'WP_REDIS_PORT', $this->redis( 'port' ) );
        $this->define( 'WP_REDIS_PASSWORD', $this->redis( 'password' ) );

        $this->define( 'WP_REDIS_DISABLE_ADMINBAR', $this->redis( 'adminbar' ) );
        $this->define( 'WP_REDIS_DISABLE_METRICS', $this->redis( 'disable-metrics' ) );
        $this->define( 'WP_REDIS_DISABLE_BANNERS', $this->redis( 'disable-banners' ) );

        $this->define( 'WP_REDIS_TIMEOUT', $this->redis( 'timeout' ) );
        $this->define( 'WP_REDIS_READ_TIMEOUT', $this->redis( 'read-timeout' ) );

        // web app security key
        $this->define( 'WEBAPP_ENCRYPTION_KEY', $this->security( 'encryption_key' ) );
    }

    protected function redis( string $key )
    {
        if ( empty( $this->args['redis'] ) ) {
            return null;
        }

        return $this->args['redis'][ $key ] ?? null;
    }

    protected function security( string $key )
    {
        if ( empty( $this->args['security'] ) ) {
            return null;
        }

        return $this->args['security'][ $key ] ?? null;
    }
}
