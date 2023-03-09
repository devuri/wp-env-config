<?php

namespace DevUri\Config;

/**
 * Setup Environment.
 */
class Environment
{
    use ConfigTrait;

    public static function production()
    {
        // Disable Plugin and Theme Editor.
        self::define( 'DISALLOW_FILE_EDIT', true );

        self::define( 'WP_DEBUG_DISPLAY', false );
        self::define( 'SCRIPT_DEBUG', false );

        self::define( 'WP_CRON_LOCK_TIMEOUT', 60 );
        self::define( 'EMPTY_TRASH_DAYS', 15 );

        self::define( 'WP_DEBUG', false );
        self::define( 'WP_DEBUG_LOG', false );
        ini_set( 'display_errors', '0' );
    }

    public static function staging()
    {
        self::define( 'DISALLOW_FILE_EDIT', false );

        self::define( 'WP_DEBUG_DISPLAY', true );
        self::define( 'SCRIPT_DEBUG', false );

        self::define( 'WP_DEBUG', true );
        self::define( 'WP_DEBUG_LOG', true );
        ini_set( 'display_errors', '0' );
    }

    public static function development()
    {
        self::define( 'WP_DEBUG', true );
        self::define( 'SAVEQUERIES', true );

        self::define( 'WP_DEBUG_DISPLAY', true );
        self::define( 'WP_DISABLE_FATAL_ERROR_HANDLER', true );

        self::define( 'SCRIPT_DEBUG', true );
        self::define( 'WP_DEBUG_LOG', true );
        ini_set( 'display_errors', '1' );
    }

    public static function debug()
    {
        self::define( 'WP_DEBUG', true );
        self::define( 'WP_DEBUG_LOG', true );
        self::define( 'WP_DEBUG_DISPLAY', true );
        self::define( 'CONCATENATE_SCRIPTS', false );
        self::define( 'SAVEQUERIES', true );

        @error_reporting( E_ALL );
        @ini_set( 'log_errors', true );
        @ini_set( 'log_errors_max_len', '0' );
        @ini_set( 'display_errors', 1 );
        @ini_set( 'display_startup_errors', 1 );
    }

    public static function secure()
    {
        // Disable Plugin and Theme Editor.
        self::define( 'DISALLOW_FILE_EDIT', true );
        self::define( 'DISALLOW_FILE_MODS', true );

        self::define( 'WP_DEBUG_DISPLAY', false );
        self::define( 'SCRIPT_DEBUG', false );

        self::define( 'WP_CRON_LOCK_TIMEOUT', 120 );
        self::define( 'EMPTY_TRASH_DAYS', 10 );

        self::define( 'WP_DEBUG', false );
        self::define( 'WP_DEBUG_LOG', false );
        ini_set( 'display_errors', '0' );
    }

    public static function env( string $env_type )
    {
		$types = [
            'production'  => self::production(),
            'staging'     => self::staging(),
            'debug'       => self::debug(),
            'development' => self::development(),
            'secure'      => self::secure(),
		];

		$types[ $env_type ];
    }
}
