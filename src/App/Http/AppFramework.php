<?php

namespace Urisoft\App\Http;

use Exception;
use Symfony\Component\ErrorHandler\Debug;
use Urisoft\App\Setup;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class AppFramework
{
    protected $app_path = null;
    protected $setup    = null;
    protected $config   = null;
    protected $config_dir;

    /**
     * Setup App.
     *
     * @param string $app_path    The base app path, e.g., __DIR__.
     * @param string $site_config The config directory location.
     * @param string $options     The configuration options filename, e.g., app.php.
     *
     * @throws Exception When the options file is not found.
     */
    public function __construct( string $app_path, string $site_config, string $options = 'app' )
    {
        $this->app_path   = $app_path;
        $this->config_dir = $site_config;

        /*
         * We need setup to get access to our env values.
         *
         * @var Setup
         */
        $this->setup = new Setup( $this->app_path );

        /**
         * setup params.
         *
         * @var string
         */
        $params_file = "{$this->app_path}/{$this->config_dir}/{$options}.php";
        if ( file_exists( $params_file ) ) {
            $this->config = require_once $params_file;
        } else {
            throw new Exception( 'Options file not found.', 1 );
        }

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
            wp_terminate( 'Uncaught TypeError BaseKernel($args) must be of type array' );
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
}
