<?php

/**
 * Load common and WordPress based resources
 *
 * @since 1.0.0
 */
class Advanced_Ads_Genesis_Plugin {

	/**
	 *
	 * @var Advanced_Ads_Genesis_Plugin
	 */
	protected static $instance;

	/**
	 * Plugin options
	 *
	 * @var     array (if loaded)
	 */
	protected $options = false;

	/**
	 * Name of options in db
	 *
	 * @car     string
	 */
	public $options_slug;


	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'wp_plugins_loaded' ) );
	}

	/**
	 *
	 * @return Advanced_Ads_Genesis_Plugin
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load actions and filters
	 *
	 * @todo include more of the hooks used in public and admin class
	 */
	public function wp_plugins_loaded() {
		// Stop, if main plugin doesn’t exist.
		if ( ! class_exists( 'Advanced_Ads', false ) ) {
			return;
		}
	
		$this->load_plugin_textdomain();

		$this->options_slug = ADVADS_SLUG . '-slider';
	}
	
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.2
	 */
	public function load_plugin_textdomain() {
		// $locale = apply_filters('advanced-ads-plugin-locale', get_locale(), $domain);
		load_plugin_textdomain( 'advanced-ads-genesis', false, AAG_BASE_DIR . '/languages' );
	}

	/**
	 * Load advanced ads settings
	 */
	public function options() {
		// Don’t initiate if main plugin not loaded.
		if ( ! class_exists( 'Advanced_Ads' ) ) {
			return array();
		}

		return Advanced_Ads::get_instance()->options();
	}
}

