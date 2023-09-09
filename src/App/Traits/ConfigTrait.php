<?php

namespace Urisoft\App\Traits;

use function defined;

use ReflectionClass;
use ReflectionException;
use Roots\WPConfig\Config;

trait ConfigTrait
{
    /**
     * Wrapper to define config constant items.
     *
     * This will check if the constant is defined before attempting to define.
     * If it is defined then do nothing, that allows them to be overridden, in wp-config.php.
     *
     * @param string $name  constant name.
     * @param mixed  $value constant value
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
            $this->dotenv->required( $name )->notEmpty();
        }
    }

    public function apply(): void
    {
        Config::apply();
    }

    /**
     * Display a list of constants defined by Setup.
     *
     * Retrieves a list of constants defined by the Setup class,
     * but only if the WP_ENVIRONMENT_TYPE constant is set to 'development', 'debug', or 'staging'.
     * If WP_DEBUG is not defined or is set to false, the function returns ['disabled'].
     *
     * @return string[] Returns an array containing a list of constants defined by Setup, or null if WP_DEBUG is not defined or set to false.
     */
    public function get_config_map(): array
    {
        return self::encrypt_secret( $this->config_map, self::env_secrets() );
    }

    /**
     * @throws ReflectionException
     */
    private function set_config_map(): void
    {
        $configClass = 'Roots\WPApp\Config';

        if ( ! \defined( 'WP_DEBUG' ) ) {
            $this->config_map = [ 'disabled' ];

            return;
        }

        if ( \defined( 'WP_DEBUG' ) && false === WP_DEBUG ) {
            $this->config_map = [ 'disabled' ];

            return;
        }

        if ( \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'development', 'debug', 'staging' ], true ) ) {
            $config_map = ( new ReflectionClass( $configClass ) )->getStaticPropertyValue( 'configMap' );

            if ( \is_array( $config_map ) ) {
                $this->config_map = $config_map;
            }

            $this->config_map = [ 'invalid_type_returned' ];
        }
    }
}
