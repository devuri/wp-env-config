<?php

namespace Tests\Unit\App\Traits;

use PHPUnit\Framework\TestCase;
use Urisoft\App\Exceptions\ConstantAlreadyDefinedException;
use Urisoft\App\Traits\ConstantBuilderTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class ConstantBuilderTraitTest extends TestCase
{
    use ConstantBuilderTrait;

    public function test_define_constant(): void
    {
        $constName = 'MY_CONSTANT';
        $this->assertFalse(\defined($constName));

        $constValue = 'my_value';
        $this->define($constName, $constValue);

        $this->assertTrue(\defined($constName));

        $this->assertEquals($constValue, \constant($constName));

        // $this->expectException(ConstantAlreadyDefinedException::class);
        // $this->define($constName, 'new_value');
    }

    public function test_is_constant_defined(): void
    {
        $constName = 'ANOTHER_CONSTANT';

        $this->assertFalse($this->is_defined($constName));

        $this->define($constName, 'some_value');

        $this->assertTrue($this->is_defined($constName));
    }

    public function test_get_constant(): void
    {
        $constName = 'BOOLEAN_CONSTANT';
        $constValue = true;

        $this->define($constName, $constValue);

        $result = $this->get_constant($constName);

        $this->assertEquals($constValue, $result);

        $result = $this->get_constant('UNDEFINED_CONSTANT');
        $this->assertNull($result);
    }

    public function test_get_constant_map(): void
    {
        unset($_ENV['WP_DEBUG']);
        $this->assertEquals(['disabled'], $this->get_constant_map());

        $_ENV['WP_DEBUG'] = false;
        $this->assertEquals(['disabled'], $this->get_constant_map());

        $_ENV['WP_DEBUG'] = true;
        $_ENV['WP_ENVIRONMENT_TYPE'] = 'development';
        $this->assertEquals(['disabled'], $this->get_constant_map());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'staging';
        $this->assertEquals(['disabled'], $this->get_constant_map());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'production';
        $this->assertEquals(['disabled'], $this->get_constant_map());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'custom_environment';
        $this->assertEquals(['disabled'], $this->get_constant_map());

        $_ENV['WP_ENVIRONMENT_TYPE'] = 'debug';
        $this->assertEquals(['disabled'], $this->get_constant_map());
    }

    // public function test_define_constant(): void
    // {
    //     $constName = 'TEST_CONSTANT';
    //     $constValue = 'TestValue';
    //
    //     $this->assertFalse($this->is_defined($constName));
    //
    //     $this->define($constName, $constValue);
    //
    //     $this->assertTrue($this->is_defined($constName));
    //
    //     $this->assertEquals($constValue, $this->get_constant($constName));
    // }

    // public function test_define_duplicate_constant(): void
    // {
    //     $constName = 'DUPLICATE_CONSTANT';
    //     $constValue1 = 'Value1';
    //     $constValue2 = 'Value2';
    //
    //     $this->define($constName, $constValue1);
    //
    //     $this->expectException(ConstantAlreadyDefinedException::class);
    //     $this->expectExceptionMessage("Constant: $constName has already been defined");
    //
    //     $this->define($constName, $constValue2);
    // }
}
