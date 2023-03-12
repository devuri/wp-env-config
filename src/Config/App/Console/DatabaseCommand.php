<?php

namespace DevUri\Config\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DatabaseCommand extends Command
{
    protected static $defaultName = 'db';

    private $host;
    private $username;
    private $password;

    public function __construct()
    {
        parent::__construct();
        $this->host     = 'localhost';
        $this->username = 'user_' . self::generate_password( 6 );
        $this->password = self::generate_password( 12 );
    }

    protected function configure(): void
    {
        $this->setDescription( 'Perform a database operation.' )
            ->addArgument( 'operation', InputArgument::REQUIRED, 'The operation to perform.' )
            ->addArgument( 'databaseName', InputArgument::REQUIRED, 'The name of the database.' );

        $this->addOption( 'password', 'p', InputArgument::OPTIONAL, 'The password for the database user.' );
        $this->addOption( 'drop', 'd', InputArgument::OPTIONAL, 'Drop the database.' );
        $this->addOption( 'ver', 'dv', InputArgument::OPTIONAL, 'Get the version of the MySQL server.' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $operation    = $input->getArgument( 'operation' );
        $databaseName = $input->getArgument( 'databaseName' );

        $options = '';
        if ( null !== $input->getOption( 'password' ) ) {
            $options .= sprintf( ' -p%s', $input->getOption( 'password' ) );
        }
        if ( null !== $input->getOption( 'drop' ) ) {
            $options .= ' -D';
        }
        if ( null !== $input->getOption( 'ver' ) ) {
            $options .= ' -V';
        }

        $command = sprintf( 'mysqladmin -h%s -u%s%s %s %s', $this->host, $this->username, $options, $operation, $databaseName );

        $process = new Process( [ 'mysqladmin', $command ] );
        $process->run();

        if ( ! $process->isSuccessful() ) {
            $output->writeln( sprintf( '<error>Error performing database operation: %s.</error>', $process->getErrorOutput() ) );

            return Command::FAILURE;
        }

        $output->writeln( '<info>Database operation performed successfully!</info>' );

        return Command::SUCCESS;
    }

    /**
     * Generate a random alphanumeric password of a specified length, starting with a letter.
     *
     * @param int $length The length of the password to generate.
     *
     * @return string The generated password.
     */
    private static function generate_password( int $length = 8 ): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password   = '';
        for ( $i = 0; $i < $length; $i++ ) {
            if ( 0 === $i ) {
                $password .= $characters[ rand( 0, 51 ) ];
				// First character must be a letter
            } else {
                $password .= $characters[ rand( 0, 61 ) ];
                // Any character
            }
        }

        return $password;
    }
}
