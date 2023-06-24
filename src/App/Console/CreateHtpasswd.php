<?php

namespace Urisoft\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Urisoft\App\Console\Traits\Generate;

class CreateHtpasswd extends Command
{
    use Generate;

    protected static $defaultName = 'make:htpass';

    private $root_dir_path;
    private $htpasswd_file;

    public function __construct( string $root_dir_path, Filesystem $filesystem )
    {
        parent::__construct();
        $this->filesystem    = $filesystem;
        $this->root_dir_path = $root_dir_path;
        $this->htpasswd_file = $root_dir_path . '/_htpass/.htpasswd';
    }

    protected function configure(): void
    {
        $this->setDescription( 'Create an .htpasswd file' )
            ->addOption( 'username', 'u', InputOption::VALUE_OPTIONAL, 'The username' )
            ->addOption( 'password', 'p', InputOption::VALUE_OPTIONAL, 'The password' );
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $io = new SymfonyStyle( $input, $output );

        $username = $input->getOption( 'username' );
        $password = $input->getOption( 'password' );

        try {
            $this->filesystem->mkdir(
                Path::normalize( $this->root_dir_path . '/_htpass' ),
            );
        } catch ( IOExceptionInterface $ex ) {
            $output->writeln( '<error>Failed creating directory at:</error> ' . $ex->getPath() );

            return Command::FAILURE;
        }

        if ( empty( $username ) ) {
            $username = $this->four_letter_word();
        }

        if ( empty( $password ) ) {
            $password = self::rand_str();
        }

        // Generate the htpasswd line
        $htpasswd = $username . ':' . password_hash( $password, PASSWORD_BCRYPT );

        // Write to the .htpasswd file
        if ( false !== file_put_contents( $this->htpasswd_file, $htpasswd . PHP_EOL ) ) {
            $io->success( '.htpasswd file created successfully.' );
            $io->writeln( 'Username: <error>' . $username . '</error>' );
            $io->writeln( 'Password: <error>' . $password . '</error>' );

            return Command::SUCCESS;
        }

        $io->error( 'Failed to create .htpasswd file.' );

        return Command::FAILURE;
    }
}
