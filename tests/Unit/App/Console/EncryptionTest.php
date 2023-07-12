<?php

namespace Tests\Unit\App\Console;

use Urisoft\App\Console\Encryption;
use PHPUnit\Framework\TestCase;
use Defuse\Crypto\Crypto;
use Symfony\Component\Filesystem\Filesystem;

class EncryptionTest extends TestCase
{
    protected $encryption;

    protected function setUp(): void
    {
        $this->encryption = new Encryption(APP_TEST_PATH, new Filesystem());
    }

    public function testEncryptAndDecrypt()
    {
        $data = 'This is a test string';

        // Encrypt the data
        $encryptedData = $this->encryption->encrypt($data);

        // Ensure the encrypted data is not empty
        $this->assertNotEmpty($encryptedData);

        // Decrypt the encrypted data
        $decryptedData = $this->encryption->decrypt($encryptedData);

        // Ensure the decrypted data matches the original data
        $this->assertEquals($data, $decryptedData);
    }

    public function testEncryptEnvFile()
    {
        $this->encryption->encrypt_envfile('/.env.local');

        // Ensure the encrypted .env file exists
        $this->assertFileExists(APP_TEST_PATH . '/.env.encrypted');

        // Decrypt the encrypted .env file
        $decryptedEnvContents = Crypto::decrypt(
            file_get_contents(APP_TEST_PATH . '/.env.encrypted'),
            $this->encryption->load_encryption_key()
        );

		$envContents = file_get_contents(APP_TEST_PATH . '/.env.local');

        // Ensure the decrypted .env contents match the original .env contents
        $this->assertEquals($envContents, $decryptedEnvContents);

		// remove the test file.
		unlink( APP_TEST_PATH . '/.env.encrypted' );
    }
}
