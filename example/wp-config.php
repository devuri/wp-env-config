<?php

use Urisoft\App\Setup;

require_once __FILE__ . '/vendor/autoload.php';

/**
 * The base configuration for WordPress
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
Setup::init(__DIR__)->config(); // production

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = env('DB_PREFIX');


if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

// Sets up WordPress.
require_once ABSPATH . 'wp-settings.php';
