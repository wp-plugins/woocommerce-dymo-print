<?php
/*
Plugin Name: WooCommerce DYMO Print
Plugin URI: http://wordpress.geev.nl/product/woocommerce-dymo-print/
Description: This plugin provides shipping labels for your DYMO label printer from the backend. - Free version
Version: 1.1.1
Author: Bart Pluijms
Author URI: http://www.geev.nl/
*/
/*  Copyright 2012  Geev  (email : info@geev.nl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once( 'inc/dymo-funct.php' );

add_action('init', 'geev_update_check');
function geev_update_check()
{
    require_once ('inc/wp_autoupdate.php');
    $geev_plugin_current_version = '1.1.1';
    $geev_plugin_slug = plugin_basename(__FILE__);
	global $update;
	$update=new wp_auto_update ($geev_plugin_current_version, 'http://www.siteevaluator.nl/update/'.$geev_plugin_slug, $geev_plugin_slug,get_option( 'woocommerce_geev_dymo_license_key' ),get_option( 'woocommerce_geev_url'));
}

if (is_woocommerce_active()) {
  load_plugin_textdomain('woocommerce-dymo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
  require_once('admin/dymo-settings.php');
  add_action('manage_shop_order_posts_custom_column', 'woocommerce_dymo_alter_order_actions', 3);
  add_action('admin_init', 'woocommerce_dymo_window');
  add_action('admin_menu', 'woocommerce_dymo_admin_menu');
  add_action('add_meta_boxes', 'woocommerce_dymo_add_box');
  add_action('admin_enqueue_scripts', 'woocommerce_dymo_scripts');
  add_action('admin_print_styles', 'woocommerce_dymo_styles');
} else {
/* if WooCommerce is not active show admin message */
add_action('admin_notices', 'showAdminMessages');   
}
/**
* Added help links to plugin page
* @Since 1.1.1.
*/
function dymo_plugin_links($links) { 
  $settings_link = '<a href="admin.php?page=woocommerce_dymo">Settings</a>'; 
  $premium_link = '<a href="http://wordpress.geev.nl/product/woocommerce-dymo-print/" title="Buy Pro" target=_blank>Buy Pro</a>'; 
  array_unshift($links, $settings_link,$premium_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'dymo_plugin_links' );
?>