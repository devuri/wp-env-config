<?php

namespace Urisoft\App\Http;

use Dotenv\Dotenv;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Urisoft\App\EnvGenerator;

class Tenancy
{
    private $app_path;
    private $config_dir;

    /**
     * Tenancy constructor.
     *
     * @param string $app_path    The base directory path of the application (e.g., __DIR__).
     * @param string $site_config The site config directory name
     */
    public function __construct( string $app_path, string $site_config )
    {
        $this->app_path   = $app_path;
        $this->config_dir = $site_config;
    }

    /**
     * Initializes the AppFramework Kernel with optional multi-tenant support.
     *
     * @throws Exception If there are issues loading environment variables or initializing the AppFramework.
     *
     * @return void
     */
    public function initialize(): void
    {
        if ( file_exists( "{$this->app_path}/{$this->config_dir}/tenancy.php" ) ) {
            require_once "{$this->app_path}/{$this->config_dir}/tenancy.php";
        }

        if ( \defined( 'ALLOW_MULTITENANT' ) && true === ALLOW_MULTITENANT ) {
            $this->setup_multi_tenant();
        }
    }

    /**
     * Sets up the environment for a multi-tenant configuration.
     */
    private function setup_multi_tenant(): void
    {
        $_app_http_host = HttpFactory::init()->get_http_host();

        $_dotenv = Dotenv::createImmutable( $this->app_path );

        try {
            $_dotenv->load();
            $_dotenv->required( 'LANDLORD_DB_HOST' )->notEmpty();
            $_dotenv->required( 'LANDLORD_DB_NAME' )->notEmpty();
            $_dotenv->required( 'LANDLORD_DB_USER' )->notEmpty();
            $_dotenv->required( 'LANDLORD_DB_PASSWORD' )->notEmpty();
            $_dotenv->required( 'LANDLORD_DB_PREFIX' )->notEmpty();
        } catch ( Exception $e ) {
            wp_terminate( 'Landlord info is required for multi-tenant', 403 );
        }

        $tenant = new DB( 'tenant', env( 'LANDLORD_DB_HOST' ), env( 'LANDLORD_DB_NAME' ), env( 'LANDLORD_DB_USER' ), env( 'LANDLORD_DB_PASSWORD' ), env( 'LANDLORD_DB_PREFIX' ) );
        $hostd  = $tenant->where( 'domain', $_app_http_host );

        if ( ! $hostd ) {
            wp_terminate( 'The website is not defined. Please review the URL and try again.', 403 );
        } else {
            $this->define_tenant_constants( $hostd[0] );
            $this->maybe_regenerate_env_file( $hostd[0]->uuid );
        }

        // Clean up sensitive environment variables
        sclean_sensitive_env( [ 'LANDLORD_DB_HOST', 'LANDLORD_DB_NAME', 'LANDLORD_DB_USER', 'LANDLORD_DB_PASSWORD', 'LANDLORD_DB_PREFIX' ] );

        unset( $_dotenv );
    }

    /**
     * Defines constants based on the tenant's information.
     *
     * @param object $tenant Tenant object with domain and uuid.
     */
    private function define_tenant_constants( $tenant ): void
    {
        \define( 'APP_HTTP_HOST', $tenant->domain );
        \define( 'APP_TENANT_ID', $tenant->uuid );
        \define( 'IS_MULTITENANT', true );

        if ( ! \defined( 'REQUIRE_TENANT_CONFIG' ) ) {
            \define( 'REQUIRE_TENANT_CONFIG', false );
        }
    }

    /**
     * Regenerates the tenant-specific .env file if it doesn't exist.
     *
     * @param string $tenant_id Tenant's UUID.
     */
    private function maybe_regenerate_env_file( string $tenant_id ): void
    {
        $tenant_env_path = "{$this->app_path}/{$this->config_dir}/{$tenant_id}/.env";
        if ( ! file_exists( $tenant_env_path ) ) {
            $generator = new EnvGenerator( new Filesystem() );
            $db_prefix = $this->get_db_prefix( $tenant_id );
            $generator->create( $tenant_env_path, APP_HTTP_HOST, $db_prefix );
            unset( $generator );
        }
    }

    /**
     * Determines the database prefix for the tenant.
     *
     * @param string $tenant_id Tenant's UUID.
     *
     * @return null|string Database prefix or null if not the main site.
     */
    private function get_db_prefix( string $tenant_id ): ?string
    {
        if ( \defined( 'LANDLORD_UUID' ) && LANDLORD_UUID === $tenant_id ) {
            return env( 'LANDLORD_DB_PREFIX' );
        }

        return null;
    }
}
