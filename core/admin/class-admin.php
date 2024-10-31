<?php
/**
 * Admin class
 *
 * @package reCAPTCHAGive
 */

namespace ReCAPTCHA_Give\Core\Admin;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Admin
 */
class Admin {

	/**
	 * Admin constructor
	 */
	public function __construct() {
		new \ReCAPTCHA_Give\Core\Admin\Settings();

		add_filter( 'plugin_action_links_' . RECAPTCHA_GIVE_PLUGIN_BASE, array( $this, 'plugin_action_links' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	/**
	 * Scripts
	 */
	public function scripts( $hook_suffix ) {
		if (
			'settings_page_recaptchagive-settings' === $hook_suffix
		) {
			wp_enqueue_style(
				'recaptchagive-admin',
				plugin_dir_url( RECAPTCHA_GIVE ) . 'assets/css/admin.css',
				false,
				RECAPTCHA_GIVE_VERSION
			);

			wp_enqueue_script(
				'recaptchagive-admin',
				plugin_dir_url( RECAPTCHA_GIVE ) . 'assets/js/admin.js',
				array(),
				RECAPTCHA_GIVE_VERSION,
				true
			);
		}
	}

	/**
	 * Plugin action links.
	 *
	 * Adds action links to the plugin list table
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @param array $links An array of plugin action links.
	 */
	public function plugin_action_links( $links ) {
		$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'options-general.php?page=recaptchagive-settings' ), __( 'Settings', 'recaptchagive' ) );

		array_unshift( $links, $settings_link );

		return $links;
	}
}
