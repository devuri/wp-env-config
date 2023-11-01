<?php

namespace Tests\Unit\App\Console;

use Defuse\Crypto\Crypto;
use PHPUnit\Framework\TestCase;
use Urisoft\Encryption;
use Urisoft\Filesystem;

/**
 * @internal
 *
 * @coversNothing
 */
class EncryptionTest extends TestCase
{
    protected $encryption;
    protected $secret_test_data;

    protected function setUp(): void
    {
        $this->encryption = new Encryption(APP_TEST_PATH, new Filesystem());
        $this->secret_test_data = 'This is our secret test string';
    }

    protected function tearDown(): void
    {
        $files = [
            APP_TEST_PATH . '/.env.encrypted',
            APP_TEST_PATH . '/.env.dencryptfile',
            APP_TEST_PATH . '/.env.encryptfile',
        ];

        foreach ( $files as $file ) {
            if ( file_exists( $file ) ) {
                unlink( $file );
            }
        }
    }

    public function test_encrypt_and_decrypt(): void
    {
        $encryptedData = $this->encryption->encrypt($this->secret_test_data, false );

        $this->assertNotEmpty($encryptedData);

        $decryptedData = $this->encryption->decrypt($encryptedData, false );

        $this->assertEquals($this->secret_test_data, $decryptedData);
    }

    public function test_encoded_encrypt_and_decrypt(): void
    {
        $encryptedData = $this->encryption->encrypt($this->secret_test_data);

        $this->assertNotEmpty($encryptedData);

        $decryptedData = $this->encryption->decrypt( $encryptedData );

        $this->assertEquals($this->secret_test_data, $decryptedData);
    }

    public function test_encrypt_env_file(): void
    {
        $this->encryption->encrypt_envfile('/.env.local');

        $this->assertFileExists(APP_TEST_PATH . '/.env.encrypted');

        $decryptedEnvContents = Crypto::decrypt(
            file_get_contents(APP_TEST_PATH . '/.env.encrypted'),
            $this->encryption->load_encryption_key()
        );

        $envContents = file_get_contents(APP_TEST_PATH . '/.env.local');

        $this->assertEquals($envContents, $decryptedEnvContents);
    }

    public function test_file_encryption(): void
    {
        $this->encryption->encrypt_file(
            APP_TEST_PATH . '/.env.local',
            APP_TEST_PATH . '/.env.encryptfile'
        );

        $this->assertFileExists(APP_TEST_PATH . '/.env.encryptfile');

        $this->encryption->decrypt_file(
            APP_TEST_PATH . '/.env.encryptfile',
            APP_TEST_PATH . '/.env.dencryptfile'
        );

        $this->assertFileExists(APP_TEST_PATH . '/.env.dencryptfile');

        $fileContents = file_get_contents(APP_TEST_PATH . '/.env.local');

        $decryptedfile = file_get_contents(APP_TEST_PATH . '/.env.dencryptfile');

        $this->assertEquals($fileContents, $decryptedfile);
    }

    public function test_encrypted_value(): void
    {
        $secret_data =  'this is my secret license data';

        $_ENV['MY_SUPER_SECRET_VALUE'] = $secret_data;

        // passing true will encrypt env() data.
        $encrypted_value = env('MY_SUPER_SECRET_VALUE', true );

        $this->assertNotEmpty( $encrypted_value );

        $decrypted = $this->encryption->decrypt( $encrypted_value );

        $this->assertEquals( $decrypted, $secret_data );
    }
}
