<?php

namespace Tests\Unit;

use Tests\BaseTest;

/**
 * @internal
 * @coversNothing
 */
class HelpersTest extends BaseTest
{
    public function test_asset_function(): void
    {
        \define( 'WP_HOME', 'https://example.com');

        \define( 'ASSET_URL', 'https://example.com/assets');

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
        $asset_url = asset_url() . "images/thing.png";

        $this->assertIsString($asset_url);

        $url = "https://example.com/assets/dist/images/thing.png";

        $this->assertSame($url, $asset_url);
    }
}
