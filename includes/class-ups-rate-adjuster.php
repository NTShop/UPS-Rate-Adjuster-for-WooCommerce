<?php
/**
 * UPS Rate Adjuster
 *
 * Adjusts UPS shipping rates before any shipping taxes are applied
 *
 * @author      Mark Edwards
 * @version     1.0
 *
 *  @package UPS Rate Adjuster
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * UPS rate adjuster worker class
 */
class UPS_Rate_Adjuster {

	/**
	 *  Constructor, adds a filter to intercept and adjust UPS shipping rates
	 */
	public function __construct() {
		add_filter( 'woocommerce_shipping_method_add_rate_args', array( &$this, 'adjust_shipping_rate' ), 1, 2 );
	}

	/**
	 * Adjusts a UPS shipping rate and returns the rate.
	 * Rates are intercepted here before any shipping taxes are applied.
	 *
	 * @param array              $rate Array of rate parameters.
	 * @param WC_Shipping_Method $shipping_method Instance of WC_Shipping_Method.
	 *
	 * @return array
	 */
	public function adjust_shipping_rate( $rate, $shipping_method ) {

		if ( empty( $rate['id'] ) ) {
			return $rate;
		}

		// For speed first ensure that the rate is from UPS before proceeding.
		if ( false === strpos( strtolower( $rate['id'] ), 'ups' ) ) {
			return $rate;
		}

		// Check each product in the cart to determine if it has a rate adjustment defined.
		// If so then adjust the UPS rate.
		foreach ( WC()->cart->get_cart() as $item => $item_values ) {

			// Check product for rate adjustment.
			$adjustment = $item_values['data']->get_meta( '_ups_rate_adjustment', true, 'edit' );

			// If $adjustment is empty and this product is a variation then
			// check the parent product for an adjustment value.
			if ( empty( $adjustment ) && $item_values['data']->is_type( 'variation' ) ) {
				// Check parent variable product for an adjustment setting.
				$variable_product = wc_get_product( $item_values['data']->get_parent_id() );
				$adjustment       = $variable_product->get_meta( '_ups_rate_adjustment', true, 'edit' );
			}

			if ( empty( $adjustment ) ) {
				continue;
			}

			// Is the defined adjustment indicated as a percentage?
			if ( false !== strpos( $adjustment, '%' ) ) {
				// Convert adjustment to decimal.
				$adjustment = floatval( str_replace( '%', '', $adjustment ) );
				$adjustment = $adjustment / 100;
				$adjustment = $rate['cost'] * $adjustment;
			} else {
				$adjustment = floatval( $adjustment );
			}

			// Apply adjustment once per quantity of the item in the cart.
			for ( $i = 0; $i < $item_values['quantity']; $i++ ) {
				if ( ( $rate['cost'] + $adjustment ) < 0 ) {
					$rate['cost'] = 0;
				} else {
					$rate['cost'] += $adjustment;
				}
			}
		}

		return $rate;
	}
}

new UPS_Rate_Adjuster();
