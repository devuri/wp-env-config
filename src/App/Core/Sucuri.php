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
    protected $wp_sudo_admin;

    protected $admin_group;
    /**
     * Initializes the Sucuri class.
     */
    public function __construct( ?int $wp_sudo_admin = null, ?array $admin_group = null )
    {
        $this->wp_sudo_admin = $wp_sudo_admin;
        $this->admin_group   = $admin_group;
        add_action( 'plugins_loaded', [ $this, 'init_action' ] );
    }

    public function init_action(): void
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
        add_action(
            'admin_enqueue_scripts',
            function ( $hook ): void {
                if ( 'DEBUG' === strtoupper( HTTP_ENV_CONFIG ) || 'DEB' === strtoupper( HTTP_ENV_CONFIG ) ) {
                    return;
                }

                $style = '.sucuriscan-hstatus-0 {display:none !important;}';
                wp_add_inline_style( 'sucuriscan', $style );
            },
            99
        );
    }

    /**
     * Removes Sucuri admin UI if user is not sudo admin.
     */
    public function remove_sucuri_admin_ui(): void
    {
        if ( \defined( 'WP_SUDO_ADMIN' ) && is_user_logged_in() ) {
            $current_user = wp_get_current_user();

            $sucuri_menu_prefix = is_multisite() ? 'network_' : '';

            if ( $this->wp_sudo_admin === $current_user->ID ) {
                return;
            }

            if ( $this->is_sudo_admin_group( $current_user->ID ) ) {
                return;
            }

            remove_action( 'admin_menu', $sucuri_menu_prefix . 'sucuriscanAddMenuPage' );
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

    /**
     * Check if a user belongs to the sudo admin group.
     *
     * This method determines whether a given user belongs to the sudo admin group. The sudo admin group
     * is defined as an array of user IDs with elevated administrative privileges.
     *
     * @param int $user_id The user ID to check.
     *
     * @return null|bool Returns `true` if the user belongs to the sudo admin group, `false` if not,
     *                   or `null` if the sudo admin group is not defined or not an array.
     */
    protected function is_sudo_admin_group( int $user_id ): ?bool
    {
        if ( ! $this->admin_group || ! \is_array( $this->admin_group ) ) {
            return null;
        }

        if ( \in_array( $user_id, $this->admin_group, true ) ) {
            return true;
        }

        return false;
    }
}
