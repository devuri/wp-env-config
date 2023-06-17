<?php

namespace DevUri\Config;

use DevUri\Config\Kernel;

class App
{
	protected $app_path = null;
	protected $config = null;

    /**
     * Setup App.
     *
     * @param string   $app_path
     */
    public function __construct( string $app_path )
    {
		$this->app_path = $app_path;
		$this->config   = require_once $this->app_path . '/app.php';
    }

	protected function kernel(): Kernel
	{
		return new Kernel( $this->app_path, $this->config );
	}
}
