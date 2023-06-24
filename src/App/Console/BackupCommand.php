<?php

namespace Urisoft\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use ZipArchive;

class BackupCommand extends Command
{
    protected static $defaultName = 'backup';

    private $root_dir_path;
    private $filesystem;

    /**
     * BackupCommand constructor.
     *
     * php bin/console backup >/dev/null 2>&1 &
     *
     * @param string     $root_dir_path
     * @param Filesystem $filesystem
     */
    public function __construct( string $root_dir_path, Filesystem $filesystem )
    {
        $this->filesystem    = $filesystem;
        $this->root_dir_path = $root_dir_path;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription( 'Backup the WordPress web application' );
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $backup_directory = $this->root_dir_path . '/storage/.backups';

        // Create backup directory if it doesn't exist
        if ( ! $this->filesystem->exists( $backup_directory ) ) {
            $this->filesystem->mkdir( $backup_directory );
        }

        // Create a timestamped backup file
        $backup_file = $backup_directory . '/backup_' . gmdate( 'YmdHis' ) . '.zip';

        // Create a ZIP archive of the site directory
        $zip = new ZipArchive();
        $zip->open( $backup_file, ZipArchive::CREATE | ZipArchive::OVERWRITE );
        $this->add_directory_zip( $this->root_dir_path, '', $zip );
        $zip->close();

        $output->writeln( 'Backup created: ' . $backup_file );

        return Command::SUCCESS;
    }

    /**
     * Add directory and its files to a ZIP archive.
     *
     * @param string     $directory
     * @param string     $prefix
     * @param ZipArchive $zip
     */
    private function add_directory_zip( $directory, $prefix, $zip ): void
    {
        $handle = opendir( $directory );

        while ( false !== ( $file = readdir( $handle ) ) ) {
            if ( '.' !== $file && '..' !== $file ) {
                $path       = $directory . '/' . $file;
                $local_path = $prefix . '/' . $file;

                // Exclude directory
                if ( 'storage' === $file || 'vendor' === $file ) {
                    continue;
                }

                // Exclude directory
                if ( 'plugins' === $file || 'wp' === $file ) {
                    continue;
                }

                if ( is_dir( $path ) ) {
                    $this->add_directory_zip( $path, $local_path, $zip );
                } else {
                    $zip->addFile( $path, $local_path );
                }
            }
        }// end while

        closedir( $handle );
    }
}
