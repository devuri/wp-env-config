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

// list of setup options

// Setup::init(__DIR__)->config(); // production
//
// Setup::init(__DIR__)->config('development'); // development
//
// Setup::init(__DIR__)->config('staging'); // staging
//
// Setup::init(__DIR__)->config('production'); // production
//
// Setup::init(__DIR__)->config('secure'); // secure
//
// Setup::init(__DIR__)->config('development', false )->environment()->database()->salts()->apply();

// dump( Setup::init(__DIR__)->getEnvironment() ); // Get the current Environment setup.

// Debug must be on and 'development' set as WP_ENVIRONMENT_TYPE in the .env file.
// dump( Setup::init(__DIR__)->configMap() ); // Display a list of constants defined by Setup.

// this will output the following:

// "WP_ENVIRONMENT_TYPE" => "development"
// "WP_DEBUG" => true
// "SAVEQUERIES" => true
// "WP_DEBUG_DISPLAY" => true
// "WP_DISABLE_FATAL_ERROR_HANDLER" => true
// "SCRIPT_DEBUG" => true
// "WP_DEBUG_LOG" => true
// "DB_NAME" => ""
// "DB_USER" => ""
// "DB_PASSWORD" => ""
// "DB_HOST" => "localhost"
// "DB_CHARSET" => "utf8mb4"
// "DB_COLLATE" => ""
// "WP_HOME" => ""
// "WP_SITEURL" => ""
// "UPLOADS" => "wp-content/uploads"
// "WP_MEMORY_LIMIT" => "256M"
// "WP_MAX_MEMORY_LIMIT" => "256M"
// "CONCATENATE_SCRIPTS" => true
// "FORCE_SSL_ADMIN" => true
// "FORCE_SSL_LOGIN" => true
// "AUTOSAVE_INTERVAL" => 180
// "WP_POST_REVISIONS" => 10
// "AUTH_KEY" => ""
// "SECURE_AUTH_KEY" => ""
// "LOGGED_IN_KEY" => ""
// "NONCE_KEY" => ""
// "AUTH_SALT" => ""
// "SECURE_AUTH_SALT" => ""
// "LOGGED_IN_SALT" => ""
// "NONCE_SALT" => ""
// "DEVELOPERADMIN" => null

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

// hardening and secure setup mode.
// secure setup mode:
// Setup::init(__DIR__)->config('secure');
// this helps to reduce the attack surface by
// disabling both file editor and installer for themes and plugins.


// use false to disable and bypass the default setup process and roll your own.
// Setup::init(__DIR__)->config( 'development', false )->environment()->database()->salts()->apply();

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
*/
$table_prefix = "wp_"; // we can move this to .env as well.
// $table_prefix = env('DB_PREFIX');

/* That's all, stop editing! Happy publishing. */

 /** Absolute path to the WordPress directory. */
 if ( ! defined( 'ABSPATH' ) ) {
 	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
 }

 /** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
