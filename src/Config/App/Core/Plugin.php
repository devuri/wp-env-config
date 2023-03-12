<?php
/**
 * This file is part of the WordPress project install.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace App\Core;

class Plugin
{
    public function __construct()
    {
        // add_action( 'wp_before_admin_bar_render', [ WhiteLabel::class, 'logout_link' ] );
        add_action( 'admin_bar_menu', [ WhiteLabel::class, 'remove_admin_wp_logo' ], 99 );
        add_filter( 'admin_footer_text', [ WhiteLabel::class, 'change_footer_text' ] );
        add_action( 'wp_dashboard_setup', [ WhiteLabel::class, 'remove_dashboard_widgets' ], 99 );

        // Remove wp version.
        add_filter('the_generator', function() {
            return null;
        });

        // Add the env type to admin bar.
        add_action('admin_bar_menu', function ( $admin_bar ): void {
            if ( ! current_user_can('manage_options') ) {
                return;
            }

            $env_label = strtoupper( HTTP_ENV_CONFIG );

            $admin_bar->add_menu( [
                'id'    => 'wp-app-environment',
                'title' => wp_kses_post(":: Env $env_label ::"),
                'href'  => '#',
                'meta'  => [
                    'title' => __("Environment: $env_label"),
                    'class' => 'qm-warning',
                ],
            ]);
        }, 1199);
    }

    public static function init()
    {
        return new self();
    }
}
