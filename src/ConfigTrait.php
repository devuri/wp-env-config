<?php

namespace DevUri\Config;

use function Env\env;

use Exception;
use ReflectionClass;
use Roots\WPConfig\Config;

trait ConfigTrait
{
    /**
     * Wrapper to define config constant items.
     *
     * This will check if the constant is defined before attempting to define.
     * If it is defined then do nothing, that allows them be overridden, in wp-config.php.
     *
     * @param string      $name  constant name.
     * @param bool|string $value constant value
     *
     * @return void
     */
    public static function define( string $name, $value ): void
    {
        if ( ! \defined( $name ) ) {
            Config::define( $name, $value );
        }
    }

    public function required( string $name ): void
    {
        if ( ! \defined( $name ) ) {
			// @phpstan-ignore-next-line.
            $this->env->required( $name )->notEmpty();
        }
    }

    public static function get( string $name )
    {
        try {
            Config::get( $name );
        } catch ( Exception $e ) {
            return $e->getMessage();
        }
    }

    public function apply(): void
    {
        Config::apply();
    }

    /**
     * Display a list of constants defined by Setup.
     *
     * Debug must be on and 'development' set as WP_ENVIRONMENT_TYPE in the .env file.
     *
     * @return array|null list of constants defined.
     */
    public function configMap(): ?array
    {
        $configClass = 'Config';

        if ( ! \defined( 'WP_DEBUG' ) ) {
            return null;
        }

        if ( false === WP_DEBUG ) {
            return null;
        }

        if ( 'development' === env( 'WP_ENVIRONMENT_TYPE' ) ) {
            return ( new ReflectionClass( $configClass ) )->getStaticPropertyValue( 'configMap' );
        }

		return null;
    }
    /**
     * Env defaults,.
     *
     * These are some defaults that will apply
     * if they do not exist in .env
     *
     * @param string $key val to retrieve
     *
     * @return mixed
     */
    protected static function const( string $key )
    {
        $constant['environment'] = 'development';
        $constant['debug']       = true;
        $constant['db_host']     = 'localhost';
        $constant['optimize']    = true;
        $constant['memory']      = '256M';
        $constant['ssl_admin']   = true;
        $constant['ssl_login']   = true;
        $constant['autosave']    = 180;
        $constant['revisions']   = 10;

        return $constant[ $key ] ?? null;
    }
}
