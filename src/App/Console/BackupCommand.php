<?php

namespace Urisoft\App\Console;

use Dotenv\Dotenv;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Urisoft\App\Console\Traits\Generate;
use ZipArchive;

class BackupCommand extends Command
{
    use Generate;

    protected static $defaultName = 'backup';

    private $root_dir_path;
    private $filesystem;
    private $snapshot_dir;
    private $backup_file;
    private $backup_time;
    private $backup_dir;
    private $backup_zip;
    private $s3backup_dir;

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
        $this->load_dotenv( $this->root_dir_path );
        $this->snapshot_dir = $this->root_dir_path . '/.snapshot/' . gmdate( 'Y' ) . '/' . gmdate( 'm' );

        // Create backup directory if it doesn't exist.
        if ( ! $this->filesystem->exists( $this->snapshot_dir ) ) {
            $this->filesystem->mkdir( $this->snapshot_dir );
        }

        // backup directory.
        $this->backup_file = self::unique_filename( '.zip', 'snap' );
        $this->backup_time = time();
        $this->backup_dir  = $this->snapshot_dir . '/' . $this->backup_time;

        // determines if we include plugins (true||false).
        $this->backup_plugins = env( 'BACKUP_PLUGINS' );

		// usually the sitename.
        $this->s3backup_dir = env( 'S3_BACKUP_DIR', 'wpsnaps' );

        // create backup directory
        if ( ! $this->filesystem->exists( $this->backup_dir ) ) {
            $this->filesystem->mkdir( $this->backup_dir );
        }

        // zip filename
        $this->backup_zip = $this->backup_dir . '/' . $this->backup_file;

        parent::__construct();
    }

    /**
     * Saves an array to the 'snap.json' file using the Symfony Filesystem component.
     *
     * @param array $data The array to be saved.
     *
     * @return bool True on success, false on failure.
     */
    public function save_snap_info( array $data ): bool
    {
        try {
            $this->filesystem->dumpFile( $this->root_dir_path . '/snap.json', json_encode( $data ) );

            return true;
        } catch ( Exception $e ) {
            return false;
        }
    }

    protected function configure(): void
    {
        $this->setDescription( 'Backup the WordPress web application' );
    }

    /**
     * @return int
     *
     * @psalm-return 0|1
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        // backup db
        $dbbackup = $this->create_sql_dump();

        if ( 0 !== $dbbackup['code'] ) {
            return Command::FAILURE;
        }

        $this->save_snap_info(
            array_merge(
                $dbbackup,
                [
                    'snap'      => $this->backup_file,
                    'date'      => gmdate( 'd-m-Y' ),
                    'timestamp' => $this->backup_time,
                ]
            )
        );

        // Create a ZIP archive of the site directory
        $zip = new ZipArchive();
        $zip->open( $this->backup_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE );
        $this->add_directory_zip( $this->root_dir_path, '', $zip );
        $zip->close();

        // save snap info
        $this->filesystem->copy( $this->root_dir_path . '/snap.json', $this->backup_dir . '/snap.json' );

        // remove db directory.
        $this->filesystem->remove( $this->root_dir_path . '/.sqldb' );
        unlink( $this->root_dir_path . '/snap.json' );
        // $output->writeln( 'Backup snapshot created: ' . $this->backup_zip );

        // maybe upload to s3
        if ( env( 'ENABLE_S3_BACKUP' ) ) {
            $this->s3_upload_backup( $this->backup_zip, $this->s3backup_dir . '/' . $this->backup_file );
        }

        return Command::SUCCESS;
    }

    protected function s3_upload_backup( string $local_file, string $s3objectfilekey ): bool
    {
        $uploader = new S3Uploader(
            env( 'S3_BACKUP_KEY', '' ),
            env( 'S3_BACKUP_SECRET', '' ),
            env( 'S3_BACKUP_BUCKET', 'wp-env-s3snaps' ),
            env( 'S3_BACKUP_REGION', 'us-west-1' ),
            // Specify the region where your S3 bucket is located
        );

        return $uploader->uploadFile( $local_file, $s3objectfilekey );
    }

    /**
     * Load the $_ENV.
     *
     * @param string $root_dir_path
     *
     * @return void
     */
    private function load_dotenv( string $root_dir_path ): void
    {
        $dotenv = Dotenv::createImmutable(
            $root_dir_path,
            [
                'env',
                '.env',
                '.env.secure',
                '.env.prod',
                '.env.staging',
                '.env.dev',
                '.env.debug',
                '.env.local',
            ]
        );
        $dotenv->load();
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
                if ( 'wp' === $file || 'vendor' === $file ) {
                    continue;
                }

                // Exclude plugins directory
                if ( 'plugins' === $file && ! $this->backup_plugins ) {
                    continue;
                }

                // Exclude snapshots directory
                if ( '.snapshot' === $file ) {
                    continue;
                }

                if ( is_dir( $path ) ) {
                    $this->add_directory_zip( $path, $local_path, $zip );
                } else {
                    $zip->addFile( $path, $local_path );
                }
            }// end if
        }// end while

        closedir( $handle );
    }


    /**
     * @return (null|int|mixed|string)[]
     *
     * @psalm-return array{db_name: mixed, db_user: mixed, sqlfile: string, code: int|null}
     */
    private function create_sql_dump(): array
    {
        $sqldb = [
            'db_name'     => env( 'DB_NAME' ),
            'db_user'     => env( 'DB_USER' ),
            'db_passowrd' => env( 'DB_PASSWORD' ),
            'db_host'     => env( 'DB_HOST' ),
            'db_prefix'   => env( 'DB_PREFIX' ),
            'directory'   => $this->root_dir_path . '/.sqldb',
            'db_file'     => self::unique_filename( '.sql', env( 'DB_NAME' ) . '-db' ),
        ];

        if ( ! $this->filesystem->exists( $sqldb['directory'] ) ) {
            $this->filesystem->mkdir( $sqldb['directory'] );
        }

        // Create a new process
        $process = Process::fromShellCommandline(
            sprintf(
                'mysqldump -u %s -p%s %s > %s/%s',
                $sqldb['db_user'],
                $sqldb['db_passowrd'],
                $sqldb['db_name'],
                $sqldb['directory'],
                $sqldb['db_file'],
            )
        );

        $process->setInput( null );

        // Run the process silently
        $process->run(
            function ( $type, $buffer ): void {
                // Do nothing with the output
            }
        );

        return [
            'db_name' => $sqldb['db_name'],
            'db_user' => $sqldb['db_user'],
            'sqlfile' => $sqldb['db_file'],
            'code'    => $process->getExitCode(),
        ];
    }
}
