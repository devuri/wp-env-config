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
    /**
     * @var array Holds the update data retrieved from wp_get_update_data().
     */
    private $update_data;

    /**
     * @var int Holds the count of available updates.
     */
    private $available_updates;

    /**
     * Constructor initializes the update data and available updates count.
     */
    public function __construct() {
        // Initialize the update data when the class is constructed.
        $this->update_data = wp_get_update_data();
        $this->available_updates = count( $this->update_data );
    }

    /**
     * Get the count of available updates.
     *
     * @return int The number of available updates.
     */
    public function get_available_updates(): int
    {
        return (int) $this->available_updates;
    }

    /**
     * Get the count of available core updates.
     *
     * @return int The number of available core updates.
     */
    public function get_core_update(): int
    {
        return $this->get_update('core');
    }

    /**
     * Get the count of available theme updates.
     *
     * @return int The number of available theme updates.
     */
    public function get_theme_update(): int
    {
        return $this->get_update('themes');
    }

    /**
     * Get the count of available plugin updates.
     *
     * @return int The number of available plugin updates.
     */
    public function get_plugin_update(): int
    {
        return $this->get_update('plugins');
    }

    /**
     * Get the update count for a specific update type.
     *
     * @param string|null $update_type The type of update to retrieve.
     *
     * @return int The number of available updates for the specified type,
     *             or the entire update data array if $update_type is null.
     */
    protected function get_update(?string $update_type = null)
    {
        if ($update_type) {
            return (int) $this->update_data[$update_type];
        }

        return $this->update_data;
    }
}
