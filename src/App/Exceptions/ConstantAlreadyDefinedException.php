<?php

namespace Urisoft\App\Exceptions;

/**
 * Exception thrown when attempting to define() a constant that has already been defined.
 *
 * This exception is thrown when a user tries to define a PHP constant using the define() function,
 * but the constant has already been defined elsewhere in the code.
 *
 * @package Roots\Bedrock
 */
class ConstantAlreadyDefinedException extends \RuntimeException
{
    // Class implementation goes here
}
