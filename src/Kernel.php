<?php

namespace DevUri\Config;

use DevUri\Config\App\HttpKernel;

class Kernel extends HttpKernel
{
    public function __construct( string $app_path, $args = [] )
    {
        parent::__construct( $app_path, $args );
    }
}
