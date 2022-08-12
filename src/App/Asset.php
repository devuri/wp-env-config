<?php

namespace DevUri\Config\App;

class Asset
{
    /**
     * The Asset url.
     *
     * You can configure the asset URL by setting the ASSET_URL in your .env
     * Or optionally in the main config file.
     *
     * @param string     $asset path to the asset like: "/images/thing.png"
     * @param null|mixed $path
     *
     * @return string
     */
    public static function url( string $asset, ?string $path = null ): string
    {
        if ( $path ) {
            return WP_HOME . $path . $asset;
        }

        return ASSET_URL . '/dist' . $asset;
    }
}
