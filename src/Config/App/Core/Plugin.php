<?php
/**
 * This file is part of the WordPress project install.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace DevUri\Config\App\Core;

class Plugin
{
    public function __construct()
    {
        self::add_white_label();

        // Remove wp version.
        add_filter(
            'the_generator',
            function() {
                return null;
            }
        );

        // Add the env type to admin bar.
        add_action(
            'admin_bar_menu',
            function ( $admin_bar ): void {
                if ( ! current_user_can( 'manage_options' ) ) {
                    return;
                }

                // @phpstan-ignore-next-line
                $env_label = strtoupper( HTTP_ENV_CONFIG );

                $admin_bar->add_menu(
                    [
                        'id'    => 'wp-app-environment',
                        'title' => wp_kses_post( ":: Env $env_label ::" ),
                        'href'  => '#',
                        'meta'  => [
                            'title' => __( "Environment: $env_label" ),
                            'class' => 'qm-warning',
                        ],
                    ]
                );
            },
            1199
        );
    }

    public static function add_white_label(): WhiteLabel
    {
        return new WhiteLabel();
    }

    public static function init(): self
    {
        return new self();
    }
}
