<?php 
	get_header();
	 $user_ID = get_current_user_id(); 
	  if($user_ID == 206619){
?>
<div class="container content-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">
                  <?php do_action( 'et_page_heading' ); ?>                  
				<div class="content <?php esc_attr_e( $l['content-class'] ); ?>">
                    <div class="intro-content"><?php the_content(); ?></div>
                    <a class="back-to-admin" href="/wp-admin">Back to dashboard</a>
                    <?php $current_user = wp_get_current_user(); 
                              $frID2 = $current_user->ID; ?>
                    <div class="admin-date-selection">
                        <label>From</label><input type="text" id="from_date"> <label>To</label><input type="text" id="to_date"><br><span class="filter_dates">Filter</span><span class="reset-dates">Reset</span>                
                    </div>
                    <span class="generate-pdf total">Download Total Sale Report</span>
                    <div id="admin_total_sale" class="fundraiser-dashboard-section report total-sale">
                        <h3>Total Sale</h3>
                        <h5>Total Sale so far: <strong>$<span class="fr-total-sale"></span></strong></h5>
                        <div class="fundraiser-order-table">
                            <div class="fundraiser-order-row fr-header">
                                <div class="fr-order-col fr-order-id">Order</div>
                                <div class="fr-order-col fr-order-date">Date</div>
                                <div class="fr-order-col fr-order-customer">Customer</div>
                                <div class="fr-order-col fr-order-seller">Fundraiser</div>
                                <div class="fr-order-col fr-order-total">Total</div>
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>
                        <?php 
                            $args = array(
                                'status' => "published",
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                                $order_data = $order->get_data(); ?>
                                <div class="fundraiser-order-row fr-body datematch" order-qty="<?php echo $order_data['quantity']; ?>">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller"><?php 
                                        $slrID = get_post_meta($order->ID, 'fundraiser_field', true );
                                        $slr_info = get_userdata($slrID);
                                        ?><?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php echo $order->get_total(); ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="total-order-<?php echo $order->ID; ?>-details">Expand</span></div>
                                <div id="total-order-<?php echo $order->ID; ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>
                                    <div class="fundraiser-order-details-row fr-body">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['total']; ?></span></div>
                                    </div>                                    
                                    <?php endforeach; ?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col seller-order-subtotal">$<span></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Discount</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col seller-order-discount">$<span></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span><?php echo $order->get_total(); ?></span></div>
                                    </div>
                                </div>
                                </div>
                            <?php 
                        }
                        ?>
                            
                        </div>
                    </div>
                    
                    <div class="fundraiser-selection">
                        <h3>Fundraiser Sale</h3>
                        <select class="fundraiser-select-report" value="">
                            <option value="All">All Fundraisers</option>
                        </select>       
                    <span class="generate-pdf fr">Download Fundraiser Report</span>                 
                    </div>
                    <div id="admin_fr_sale_report">
                    <div id="admin_online_sale" class="fundraiser-dashboard-section report online-sale">
                        <h3>Online Sale for <span class="dynamic_fr_name">All Fundraisers</span></h3>
                        <h5>Online Sale so far: <strong>$<span class="fr-online-sale"></span></strong></h5>
                        <div class="fundraiser-order-table">
                            <div class="fundraiser-order-row fr-header">
                                <div class="fr-order-col fr-order-id">Order</div>
                                <div class="fr-order-col fr-order-date">Date</div>
                                <div class="fr-order-col fr-order-customer">Customer</div>
                                <div class="fr-order-col fr-order-seller">Fundraiser</div>
                                <div class="fr-order-col fr-order-total">Total</div>
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>
                        <?php 
                            $args = array(
                                'status' => "published",
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id']; 
                                $fr_user_meta = get_userdata($order_customer_id);
                                $fr_user_roles = $fr_user_meta->roles; 
                                $slrID = get_post_meta($order->ID, 'fundraiser_field', true );
                                if (! in_array( 'fundraiser', $fr_user_roles, true ) ) { ?>
                                <div class="fundraiser-order-row fr-body datematch frmatch" admin-fr-id="<?php echo $slrID; ?>" order-qty="<?php echo $order_data['quantity']; ?>">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller"><?php 
                                        $slr_info = get_userdata($slrID);
                                        ?><?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php echo $order->get_total(); ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="ol-order-<?php echo $order->ID; ?>-details">Expand</span></div>
                                <div id="ol-order-<?php echo $order->ID; ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_data = $item_values->get_data();  ?>
                                    <div class="fundraiser-order-details-row fr-body">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>
                                        <div class="fundraiser-order-details-col product-quantity"><?php echo $item_data['quantity']; ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['total']; ?></span></div>
                                    </div>                                    
                                    <?php endforeach; ?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col seller-order-subtotal">$<span></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Discount</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col seller-order-discount">$<span></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span><?php echo $order->get_total(); ?></span></div>
                                    </div>
                                </div>
                                </div>
                            <?php 
                        }
                        }
                        ?>
                            
                        </div>
                    </div>
                    
                    <div id="admin_catalogue_sale" class="fundraiser-dashboard-section report catalogue-sale">
                        <h3>Catalogue Sale for <span class="dynamic_fr_name">All Fundraisers</span></h3>
                        <h5>Mass Catalogue Sale so far: <strong>$<span class="fr-catalogue-sale"></span></strong></h5>
                        <div class="fundraiser-order-table">
                            <div class="fundraiser-order-row fr-header">
                                <div class="fr-order-col fr-order-id">Order</div>
                                <div class="fr-order-col fr-order-date">Date</div>
                                <div class="fr-order-col fr-order-customer">Customer</div>
                                <div class="fr-order-col fr-order-seller">Fundraiser</div>
                                <div class="fr-order-col fr-order-total">Total</div>
                                <div class="fr-order-col fr-order-action">Details</div>
                            </div>
                        <?php 
                            $args = array(
                                'status' => "published",
                                'meta_key' => 'fundraiser_field',
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                                $order_data = $order->get_data();
                                $order_customer_id2 = $order_data['customer_id']; 
                                $fr_user_meta2 = get_userdata($order_customer_id2);
                                $fr_user_roles2 = $fr_user_meta2->roles; 
                                if ( in_array( 'fundraiser', $fr_user_roles2, true ) ) {  ?>
                                <div class="fundraiser-order-row fr-body datematch frmatch" admin-fr-id="<?php echo $order_customer_id2; ?>">
                                    <div class="fr-order-col fr-order-id"><?php echo $order->ID; ?></div>
                                    <div class="fr-order-col fr-order-date"><?php echo $order_data['date_created']->date('Y-m-d'); ?></div>
                                    <div class="fr-order-col fr-order-customer"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></div>
                                    <div class="fr-order-col fr-order-seller"><?php 
                                        $slrID = get_post_meta($order->ID, 'fundraiser_field', true );
                                        $slr_info = get_userdata($slrID);
                                        ?><?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></div>
                                    <div class="fr-order-col fr-order-total"><span class="currancy-symbol">$</span><span class="fr-order-amt"><?php echo $order->get_total(); ?></span></div>
                                    <div class="fr-order-col fr-order-action"><span class="details-btn" data-trigger="cat-order-<?php echo $order->ID; ?>-details">Expand</span></div>
                                <div id="cat-order-<?php echo $order->ID; ?>-details" class="fundraiser-order-details-table">
                                    <div class="fundraiser-order-details-row fr-header">
                                        <div class="fundraiser-order-details-col product-name">Product</div>
                                        <div class="fundraiser-order-details-col product-quantity">Quantity</div>
                                        <div class="fundraiser-order-details-col product-seller">Seller</div>
                                        <div class="fundraiser-order-details-col product-buyer">Buyer</div>
                                        <div class="fundraiser-order-details-col product-price">Total</div>
                                    </div> 
                                    <?php $order_item_quantity = 0; ?>
                                    <?php foreach ($order->get_items() as $item_key => $item_values): 
                                            $item_id = $item_values->get_id();
                                            $item_data = $item_values->get_data();  
                                            $order_item_quantity += $item_data['quantity']; ?>
                                    <div class="fundraiser-order-details-row fr-body line-item-match">
                                        <div class="fundraiser-order-details-col product-name"><?php echo $item_data['name']; ?></div>
                                        <div class="fundraiser-order-details-col product-quantity num"><?php echo $item_data['quantity']; ?></div>
                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_seller_display", true);  ?></div>
                                        <div class="fundraiser-order-details-col product-seller"><?php echo wc_get_order_item_meta($item_id, "_wdm_buyer_name", true); ?></div>
                                        <div class="fundraiser-order-details-col product-price show">$<span><?php echo $item_data['total']; ?></span></div>
                                    </div>                                    
                                    <?php endforeach; ?>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Subtotal</div>
                                        <div class="fundraiser-order-details-col product-quantity parent-order-qty"><span><?php echo $order_item_quantity; ?></span></div>
                                        <div class="fundraiser-order-details-col product-seller"></div>
                                        <div class="fundraiser-order-details-col product-buyer"></div>
                                        <div class="fundraiser-order-details-col all-order-subtotal">$<span></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Discount</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-seller"></div>
                                        <div class="fundraiser-order-details-col product-buyer"></div>
                                        <div class="fundraiser-order-details-col all-order-discount-calc">$<span></span></div>
                                    </div>
                                    <div class="fundraiser-order-details-row fr-footer">
                                        <div class="fundraiser-order-details-col product-name">Order Total</div>
                                        <div class="fundraiser-order-details-col product-quantity"></div>
                                        <div class="fundraiser-order-details-col product-seller"></div>
                                        <div class="fundraiser-order-details-col product-buyer"></div>
                                        <div class="fundraiser-order-details-col product-price">$<span><?php echo $order->get_total(); ?></span></div>
                                    </div>
                                </div>
                                </div>
                            <?php 
                        }
                        }
                        ?>
                            
                        </div>
                    </div>
                    </div>
                </div>

			</div><!-- end row-fluid -->

		</div>
	</div><!-- end container -->
    <div class="mass-order-popup">
        <div class="mass-order-popup-wrapper">
            <a href="/spring-summer-catalogue-mass-order/" class="fr-btn">Spring Summer Catalogue</a>
            <a href="/digital-fall-winter-catalogue/" class="fr-btn">Digital Fall-Winter Catalogue</a>
            <a href="#" class="txt-link">Cancel</a>
        </div>
    </div>

<?php
}else{
  ?>
  <div class="container content-page">
		<div class="page-content sidebar-position-<?php esc_attr_e( $l['sidebar'] ); ?> sidebar-mobile-<?php esc_attr_e( class_exists('WooCommerce') && ( is_checkout() || is_cart() || is_account_page() || in_array( 'woocommerce-wishlist', get_body_class() ) ) ? $l['shop-sidebar-mobile'] : $l['sidebar-mobile'] ); ?>">
			<div class="row">
			    Sorry!!, You can access
			</div>
		</div>
	</div>
  <?php
}
	get_footer();
?>
