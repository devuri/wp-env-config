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
     * @param string $option_name The name of the option used to store the public key.
     * @param string $app_path    The path to the application directory.
     */
    public function __construct( $option_name, $app_path )
    {
        $this->option_name = $option_name;
        $this->app_path    = $app_path;
    }

    /**
     * Saves the public key as an option in the WordPress options table.
     *
     * @param string $uuid_filename  The UUID public key filename.
     * @param string $publickeys_dir The directory where the public keys are stored (default: 'publickeys').
     */
    public function save_public_key( $uuid_filename, $publickeys_dir = 'publickeys' ): void
    {
        $public_key_path = $this->app_path . '/' . $publickeys_dir . '/' . $uuid_filename;

        if ( file_exists( $public_key_path ) ) {
            $public_key = file_get_contents( $public_key_path );
            update_option( $this->option_name, base64_encode( $public_key ) );
        }
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
