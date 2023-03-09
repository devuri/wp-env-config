<?php

namespace DevUri\Config;

/**
 * Setup Environment.
 */
class Environment
{
    use ConfigTrait;

    public static function production(): void
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

    public static function staging( $error_log_dir ): void
    {
        self::define( 'DISALLOW_FILE_EDIT', false );

        self::define( 'WP_DEBUG_DISPLAY', true );
        self::define( 'SCRIPT_DEBUG', false );

        self::define( 'WP_DEBUG', true );
        ini_set( 'display_errors', '0' );

        self::set_debug_log( $error_log_dir );
    }

    public static function development( $error_log_dir ): void
    {
        self::define( 'WP_DEBUG', true );
        self::define( 'SAVEQUERIES', true );

        self::define( 'WP_DEBUG_DISPLAY', true );
        self::define( 'WP_DISABLE_FATAL_ERROR_HANDLER', true );

        self::define( 'SCRIPT_DEBUG', true );
        ini_set( 'display_errors', '1' );

        self::set_debug_log( $error_log_dir );
    }

    public static function debug( $error_log_dir ): void
    {
        self::define( 'WP_DEBUG', true );
        self::define( 'WP_DEBUG_DISPLAY', true );
        self::define( 'CONCATENATE_SCRIPTS', false );
        self::define( 'SAVEQUERIES', true );

        self::set_debug_log( $error_log_dir );

        @error_reporting( E_ALL );
        @ini_set( 'log_errors', true );
        @ini_set( 'log_errors_max_len', '0' );
        @ini_set( 'display_errors', 1 );
        @ini_set( 'display_startup_errors', 1 );
    }

    public static function secure(): void
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

    protected static function set_debug_log( $error_log_dir ): void
    {
        if ( $error_log_dir ) {
            self::define( 'WP_DEBUG_LOG', $error_log_dir );
        } else {
            self::define( 'WP_DEBUG_LOG', true );
        }
    }
}
