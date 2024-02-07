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

use Urisoft\App\Core\Settings\AdminSettingsPage;
use Urisoft\App\Core\Traits\ActivateElementorTrait;
use Urisoft\App\Core\Traits\AdminBarMenuTrait;

class Plugin
{
    use ActivateElementorTrait;
    use AdminBarMenuTrait;
    public const ADMIN_BAR_MENU_ID = 'wp-app-environment';

    protected $env_menu_id;
    protected $http_env_type;
    protected $wp_sudo_admin;
    protected $admin_group;

    public function __construct()
    {
        // define basic app settings
        $this->define_basic_app_init();

        new WhiteLabel();

        // Custom Sucuri settings.
        new Sucuri( $this->wp_sudo_admin, $this->admin_group );

        // basic auth
        BasicAuth::init();

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

		// separate uploads for multi tenant.
		if ( env( 'IS_MULTI_TENANT_APP' ) ) {
			add_filter('upload_dir', [$this, 'set_upload_directory']);
		}

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

        $this->add_core_app_events();

        // Add some special admin pages.
        new AdminSettingsPage(
            'Composer plugins',
            function (): void {
                ?><div class="wrap">
					<h2>Composer Plugins List</h2>
					<?php
                    dump( app_packagist_plugins_list() );
					?>
				</div>
				<?php
            }
        );
    }

    public static function init(): self
    {
        return new self();
    }


	public function set_upload_directory($dir)
	{
		$custom_dir = "/" .env( 'APP_TENANT_ID' ) . '/uploads';
		$dir['basedir'] = WP_CONTENT_DIR . $custom_dir;
		$dir['baseurl'] = content_url() . $custom_dir;
		$dir['path'] = $dir['basedir'] . $dir['subdir'];
		$dir['url'] = $dir['baseurl'] . $dir['subdir'];
		return $dir;
	}

    protected function add_core_app_events(): void
    {
        $app_events = new ScheduledEvent(
            'core_app_events',
            function(): void {
                $this->auto_activate_elementor();

                do_action( 'env_app_events' );

                // error_log('Custom App event executed at ' . current_time('mysql'));
            },
            'hourly'
        );

        $app_events->add_app_event();
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

    protected function define_basic_app_init(): void
    {
        if ( \defined( 'WP_SUDO_ADMIN' ) && WP_SUDO_ADMIN ) {
            $this->wp_sudo_admin = (int) WP_SUDO_ADMIN;
        } else {
            $this->wp_sudo_admin = null;
        }

        if ( \defined( 'SUDO_ADMIN_GROUP' ) && SUDO_ADMIN_GROUP ) {
            $this->admin_group = SUDO_ADMIN_GROUP;
        } else {
            $this->admin_group = null;
        }

        if ( \defined( 'HTTP_ENV_CONFIG' ) && HTTP_ENV_CONFIG ) {
            $this->http_env_type = strtoupper( HTTP_ENV_CONFIG );
        } else {
            $this->http_env_type = null;
        }

        // admin bar menu ID.
        $this->env_menu_id = self::ADMIN_BAR_MENU_ID;

        // allows auto login.
        if ( env( 'WPENV_AUTO_LOGIN_SECRET_KEY' ) ) {
            AutoLogin::init( env( 'WPENV_AUTO_LOGIN_SECRET_KEY' ), env( 'WP_ENVIRONMENT_TYPE' ) );
        }

        if ( env( 'DISABLE_WP_APPLICATION_PASSWORDS' ) ) {
            add_filter( 'wp_is_application_passwords_available', '__return_false' );
        }
    }
}
