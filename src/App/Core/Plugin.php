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
    protected $env_menu_id;

    public function __construct()
    {
        if ( \defined( 'WP_SUDO_ADMIN' ) && WP_SUDO_ADMIN ) {
            $wp_sudo_admin = (int) WP_SUDO_ADMIN;
        } else {
            $wp_sudo_admin = null;
        }

        if ( \defined( 'SUDO_ADMIN_GROUP' ) && SUDO_ADMIN_GROUP ) {
            $admin_group = SUDO_ADMIN_GROUP;
        } else {
            $admin_group = null;
        }

        // admin bar menu ID.
        $this->env_menu_id = 'wp-app-environment';

        new WhiteLabel();

        // Custom Sucuri settings.
        new Sucuri( $wp_sudo_admin, $admin_group );

        // basic auth
        BasicAuth::init();

        // allows auto login.
        if ( env( 'WPENV_AUTO_LOGIN_SECRET_KEY' ) ) {
            AutoLogin::init( env( 'WPENV_AUTO_LOGIN_SECRET_KEY' ), env( 'WP_ENVIRONMENT_TYPE' ) );
        }

        add_action(
            'send_headers',
            function(): void {
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
        add_action( 'admin_bar_menu', [ $this, 'app_env_admin_bar_menu' ], 1199 );

        // custom theme directory.
        if ( \defined( 'APP_THEME_DIR' ) ) {
            register_theme_directory( APP_THEME_DIR );
        }

        // Disable login screen language switcher.
        add_filter(
            'login_display_language_dropdown',
            function() {
                return false;
            }
        );

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

    public function app_env_admin_bar_menu( $admin_bar ): void
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $env_menu_id = 'wp-app-environment';

        if ( \defined( 'HTTP_ENV_CONFIG' ) && HTTP_ENV_CONFIG ) {
            $env_label = strtoupper( HTTP_ENV_CONFIG );
        } else {
            $env_label = null;
        }

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

    protected function security_headers(): void
    {
        if ( ! \defined( 'SET_SECURITY_HEADERS' ) ) {
            return;
        }

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
