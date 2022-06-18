<?php
/**
 * UPS Rate Adjuster Product Settings
 *
 * Display the shipping rate adjustment settings when editing a product
 *
 * @author      Mark Edwards
 * @version     1.0
 *
 * @package UPS Rate Adjuster
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UPS Rate Adjuster Settings class
 */
class UPS_Rate_Adjuster_Settings {

	/**
	 * Constructor, adds hooks to show and save rate adjustment settings when editing a product
	 */
	public function __construct() {
		add_action( 'woocommerce_product_options_shipping', array( &$this, 'add_product_settings' ), 5 );
		add_action( 'woocommerce_product_after_variable_attributes', array( &$this, 'add_variation_setting' ), 1, 3 );
		add_action( 'woocommerce_admin_process_product_object', array( &$this, 'save_product_meta' ), 5, 1 );
		add_action( 'woocommerce_save_product_variation', array( &$this, 'save_variation_product_meta' ), 5, 2 );
	}

	/**
	 * Adds rate adjustment setting to simple products.
	 */
	public function add_product_settings() {
		global $post;

		$product_object = wc_get_product( $post->ID );

		$adjustment_value = $product_object->get_meta( '_ups_rate_adjustment', true, 'edit' );

		woocommerce_wp_text_input(
			array(
				'id'          => '_ups_rate_adjustment',
				'value'       => $adjustment_value,
				'label'       => __( 'UPS rate adjustment', 'woocommerce-adjust-ups-rates' ),
				'placeholder' => '',
				'desc_tip'    => true,
				'description' => __( 'Adjust UPS rates up or down with a fixed value or percentage. Examples: +5, +5%, -12, -10%, etc.', 'woocommerce-adjust-ups-rates' ),
				'type'        => 'text',
			)
		);
	}

	/**
	 * Adds rate adjustment setting to variation products.
	 *
	 * @param int     $loop Loop counter points to the current variation being rendered.
	 * @param array   $variation_data Array of variation data @deprecated.
	 * @param WP_Post $variation Variation post object data.
	 */
	public function add_variation_setting( $loop, $variation_data, $variation ) {
		$product_object = wc_get_product( $variation->ID );

		$adjustment_value = $product_object->get_meta( '_ups_rate_adjustment', true, 'edit' );

		woocommerce_wp_text_input(
			array(
				'id'          => '_ups_rate_adjustment',
				'name'        => "_variation_ups_rate_adjustment[{$loop}]",
				'value'       => $adjustment_value,
				'label'       => __( 'UPS rate adjustment', 'woocommerce-adjust-ups-rates' ),
				'placeholder' => '',
				'desc_tip'    => true,
				'description' => __( 'Adjust UPS rates up or down with a fixed value or percentage. Examples: +5, +5%, -12, -10%, etc.', 'woocommerce-adjust-ups-rates' ),
				'type'        => 'text',
				'class'       => 'short',
				'style'       => 'width:7rem',
			)
		);
	}

	/**
	 * Saves the rate adjustment setting for simple products.
	 *
	 * @param WC_Product $product The product object.
	 */
	public function save_product_meta( $product ) {
		// WC already handled the nonce at this point.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! empty( $_POST['_ups_rate_adjustment'] ) ) {
			// WC already handled the nonce at this point.
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$adjustment = sanitize_text_field( wp_unslash( $_POST['_ups_rate_adjustment'] ) );
		} else {
			$adjustment = '';
		}

		$product->update_meta_data( '_ups_rate_adjustment', $adjustment );
	}

	/**
	 * Saves the rate adjustment setting for a variation product.
	 *
	 * @param int $variation_id the $post ID of the variation.
	 * @param int $loop_counter the loop counter of the variation being processed.
	 */
	public function save_variation_product_meta( $variation_id, $loop_counter ) {
		$variation = wc_get_product( $variation_id );

		// WC already handled the nonce at this point.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! empty( $_POST['_variation_ups_rate_adjustment'][ $loop_counter ] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing
			$adjustment = sanitize_text_field( wp_unslash( $_POST['_variation_ups_rate_adjustment'][ $loop_counter ] ) );
		} else {
			$adjustment = '';
		}

		$variation->update_meta_data( '_ups_rate_adjustment', $adjustment );
		$variation->save();
	}
}

new UPS_Rate_Adjuster_Settings();
