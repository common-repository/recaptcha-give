<?php
/**
 * ReCAPTCHA class
 *
 * @package reCAPTCHAGive
 */

namespace ReCAPTCHA_Give\Modules;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * GiveWP reCAPTCHA
 */
class ReCAPTCHA {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'recaptchagive_setting_sections', array( $this, 'sections' ) );
		add_filter( 'recaptchagive_settings', array( $this, 'settings' ) );

		// Add reCAPTCHA script.
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 10 );

		// Adds reCAPTCHA to the donation forms.
		add_action( 'give_donation_form_before_submit', array( $this, 'add_recaptcha_field' ), 10 );

		// Processes the form.
		add_action( 'give_checkout_error_checks', array( $this, 'process_form' ), 10, 2 );
	}

	/**
	 * Processes donation form submissions
	 */
	public function process_form( $valid_data, $data ) {
		$options = get_option( 'recaptchagive' );

		$recaptcha_url        = 'https://www.google.com/recaptcha/api/siteverify';
		$recaptcha_secret_key = $options['v2_secret_key'];
		$recaptcha_response   = wp_remote_post( $recaptcha_url . '?secret=' . $recaptcha_secret_key . '&response=' . $data['g-recaptcha-response'] . '&remoteip=' . $_SERVER['REMOTE_ADDR'] );
		$recaptcha_data       = wp_remote_retrieve_body( $recaptcha_response );

		if ( ! isset( $recaptcha_data->success ) && ! $recaptcha_data->success == true ) {
			// User must have validated the reCAPTCHA to proceed with donation.
			if ( ! isset( $data['g-recaptcha-response'] ) || empty( $data['g-recaptcha-response'] ) ) {
				give_set_error( 'g-recaptcha-response', __( 'Please verify that you are not a robot.', 'recaptchagive' ) );
			}
		}

		return $valid_data;
	}

	public function add_recaptcha_field() {
		$options = get_option( 'recaptchagive' );
		?>
		<div style="margin-top: 1rem;" id="give-recaptcha-element" class="g-recaptcha" data-sitekey="<?php echo esc_html( $options['v2_site_key'] ); ?>"></div>
		<?php
	}

	/**
	 * Scripts
	 */
	public function scripts() {
		$options = get_option( 'recaptchagive' );

		if ( ! empty( $options['v2_site_key'] ) ) {
			$options['v2_site_key'] = trim( $options['v2_site_key'] );

			wp_register_script( 'recaptchagive-google', 'https://www.google.com/recaptcha/api.js', array( 'jquery' ), RECAPTCHA_GIVE_VERSION, true );
			// @todo - makes absolutely no sense, script key name appears to have to begin with 'give', otherwise the recaptcha won't work.
			wp_enqueue_script( 'giverecaptcha', plugin_dir_url( RECAPTCHA_GIVE ) . 'assets/js/recaptcha.js', array( 'recaptchagive-google' ), RECAPTCHA_GIVE_VERSION, true );
			wp_localize_script( 'giverecaptcha', 'RECAPTCHAGIVE', array( 'v2_site_key' => $options['v2_site_key'] ) );
		}
	}

	/**
	 * Sections
	 *
	 * @param array $sections Admin setting sections.
	 */
	public function sections( $sections ) {
		$sections['recaptcha'] = array(
			'title' => __( 'reCAPTCHA', 'recaptchagive' ),
		);

		return $sections;
	}

	/**
	 * Settings
	 *
	 * @param array $settings Admin settings.
	 */
	public function settings( $settings ) {
		$options = get_option( 'recaptchagive' );

		$settings['v2_site_key'] = array(
			'title'       => __( 'Site Key', 'recaptchagive' ),
			'desc'        => sprintf(
				wp_kses(
					__( 'Enter your site\'s reCAPTCHA site key.', 'recaptchagive' ),
					array()
				)
			),
			'section'     => 'recaptcha',
			'type'        => 'text',
			'field_class' => 'regular-text',
			'placeholder' => __( 'Enter your site\'s reCAPTCHA site key.', 'recaptchagive' ),
			'value'       => ! empty( $options['v2_site_key'] ) ? $options['v2_site_key'] : false,
		);

		$settings['v2_secret_key'] = array(
			'title'       => __( 'Secret Key', 'recaptchagive' ),
			'desc'        => sprintf(
				wp_kses(
					__( 'Enter your site\'s reCAPTCHA secret key.', 'recaptchagive' ),
					array()
				)
			),
			'section'     => 'recaptcha',
			'type'        => 'text',
			'field_class' => 'regular-text',
			'placeholder' => __( 'Enter your site\'s reCAPTCHA secret key.', 'recaptchagive' ),
			'value'       => ! empty( $options['v2_secret_key'] ) ? $options['v2_secret_key'] : false,
		);

		return $settings;
	}
}
