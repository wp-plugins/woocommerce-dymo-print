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

function woocommerce_dymo_scripts() {
	wp_register_script( 'woocommerce-dymo-js', plugins_url( '/js/woocommerce-dymo.js', dirname(__FILE__) ) );
	wp_enqueue_script( 'woocommerce-dymo-js', array('jquery') );
}

/**
* WordPress Administration Menu
*/
function woocommerce_dymo_admin_menu() {
	$page = add_submenu_page('woocommerce', __( 'DYMO Print', 'woocommerce-dymo' ), __( 'DYMO Print', 'woocommerce-dymo' ), 'manage_woocommerce', 'woocommerce_dymo', 'woocommerce_dymo_page' );
}

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
		?><p style="display:block;clear:both;height:14px;"><a class="button dymo-link tips" data-tip="<?php _e('Print Shipping Label', 'woocommerce-dymo'); ?>" href="<?php echo wp_nonce_url(admin_url('?print_dymo=true&post='.$post->ID.'&type=print_shipping_label'), 'print-dymo'); ?>"><img src="<?php echo plugins_url( 'img/icon-print-shipping.png' , dirname(__FILE__) ); ?>" alt="<?php _e('Print Shipping Label', 'woocommerce-dymo'); ?>" width="14"></a></p>
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
* Convert address to javascript string
* @since 1.1.1
*/
function woo_dymo_convert_address($adres) {
	$adres= preg_replace('/<br(\s+)?\/?>/i', "", preg_replace("/[\n\r]/","|",$adres));
	return str_replace("'", "\'", htmlspecialchars_decode($adres,ENT_QUOTES)); 
}

/**
* Output preview if needed
*/
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
	return '<DieCutLabel Version="8.0" Units="twips">
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
			<Name>ADDRESS</Name>
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
					<String>adres</String>
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
	</ObjectInfo></DieCutLabel>';

}
function woocommerce_dymo_window_2() {
	if (isset($_GET['print_dymo'])) {
		$nonce = $_REQUEST['_wpnonce'];
		global $woocommerce;
  		if (!wp_verify_nonce($nonce, 'print-dymo') || !is_user_logged_in() ) die('You are not allowed to view this page.');
		$order="";
		$mypost=$_GET['post'];
    	$orders = explode(',', $mypost);
		$action = $_GET['type'];
		/* here print flow*/
		echo 'test';
		exit();

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
/* here print flow*/
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php _e('Print DYMO labels', 'woocommerce-dymo'); ?></title>
	<link href="<?php echo plugins_url( '/css/woocommerce-dymo.css', dirname(__FILE__));?>"  rel="stylesheet" type="text/css" media="screen,print" />
	<script charset="UTF-8" type="text/javascript" src="<?php echo plugins_url( '/js/woocommerce-dymo-print.js', dirname(__FILE__));?>"> </script>
</head>
<body>
<div id=error class=error><?php _e('Something went wrong while printing your label.', 'woocommerce-dymo');?> <?php _e('Please make sure your label contains the required object fields.', 'woocommerce-dymo');?> <?php echo sprintf(__('For more information about how to create your labels, see %s.', 'woocommerce-dymo'),'<a href="http://wordpress.geev.nl/support/documentation/" target=_blank">'.__('our documentation','woocommerce-dymo').'</a>');?></div>
<script type="text/javascript">template = '<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>' + '<?php echo preg_replace('/\s\s+/', '\' + \'', woocommerce_dymo_print_label()); ?>';</script>
	<?php $actie=__('Shipping Label', 'woocommerce-dymo'); echo '<h1>'.sprintf( __('Print DYMO %s' , 'woocommerce-dymo') , $actie ).'</h1>'; 
	
	
	$content = ob_get_clean();
	$i=0;
	foreach ($orders as $order_id) {
		$order = new WC_Order($order_id);
  		  // Read the file
  		  ob_start();?>
		  <div class=printing id=id_<?php echo $i;?>><p><strong><?php _e('Printing label for order:', 'woocommerce-dymo');?>  <?php echo $order->get_order_number(); ?></strong></p><p>
			<?php 
			 	if($order->get_formatted_shipping_address()!="") { $adres = $order->get_formatted_shipping_address(); } else { $adres = $order->get_formatted_billing_address(); }
				echo $adres;
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
			printParams.twinTurboRoll = dymo.label.framework.TwinTurboRoll.Auto; // or Left or Right 
		} 
    }
		<?php $adres=woo_dymo_convert_address($adres);?>
		var adres_in = '<?php echo $adres;?>';
		var adres= adres_in.replace(/\|/g, "\n");
		<?php if(woocommerce_dymo_print_company_name()!="") { ?> label.setObjectText("COMPANY", "<?php echo woocommerce_dymo_print_company_name(); ?>"); <?php } ?>
		<?php if(woocommerce_dymo_print_company_extra()!="") { ?> label.setObjectText("EXTRA", "<?php echo woocommerce_dymo_print_company_extra(); ?>");<?php }?>
		<?php if($adres!="") { ?>label.setObjectText("ADDRESS", adres);<?php } ?>
		setTimeout(function() {label.print(printer.name, dymo.label.framework.createLabelWriterPrintParamsXml(printParams)); },2000);
		}
		document.getElementById('error').style.display = 'none';
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