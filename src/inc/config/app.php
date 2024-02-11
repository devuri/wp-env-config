<?php

/**
 * This file defines various framework configuration options using key-value pairs.
 *
 * The values can be set in this file or by using environment variables defined in the `.env` file.
 * By utilizing environment variables, we can easily configure and customize the framework for different environments.
 * Some values are predefined by the framework, while others can be explicitly defined here as per specific requirements.
 *
 * The configuration options can be accessed in two ways:
 * 1. Using the `config()` framework helper function, which provides easy access to the configuration values.
 * 2. Utilizing the `get_config()` method available in the framework's Kernel, which returns the configuration options as an array.
 *
 * Note that almost all configuration options in this file are optional, as the framework provides sensible defaults for required values.
 * Any options not explicitly set here will be automatically handled by the framework.
 *
 * @var array
 */
return [

    /*
     * Security settings for the WordPress application.
     *
     * This array contains various security settings to enhance the security of the WordPress application.
     *
     * @var array $security {
     *     An array of security settings.
     *
     *     @type string|null $encryption_key  Full path to encryption key file (.txt) e.g., 'home/user/etc/.myweb-app-secret'
     *                                        This will become home/user/etc/.myweb-app-secret.txt.
     *                                        Set to null if encryption key is not defined.
     *     @type bool $brute-force            Whether to enable brute force protection.
     *     @type bool $two-factor             Whether to enable two-factor authentication.
     *     @type bool $no-pwned-passwords     Whether to check for passwords that have been exposed in data breaches.
     *     @type array|null $admin-ips        An array of IP addresses allowed for administrative access.
     *                                        Set to null or an empty array to disable the feature.
     *                                        Format: ['192.168.000.41', '192.168.000.34']
     * }
     */
    'security'         => [
        'encryption_key'     => null,
        'brute-force'        => true,
        'two-factor'         => true,
        'no-pwned-passwords' => true,
        'admin-ips'          => [],
    ],

    /*
     * Email SMTP configuration for WordPress.
     *
     * Configure the mailer settings for sending emails in WordPress using various providers such as Brevo, Postmark,
     * SendGrid, Mailgun, and SES.
     *
     * Available providers:
     * - 'brevo': Brevo mailer using the API key specified in the environment variable 'BREVO_API_KEY'.
     * - 'postmark': Postmark mailer using the token specified in the environment variable 'POSTMARK_TOKEN'.
     * - 'sendgrid': SendGrid mailer using the API key specified in the environment variable 'SENDGRID_API_KEY'.
     * - 'mailgun': Mailgun mailer using the domain, secret, endpoint, and scheme specified in the respective
     *              environment variables 'MAILGUN_DOMAIN', 'MAILGUN_SECRET', 'MAILGUN_ENDPOINT', and 'MAILGUN_SCHEME'.
     * - 'ses': SES (Amazon Simple Email Service) mailer using the access key, secret access key, and region specified
     *          in the respective environment variables 'AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', and 'AWS_DEFAULT_REGION'.
     *
     * Note: Make sure to set the required environment variables for each mailer provider.
     */

    'mailer'           => [
        'brevo'      => [
            'apikey' => env( 'BREVO_API_KEY' ),
        ],

        'postmark'   => [
            'token' => env( 'POSTMARK_TOKEN' ),
        ],

        'sendgrid'   => [
            'apikey' => env( 'SENDGRID_API_KEY' ),
        ],

        'mailerlite' => [
            'apikey' => env( 'MAILERLITE_API_KEY' ),
        ],

        'mailgun'    => [
            'domain'   => env( 'MAILGUN_DOMAIN' ),
            'secret'   => env( 'MAILGUN_SECRET' ),
            'endpoint' => env( 'MAILGUN_ENDPOINT', 'api.mailgun.net' ),
            'scheme'   => 'https',
        ],

        'ses'        => [
            'key'    => env( 'AWS_ACCESS_KEY_ID' ),
            'secret' => env( 'AWS_SECRET_ACCESS_KEY' ),
            'region' => env( 'AWS_DEFAULT_REGION', 'us-east-1' ),
        ],
    ],

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
    'sudo_admin'       => env( 'SUDO_ADMIN', 1 ),

    /*
     * Sudo Admin Group: A group of users with higher administrative privileges.
     *
     * This option allows us to define a group of users with elevated administrative privileges,
     * in addition to the main sudo admin user defined in the 'sudo_admin' option.
     * The value should be an array of user IDs.
     *
     * @var array|null An array of user IDs representing the sudo admin group. Setting it to null disables the sudo admin group feature.
     *
     * @default null
     */
    'sudo_admin_group' => null,

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
    'web_root'         => 'public',

    /*
     * Configuration settings for the S3 Uploads plugin.
     *
     * @var array $s3_uploads
     *   Configuration options for S3 Uploads.
     *
     * @param string $s3_uploads['bucket']
     *   The name of the S3 bucket to upload files to. Defaults to 'site-uploads'.
     *
     * @param string $s3_uploads['key']
     *   The AWS access key ID. Defaults to an empty string.
     *
     * @param string $s3_uploads['secret']
     *   The AWS secret access key. Defaults to an empty string.
     *
     * @param string $s3_uploads['region']
     *   The AWS region to use. Defaults to 'us-east-1'.
     *
     * @param string $s3_uploads['bucket-url']
     *   The base URL of the S3 bucket. Defaults to 'https://example.com'.
     *
     * @param string $s3_uploads['object-acl']
     *   The access control list for uploaded objects. Defaults to 'public'.
     *
     * @param string $s3_uploads['expires']
     *   The expiration time for HTTP caching headers. Defaults to '2 days'.
     *
     * @param string $s3_uploads['http-cache']
     *   The value for the 'Cache-Control' header. Defaults to '300'.
     */
    's3uploads'        => [
        'bucket'     => env( 'S3_UPLOADS_BUCKET', 'site-uploads' ),
        'key'        => env( 'S3_UPLOADS_KEY', '' ),
        'secret'     => env( 'S3_UPLOADS_SECRET', '' ),
        'region'     => env( 'S3_UPLOADS_REGION', 'us-east-1' ),
        'bucket-url' => env( 'S3_UPLOADS_BUCKET_URL', 'https://example.com' ),
        'object-acl' => env( 'S3_UPLOADS_OBJECT_ACL', 'public' ),
        'expires'    => env( 'S3_UPLOADS_HTTP_EXPIRES', '2 days' ),
        'http-cache' => env( 'S3_UPLOADS_HTTP_CACHE_CONTROL', '300' ),
    ],

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
    'asset_dir'        => 'assets',

    /*
     * Sets the content directory for the project.
     *
     * By default, the project uses the 'app' directory as the content directory.
     * The 'app' directory is equivalent to the 'wp-content' directory.
     * However, this can be modified to use a different directory, such as 'content'.
     */
    'content_dir'      => 'app',

    /*
     * Sets the plugins directory.
     *
     * The plugins directory is located outside the project directory and
     * allows for installation and management of plugins using Composer.
     */
    'plugin_dir'       => 'plugins',

    /*
     * Sets the directory for Must-Use (MU) plugins.
     *
     * The MU plugins directory is used to include custom logic that is considered essential for the project.
     * It provides a way to include functionality that should always be active and cannot be deactivated by site administrators.
     *
     * By default, the framework includes the 'compose' MU plugin, which includes the 'web_app_config' hook.
     * This hook can be leveraged to configure the web application in most cases.
     */
    'mu_plugin_dir'    => 'mu-plugins',

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
    'sqlite_dir'       => 'sqlitedb',
    'sqlite_file'      => '.sqlite-wpdatabase',

    /*
     * Sets the default fallback theme for the project.
     *
     * By default, WordPress uses one of the "twenty*" themes as the fallback theme.
     * However, in our project, we have the flexibility to define our own custom fallback theme.
     */
    'default_theme'    => 'brisko',

    /*
     * Disable WordPress updates.
     *
     * Since we will manage updates with Composer,
     * it is recommended to disable all updates within WordPress.
     */
    'disable_updates'  => true,

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
    'can_deactivate'   => true,

    /*
     * Sets the directory for additional themes.
     *
     * In addition to the default 'themes' directory, we can utilize the 'templates' directory
     * to include our own custom themes for the project. This provides flexibility and allows
     * us to have a separate location for our custom theme files.
     */
    'theme_dir'        => 'templates',

    /*
     * Sets the error handler for the project.
     *
     * The framework provides options for using either Oops or Symfony as the error handler.
     * By default, the Symfony error handler is used.
     * To change the error handler, set the 'error_handler' option to 'oops'.
     * To disable the error handlers completely, set the 'error_handler' option to false.
     *
     * Please note that the error handler will only run in 'debug', 'development', or 'local' environments.
     */
    'error_handler'    => null,

    /*
     * Redis cache configuration for the WordPress application.
     *
     * This array contains configuration settings for the Redis cache integration in WordPress.
     * For detailed installation instructions, refer to the documentation at:
     * {@link https://github.com/rhubarbgroup/redis-cache/blob/develop/INSTALL.md}
     *
     * @var array $redis {
     *     An array of Redis cache configuration settings.
     *
     *     @type bool $disabled            Whether Redis cache is disabled.
     *                                    Default: false if the environment variable 'WP_REDIS_DISABLED' is not set.
     *     @type string $host              The Redis server hostname or IP address.
     *                                    Default: '127.0.0.1' if the environment variable 'WP_REDIS_HOST' is not set.
     *     @type int $port                 The Redis server port number.
     *                                    Default: 6379 if the environment variable 'WP_REDIS_PORT' is not set.
     *     @type string $password          The password to authenticate with Redis.
     *                                    Default: '' (empty string) if the environment variable 'WP_REDIS_PASSWORD' is not set.
     *                                    Using the phpredis extension for Redis.
     *     @type bool $adminbar            Whether to disable Redis cache for the WordPress admin bar.
     *                                    Default: false if the environment variable 'WP_REDIS_DISABLE_ADMINBAR' is not set.
     *     @type bool $disable-metrics     Whether to disable Redis cache metrics.
     *                                    Default: false if the environment variable 'WP_REDIS_DISABLE_METRICS' is not set.
     *     @type bool $disable-banners     Whether to disable Redis cache banners.
     *                                    Default: false if the environment variable 'WP_REDIS_DISABLE_BANNERS' is not set.
     *     @type string $prefix            The Redis cache key prefix.
     *                                    Default: MD5 hash of 'WP_HOME' environment variable concatenated with 'redis-cache'
     *                                    if the environment variable 'WP_REDIS_PREFIX' is not set.
     *     @type int $database             The Redis database index to use (0-15).
     *                                    Default: 0 if the environment variable 'WP_REDIS_DATABASE' is not set.
     *     @type int $timeout              The Redis connection timeout in seconds.
     *                                    Default: 1 if the environment variable 'WP_REDIS_TIMEOUT' is not set.
     *     @type int $read-timeout         The Redis read timeout in seconds.
     *                                    Default: 1 if the environment variable 'WP_REDIS_READ_TIMEOUT' is not set.
     * }
     */
    'redis'            => [
        'disabled'        => env( 'WP_REDIS_DISABLED', false ),
        'host'            => env( 'WP_REDIS_HOST', '127.0.0.1' ),
        'port'            => env( 'WP_REDIS_PORT', 6379 ),
        'password'        => env( 'WP_REDIS_PASSWORD', '' ),
        'adminbar'        => env( 'WP_REDIS_DISABLE_ADMINBAR', false ),
        'disable-metrics' => env( 'WP_REDIS_DISABLE_METRICS', false ),
        'disable-banners' => env( 'WP_REDIS_DISABLE_BANNERS', false ),
        'prefix'          => env( 'WP_REDIS_PREFIX', md5( env( 'WP_HOME' ) ) . 'redis-cache' ),
        'database'        => env( 'WP_REDIS_DATABASE', 0 ),
        'timeout'         => env( 'WP_REDIS_TIMEOUT', 1 ),
        'read-timeout'    => env( 'WP_REDIS_READ_TIMEOUT', 1 ),
    ],

    /*
     * Represents a public key used for encryption or verification purposes.
     * The public key can be stored as an option in the WordPress options table.
     *
     * The framework assumes that the public keys are stored in a top-level directory called "publickeys" in either the .pub or .pem format.
     * The keys can be retrieved and used as needed. Plugins can be used to fetch and save the keys.
     *
     * IMPORTANT: If you decide to save these keys, use the base64_encode function.
     * base64_encode is a function commonly used to encode binary data into a text format that can be safely stored or transmitted in various systems.
     * It takes binary data as input and returns a string consisting of characters from a predefined set (64 characters).
     * This encoding process ensures that the encoded data remains intact and can be decoded back into its original form when needed.
     *
     * use the command to generate key files: php nino config create-public-key
     * This will generate a sample key with uuid filename, replace the sample key with your own and add the filename to env file.
     *
     * @var array $publickey An array containing the UUID of the public key stored as an option in the WordPress options table.
     */
    'publickey'        => [
        'app-key' => env( 'WEB_APP_PUBLIC_KEY', null ),
    ],

    /*
     * Defines the public key directory.
     *
     * This is the directory where we store out public key files.
     * the directory here is relative to the application root path
     */
    'publickey_dir'    => 'pubkeys',
];
