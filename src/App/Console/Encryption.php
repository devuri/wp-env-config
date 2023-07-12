<?php

namespace Urisoft\App\Console;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;

class Encryption
{
	protected $keyAscii;
    private $root_dir_path;
    private $filesystem;
    private $EncryptionKey;
    private $AsciiKeyfile;

	/**
     * Setup Encryption.
     *
     * @param string     $root_dir_path    The base app path, e.g., __DIR__.
     * @param Filesystem $filesystem       The filesystem instance.
     * @param string|null $secret_key_path  The path to the directory containing the secret key file.
     * @param string     $keyid            [optional] The filename identifier of the secret key file. Default is 'secret'.
     *
     * @throws InvalidArgumentException If the secret key file is not found.
     */
    public function __construct(string $root_dir_path, Filesystem $filesystem, ?string $secret_key_path = null, $keyid = 'secret')
    {
        $this->root_dir_path = $root_dir_path;
        $this->filesystem = $filesystem;

        if (!$secret_key_path && defined('WEBAPP_ENCRYPTION_KEY')) {
            $this->AsciiKeyfile = WEBAPP_ENCRYPTION_KEY;
        } else {
            $this->AsciiKeyfile = $secret_key_path . "/.$keyid.txt";
        }

        if (!file_exists($this->AsciiKeyfile)) {
            throw new InvalidArgumentException("File not found: $this->AsciiKeyfile");
        }
    }

	/**
     * Loads the encryption key from the specified path.
     *
     * This function reads the contents of the secret key file located at the given path
     * and retrieves the encryption key. The key is expected to be stored in ASCII format.
     *
     * @return Key|null The loaded encryption key as an instance of the Key class, or null if the key could not be loaded.
     */
    public function load_encryption_key(): ?Key
    {
        $this->keyAscii = file_get_contents($this->AsciiKeyfile);
        $this->EncryptionKey = Key::loadFromAsciiSafeString($this->keyAscii);

        return $this->EncryptionKey;
    }

	/**
     * Encrypts the contents of the .env file.
     *
     * The encrypted contents are saved in a file named '.env.encrypted'.
     *
     * @throws Exception If there is an error encrypting or writing the encrypted contents.
     */
    public function encrypt_envfile( $file = '/.env' )
    {
        $this->load_encryption_key();

        $contents = file_get_contents($this->root_dir_path . $file );

        if ($contents === false) {
            throw new Exception("Failed to read the .env file");
        }

        $encryptedContents = Crypto::encrypt($contents, $this->EncryptionKey);

        $this->filesystem->dumpFile( $this->root_dir_path . '/.env.encrypted', $encryptedContents);
    }

	/**
     * Encrypts the given data.
     *
     * @param mixed $data The data to encrypt.
     *
     * @return Crypto The encrypted data.
     */
    public function encrypt($data): string
    {
        $this->load_encryption_key();

        return Crypto::encrypt($data, $this->EncryptionKey);
    }

	/**
     * Decrypts the given ciphertext.
     *
     * @param string $ciphertext The ciphertext to decrypt.
     *
     * @return mixed|null The decrypted data, or null if decryption fails.
     */
    public function decrypt($ciphertext)
    {
        $this->load_encryption_key();

        try {
            $data = Crypto::decrypt($ciphertext, $this->EncryptionKey);
            return $data;
        } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {
            error_log("decrypt error: wrong key was loaded, or the ciphertext has changed");
            return null;
        }
    }
}
