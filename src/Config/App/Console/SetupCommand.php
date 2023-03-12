<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SetupCommand extends Command
{
    protected static $defaultName = 'setup';

    private $filesystem;
    private $files_dir;

    public function __construct( Filesystem $filesystem, $files_dir )
    {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->files      = [
            'env'      => $files_dir . '/.env',
            'htaccess' => $files_dir . '/public/.htaccess',
            'robots'   => $files_dir . '/public/robots.txt',
        ];
    }

    protected function configure(): void
    {
        $this->setDescription( 'Search and replace domain in multiple files.' )
            ->addArgument( '_domain', InputArgument::REQUIRED, 'The replacement domain.' );
    }

    protected function execute( InputInterface $input, OutputInterface $output )
    {
        $replacementDomain = $input->getArgument( '_domain' );

        if ( ! $this->filesystem->exists( $this->files['env'] ) ) {
            $output->writeln( '<comment>.env file does not exist. we will use .env-example.</comment>' );
            $this->filesystem->rename( $this->files_dir . '.env-example', $this->files['env'] );
        }

        foreach ( $this->files as $key => $file ) {
            $pattern     = 'example.com';
            $replacement = $replacementDomain;

            if ( $this->filesystem->exists( $file ) ) {
                $backupFile = $file . '.bak';
                $this->filesystem->copy( $file, $backupFile );

                $content = file_get_contents( $file );
                $content = str_replace( $pattern, $replacement, $content );
                $this->filesystem->dumpFile( $file, $content );

                $output->writeln( "<info>Replaced all instances of '$pattern' in $file with '$replacement'.</info>" );
            } else {
                $output->writeln( "Could not find $file." );
            }
        }

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

    private function saltToArray(): array
    {
        $salts   = file_get_contents( 'https://api.wordpress.org/secret-key/1.1/salt/' );
        $string  = str_replace( [ "\r", "\n" ], '', $salts );
        $pattern = "/define\('([^']*)',\s*'([^']*)'\);/";
        $result  = [];
        if ( preg_match_all( $pattern, $string, $matches, PREG_SET_ORDER ) ) {
            foreach ( $matches as $match ) {
                $key            = $match[1];
                $val            = $match[2];
                $result[ $key ] = $val;
            }
        } else {
            // Handle invalid input
            $result = [ 'error' => 'Invalid input string' ];
        }

        return $result;
    }

    private static function saltContent( object $salt ): string
    {
        return <<<END

		AUTH_KEY='$salt->AUTH_KEY'
		SECURE_AUTH_KEY='$salt->SECURE_AUTH_KEY'
		LOGGED_IN_KEY='$salt->LOGGED_IN_KEY'
		NONCE_KEY='$salt->NONCE_KEY'
		AUTH_SALT='$salt->AUTH_SALT'
		SECURE_AUTH_SALT='$salt->SECURE_AUTH_SALT'
		LOGGED_IN_SALT='$salt->LOGGED_IN_SALT'
		NONCE_SALT='$salt->NONCE_SALT'

		END;
    }
}
