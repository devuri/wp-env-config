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
        return new BaseKernel( APP_TEST_PATH );
    }

    public function test_get_app_path(): void
    {
        $this->assertEquals( APP_TEST_PATH , $this->http_app()->get_app_path() );
    }

    public function test_default_args(): void
    {
        $default_args = [
            "web_root" => "public",
            "wp_dir_path" => "wp",
            "wordpress" => "wp",
            "asset_dir" => "assets",
            "content_dir" => "content",
            "plugin_dir" => "plugins",
            "mu_plugin_dir" => "mu-plugins",
            "sqlite_dir" => "sqlitedb",
            "sqlite_file" => ".sqlite-wpdatabase",
            "default_theme" => "twentytwentythree",
            "disable_updates" => true,
            "can_deactivate" => true,
            "error_handler" => "symfony",
            "config_file" => "config",
            'templates_dir' => null,
            "sudo_admin" => null,
            "sucuri_waf" => false,
        ];

        $this->assertEquals( $default_args, $this->http_app()->get_args());
    }

    public function test_constants_defined(): void
    {
        $this->http_app()->constants();

        $const_defaults = [
            "APP_PATH" => APP_TEST_PATH,
            "PUBLIC_WEB_DIR" => APP_TEST_PATH . "/public",
            "WP_DIR_PATH" => APP_TEST_PATH . "/public/wp",
            "APP_ASSETS_DIR" => APP_TEST_PATH . "/public/assets",
            "APP_CONTENT_DIR" => "/content",
            "WP_CONTENT_DIR" => APP_TEST_PATH . "/public/content",
            "CONTENT_DIR" => "/content",
            "WP_CONTENT_URL" => "https://example.com/content",
            "WP_PLUGIN_DIR" => APP_TEST_PATH . "/public/plugins",
            "WP_PLUGIN_URL" => "https://example.com/plugins",
            "WPMU_PLUGIN_DIR" => APP_TEST_PATH . "/public/mu-plugins",
            "WPMU_PLUGIN_URL" => "https://example.com/mu-plugins",
            "AUTOMATIC_UPDATER_DISABLED" => true,
            "WP_SUDO_ADMIN" => null,
            "CAN_DEACTIVATE_PLUGINS" => true,
            "DB_DIR" => APP_TEST_PATH . "/sqlitedb",
            "DB_FILE" => ".sqlite-wpdatabase",
            "WP_DEFAULT_THEME" => "twentytwentythree",
            "COOKIEHASH" => "c984d06aafbecf6bc55569f964148ea3",
            "USER_COOKIE" => "wpc_user_c984d06aafbecf6bc55569f964148ea3",
            "PASS_COOKIE" => "wpc_pass_c984d06aafbecf6bc55569f964148ea3",
            "AUTH_COOKIE" => "wpc_c984d06aafbecf6bc55569f964148ea3",
            "SECURE_AUTH_COOKIE" => "wpc_sec_c984d06aafbecf6bc55569f964148ea3",
            "LOGGED_IN_COOKIE" => "wpc_logged_in_c984d06aafbecf6bc55569f964148ea3",
            "TEST_COOKIE" => "613df23f4d18ac79c829ba8c18b503e4",
            "ENABLE_SUCURI_WAF" =>   false,
            "SUCURI_DATA_STORAGE" =>   APP_TEST_PATH . "../../storage/logs/sucuri",
        ];

        $this->assertIsArray( $this->http_app()->get_defined() );

        $count = \count( $this->http_app()->get_defined() );

        $this->assertEquals( 27, $count );

        $this->assertEquals( $const_defaults, $this->http_app()->get_defined());
    }
}
