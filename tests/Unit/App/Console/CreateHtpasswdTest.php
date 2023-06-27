<?php

namespace Tests\Unit\App\Console;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Urisoft\App\Console\CreateHtpasswd;

/**
 * @internal
 *
 * @coversNothing
 */
class CreateHtpasswdTest extends TestCase
{
    private $rootDirPath;
    private $htpasswdFile;
    private $filesystem;

    protected function setUp(): void
    {
        $this->rootDirPath = APP_TEST_PATH;
        $this->htpasswdFile = $this->rootDirPath . '/_htpass/.htpasswd';
        $this->filesystem = new Filesystem();
    }

    protected function tearDown(): void
    {
        $this->filesystem->remove($this->rootDirPath . '/_htpass');
    }

    public function test_execute(): void
    {
        $application = new Application();
        $application->add(new CreateHtpasswd($this->rootDirPath, $this->filesystem));

        $command = $application->find('make:htpass');
        $commandTester = new CommandTester($command);

        $username = 'test_user';
        $password = 'test_password';

        // Simulate user input by providing options
        $commandTester->execute([
            'command' => $command->getName(),
            '--username' => $username,
            '--password' => $password,
        ]);

        // Assert command output
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('[OK] .htpasswd file created successfully.', $output);
        $this->assertStringContainsString('Username: ' . $username, $output);
        $this->assertStringContainsString('Password: ' . $password, $output);

        // Assert the existence of the created .htpasswd file
        $this->assertFileExists($this->htpasswdFile);
    }
}
