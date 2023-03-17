<?php

namespace DevUri\Config\App\Console;

use DevUri\Config\App\HttpKernel;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Nino.
 *
 * Italian, "Nino" is a diminutive of the name Giovanni, which means "God is gracious".
 * Spanish, "Niño" means "boy" or "child".
 * Georgian, "Nino" is a female name that means "granddaughter".
 * Japanese, "Nino" is a surname that means "two fields" or "two wilds".
 * Swahili, "Nino" means "we are" or "we exist".
 */
class Nino
{
    protected $root_dir;
    protected $http_app;

    /**
     * New Application command.
     */
    public function __construct( string $root_dir, ?HttpKernel $app = null )
    {
        $this->root_dir = $root_dir;
        $this->http_app = $app;

        // cli only.
        if ( PHP_SAPI !== 'cli' ) {
            exit( 'please run from command line only' );
        }
    }

    public function load(): void
    {
        $nino_cli = new Application();

        $setup      = new SetupCommand( $this->root_dir, new Filesystem() );
        $installer  = new InstallerCommand( $this->root_dir );
        $certbotssl = new CertCommand();
        $database   = new DatabaseCommand();
        $config     = new ConfigCommand( $this->root_dir, $this->http_app );
        $serve      = new ServeCommand( $this->root_dir, new Filesystem() );

        self::add_command( $serve, $nino_cli );
        // self::add_command( $database, $nino_cli );
        self::add_command( $setup, $nino_cli );
        // self::add_command( $installer, $nino_cli );
        self::add_command( $certbotssl, $nino_cli );
        self::add_command( $config, $nino_cli );

        $nino_cli->run();
    }

    /**
     * Add new Application command.
     *
     * @param Command     $command the command
     * @param Application $cli     the Application
     */
    protected static function add_command( Command $command, Application $cli ): void
    {
        $cli->add( $command );
        $cli->setDefaultCommand( $command->getName() );
    }
}
