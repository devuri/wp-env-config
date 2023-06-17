<?php

namespace DevUri\Config;

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

    public function kernel(): Kernel
    {
        return new Kernel( $this->app_path, $this->config );
    }
}
