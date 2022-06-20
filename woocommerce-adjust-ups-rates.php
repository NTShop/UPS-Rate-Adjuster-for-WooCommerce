<?php
/**
 * Plugin Name: WooCommerce UPS Rate Adjuster
 * Plugin URI: https://github.com/NTShop/UPS-Rate-Adjuster-for-WooCommerce
 * Description: Allows the UPS rates to be adjusted up or down on a per product basis. Requires IgniteWoo's UPS Shipping Pro extension.
 * Version: 1.0
 * Author: Mark Edwards
 * Author URI: https://github.com/NTShop/
 * Text Domain: woocommerce-adjust-ups-rates
 * Domain Path: languages/
 * License: GPLv3
 * WC requires at least: 4.0
 * WC tested up to: 6.5
 *
 * Copyright (c) 2022 - Mark Edwards - All Rights Reserved
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package UPS_Rate_Adjuster
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads main class file is WooCommerce is active on the site.
 *
 * @return void
 */
function ups_rate_adjuster_load_if_wc_active() {
	if ( ! class_exists( 'woocommerce' ) ) {
		return;
	}
	require_once dirname( __FILE__ ) . '/includes/class-wc-adjust-ups-rates.php';
}
add_action( 'plugins_loaded', 'ups_rate_adjuster_load_if_wc_active', 0 );
