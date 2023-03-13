<?php

namespace DevUri\Config\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command
{
    protected static $defaultName = 'config';

    private $root_dir_path;

    public function __construct( string $root_dir_path, ?object $app = null )
    {
        parent::__construct();
        $this->root_dir_path = $root_dir_path;
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
			dump( self::get_env( $_ENV ) );
        }
        // $output->writeln( "<comment>$config_task is not a valid config task</comment>" );

        return Command::SUCCESS;
    }

	/**
	 * Filter config output.
	 *
	 * @param  string  $env_values
	 * @return mixed
	 */
	protected static function get_env( array $config ): array
	{
		$env_vars = [];
		foreach ($config as $key => $value) {
		    if ( in_array( $key, self::env_secret() )) {
		        $env_vars[$key] = hash('sha256', $value);
		    } else {
		    	$env_vars[$key] = $value;
		    }
		}

		return $env_vars;
	}

	private function env_secret( array $secrets = [] ): array
	{
		return array_merge(
			$secrets,
			array('DB_USER', 'DB_PASSWORD', 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT')
		);
	}
}
