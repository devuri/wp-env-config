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

                $env_label = strtoupper( HTTP_ENV_CONFIG );

                $admin_bar->add_menu(
                    [
                        'id'    => 'wp-app-environment',
                        'title' => wp_kses_post( ":: Env $env_label ::" ),
                        'href'  => '#',
                        'meta'  => [
                            'title' => __( 'Environment: ' ) . $env_label,
                            'class' => 'qm-warning',
                        ],
                    ]
                );
            },
            1199
        );

        // custom theme directory located outside wp-content.
        if ( \defined( 'APP_THEME_DIR' ) ) {
            register_theme_directory( APP_THEME_DIR );
        }

        /*
         * Prevent Admin users from deactivating plugins.
         *
         * While this will remove the deactivation link it does NOT prevent deactivation
         * It will only hide the link to deactivate.
         */
        add_filter(
            'plugin_action_links',
            function ( $actions, $plugin_file, $plugin_data, $context ) {
                if ( ! \defined( 'CAN_DEACTIVATE_PLUGINS' ) ) {
                    return $actions;
                }

                // if set to true users should be allowed to deactivate plugins.
                if ( true === CAN_DEACTIVATE_PLUGINS ) {
                    return $actions;
                }

                if ( \array_key_exists( 'deactivate', $actions ) ) {
                    unset( $actions['deactivate'] );
                }

                return $actions;
            },
            10,
            4
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
