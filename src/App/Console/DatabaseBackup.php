<?php

namespace Urisoft\App\Console;

use Dotenv\Dotenv;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Urisoft\App\Console\Traits\Generate;

class DatabaseBackup extends Command
{
    use Generate;

    protected static $defaultName = 'db:backup';

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

        // load env
        $dotenv = Dotenv::createImmutable(
            $this->root_dir_path,
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

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription( 'Backup WordPress database.' )
            ->setHelp( 'This command allows you to create a backup of the WordPress database.' );
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $backup = [
            'db_name'     => env( 'DB_NAME' ),
            'db_user'     => env( 'DB_USER' ),
            'db_passowrd' => env( 'DB_PASSWORD' ),
            'db_host'     => env( 'DB_HOST' ),
            'db_prefix'   => env( 'DB_PREFIX' ),
            'directory'   => $this->root_dir_path . '/storage/.backups/db',
            'db_file'     => 'backup_' . self::rand_str() . mb_strtolower( gmdate( '_dmY_His' ) ) . '.sql',
        ];

        if ( ! file_exists( $backup['directory'] ) ) {
            mkdir( $backup['directory'], 0777, true );
        }

        // Build the command to execute
        $command = sprintf(
            'mysqldump -u %s -p%s %s > %s/%s',
            $backup['db_user'],
            $backup['db_passowrd'],
            $backup['db_name'],
            $backup['directory'],
            $backup['db_file'],
        );

        // Create a new process
        $process = Process::fromShellCommandline( $command );

        $process->setInput( null );

        // Run the process silently
        $process->run(
            function ( $type, $buffer ): void {
                // Do nothing with the output
            }
        );

        if ( $process->isSuccessful() ) {
            $output->writeln( sprintf( 'Backup created successfully: %s/%s', $backup['directory'], $backup['db_file'] ) );
        } else {
            $output->writeln( 'Error creating backup.' );
        }

        return $process->getExitCode();
    }
}
