<?php

namespace Urisoft\App\Traits;

/**
 * Trait EnvironmentSwitch.
 *
 * This trait provides methods for setting up different environmental configurations
 * such as development, staging, production, and debugging within your application.
 */
trait EnvironmentSwitch
{
    /**
     * Secure.
     */
    public function env_secure(): void
    {
        // Disable Plugin and Theme Editor.
        self::define( 'DISALLOW_FILE_EDIT', true );
        self::define( 'DISALLOW_FILE_MODS', true );

        self::define( 'WP_DEBUG_DISPLAY', false );
        self::define( 'SCRIPT_DEBUG', false );

        self::define( 'WP_CRON_LOCK_TIMEOUT', 120 );
        self::define( 'EMPTY_TRASH_DAYS', 10 );

        if ( $this->error_log_dir ) {
            self::define( 'WP_DEBUG', true );
            self::define( 'WP_DEBUG_LOG', $this->error_log_dir );
        } else {
            self::define( 'WP_DEBUG', false );
            self::define( 'WP_DEBUG_LOG', false );
        }

        ini_set( 'display_errors', '0' );
    }

    public function env_production(): void
    {
        // Disable Plugin and Theme Editor.
        self::define( 'DISALLOW_FILE_EDIT', true );

        self::define( 'WP_DEBUG_DISPLAY', false );
        self::define( 'SCRIPT_DEBUG', false );

        self::define( 'WP_CRON_LOCK_TIMEOUT', 60 );
        self::define( 'EMPTY_TRASH_DAYS', 15 );

        if ( $this->error_log_dir ) {
            self::define( 'WP_DEBUG', true );
            self::define( 'WP_DEBUG_LOG', $this->error_log_dir );
        } else {
            self::define( 'WP_DEBUG', false );
            self::define( 'WP_DEBUG_LOG', false );
        }

        ini_set( 'display_errors', '0' );
    }

    public function env_staging(): void
    {
        self::define( 'DISALLOW_FILE_EDIT', false );

        self::define( 'WP_DEBUG_DISPLAY', true );
        self::define( 'SCRIPT_DEBUG', false );

        self::define( 'WP_DEBUG', true );
        ini_set( 'display_errors', '0' );

        self::set_debug_log();
    }

    public function env_development(): void
    {
        self::define( 'WP_DEBUG', true );
        self::define( 'SAVEQUERIES', true );

        self::define( 'WP_DEBUG_DISPLAY', true );
        self::define( 'WP_DISABLE_FATAL_ERROR_HANDLER', true );

        self::define( 'SCRIPT_DEBUG', true );
        ini_set( 'display_errors', '1' );

        self::set_debug_log();
    }

    /**
     * Debug.
     */
    public function env_debug(): void
    {
        self::define( 'WP_DEBUG', true );
        self::define( 'WP_DEBUG_DISPLAY', true );
        self::define( 'CONCATENATE_SCRIPTS', false );
        self::define( 'SAVEQUERIES', true );

        self::set_debug_log();

        error_reporting( E_ALL );
        ini_set( 'log_errors', '1' );
        ini_set( 'log_errors_max_len', '0' );
        ini_set( 'display_errors', '1' );
        ini_set( 'display_startup_errors', '1' );
    }

    /**
     * Set debug environment.
     */
    protected function set_debug_log(): void
    {
        if ( $this->error_log_dir ) {
            self::define( 'WP_DEBUG_LOG', $this->error_log_dir );
        } else {
            self::define( 'WP_DEBUG_LOG', true );
        }
    }
}
