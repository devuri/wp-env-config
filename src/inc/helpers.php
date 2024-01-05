<?php

use Defuse\Crypto\Key;
use Urisoft\App\Core\Plugin;
use Urisoft\App\Http\AppFramework;
use Urisoft\App\Http\Asset;
use Urisoft\DotAccess;
use Urisoft\Encryption;
use InvalidArgumentException;

// @codingStandardsIgnoreFile.

if ( ! \function_exists( 'asset' ) ) {
    /**
     * The Asset url.
     *
     * You can configure the asset URL by setting the ASSET_URL in your .env
     * Or optionally in the main config file.
     *
     * @param string      $asset path to the asset like: "/images/thing.png"
     * @param null|string $path
     *
     * @return string
     */
    function asset( string $asset, ?string $path = null ): string
    {
        return Asset::url( $asset, $path );
    }
}

if ( ! \function_exists( 'asset_url' ) ) {
    /**
     * The Asset url only.
     *
     * @param null|string $path
     *
     * @return string
     */
    function asset_url( ?string $path = null ): string
    {
        return Asset::url( '/', $path );
    }
}

/**
 * Get the value of an environment variable.
 *
 * @param string     $name               the environment variable name.
 * @param null|mixed $default_or_encrypt provides a default value or bool `true` which indicates the output should be encrypted
 * @param bool       $strtolower
 *
 * @return mixed
 *  @throws InvalidArgumentException
 */
function env( string $name, $default_or_encrypt = null, bool $strtolower = false )
{
    if ( isset( $_ENV[ $name ] ) ) {
        $env_data = $_ENV[ $name ];
    } else {
        $env_data = $default_or_encrypt;
    }

    if ( \is_bool( $default_or_encrypt ) && true === $default_or_encrypt ) {

		if( ! defined('APP_PATH') ){
			throw new InvalidArgumentException( 'Error: APP_PATH is not defined', 1 );
		}

		$encryption = new Encryption( APP_PATH );

		// returns encrypted and base64 encoded.
		return $encryption->encrypt( $env_data );
    }

    if ( is_int_val( $env_data ) ) {
        return (int) $env_data;
    }

    if ( \in_array( $env_data, [ 'True', 'true', 'TRUE' ], true ) ) {
        return true;
    }

    if ( \in_array( $env_data, [ 'False', 'false', 'FALSE' ], true ) ) {
        return false;
    }

    if ( \in_array( $env_data, [ 'Null', 'null', 'NULL' ], true ) ) {
        return '';
    }

    if ( $strtolower ) {
        return strtolower( $env_data );
    }

    return $env_data;
}

if ( ! \function_exists( 'is_int_val' ) ) {
    /**
     * Check if a string is an integer value.
     *
     * @param int|string $str The string to check.
     *
     * @return bool Returns true if the string is an integer value, and false otherwise.
     */
    function is_int_val( $str )
    {
        return is_numeric( $str ) && \intval( $str ) == $str;
    }
}

if ( ! \function_exists( 'get_http_env' ) ) {
    /**
     * Get the current set wp app env.
     *
     * This is used in the compose mu plugin.
     *
     * @return null|string the current app env set, or null if not defined
     */
    function get_http_env(): ?string
    {
        if ( ! \defined( 'HTTP_ENV_CONFIG' ) ) {
            return null;
        }

        return strtoupper( HTTP_ENV_CONFIG );
    }
}

if ( ! \function_exists( 'wpc_app' ) ) {
    /**
     * Start up and set the AppFramework Kernel.
     *
     * @param string $app_path The base app path. like __DIR__
     * @param string $options  The options filename, default 'app'
     *
     * @return \Urisoft\App\Http\BaseKernel
     */
    function wpc_app( string $app_path, string $options = 'app', ?array $tenant_ids = null ): Urisoft\App\Http\BaseKernel
    {
        try {
            $app = new AppFramework( $app_path, $options, $tenant_ids );
        } catch ( Exception $e ) {
            exit( $e->getMessage() );
        }

        return $app->kernel();
    }
}

if ( ! \function_exists( 'wpc_app_config_core' ) ) {
    /**
     * Start and load core plugin.
     *
     * @return void
     */
    function wpc_app_config_core(): void
    {
        if ( ! \defined( 'ABSPATH' ) ) {
            exit;
        }

        Plugin::init();
    }
}

if ( ! \function_exists( 'wpc_installed_plugins' ) ) {
    /**
     * Get installed plugins.
     *
     * @return string[]
     *
     * @psalm-return list<string>
     */
    function wpc_installed_plugins(): array
    {
        $plugins = get_plugins();

        $plugin_slugs = [];

        foreach ( $plugins as $key => $plugin ) {
            $slug = explode( '/', $key );

			// Add the slug to the array
            $plugin_slugs[] = '"wpackagist-plugin/' . $slug[0] . '": "*",';

        }

        return $plugin_slugs;
    }
}// end if

if ( ! \function_exists( 'app_config_default' ) ) {
    /**
     * Get default app config values.
     *
     * @return (null|((mixed|string)[]|mixed|true)[]|bool|mixed|string)[]
     *
     * @psalm-return array{security: array{'brute-force': true, 'two-factor': true, 'no-pwned-passwords': true, 'admin-ips': array<empty, empty>}, mailer: array{brevo: array{apikey: mixed}, postmark: array{token: mixed}, sendgrid: array{apikey: mixed}, mailerlite: array{apikey: mixed}, mailgun: array{domain: mixed, secret: mixed, endpoint: mixed, scheme: 'https'}, ses: array{key: mixed, secret: mixed, region: mixed}}, sudo_admin: mixed, sudo_admin_group: null, web_root: 'public', s3uploads: array{bucket: mixed, key: mixed, secret: mixed, region: mixed, 'bucket-url': mixed, 'object-acl': mixed, expires: mixed, 'http-cache': mixed}, asset_dir: 'assets', content_dir: 'app', plugin_dir: 'plugins', mu_plugin_dir: 'mu-plugins', sqlite_dir: 'sqlitedb', sqlite_file: '.sqlite-wpdatabase', default_theme: 'brisko', disable_updates: true, can_deactivate: false, theme_dir: 'templates', error_handler: null, redis: array{disabled: mixed, host: mixed, port: mixed, password: mixed, adminbar: mixed, 'disable-metrics': mixed, 'disable-banners': mixed, prefix: mixed, database: mixed, timeout: mixed, 'read-timeout': mixed}, publickey: array{'app-key': mixed}}
     */
    function app_config_default(): array
    {
        return require_once __DIR__ . '/app.php';
    }
}

/**
 * Retrieve configuration data using dot notation.
 *
 * This function provides a convenient way to access nested data stored in a configuration file
 * using dot notation. It uses the DotAccess library to facilitate easy access to the data.
 *
 * @param null|string $key         The dot notation key to access the data. If null, the entire
 *                                 configuration data will be returned.
 * @param mixed       $default     The default value to return if the key is not found.
 * @param mixed       $data_access
 *
 * @return mixed The value associated with the specified key or the default value if the key
 *               is not found. If no key is provided (null), the entire configuration data is
 *               returned.
 *
 * @see https://github.com/devuri/dot-access DotAccess library used for dot notation access.
 */
function config( ?string $key = null, $default = null, $data_access = false )
{
    $dotdata = null;

    if ( $data_access ) {
        $dotdata = $data_access;
    } else {
        $dotdata = new DotAccess( APP_PATH . '/app.php' );
    }

    if ( \is_null( $key ) ) {
        return $dotdata;
    }

    return $dotdata->get( $key, $default );
}

/**
 * Gets hash of given string.
 *
 * If no secret key is provided we will use the SECURE_AUTH_KEY wp key.
 *
 * @param string $data      Message to be hashed.
 * @param string $secretkey Secret key used for generating the HMAC variant.
 * @param string $algo      Name of selected hashing algorithm (i.e. "md5", "sha256", "haval160,4", etc..)
 *
 * @return string Returns a string containing the calculated hash value.
 *
 * @see https://www.php.net/manual/en/function.hash-hmac.php
 */
function evhash( $data, ?string $secretkey = null, string $algo = 'sha256' ): string
{
    if ( \is_null( $secretkey ) ) {
        return hash_hmac( $algo, $data, env( 'SECURE_AUTH_KEY' ) );
    }

    return hash_hmac( $algo, $data, $secretkey );
}

function get_server_host()
{
    if ( isset( $_SERVER['HTTP_HOST'] )) {
        $host_domain = strtolower( stripslashes( $_SERVER['HTTP_HOST'] ) );
    } else {
        $host_domain = null;
    }

    $prefix  = 'http';

    if ( str_ends_with( $host_domain, ':80' ) ) {
        $host_domain = substr( $host_domain, 0, -3 );
    } elseif ( str_ends_with( $host_domain, ':443' ) ) {
        $host_domain = substr( $host_domain, 0, -4 );
        $prefix      = 'https';
    }

    return [
        'prefix' => $prefix,
        'domain' => $host_domain,
    ];
}

function get_http_app_host(): ?string
{
    // $_SERVER variables can't always be completely trusted.
    if ( isset( $_SERVER['HTTP_HOST'] ) ) {
        // Sanitize the HTTP_HOST to allow only valid characters for a host
        $httpHost = filter_var( $_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING );

        $httpHost = app_sanitizer( $httpHost );

        $httpHost = strtolower( stripslashes( $httpHost ) );

        // Split the host into parts to handle subdomains or additional segments
        $hostParts = explode( '.', $httpHost );

        // Check that the host has at least two parts (e.g., domain and TLD)
        if ( \count( $hostParts ) >= 2 ) {
            return $httpHost;
        }

        return null;
    }

    return 'default_domain.com';
}


function get_app_request_url(): ?string
{
    $isHttps = is_app_https_secure();

    $app_host = strtolower( stripslashes( get_http_app_host() ) );

    if ( \is_null( $app_host ) ) {
        return null;
    }

    $protocol = $isHttps ? 'https' : 'http';

    $request_url = filter_var( $protocol . '://' . $app_host, FILTER_SANITIZE_URL );

    return strtolower( $request_url );
}

function is_app_https_secure(): bool
{
    if ( isset( $_SERVER['HTTPS'] ) ) {
        return filter_var( $_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN );
    }

    return false;
}

/*
 * Generates a list of WordPress plugins in Composer format.
 *
 * @return array An associative array of Composer package names and their version constraints.
 */
if ( ! \function_exists( 'app_packagist_plugins_list' ) ) {
    function app_packagist_plugins_list()
    {
        if ( ! \function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins = get_plugins();

        $plugins_list = [];

        foreach ( $all_plugins as $plugin_path => $plugin_data ) {
            // Extract the plugin slug from the directory name.
            $plugin_slug = sanitize_title( \dirname( $plugin_path ) );

            // Format the package name with the 'wpackagist-plugin' prefix.
            $package_name = "wpackagist-plugin/{$plugin_slug}";

            $plugins_list[ $package_name ] = 'latest';
        }

        return $plugins_list;
    }
}

/**
 * Basic Sanitize and prepare for a string input for safe usage in the application.
 *
 * This function sanitizes the input by removing leading/trailing whitespace,
 * stripping HTML and PHP tags, converting special characters to HTML entities,
 * and removing potentially dangerous characters for security.
 *
 * @param string $input The input string to sanitize.
 *
 * @return string The sanitized input ready for safe usage within the application.
 */
function app_sanitizer( string $input ): string
{
    $input = trim($input);
    $input = strip_tags($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    $input = str_replace(["'", "\"", "--", ";"], "", $input);

    return filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}
