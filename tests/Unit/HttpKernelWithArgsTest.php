<?php

namespace Tests\Unit;

use DevUri\Config\App\HttpKernel;
use Tests\BaseTest;

/**
 * Test the Kernel.
 *
 * @internal
 *
 * @coversNothing
 */
class HttpKernelWithArgsTest extends BaseTest
{
    public function test_http_app_with_args(): void
    {
        $args = [
            'web_root'      => 'public',
            'wordpress'     => 'cms',
        ];

        $app_with_args = new HttpKernel( getenv('FAKE_APP_DIR_PATH'), $args );

        $output = [
            'web_root'        => 'public',
            'wp_dir_path'     => 'cms',
            'wordpress'       => 'cms',
            'asset_dir'       => 'assets',
            'content_dir'     => 'content',
            'plugin_dir'      => 'plugins',
            'mu_plugin_dir'   => 'mu-plugins',
            'disable_updates' => true,
        ];

        $this->assertEquals( $output, $app_with_args->get_args());
    }
}
