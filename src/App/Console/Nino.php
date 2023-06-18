<?php

namespace Urisoft\App\Console;

use Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Filesystem;
use Urisoft\App\Kernel;

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
    protected $nino;

    /**
     * New Application command.
     */
    public function __construct( string $root_dir, ?Kernel $app = null )
    {
        $this->nino     = new Application();
        $this->root_dir = $root_dir;
        $this->http_app = $app;

        // cli only.
        if ( PHP_SAPI !== 'cli' ) {
            exit( 'please run from command line only' );
        }
    }

    /**
     * @throws Exception
     */
    public function load(): void
    {
        $this->add_command( new ServeCommand( $this->root_dir, new Filesystem() ) );
        $this->add_command( new SetupCommand( $this->root_dir, new Filesystem() ) );
        $this->add_command( new InstallPackage() );
        $this->add_command( new GenerateComposer() );
        $this->add_command( new CertCommand() );
        $this->add_command( new MakeCommand( $this->root_dir, new Filesystem() ) );
        $this->add_command( new ConfigCommand( $this->root_dir, $this->http_app ) );

        $this->nino->run();
    }

    /**
     * Add new Application command.
     *
     * @param Command $command the command
     */
    protected function add_command( Command $command ): void
    {
        $this->nino->add( $command );
        $this->nino->setDefaultCommand( $command->getName() );
    }
}
