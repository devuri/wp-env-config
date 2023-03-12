<?php

namespace DevUri\Config\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Console extends Command
{
    protected $root_dir_path;

    public function __construct( $root_dir_path )
    {
        parent::__construct();

        $this->root_dir_path = $root_dir_path;
    }

    protected function configure(): void
    {
        $this->setDescription( ' Description.' )
            ->addArgument( '_task', InputArgument::OPTIONAL, 'The input.', false );
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        return Command::SUCCESS;
    }

    /**
     * Generate a random alphanumeric password of a specified length, starting with a letter.
     *
     * @param int $length The length of the password to generate.
     *
     * @return string The generated password.
     */
    protected static function generate_password( int $length = 8 ): string
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
