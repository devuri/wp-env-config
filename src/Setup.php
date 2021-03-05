<?php

namespace DevUri\Config;

use function Env\env;

/**
 * Setup WP Config.
 */
class Setup extends EnvConfig
{

	protected static function const( $key ){

		$constant = [];
		$constant['environment'] = 'development';
		$constant['debug']       = true;
		$constant['db_host']     = 'localhost';
		$constant['uploads']     = 'wp-content/uploads';
		$constant['optimize']    = false;
		$constant['memory']      = '256M';
		$constant['ssl_admin']   = true;
		$constant['ssl_login']   = true;
		$constant['autosave']    = 180;
		$constant['revisions']   = 10;

		return $constant[$key];
	}

	/**
	 * Singleton
	 *
	 * @return object
	 */
	public static function init( $path, $setup = null ) {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( $path, $setup );
		}
		return self::$instance;
	}

	/**
	 * Debug Settings
	 *
	 * @return void
	 */
	public function debug( $environment ): self {

		// check debug settings.
		$this->is_debug( $environment );

		// debugger
		if ( defined('WP_DEBUG') && false === WP_DEBUG ) :

			// Disable Plugin and Theme Editor.
			self::define( 'DISALLOW_FILE_EDIT', true );

			self::define('WP_DEBUG_DISPLAY', false);
			self::define('WP_DEBUG_LOG', true );
			self::define('SCRIPT_DEBUG', false);
			self::define('WP_CRON_LOCK_TIMEOUT', 60);
			self::define('EMPTY_TRASH_DAYS', 15);
			ini_set('display_errors', '0');

			//Block External URL Requests.
			//@link https://wordpress.org/support/article/editing-wp-config-php/#block-external-url-requests
			// self::define( 'WP_HTTP_BLOCK_EXTERNAL', true );
			// self::define( 'WP_ACCESSIBLE_HOSTS',
			// 		'api.wordpress.org,*.github.com' );

		elseif ( defined('WP_DEBUG') &&  true === WP_DEBUG ) :

		   self::define('SAVEQUERIES', true);
		   self::define('WP_DEBUG_DISPLAY', true);
		   self::define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
		   self::define('SCRIPT_DEBUG', true);
		   self::define('WP_DEBUG_LOG', $this->path . '/tmp/wp-errors.log' );
		   ini_set('display_errors', '1');

		endif;

		return $this;
	}

	/**
	 * Site Url Settings
	 *
	 * @return self
	 */
	public function site_url(): self {

		self::define('WP_HOME', env('WP_HOME')  );
		self::define('WP_SITEURL', env('WP_SITEURL') );

		return $this;
	}

	/**
	 * Uploads Directory Setting
	 *
	 * @return self
	 */
	public function uploads(): self {

		self::define( 'UPLOADS', env('UPLOAD_DIR') ?: self::const( 'uploads' ) );
		return $this;
	}

	/**
	 *  DB settings
	 *
	 * @return self
	 */
	public function database(): self {
 	   self::define('DB_NAME', env('DB_NAME') );
 	   self::define('DB_USER', env('DB_USER') );
 	   self::define('DB_PASSWORD', env('DB_PASSWORD') );
 	   self::define('DB_HOST', env('DB_HOST') ?: self::const( 'db_host' ) );
 	   self::define('DB_CHARSET', 'utf8mb4');
 	   self::define('DB_COLLATE', '');
	   return $this;
	}

	/**
	 * Optimize
	 *
	 * @return self
	 */
	public function optimize(): self {
	   self::define('CONCATENATE_SCRIPTS', env('CONCATENATE_SCRIPTS') ?: self::const( 'optimize' ) );
	   return $this;
	}

	/**
	 * Memory Settings
	 *
	 * @return self
	 */
	public function memory(): self {
		self::define('WP_MEMORY_LIMIT', env('MEMORY_LIMIT')  ?: self::const( 'memory' ) );
		self::define('WP_MAX_MEMORY_LIMIT', env('MAX_MEMORY_LIMIT') ?: self::const( 'memory' ) );
		return $this;
	}

	/**
	 * Authentication Unique Keys and Salts
	 *
	 * @return self
	 */
	public function salts(): self {
		self::define('AUTH_KEY', env('AUTH_KEY') );
		self::define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY') );
		self::define('LOGGED_IN_KEY', env('LOGGED_IN_KEY') );
		self::define('NONCE_KEY', env('NONCE_KEY') );
		self::define('AUTH_SALT', env('AUTH_SALT') );
		self::define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT') );
		self::define('LOGGED_IN_SALT', env('LOGGED_IN_SALT') );
		self::define('NONCE_SALT', env('NONCE_SALT') );
		self::define('DEVELOPERADMIN', env('DEVELOPERADMIN') );
		return $this;
	}

	/**
	 * SSL
	 *
	 * @return self
	 */
	public function force_ssl(): self {
		self::define('FORCE_SSL_ADMIN', env('FORCE_SSL_ADMIN') ?: self::const( 'ssl_admin' ) );
		self::define('FORCE_SSL_LOGIN', env('FORCE_SSL_LOGIN') ?: self::const( 'ssl_login' ) );
		return $this;
	}

	/**
	 * AUTOSAVE and REVISIONS
	 *
	 * @return self
	 */
	public function autosave(): self {
		self::define('AUTOSAVE_INTERVAL', env('AUTOSAVE_INTERVAL') ?: self::const( 'autosave' ) );
		self::define('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?: self::const( 'revisions' ) );
		return $this;
	}

}
