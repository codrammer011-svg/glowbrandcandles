<?php
/**
 * Template Name: Print Order Recieved
 */
 $order_id=$_GET['order_id'];
 if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! $order = wc_get_order( $order_id ) ) {
	return;
}

$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$order_type = get_post_meta($order_id, 'wc_order_type', true );
			if(!empty($order_type) && $order_type=='Catlog') 
			{
				$seller_name= array();
				
				foreach ( $order_items as $item_id => $item ) :
				$s_name=$item['_wdm_seller_name'];
				 $order_id    = $item['order_id'];
				/*$s_name=wc_get_order_item_meta( $order_id, '_wdm_seller_name', true );*/
				if(!in_array($s_name,$seller_name))
				{
					$seller_name[]=$s_name;
				}
				
				endforeach; 
				/*print_r($seller_name); */
					$seller_arr=array();
				for($i=0;$i<count($seller_name);$i++) {
					$product_tr_count=0;	
					$sub_total=0;
					$quantityTotal=0;					
										
					$title = "";
					$title2="";	
					$s_name2="";					
					foreach ( $order_items as $item_id => $item ) :
					$quantity=0;
					$product = $item->get_product();
				$s_name2=$item['_wdm_seller_name'];
					if($seller_name[$i]==$s_name2)
					{
							$title=$seller_name[$i];
							if($title2 != $title)
							{
								$seller_arr[$i]['order_id']=$item['order_id'];
								$seller_arr[$i]['seller_name']=$title;
							}
							$title2 = $title;
							$product_tr_count++;
							$quantity = $item->get_quantity();
							$each_product_price= $product->get_regular_price();
									
							$subTotal=$quantity*$each_product_price;
							$quantityTotal+=$quantity;
							$sub_total+=$subTotal;
					}
					endforeach; 
					$seller_arr[$i]['product_tr_count']=$product_tr_count;
						$seller_arr[$i]['subtotal']=$sub_total;
						$seller_arr[$i]['quantity']=$quantityTotal;
						
				}
				/*print_r($seller_arr);*/
			$title="";
			$title2="";
			$j=0;
			}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php global $etheme_responsive, $woocommerce; ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
    
    <meta name="viewport" content="<?php if($etheme_responsive): ?>width=device-width, initial-scale=1, maximum-scale=2.0<?php else: ?>width=1200<?php endif; ?>"/>
   	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
   	
	<link rel="shortcut icon" href="<?php echo et_get_favicon(); ?>" />
	<title><?php wp_title( '|', true, 'right' );?></title>
	<?php
			if ( is_singular() && get_option( 'thread_comments' ) )
				wp_enqueue_script( 'comment-reply' );

			wp_head();
		?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">
	<style type="text/css">
	body {
    font-family: 'Proxima Nova', Georgia, sans-serif;
    font-size: 13px;
    width: 100%;
    margin: 0 auto;
    padding: 15px 40px;
    display: inline-block;
}
    table 
	{ 
		max-width: 100%;
		background-color: transparent;
		border-collapse: collapse;
		border-spacing: 0;  width: 100%;
		margin: 0 0 20px;
		float: left;
	}
	table th
	{
		font: 14px/22px 'Roboto', Arial, Georgia, sans-serif;
		text-transform: uppercase;
		color: #252525;
		vertical-align: middle;
		letter-spacing: 1px;
		padding: 22px 0 !important;
		line-height: 1.42857143;
		font-weight: 500;
		vertical-align: top;    text-align: left;
	}
	   table {  page-break-after:always; }
    tr    { page-break-inside:avoid; page-break-after:auto; }
   thead { display:table-header-group; }
    tfoot { display:table-footer-group; } 
	tbody tr td {
		line-height: 1.42857143;
		vertical-align: top;
		border-top: 1px solid #dddddd;
		font-size: 15px;
		font-family: 'Roboto';
		vertical-align: middle;padding: 8px;text-align: left;    
		padding-left: 0;
		font: 14px/22px 'Roboto', Arial, Helvetica, sans-serif;
	}
	tbody tr td a
	{
		font: 14px/22px 'Roboto', Arial, Helvetica, sans-serif;
		text-transform: uppercase;
		color: #000;
	}
	p {
    margin-bottom: 20px;
    color: #767676;
    line-height: 1.8;
}
ul li {
    list-style: none;
    padding-bottom: 8px;
}
b, strong {
    font-weight: 700;
}
.print-icon
{
	    float: right;
    font-size: 23px;
    position: absolute;
    right: 50px;
    top: 13px;
	cursor:pointer;
}
</style>
<script>

</script>
</head>

<body <?php body_class(); ?> onload="window.print();">
<span class="print-icon" onclick="window.print();" style="color:red;">Click Print <i class="fa fa-print" aria-hidden="true"></i></span>
<p class="woocommerce-thankyou-order-received"><?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Thank you. Your order has been received.', 'woocommerce'), $order); ?></p>
<ul class="woocommerce-thankyou-order-details order_details">
   <li class="order_no">
        <?php _e( 'Order No:', 'woocommerce' ); ?>
        <strong><?php echo "#".$order_id; ?></strong>
    </li>
    <li class="date">
        <?php _e( 'Date:', 'woocommerce' ); ?>
        <strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "") ) ); ?></strong>
    </li>
	 <?php if ( $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php esc_html_e( 'Email:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
				<?php endif; ?>
    <li class="">
        <?php _e( 'Total:', 'woocommerce' ); ?>
        <strong><?php echo $order->get_formatted_order_total(); ?></strong>
    </li>
    <?php if ( $order->payment_method_title ) : ?>
    <li class="method">
        <?php _e( 'Payment Method:', 'woocommerce' ); ?>
        <strong><?php echo $order->payment_method_title; ?></strong>
    </li>
    <?php endif; ?>
</ul>
<h2 style="border-bottom: 2px solid #d5d5d5; text-transform: uppercase; font-size: 18px;padding-bottom: 10px;margin-bottom: 10px;">Order details</h2>
 <table style="">
        <thead>
          <tr>
				<th class="product-name"><?php esc_html_e( 'Product', 'royal' ); ?></th>
				<th class="product-price"><?php esc_html_e( 'Price', 'royal' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'royal' ); ?></th>
				<th class="product-subtotal"><?php esc_html_e( 'Total', 'royal' ); ?></th>
			</tr>
        </thead>
       
        <tbody>
		<?php
       	foreach ( $order_items as $item_id => $item ) 
			{
				$product = $item->get_product();
				$order_id    = $item['order_id'];
				$order_type = get_post_meta($order_id, 'wc_order_type', true );
				if(!empty($order_type) && $order_type=='Catlog') 
				{
					$p_seller_name=$seller_arr[$j]['seller_name'];
					$product_tr_count=$seller_arr[$j]['product_tr_count'];
					$product_Total=$seller_arr[$j]['subtotal'];
					$quantity_Total=$seller_arr[$j]['quantity'];
				
					$seller_n = wc_get_order_item_meta( $item_id, '_wdm_seller_name', true );
					$title=$seller_n;
					
				
				
				if(!empty($title))
				{
					if($title2 != $title)
					{
					$itemcount=0;
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">
					
					<td class="woocommerce-table__product-name product-name"><?php echo '<b>Seller Name: '.$title.'</b>'; ?></td>	
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php }  $title2 = $title;
				} 
				if($title2==$title)
				{
					$itemcount++;
				}
				
				}	
			?>
			<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">


	<td class="woocommerce-table__product-name product-name" style="color:#000;font: 14px/22px 'Roboto', Arial, Helvetica, sans-serif; text-transform: uppercase;">
	
		<?php
     
			$is_visible        = $product && $product->is_visible();
			$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

			echo apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a>%s</a>', $item->get_name() ) : $item->get_name(), $item, $is_visible );
		

			do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

			wc_display_item_meta( $item );

			do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
		?>
	</td>

	<td class="product-price">
		<?php $pr="$".$product->get_regular_price(); esc_html_e($pr); ?>
	</td>
	
	<td class="product-quantity">
		<?php /*echo apply_filters( 'woocommerce_order_item_quantity_html', sprintf( '%s', $item->get_quantity() ), $item );*/
			esc_html_e($item->get_quantity());
		?>
	</td>
	<td class="woocommerce-table__product-total product-total">
		<?php echo $order->get_formatted_line_subtotal( $item ); ?>
	</td>

</tr>
			<?php
			
			
				if(!empty($order_type) && $order_type=='Catlog') 
				{
					if($title2 == $p_seller_name && $itemcount==$product_tr_count){ ?>								
									<tr>									
										<td style="text-align:left;color: red;font: 17px/20px 'Roboto', Arial, sans-serif;font-weight: 700;padding: 15px 0px;border-bottom: 1px solid black;border-top: 1px solid black;opacity: 0.9;"><?php echo '<b>Total Sales For : '.$p_seller_name.'</b>'; ?></td>
										<td style="text-align:left;color: red;font: 17px/20px 'Roboto', Arial, sans-serif;font-weight: 700;padding: 15px 0px;border-bottom: 1px solid black;border-top: 1px solid black;opacity: 0.9;"></td>									
										<td style="text-align:left;color: red;font: 17px/20px 'Roboto', Arial, sans-serif;font-weight: 700;padding: 15px 0px;border-bottom: 1px solid black;border-top: 1px solid black;opacity: 0.9;"><?php echo $quantity_Total; ?></td>									
										<td style="text-align:left;color: red;font: 17px/20px 'Roboto', Arial, sans-serif;font-weight: 700;padding: 15px 0px;border-bottom: 1px solid black;border-top: 1px solid black;opacity: 0.9;"><?php echo '<span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>'.number_format($product_Total,2).'</bdi></span>';?></td>								
									</tr>					
				<?php $j++; }  }  
			}
			
			
			?>
		</tbody>
		<tfoot>
		<?php 
		 if(!empty($order_type) && $order_type=='Catlog') 
					{
				foreach ($order->get_items() as $item_key => $item_values): 
                        $item_data = $item_values->get_data(); 
						$order_type_1 = get_post_meta($order->get_id(), 'wc_order_type', true );
						if($order_type_1=="Online") 
						{
							$item_qty  +=  $item_data['quantity']; 
						}
						else if($order_type_1=="Catlog") 
						{
							$item_qty_catalog  +=  $item_data['quantity']; 
						}
						else{}
				endforeach; 
			?>
			<tr>
						<th scope="row">Total sold items:</th>
						<td></td>
						<td></td>
						<td><?php echo $item_qty_catalog; ?></td>
					</tr>
			<?php			
					}
			?>
			<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					?>
					<?php  if(!empty($order_type) && $order_type=='Catlog') 
					{ ?>
					<tr>
						<th scope="row"><?php echo $total['label']; ?></th>
						<td></td>
						<td></td>
						<td><?php echo $total['value']; ?></td>
					</tr>
					<?php } ?>
					
					
					<?php
				}
			?>
		
			
			<?php if ( $order->get_customer_note() ) : ?>
				<tr>
					<th><?php _e( 'Note:', 'woocommerce' ); ?></th>
					<td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
				</tr>
			<?php endif; ?>
		</tfoot>
    </table>
</body>
</html>
