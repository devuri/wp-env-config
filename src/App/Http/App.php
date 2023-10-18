<?php

namespace Urisoft\App\Http;

use Exception;
use Symfony\Component\ErrorHandler\Debug;
use Urisoft\App\Setup;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class App
{
    protected $app_path = null;
    protected $setup    = null;
    protected $config   = null;

    /**
     * Setup App.
     *
     * @param string $app_path The base app path, e.g., __DIR__.
     * @param string $options  The configuration options filename, e.g., app.php.
     *
     * @throws Exception When the options file is not found.
     */
    public function __construct( string $app_path, string $options = 'app' )
    {
        $this->app_path = $app_path;

        /*
         * We need setup to get access to our env values.
         *
         * @var Setup
         */
        $this->setup = new Setup( $this->app_path );

		//$supported_names = [];
		//$this->setup = new Setup( $this->app_path, $supported_names );
		// tenant files live in app_path/sites/tenant_id/.env etc

        if ( ! file_exists( $this->app_path . "/{$options}.php" ) ) {
            throw new Exception( 'Options file not found.', 1 );
        }

        $this->set_config( $options );

        // handle errors early.
        $this->set_app_errors();
    }

    /**
     * Get the kernel instance.
     *
     * @return BaseKernel The kernel instance.
     */
    public function kernel(): BaseKernel
    {
        if ( ! \is_array( $this->config ) ) {
            exit( 'Uncaught TypeError BaseKernel($args) must be of type array' );
        }

        return new BaseKernel( $this->app_path, $this->config, $this->setup );
    }

    /**
     * Set up the application error handling based on environment settings.
     */
    protected function set_app_errors(): void
    {
        if ( ! \in_array( env( 'WP_ENVIRONMENT_TYPE' ), [ 'debug', 'development', 'dev', 'local' ], true ) ) {
            return;
        }

        if ( \defined( 'WP_INSTALLING' ) ) {
            return;
        }

        if ( false === $this->config['error_handler'] ) {
            return;
        }

        if ( true === $this->config['error_handler'] ) {
            Debug::enable();

            return;
        }

        if ( \is_null( $this->config['error_handler'] ) || 'symfony' === $this->config['error_handler'] ) {
            Debug::enable();
        } elseif ( 'oops' === $this->config['error_handler'] ) {
            $whoops = new Run();
            $whoops->pushHandler( new PrettyPageHandler() );
            $whoops->register();
        }
    }

    /**
     * Set the config options.
     *
     * @param string $options The configuration options filename, e.g., app.php.
     * @param string $tenant The tenant data e.g., [ 'tenant_id' => 495743 ].
     */
    protected function set_config( string $options, array $tenant = [] ): void
    {
        $config = require_once $this->app_path . "/{$options}.php";

        $this->config = array_merge( $config, $tenant );
    }

	/**
	 * Get the server host/domain and prefix type (http or https).
	 *
	 * @return array An associative array with 'prefix' and 'domain' keys.
	 */
	protected function get_server_host()
	{
	    $host_domain = strtolower(stripslashes($_SERVER['HTTP_HOST']));
	    $prefix = 'http';

	    if (str_ends_with($host_domain, ':80')) {
	        $host_domain = substr($host_domain, 0, -3);
	    } elseif (str_ends_with($host_domain, ':443')) {
	        $host_domain = substr($host_domain, 0, -4);
	        $prefix = 'https';
	    }

	    return [
	        'prefix' => $prefix,
	        'domain' => $host_domain,
	    ];
	}
}
