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
        if ( \defined( 'WP_SUDO_ADMIN' ) && WP_SUDO_ADMIN ) {
            $wp_sudo_admin = WP_SUDO_ADMIN;
        } else {
            $wp_sudo_admin = null;
        }

        if ( \defined( 'SUDO_ADMIN_GROUP' ) && SUDO_ADMIN_GROUP ) {
            $admin_group = SUDO_ADMIN_GROUP;
        } else {
            $admin_group = null;
        }

        new WhiteLabel();

        // Custom Sucuri settings.
        new Sucuri( $wp_sudo_admin, $admin_group );

        // basic auth
        BasicAuth::init();

        add_action(
            'send_headers',
            function(): void {
                if ( ! \defined( 'SET_SECURITY_HEADERS' ) ) {
                    return;
                }

                $this->security_headers();
            }
        );

        // Disable User Notification of Password Change Confirmation
        apply_filters(
            'send_email_change_email',
            function ( $user, $userdata ) {
				return env( 'SEND_EMAIL_CHANGE_EMAIL' ) ? env( 'SEND_EMAIL_CHANGE_EMAIL' ) : true;
			}
        );

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

        // custom theme directory.
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

    public static function init(): self
    {
        return new self();
    }

    protected function security_headers(): void
    {
        $home_domain = $this->extract_domain( WP_HOME );

        header( 'Access-Control-Allow-Origin: www.google-analytics.com' );
        header( 'Strict-Transport-Security: max-age=31536000' );
        header( 'Content-Security-Policy: script-src \'self\' *.' . $home_domain . ' www.google-analytics.com *.google-analytics.com *.googlesyndication.com *.google.com *.google.com *.quantcount.com *.facebook.net *.gubagoo.io .hotjar.com *.inspectlet.com *.pingdom.net *.twitter.com *.quantserve.com *.googletagservices.com *.googleapis.com *.gubagoo.io \'unsafe-inline\';' );
        header( 'X-Frame-Options: SAMEORIGIN' );
        header( 'X-Content-Type-Options: nosniff' );
        header( 'Content-Security-Policy: frame-ancestors \'self\' https://' . $home_domain );
        header( 'X-XSS-Protection: 1; mode=block;' );
        header( 'Referrer-Policy: same-origin' );
    }

    /**
     * Extracts the domain from a URL.
     *
     * @param string $url The URL to extract the domain from.
     *
     * @return null|string The extracted domain or null if extraction fails.
     */
    protected function extract_domain( string $url ): ?string
    {
        $parsed_url = wp_parse_url( $url );

        if ( isset( $parsed_url['host'] ) ) {
            $host_parts = explode( '.', $parsed_url['host'] );
            $num_parts  = \count( $host_parts );

            // Check if the host has at least two parts (e.g., 'example.com').
            if ( $num_parts >= 2 ) {
                return $host_parts[ $num_parts - 2 ] . '.' . $host_parts[ $num_parts - 1 ];
            }
        }

        return null;
    }
}
