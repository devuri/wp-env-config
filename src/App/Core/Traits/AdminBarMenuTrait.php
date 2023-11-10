<?php
/**
 * This file is part of the WordPress project install.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace Urisoft\App\Core\Traits;

use Urisoft\App\Core\Updates;

trait AdminBarMenuTrait
{
    public function app_env_admin_bar_menu( $admin_bar ): void
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $env_menu_id = 'wp-app-environment';
        $env_label   = $this->http_env_type;

        /**
         * When in secure env updates are not visible.
         *
         * in that case this will give us an indication of available updates
         *
         * @var Updates
         */
        $wp_updates = new Updates();

        if ( $wp_updates->get_available_updates() ) {
            $wp_update_count = $wp_updates->get_available_updates();
        } else {
            $wp_update_count = 0;
        }

        $admin_bar->add_menu(
            [
                'id'    => $this->env_menu_id,
                'title' => wp_kses_post( ":: Env $env_label :: [$wp_update_count]" ),
                'href'  => '#',
                'meta'  => [
                    'title' => __( 'Environment: ' ) . $env_label,
                    'class' => 'wpc-warning',
                ],
            ]
        );

        $admin_bar->add_menu(
            [
                'parent' => $this->env_menu_id,
                'id'     => 'wp-app-updates',
                'title'  => "$wp_update_count" . __( ' Available Updates' ),
                'href'   => '#',
                'meta'   => [
                    'title' => __( 'Updates Available' ),
                    'class' => 'wpc-warning',
                ],
            ]
        );

        // Integrated Version Control
        $admin_bar->add_menu(
            [
                'parent' => $this->env_menu_id,
                'id'     => 'wp-app-ivc',
                'title'  => __( 'Integrated Version Control (vcs build)' ),
                'href'   => '#',
                'meta'   => [
                    'title' => __( 'Built with Integrated Version Control and Deployment Pipeline (wpenv.io)' ),
                    'class' => 'wpc-warning',
                ],
            ]
        );
    }
}
