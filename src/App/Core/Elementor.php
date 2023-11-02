<?php

namespace Urisoft\App\Core;

use Exception;
use InvalidArgumentException;

/**
 * Easier setup for Elementor Pro activation.
 *
 * for manual activation use (/wp-admin/admin.php?page=elementor-license&mode=manually)
 */
class Elementor
{
    public const PRODUCT_NAME = 'Elementor Pro';
    public const STORE_URL    = 'https://my.elementor.com/api/v1/licenses/';
    public const RENEW_URL    = 'https://go.elementor.com/renew/';

    // License Statuses
    public const STATUS_VALID    = 'valid';
    public const STATUS_INVALID  = 'invalid';
    public const STATUS_EXPIRED  = 'expired';
    public const STATUS_INACTIVE = 'site_inactive';
    public const STATUS_DISABLED = 'disabled';

    // Requests lock config.
    public const REQUEST_LOCK_TTL    = MINUTE_IN_SECONDS;
    public const REQUEST_LOCK_OPTION = '_elementor_pro_api_requests_lock';
    public const ACTIVATION_LOCK_ID  = '_elementor_pro_wpenv_activation_lock';

    // activate stuff needed.
    public const LICENSE_KEY_OPTION           = 'elementor_pro_license_key';
    public const LICENSE_DATA_OPTION          = '_elementor_pro_license_data';
    public const LICENSE_DATA_FALLBACK_OPTION = self::LICENSE_DATA_OPTION . '_fallback';

    private $api_version = null;
    private $license_key = null;

    /**
     * Initializes the Elementor pro class.
     *
     * @param string $license_key
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function __construct( string $license_key )
    {
        if ( ! \defined( 'ELEMENTOR_PRO_VERSION' ) ) {
            throw new InvalidArgumentException( 'ELEMENTOR_PRO_VERSION is not defined' );
        }

        if ( \defined( 'ELEMENTOR_PRO_VERSION' ) ) {
            $this->api_version = ELEMENTOR_PRO_VERSION;
        }

        $this->license_key = $license_key;
    }

    public function is_activation_locked(): bool
    {
        if ( get_transient( self::ACTIVATION_LOCK_ID ) ) {
            return true;
        }

        return false;
    }

    public function activate()
    {
        if ( $this->is_activation_locked() ) {
            return false;
        }

        $activated = $this->post_request( 'activate_license' );

        if ( self::STATUS_EXPIRED === $activated['license'] ) {
            error_log( 'Elementor pro activation Expired' );

            return false;
        }
        if ( self::STATUS_INACTIVE === $activated['license'] ) {
            error_log( 'Error: Elementor Pro license Mismatch' );

            return false;
        }
        if ( self::STATUS_INVALID === $activated['license'] ) {
            error_log( 'Error: Elementor Pro license Invalid' );

            return false;
        }
        if ( self::STATUS_DISABLED === $activated['license'] ) {
            error_log( 'Error: Elementor Pro license Disabled' );

            return false;
        }

        if ( $activated ) {
            error_log( 'Elementor Pro license Active' );
            update_option( self::LICENSE_KEY_OPTION, $this->license_key );
            update_option( self::LICENSE_DATA_OPTION, $activated );
            update_option( self::LICENSE_DATA_FALLBACK_OPTION, $activated );
            update_option( '_elementor_pro_installed_time', time() );
            self::set_transient( self::ACTIVATION_LOCK_ID, time(), DAY_IN_SECONDS );

            return true;
        }

        return false;
    }

    public function get_status()
    {
        $status = $this->post_request( 'activate_license' );

        return $status['license'] ?? null;
    }

    public function deactivate()
    {
        delete_option( self::LICENSE_KEY_OPTION );
        delete_option( self::LICENSE_DATA_OPTION );
        delete_option( self::LICENSE_DATA_FALLBACK_OPTION );
        delete_transient( self::ACTIVATION_LOCK_ID );

        return $this->post_request( 'deactivate_license' );
    }

    protected static function set_transient( string $transient_name, $value, int $expiration = 0 )
    {
        if ( empty( $expiration ) ) {
            $expiration = DAY_IN_SECONDS;
        }

        return set_transient( $transient_name, $value, $expiration );
    }

    private function post_request( string $edd_action = 'activate_license' )
    {
        if ( \is_null( $this->api_version ) ) {
            error_log( 'api_version is null' );

            return false;
        }

        $response = wp_remote_post(
            self::STORE_URL,
            [
                'timeout' => 40,
                'body'    => [
                    'edd_action'  => $edd_action,
                    'license'     => $this->license_key,
                    'api_version' => $this->api_version,
                    'item_name'   => self::PRODUCT_NAME,
                    'site_lang'   => get_bloginfo( 'language' ),
                    'url'         => home_url(),
                ],
            ]
        );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $response_code = (int) wp_remote_retrieve_response_code( $response );

        if ( 200 !== $response_code ) {
            return false;
        }

        return json_decode( wp_remote_retrieve_body( $response ), true );
    }

    private function license_data()
    {
        $license_data = get_option( self::LICENSE_KEY_OPTION );

        if ( ! $license_data ) {
            return [
                'license'          => 'http_error',
                'payment_id'       => '0',
                'license_limit'    => '0',
                'site_count'       => '0',
                'activations_left' => '0',
                'success'          => false,
            ];
        }

        return $license_data;
    }
}
