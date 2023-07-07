<?php

namespace Urisoft\App\Console\Traits;

use Devuri\UUIDGenerator\UUIDGenerator;
use Exception;
use Urisoft\PasswordGenerator;

trait Generate
{
    public static function uuid(): string
    {
        return ( new UUIDGenerator() )->generateUUID();
    }

    /**
     * Generate a random four-letter word.
     *
     * @return string The generated four-letter word.
     */
    public function four_letter_word(): string
    {
        $consonants = $this->get_consonants();
        $vowels     = $this->get_vowels();
        $word       = '';

        for ( $j = 0; $j < 4; $j++ ) {
            if ( 0 === $j % 2 ) {
                $word .= $consonants[ array_rand( $consonants ) ];
            } else {
                $word .= $vowels[ array_rand( $vowels ) ];
            }
        }

        return $word;
    }


    public function create_uuid_key_file()
    {
        $filename = self::uuid();
        $this->filesystem->copy(
            __DIR__ . '/sample-key.pub',
            $this->root_dir_path . '/publickeys/' . self::uuid() . '.pub'
        );

        return $filename;
    }

    /**
     * Generates a unique filename with optional prefix and hashing.
     *
     * @param string       $ext   The file extension.
     * @param null|string  $name  Optional. The prefix for the filename. Default is null.
     * @param false|string $hasit Optional. The hashing algorithm to use. Default is 'sha256'.
     *
     * @return string The unique filename generated.
     */
    public static function unique_filename( string $ext, ?string $name = null, $hasit = 'sha256' ): string
    {
        $prefix   = ( $name ) ? $name . '-' : null;
        $filename = $prefix . self::uuid();
        $datetime = mb_strtolower( gmdate( 'd-m-Y-' ) . time() );

        if ( $hasit ) {
            return $prefix . $datetime . hash( $hasit, $filename ) . $ext;
        }

        return $filename . '-' . $datetime . '-' . self::rand_str() . $ext;
    }

    /**
     * Generate a random alphanumeric alphanum_str of a specified length, starting with a letter.
     *
     * @param int $length The length of the alphanum_str to generate.
     *
     * @return string The generated string.
     */
    protected static function rand_str( int $length = 8 ): string
    {
        return PasswordGenerator::generatePassword( $length, false );
    }

    protected static function htpasswd( string $username, $password, $salted = null ): string
    {
        if ( ! $salted ) {
            $salt = self::rand_str( 16 );
        } else {
            $salt = $salted;
        }

        $salted_password = '$apr1$' . $salt . '$' . md5( $salt . $password . $salt );

        return $username . ':' . $salted_password . ':' . $salt;
    }

    /**
     * @param mixed $password
     *
     * @throws Exception
     */
    protected static function bcr_htpasswd( string $username, $password ): string
    {
        // Determine the number of hashing rounds (between 4 and 31)
        $cost = 10;

        // Generate a random salt using bcrypt's built-in function
        $salt = sprintf( '$2y$%02d$%s', $cost, substr( strtr( base64_encode( random_bytes( 16 ) ), '+', '.' ), 0, 22 ) );

        return $username . ':' . crypt( $password, $salt );
    }

    /**
     * Get the consonants array.
     *
     * @return string[] The consonants array.
     *
     * @psalm-return array{0: 'b', 1: 'c', 2: 'd', 3: 'f', 4: 'g', 5: 'h', 6: 'j', 7: 'k', 8: 'l', 9: 'm', 10: 'n', 11: 'p', 12: 'q', 13: 'r', 14: 's', 15: 't', 16: 'v', 17: 'w', 18: 'x', 19: 'y', 20: 'z'}
     */
    private function get_consonants(): array
    {
        return [ 'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z' ];
    }

    /**
     * Get the vowels array.
     *
     * @return string[] The vowels array.
     *
     * @psalm-return array{0: 'a', 1: 'e', 2: 'i', 3: 'o', 4: 'u'}
     */
    private function get_vowels(): array
    {
        return [ 'a', 'e', 'i', 'o', 'u' ];
    }
}
