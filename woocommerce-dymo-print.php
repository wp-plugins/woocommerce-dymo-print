<?php
/*
Plugin Name: WooCommerce DYMO Print
Plugin URI: https://wpfortune.com/shop/plugins/woocommerce-dymo-print/
Description: This plugin provides shipping labels for your DYMO label printer from the backend. - Free version
Version: 1.2.5
Author: bpluijms, wpfortune
Author URI: https://wpfortune.com/
*/
/*  Copyright 2012  WP Fortune  (email : info@wpfortune.com)

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

if (in_array('woocommerce/woocommerce.php',get_option('active_plugins'))) {
  load_plugin_textdomain('woocommerce-dymo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
  require_once('admin/dymo-settings.php');
  add_action('manage_shop_order_posts_custom_column', 'woocommerce_dymo_alter_order_actions', 3);
  add_action('admin_init', 'woocommerce_dymo_window');
  add_action('admin_menu', 'woocommerce_dymo_admin_menu');
  add_action('add_meta_boxes', 'woocommerce_dymo_add_box');
  add_action('admin_enqueue_scripts', 'woocommerce_dymo_scripts');
} else {
/* if WooCommerce is not active show admin message */
add_action('admin_notices', 'showDymoAdminMessages');   
}
/**
* Added help links to plugin page
* @Since 1.1.1.
*/
function dymo_plugin_links($links) { 
  $settings_link = '<a href="admin.php?page=woocommerce_dymo">Settings</a>'; 
  $premium_link = '<a href="https://wpfortune.com/shop/plugins/woocommerce-dymo-print/" title="Buy Pro" target=_blank>Buy Pro</a>'; 
  array_unshift($links, $settings_link,$premium_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'dymo_plugin_links' );
?>