<?php
/**
 * GiveWP - reCAPTCHA Integration
 *
 * @package    reCAPTCHAGive
 * @subpackage WordPress
 * @since      1.0.0
 * @author     Highfivery LLC
 * @copyright  2021 Highfivery LLC
 * @license    GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       GiveWP - reCAPTCHA Integration
 * Plugin URI:        https://www.highfivery.com/projects/recaptcha-give/
 * Description:       Quickly & easily integrate Google reCAPTCHA into your GiveWP donation forms to help prevent spam and fraudulent transactions.
 * Version:           1.0.2
 * Requires at least: 5.2
 * Requires PHP:      7.3
 * Author:            Highfivery LLC
 * Author URI:        https://www.highfivery.com/
 * Text Domain:       recaptchagive
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

// Define plugin constants.
define( 'RECAPTCHA_GIVE', __FILE__ );
define( 'RECAPTCHA_GIVE_PATH', plugin_dir_path( RECAPTCHA_GIVE ) );
define( 'RECAPTCHA_GIVE_PLUGIN_BASE', plugin_basename( RECAPTCHA_GIVE ) );
define( 'RECAPTCHA_GIVE_VERSION', '1.0.0' );

add_action( 'plugins_loaded', 'recaptchagive_load_plugin_textdomain' );

if ( ! version_compare( PHP_VERSION, '7.3', '>=' ) ) {
	add_action( 'admin_notices', 'recaptchagive_fail_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '5', '>=' ) ) {
	add_action( 'admin_notices', 'recaptchagive_fail_wp_version' );
} else {
	require_once RECAPTCHA_GIVE_PATH . 'includes/class-plugin.php';
}

/**
 * Load plugin textdomain
 */
function recaptchagive_load_plugin_textdomain() {
	load_plugin_textdomain( 'recaptchagive' );
}

/**
 * Admin notice for minimum PHP version
 */
function recaptchagive_fail_php_version() {
	/* translators: %s: PHP version */
	$message      = sprintf( esc_html__( 'reCAPTCHA for Give requires PHP version %s+, plugin is currently NOT RUNNING.', 'recaptchagive' ), '7.3' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}

/**
 * Admin notice for minimum WordPress version
 */
function recaptchagive_fail_wp_version() {
	/* translators: %s: WordPress version */
	$message      = sprintf( esc_html__( 'reCAPTCHA for Give requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'recaptchagive' ), '5' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}
