<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class EnvHelperTest extends TestCase
{
    public function test_env_variable_exists()
    {
        // Simulate the presence of an environment variable
        $_ENV['TEST_VAR'] = 'true';

        $this->assertEquals(true, env('TEST_VAR'));
    }

    public function test_env_variable_does_not_exist()
    {
        // Ensure the environment variable is not set
        unset($_ENV['NON_EXISTENT_VAR']);

        // Provide a default value
        $this->assertEquals('default', env('NON_EXISTENT_VAR', 'default'));
    }

    public function test_env_variable_is_null()
    {
        // Set the environment variable to null
        $_ENV['NULL_VAR'] = null;

        $this->assertEquals('default', env('NULL_VAR', 'default'));
    }

    public function test_env_variable_is_numeric()
    {
        // Set the environment variable to a numeric value
        $_ENV['NUMERIC_VAR'] = '42';

        $this->assertEquals(42, env('NUMERIC_VAR'));
    }

    public function test_to_towercase_conversion()
    {
        // Set the environment variable to an uppercase value
        $_ENV['UPPERCASE_VAR'] = 'Uppercase';

        $this->assertEquals('uppercase', env('UPPERCASE_VAR', null, true));
    }
}
