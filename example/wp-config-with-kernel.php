<?php

use Urisoft\App\Kernel;

if ( file_exists( \dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once \dirname( __FILE__ ) . '/vendor/autoload.php';
} else {
    exit("Cant find the vendor autoload file.");
}

/**
 * The base configuration for WordPress.
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
 * @see https://codex.wordpress.org/Editing_wp-config.php
 */

// run setup.
$http_app = new Kernel(__DIR__);

// start enviroment with defined constants and directory structure.
$http_app->init('development'); // development | staging | production | secure

// start enviroment with BUT disable defined constants and directory structure.
$http_app->init('development', false); // development | staging | production | secure

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = env('DB_PREFIX');

// That's all, stop editing! Happy publishing.

// Absolute path to the WordPress directory.
if ( ! \defined( 'ABSPATH' ) ) {
    \define( 'ABSPATH', \dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
