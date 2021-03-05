<?php

namespace DevUri\Config;

use Roots\WPConfig\Config;

trait ConfigTrait {

	protected static function const( $key ){

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
}
