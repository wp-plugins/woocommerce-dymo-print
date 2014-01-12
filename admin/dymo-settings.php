<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* WordPress Settings Page
*/
function woocommerce_dymo_page() {
// Check the user capabilities
	if ( !current_user_can( 'manage_woocommerce' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'woocommerce-dymo' ) );
	}
// Save the field values
	if ( isset( $_POST['dymo_fields_submitted'] ) && $_POST['dymo_fields_submitted'] == 'submitted' ) {
		foreach ( $_POST as $key => $value ) {
			if ( get_option( $key ) != $value ) {
				update_option( $key, $value );
			} else {
				add_option( $key, $value, '', 'no' );
			}
		}
	}
?>
<style>table td p {padding:0px !important;} table.dymocheck{width:100%;border:1px solid #ccc !important;text-align:center;margin:0 0 20px 0}.dymocheck tr th{border-bottom:1px solid #ccc !important;background:#ccc;}</style>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php _e( 'WooCommerce - Print DYMO shipping labels', 'woocommerce-dymo' ); ?></h2>
	<?php if ( isset( $_POST['dymo_fields_submitted'] ) && $_POST['dymo_fields_submitted'] == 'submitted' ) { ?>
	<div id="message" class="updated fade"><p><strong><?php _e( 'Your settings have been saved.', 'woocommerce-dymo' ); ?></strong></p></div>
	<?php } 
	if (( get_option( 'woocommerce_ship_to_billing_address_only' ) == 'yes' && get_option('woocommerce_require_shipping_address')=='no' )||  get_option('woocommerce_calc_shipping')=='no' ) {?>
	<div id="message" class="error fade"><p><strong><?php _e( 'Shipping is not active. You can only use WooCommerce DYMO Print FREE with shipping addresses.', 'woocommerce-dymo' ); ?></strong></p></div>
	<?php }	?>
	
	<div id="content">
		<form method="post" action="" id="dymo_settings">
			<input type="hidden" name="dymo_fields_submitted" value="submitted">
			<div id="poststuff">
				<div style="float:left; width:74%; padding-right:1%;">
					<div class="postbox">
						<h3><?php _e( 'General Settings', 'woocommerce-dymo' ); ?></h3>
						<div class="inside dymo-settings">
							<table class="form-table">
								<tr>
    								<th>
    									<label for="woocommerce_dymo_company_name"><b><?php _e( 'Company name:', 'woocommerce-dymo' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="woocommerce_dymo_company_name" class="regular-text" value="<?php echo stripslashes(get_option( 'woocommerce_dymo_company_name' )); ?>" /><br />
    									<span class="description"><?php
    										echo __( 'Your custom company name for the labels.', 'woocommerce-dymo' );
    										echo '<br /><strong>' . __( 'Note:', 'woocommerce-dymo' ) . '</strong> ';
    										_e( 'Leave blank to not to print a company name.', 'woocommerce-dymo' );
    									?></span>
    								</td>
    							</tr>
    							<tr>
    								<th>
    									<label for="woocommerce_dymo_company_extra"><b><?php _e( 'Company extra info:', 'woocommerce-dymo' ); ?></b></label>
    								</th>
    								<td>
    									<input type=text name="woocommerce_dymo_company_extra" cols="45" rows="3" class="regular-text" value="<?php echo stripslashes(get_option( 'woocommerce_dymo_company_extra' )); ?>"/><br />
    									<span class="description"><?php
    										echo __( 'Some extra info that is displayed on your label.', 'woocommerce-dymo' );
    										echo '<br /><strong>' . __( 'Note:', 'woocommerce-dymo' ) . '</strong> ';
    										_e( 'Leave blank to not to print the info.', 'woocommerce-dymo' );
    									?></span>
    								</td>
    							</tr>
								<tr>
									<td colspan=2>
										<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'woocommerce-dymo' ); ?>" /></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="postbox">
					<?php wp_register_script( 'woocommerce-dymo-print-js', plugins_url( '/woocommerce-dymo-print/js/woocommerce-dymo-print.js' ) );
	wp_enqueue_script( 'woocommerce-dymo-print-js', array('jquery') );
	wp_register_script( 'woocommerce-dymo-check-js', plugins_url( '/woocommerce-dymo-print/js/woocommerce-dymo-check.js' ) );
	wp_enqueue_script( 'woocommerce-dymo-check-js', array('jquery') );
	?>
						<h3><?php _e( 'Debug', 'woocommerce-dymo' ); ?></h3>
						<div class="inside dymo-check">
							
							<p><?php echo __( 'If you have any problems, please check below data.', 'woocommerce-dymo' );?></p>
							<div id="printersInfoContainer"></div>
<div class="printControls">
            <button class="button inputbutton" style="float:right" id="updateTableButton"><?php _e( 'Refresh', 'woocommerce-dymo' ); ?></button>
            <button class="button button-primary" id="printButton"><?php _e( 'Print printers information on', 'woocommerce-dymo' ); ?></button>
            <select id="printersSelect"></select>

    </div>
						</div>
					</div>
				</div>
				<div style="float:right; width:25%;">
					<div class="postbox">
						<h3><?php _e( 'Buy Pro!', 'woocommerce-dymo' ); ?></h3>
						<div class="inside dymo-preview">
							<p><?php echo __( 'Check out our ', 'woocommerce-dymo' ); ?> <a href="http://wordpress.geev.nl/product/woocommerce-dymo-print/">website</a> <?php _e('to find out more about WooCommerce DYMO Print Pro.', 'woocommerce-dymo' );?></p>
							<p><?php _e('For only &euro; 19,00 you will get a lot of features and access to our support section.', 'woocommerce-dymo' );?></p>
							<p><?php _e('A couple of features:', 'woocommerce-dymo' );?>
							<ul style="list-style:square;padding-left:20px;margin-top:-10px;"><li><?php _e('Print DYMO billing & shipping labels', 'woocommerce-dymo' );?></li><li><?php _e('Customize your own labels', 'woocommerce-dymo' );?></li><li><?php _e('Choose your label size', 'woocommerce-dymo' );?></li><li><?php _e('Print your company logo on your labels', 'woocommerce-dymo' );?></li><li><?php _e('Use a DYMO Labelwriter 450 Twin Turbo', 'woocommerce-dymo' );?></li><li><?php _e('Bulk printing', 'woocommerce-dymo' );?></li><li><strong><?php _e('New:', 'woocommerce-dymo' );?> <?php _e('Print Product list', 'woocommerce-dymo' );?></strong></li><li><strong><?php _e('New:', 'woocommerce-dymo' );?> <?php _e('Print Order number', 'woocommerce-dymo' );?></strong></li><li><strong><?php _e('New:', 'woocommerce-dymo' );?> <?php _e('Use a different label markup for shipping and invoice labels.', 'woocommerce-dymo' );?></strong></li></ul>
						</div>
					</div>
					<div class="postbox">
						<h3><?php _e( 'Show Your Love', 'woocommerce-dymo' ); ?></h3>
						<div class="inside dymo-preview">
							<p><?php echo sprintf(__( 'This plugin is developed by %s, a Dutch graphic design and webdevelopment company.', 'woocommerce-dymo' ),'Geev vormgeeving'); ?></p>
							<p><?php _e( 'If you are happy with this plugin please show your love by liking us on Facebook', 'woocommerce-dymo' ); ?></p>
							<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fgeevvormgeeving&amp;width=220&amp;height=62&amp;show_faces=false&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:62px;" allowTransparency="true"></iframe>
							<p><?php _e( 'Or', 'woocommerce-dymo' ); ?></p>
							<ul style="list-style:square;padding-left:20px;margin-top:-10px;">
								<li><a href="http://wordpress.org/extend/plugins/woocommerce-dymo-print/" target=_blank title="Woocommerce DYMO print"><?php _e( 'Rate the plugin 5&#9733; on WordPress.org', 'woocommerce-dymo' ); ?></a></li>
								<li><a href="http://wordpress.geev.nl/product/woocommerce-dymo-print/" target=_blank title="Woocommerce DYMO print"><?php _e( 'Blog about it & link to the plugin page', 'woocommerce-dymo' ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php } ?>