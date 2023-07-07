<?php

namespace Urisoft\App\Console;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Urisoft\App\Console\Traits\Env;

class ServeCommand extends Command
{
    use Env;

    protected static $defaultName = 'serve';

    private $root_dir_path;
    private $filesystem;
    private $app_env;

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

        $this->app_env = self::get_env_array( $this->files['env'] );
    }

    protected function configure(): void
    {
        $this->setDescription( 'Start the built-in PHP web server.' )
            ->addOption( 'port', 'p', InputOption::VALUE_REQUIRED, 'The port number to use.', 8000 )
            ->addOption( 'host', null, InputOption::VALUE_REQUIRED, 'The host name to use.', 'localhost' )
            ->addOption( 'docroot', null, InputOption::VALUE_REQUIRED, 'The document root to use.', 'public' )
            ->addOption( 'ini', null, InputOption::VALUE_REQUIRED, 'The path to the php.ini file to use.', 'public/.user.ini' );
    }

    /**
     * @return int
     *
     * @psalm-return 0|1
     */
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $port    = $input->getOption( 'port' );
        $host    = $input->getOption( 'host' );
        $docroot = $input->getOption( 'docroot' );
        $ini     = $input->getOption( 'ini' );

        if ( ! $this->filesystem->exists( $this->files['env'] ) ) {
            $output->writeln( '<comment>.env file does not exist. we will use .env-example.</comment>' );
            $this->filesystem->rename( $this->root_dir_path . '/.env-example', $this->files['env'] );
        }

        if ( ! is_numeric( $port ) || $port < 1024 || $port > 65535 ) {
            throw new InvalidArgumentException( sprintf( 'Invalid port number: %s', $port ) );
        }

        $env_home_url = explode( ':', $this->app_env['WP_HOME'] );
        $env_port     = $env_home_url[2] ?? 0;;

        if ( ! $this->is_valid_env_port(  (int) $port, (int) $env_port ) ) ) {
            $output->writeln( PHP_EOL . "<comment>Server port:$port did not match .env file port:$env_port</comment>" . PHP_EOL );
        }

        if ( ! is_dir( $docroot ) ) {
            throw new InvalidArgumentException( sprintf( 'Document root not found: %s', $docroot ) );
        }

        if ( ! is_file( $ini ) ) {
            throw new InvalidArgumentException( sprintf( 'php.ini file not found: %s', $ini ) );
        }

        $output->writeln( sprintf( '<info>Starting PHP web server on http://%s:%d</info>', $host, $port ) );

        $process = new Process( [ 'php', '-S', sprintf( '%s:%d', $host, $port ), '-t', $docroot, '-c', $ini ] );
        $process->setTimeout( null );
        $process->setIdleTimeout( null );

        try {
            $process->start();

            foreach ( $process as $type => $data ) {
                if ( Process::OUT === $type ) {
                    $output->write( $data );
                }
            }

            if ( ! $process->isSuccessful() ) {
                throw new ProcessFailedException( $process );
            }
        } catch ( ProcessFailedException $e ) {
            $output->writeln( sprintf( '<error>The server could not be started: %s</error>', $e->getMessage() ) );

            return 1;
        }

        return 0;
    }

    /**
     * @param numeric $port
     */
    protected function is_valid_env_port( $port, int $env_port ): bool
    {
        if ( $env_port === $port ) {
            return true;
        }

        return false;
    }
}
