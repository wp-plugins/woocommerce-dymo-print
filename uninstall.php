<?php 
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

delete_option('woocommerce_dymo_label');
delete_option('dymo_fields_submitted');
delete_option('woocommerce_dymo_company_name');
delete_option('woocommerce_dymo_company_extra');
delete_option('woocommerce_geev_url');
delete_option('woocommerce_geev_dymo_license_key');