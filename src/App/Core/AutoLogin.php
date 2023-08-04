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

use WP_User;

/**
 * Class AutoLogin.
 *
 * The AutoLogin class handles automatic login functionality for WordPress sites.
 */
class AutoLogin
{
    /**
     * The secret key used for generating and verifying signatures.
     *
     * @var null|string
     */
    protected $secret_key = null;

    /**
     * Holds the login service parameters.
     *
     * @var null|array
     */
    protected $login_service = null;

    /**
     * The URL of the home page of the WordPress site.
     *
     * @var null|string
     */
    protected $home_url = null;

    /**
     * The URL of the user's admin area (dashboard).
     *
     * @var null|string
     */
    protected $user_admin_url = null;

    /**
     * AutoLogin constructor.
     *
     * Initializes the AutoLogin class with necessary properties and settings.
     *
     * The constructor sets up the class properties required for the auto-login functionality.
     * It initializes the secret key for added security, stores the home URL and user admin URL for redirection,
     * and initializes an empty array to hold the login service parameters for processing auto-login requests.
     *
     * @return void This method does not return any value.
     */
    public function __construct()
    {
        $this->secret_key     = env( 'AUTO_LOGIN_SECRET_KEY' );
        $this->home_url       = home_url( '/' );
        $this->user_admin_url = user_admin_url();
        $this->login_service  = [];
    }

    /**
     * Registers the auto-login action to handle automatic logins when the 'init' action is triggered.
     *
     * This method registers the 'hanlde_auto_login' method to be executed when the 'init' action is triggered.
     * The 'hanlde_auto_login' method handles automatic logins by processing specific query parameters and
     * authenticating users based on the provided information. The registration of this action is conditional
     * and depends on the presence of a secret key for added security.
     *
     * @return void This method does not return any value.
     */
    public function register_login_action(): void
    {
        if ( $this->secret_key ) {
            add_action( 'init', [ $this, 'hanlde_auto_login' ] );
        }
    }

    /**
     * Initializes the automatic login functionality.
     *
     * This method is the entry point for the automatic login feature. It creates an instance of the class
     * and registers the necessary action to trigger the auto-login process when the designated event occurs.
     *
     * @return void This method does not return any value.
     */
    public static function init(): void
    {
        $auto_login = new self();
        $auto_login->register_login_action();
    }

    /**
     * Handles the automatic login process based on the provided query parameters.
     *
     * This method processes the automatic login request initiated via the 'wpenv_auto_login' query parameter.
     * It verifies the request's validity, checks if the login timestamp is within the valid range (30 seconds),
     * and authenticates the user if the signature is valid. The method also checks the environment type to prevent
     * auto-login on production sites to ensure security.
     *
     * @return void This method does not return any value.
     */
    public function hanlde_auto_login(): void
    {
        // Get the current timestamp.
        $current_timestamp = time();

        // do not allow production login.
        if ( \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'sec', 'secure', 'prod', 'production' ], true ) ) {
            return;
        }

        // WARNING | Processing form data without nonce verification.
        if ( isset( $_GET['wpenv_auto_login'] ) ) {
            $this->login_service = [
                'timestamp' => static::get_req( 'timestamp' ),
                'username'  => static::get_req( 'username' ),
                'site_id'   => static::get_req( 'site_id' ),
            ];

            // Check if the URL has expired (more than 30 seconds old).
            if ( $current_timestamp - (int) $this->login_service['timestamp'] > 30 ) {
                wp_die( 'login expired' );

                return;
            }

            $signature = static::get_req( 'signature' );

            if ( \is_null( $this->login_service['username'] ) || \is_null( $signature ) ) {
                return;
            }

            if ( ! $this->verify_signature( $signature ) ) {
                wp_safe_redirect( $this->home_url );

                return;
            }

            $user = get_user_by( 'login', $this->login_service['username'] );

            if ( $user ) {
                static::authenticate( $user );
                wp_safe_redirect( $this->user_admin_url );
                exit;
            }

            wp_safe_redirect( $this->home_url );
            exit;
        }// end if
    }

    /**
     * Verifies the authenticity of the signature for the auto-login request.
     *
     * This method checks the provided signature against the expected signature to ensure the authenticity
     * of the auto-login request. It uses the login service parameters and a secret key to generate the expected
     * signature and then compares it with the provided signature using the hash_equals function to prevent timing attacks.
     *
     * @param string $signature The signature to be verified for the auto-login request.
     *
     * @return bool Returns true if the provided signature matches the expected signature, false otherwise.
     */
    protected function verify_signature( $signature )
    {
        $http_query = http_build_query( $this->login_service, '', '&' );

        $generateSignature = hash_hmac( 'sha256', $http_query, $this->secret_key );

        return hash_equals( $generateSignature, $signature );
    }

    /**
     * Authenticates the user and performs necessary actions after successful authentication.
     *
     * This method is responsible for authenticating the user after a successful login attempt. If the
     * provided user object is not empty, it clears the current authentication cookie, sets the user as the
     * current user, and sets a new authentication cookie. It also triggers the 'wp_login' action hook and
     * redirects the user to the admin area (user dashboard) upon successful authentication. If the user object
     * is empty, it redirects the user back to the home URL.
     *
     * @param WP_User $user The WP_User object representing the user to authenticate.
     *
     * @return void This method does not return any value.
     */
    protected static function authenticate( WP_User $user ): void
    {
        if ( ! $user ) {
            return;
        }

        wp_clear_auth_cookie();
        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID, false, is_ssl() );
        do_action( 'wp_login', $user->user_login, $user );
    }

    /**
     * Retrieves and sanitizes a specific query parameter from the $_GET superglobal array.
     *
     * This method is used to get and sanitize a specific query parameter from the $_GET array
     * to prevent potential security vulnerabilities. It uses the sanitize_text_field and wp_unslash
     * functions to sanitize the input and remove any harmful data.
     *
     * @param string $req_input The name of the query parameter to retrieve and sanitize.
     *
     * @return null|string The sanitized value of the specified query parameter, or null if the parameter is not set.
     */
    protected static function get_req( string $req_input )
    {
        if ( isset( $_GET[ $req_input ] ) ) {
            return sanitize_text_field( wp_unslash( $_GET[ $req_input ] ) );
        }

        return null;
    }
}
