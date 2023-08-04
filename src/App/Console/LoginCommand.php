<?php

namespace Urisoft\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Urisoft\App\Console\Traits\Generate;
use Dotenv\Dotenv;

class LoginCommand extends Command
{
	use Generate;

    protected static $defaultName = 'wp:login';

    private $root_dir_path;
    private $filesystem;

    // wp:login -u admin
    public function __construct( string $root_dir_path, Filesystem $filesystem )
    {
        parent::__construct();
        $this->filesystem    = $filesystem;
        $this->root_dir_path = $root_dir_path;
		$this->load_dotenv( $this->root_dir_path );
    }

    protected function configure(): void
    {
        $this->setDescription( 'Generate an auto-login URL for a user.' )
            ->addOption( 'user', 'u', InputOption::VALUE_REQUIRED, 'The admin username.', 'admin' )
            ->setHelp( 'This command will generate an auto-login URL.' );
    }

    /**
     * @return int
     *
     * @psalm-return 0|1
     */
    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $io = new SymfonyStyle( $input, $output );

        $username   = $input->getOption( 'user' );

        $io->title( 'WordPress Auto-login Started...' );

        $autoLoginUrl = self::login( $username );

        if ( $autoLoginUrl ) {
            $io->newLine();
            $io->section( 'Auto-login URL:' );
            $io->writeln($autoLoginUrl);
            $io->newLine();

            return Command::SUCCESS;
        }

        $io->warning( 'Auto-login failed.' );

        return Command::FAILURE;
    }

    /**
     * WordPress Quick Login.
     *
     * @return string
     */
    protected static function login( string $username  ): string
    {
	    $secretKey = env('AUTO_LOGIN_SECRET_KEY');

		$service_data = [
			'timestamp' => time(),
			'username' => urlencode($username),
			'site_id' => self::random_id(),
		];

		$http_query = http_build_query($service_data, '', '&');

		$signature = hash_hmac('sha256', $http_query, $secretKey);

	    $autoLoginUrl = env( 'WP_HOME' ) . '/?auto_login=1&' . $http_query . '&signature=' . $signature;

	    return $autoLoginUrl;
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
}
