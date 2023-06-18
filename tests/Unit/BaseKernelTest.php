<?php

namespace Tests\Unit;

use Tests\BaseTest;
use Urisoft\App\Http\BaseKernel;

/**
 * Test the Kernel.
 *
 * @internal
 *
 * @coversNothing
 */
class BaseKernelTest extends BaseTest
{
    public function http_app(): BaseKernel
    {
        return new BaseKernel( getenv('FAKE_APP_DIR_PATH') );
    }

    public function test_get_app_path(): void
    {
        $this->assertEquals( '/srv/users/dev/apps/example', $this->http_app()->get_app_path() );
    }

    public function test_default_args(): void
    {
        $default_args = [
            'web_root'        => 'public',
            'wp_dir_path'     => 'wp',
            'wordpress'       => 'wp',
            'asset_dir'       => 'assets',
            'content_dir'     => 'content',
            'plugin_dir'      => 'plugins',
            'mu_plugin_dir'   => 'mu-plugins',
            'sqlite_dir'      => 'sqlitedb',
            'sqlite_file'     => '.sqlite-wpdatabase',
            'disable_updates' => true,
        ];

        $this->assertEquals( $default_args, $this->http_app()->get_args());
    }

    public function test_constants_defined(): void
    {
        $this->http_app()->constants();

        $const_defaults = [
            'APP_PATH' => '/srv/users/dev/apps/example',
            'PUBLIC_WEB_DIR' => '/srv/users/dev/apps/example/public',
            'WP_DIR_PATH' => '/srv/users/dev/apps/example/public/wp',
            'APP_ASSETS_DIR' => '/srv/users/dev/apps/example/public/assets',
            'AUTOMATIC_UPDATER_DISABLED' => true,
            'APP_CONTENT_DIR' => '/content',
            'WP_CONTENT_DIR' => '/srv/users/dev/apps/example/public/content',
            'CONTENT_DIR' => '/content',
            'WP_CONTENT_URL' => 'https://example.com/content',
            'WP_PLUGIN_DIR' => '/srv/users/dev/apps/example/public/plugins',
            'WP_PLUGIN_URL' => 'https://example.com/plugins',
            'WPMU_PLUGIN_DIR' => '/srv/users/dev/apps/example/public/mu-plugins',
            'WPMU_PLUGIN_URL' => 'https://example.com/mu-plugins',
        ];

        $this->assertIsArray( $this->http_app()->get_defined() );

        $count = \count( $this->http_app()->get_defined() );

        $this->assertEquals( 13, $count );

        $this->assertEquals( $const_defaults, $this->http_app()->get_defined());
    }
}
