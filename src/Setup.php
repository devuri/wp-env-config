<?php

namespace DevUri\Config;

use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\ErrorHandler\DebugClassLoader;
use Roots\WPConfig\Config;
use Dotenv\Dotenv;
use function Env\env;
use Env\Env;

/**
 * Setup WP Config.
 */
class Setup
{

	/**
	 *  Directory $path
	 */
	public $path;

	/**
	 *  Dotenv $env
	 */
	public $env;

	/**
	 * Private $instance
	 *
	 * @var $instance
	 */
	private static $instance;

	/**
	 * Defines Version
	 */
	const VERSION = '0.2.3';

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
	 * Constructer.
	 *
	 * @param bool $default use default config setup.
	 * @param array  $args  additional args.
	 * @link https://github.com/WordPress/WordPress/blob/master/wp-includes/default-constants.php
	 */
	private function __construct( $path, $setup = null ) {

		// define directory path.
		$this->path = $path;

		// check whether a .env file exists.
		if ( ! file_exists( $this->path . '/.env') ) {
			exit(" env file was not found" );
		}

		$dotenv = Dotenv::createImmutable($this->path);

			try {
				$dotenv->load();
			} catch ( \Exception $e ) {
				exit( $e->getMessage() );
			}

		$this->env = $dotenv;

		// Setup::init( 'production' )
		if ( ! is_array( $setup ) ) {
			$setup = array( 'environment' => $setup );
		}

		// defualt setup.
		$default = array(
		    'default'     => true,
		    'environment' => null,
		    'symfony'     => false,
		);
		$setup = array_merge( $default, $setup );

		// Get the values from $_ENV, instead getenv().
		Env::$options = Env::USE_ENV_ARRAY;

		$this->is_required();

		// run default setup using env vars.
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

	private static function const( $key ){

		$constant = [];
		$constant['environment']    = 'development';
		$constant['debug']          = true;
		$constant['db_host']        = 'localhost';
		$constant['uploads']        = 'wp-content/uploads';
		$constant['optimize']       = true;
		$constant['memory']         = '256M';
		$constant['ssl_admin']      = true;
		$constant['ssl_login']      = true;
		$constant['autosave']       = 180;
		$constant['revisions']      = 10;

		return $constant[$key];
	}

	/**
	 * Wrapper to define config constant items.
	 *
	 * This will check if the constant is defined before attempting to define.
	 * If it is defined then do nothing, that allows them be overridden, in wp-config.php.
	 *
	 * @param  string $name  constant name.
	 * @param  string|bool $value constant value
	 * @return void
	 */
	public static function define( $name, $value ): void {
		if ( ! defined( $name ) ) {
			Config::define( $name, $value);
		}
	}

	public function required( $name ): void {
		if ( ! defined( $name ) ) {
			$this->env->required( $name )->notEmpty();
		}
	}

	public static function get( $name ): void {
		Config::get($name);
	}

	public static function apply(): void {
		Config::apply();
	}

	// required vars.
	private function is_required() {

		try {

			// site url
			$this->required( 'WP_HOME' );
			$this->required( 'WP_SITEURL' );

			// db vars must be defined in .env.
			$this->env->required( 'DB_HOST' )->notEmpty();
			$this->env->required( 'DB_NAME' )->notEmpty();
			$this->env->required( 'DB_USER' )->notEmpty();
			$this->env->required( 'DB_PASSWORD' )->notEmpty();
			$this->env->required( 'DB_HOST' )->notEmpty();
			$this->env->required( 'DB_PREFIX' )->notEmpty();

			// salts must be defined in .env.
			$this->env->required( 'AUTH_KEY' )->notEmpty();
			$this->env->required( 'SECURE_AUTH_KEY' )->notEmpty();
			$this->env->required( 'LOGGED_IN_KEY' )->notEmpty();
			$this->env->required( 'NONCE_KEY' )->notEmpty();
			$this->env->required( 'AUTH_SALT' )->notEmpty();
			$this->env->required( 'SECURE_AUTH_SALT' )->notEmpty();
			$this->env->required( 'LOGGED_IN_SALT' )->notEmpty();
			$this->env->required( 'NONCE_SALT' )->notEmpty();

		} catch (\Exception $e) {
			dump( $e->getMessage() );
			exit();
		}
	}

	/**
	 * Setting the environment type
	 *
	 * @return void
	 */
	public function environment( $defined = null ): self {

		if ( is_null( $defined ) ) {
			Setup::define('WP_ENVIRONMENT_TYPE', env('WP_ENVIRONMENT_TYPE') ?: self::const( 'environment' ) );
		}

		Setup::define('WP_ENVIRONMENT_TYPE', $defined );
		return $this;
	}

	/**
	 * Debug Settings
	 *
	 * @return void
	 */
	private function is_debug( $environment ): bool {

		if ( 'production' === $environment ) {
			return false;
		}
		return true;
	}

	/**
	 * Debug Settings
	 *
	 * @return void
	 */
	public function debug( $environment ): self {

		if ( 'production' === $environment ) {
			define( 'WP_DEBUG', false );
			return $this;
		}

		/**
		 * Turns on WP_DEBUG mode based on on environment, off for 'production'.
		 *
		 * To enable just define WP_ENVIRONMENT_TYPE in .env file as 'staging' or 'development' etc
		 */
		if ( 'production' === env('WP_ENVIRONMENT_TYPE') ) {
			define( 'WP_DEBUG', false );
		} else {
			define( 'WP_DEBUG', true );
		}

		if ( defined('WP_DEBUG') && false === WP_DEBUG ) :

			Setup::define('WP_DEBUG_DISPLAY', false);
			Setup::define('WP_DEBUG_LOG', false);
			Setup::define('SCRIPT_DEBUG', false);
			ini_set('display_errors', '0');

		elseif ( defined('WP_DEBUG') &&  true === WP_DEBUG ) :

		   Setup::define('SAVEQUERIES', true);
		   Setup::define('WP_DEBUG_DISPLAY', true);
		   Setup::define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
		   Setup::define('SCRIPT_DEBUG', true);
		   Setup::define('WP_DEBUG_LOG', $this->path . '/tmp/wp-errors.log' );
		   ini_set('display_errors', '1');

		endif;

		return $this;
	}

	/**
	 * Symfony Debug.
	 *
	 * @return self
	 */
	public function symfony_debug( $enable = false ): self {

		if ( false === $enable ) {
			return $this;
		}

		if ( defined('WP_DEBUG') &&  true === WP_DEBUG ) :
			Debug::enable();
		endif;

		return $this;

	}

	/**
	 * Site Url Settings
	 *
	 * @return self
	 */
	public function site_url(): self {

		Setup::define('WP_HOME', env('WP_HOME')  );
		Setup::define('WP_SITEURL', env('WP_SITEURL') );

		return $this;
	}

	/**
	 * Uploads Directory Setting
	 *
	 * @return self
	 */
	public function uploads(): self {

		Setup::define( 'UPLOADS', env('UPLOAD_DIR') ?: self::const( 'uploads' ) );
		return $this;
	}


	/**
	 *  DB settings
	 *
	 * @return self
	 */
	public function database(): self {
 	   Setup::define('DB_NAME', env('DB_NAME') );
 	   Setup::define('DB_USER', env('DB_USER') );
 	   Setup::define('DB_PASSWORD', env('DB_PASSWORD') );
 	   Setup::define('DB_HOST', env('DB_HOST') ?: self::const( 'db_host' ) );
 	   Setup::define('DB_CHARSET', 'utf8mb4');
 	   Setup::define('DB_COLLATE', '');
	   return $this;
	}

	/**
	 * Optimize
	 *
	 * @return self
	 */
	public function optimize(): self {
	   Setup::define('CONCATENATE_SCRIPTS', env('CONCATENATE_SCRIPTS') ?: self::const( 'optimize' ) );
	   return $this;
	}

	/**
	 * Memory Settings
	 *
	 * @return self
	 */
	public function memory(): self {
		Setup::define('WP_MEMORY_LIMIT', env('MEMORY_LIMIT')  ?: self::const( 'memory' ) );
		Setup::define('WP_MAX_MEMORY_LIMIT', env('MAX_MEMORY_LIMIT') ?: self::const( 'memory' ) );
		return $this;
	}

	/**
	 * Authentication Unique Keys and Salts
	 *
	 * @return self
	 */
	public function salts(): self {
		Setup::define('AUTH_KEY', env('AUTH_KEY') );
		Setup::define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY') );
		Setup::define('LOGGED_IN_KEY', env('LOGGED_IN_KEY') );
		Setup::define('NONCE_KEY', env('NONCE_KEY') );
		Setup::define('AUTH_SALT', env('AUTH_SALT') );
		Setup::define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT') );
		Setup::define('LOGGED_IN_SALT', env('LOGGED_IN_SALT') );
		Setup::define('NONCE_SALT', env('NONCE_SALT') );
		Setup::define('DEVELOPERADMIN', env('DEVELOPERADMIN') );
		return $this;
	}

	/**
	 * SSL
	 *
	 * @return self
	 */
	public function force_ssl(): self {
		Setup::define('FORCE_SSL_ADMIN', env('FORCE_SSL_ADMIN') ?: self::const( 'ssl_admin' ) );
		Setup::define('FORCE_SSL_LOGIN', env('FORCE_SSL_LOGIN') ?: self::const( 'ssl_login' ) );
		return $this;
	}

	/**
	 * AUTOSAVE and REVISIONS
	 *
	 * @return self
	 */
	public function autosave(): self {
		Setup::define('AUTOSAVE_INTERVAL', env('AUTOSAVE_INTERVAL') ?: self::const( 'autosave' ) );
		Setup::define('WP_POST_REVISIONS', env('WP_POST_REVISIONS') ?: self::const( 'revisions' ) );
		return $this;
	}

}
