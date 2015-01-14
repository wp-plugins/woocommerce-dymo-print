<?php 
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

delete_option('woocommerce_dymo_label');
delete_option('dymo_fields_submitted');
delete_option('woocommerce_dymo_company_name');
delete_option('woocommerce_dymo_company_extra');