<?php

return [
    /*
     * Sudo Admin: The main administrator or developer.
     *
     * By default, all admin users are considered equal in WordPress. However, this option allows us to create
     * a higher level of administrative privileges for a specific user.
     *
     * @var int|null The user ID of the sudo admin. Setting it to null disables the sudo admin feature.
     *
     * @default null
     */
    "sudo_admin" => null,

    /*
     * Web Root: the public web directory.
     *
     * By default, the project's web root is set to "public". If you change this to something other than "public",
     * you will also need to edit the composer.json file. For example, if our web root is "public_html", the relevant
     * composer.json entries would be:
     *
     * "wordpress-install-dir": "public_html/wp",
     * "installer-paths": {
     *     "public_html/app/mu-plugins/{$name}/": [
     *         "type:wordpress-muplugin"
     *     ],
     *     "public_html/app/plugins/{$name}/": [
     *         "type:wordpress-plugin"
     *     ],
     *     "public_html/template/{$name}/": [
     *         "type:wordpress-theme"
     *     ]
     * }
     */
    "web_root" => "public",

    /*
     * Global assets directory.
     *
     * This configuration allows us to define a directory for globally accessible assets.
     * If we are using build tools like webpack, mix, vite, etc., this directory can be used to store compiled assets.
     * The path is relative to the `web_root` setting, so if our web root is `public`, assets would be in `public/assets`.
     *
     * The asset URL can be configured by setting the ASSET_URL in your .env file.
     *
     * Global helpers can be used in the web application to interact with these assets:
     *
     * - asset($asset): Returns the full URL of the asset. The $asset parameter is the path to the asset, e.g., "/images/thing.png".
     *   Example: asset("/images/thing.png") returns "https://example.com/assets/dist/images/thing.png".
     *
     * - asset_url($path): Returns the asset URL without the filename. The $path parameter is the path to the asset.
     *   Example: asset_url("/dist") returns "https://example.com/assets/dist/".
     */
    "asset_dir" => "assets",

    /*
     * Sets the content directory for the project.
     *
     * By default, the project uses the 'app' directory as the content directory.
     * The 'app' directory is equivalent to the 'wp-content' directory.
     * However, this can be modified to use a different directory, such as 'content'.
     */
    "content_dir" => "app",

    /*
     * Sets the plugins directory.
     *
     * The plugins directory is located outside the project directory and
     * allows for installation and management of plugins using Composer.
     */
    "plugin_dir" => "plugins",

    /*
     * Sets the directory for Must-Use (MU) plugins.
     *
     * The MU plugins directory is used to include custom logic that is considered essential for the project.
     * It provides a way to include functionality that should always be active and cannot be deactivated by site administrators.
     *
     * By default, the framework includes the 'compose' MU plugin, which includes the 'web_app_config' hook.
     * This hook can be leveraged to configure the web application in most cases.
     */
    "mu_plugin_dir" => "mu-plugins",

    /*
     * SQLite Configuration
     *
     * WordPress supports SQLite via a plugin (which might soon be included in core).
     * These options need to be set when using the drop-in SQLite database with WordPress.
     * The SQLite database location and filename can be configured here.
     * The `sqlite_dir` directory is relative to `APP_PATH`.
     *
     * @see https://github.com/aaemnnosttv/wp-sqlite-db
     */
    "sqlite_dir" => "sqlitedb",
    "sqlite_file" => ".sqlite-wpdatabase",

    /*
     * Sets the default fallback theme for the project.
     *
     * By default, WordPress uses one of the "twenty*" themes as the fallback theme.
     * However, in our project, we have the flexibility to define our own custom fallback theme.
     */
    "default_theme" => "brisko",

    /*
     * Disable WordPress updates.
     *
     * Since we will manage updates with Composer,
     * it is recommended to disable all updates within WordPress.
     */
    "disable_updates" => true,

    /*
     * Controls whether we can deactivate plugins.
     *
     * This setting determines whether the option to deactivate plugins is available.
     * Setting it to false will hide the control to deactivate plugins,
     * but it does not remove the functionality itself.
     *
     * Setting it to true brings back the ability to deactivate plugins.
     * The default setting is true.
     */
    "can_deactivate" => false,

    /*
     * Sets the directory for additional themes.
     *
     * In addition to the default 'themes' directory, we can utilize the 'templates' directory
     * to include our own custom themes for the project. This provides flexibility and allows
     * us to have a separate location for our custom theme files.
     */
    "theme_dir" => "templates",

    /*
     * Sets the error handler for the project.
     *
     * The framework provides options for using either Oops or Symfony as the error handler.
     * By default, the Symfony error handler is used.
     * To change the error handler, set the 'error_handler' option to 'oops'.
     * To disable the error handlers completely, set the 'error_handler' option to null.
     *
     * Please note that the error handler will only run in 'debug', 'development', or 'local' environments.
     */
    "error_handler" => null,
];
