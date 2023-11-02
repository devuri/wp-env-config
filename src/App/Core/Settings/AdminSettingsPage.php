<?php

namespace Urisoft\App\Core\Settings;

/**
 * Custom Admin Page class for WordPress.
 */
class AdminSettingsPage
{
    /**
     * @var string The name of the setting.
     */
    private $settings_page;

    /**
     * @var string The content of the settings page.
     */
    private $content_callback;

    /**
     * @var bool Whether the page should be a submenu.
     */
    private $is_submenu;

    /**
     * AdminSettingsPage constructor.
     *
     * @param string   $settings_page         The name of the setting.
     * @param callable $page_content_callback The content callback of the settings page.
     * @param bool     $is_submenu            Whether the page should be a submenu.
     */
    public function __construct( string $settings_page, $page_content_callback = null, bool $is_submenu = true )
    {
        $this->settings_page    = $settings_page;
        $this->content_callback = $page_content_callback ?? 'no_callback_defined';
        $this->is_submenu       = $is_submenu;

        add_action( 'admin_menu', [ $this, 'register_settings_page' ] );
    }

    /**
     * Registers the custom settings page in the WordPress admin menu.
     */
    public function register_settings_page(): void
    {
        if ( $this->is_submenu ) {
            add_submenu_page(
                'options-general.php',
                $this->settings_page . ' admin',
                $this->settings_page,
                'manage_options',
                sanitize_title( $this->settings_page ),
                $this->content_callback
            );

            return;
        }

        add_options_page(
            $this->settings_page . ' admin',
            $this->settings_page,
            'manage_options',
            sanitize_title( $this->settings_page ),
            $this->content_callback
        );
    }
}
