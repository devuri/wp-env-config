<?php
/**
 * This file is part of the Slim White Label WordPress Plugin.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace Urisoft\App\Core;

/**
 * Change The Color Scheme of wp-admin based on env type like staging production secure etc.
 */
class EnvColor
{
    protected $env_type  = null;
    protected $env_color = [];

    /**
     * Constructor initialize.
     */
    public function __construct( string $env_type )
    {
        $this->env_type  = $env_type;
        $this->env_color = [
            'secure'      => null,
            'sec'         => null,
            'production'  => null,
            'prod'        => null,
            'development' => 'coffee',
            'debug'       => 'coffee',
            'dev'         => 'coffee',
            'staging'     => 'ectoplasm',
        ];

        // add_filter('get_user_option_admin_color', [$this, 'set_env_admin_color'] );
    }
}
