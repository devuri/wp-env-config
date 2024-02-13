<?php

use Defuse\Crypto\Key;
use Urisoft\App\Core\Plugin;
use Urisoft\App\Http\AppFramework;
use Urisoft\App\Http\Asset;
use Urisoft\App\Http\Tenancy;
use Urisoft\DotAccess;
use Urisoft\Encryption;

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
 * @throws InvalidArgumentException
 *
 * @return mixed
 */
function env( string $name, $default_or_encrypt = null, bool $strtolower = false )
{
    if ( isset( $_ENV[ $name ] ) ) {
        $env_data = $_ENV[ $name ];
    } else {
        $env_data = $default_or_encrypt;
    }

    if ( \is_bool( $default_or_encrypt ) && true === $default_or_encrypt ) {
        if ( ! \defined('APP_PATH') ) {
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
     * Initializes the AppFramework Kernel with optional multi-tenant support.
     *
     * Sets up the application kernel based on the provided application directory path.
     * In multi-tenant configurations, it dynamically adjusts the environment based on
     * the current HTTP host and tenant-specific settings. It ensures all required
     * environment variables for the landlord (main tenant) are set and terminates
     * execution with an error message if critical configurations are missing or if
     * the tenant's domain is not recognized.
     *
     * @param string $app_path The base directory path of the application (e.g., __DIR__).
     * @param string $options  Optional. The configuration filename, defaults to 'app'.
     *
     * @throws Exception If there are issues loading environment variables or initializing the AppFramework.
     * @throws Exception If required multi-tenant environment variables are missing or if the tenant's domain is not recognized.
     *
     * @return Urisoft\App\Http\BaseKernel The initialized application kernel.
     */
    function wpc_app( string $app_path, string $options = 'app' ): Urisoft\App\Http\BaseKernel
    {
        if ( ! \defined('SITE_CONFIG_DIR') ) {
            \define( 'SITE_CONFIG_DIR', 'config');
        }

        /**
         * Handle multi-tenant setups.
         *
         * @var Tenancy
         */
        $tenancy = new Tenancy( $app_path, SITE_CONFIG_DIR );
        $tenancy->initialize();

        try {
            $app = new AppFramework( $app_path, SITE_CONFIG_DIR, $options );
        } catch ( Exception $e ) {
            wp_terminate('Framework Initialization Error: ' );
        }

        // @phpstan-ignore-next-line
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
     * @return (null|bool|mixed|(mixed|(mixed|string)[]|true)[]|string)[]
     *
     * @psalm-return array{security: array{'brute-force': true, 'two-factor': true, 'no-pwned-passwords': true, 'admin-ips': array<empty, empty>}, mailer: array{brevo: array{apikey: mixed}, postmark: array{token: mixed}, sendgrid: array{apikey: mixed}, mailerlite: array{apikey: mixed}, mailgun: array{domain: mixed, secret: mixed, endpoint: mixed, scheme: 'https'}, ses: array{key: mixed, secret: mixed, region: mixed}}, sudo_admin: mixed, sudo_admin_group: null, web_root: 'public', s3uploads: array{bucket: mixed, key: mixed, secret: mixed, region: mixed, 'bucket-url': mixed, 'object-acl': mixed, expires: mixed, 'http-cache': mixed}, asset_dir: 'assets', content_dir: 'app', plugin_dir: 'plugins', mu_plugin_dir: 'mu-plugins', sqlite_dir: 'sqlitedb', sqlite_file: '.sqlite-wpdatabase', default_theme: 'brisko', disable_updates: true, can_deactivate: false, theme_dir: 'templates', error_handler: null, redis: array{disabled: mixed, host: mixed, port: mixed, password: mixed, adminbar: mixed, 'disable-metrics': mixed, 'disable-banners': mixed, prefix: mixed, database: mixed, timeout: mixed, 'read-timeout': mixed}, publickey: array{'app-key': mixed}}
     */
    function app_config_default(): array
    {
        return require_once __DIR__ . '/config/app.php';
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
        $dotdata = new DotAccess( APP_PATH . SITE_CONFIG_DIR . '/app.php' );
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

    return filter_var($input, FILTER_UNSAFE_RAW, FILTER_FLAG_NO_ENCODE_QUOTES);
}

function env_tenant_id(): ?string
{
    if ( \defined( 'APP_TENANT_ID' ) ) {
        return APP_TENANT_ID;
    }
    if ( env( 'APP_TENANT_ID' ) ) {
        return env( 'APP_TENANT_ID' );
    }

    return null;
}

/**
 * Custom function to terminate script execution, display a message, and set an HTTP status code.
 *
 * @param string $message     The message to display.
 * @param int    $status_code The HTTP status code to send.
 */
function wp_terminate($message, int $status_code = 500): void
{
    http_response_code($status_code);
    ?><!DOCTYPE html><html lang='en'>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset='UTF-8'" />
		<meta name="viewport" content="width=device-width">
		<title>Unavailable</title>
		<style type="text/css">
			html {
				background: #f1f1f1;
			}
			body {
				color: #444;
				max-width: 700px;
				margin: 2em auto;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			}
			h1 {
				border-bottom: 1px solid #dadada;
				clear: both;
				color: #666;
				font-size: 24px;
				margin: 30px 0 0 0;
				padding: 0;
				padding-bottom: 7px;
			}
			footer {
			    clear: both;
			    color: #cdcdcd;
			    margin: 30px 0 0 0;
			    padding: 0;
			    padding-bottom: 7px;
				font-size: small;
				text-transform: uppercase;
			}
			#error-page {
				background: #fff;
				margin-top: 50px;
				-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
				box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
				padding: 1em 2em;
			}
			#error-page p,
			#error-page .die-message {
				font-size: 14px;
				line-height: 1.5;
				margin: 25px 0 20px;
			}
			#error-page code {
				font-family: Consolas, Monaco, monospace;
			}
			ul li {
				margin-bottom: 10px;
				font-size: 14px ;
			}
			a {
				color: #0073aa;
			}
		</style>
	</head>
	<body id="page">
		<div id="error-page" class="">
			<?php echo $message; ?>
		</div>
		<footer align="center">
			Status Code:<span style="color:#afafaf"><?php echo $status_code; ?></span>
		</footer>
	</body>
	</html><?php
    exit;
}

/**
 * Cleans up sensitive environment variables.
 *
 * This function removes specified environment variables from the $_ENV superglobal
 * and the environment to help secure sensitive information.
 *
 * @param array $sensitives An array of environment variable names to be cleaned up.
 */
function sclean_sensitive_env(array $sensitives): void
{
    foreach ($sensitives as $var) {
        unset($_ENV[$var]);
        putenv($var . '='); // Ensure to concatenate '=' to effectively unset it
    }
}
