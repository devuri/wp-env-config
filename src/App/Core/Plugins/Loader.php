<?php

namespace Urisoft\App\Core\Plugins;

/**
 * Typically used in mu context to load plugins.
 */
class Loader
{
    /**
     * Array to hold the plugins that should be loaded.
     */
    private $plugins = [];

    public function __construct()
    {
        add_filter( 'option_active_plugins', [ $this, 'load_plugins' ] );
    }

    /**
     * Adds a plugin to the list of plugins that should be loaded.
     *
     * @param string $plugin_name_or_slug The plugin slug or path to ensure is loaded.
     */
    public function add_plugin( $plugin_name_or_slug ): void
    {
        if ( ! \in_array( $plugin_name_or_slug, $this->plugins, true ) ) {
            $this->plugins[] = $plugin_name_or_slug;
        }
    }

    /**
     * Filters the list of active plugins to include the plugins added via add_plugin.
     *
     * @param array $active_plugins Currently active plugin paths.
     *
     * @return array Modified list of active plugin paths.
     */
    private function load_plugins( $active_plugins )
    {
        foreach ( $this->plugins as $plugin ) {
            if ( ! \in_array( $plugin, $active_plugins, true ) ) {
                $active_plugins[] = $plugin;
            }
        }

        return $active_plugins;
    }
}
