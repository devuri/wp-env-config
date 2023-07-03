<?php

namespace Urisoft\App\Core\Settings;

/**
 * Custom settings class for WordPress.
 */
class Register
{
    private $setting_name;

    /**
     * Settings constructor.
     *
     * @param string $setting_name The name of the setting.
     */
    public function __construct( $setting_name )
    {
        $this->setting_name = $setting_name;
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    /**
     * Register the custom setting in WordPress.
     */
    public function register_settings(): void
    {
        register_setting(
            'general',
            $this->setting_name,
            [
                'sanitize_callback' => 'absint',
                'show_in_rest'      => true,
                'default'           => 0,
                'type'              => 'integer',
            ]
        );

        add_settings_field(
            $this->setting_name,
            'Basic Authentication Setting',
            [ $this, 'render_settings' ],
            'general',
            'default',
            [
                'type'        => 'checkbox',
                'name'        => $this->setting_name,
                'label_for'   => $this->setting_name,
                'description' => 'Enable Basic Authentication',
            ]
        );
    }

    /**
     * Callback function for the checkbox setting field.
     *
     * @param mixed $val
     */
    public function render_settings( $val ): void
    {
        $checked = checked( get_option( $this->setting_name ), 1, false );
        ?>
		<input type="checkbox" id="<?php echo esc_attr( $this->setting_name ); ?>" name="<?php echo esc_attr( $this->setting_name ); ?>" value="1" <?php echo $checked; ?> />
		<span class="description"><?php echo esc_html( $val['description'] ); ?></span>
		<p class="description"><?php echo esc_html( 'By checking this box, you ensure that users will need to provide authentication credentials to access the environment. By default, this feature is activated for both development (dev) and debug environments. You need to set BASIC_AUTH_USER and BASIC_AUTH_PASSWORD in your .env file' ); ?></span>
		<?php
    }
}
