<?php

namespace Tests\Unit;

use Tests\BaseTest;
use DevUri\Config\App\HttpKernel;

/**
 * Test the Kernel.
 */
class HttpKernelTest extends BaseTest
{
	public static function http_app(): HttpKernel
    {
		$httpapp = new HttpKernel( getenv('FAKE_APP_DIR_PATH') );

		return $httpapp;
    }

	public function test_get_app_path(): void
	{
		$this->assertEquals( '/srv/users/dev/apps/example', self::http_app()->get_app_path() );
	}

	public function test_empty_args(): void
	{
		$this->assertEquals( [], self::http_app()->get_args());
	}

	public function test_constants_defined(): void
	{
		self::http_app()->constants();

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

		$this->assertIsArray( self::http_app()->get_defined() );

		$count = count( self::http_app()->get_defined() );

		$this->assertEquals( 13, $count );

		$this->assertEquals( $const_defaults, self::http_app()->get_defined());
	}
}
