<?php

namespace Urisoft\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Urisoft\App\Console\Traits\Generate;

class MakeCommand extends Command
{
    use Generate;

    protected static $defaultName = 'make:dbadmin';

    private $root_dir_path;
    private $filesystem;
    private $env_file;

    public function __construct( string $root_dir_path, Filesystem $filesystem )
    {
        parent::__construct();
        $this->filesystem    = $filesystem;
        $this->root_dir_path = $root_dir_path;
        $this->env_file      = $root_dir_path . '/.env';
    }

    protected function configure(): void
    {
        $this->setDescription( 'Create DB admin directory' )
            ->addOption( '_dir', '-d', InputOption::VALUE_REQUIRED, 'The database admin directory.', self::uuid() );
    }

    /**
     * Create the dbadmin dir.
     *
     * make the dir in public/"$dbadmin"
     *
     * @return int
     *
     * @psalm-return 0|1
     */
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $dbadmin = $input->getOption( '_dir' );

        // find
        $env_file_content = file_get_contents( $this->env_file );

        if ( preg_match( '/WP_DB_PHPADMIN/i', $env_file_content ) ) {
            $output->writeln( PHP_EOL . '<comment>Your dbadmin path is stored as constant in .env:</comment><info>WP_DB_PHPADMIN</info>' . PHP_EOL );

            return Command::SUCCESS;
        }

        try {
            $this->filesystem->mkdir(
                Path::normalize( $this->root_dir_path . "/public/d$dbadmin" ),
            );
        } catch ( IOExceptionInterface $ex ) {
            $output->writeln( '<error>Failed creating directory at:</error> ' . $ex->getPath() );

            return Command::FAILURE;
        }

        $this->filesystem->appendToFile( $this->env_file, PHP_EOL . "WP_DB_PHPADMIN='$dbadmin'" );

        $output->writeln( PHP_EOL . "<comment>Your dbadmin path is:</comment><info>/d$dbadmin</info>" . PHP_EOL );

        return Command::SUCCESS;
    }
}
