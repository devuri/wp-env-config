<?php

// Safely load /vendor/autoload.php or exit.
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
 	require_once  dirname( __FILE__ ) . '/vendor/autoload.php';
} else {
 	exit("Cant find the autoload file (/vendor/autoload.php)");
}

use DevUri\Config\Setup;

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// To override setup constant defined in config() method define them before.
Setup::init(__DIR__)->config('development');
// after setup we can define other constant in the normal way or using env function
// or simply use "Setup::get( 'UPLOAD_DIR' )"
// // Custom Uploads Directory.
// define('UPLOADS', Setup::get( 'UPLOAD_DIR' ) );

// or
// define('UPLOADS', env( 'UPLOAD_DIR' ) );
// remember to include with use "use function Env\env;" when using the env function.

// Both "Setup::get( 'UPLOAD_DIR' )" and "env( 'UPLOAD_DIR' )"
// will grab the value from .env file.

// Can also do.
// Setup::init(__DIR__)->config();
// this will setup in development mode.

// use false to disable and bypass the default setup process and roll your own.
// Setup::init(__DIR__)->config( 'development', false )->environment()->database()->salts()->apply();

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
*/
$table_prefix = "wp_";
// we can move this to .env as well like so.
// $table_prefix = env('DB_PREFIX');

/* That's all, stop editing! Happy publishing. */

 /** Absolute path to the WordPress directory. */
 if ( ! defined( 'ABSPATH' ) ) {
 	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
 }

 /** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
