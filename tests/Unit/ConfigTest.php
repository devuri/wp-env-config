<?php

namespace Tests\Unit;

use Tests\BaseTest;
use Urisoft\DotAccess;

/**
 * @internal
 *
 * @coversNothing
 */
class ConfigTest extends BaseTest
{
    public function test_config_function_with_valid_key(): void
    {
        $configData = [
            'app' => [
                'name' => 'MyApp',
                'debug' => true,
            ],
            'database' => [
                'host' => 'localhost',
                'port' => 3306,
            ],
        ];

        $keyToTest = 'app.name';

        $result = config($keyToTest, null, new DotAccess(  $configData ) );

        $this->assertEquals($configData['app']['name'], $result);
    }

    public function test_config_function_with_invalid_key(): void
    {
        $keyToTest = 'nonexistent.key';

        $result = config($keyToTest, null, new DotAccess( [] ) );

        $this->assertNull($result);
    }

    protected static function app_config_array_data()
    {
        $_config_array_data = require_once \dirname( __FILE__, 3 ) . '/src/inc/app.php';

        return $_config_array_data;
    }
}
