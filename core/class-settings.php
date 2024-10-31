<?php
/**
 * Settings class
 *
 * @package reCAPTCHAGive
 */

namespace ReCAPTCHA_Give\Core;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Settings
 */
class Settings {

	/**
	 * Settings
	 *
	 * @var Settings
	 */
	public static $settings = array();

	/**
	 * Sections
	 *
	 * @var Sections
	 */
	public static $sections = array();

	/**
	 * Returns the plugin setting sections
	 */
	public static function get_sections() {
		self::$sections['general'] = array(
			'title' => __( 'General Settings', 'recaptchagive' ),
		);

		return apply_filters( 'recaptchagive_setting_sections', self::$sections );
	}

	/**
	 * Returns the plugin settings.
	 *
	 * @param string $key Setting key to retrieve.
	 */
	public static function get_settings( $key = false ) {
		$options = get_option( 'recaptchagive' );

		self::$settings['share_data'] = array(
			'title'       => __( 'Usage Data Sharing', 'recaptchagive' ),
			'section'     => 'general',
			'type'        => 'checkbox',
			'options'     => array(
				'enabled' => sprintf(
					wp_kses(
						/* translators: %s: url */
						__( 'Join <a href="%1$s" target="_blank" rel="noreferrer noopener">Zero Spam\'s global community</a> &amp; report detections by opting in to share non-sensitive data. <a href="%2$s" target="_blank" rel="noreferrer noopener">Learn more</a>.', 'recaptchagive' ),
						array(
							'a'    => array(
								'target' => array(),
								'href'   => array(),
								'rel'    => array(),
							),
						)
					),
					esc_url( 'https://www.zerospam.org/?utm_source=givewp_recaptcha&utm_medium=settings_page&utm_campaign=data_sharing' ),
					esc_url( 'https://github.com/bmarshall511/wordpress-zero-spam/wiki/FAQ#what-data-is-shared-when-usage-data-sharing-is-enabled' )
				),
			),
			'value'       => ! empty( $options['share_data'] ) ? $options['share_data'] : false,
			'recommended' => 'enabled',
		);

		$settings = apply_filters( 'recaptchagive_settings', self::$settings );

		if ( $key ) {
			if ( ! empty( $settings[ $key ]['value'] ) ) {
				return $settings[ $key ]['value'];
			}

			return false;
		}

		return $settings;
	}
}
