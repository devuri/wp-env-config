<?php

namespace Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    public function default_args(): array
    {
        return [
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
            "sudo_admin_group" => null,
            "sucuri_waf" => false,
            'redis' => [],
            'security' => [],
        ];
    }
}
