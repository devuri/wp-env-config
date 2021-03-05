<?php
/**
 * The base configuration for WordPress
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
require_once  dirname( __FILE__ ) . '/vendor/autoload.php';

use DevUri\Config\Setup;

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
*/
$table_prefix = "wp_";

// setup config.
Setup::init(__DIR__, 'production' );

/* That's all, stop editing! Happy publishing. */

 /** Absolute path to the WordPress directory. */
 if ( ! defined( 'ABSPATH' ) ) {
 	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
 }

 /** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
