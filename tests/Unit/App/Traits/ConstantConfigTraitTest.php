<?php

namespace Tests\Unit\App\Traits;

use Urisoft\App\Traits\ConstantConfigTrait;
use PHPUnit\Framework\TestCase;

class ConstantConfigTraitTest extends TestCase
{
    use ConstantConfigTrait;

    protected function setUp(): void
    {
        $this->clearDefinedConstants();
    }

    protected function tearDown(): void
    {
        $this->clearDefinedConstants();
    }

    public function testDefineConstant()
    {
        $constName = 'TEST_CONSTANT';
        $constValue = 'TestValue';

        $this->assertFalse($this->is_defined($constName));

        $this->define($constName, $constValue);

        $this->assertTrue($this->is_defined($constName));
        $this->assertEquals($constValue, $this->get_constant($constName));
    }

    public function testDefineDuplicateConstant()
    {
        $constName = 'DUPLICATE_CONSTANT';
        $constValue1 = 'Value1';
        $constValue2 = 'Value2';

        $this->define($constName, $constValue1);

        $this->expectException(ConstantAlreadyDefinedException::class);
        $this->expectExceptionMessage("Constant: $constName has already been defined");

        $this->define($constName, $constValue2);
    }

    protected function clearDefinedConstants()
    {
        // Clear all defined constants to avoid interference between tests.
        foreach (array_keys(static::$constants) as $constant) {
            if ($this->is_defined($constant)) {
                \define($constant, null);
                unset(static::$constants[$constant]);
            }
        }
    }
}
