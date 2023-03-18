<?php

namespace DevUri\Config\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class SetupCommand extends Command
{
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

        $dbprefix = strtolower( self::generate_str( 8 ) . '_' );

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

    /**
     * @return string[]
     *
     * @psalm-return array<string, string>
     */
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

    /**
     * Generate a random alphanumeric alphanum_str of a specified length, starting with a letter.
     *
     * @param int $length The length of the alphanum_str to generate.
     *
     * @return string The generated string.
     */
    private static function generate_str( int $length = 8 ): string
    {
        $characters   = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $alphanum_str = '';
        for ( $i = 0; $i < $length; $i++ ) {
            if ( 0 === $i ) {
                $alphanum_str .= $characters[ rand( 0, 51 ) ];
				// First character must be a letter
            } else {
                $alphanum_str .= $characters[ rand( 0, 61 ) ];
                // Any character
            }
        }

        return $alphanum_str;
    }
}
