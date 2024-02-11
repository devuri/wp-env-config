<?php

namespace Tests\Unit\Helpers;

use Tests\BaseTest;

/**
 * @internal
 *
 * @coversNothing
 */
class HelpersTest extends BaseTest
{
    public function test_asset_function(): void
    {
        \define( 'WP_HOME', 'https://example.com');

        $asset_url = asset( "/images/thing.png" );

        $this->assertIsString($asset_url);

        $default = "https://example.com/assets/dist/images/thing.png";

        $this->assertSame($default, $asset_url);
    }

    public function test_asset_custom_path(): void
    {
        $asset_url = asset( "/images/thing.png", "/static" );

        $this->assertIsString($asset_url);

        $static_path = "https://example.com/static/images/thing.png";

        $this->assertSame($static_path, $asset_url);
    }

    public function test_asset_url_return_url_only(): void
    {
        $assets = asset_url();

        $asset_url = $assets . "images/thing.png";

        $this->assertIsString($assets);

        $this->assertSame($assets, "https://example.com/assets/dist/");

        $this->assertIsString($asset_url);

        $url = "https://example.com/assets/dist/images/thing.png";

        $this->assertSame($url, $asset_url);
    }

    public function test_static_asset_url_return(): void
    {
        $static_url = asset_url('/static');

        $this->assertIsString($static_url);

        $url = "https://example.com/static/";

        $this->assertSame($url, $static_url);
    }
}
