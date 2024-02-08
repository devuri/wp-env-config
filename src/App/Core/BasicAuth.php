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

use Urisoft\App\Core\Settings\Register;

/**
 * Class BasicAuth.
 *
 * Handles the WordPress hooks and callbacks for basic authentication.
 *
 * @see https://www.php.net/manual/en/features.http-auth.php
 */
class BasicAuth
{
    protected const AUTH_OPTION = 'wpc_basic_auth';

    protected $username;
    protected $user_password;
    protected $auth_option;
    protected $web_app;

    /**
     * Initializes the Sucuri class.
     */
    public function __construct()
    {
        $this->username      = env( 'BASIC_AUTH_USER', false );
        $this->user_password = env( 'BASIC_AUTH_PASSWORD', false );
        $this->auth_option   = (bool) get_option( self::AUTH_OPTION );
        $this->web_app       = get_option( 'blogname' );
    }

    /**
     * Initializes the BasicAuth class.
     */
    public static function init(): void
    {
        $app_auth = new self();
        $app_auth->register_hooks();
    }

    /**
     * Registers the hooks for the BasicAuth class.
     */
    public function register_hooks(): void
    {
        if ( $this->is_production() ) {
            delete_option( self::AUTH_OPTION );

            return;
        }

        new Register( self::AUTH_OPTION );
        add_action( 'init', [ $this, 'require_auth' ] );
    }

    /**
     * Requires Basic auth.
     */
    public function require_auth(): void
    {
        if ( ! $this->auth_option || true === $this->is_background_work() ) {
            return;
        }

        $request_uri = $this->get_request_uri();

        /**
         * Filters which pages are allowed through basic auth.
         *
         * @param array $allowed Allowed pages.
         */
        $allowed = apply_filters( 'wpc_basic_auth_allowed_pages', [ '/robots.txt' ] );

        // check uri
        if ( $request_uri && \in_array( $request_uri, $allowed, true ) ) {
            return;
        }

        if ( $this->username && $this->user_password ) {
            header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );

            $authenticated = false;

            if ( empty( $_SERVER['PHP_AUTH_USER'] ) && empty( $_SERVER['PHP_AUTH_PW'] ) ) {
                $authenticated = false;
            } elseif ( $_SERVER['PHP_AUTH_USER'] === $this->username && $_SERVER['PHP_AUTH_PW'] === $this->user_password ) {
                $authenticated = true;
            }

            if ( ! $authenticated ) {
                header( 'HTTP/1.1 401 Authorization Required' );
                header( "WWW-Authenticate: Basic realm=\"$this->web_app development site login\"" );
                wp_terminate( "<h2>Wrong Credentials: $this->web_app Requires login </h2>" );
            }
        }
    }

    /**
     * Checks if the application is running in a background work environment.
     *
     * @return bool True if running in a background work environment, false otherwise.
     */
    protected function is_background_work(): bool
    {
        if ( \defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
            return true;
        }

        if ( \defined( 'DOING_CRON' ) && DOING_CRON ) {
            return true;
        }

        if ( \defined( 'WP_CLI' ) && WP_CLI ) {
            return true;
        }

        if ( \defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return true;
        }

        if ( current_user_can( 'manage_options' ) ) {
            return true;
        }

        return false;
    }

    /**
     * Retrieves the sanitized REQUEST_URI.
     *
     * @return null|string Sanitized REQUEST_URI or null if not set.
     */
    protected function get_request_uri(): ?string
    {
        if ( isset( $_SERVER['REQUEST_URI'] ) ) {
            return sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
        }

        return null;
    }

    /**
     * Checks if the environment is in production.
     *
     * @return bool True if the environment is in production, false otherwise.
     */
    private function is_production(): bool
    {
        if ( \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'sec', 'secure', 'prod', 'production' ], true ) ) {
            return true;
        }

        return false;
    }
}
