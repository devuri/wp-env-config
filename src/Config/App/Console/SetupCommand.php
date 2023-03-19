<?php

namespace DevUri\Config\App\Console;

use DevUri\Config\App\Console\Traits\Env;
use DevUri\Config\App\Console\Traits\Generate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SetupCommand extends Command
{
    use Env;
    use Generate;

    protected static $defaultName = 'setup';

    private $filesystem;
    private $root_dir_path;

    public function __construct( string $root_dir_path, Filesystem $filesystem )
    {
        parent::__construct();
        $this->filesystem    = $filesystem;
        $this->root_dir_path = $root_dir_path;
        $this->files         = [
            'env'      => $root_dir_path . '/.env',
            'htaccess' => $root_dir_path . '/public/.htaccess',
            'robots'   => $root_dir_path . '/public/robots.txt',
        ];
    }

    protected function configure(): void
    {
        $this->setDescription( 'Search and replace domain in multiple files.' )
            ->addArgument( '_domain', InputArgument::REQUIRED, 'The replacement domain.' );
    }

    /**
     * @return int
     */
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $replacementDomain = $input->getArgument( '_domain' );

        if ( ! $this->filesystem->exists( $this->files['env'] ) ) {
            $output->writeln( '<comment>.env file does not exist. we will use .env-example.</comment>' );
            $this->filesystem->rename( $this->root_dir_path . '.env-example', $this->files['env'] );
        }

        $dbprefix = strtolower( self::rand_str( 8 ) . '_' );

        foreach ( $this->files as $key => $file ) {
            $pattern     = 'example.com';
            $replacement = $replacementDomain;

            if ( $this->filesystem->exists( $file ) ) {
                $backupFile = $file . '.bak';
                $this->filesystem->copy( $file, $backupFile );

                $contents = file_get_contents( $file );
                $contents = str_replace( $pattern, $replacement, $contents );

                if ( '.env' === basename( $file ) ) {
                    // (?!\w)  This ensures that lines like "DB_PREFIX=wp_yg4tzb" are not matched
                    $contents = preg_replace( '/^(DB_PREFIX=wp_(?!\w)).*$/m', "DB_PREFIX=wp_$dbprefix" . PHP_EOL, $contents );
                }

                $this->filesystem->dumpFile( $file, $contents );

                $output->writeln( "<info>Replaced: '$pattern' in $file with '$replacement'.</info>" );
            } else {
                $output->writeln( "Skipped: <comment>Could not find $file.</comment>" );
            }
        }// end foreach

        $salts = (object) $this->saltToArray();

        if ( $this->filesystem->exists( $this->files['env'] ) ) {
            $this->filesystem->appendToFile( $this->files['env'], "\n" . self::saltContent( $salts ) );
            $output->writeln( '<info>Salts added to env </info>' );
        } else {
            $this->filesystem->dumpFile( $this->files['env'], self::saltContent( $salts ) );
            $output->writeln( '<info>Salts saved to new env file.</info>' );
        }

        return Command::SUCCESS;
    }
}
