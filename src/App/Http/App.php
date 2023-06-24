<?php

namespace Urisoft\App\Http;

class App
{
    protected $app_path = null;
    protected $config   = null;

    /**
     * Setup App.
     *
     * @param string $app_path The base app path. like __DIR__
     * @param string $options The configuration options filename. like app.php
     */
    public function __construct( string $app_path, string $options = 'app' )
    {
        $this->app_path = $app_path;
        $this->config   = require_once $this->app_path ."/{$options}.php";
    }

    public function kernel(): BaseKernel
    {
        return new BaseKernel( $this->app_path, $this->config );
    }
}
