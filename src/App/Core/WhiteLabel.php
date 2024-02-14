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
 * white label class.
 */
class WhiteLabel
{
    protected $apt9_url;
    protected $home_url;
    protected $date_year;
    protected $site_name;
    protected $powered_by;
    protected $tenant_id;

    public function __construct()
    {
        $this->apt9_url   = 'https://github.com/devuri/wpenv-framework';
        $this->home_url   = home_url();
        $this->date_year  = gmdate( 'Y' );
        $this->site_name  = get_bloginfo( 'name' );
        $this->tenant_id  = env_tenant_id();
        $this->powered_by = apply_filters( 'wpenv_powered_by', 'Powered by WPTenancy.' );

        // add_action( 'wp_before_admin_bar_render', [ $this, 'logout_link' ] );
        add_action( 'admin_bar_menu', [ $this, 'remove_admin_wp_logo' ], 99 );
        add_filter( 'admin_footer_text', [ $this, 'change_footer_text' ] );
        add_action( 'wp_dashboard_setup', [ $this, 'remove_dashboard_widgets' ], 99 );
    }

    /**
     * Remove the Widgets ( do a check if the user can manage_options )
     * we should add an option for this to allow admins to choose who can view the lowest level etc.
     */
    public function remove_dashboard_widgets(): void
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
    public function logout_link(): void
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
    public function remove_admin_wp_logo( object $wp_admin_bar ): void
    {
        $wp_admin_bar->remove_node( 'wp-logo' );
    }

    /**
     * change_footer_text.
     *
     * @return string
     */
    public function change_footer_text(): string
    {
        return wp_kses_post( "&copy; $this->date_year <a href=\"$this->home_url\" target=\"_blank\">$this->site_name</a> All Rights Reserved. $this->powered_by $this->tenant_id" );
    }
}
