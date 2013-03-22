<?php
/**
 * All functions
 */
if ( ! class_exists( 'WOO_Check' ) )
	require_once 'class-check-woocommerce.php';

/**
 * WC Detection
 */
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		return WOO_Check::woocommerce_active_check();
	}
}