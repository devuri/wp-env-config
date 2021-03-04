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
	const VERSION = '0.1.0';

	/**
	 * Singleton
	 *
	 * @return object
	 */
	public static function init( $setup, $env ) {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( $setup, $env );
		}
		return self::$instance;
	}

	/**
	 * Constructer.
	 *
	 * @param bool $default use default config setup.
	 * @param array  $args  additional args.
	 */
	private function __construct( $setup = null, Dotenv $env ) {

		$this->env = $env;

		// Setup::init( 'production' )
		if ( ! is_array( $setup ) ) {
			$setup = array( 'environment' => $setup );
		}

		// defines the website url.
		define( 'SITEURL', ( $_SERVER['HTTPS'] ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] );

		/**
		 *
		 * @var array
		 */
		$default = array(
		    'default'     => true,
		    'environment' => null,
		    'debug'       => true,
		    'symfony'     => false,
		    'uploads'     => null,
		    'content'     => false,
		    'site_url'    => SITEURL,
		);

		$setup = array_merge( $setup, $default );

		// Get the values from $_ENV, instead getenv().
		Env::$options = Env::USE_ENV_ARRAY;

		$this->is_required();

		// run default setup using env vars.
		if ( $setup['default'] ) {
			$this->environment( $setup['environment'] )
				->debug( $setup['debug'] )
				->symfony_debug( $setup['symfony'] )
				->database()
				->site_url( $setup['site_url'] )
				->uploads( $setup['uploads'] )
				->content_directory( $setup['content'] )
				->memory()
				->salts();
			self::apply();
		}

	}

	/**
	 * Wrapper to define config constants items.
	 *
	 * @param  string $name  constant name.
	 * @param  string|bool $value constant value
	 * @return void
	 */
	public static function define( $name, $value ): void {
		Config::define( $name, $value);
	}

	public static function get( $name ): void {
		Config::get($name);
	}

	public static function apply(): void {
		Config::apply();
	}

	private function is_required() {
		// required vars.
		try {

			// db vars.
			$this->env->required( 'DB_HOST' )->notEmpty();
			$this->env->required( 'DB_NAME' )->notEmpty();
			$this->env->required( 'DB_USER' )->notEmpty();
			$this->env->required( 'DB_PASSWORD' )->notEmpty();
			$this->env->required( 'DB_HOST' )->notEmpty();
			$this->env->required( 'DB_PREFIX' )->notEmpty();

			// salts.
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

		Setup::define('WP_ENVIRONMENT_TYPE', env('WP_ENVIRONMENT_TYPE') ?: $defined );
		return $this;
	}

	/**
	 * Debug Settings
	 *
	 * @return void
	 */
	public function debug( $debug = true ): self {

		if ( false === $debug ) {
			return $this;
		}

		/**
		 * Turns on WP_DEBUG mode based on on environment, off for 'production'.
		 *
		 * To enable just define WP_ENV in .env file as 'staging' or 'development' etc
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
		   Setup::define('WP_DEBUG_LOG', dirname( __FILE__ ) . '/tmp/wp-errors.log' );
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
	public function site_url( $url ): self {

		Setup::define('WP_HOME', env('WP_HOME') ?: $url );
		Setup::define('WP_SITEURL', env('WP_SITEURL')  ?: $url );

		return $this;
	}

	/**
	 * Uploads Directory Setting
	 *
	 * @return self
	 */
	public function uploads( $uploads = null ): self {

		if ( is_null( $uploads ) ) {
			return $this;
		}

		Setup::define( 'UPLOADS', env('UPLOAD_DIR') ?: $uploads );
		return $this;
	}

	/**
	 * Content Directory Setting
	 *
	 * @return self
	 */
	public function content_directory( $dir = false ): self {

		if ( false === $dir ) {
			return $this;
		}

		Setup::define('CONTENT_DIR', '/app');
		Setup::define('WP_CONTENT_DIR', dirname( __FILE__ ) . '/public/app/' );
		Setup::define('WP_CONTENT_URL', Setup::get('WP_HOME') . Setup::get('CONTENT_DIR') );
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
 	   Setup::define('DB_HOST', env('DB_HOST') ?: 'localhost');
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
	   Setup::define('CONCATENATE_SCRIPTS', env('CONCATENATE_SCRIPTS') ?: true );
	   return $this;
	}

	/**
	 * Memory Settings
	 *
	 * @return self
	 */
	public function memory(): self {
		/* Change WP_MEMORY_LIMIT to increase the memory limit for public pages. */
		Setup::define('WP_MEMORY_LIMIT', env('MEMORY_LIMIT')  ?: '256M' );

		/* Uncomment and change WP_MAX_MEMORY_LIMIT to increase the memory limit for admin pages. */
		Setup::define('WP_MAX_MEMORY_LIMIT', env('MAX_MEMORY_LIMIT') ?: '256M' );

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
}
