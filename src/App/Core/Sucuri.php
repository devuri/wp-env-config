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
 *
 * @see https://github.com/szepeviktor/custom-sucuri/blob/master/custom-sucuri.php
 */
class Sucuri
{
    /**
     * Initializes the Sucuri class.
     */
    public function __construct()
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

        add_action( 'admin_menu', [ $this, 'remove_firewall_ui' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'hide_waf_postbox' ], 20 );
    }

    /**
     * Removes Sucuri admin UI if user is not sudo admin.
     */
    public function remove_sucuri_admin_ui(): void
    {
        if ( \defined( 'WP_SUDO_ADMIN' ) && is_user_logged_in() ) {
            $current_user = wp_get_current_user();

            if ( WP_SUDO_ADMIN !== $current_user->ID ) {
                remove_action( 'admin_menu', 'SucuriScanInterface::add_interface_menu' );
            }
        }
    }

    /**
     * Removes WAF admin menu and the corresponding tab.
     */
    public function remove_firewall_ui(): void
    {
        global $sucuriscan_pages;
        // Would remove only the admin menu: remove_submenu_page('sucuriscan', 'sucuriscan_firewall');
        unset( $sucuriscan_pages['sucuriscan_firewall'] );
    }

    /**
     * Hide "Website Firewall protection" postbox on Hardening tab.
     *
     * @param string $hook The current admin page hook.
     */
    public function hide_waf_postbox( $hook ): void
    {
        if ( 'sucuri-security_page_sucuriscan_hardening' !== $hook ) {
            return;
        }

        $style  = '.sucuri-security_page_sucuriscan_hardening #poststuff .postbox:nth-of-type(2) {display:none !important;}';
        $style .= '.sucuri-security_page_sucuriscan_hardening #sucuriscan-hardening .postbox:nth-of-type(1) {display:none !important;}';
        wp_add_inline_style( 'wp-admin', $style );
    }
}
