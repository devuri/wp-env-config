<?php

defined( 'ABSPATH' ) or die('.');

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function wp_env_verify_signature($service, $signature)
{
	$secretKey = env('AUTO_LOGIN_SECRET_KEY');

	$http_query = http_build_query($service, '', '&');

	$generatedSignature = hash_hmac('sha256', $http_query, $secretKey);;

	return hash_equals($generatedSignature, $signature);
}

function auto_login_mu_plugin_check_auto_login() {

	$home_url = home_url('/');
	$admin_url = admin_url();

	if ( \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'sec', 'secure', 'prod', 'production' ], true ) ) {
		wp_safe_redirect($home_url);
        exit;
	}

    if (isset($_GET['auto_login'])) {

		$service = [
			'timestamp' => sanitize_text_field($_GET['timestamp'] ?? ''),
			'username' => sanitize_text_field($_GET['username'] ?? ''),
			'site_id' => sanitize_text_field($_GET['site_id'] ?? ''),
		];

		// Get the current timestamp.
	   $currentTimestamp = time();

	   // Check if the URL has expired (more than 60 seconds old).
	   if ($currentTimestamp - $service['timestamp'] > 60) {
		   wp_die('login expired');
		   return;
	   }

		$signature = sanitize_text_field($_GET['signature'] ?? '');

        if (empty($service['username']) || empty($signature)) {
            return;
        }

		if (! wp_env_verify_signature($service, $signature)) {
			wp_safe_redirect($home_url);
            return;
        }

		$user = get_user_by('login', $service['username']);

        if ($user) {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            do_action('wp_login', $user->user_login, $user);
        }

        wp_safe_redirect($admin_url);
        exit;
    }
}
add_action('init', 'auto_login_mu_plugin_check_auto_login');
