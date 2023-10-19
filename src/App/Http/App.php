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
    public function __construct( string $app_path, string $options = 'app', ?array $tenant_ids = null )
    {
        $this->app_path = $app_path;

        /*
         * We need setup to get access to our env values.
         *
         * @var Setup
         */
        if( $tenant_ids ) {
			$this->setup = new Setup( $this->app_path, ['tenant_ids' => $tenant_ids] );
		} else {
			$this->setup = new Setup( $this->app_path );
		}


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
}
