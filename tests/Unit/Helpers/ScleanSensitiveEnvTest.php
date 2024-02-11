<?php

namespace Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ScleanSensitiveEnvTest extends TestCase
{
    public function setUp(): void
    {
        putenv('TEST_VAR1=dummyvalue1');
        $_ENV['TEST_VAR2'] = 'dummyvalue2';
    }

    public function tearDown(): void
    {
        putenv('TEST_VAR1');
        unset($_ENV['TEST_VAR2']);
    }

    public function test_sclean_sensitive_env(): void
    {
        // Check that environment variables are initially set
        $this->assertEquals('dummyvalue1', getenv('TEST_VAR1'));
        $this->assertEquals('dummyvalue2', $_ENV['TEST_VAR2'] ?? null);

        // Call the function to clean up the specified
        sclean_sensitive_env(['TEST_VAR1', 'TEST_VAR2']);

        // Verify that the environment variables have been unset
        $this->assertEmpty(getenv('TEST_VAR1'));
        $this->assertArrayNotHasKey('TEST_VAR2', $_ENV);
    }
}
