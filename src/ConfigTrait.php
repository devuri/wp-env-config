<?php

namespace DevUri\Config;

use Roots\WPConfig\Config;
use function Env\env;

trait ConfigTrait {

    /**
     * Env defaults,
     *
     * These are some defaults that will apply
     * if they do not exist in .env
     *
     * @param string $key val to retrieve
     * @return mixed
     */
	protected static function const(string $key){

		$constant = [];
		$constant['environment'] = 'development';
		$constant['debug']       = true;
		$constant['db_host']     = 'localhost';
		$constant['uploads']     = 'wp-content/uploads';
		$constant['optimize']    = true;
		$constant['memory']      = '256M';
		$constant['ssl_admin']   = true;
		$constant['ssl_login']   = true;
		$constant['autosave']    = 180;
		$constant['revisions']   = 10;

		return $constant[$key];
	}

    /**
     * Wrapper to define config constant items.
     *
     * This will check if the constant is defined before attempting to define.
     * If it is defined then do nothing, that allows them be overridden, in wp-config.php.
     *
     * @param string $name constant name.
     * @param string|bool $value constant value
     * @return void
     */
	public static function define(string $name, $value): void {
		if ( ! defined( $name ) ) Config::define( $name, $value);
	}

    public function required(string $name ): void {
		if ( ! defined( $name ) ) $this->env->required( $name )->notEmpty();
	}

    public static function get(string $name)
    {
		try {
			Config::get($name);
		} catch ( \Exception $e ) {
			return $e->getMessage();
		}
	}

    public function apply(): void {
		Config::apply();
	}

	/**
	 * Display a list of constants defined by Setup.
	 *
	 * Debug must be on and 'development' set in the .env file.
	 *
	 * @return bool|array list of constants defined.
	 */
	public function configMap(){

		if ( ! defined('WP_DEBUG') ) return false;

		if ( false === WP_DEBUG ) {
            return false;
        }

		if ( 'development' === env('WP_ENVIRONMENT_TYPE') ) {
			$reflectWPConfig = new \ReflectionClass(new Config);
			return $reflectWPConfig->getStaticPropertyValue('configMap');
		}
	}

}
