<?php

namespace DevUri\Config;

use function Env\env;

/**
 * Setup WP Config.
 */
class Setup extends EnvConfig
{

    /**
     * Singleton
     *
     * @param $path
     * @return object
     */
	public static function init( $path ): ConfigInterface {

		if ( ! isset( self::$instance ) ) self::$instance = new self( $path );
		return self::$instance;
	}

    /**
     * Runs config setup with default setting.
     *
     * @param array|null $setup
     * @return void
     */
	public function config($setup = null): void {

		// check required vars.
		$this->is_required();

		// self::init( __DIR__ )->config('production')
		if ( ! is_array( $setup ) ) {
			$setup = array( 'environment' => $setup );
		}

		// default setup.
		$default = [
			'default'     => true,
			'environment' => null,
			'symfony'     => false,
        ];

        $setup = array_merge( $default, $setup );

		if ( $setup['default'] ) {
			$this->environment( $setup['environment'] )
				->debug( $setup['environment'] )
				->symfony_debug( $setup['symfony'] )
				->database()
				->site_url()
				->uploads( $setup['uploads'] )
				->memory()
				->optimize()
				->force_ssl()
				->autosave()
				->salts();
			self::apply();
		}
	}

    /**
     * Debug Settings
     *
     * @param $environment
     * @return Setup
     */
	public function debug( $environment ): ConfigInterface
    {

		$this->is_debug( $environment );

		/**
		 * Debugger setup based on environment.
		 */
		if ( defined('WP_DEBUG') && (false === WP_DEBUG) ) :

			// Disable Plugin and Theme Editor.
			self::define( 'DISALLOW_FILE_EDIT', true );

			self::define('WP_DEBUG_DISPLAY', false);
			self::define('WP_DEBUG_LOG', true );
			self::define('SCRIPT_DEBUG', false);
			self::define('WP_CRON_LOCK_TIMEOUT', 60);
			self::define('EMPTY_TRASH_DAYS', 15);
			ini_set('display_errors', '0');

			// Block External URL Requests.
			// @link https://wordpress.org/support/article/editing-wp-config-php/#block-external-url-requests
			// self::define( 'WP_HTTP_BLOCK_EXTERNAL', true );
			// self::define( 'WP_ACCESSIBLE_HOSTS', 'api.wordpress.org,*.github.com' );

		elseif ( defined('WP_DEBUG') && (true === WP_DEBUG)) :

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
	public function site_url(): ConfigInterface {

		self::define('WP_HOME', env('WP_HOME')  );
		self::define('WP_SITEURL', env('WP_SITEURL') );

		return $this;
	}

    /**
     * Uploads Directory Setting
     *
     * @param $upload_dir
     * @return self
     */
	public function uploads( string $upload_dir ): ConfigInterface {

		self::define( 'UPLOADS', env('UPLOAD_DIR') ?: self::const( 'uploads' ) );
		return $this;
	}

	/**
	 * Optimize
	 *
	 * @return self
	 */
	public function optimize(): ConfigInterface {
	   self::define('CONCATENATE_SCRIPTS', env('CONCATENATE_SCRIPTS') ?: self::const( 'optimize' ) );
	   return $this;
	}

	/**
	 * Memory Settings
	 *
	 * @return self
	 */
	public function memory(): ConfigInterface {
		self::define('WP_MEMORY_LIMIT', env('MEMORY_LIMIT')  ?: self::const( 'memory' ) );
		self::define('WP_MAX_MEMORY_LIMIT', env('MAX_MEMORY_LIMIT') ?: self::const( 'memory' ) );
		return $this;
	}

	/**
	 * SSL
	 *
	 * @return self
	 */
	public function force_ssl(): ConfigInterface {
		self::define('FORCE_SSL_ADMIN', env('FORCE_SSL_ADMIN') ?: self::const( 'ssl_admin' ) );
		self::define('FORCE_SSL_LOGIN', env('FORCE_SSL_LOGIN') ?: self::const( 'ssl_login' ) );
		return $this;
	}

	/**
	 * AUTOSAVE and REVISIONS
	 *
	 * @return self
	 */
	public function autosave(): ConfigInterface {
		self::define('AUTOSAVE_INTERVAL', env('AUTOSAVE_INTERVAL') ?: self::const( 'autosave' ) );
		self::define('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?: self::const( 'revisions' ) );
		return $this;
	}

}
