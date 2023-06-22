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

		add_action('send_headers', function() {
			if ( ! defined('SET_SECURITY_HEADERS') ) {
				return;
			}

			$this->security_headers();
		});

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

	protected function security_headers()
	{
	    header('Access-Control-Allow-Origin: www.google-analytics.com');
	    header('Strict-Transport-Security: max-age=31536000');
	    header('Content-Security-Policy: script-src \'self\' *.example.com www.google-analytics.com *.google-analytics.com *.googlesyndication.com *.google.com *.google.com *.quantcount.com *.facebook.net *.gubagoo.io .hotjar.com *.gstatic.com *.inspectlet.com *.pingdom.net *.twitter.com *.quantserve.com *.googletagservices.com *.googleapis.com *.gubagoo.io \'unsafe-inline\';');
	    header('X-Frame-Options: SAMEORIGIN');
	    header('X-Content-Type-Options: nosniff');
	    header('Content-Security-Policy: frame-ancestors \'self\' https://example.com');
	    header('X-XSS-Protection: 1; mode=block;');
	    header('Referrer-Policy: same-origin');
	}
}
