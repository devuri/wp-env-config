<?php
/**
 * This file is part of the WordPress project install.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace Urisoft\App\Core;

/**
 * Restricted Admin.
 *
 * Restricts certain capabilities for the administrator role, except for a special admin user.
 *
 * !! DEPRECATED !!
 */
class RestrictedAdmin
{
    protected $wp_sudo_admin;

    protected $admin_group;

    /**
     * Initializes the class.
     */
    public function __construct( ?int $wp_sudo_admin = null, ?array $admin_group = null )
    {
        $this->wp_sudo_admin = $wp_sudo_admin;
        $this->admin_group   = $admin_group;

        add_action( 'init', [ $this, 'enable_restricted_admin' ] );
    }

    public function enable_restricted_admin(): void
    {
        // Check if WordPress is installed and that this is not returning null
        $admin = get_role( 'administrator' );

        // Check if ENABLE_RESTRICTED_ADMIN constant is null or false
        if ( ! \defined( 'ENABLE_RESTRICTED_ADMIN' ) || ! ENABLE_RESTRICTED_ADMIN ) {
            // Add the capabilities back to the administrator role
            $capabilities = [
                'edit_users',
                'delete_users',
                'create_users',
                'unfiltered_upload',
                'install_plugins',
                'delete_plugins',
                'install_themes',
                'remove_users',
                'delete_themes',
            ];

            foreach ( $capabilities as $capability ) {
                $admin->add_cap( $capability );
            }

            return;
        }

        if ( \defined( 'SUDO_ADMIN' ) && is_user_logged_in() && get_current_user_id() === $this->wp_sudo_admin ) {
            return;
        }

        $capabilities = [
            'edit_users',
            'delete_users',
            'create_users',
            'unfiltered_upload',
            'install_plugins',
            'delete_plugins',
            'install_themes',
            'remove_users',
            'delete_themes',
        ];

        foreach ( $capabilities as $capability ) {
            $admin->remove_cap( $capability );
        }
    }
}
