<?php
/**
 * UPS Rate Adjuster - loads files and handles language translation file
 *
 * @author      Mark Edwards
 * @version     1.0
 *
 * @package UPS_Rate_Adjuster
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Undocumented class
 */
class WC_Adjust_UPS_Rates {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ), 1 );
		add_action( 'admin_init', array( $this, 'admin_init' ), 10 );
	}

	/**
	 * Load files and set hooks.
	 */
	public function init() {
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		require_once dirname( __FILE__ ) . '/class-ups-rate-adjuster.php';
	}

	/**
	 * Load product settings file.
	 */
	public function admin_init() {
		require_once dirname( __FILE__ ) . '/class-ups-rate-adjuster-settings.php';
	}

	/**
	 * Load Localization files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
	 *
	 * Locales found in:
	 *  - WP_LANG_DIR/woocommerce/woocommerce-adjust-ups-rates--LOCALE.mo
	 *  - WP_CONTENT_DIR/plugins/woocommerce-adjust-ups-rates/languages/woocommerce-adjust-ups-rates--LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'woocommerce-adjust-ups-rates' );
		load_textdomain( 'woocommerce-adjust-ups-rates', WP_LANG_DIR . '/woocommerce/woocommerce-adjust-ups-rates-' . $locale . '.mo' );
		load_plugin_textdomain( 'woocommerce-adjust-ups-rates', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

new WC_Adjust_UPS_Rates();
