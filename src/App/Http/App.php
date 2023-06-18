<?php

namespace Urisoft\App\Http;

class App
{
    protected $app_path = null;
    protected $config   = null;

    /**
     * Setup App.
     *
     * @param string $app_path
     */
    public function __construct( string $app_path )
    {
        $this->app_path = $app_path;
        $this->config   = require_once $this->app_path . '/app.php';
    }

    public function kernel(): BaseKernel
    {
        return new BaseKernel( $this->app_path, $this->config );
    }
}
