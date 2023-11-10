<?php
/**
 * This file is part of the WordPress project install.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace Urisoft\App\Core\Traits;

use Exception;
use Urisoft\App\Core\Elementor;

trait ActivateElementorTrait
{
    protected function auto_activate_elementor(): ?bool
    {
        // auto activate elementor.
        $auto_activation = env( 'ELEMENTOR_AUTO_ACTIVATION' );

        if ( ! $auto_activation || false === $auto_activation ) {
            return null;
        }

        if ( env( 'ELEMENTOR_PRO_LICENSE' ) === get_option( 'elementor_pro_license_key' ) ) {
            // if the key is present it may already be active.
            return null;
        }

        try {
            $elementor = new Elementor( env( 'ELEMENTOR_PRO_LICENSE' ) );
        } catch ( Exception $e ) {
            error_log( $e->getMessage() );

            return null;
        }

        if ( $elementor->is_activation_locked() ) {
            return null;
        }

        $license_status = $elementor->get_status();

        if ( ! 'valid' === $license_status ) {
            error_log( 'auto activation elementor pro license failed:' . (string) $license_status );

            return false;
        }

        // activate it.
        if ( $elementor->activate() ) {
            error_log( 'Elementor licence activated' );

            return true;
        }

        return false;
    }
}
