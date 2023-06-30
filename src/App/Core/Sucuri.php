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
 * Custom Sucuri settings.
 */
class Sucuri
{
    /**
     * Initializes the Sucuri class.
     */
    public function __construct()
    {
		add_action( 'plugins_loaded', [$this, 'init_action' ] );
    }

	public function  init_action(): void
	{
		if ( current_user_can( 'manage_options' ) ) {
            add_action( 'init', [ $this, 'remove_sucuri_admin_ui' ] );
            add_action( 'init', [ $this, 'disable_firewall_ui' ] );
        }
	}

    /**
     * Disable Sucuri WAF admin stuff.
     */
    public function disable_firewall_ui(): void
    {
        if ( \defined( 'ENABLE_SUCURI_WAF' ) && ENABLE_SUCURI_WAF ) {
            return;
        }

        add_action( 'admin_menu', [ $this, 'remove_firewall_submenu' ] );
		add_action( 'admin_enqueue_scripts', function ( $hook ): void
		    {
				if ( 'DEBUG' === strtoupper( HTTP_ENV_CONFIG ) || 'DEB' === strtoupper( HTTP_ENV_CONFIG ) ) {
					return;
				}

		        $style = '.sucuriscan-hstatus-0 {display:none !important;}';
		        wp_add_inline_style( 'sucuriscan', $style );
		}, 99 );
    }

	/**
     * Removes Sucuri admin UI if user is not sudo admin.
     */
    public function remove_sucuri_admin_ui(): void
    {
        if ( \defined( 'WP_SUDO_ADMIN' ) && is_user_logged_in() ) {
            $current_user = wp_get_current_user();

			$sucuri_menu_prefix = is_multisite() ? 'network_' : '';

            if ( WP_SUDO_ADMIN !== $current_user->ID ) {
				remove_action( 'admin_menu', $sucuri_menu_prefix . 'sucuriscanAddMenuPage' );
            }
        }
    }

    /**
     * Removes WAF admin menu and the corresponding tab.
     */
    public function remove_firewall_submenu(): void
    {
		global $submenu;

		unset( $submenu['sucuriscan'][1] );
    }
}
