<?php

use DevUri\Config\Kernel;

/*
 * This is the bootstrap file for the web application.
 *
 * It loads the necessary files and sets up the environment for the application to run.
 * This includes initializing the Composer autoloader, which is used to load classes and packages.
 *
 * The application uses Composer to manage dependencies and packages including WordPress themes and plugins, which allows you to easily add, update, and remove packages as needed.
 * To install or update packages, you can use the `composer` command line.
 *
 * If you need to modify the behavior of the application, you should do so by editing the config.php or .env configuration files.
 * This makes it easier to maintain the application and deploy updates without introducing new bugs or breaking changes.
 *
 * IMPORTANT: Do NOT modify this file unless you are sure of what you are doing.
 * Any changes you make to this file may affect the behavior of the entire application and cause errors or unexpected behavior.
 * If you are unsure about how to modify the application, please refer to the documentation or seek assistance from a qualified developer.
 *
 * To modify the setup, please refer to the documentation for instructions.
 *
 * @see https://github.com/devuri/wp-env-config
 */
if ( file_exists( \dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once \dirname( __FILE__ ) . '/vendor/autoload.php';
} else {
    exit("Cant find the vendor autoload file.");
}

/**
 * Start the web application.
 *
 * @var Kernel
 */
$http_app = new Kernel( __DIR__, [
    'web_root'      => 'public',
    'content_dir'   => 'app',
    'plugin_dir'    => 'app/plugins',
    'mu_plugin_dir' => 'app/mu-plugins',
    'default_theme' => 'brisko',
] );

/*
 * Load constant overrides.
 *
 * This will load constant values that override constants defined in setup.
 */
$http_app->overrides();

/*
 * Configuration settings for your web application.
 *
 * We recommend using the .env file to set these values.
 * The possible values are: 'debug', 'development', 'staging', 'production', or 'secure'.
 * The web application will use either the value of WP_ENVIRONMENT_TYPE or 'production'.
 *
 * Error handler: the framework allows for the use of oops or symfony.
 * The default error handler is symfony, to change the handler use `init(['errors' => 'oops'])`
 * to disable the handlers completely use `init(['errors' => null])`
 *
 * If you want to set the environment to 'development', add the following to your .env
 * WP_ENVIRONMENT_TYPE=development
 */
$http_app->init();

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = env('DB_PREFIX');
