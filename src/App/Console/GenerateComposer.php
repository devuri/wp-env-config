<?php

namespace Urisoft\App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class GenerateComposer extends Command
{
    protected static $defaultName = 'generate:composer';

    private $root_dir_path;
    private $filesystem;
    private $composer_file;

    public function __construct( string $root_dir_path, Filesystem $filesystem )
    {
        parent::__construct();
        $this->filesystem    = $filesystem;
        $this->root_dir_path = $root_dir_path;
        $this->composer_file = $root_dir_path . '/composer.json';
    }

    protected function configure(): void
    {
        $this->setDescription( 'Generate fresh copy of composer.json file and run composer install' );
    }

    protected function execute( InputInterface $input, OutputInterface $output ): int
    {
        if ( $this->filesystem->exists( $this->composer_file ) ) {
            $output->writeln( '<comment>Composer file already exist.</comment>' );

            return Command::SUCCESS;
        }

        file_put_contents( 'composer.json', $this->composer_json() );

        $output->writeln( 'composer.json file generated.' );

        // Run composer install
        $process = new Process( [ 'composer', 'install' ] );
        $process->setTty( true );
        $process->run();

        if ( $process->isSuccessful() ) {
            $output->writeln( 'Composer install completed successfully.' );

            return Command::SUCCESS;
        }
        $output->writeln( 'Composer install failed.' );

        return Command::FAILURE;
    }

    private function composer_json(): string
    {
        return '{
            "name":"devuri/wp-env-app",
            "type":"project",
            "license":"MIT",
            "description":"A base WordPress project to create web applications using environment variables.",
            "minimum-stability":"dev",
            "prefer-stable":true,
            "require":{
                "devuri/wp-env-config":"dev-master",
                "wpackagist-plugin/query-monitor":"*",
                "wpackagist-plugin/email-log":"*",
                "wpackagist-theme/hello-elementor":"*",
                "wpackagist-theme/twentytwentythree":"*"
            },
            "repositories":[
                {
                    "type":"composer",
                    "url":"https://wpackagist.org",
                    "only":[
                        "wpackagist-plugin/*",
                        "wpackagist-theme/*"
                    ]
                }
            ],
            "extra":{
                "wordpress-install-dir":"public/wp",
                "installer-paths":{
                    "public/mu-plugins/{$name}/":[
                        "type:wordpress-muplugin"
                    ],
                    "public/plugins/{$name}/":[
                        "type:wordpress-plugin"
                    ],
                    "public/app/themes/{$name}/":[
                        "type:wordpress-theme"
                    ]
                }
            },
            "config": {
                "allow-plugins": {
                    "johnpbloch/wordpress-core-installer": true,
                    "composer/installers": true
                }
            }
        }';
    }
}
