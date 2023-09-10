<?php

namespace Urisoft\App\Traits;

use Urisoft\App\Exceptions\ConstantAlreadyDefinedException;

/**
 * Trait ConstantBuilderTrait.
 *
 * This trait provides methods for defining and managing constants in your application.
 */
trait ConstantBuilderTrait
{
    /**
     * List of constants defined.
     *
     * @var array
     */
    protected static $constants = [];

	/**
     * list of constants defined by Setup.
     *
     * @var array
     */
    protected $constant_map = [ 'disabled' ];

    /**
     * Define a constant with a value.
     *
     * @param string $const The name of the constant to define.
     * @param mixed  $value The value to assign to the constant.
     *
     * @throws ConstantAlreadyDefinedException if the constant has already been defined.
     */
    public function define( string $const, $value ): void
    {
        if ( $this->is_defined( $const ) ) {
            return;
            // throw new ConstantAlreadyDefinedException( "Constant: $const has already been defined" );
        }

        \define( $const, $value );

        static::$constants[ $const ] = $value;
    }

    /**
     * Check if a constant is defined.
     *
     * @param string $const The name of the constant to check.
     *
     * @return bool True if the constant is defined, false otherwise.
     */
    public function is_defined( string $const ): bool
    {
        return \defined( $const );
    }

    /**
     * Get the value of a defined constant.
     *
     * @param string $key The name of the constant to retrieve.
     *
     * @return null|mixed The value of the constant if defined, null otherwise.
     */
    public function get_constant( string $key )
    {
        if ( isset( static::$constants[ $key ] ) ) {
            return static::$constants[ $key ];
        }

        return null;
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
    public function get_constant_map(): array
    {
        return self::encrypt_secret( $this->constant_map, self::env_secrets() );
    }

	/**
     * Set the constant map based on environmental conditions.
     *
     * This method determines the constant map based on the presence of WP_DEBUG and the environment type.
     * If WP_DEBUG is not defined or set to false, the constant map will be set to ['disabled'].
     * If the environment type is 'development', 'debug', or 'staging', it will use the static $constants property
     * as the constant map if it's an array; otherwise, it will set the constant map to ['invalid_type_returned'].
     */
    protected function set_constant_map(): void
    {
        if ( ! \defined( 'WP_DEBUG' ) ) {
            $this->constant_map = [ 'disabled' ];

            return;
        }

        if ( \defined( 'WP_DEBUG' ) && false === WP_DEBUG ) {
            $this->constant_map = [ 'disabled' ];

            return;
        }

        if ( \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'development', 'debug', 'staging' ], true ) ) {
            $constant_map = static::$constants;

            if ( \is_array( $constant_map ) ) {
                $this->constant_map = $constant_map;
            }

            $this->constant_map = [ 'invalid_type_returned' ];
        }
    }
}
