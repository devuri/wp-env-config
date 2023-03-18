<?php

namespace DevUri\Config\App\Console;

use DevUri\Config\App\Console\Traits\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command
{
    use Generator;

    protected static $defaultName = 'config';

    private $root_dir_path;
    private $http_app;

    public function __construct( string $root_dir_path, ?object $app = null )
    {
        parent::__construct();
        $this->root_dir_path = $root_dir_path;
        $this->http_app      = $app;
    }

    protected function configure(): void
    {
        $this->setDescription( 'Display a list of constants defined by Setup.' )
            ->addArgument( '_task', InputArgument::OPTIONAL, 'The task that nino needs to perform.', false );
    }

    /**
     * @return int
     */
    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        $config_task = $input->getArgument( '_task' );

        if ( false === $config_task ) {
            $output->writeln( "<info>Config Setup:$this->root_dir_path</info>" );
            dump( $this->get_env( $_ENV ) );
        }

        if ( 'uuid' === $config_task ) {
            $uuid = self::uuid();
            $output->writeln( "uuid: <info>$uuid</info>" . PHP_EOL );
        }

        // $output->writeln( "<comment>$config_task is not a valid config task</comment>" );

        return Command::SUCCESS;
    }

    /**
     * Filter config output.
     *
     * @param string[] $config
     *
     * @return string[]
     */
    protected function get_env( array $config ): array
    {
        $env_vars = [];
        foreach ( $config as $key => $value ) {
            if ( \in_array( $key, $this->env_secret(), true ) ) {
                $env_vars[ $key ] = hash( 'sha256', $value );
            } else {
                $env_vars[ $key ] = $value;
            }
        }

        return $env_vars;
    }

    /**
     * @return (mixed|string)[]
     *
     * @psalm-return array{0: 'DB_USER', 1: 'DB_PASSWORD', 2: 'AUTH_KEY', 3: 'SECURE_AUTH_KEY', 4: 'LOGGED_IN_KEY', 5: 'NONCE_KEY', 6: 'AUTH_SALT', 7: 'SECURE_AUTH_SALT', 8: 'LOGGED_IN_SALT', 9: 'NONCE_SALT'}
     */
    private function env_secret( array $secrets = [] ): array
    {
        return array_merge(
            $secrets,
            [ 'DB_USER', 'DB_PASSWORD', 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ]
        );
    }
}
