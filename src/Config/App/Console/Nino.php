<?php

namespace DevUri\Config\App\Console;

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

    /**
     * New Application command.
     */
    public function __construct( string $root_dir )
    {
        $this->root_dir = $root_dir;
    }

    public function load(): void
    {
        $app = new Application();

        $setup     = new SetupCommand( new Filesystem(), $this->root_dir );
        $installer = new InstallerCommand( $this->root_dir );
        $database  = new DatabaseCommand();
        $config    = new ConfigCommand( $this->root_dir );
        $serve     = new ServeCommand();

        self::add_command( $serve, $app );
        // self::add_command( $database, $app );
        self::add_command( $setup, $app );
        self::add_command( $installer, $app );
        self::add_command( $config, $app );

        $app->run();
    }

    /**
     * Add new Application command.
     *
     * @param Command     $command the command
     * @param Application $app     the Application
     */
    protected static function add_command( Command $command, Application $app ): void
    {
        $app->add( $command );
        $app->setDefaultCommand( $command->getName() );
    }
}
