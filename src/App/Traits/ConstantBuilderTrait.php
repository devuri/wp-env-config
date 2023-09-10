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
}
