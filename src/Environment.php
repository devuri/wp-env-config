<?php

namespace DevUri\Config;

/**
 * Setup Environment.
 */
class Environment
{

    use ConfigTrait;

	public static function production(): void {

		// Disable Plugin and Theme Editor.
		self::define('DISALLOW_FILE_EDIT', true );

		self::define('WP_DEBUG_DISPLAY', false);
		self::define('SCRIPT_DEBUG', false);

		self::define('WP_CRON_LOCK_TIMEOUT', 60);
		self::define('EMPTY_TRASH_DAYS', 15);

		self::define( 'WP_DEBUG', false );
		self::define('WP_DEBUG_LOG', false );
		ini_set('display_errors', '0');

	}

	public static function staging(): void {

		self::define( 'DISALLOW_FILE_EDIT', false );

		self::define('WP_DEBUG_DISPLAY', true);
		self::define('SCRIPT_DEBUG', false);

		self::define( 'WP_DEBUG', true );
		self::define('WP_DEBUG_LOG', true );
		ini_set('display_errors', '0');
	}

	public static function development(): void {

		self::define('WP_DEBUG', true );
		self::define('SAVEQUERIES', true);

		self::define('WP_DEBUG_DISPLAY', true);
		self::define('WP_DISABLE_FATAL_ERROR_HANDLER', true);

		self::define('SCRIPT_DEBUG', true);
		self::define('WP_DEBUG_LOG', true );
		ini_set('display_errors', '1');
	}

	public static function debug(): void {

		self::define('WP_DEBUG', true );
		self::define('WP_DEBUG_DISPLAY', true);

		self::define('WP_DEBUG_LOG', true );
		ini_set('display_errors', '0');
	}

}
