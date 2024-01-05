<?php
/**
 * This file is part of the WordPress project install.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace Urisoft\App\Core;

/**
 * Represents a public key used for encryption or verification purposes.
 */
class PublicKey
{
    /**
     * The name of the option used to store the public key.
     *
     * @var string
     */
    private $option_name;

    /**
     * The path to the application directory.
     *
     * @var string
     */
    private $app_path;

    /**
     * PublicKey constructor.
     *
     * @param string $app_path    The path to the application directory.
     * @param string $option_name The name of the option used to store the public key.
     */
    public function __construct( $app_path, string $option_name = 'wp_env_pubkey' )
    {
        $this->app_path    = $app_path;
        $this->option_name = $option_name;
    }

    /**
     * Saves the public key as an option in the WordPress options table.
     *
     * @param string $key_filename   The public key filename.
     * @param string $publickeys_dir The directory where the public keys are stored (default: 'pubkey').
     */
    public function save_public_key( $key_filename, $publickeys_dir = 'pubkey' ): bool
    {
        $public_key_path = $this->app_path . '/' . $publickeys_dir . '/' . $key_filename;

        if ( file_exists( $public_key_path ) ) {
            $public_key = file_get_contents( $public_key_path );

            return update_option( $this->option_name, base64_encode( $public_key ) );
        }

        return false;
    }

    /**
     * Retrieves the stored public key from the WordPress options table.
     *
     * @return null|string The retrieved public key, or null if not found.
     */
    public function get_public_key()
    {
        $public_key = get_option( $this->option_name );

        if ( $public_key ) {
            return base64_decode( $public_key, true );
        }

        return null;
    }
}
