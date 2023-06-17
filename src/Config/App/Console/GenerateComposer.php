<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class GenerateComposer extends Command
{
    protected static $defaultName = 'generate:composer';

    protected function configure()
    {
        $this->setDescription('Generate composer.json file and run composer install');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
	{
        file_put_contents('composer.json', static::composer_file() );

        $output->writeln('composer.json file generated.');

        // Run composer install
        $process = new Process(['composer', 'install']);
        $process->setTty(true);
        $process->run();

        if ($process->isSuccessful()) {
            $output->writeln('Composer install completed successfully.');
        } else {
            $output->writeln('Composer install failed.');
        }
    }

	private static function composer_file(): string
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
                    "public/templates/{$name}/":[
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
