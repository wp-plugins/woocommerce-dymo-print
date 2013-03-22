<?php
/*
Plugin Name: WooCommerce DYMO Print
Plugin URI: http://www.geev.nl/
Description: This plugin provides shipping labels for your DYMO label printer from the backend. - Free version
Version: 1.0.5
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
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	require_once( 'inc/functions.php' );
}
require_once('inc/update.php');
if (is_woocommerce_active()) {
  load_plugin_textdomain('woocommerce-dymo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
  add_action('manage_shop_order_posts_custom_column', 'woocommerce_dymo_alter_order_actions', 3);
  add_action('admin_init', 'woocommerce_dymo_window');
  add_action('admin_menu', 'woocommerce_dymo_admin_menu');
  add_action('add_meta_boxes', 'woocommerce_dymo_add_box');
  add_action('admin_enqueue_scripts', 'woocommerce_dymo_scripts');
  add_action('admin_print_styles', 'woocommerce_dymo_styles');

/**
* Initialize settings
*/

/**
* Plugin specific scripts
*/
function woocommerce_dymo_scripts() {
    wp_register_script( 'woocommerce-dymo-js', plugins_url( '/js/woocommerce-dymo.js', __FILE__ ) );
    wp_register_script( 'woocommerce-dymo-validate', plugins_url( '/js/jquery.validate.min.js', __FILE__ ) );
	wp_enqueue_script( 'woocommerce-dymo-js', array('jquery') );
    wp_enqueue_script( 'woocommerce-dymo-validate', array('jquery') );
    wp_enqueue_script('common');
    wp_enqueue_script('wp-lists');
    wp_enqueue_script('postbox');
	//wp_enqueue_script( 'media-upload' );
	//wp_enqueue_script( 'thickbox' );
	}

/**
* Plugin specific styles
*/
function woocommerce_dymo_styles() {
	//wp_enqueue_style( 'thickbox' );
}

/**
* WordPress Administration Menu
*/
function woocommerce_dymo_admin_menu() {
	$page = add_submenu_page('woocommerce', __( 'DYMO Print', 'woocommerce-dymo' ), __( 'DYMO Print', 'woocommerce-dymo' ), 'manage_woocommerce', 'woocommerce_dymo', 'woocommerce_dymo_page' );
}
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
	<?php } ?>
	<p><?php _e( 'Change settings for DYMO shipping labels.', 'woocommerce-dymo' ); ?></p>
	<div id="content">
		<form method="post" action="" id="dymo_settings">
			<input type="hidden" name="dymo_fields_submitted" value="submitted">
			<div id="poststuff">
				<div style="float:left; width:74%; padding-right:1%;">
					<div class="postbox" style="display:none;">
						<h3><?php _e( 'Label markup', 'woocommerce-dymo' ); ?></h3>
						<div class="inside dymo-markup">
							<table class="form-table">
								<tr>
									<th>
    									<label for="woocommerce_dymo_label"><b><?php _e( 'Your Label XML:', 'woocommerce-dymo' ); ?></b></label>
    								</th>
    								<td>
    									<textarea name="woocommerce_dymo_label" cols="45" rows="3" class="regular-text" style="width:100%;height:200px;"><DieCutLabel Version="8.0" Units="twips">
	<PaperOrientation>Landscape</PaperOrientation>
	<Id>LargeAddress</Id>
	<PaperName>30321 Large Address</PaperName>
	<DrawCommands>
		<RoundRectangle X="0" Y="0" Width="2025" Height="5020" Rx="270" Ry="270" />
	</DrawCommands>
	<ObjectInfo>
		<TextObject>
			<Name>EXTRA</Name>
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />
			<LinkedObjectName></LinkedObjectName>
			<Rotation>Rotation0</Rotation>
			<IsMirrored>False</IsMirrored>
			<IsVariable>False</IsVariable>
			<HorizontalAlignment>Right</HorizontalAlignment>
			<VerticalAlignment>Top</VerticalAlignment>
			<TextFitMode>None</TextFitMode>
			<UseFullFontHeight>True</UseFullFontHeight>
			<Verticalized>False</Verticalized>
			<StyledText>
				<Element>
					<String> </String>
					<Attributes>
						<Font Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
					</Attributes>
				</Element>
			</StyledText>
		</TextObject>
		<Bounds X="2812" Y="1715" Width="1958" Height="225" />
	</ObjectInfo>
	<ObjectInfo>
		<AddressObject>
			<Name>ORDER</Name>
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />
			<LinkedObjectName></LinkedObjectName>
			<Rotation>Rotation0</Rotation>
			<IsMirrored>False</IsMirrored>
			<IsVariable>True</IsVariable>
			<HorizontalAlignment>Left</HorizontalAlignment>
			<VerticalAlignment>Top</VerticalAlignment>
			<TextFitMode>ShrinkToFit</TextFitMode>
			<UseFullFontHeight>True</UseFullFontHeight>
			<Verticalized>False</Verticalized>
			<StyledText>
				<Element>
					<String>Bedrijfsnaam
Contactnaam
Straat
Postcode + Plaats
Land</String>
					<Attributes>
						<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
					</Attributes>
				</Element>
			</StyledText>
			<ShowBarcodeFor9DigitZipOnly>False</ShowBarcodeFor9DigitZipOnly>
			<BarcodePosition>AboveAddress</BarcodePosition>
			<LineFonts>
				<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
				<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
				<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
				<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
				<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />
			</LineFonts>
		</AddressObject>
		<Bounds X="337" Y="165" Width="4455" Height="1220" />
	</ObjectInfo>
	<ObjectInfo>
		<TextObject>
			<Name>COMPANY</Name>
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />
			<LinkedObjectName></LinkedObjectName>
			<Rotation>Rotation0</Rotation>
			<IsMirrored>False</IsMirrored>
			<IsVariable>False</IsVariable>
			<HorizontalAlignment>Left</HorizontalAlignment>
			<VerticalAlignment>Top</VerticalAlignment>
			<TextFitMode>None</TextFitMode>
			<UseFullFontHeight>True</UseFullFontHeight>
			<Verticalized>False</Verticalized>
			<StyledText>
				<Element>
					<String> </String>
					<Attributes>
						<Font Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />
					</Attributes>
				</Element>
			</StyledText>
		</TextObject>
		<Bounds X="322" Y="1670" Width="3165" Height="270" />
	</ObjectInfo>
</DieCutLabel>							</textarea><br />
										<span class="description"><?php echo __( 'Copy Paste your complete label XML output <u>without</u> the first line:', 'woocommerce-dymo' ).'<pre>&lt;?xml version="1.0" encoding="utf-8"?&gt;</pre>';?></span>
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
    										echo __( 'Leave blank to not to print a company name.', 'woocommerce-dymo' );
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
    										echo __( 'Leave blank to not to print the info.', 'woocommerce-dymo' );
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
						<h3><?php _e( 'Let\'s get pro!', 'woocommerce-dymo' ); ?></h3>
						<div class="inside dymo-pro">
							<p><?php echo __( 'You can buy an license key from our website. When using WooCommerce DYMO Print Pro you are able to use more features. Check our website for more information.', 'woocommerce-dymo' );?></p>
							<table class="form-table">
								<tr>
    								<th>
    									<label for="woocommerce_geev_label"><b><?php _e( 'License Key', 'woocommerce-dymo' ); ?></b></label>
    								</th>
    								<td>
    									<input type="text" name="woocommerce_geev_license_key" class="regular-text" value="<?php echo stripslashes(get_option( 'woocommerce_geev_license_key' )); ?>" /><br />
    									<span class="description"><?php echo __( 'Copy Paste your license key to activate WooCommerce DYMO Print Pro. After activation you are able to download the PRO version.');?></span>
    								</td>
    							</tr>
								<tr>
									<td colspan=2>
										<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save License Key', 'woocommerce-dymo' ); ?>" /></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="postbox">
					<?php wp_register_script( 'woocommerce-dymo-print-js', plugins_url( '/js/woocommerce-dymo-print.js', __FILE__ ) );
	wp_enqueue_script( 'woocommerce-dymo-print-js', array('jquery') );
	wp_register_script( 'woocommerce-dymo-check-js', plugins_url( '/js/woocommerce-dymo-check.js', __FILE__ ) );
	wp_enqueue_script( 'woocommerce-dymo-check-js', array('jquery') );
	?>
						<h3><?php _e( 'DYMO feature check', 'woocommerce-dymo' ); ?></h3>
						<div class="inside dymo-check">
							
							<p><?php echo __( 'If you have any problems, please check below data.', 'woocommerce-dymo' );?></p>
							<div id="printersInfoContainer"></div>
<div class="printControls">
            <button id="updateTableButton"><?php _e( 'Refresh', 'woocommerce-dymo' ); ?></button>
            <button id="printButton"><?php _e( 'Print printers information on', 'woocommerce-dymo' ); ?></button>
            <select id="printersSelect"></select>

    </div>
						</div>
					</div>
				</div>
				<div style="float:right; width:25%;">
					<div class="postbox">
						<h3><?php _e( 'Buy Pro!', 'woocommerce-dymo' ); ?></h3>
						<div class="inside dymo-preview">
							<p><?php echo __( 'Check out our ', 'woocommerce-dymo' ); ?> <a href="http://www.siteevaluator.nl/plugins/">website</a> <?php _e('to find out more about WooCommerce DYMO Print Pro.', 'woocommerce-dymo' );?></p>
							<p><?php _e('For only &euro; 15,00 you will get a lot of features and access to our support section.', 'woocommerce-dymo' );?></p>
							<p><?php _e('A couple of features:', 'woocommerce-dymo' );?>
							<ul style="list-style:square;padding-left:20px;margin-top:-10px;"><li><?php _e('Print DYMO billing & shipping labels', 'woocommerce-dymo' );?></li><li><?php _e('Customize your own labels', 'woocommerce-dymo' );?></li><li><?php _e('Choose your label size', 'woocommerce-dymo' );?></li><li><?php _e('Print your company logo on your labels', 'woocommerce-dymo' );?></li><li><?php _e('Use a DYMO Labelwriter 450 Twin Turbo', 'woocommerce-dymo' );?></li></ul>
						</div>
					</div>
					<div class="postbox">
						<h3><?php _e( 'Show Your Love', 'woocommerce-dymo' ); ?></h3>
						<div class="inside dymo-preview">
							<p><?php echo sprintf(__( 'This plugin is developed by %s, a Dutch graphic design and webdevelopment company.', 'woocommerce-dymo' ),'Geev vormgeeving'); ?></p>
							<p><?php _e( 'If you are happy with this plugin please show your love by liking us on Facebook', 'woocommerce-dymo' ); ?></p>
							<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fgeevvormgeeving&amp;width=180&amp;height=62&amp;show_faces=false&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:180px; height:62px;" allowTransparency="true"></iframe>
							<p><?php _e( 'Or', 'woocommerce-dymo' ); ?></p>
							<ul style="list-style:square;padding-left:20px;margin-top:-10px;">
							<!--<li><a href="http://www.siteevaluator.nl/plugins/woocommerce-dymo-print/" target=_blank title="Woocommerce DYMO print"><?php _e( 'Rate the plugin 5&#9733; on WordPress.org', 'woocommerce-dymo' ); ?></a></li>-->
								<li><a href="http://www.siteevaluator.nl/plugins/woocommerce-dymo-print/" target=_blank title="Woocommerce DYMO print"><?php _e( 'Blog about it & link to the plugin page', 'woocommerce-dymo' ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php }

function woocommerce_dymo_add_box() {
	add_meta_box( 'woocommerce-dymo-box', __( 'Print DYMO labels', 'woocommerce-dymo' ), 'woocommerce_dymo_create_box_content', 'shop_order', 'side', 'default' );
}
function woocommerce_dymo_create_box_content() {
	global $post_id;
?>
	<table class="form-table dymo-box">
		<tr>
			<td><a class="button dymo-link" href="<?php echo wp_nonce_url(admin_url('?print_dymo=true&post='.$post_id.'&type=print_shipping_label'), 'print-dymo'); ?>"><?php _e('Print Shipping Label', 'woocommerce-dymo'); ?></a></td>
		</tr>
	</table>
<?php }

/**
* Insert buttons to orders page
*/
function woocommerce_dymo_alter_order_actions($column) {
	global $post;
	$order = new WC_Order( $post->ID );
    switch ($column) {
		case "order_actions" :
		?><p><a class="button dymo-link" href="<?php echo wp_nonce_url(admin_url('?print_dymo=true&post='.$post->ID.'&type=print_shipping_label'), 'print-dymo'); ?>"><?php _e('Print Shipping Label', 'woocommerce-dymo'); ?></a></p>
		<?php
		break;
    }
}

/**
* Output items for display
*/
function woocommerce_dymo_order_items_table( $order, $show_price = FALSE ) {
	$return = '';
	foreach($order->get_items() as $item) {
		$_product = $order->get_product_from_item( $item );
		$sku = $variation = '';
		$sku = $_product->get_sku();
		$item_meta = new WC_Order_Item_Meta( $item['item_meta'] );
		$variation = '<br/><small>' . $item_meta->display( TRUE, TRUE ) . '</small>';
		$return .= '<tr>
		  <td style="text-align:left; padding: 3px;">' . $sku . '</td>
			<td style="text-align:left; padding: 3px;">' . apply_filters('woocommerce_order_product_title', $item['name'], $_product) . $variation . '</td>
			<td style="text-align:left; padding: 3px;">'.$item['qty'].'</td>';
		if ($show_price) {
			$return .= '<td style="text-align:left; padding: 3px;">';
				if ( $order->display_cart_ex_tax || !$order->prices_include_tax ) :
					$ex_tax_label = ( $order->prices_include_tax ) ? 1 : 0;
					$return .= woocommerce_price( $order->get_line_subtotal( $item ), array('ex_tax_label' => $ex_tax_label ));
				else :
					$return .= woocommerce_price( $order->get_line_subtotal( $item, TRUE ) );
				endif;
			$return .= '
			</td>';
		}
		$return .= '</tr>';
	}
	$return = apply_filters( 'woocommerce_dymo_order_items_table', $return );
	return $return;
}

/**
* Output preview if needed
*/
function woocommerce_dymo_twin_roll() {
    return get_option('woocommerce_dymo_twin_roll');
}
function woocommerce_dymo_print_company_name() {
	if (get_option('woocommerce_dymo_company_name') != '') {
		return get_option('woocommerce_dymo_company_name');
	}
}
function woocommerce_dymo_print_company_extra() {
	if (get_option('woocommerce_dymo_company_extra') != '') {
		return nl2br(stripslashes(get_option('woocommerce_dymo_company_extra')));
	}
}
function woocommerce_dymo_print_label() {
	if (get_option('woocommerce_dymo_label') != '') {
		return get_option('woocommerce_dymo_label');
	}
}
function woocommerce_dymo_window() {
	if (isset($_GET['print_dymo'])) {
		$nonce = $_REQUEST['_wpnonce'];
		global $woocommerce;
  		if (!wp_verify_nonce($nonce, 'print-dymo') || !is_user_logged_in() ) die('You are not allowed to view this page.');
		$order="";
		$order= ob_get_clean();
		$mypost=ob_get_clean();
		$mypost='';
		$mypost=$_GET['post'];
    	$orders = explode(',', $mypost);
		$action = $_GET['type'];
		ob_start();
/* here print flow*/
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php _e('Print DYMO labels', 'woocommerce-dymo'); ?></title>
	<link href="<?php echo site_url();?>/wp-content/plugins/woocommerce-dymo/css/woocommerce-dymo.css" rel=" stylesheet" type="text/css" media="screen,print" />
	<script charset="UTF-8" type="text/javascript" src="<?php echo site_url();?>/wp-content/plugins/woocommerce-dymo/js/woocommerce-dymo-print.js"> </script>
</head>
<body>
<script type="text/javascript">
template = '<? echo '<?xml version="1.0" encoding="utf-8"?>'; ?>' + '<?php echo preg_replace('/\s\s+/', '\' + \'', woocommerce_dymo_print_label()); ?>';
</script>
	<?php if ($action == 'print_billing_label') { $actie=__('Billing Label', 'woocommerce-dymo'); } else { $actie=__('Shipping Label', 'woocommerce-dymo'); } echo '<h1>'.sprintf( __('Print DYMO %s' , 'woocommerce-dymo') , $actie ).'</h1>'; 
	$content = ob_get_clean();
	$i=0;
	foreach ($orders as $order_id) {
		$order = new WC_Order($order_id);
  		  // Read the file
  		  ob_start();?>
		  <div class=printing id=id_<?php echo $i;?>><p><strong><?php _e('Printing label for order:', 'woocommerce-dymo');?>  <?php echo $order->get_order_number(); ?></strong></p><p>
			<?php 
			if ($action == 'print_billing_label') { 
				echo $order->get_formatted_billing_address(); 
			} else { 
				echo $order->get_formatted_shipping_address();  }
			?></p></div>
	<script type="text/javascript">
	var z=1;
	var k=0;
	var adres='';
	var printers = dymo.label.framework.getPrinters();
	var printParams = {};
	if (printers.length != 0) {
        var label = dymo.label.framework.openLabelXml(template);
		var printer=printers[0];
		if (typeof printer.isTwinTurbo != "undefined")
    {
        if (printer.isTwinTurbo) { 
		<?php if(woocommerce_dymo_twin_roll()=='left') { ?>
		printParams.twinTurboRoll = dymo.label.framework.TwinTurboRoll.Left; // or Left or Right 
		<?php } elseif(woocommerce_dymo_twin_roll()=='right') {?>
		printParams.twinTurboRoll = dymo.label.framework.TwinTurboRoll.Right;
		<?php } else { ?>
		printParams.twinTurboRoll = dymo.label.framework.TwinTurboRoll.Auto; // or Left or Right 
		<?php }?>
		} 
    }
		
		<?php if ($action == 'print_billing_label') { 
			if($order->get_formatted_billing_address()!="") { $adres = $order->get_formatted_billing_address();  $adres=htmlspecialchars(preg_replace('/<br(\s+)?\/?>/i', "", $adres), ENT_QUOTES); $adres = preg_replace("/[\n\r]/","|",$adres); }
		} else { 
  		    if($order->get_formatted_shipping_address()!="") { $adres = $order->get_formatted_shipping_address();  $adres=htmlspecialchars(preg_replace('/<br(\s+)?\/?>/i', "", $adres), ENT_QUOTES); $adres = preg_replace("/[\n\r]/","|",$adres); }
		} ?>
		var adres_in='<?php echo $adres;?>';
		var adres= adres_in.replace(/\|/g, "\n");
		<?php if(woocommerce_dymo_print_company_name()!="") { ?> label.setObjectText("COMPANY", "<?php echo woocommerce_dymo_print_company_name(); ?>"); <?php } ?>
		<?php if(woocommerce_dymo_print_company_extra()!="") { ?> label.setObjectText("EXTRA", "<?php echo woocommerce_dymo_print_company_extra(); ?>");<?php }?>
		<?php if($adres!="") { ?>label.setObjectText("ORDER", adres);<?php } ?>
		setTimeout(function() {label.print(printer.name, dymo.label.framework.createLabelWriterPrintParamsXml(printParams)); },2000);
		}
	</script>
		  <?php
  		  $content .= ob_get_clean();
		  $i++;
      }
		 ?>
		 </body>
</html><?php
  		echo $content;
  		exit;
    }
}
}
?>