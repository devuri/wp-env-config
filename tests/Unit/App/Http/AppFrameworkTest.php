<?php

namespace Tests\Unit\App\Http;

use Tests\BaseTest;

/**
 * Test the Kernel.
 *
 * @internal
 *
 * @coversNothing
 */
class AppFrameworkTest extends BaseTest
{
    public function test_class_exists_is_true(): void
    {
        $this->assertTrue( class_exists('Urisoft\App\Http\AppFramework') );
    }
}
