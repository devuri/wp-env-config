<?php

namespace Tests\Unit\App\Traits;

use PHPUnit\Framework\TestCase;
use Urisoft\App\Exceptions\ConstantAlreadyDefinedException;
use Urisoft\App\Traits\ConstantConfigTrait;

/**
 * @internal
 *
 * @coversNothing
 */
class ConstantConfigTraitTest extends TestCase
{
    use ConstantConfigTrait;

    public function test_define_constant(): void
    {
        $constName = 'TEST_CONSTANT';
        $constValue = 'TestValue';

        $this->assertFalse($this->is_defined($constName));

        $this->define($constName, $constValue);

        $this->assertTrue($this->is_defined($constName));

        $this->assertEquals($constValue, $this->get_constant($constName));
    }

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
