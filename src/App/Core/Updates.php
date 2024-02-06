<?php
/**
 * This file is part of the Slim White Label WordPress Plugin.
 *
 * (c) Uriel Wilson
 *
 * Please see the LICENSE file that was distributed with this source code
 * for full copyright and license information.
 */

namespace Urisoft\App\Core;

/**
 * Retrieve information about available updates
 * for WordPress core, themes, and plugins.
 */
class Updates
{
    protected $update_plugins;
    protected $update_themes;
    protected $update_wordpress;
    protected $translation_updates;

    /**
     * Constructor initializes the update data and available updates count.
     */
    public function __construct()
    {
        $this->update_plugins      = get_site_transient( 'update_plugins' );
        $this->update_themes       = get_site_transient( 'update_themes' );
        $this->update_wordpress    = get_site_transient( 'update_core' );
        $this->translation_updates = wp_get_translation_updates();
    }

    /**
     * Get the count of available updates.
     *
     * @return int The number of available updates.
     */
    public function get_available_updates(): ?int
    {
        $updates = [
            'core'    => $this->get_core_update(),
            'themes'  => $this->get_theme_update(),
            'plugins' => $this->get_plugin_update(),
        ];

        return (int) $updates['core'] + $updates['themes'] + $updates['plugins'];
    }

    /**
     * Get the count of available core updates.
     *
     * @return int The number of available core updates.
     */
    public function get_core_update(): ?int
    {
        if ( ! empty( $this->update_wordpress->updates )
            && ! \in_array( $this->update_wordpress->updates[0]->response, [ 'development', 'latest' ], true )
        ) {
            return 1;
        }

        return 0;
    }

    /**
     * Get the count of available theme updates.
     *
     * @return int The number of available theme updates.
     */
    public function get_theme_update(): int
    {
        if ( \is_object( $this->update_themes ) && property_exists( $this->update_themes, 'response' ) ) {
            return \count( $this->update_themes->response );
        }

        return 0;
    }

    /**
     * Get the count of available plugin updates.
     *
     * @return int The number of available plugin updates.
     */
    public function get_plugin_update(): int
    {
        if ( \is_object( $this->update_plugins ) && property_exists( $this->update_plugins, 'response' ) ) {
            return \count( $this->update_plugins->response );
        }

        return 0;
    }
}
