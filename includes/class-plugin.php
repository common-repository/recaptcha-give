<?php
/**
 * Main plugin class
 *
 * @package reCAPTCHAGive
 */

namespace ReCAPTCHA_Give;

// Security Note: Blocks direct access to the plugin PHP files.
defined( 'ABSPATH' ) || die();

/**
 * Main plugin class
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	/**
	 * Plugin constructor
	 */
	private function __construct() {
		$this->register_autoloader();
		$this->init_modules();
	}

	/**
	 * Register autoloader
	 */
	private function register_autoloader() {
		require_once RECAPTCHA_GIVE_PATH . 'includes/class-autoloader.php';

		Autoloader::run();
	}

	/**
	 * Instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initializes modules
	 */
	public function init_modules() {
		new \ReCAPTCHA_Give\Modules\ReCAPTCHA();
		new \ReCAPTCHA_Give\Core\Admin\Admin();
	}

	/**
	 * Init
	 */
	public function init() {
		$this->init_components();
	}
}

Plugin::instance();
