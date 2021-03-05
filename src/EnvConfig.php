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
	const VERSION = '0.2.6';

	/**
	 * Constructer.
	 *
	 * @param bool $default use default config setup.
	 * @param array  $args  additional args.
	 * @link https://github.com/WordPress/WordPress/blob/master/wp-includes/default-constants.php
	 */
	public function __construct( $path, $setup = null ) {

		// define directory path.
		$this->path = $path;

		// check whether a .env file exists.
		if ( ! file_exists( $this->path . '/.env') ) {
			exit(" env file was not found" );
		}

		$dotenv = Dotenv::createImmutable($this->path);
		$this->env = $dotenv;

			try {
				$dotenv->load();
			} catch ( \Exception $e ) {
				exit( $e->getMessage() );
			}

		// self::init( __DIR__, 'production' )
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

		// print_r( ABSPATH );
		// print_r( @get_defined_constants() );

	}

	// required vars.
	private function is_required() {

		try {

			// site url, can overridden in wp-config.php
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
	public function environment( $defined = null ): self {

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
	protected function is_debug( $environment ): self {

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
	abstract function database(): ConfigInterface;

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
	 * Authentication Unique Keys and Salts
	 *
	 * @return self
	 */
	abstract function salts(): ConfigInterface;

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


}
