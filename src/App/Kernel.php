<?php

namespace Urisoft\App;

use Exception;
use Urisoft\App\Http\BaseKernel;

class Kernel extends BaseKernel
{
    /**
     * Setup Kernel.
     *
     * @param string   $app_path
     * @param string[] $args
     *
     * @throws Exception
     */
    public function __construct( string $app_path, array $args = [] )
    {
        parent::__construct( $app_path, $args );
    }
}
