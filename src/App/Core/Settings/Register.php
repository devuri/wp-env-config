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
            'basicauth_options',
            $this->setting_name,
            [
                'sanitize_callback' => [ $this, 'sanitize_setting' ],
                'show_in_rest'      => true,
                'default'           => 0,
                'type'              => 'boolean',
            ]
        );

        add_settings_section(
            'basicauth_general_section',
            'Web Application Authentication Settings',
            [ $this, 'render_section' ],
            'general'
        );

        add_settings_field(
            $this->setting_name,
            'Basic Authentication Setting',
            [ $this, 'render_settings' ],
            'general',
            'basicauth_general_section',
            [
                'type'        => 'checkbox',
                'name'        => $this->setting_name,
                'label_for'   => $this->setting_name,
                'description' => 'When checked, Basic Authentication will be required for this environment. The default is for this to be active on dev and staging environments.',
                'tip'         => esc_attr( 'Use to Setup Basic Authentication.' ),
            ]
        );
    }

    /**
     * Sanitize the setting value.
     *
     * @param mixed $value The value to be sanitized.
     *
     * @return mixed The sanitized value.
     */
    public function sanitize_setting( $value )
    {
        $sanitized_value = ( 'on' === $value ) ? 1 : 0;

        return sanitize_text_field( $sanitized_value );
    }

    /**
     * Callback function for the general section.
     */
    public function render_section(): void
    {
        echo '<p>Basic Authentication settings.</p>';
    }

    /**
     * Callback function for the checkbox setting field.
     *
     * @param mixed $val
     */
    public function render_settings( $val ): void
    {
        $is_enabled = ( 1 === get_option( $this->setting_name ) ) ? 'on' : 'off';
        $checked    = checked( get_option( $this->setting_name ), 1, false );
        ?>
		<input type="checkbox" id="<?php echo esc_attr( $this->setting_name ); ?>" name="<?php echo esc_attr( $this->setting_name ); ?>" value="<?php echo esc_attr( $is_enabled ); ?>" <?php echo $checked; ?> />
		<span class="description"><?php echo esc_html( $val['description'] ); ?></span>
		<b class="wntip" data-title="<?php echo esc_attr( $val['tip'] ); ?>"> ? </b>
		<?php
    }
}
