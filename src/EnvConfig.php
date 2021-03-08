<?php

namespace DevUri\Config;

use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\ErrorHandler;
use Symfony\Component\ErrorHandler\DebugClassLoader;
use Dotenv\Dotenv;
use function Env\env;
use Env\Env;

/**
 * Setup WP Config.
 */
abstract class EnvConfig implements ConfigInterface
{

	use ConfigTrait;

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
	protected static $instance;

	/**
	 * Defines Version
	 */
	const VERSION = '0.4.1';

	/**
	 * Constructer.
	 *
	 * @param array  $path  current Directory.
	 */
	public function __construct( $path ) {

		$this->path = $path;

		$dotenv = Dotenv::createImmutable($this->path);
		$this->env = $dotenv;

			try {
				$dotenv->load();
			} catch ( \Exception $e ) {
				exit( $e->getMessage() );
			}

		Env::$options = Env::USE_ENV_ARRAY;

	}

	/**
	 * Runs config setup.
	 *
	 * Define in child class.
	 *
	 * @param  array $setup
	 * @return void
	 */
	abstract function config( $setup ): void;

	// required vars.
	protected function is_required() {

		try {

			// site url, can be overridden in wp-config.php
			$this->required( 'WP_HOME' );
			$this->required( 'WP_SITEURL' );

			// db vars must be defined in .env.
			$this->env->required( 'DB_HOST' )->notEmpty();
			$this->env->required( 'DB_NAME' )->notEmpty();
			$this->env->required( 'DB_USER' )->notEmpty();
			$this->env->required( 'DB_PASSWORD' )->notEmpty();

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
	public function environment( $defined = null ): ConfigInterface {

		if ( is_null( $defined ) ) {
			self::define('WP_ENVIRONMENT_TYPE', env('WP_ENVIRONMENT_TYPE') ?: self::const( 'environment' ) );
		}

		self::define('WP_ENVIRONMENT_TYPE', $defined );
		return $this;
	}

	/**
	 * Debug Settings
	 *
	 * @return void
	 */
	protected function is_debug( $environment ): ConfigInterface {

		if ( 'production' === $environment ) {
			define( 'WP_DEBUG', false );
		} else {
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
		}
		return $this;
	}

	/**
	 * Symfony Debug.
	 *
	 * @return self
	 */
	public function symfony_debug( $enable = false ): ConfigInterface {

		if ( false === $enable ) {
			return $this;
		}

		if ( defined('WP_DEBUG') &&  true === WP_DEBUG ) :
			Debug::enable();
		endif;

		return $this;

	}

	/**
	 * Debug Settings
	 *
	 * @return void
	 */
	abstract function debug( $environment ): ConfigInterface ;

	/**
	 * Site Url Settings
	 *
	 * @return self
	 */
	abstract function site_url(): ConfigInterface;

	/**
	 * Uploads Directory Setting
	 *
	 * @return self
	 */
	abstract function uploads(): ConfigInterface;

	/**
	 *  DB settings
	 *
	 * @return self
	 */
	public function database(): ConfigInterface {
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
	abstract function optimize(): ConfigInterface;

	/**
	 * Memory Settings
	 *
	 * @return self
	 */
	abstract function memory(): ConfigInterface;

	/**
	 * SSL
	 *
	 * @return self
	 */
	abstract function force_ssl(): ConfigInterface;

	/**
	 * AUTOSAVE and REVISIONS
	 *
	 * @return self
	 */
	abstract function autosave(): ConfigInterface;

	/**
	 * Authentication Unique Keys and Salts
	 *
	 * @return self
	 */
	public function salts(): ConfigInterface {
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

}
