<?php
/**
 * This file is part of the Slim White Label WordPress PLugin.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace App\Core;

/**
 * white label class.
 */
class WhiteLabel
{
    /**
     * Remove the Widgets ( do a check if the user can manage_options )
     * we should add an option for this to allow admins to choose who can view lowest level etc.
     */
    public static function remove_dashboard_widgets(): void
    {
        global $wp_meta_boxes;

        if ( ! current_user_can( 'manage_options' ) ) {
            $wp_meta_boxes['dashboard']['normal']['core'] = [];
            $wp_meta_boxes['dashboard']['side']['core']   = [];
        }
    }

    /**
     * logout_link.
     *
     * @return void
     */
    public static function logout_link(): void
    {
        global $wp_admin_bar;
        $wp_admin_bar->add_menu(
            [
                'id'     => 'wp-wll-logout',
                'title'  => 'Log Out',
                'parent' => 'top-secondary',
                'href'   => wp_logout_url(),
            ]
        );
        $wp_admin_bar->remove_menu( 'my-account' );
        $wp_admin_bar->remove_menu( 'wp-admin' );
    }

    /**
     * remove_wp_logo.
     *
     * @param object $wp_admin_bar
     *
     * @return void
     */
    public static function remove_admin_wp_logo( $wp_admin_bar ): void
    {
        $wp_admin_bar->remove_node( 'wp-logo' );
    }

    /**
     * change_footer_text.
     *
     * @return void
     */
    public static function change_footer_text(): void
    {
        echo '&copy; ' . esc_html( gmdate( 'Y' ) ) . ' <a href="' . esc_url( home_url() ) . '" target="_blank">' . esc_html( get_bloginfo( 'name' ) ) . '</a> All Rights Reserved.';
    }
}
