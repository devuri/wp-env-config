<?php

\defined( 'ABSPATH' ) || die( '.' );

if ( ! \defined( 'ABSPATH' ) ) {
    exit;
}

function wpenv_verify_signature( $service, $signature )
{
    $secretKey = env( 'AUTO_LOGIN_SECRET_KEY' );

    $http_query = http_build_query( $service, '', '&' );

    $generatedSignature = hash_hmac( 'sha256', $http_query, $secretKey );

    return hash_equals( $generatedSignature, $signature );
}

function wpenv_get_req_input( string $req_input )
{
    if ( isset( $_GET[ $req_input ] ) ) {
        return sanitize_text_field( wp_unslash( $_GET[ $req_input ] ) );
    }

    return null;
}

function auto_login_mu_plugin_check_auto_login(): void
{
    $home_url = home_url( '/' );

    if ( \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'sec', 'secure', 'prod', 'production' ], true ) ) {
        wp_safe_redirect( $home_url );
        exit;
    }

    if ( isset( $_GET['auto_login'] ) ) {
        $service = [
            'timestamp' => wpenv_get_req_input( 'timestamp' ),
            'username'  => wpenv_get_req_input( 'username' ),
            'site_id'   => wpenv_get_req_input( 'site_id' ),
        ];

        // Get the current timestamp.
        $currentTimestamp = time();

        // Check if the URL has expired (more than 60 seconds old).
        if ( $currentTimestamp - $service['timestamp'] > 60 ) {
            wp_die( 'login expired' );

            return;
        }

        $signature = wpenv_get_req_input( 'signature' );

        if ( \is_null( $service['username'] ) || \is_null( $signature ) ) {
            return;
        }

        if ( ! wpenv_verify_signature( $service, $signature ) ) {
            wp_safe_redirect( $home_url );

            return;
        }

        $user = get_user_by( 'login', $service['username'] );

        if ( $user ) {
            wp_clear_auth_cookie();
            wp_set_current_user( $user->ID );
            wp_set_auth_cookie( $user->ID, false, is_ssl() );

            do_action( 'wp_login', $user->user_login, $user );
            $redirect_to = user_admin_url();
            wp_safe_redirect( $redirect_to );
            exit;
        }

        wp_safe_redirect( $home_url );
        exit;
    }// end if
}
add_action( 'init', 'auto_login_mu_plugin_check_auto_login' );
