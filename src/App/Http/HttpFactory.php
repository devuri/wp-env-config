<?php

namespace Urisoft\App\Http;

class HttpFactory
{
    /**
     * Creates and returns an instance of AppHostManager.
     *
     * @return AppHostManager An instance of the AppHostManager class.
     */
    public static function init(): AppHostManager
    {
        return new AppHostManager();
    }
}
