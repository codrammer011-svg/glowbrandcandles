<?php
/*
Plugin Name: Fundraiser Module by Armentum
Plugin URI: 
Description: Custom Fundraiser Module for GSC website.
Version: 
Author: Team Armentum
Author URI: 
*/

if (!defined('ABSPATH')){
    exit; //Exit if accessed directly
}

add_action('wp_ajax_nopriv_wp_catalog_type', 'wp_catalog_type'); 
add_action('wp_ajax_wp_catalog_type', 'wp_catalog_type');
function wp_catalog_type()
{
	session_start();
	$CatalogType=$_POST['catalogType'];
	$_SESSION['catalog_type']=$CatalogType;
	echo $_SESSION['catalog_type'];
}
require_once plugin_dir_path( __FILE__ ) . 'includes/glow-seller-orders.php';

add_filter( 'theme_page_templates', function ( $t ) {
    $t['glow-spring-summer']  = 'Seller — Spring Summer Brochure Order';
    $t['glow-perfect-scents'] = 'Seller — Perfect Scents for Every Season';
    $t['glow-fall-winter']    = 'Seller — Fall-Winter Brochure Order';
    return $t;
} );

add_filter( 'template_include', function ( $template ) {

    if ( ! is_page() ) return $template;

    $map = array(
        'glow-spring-summer'  => 'seller-spring-summer.php',
        'glow-perfect-scents' => 'seller-perfect-scents.php',
        'glow-fall-winter'    => 'seller-fall-winter.php',
    );

    $slug = get_page_template_slug( get_queried_object_id() );

    if ( isset( $map[ $slug ] ) ) {
        $file = plugin_dir_path( __FILE__ ) . 'templates/' . $map[ $slug ];
        if ( file_exists( $file ) ) return $file;
    }

    return $template;
}, 99 );

function gsc_load_plugin_resources() {
    wp_enqueue_style( 'fundraiser-css', plugin_dir_url( __FILE__ ) . 'resources/fundraiser-styles.css','','0.1', 'all' );
    wp_enqueue_script( 'jquery-cookies', 'https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js', array('jquery'),'',true );
    wp_enqueue_script( 'pdf-generation', plugin_dir_url( __FILE__ ) . 'resources/html2pdf.bundle.min.js', array('jquery'),'0.2',true );
    wp_enqueue_script( 'fundraiser-script', plugin_dir_url( __FILE__ ) . 'resources/fundraiser-scripts.js', array('jquery'),'0.2',true );
}
add_action('wp_enqueue_scripts', 'gsc_load_plugin_resources');


add_role('fundraiser', __(
    'Fundraiser'),
    array(
        'read'                     => true,
        'view_woocommerce_reports' => true,
        'read_shop_order'          => true,
        'read_product'             => true,
        'read_shop_coupon'         => true,
        'list_users'               => true,
        )
);

add_role('seller', __(
    'Seller'),
    array(
        'read'                     => true,
        'view_woocommerce_reports' => true,
        'read_shop_order'          => true,
        'read_product'             => true,
        'read_shop_coupon'         => true,
//        'list_users'               => true,
        )
);


class PageTemplater {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new PageTemplater();
		}

		return self::$instance;

	}

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {

		$this->templates = array();


		// Add a filter to the attributes metabox to inject template into the cache.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

			// 4.6 and older
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);

		} else {

			// Add a filter to the wp 4.7 version attributes metabox
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);

		}

		// Add a filter to the save post to inject out template into the page cache
		add_filter(
			'wp_insert_post_data',
			array( $this, 'register_project_templates' )
		);


		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path
		add_filter(
			'template_include',
			array( $this, 'view_project_template')
		);


		// Add your templates to this array.
		$this->templates = array(
			'templates/fundraiser-dashboard.php' => 'Fundraiser Dashboard',
			'templates/fundraiser-dashboard-new.php' => 'Fundraiser Dashboard New',
			'templates/fundraiser-dashboard-new-1.php' => 'Fundraiser Dashboard New 1',
			'templates/fundraiser-reports.php' => 'Fundraiser Reports',
			'templates/fundraiser-seller-reports.php' => 'Seller Reports',
			'templates/seller-dashboard.php' => 'Seller Dashboard Old',
			'templates/seller-dashboard-new.php' => 'Seller Dashboard',
			'templates/seller-dashboard-new-1.php' => 'Seller Dashboard New 1',
			'templates/admin-reports.php' => 'Admin Reports',
			'templates/fundraiser-dashboard-techy.php' => 'Fundraiser Dashboard Techy',
		);

	}

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 */
	public function register_project_templates( $atts ) {

		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}

		// New cache, therefore remove the old one
		wp_cache_delete( $cache_key , 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = array_merge( $templates, $this->templates );

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	}

	/**
	 * Checks if the template is assigned to the page
	 */
	public function view_project_template( $template ) {

		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta(
			$post->ID, '_wp_page_template', true
		)] ) ) {
			return $template;
		}

		$file = plugin_dir_path( __FILE__ ). get_post_meta(
			$post->ID, '_wp_page_template', true
		);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}

		// Return template
		return $template;

	}

}
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

add_action( 'init', 'woocommerce_clear_cart_url' );
function woocommerce_clear_cart_url() {
  global $woocommerce;
	
	if ( isset( $_GET['empty-cart'] ) ) {
		$woocommerce->cart->empty_cart(); 
	}
}
add_action( 'template_redirect', 'fundraiser_dashboard_redirect' );
function fundraiser_dashboard_redirect() {
    if ( is_page( 'fundraiser-dashboard' ) ) {
        if ( ! is_user_logged_in() ) {
            wp_safe_redirect( wp_login_url( home_url( '/fundraiser-dashboard/' ) ) );
            exit;
        }
        $user  = wp_get_current_user();
        $roles = (array) $user->roles;

        // Admins & fundraisers may view the fundraiser dashboard.
        if ( array_intersect( array( 'administrator', 'fundraiser' ), $roles ) ) {
            return;
        }

        // Everyone else is routed to their own place instead.
        if ( in_array( 'representative', $roles, true ) ) {
            wp_safe_redirect( home_url( '/representative-dashboard/' ) );
        } elseif ( in_array( 'seller', $roles, true ) ) {
            wp_safe_redirect( home_url( '/seller-dashboard/' ) );
        } else {
            wp_safe_redirect( home_url( '/my-account/' ) );
        }
        exit;
    }
}

//function gsc_login_redirect_contributors() {
//  if ( current_user_can('fundraiser') ){
//      return 'fundraiser-dashboard';
//  }
//}
//
//add_filter('login_redirect', 'gsc_login_redirect_contributors');



//add_filter('woocommerce_get_item_data','gsc_add_item_meta',10,2);
//
//
//function gsc_add_item_meta($item_data, $cart_item)
//{
//
//        $item_data[] = array(
//            'key'   => 'buyer_name',
//            'value' => "Ameet"
//        );
//        $item_data[] = array(
//            'key'   => 'seller_name',
//            'value' => "David"
//        );
//
//
//    return $item_data;
//}

//function modify_orders() {
//    if ( post_type_exists( 'shop_order' ) ) {
//
//        /* Give products hierarchy (for house plans) */
//        global $wp_post_types;
//        $wp_post_types['shop_order']->hierarchical = true;
//    }
//}
//add_action( 'init', 'modify_orders', 20, 5 );
//
//add_action( 'woocommerce_new_order', 'create_suborder_for specific line items',  1, 1  );
//function create_invoice_for_wc_order() {
//
//}


add_action('woocommerce_before_add_to_cart_button','wdm_add_custom_fields');

function wdm_add_custom_fields()
{

    global $product;

    ob_start();

    ?>
<!--
        <div class="wdm-custom-fields">
            <input type="text" name="wdm_buyer_name">
            <input type="text" name="wdm_seller_name">
        </div>
-->
        <div class="clear"></div>

    <?php

    $content = ob_get_contents();
    ob_end_flush();

    return $content;
}


// Check or create user
function check_or_create_user($user_name){

    $full_name = trim($user_name);
    $full_name_array = explode(',',$full_name);
    $first_name = isset($full_name_array[0]) ? $full_name_array[0] : '';
    $last_name = isset($full_name_array[1]) ? $full_name_array[1] : '';

    //if no name. stop execution
    if($first_name ==''){
        return false;
    }

    // format username
    $user_name = trim($user_name);
    $user_name = strtolower($user_name);
    $user_name = str_replace(' ','_',$user_name);
    if($user_id = username_exists($user_name)){

    }else{
        $user_id = wp_insert_user(array(
            'user_login'=>$user_name,
            'first_name'=>$first_name,
            'last_name'=>$last_name,
            'display_name'=>$full_name,
            'user_nicename'=>$full_name,
            'role'=>'seller',
            'user_email'=>'asdas@asdasd.asd'
            ));
    }

    return $user_id;

}
function glow_woocommerce_set_cart_cookies($set){


}
add_action('woocommerce_set_cart_cookies','glow_woocommerce_set_cart_cookies');
add_action('woocommerce_cart_calculate_fees', 'discount_when_quantity_greater_than_10');
function discount_when_quantity_greater_than_10() 
{
	session_start();
    global $woocommerce;
    global $total_qty;
	global $cart_item_data;
	//$total_qtyt=$woocommerce->cart->get_cart_contents_count();
	$user = wp_get_current_user();
	$new_catalog_order=get_user_meta( $user->ID, 'new_catalog_order_50_percent',true);
		
	if(glow_fc_cart_is_catalog())
	{
		if( in_array( 'fundraiser', (array) $user->roles ) ) 
		{
			
			/* New Quantity */
			$order_quantity_test=0;
			$IDT=$user->ID;
			$args = array(
						 'status' => array('wc-waiting-call'),
						 'meta_key' => 'fundraiser_field',
						 'meta_value' => $IDT,
						 'limit' => -1
				   );
			$orders = wc_get_orders($args);
			foreach ( $orders as $forder ) 
			{			
					$order = wc_get_order( $forder->ID );
					//$order = new WC_Order($forder->ID);
					$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
					$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
					$order_type = get_post_meta($order->ID, 'wc_order_type', true );
					
					if($order_type=="ship-to-school")
					{
						if ($scat==1) { 
							 
									//Active/Inactive 
									$order_date = $order->order_date;
									$extract_date = $order_date;
									$inactive_date = get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s = get_user_meta($frID3, "user_action_status", true);
								
									$in_s=1;
									
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
								
									if ($in_s==1)
									{		
										   
											foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data(); 
												$order_type = get_post_meta($order->ID, 'wc_order_type', true );
												if($order_type=="ship-to-school") 
												{
													$order_quantity_test += $item_data['quantity']; 
													
												}
												
										endforeach; 
										
									}
						} 
						
					}
			}
			
			$total_qtyt_cart=$woocommerce->cart->get_cart_contents_count();
			$total_qtyt=$total_qtyt_cart+$order_quantity_test;
			
			
			if( $total_qtyt <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1)  ) {
				$amount=$woocommerce->cart->subtotal;
				 $to_widthdraw = floatval ($amount) * floatval (40) / 100;
				/*$coupon_code = 'fundraiser40';*/
				 $woocommerce->cart->add_fee("Fundraiser Discount", (-1 * $to_widthdraw), true, '');
			   /* if (!$woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ))) {
					$woocommerce->show_messages();
				}*/
				/*echo '<div class="woocommerce_message"><strong>Fundraiser discount 40% Discount has been Applied!</strong></div>';*/
		   }
		   else if($total_qtyt > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1)  )
		   {
			    $_SESSION['glow_catalog_session']='Yes';
			   $amount=$woocommerce->cart->subtotal;
				 $to_widthdraw = floatval ($amount) * floatval (50) / 100;
				/*$coupon_code = 'fundraiser40';*/
				 $woocommerce->cart->add_fee("Fundraiser Discount", (-1 * $to_widthdraw), true, '');
			   
		   }
		   else
		   {
			   
		   }
		}


	}
	else if(!empty($_SESSION['sellername']) && $_SESSION['sellername']=='Online') 
	{
		
	}
	else
	{
		$cart_contents = $woocommerce->cart->get_cart();
		foreach ( $cart_contents as $cart_item_key => $values )
		  {
			  $seller_name = $values['wdm_seller_name'];
			  if(empty($seller_name))
			  {
				  $order_type='Online';
			  }
			  else
			  {
				 $order_type='Catlog'; 
			  }
		  }
		if( $order_type== 'Catlog' ) 
		{
		
		if( in_array( 'fundraiser', (array) $user->roles ) ) 
		{
			$_SESSION['sellername']='Catlog';
			//$_SESSION['catalog_type']='Summer';
			$_SESSION['catalog_type']='Winter';
			
			/* New Quantity */
			$order_quantity_test=0;
			$IDT=$user->ID;
			$args = array(
						 'status' => array('wc-waiting-call'),
						 'meta_key' => 'fundraiser_field',
						 'meta_value' => $IDT,
						 'limit' => -1
				   );
			$orders = wc_get_orders($args);
			foreach ( $orders as $forder ) 
			{			
					$order = wc_get_order( $forder->ID );
					//$order = new WC_Order($forder->ID);
					$scat = get_post_meta($order->ID, 'ship_with_catalog', true );
					$frID3 = get_post_meta($order->ID, 'fundraiser_field', true );
					$order_type = get_post_meta($order->ID, 'wc_order_type', true );
					
					if($order_type=="ship-to-school")
					{
						if ($scat==1) { 
							 
									//Active/Inactive 
									$order_date = $order->order_date;
									$extract_date = $order_date;
									$inactive_date = get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s = get_user_meta($frID3, "user_action_status", true);
								
									$in_s=1;
									
									if ($inactive_date < $extract_date && $inactive_s==2)
									{
										$in_s=2;
									}
									else if($inactive_s==1 || empty($inactive_s))
									{
										$in_s=1;
									}
									else{
										$in_s=1;
									}
								
									if ($in_s==1)
									{		
										   
											foreach ($order->get_items() as $item_key => $item_values): 
												$item_data = $item_values->get_data(); 
												$order_type = get_post_meta($order->ID, 'wc_order_type', true );
												if($order_type=="ship-to-school") 
												{
													$order_quantity_test += $item_data['quantity']; 
													
												}
												
										endforeach; 
										
									}
						} 
						
					}
			}
			
			$total_qtyt_cart=$woocommerce->cart->get_cart_contents_count();
			$total_qtyt=$total_qtyt_cart+$order_quantity_test;
			
			if( $total_qtyt <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1)  ) {
				$amount=$woocommerce->cart->subtotal;
				 $to_widthdraw = floatval ($amount) * floatval (40) / 100;
				/*$coupon_code = 'fundraiser40';*/
				 $woocommerce->cart->add_fee("Fundraiser Discount", (-1 * $to_widthdraw), true, '');
			   /* if (!$woocommerce->cart->add_discount( sanitize_text_field( $coupon_code ))) {
					$woocommerce->show_messages();
				}*/
				/*echo '<div class="woocommerce_message"><strong>Fundraiser discount 40% Discount has been Applied!</strong></div>';*/
		   }
		   else if($total_qtyt > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1)  )
		   {
			     $_SESSION['glow_catalog_session']='Yes';
			   $amount=$woocommerce->cart->subtotal;
				 $to_widthdraw = floatval ($amount) * floatval (50) / 100;
				/*$coupon_code = 'fundraiser40';*/
				 $woocommerce->cart->add_fee("Fundraiser Discount", (-1 * $to_widthdraw), true, '');
			   
		   }
		   else
		   {
			 
			   
		   }
		}
		
		}
		if( $order_type== 'Online' ) 
		{
			$_SESSION['sellername']='Online';
		}
		
		
	}
}
add_filter('woocommerce_add_cart_item_data','wdm_add_item_data',10,3);

/**
 * Add custom data to Cart
 * @param  [type] $cart_item_data [description]
 * @param  [type] $product_id     [description]
 * @param  [type] $variation_id   [description]
 * @return [type]                 [description]
 */
function wdm_add_item_data($cart_item_data, $product_id, $variation_id)
{
    if($cart_item_data['restore_saved_order'] == 'Yes')
	{
		
	}
	else
	{
		if ( current_user_can('fundraiser') ){


			// Convert seller name to id
		   /* $_COOKIE['massSellerName'] = check_or_create_user($_COOKIE['massSellerName']);*/

	//        $cart_item_data['wdm_buyer_name'] = $_COOKIE['massBuyerName'];
			$cart_item_data['wdm_seller_name'] = $_COOKIE['massSellerName'];
			$cart_item_data['wdm_seller_display'] = $_COOKIE['massSellerDisplay'];
			if(!empty($_COOKIE['massSellerName']))
			{
				$_SESSION['sellername']='Catlog';
			}
			else{
				$_SESSION['sellername']='Online';
			}
		}
		else
		{
			
			$_SESSION['sellername']='Online';
			
		}
    }
    return $cart_item_data;
}





add_filter('woocommerce_get_item_data','wdm_add_item_meta',10,2);

/**
 * Display information as Meta on Cart page
 * @param  [type] $item_data [description]
 * @param  [type] $cart_item [description]
 * @return [type]            [description]
 */
function wdm_add_item_meta($item_data, $cart_item)
{

  //print_r($cart_item);
    if($cart_item){
      if(array_key_exists('wdm_seller_name', $cart_item))
      {
    //        $custom_details = $cart_item['wdm_buyer_name'];
        if(isset($cart_item['wdm_seller_name']) && isset($cart_item['wdm_seller_display'])){
        if($cart_item['wdm_seller_name']!='' && $cart_item['wdm_seller_display']!=''){
          if(!is_wp_error( $cart_item['wdm_seller_name'])){
                $custom_details2 = $cart_item['wdm_seller_name'];
        }else{
          $custom_details2='';
            }

          $custom_details3 = $cart_item['wdm_seller_display'];
          $item_data[] = array(
              'key'   => 'Seller_ID',
              'value' => ($custom_details2)? $custom_details2 :''
          );
          $item_data[] = array(
              'key'   => 'Seller_name',
              'value' =>($custom_details3)? $custom_details3 :''
          );
          }

            }

      }
}


    if($item_data){
            return $item_data;
      }
}

add_action( 'woocommerce_checkout_create_order_line_item', 'wdm_add_custom_order_line_item_meta',10,4 );

function wdm_add_custom_order_line_item_meta($item, $cart_item_key, $values, $order)
{

    if(array_key_exists('wdm_seller_name', $values))
    {
        $item->add_meta_data('_wdm_seller_name',$values['wdm_seller_name']);
        $item->add_meta_data('_wdm_seller_display',$values['wdm_seller_display']);
		/*$order_id = $order->get_order_number();
		update_post_meta( $order_id, 'wc_order_from', 'catalog');*/
//        $item->add_meta_data('_wdm_buyer_name',$values['wdm_buyer_name']);
    }
}

function force_individual_cart_items( $cart_item_data, $product_id ){

  $unique_cart_item_key = md5( microtime().rand() );
  $cart_item_data['unique_key'] = $unique_cart_item_key;

  return $cart_item_data;

}

add_filter( 'woocommerce_add_cart_item_data','force_individual_cart_items', 10, 2 );


add_action('wp_footer', 'gsc_cookie_for_fundraiser', 100 );
function gsc_cookie_for_fundraiser() {
     $args = array(
                'role'    => 'fundraiser',
                'orderby' => 'user_nicename',
                'order'   => 'ASC'
            );
            $frUsers = get_users( $args );

     $args2 = array(
                'role'    => 'seller',
                'orderby' => 'user_nicename',
                'order'   => 'ASC'
            );
            $sellerUsers = get_users( $args2 );
			session_start();
			$fID=$_SESSION["fridID"];
			$str=$_SESSION["seller_name"];
			$str_n=explode(" ",$str);
			$open=0;
			$user_info = get_userdata($_SESSION["fridID"]);
			if(in_array( 'fundraiser', (array) $user_info->roles ))
			{
				$glow_fund=1;
			}
			else
			{
				$glow_fund=0;
			}
		  $all_seller_found = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $fID,
                                ));
								 $check_result=0;
		  foreach ( $all_seller_found as $sellerUser_found ) { 	
								$s_f_t = get_user_meta( $sellerUser_found->ID, 'first_name', true );	
								$s_l_t = get_user_meta( $sellerUser_found->ID, 'last_name', true );								
								// $s_f_t=$sellerUser_found->first_name; 
								// $s_l_t= $sellerUser_found->last_name;
								 if(strtolower($str_n[0]) == strtolower($s_f_t) && strtolower($str_n[1])== strtolower($s_l_t)) {
									$glow_seller_fundrID = get_user_meta( $sellerUser_found->ID, 'user_registration_seller_fundraiser', true );
									$glow_fundr_status = get_user_meta( $glow_seller_fundrID, 'user_action_status' , true );
									if(empty($glow_fundr_status) || $glow_fundr_status!=2)
									{
									 $check_result=1;
									}
								 }
		  }
    ?>
	  <?php   $current_user2 = wp_get_current_user(); ?>
    <div id="fundraiser-list" frid="<?php if($glow_fund==1) { echo $_SESSION["fridID"]; } else { } ?>">
	<datalist id="fundraiser_fields">
	<?php
            foreach ( $frUsers as $frUser ) { 
		/*	$fundr_status = get_user_meta( $frUser->ID, 'user_action_status' , true ); */
		/*	if(empty($fundr_status) || $fundr_status!=2) */
	/*	if(empty($fundr_status))
			{ */
			?>
                <option value="<?php echo $frUser->ID; ?>" <?php if($_SESSION["fridID"] == $frUser->ID) { echo 'selected'; } if(empty($_SESSION["fridID"]) && $current_user2->ID == $frUser->ID){ echo 'selected'; } ?>><?php echo $frUser->ID; ?></option>
        <?php
        /*    } */
			}
            ?>
	</datalist>		
	</div>


    <div class="new_version" id="seller-list"><?php
            foreach ( $sellerUsers as $sellerUser ) { 
			
			$glow_seller_fundrID = get_user_meta( $sellerUser->ID, 'user_registration_seller_fundraiser', true );
			$glow_fundr_status = get_user_meta( $glow_seller_fundrID, 'user_action_status' , true );
			if(empty($glow_fundr_status) || $glow_fundr_status!=2)
			{
			$sellerUser_fname = get_user_meta( $sellerUser->ID, 'first_name', true );
			$sellerUser_lname = get_user_meta( $sellerUser->ID, 'last_name', true );
			?>
                <option frdata="<?php echo get_user_meta( $sellerUser->ID, 'user_registration_seller_fundraiser', true ); ?>" style="display:none" value="<?php echo $sellerUser->ID; ?>" <?php $s_f=$sellerUser_fname; $s_l= $sellerUser_lname; if(strtolower($str_n[0]) == strtolower($s_f) && strtolower($str_n[1])== strtolower($s_l) && $check_result==1) { $open=1;?> id="selectSeller" <?php } ?>><?php echo $sellerUser_fname . ' ' . $sellerUser_lname; ?></option>
        <?php
            } 
			}
			?><option  frdata="custom" value="custom" class="add-seller" <?php if($open !=1 && !empty($str)){ ?> id="selectCustom" <?php } ?> >Add Seller</option>
	</div>

	<script class="gsc_fr" type="text/javascript">
        var fundraiserNames = jQuery("#fundraiser-list").html();
		var sellerNames = jQuery("#seller-list").html();
		
        jQuery("#fundraiser_field").after(fundraiserNames);
        /* jQuery("select#wc_seller_field, select.mass-order-seller-name, .seller-select-report").append(sellerNames); */
		//        console.log(Cookies.get('fundraiserID'));
	//	var fridValue = Cookies.get('fundraiserID');

		//    console.log(fridValue);
		/*jQuery("#fundraiser_field option").filter(function() {
		return jQuery(this).val() == fridValue;
		}).prop('selected', true);*/
		
		<?php /*  if(!empty($_SESSION["fridID"])){?>
		var fridValue = <?php echo $_SESSION["fridID"]; ?>;
		  jQuery("#fundraiser_field").val(fridValue);
		<?php } */ ?>
		
		<?php 
		 $current_user2 = wp_get_current_user(); 
		   $cID=$current_user2->ID;
		if(!empty($_SESSION["fridID"]) || (!empty($cID) && empty($_SESSION["fridID"]))){
			if(!empty($_SESSION["fridID"]))
			{
				$fundr=$_SESSION["fridID"];
			}
			else if(!empty($cID) && empty($_SESSION["fridID"]))
			{
				$fundr=$cID;
			}
			else
			{
				
			}
			
			$user_info = get_userdata($fundr);
			if(in_array( 'fundraiser', (array) $user_info->roles ))
			{
				$glow_fund=1;
				$fundr_status = get_user_meta( $user_info->ID, 'user_action_status' , true );
				if(empty($fundr_status) || $fundr_status!=2)
				{
					$sCode=1; //active
				}
				else if($fundr_status==2)
				{
					$sCode=2;  //inactive
				}
				else
				{
					
				}
			}
			else
			{
				$glow_fund=0;
			}
			
			?>
		  /* jQuery("#fundraiser_field").val(fridValue); */
		  
		  <?php 
		  
		   if(( $glow_fund==1 && $sCode==1 ) || ( $glow_fund==1 && $sCode==2 && is_user_logged_in() && $_SESSION['sellername']=='Catlog' )) { 
		  ?>
			
			var fridValue =  "<?php echo $fundr; ?>";
		    jQuery("#fundraiser_field").val(<?php if($glow_fund==1) { echo $fundr; } else { } ?>);
			if (fridValue !== "0") {
				jQuery("#wc_seller_field option[frdata="+ fridValue +"]").fadeIn();
			}
			var ajaxurl=<?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>;
			var newFridValue = "<?php echo $fundr; ?>";
			var glow_seller = '<?php echo $_SESSION["seller_name"]; ?>';
			jQuery.ajax({
								url: ajaxurl,
								type: 'POST',
								data : {action:'seller_drop_down_list', request_id:newFridValue, glow_seller:glow_seller},
								success: function (res) {
								
									jQuery("select#wc_seller_field").append(res);
									jQuery('.woocommerce-checkout select#wc_seller_field option#selectSeller').prop('selected', true);	
									jQuery('.woocommerce-checkout select#wc_seller_field option#selectCustom').prop('selected', true);
								}
							});
		<?php } 
		else if($glow_fund==1 && ($sCode==2 && !is_user_logged_in() || ( $sCode==2 && is_user_logged_in() && $_SESSION['sellername']=='Online' ))) { 
		?>
			var ajaxurl=<?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>;
		var newFridValue = "<?php echo $fundr; ?>";
			 jQuery.ajax({
								url: ajaxurl,
								type: 'POST',
								data : {action:'wrong_code_fundraiser', request_id:newFridValue},
								success: function (res) {
								
									if(res.data!="fundrasier")
									{
									jQuery('#error_popup').html(res);
									jQuery("#error_popup").css("display","block");
									jQuery('#fundraiser_field').val("");
									
									}
									else
									{
											console.log(res);
									}
								}
							});
		
		<?php } else { } ?>
		  
		<?php } ?>
		
		
		<?php   $current_user2 = wp_get_current_user(); 
		$cID=$current_user2->ID;
		if(!empty($cID) && empty($_SESSION["fridID"]))
		{
		?>
	/*	var fridValue2="<?php echo $cID; ?>";
		jQuery("#fundraiser_field").val(fridValue2);
		jQuery("#wc_seller_field option[frdata="+ fridValue2 +"]").fadeIn();
   */
		<?php 
		}   
		$current_user2 = wp_get_current_user(); 
		?>
		jQuery("select.mass-order-seller-name option[frdata='<?php echo $current_user2->ID; ?>'], select.seller-select-report option[frdata='<?php echo $current_user2->ID; ?>']").fadeIn();
		/*jQuery('select#wc_seller_field').on('change', function() {
			 var selected = jQuery(this).val();
			 if(selected=="custom")
			 {
				 jQuery('#wc-seller-custom').show();
			 }
		});*/
	/*	jQuery('#fundraiser_field').on('change', function() {
        var newFridValue = jQuery(this).val();
		 jQuery("#wc_seller_field")[0].selectedIndex = 0;

        jQuery("#wc_seller_field option").fadeOut();
        jQuery("#wc_seller_field option[frdata="+ newFridValue +"]").fadeIn();
		jQuery("#wc_seller_field option[frdata=custom]").fadeIn();
	
		}); */
		jQuery('#error_popup').empty();
	jQuery(document).on('click', '#error_popup #m-link', function (e) 
					{
						e.preventDefault();
						jQuery("#error_popup").css("display","none");
						jQuery('#error_popup').empty();
					});
		var ajaxurl=<?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>;
		jQuery('#fundraiser_field').on('change', function() {
			var newFridValue = jQuery(this).val();
			var codelength=jQuery(this).val().length;
		/*   console.log(jQuery(this).val().length); */
		   if(codelength==6)
		   {
			jQuery.ajax({
								url: ajaxurl,
								type: 'POST',
								data : {action:'wrong_code_fundraiser', request_id:newFridValue},
								success: function (res) {
								
									if(res.data!="fundrasier")
									{
									jQuery('#error_popup').html(res);
									jQuery("#error_popup").css("display","block");
								/*	jQuery('#fundraiser_field').val(""); */
									
									}
									else
									{
										/*	console.log(res); */
									}
								}
							});
							jQuery.ajax({
								url: ajaxurl,
								type: 'POST',
								data : {action:'seller_drop_down_list', request_id:newFridValue},
								success: function (res) {
								jQuery("select#wc_seller_field").empty();
									jQuery("select#wc_seller_field").append(res);
								}
							});
		   }
		 jQuery("#wc_seller_field")[0].selectedIndex = 0;
		
        jQuery("#wc_seller_field option").fadeOut();
        jQuery("#wc_seller_field option[frdata="+ newFridValue +"]").fadeIn();
		jQuery("#wc_seller_field option[frdata=custom]").fadeIn();
	
		}); 
		
		<?php $F_ID= $_GET['frid']; if($F_ID!='') { ?>

			jQuery("input.seller_fundraiser_input").val(<?php echo $F_ID; ?>);
		<?php } ?>
	</script>
    <?php if (is_page( array( 22008, 22012 ) ) ) {
     // Empty cart
//    WC()->cart->empty_cart(true);
//    WC()->session->set('cart', array());
                global $woocommerce;
   $items = $woocommerce->cart->get_cart();
   $product_names=array();
    foreach($items as $item => $values) {
      $product_seller = $values['seller_name'];
//      $product_names[]=$_product->post_title;
    }
 /*
    // if you want string then use
    $allproductname=implode("",$product_names);
    return $allproductname;

 */
    print_r($product_seller);
   }
     ?>
	  <style>
	  #fundraiser_field
	  {
		  width:200px;
	  }
#wc_checkbox_field_field .woocommerce-input-wrapper
{
	    width: 100%;
    display: inline-block;
}
#wc_checkbox_field_field  .input-radio 
{
	display:inline-block;
}
#wc_checkbox_field_field  .radio 
{
	    display: inline-block;
    margin-right: 10px;
    padding-left: 5px;
}
#fundraiser_field_field,#wc_seller_field_field
{
	/*display:none;*/
}
#wc_seller_field_custom_field
{
	display:none;
}
#fundraiser_field_field .optional,
#wc_seller_field_field .optional,
#wc_seller_field_custom_field  .optional
{
	display:none;
}
</style>
    <script type='text/javascript'>
        jQuery(document).ready(function($){
			var radioval=jQuery('input[name="wc_checkbox_field"]:checked').val();
            if(radioval=="Yes")
			{
				jQuery("#fundraiser_field_field").css("display","block");
				jQuery("#wc_seller_field_field").css("display","block");
			}
			else{
				jQuery("#fundraiser_field_field").css("display","none");
				jQuery("#wc_seller_field_field").css("display","none");
			}
			jQuery('input[name="wc_checkbox_field"]').change(function () {
				if (jQuery("input[name='wc_checkbox_field']:checked").val() == 'Yes') {
               jQuery("#fundraiser_field_field").css("display","block");
				jQuery("#wc_seller_field_field").css("display","block");
            }
            if (jQuery("input[name='wc_checkbox_field']:checked").val() == 'No') {
               jQuery("#fundraiser_field_field").css("display","none");
				jQuery("#wc_seller_field_field").css("display","none");
				jQuery("#wc_seller_field_custom_field").css("display","none");
            }
			});
			<?php if($open==0) {  
			session_start();
			$fID=$_SESSION["fridID"];
			$str=$_SESSION["seller_name"];
			if(!empty($str)) { ?>
			jQuery("#wc_seller_field_custom").val('<?php echo $str; ?>');
			jQuery("#wc_seller_field_custom").attr('data-seller','added');
			/* jQuery('.woocommerce-checkout select#wc_seller_field option#selectCustom').prop('selected', true); */
			jQuery("#wc_seller_field_custom_field").css("display","block");
			<?php } else { ?>
			jQuery('.woocommerce-checkout select#wc_seller_field option:first').attr('selected','selected');
			<?php } }
else { ?>	
/*jQuery('.woocommerce-checkout select#wc_seller_field option#selectSeller').attr('selected','selected');		*/
	jQuery('.woocommerce-checkout select#wc_seller_field option#selectSeller').prop('selected', true);		
<?php } ?>
			jQuery('select#wc_seller_field').on('change', function() {
				var radioval=this.value;
				var radioval2=jQuery('input[name="wc_checkbox_field"]:checked').val();
				if(radioval=="custom" && radioval2=="Yes")
				{
					jQuery("#wc_seller_field_custom_field").css("display","block");
				}
				else{
					jQuery("#wc_seller_field_custom_field").css("display","none");
				}
			});
            setTimeout(function() {
                $('body').trigger('wc_fragment_refresh');
                $('body').trigger('updated_wc_div');
            }, 100);
        });
//        jQuery("body .quickster-table a.add_selection.button").click(function(){
//            jQuery(this).addClass("loading");
//            setTimeout(function(){
//                window.location.href = "/checkout";
//            }, 4000);
//        });
//        console.log(Cookies.get('massBuyerName'));
//    console.log(Cookies.get('massSellerName'));
//    console.log(Cookies.get('massSellerDisplay'));
    </script>
    <?php
}

//* Add select field to the checkout page
add_action('woocommerce_before_order_notes', 'gsc_add_select_checkout_field');
function gsc_add_select_checkout_field( $checkout ) {
	//global $woocommerce;

    $coupon_code = 'Friends25'; 
	$coupon_code2 = 'glow50';
	//$applied_coupon=$woocommerce->cart->get_applied_coupons();
	/*$WC_Cart = new WC_Cart();
	$applied_coupon= $WC_Cart->get_applied_coupons();
	$coupon = new WC_Coupon( $applied_coupon );
	$id=$coupon->get_id();*/
	foreach ( WC()->cart->get_coupons() as $code => $coupon ) :
	$coupon = new WC_Coupon( $code );
	$id=$coupon->get_id();
	endforeach;

	$no_fundraiser= get_post_meta($id, 'coupon_non_fundraiser', true);
	if($no_fundraiser == "Yes")
	{
	/*if ( $woocommerce->cart->has_discount( $coupon_code ) || $woocommerce->cart->has_discount( $coupon_code2 ) )
	{*/
		$default="No";
	}
	else
	{
		$default="Yes";
	}
	woocommerce_form_field( 'wc_checkbox_field', array(
	    'type'          => 'radio',
	    'class'         => array( 'gsc-drop','are_pu_support' ),
	    'label'         => __( '<span class="c_note">DO NOT CLICK "NO FUNDRAISER & ENTER IN NOTES" GROUP WILL NOT EARN CREDIT.</span><br/>Are you purchasing to support a fundraiser?' ),
		'required' 		=> true,
	    'options' => array(
		'Yes' => 'Yes',
		'No' => 'No'
		),
		'default'=> $default
	),
	$checkout->get_value( 'wc_checkbox_field' ));
	woocommerce_form_field( 'fundraiser_field', array(
	    'type'          => 'number',
		'custom_attributes' => array( 'list' => 'fundraiser_fields' ),
	    'class'         => array( 'gsc-drop' ),
		'id'            => 'fundraiser_field',
	    'label'         => __( 'Fundraiser Code' ),
        'required' 		=> false
	     
        
	),
	$checkout->get_value( 'fundraiser_field' ));

	woocommerce_form_field( 'wc_seller_field', array(
	    'type'          => 'select',
	    'class'         => array( 'gsc-drop' ),
	    'label'         => __( 'Seller Name' ),
       'required' 		=> false,
	    'options'       => array(
        'No-seller'		=> __( 'No Seller')
        )
	),
	$checkout->get_value( 'wc_seller_field' ));
	woocommerce_form_field( 'wc_seller_field_custom', array(
	    'type'          => 'text',
	    'class'         => array( 'gsc-drop' ),
	    'label'         => __( 'Enter Seller Name' ),
       'required' 		=> false
	),
	$checkout->get_value( 'wc_seller_field_custom' ));
	 
}

//* Process the checkout
 //add_action('woocommerce_checkout_process', 'gsc_select_checkout_field_process');
 function gsc_select_checkout_field_process() {
    global $woocommerce;
    // Check if set, if its not set add an error.
	if($_POST['wc_checkbox_field']=="Yes")
	{
		if ($_POST['fundraiser_field'] == "No-Fundraiser" && $_POST['wc_seller_field'] == "No-seller")
		wc_add_notice( 'Please select a Fundraiser & Seller.', 'error' );
	}
}

//* Update the order meta with field value
 add_action('woocommerce_checkout_update_order_meta', 'gsc_select_checkout_field_update_order_meta');
 function gsc_select_checkout_field_update_order_meta( $order_id ) {
	   /* Save New Fields- */
	   session_start();
	   $_SESSION['sellername'] = glow_fc_cart_is_catalog() ? 'Catlog' : 'Online';
	   $user = wp_get_current_user();
	   
	    if(glow_fc_cart_is_catalog())
	   {
				
				//$payment_title = $the_order_valid->get_payment_method_title();
				//update_post_meta( $order_id, 'glow_profit_deduct_metakey', $payment_title);
			if( in_array( 'fundraiser', (array) $user->roles )) 
			{

				$the_order = wc_get_order( $order_id );
				$status = $the_order->get_status();
				$payment_title = $the_order->get_payment_method_title();
				//update_post_meta( $order_id, 'glow_profit_deduct_metakey', $payment_title);
				if ( $the_order->get_date_paid() ) 
				{
						$pDate=$the_order->get_date_paid();
					 update_post_meta( $order_id, 'test_paid_metakey', $pDate);
				}
				else
				{
		              update_post_meta( $order_id, 'test_paid_metakey', 'unPaid');
				}

				// Checkout metadata precedes payment processing, including zero-total orders.
			if ($the_order)
				{
				/* Save meta for cron job */
				$check_first_catlog_order_for_cron_job=get_user_meta( $user->ID, 'wp_meta_first_catlog_order_for_cron_job', true);
				if(empty($check_first_catlog_order_for_cron_job))
				{
					update_user_meta( $user->ID, 'wp_meta_first_catlog_order_for_cron_job', "Yes");
				}
				/* End */
				/* Deduction Code */
				$profit_deduct=get_user_meta( $user->ID, 'glow_profit_deduct_user_metakey_yes', true);
				if(empty($profit_deduct)){
				$rem = 0;
				//$the_order = wc_get_order( $order_id );
				// Iterating through order fee items ONLY
				foreach( $the_order->get_items('fee') as $item_id => $item_fee ){

					// The fee name
					$fee_name = $item_fee->get_name();
					if($fee_name==="Online Profits Deduction")
					{
						// The fee total amount
						$fee_total = $item_fee->get_total();
						$fee_total=abs($fee_total);
						$glow_profit_deduct_amt=get_user_meta( $user->ID,'glow_profit_deduct_save_oncheckout_page', true);
						$glow_profit_deduct_amt_online_totalsales=get_user_meta( $user->ID,'glow_profit_deduct_save_oncheckout_page_total_online_sales', true);
						
						/* Get Meta Invoice Start -- All data save on checkout page */
						$glow_profit_deduct_amt_onlineOrder_rebate=get_user_meta( $user->ID,'glow_profit_deduct_save_oncheckout_page_total_onlineOrder_rebate', true);
						$glow_profit_deduct_amt_shiptoschoolOrder_rebate=get_user_meta( $user->ID,'glow_profit_deduct_save_oncheckout_page_total_shiptoschoolOrder_rebate', true);
						$glow_profit_deduct_amt_onlineOrder_sales=get_user_meta( $user->ID,'glow_profit_deduct_save_oncheckout_page_total_onlineOrder_sales', true);
						$glow_profit_deduct_amt_shiptoschoolOrder_sales=get_user_meta( $user->ID,'glow_profit_deduct_save_oncheckout_page_total_shiptoschoolOrder_sales', true);
						$glow_profit_deduct_amt_shiptoschoolOrder_percentageProfit=get_user_meta( $user->ID,'glow_profit_deduct_save_oncheckout_page_total_shiptoschoolOrder_percentageProfit', true);
						
						/* Meta Invoice End */
						
						if($glow_profit_deduct_amt>$fee_total)
						{
							$rem=$glow_profit_deduct_amt-$fee_total;
							$rem = number_format($rem, 2, '.', '');	
						}
						else{
							$rem = 0;
						}
						
						update_post_meta( $order_id, 'glow_profit_deduct_metakey', $fee_total);
						update_post_meta( $order_id, 'glow_profit_deduct_yes', "Yes");
						update_post_meta( $order_id, 'glow_profit_deduct_metakey_rem', $rem);
						update_post_meta( $order_id, 'glow_profit_deduct_metakey_totalRebate', $glow_profit_deduct_amt);
						update_post_meta( $order_id, 'glow_profit_deduct_metakey_totalOnlineSales', $glow_profit_deduct_amt_online_totalsales);
						
						/*Order Post meta - Invoice*/
						update_post_meta( $order_id, 'glow_profit_deduct_invoice_total_onlineOrder_rebate', $glow_profit_deduct_amt_onlineOrder_rebate);
						update_post_meta( $order_id, 'glow_profit_deduct_invoice_total_shiptoschoolOrder_rebate', $glow_profit_deduct_amt_shiptoschoolOrder_rebate);
						update_post_meta( $order_id, 'glow_profit_deduct_invoice_total_onlineOrder_sales', $glow_profit_deduct_amt_onlineOrder_sales);
						update_post_meta( $order_id, 'glow_profit_deduct_invoice_total_shiptoschoolOrder_sales', $glow_profit_deduct_amt_shiptoschoolOrder_sales);
						update_post_meta( $order_id, 'glow_profit_deduct_invoice_shiptoschoolOrder_percentageProfit', $glow_profit_deduct_amt_shiptoschoolOrder_percentageProfit);
						update_post_meta( $order_id, 'glow_profit_deduct_invoice_implement', "yes");
						/* End */
						
						update_user_meta( $user->ID, 'glow_profit_deduct_user_metakey_amount', $fee_total);
						update_user_meta( $user->ID, 'glow_profit_deduct_user_metakey_ordertype', "Catlog");
						update_user_meta( $user->ID, 'glow_profit_deduct_user_metakey_orderID', $order_id);
						update_user_meta( $user->ID, 'glow_profit_deduct_user_metakey_yes', "Yes");
						
						/*User meta - Invoice*/
						update_user_meta( $user->ID, 'glow_profit_deduct_invoice_total_onlineOrder_rebate', $glow_profit_deduct_amt_onlineOrder_rebate);
						update_user_meta( $user->ID, 'glow_profit_deduct_invoice_total_shiptoschoolOrder_rebate', $glow_profit_deduct_amt_shiptoschoolOrder_rebate);
						update_user_meta( $user->ID, 'glow_profit_deduct_invoice_total_onlineOrder_sales', $glow_profit_deduct_amt_onlineOrder_sales);
						update_user_meta( $user->ID, 'glow_profit_deduct_invoice_total_shiptoschoolOrder_sales', $glow_profit_deduct_amt_shiptoschoolOrder_sales);
						update_user_meta( $user->ID, 'glow_profit_deduct_invoice_shiptoschoolOrder_percentageProfit', $glow_profit_deduct_amt_shiptoschoolOrder_percentageProfit);
						update_user_meta( $user->ID, 'glow_profit_deduct_invoice_implement', "yes");
						/* End */		
						
						if($rem==0)
						{
							
							update_user_meta( $user->ID, 'user_status', 1 );   //paid
							update_user_meta( $user->ID, 'user_rebate', $fee_total );
							update_user_meta( $user->ID, 'user_rebate_remain_glowProfitDeduct', $rem );
						}
						else if(!empty($rem) && $rem>0)
						{
							update_user_meta( $user->ID, 'user_rebate', $fee_total );
							update_user_meta( $user->ID, 'user_rebate_remain_glowProfitDeduct', $rem );
						}
						else
						{
							
						}
						date_default_timezone_set('America/Chicago');
						/*$current_time = date('Y-m-d H:i:s');	*/				 
						$current_date = date('Y-m-d H:i:s a');
						$args=array('post_title'=>'Close Out #'.$user->ID, 'post_type'=>'close_out', 'post_status' => 'publish', 'post_author' => $user->ID);
						$post_id = wp_insert_post($args, true);
					
						if(!empty($post_id))
						{
							update_post_meta($post_id, '_glow_profit_source_order', $order_id);
						update_post_meta($post_id, 'close out_fundraiser_id', $user->ID);
							update_post_meta($post_id, 'close out_fundraiser_date', $current_date); 
							update_post_meta($post_id, 'close out_user_status_amt', $fee_total); // Paid amount
							update_post_meta($post_id, 'close out_user_status_amt_rem', $rem);   // Remaining Amount
							update_post_meta($post_id, 'glow_profit_deduct', "Yes");
							update_post_meta($post_id, 'glow_profit_deduct_total_rebate', $glow_profit_deduct_amt);	// total amount	of Rebate					
							
							update_post_meta($post_id, 'close out_gpd', "Yes"); 
							update_user_meta($user->ID, "user_close_out_value", 1);
							update_user_meta($user->ID, "user_close_out_date_inactive", $current_date);
							update_user_meta($user->ID, 'user_action_status' , 2 );
						}
						
					}
					// The fee total tax amount
					//$fee_total_tax = $item_fee->get_total_tax();
				}
			 }
			}
	   }
		}
	   
	   
	   if(!empty($_SESSION['sellername']))
		{
			/*if ( ! function_exists('WC') || ! WC()->session ) {
        return;
    }*/
    
			update_post_meta( $order_id, 'wc_order_type', esc_attr($_SESSION['sellername']));
			unset($_SESSION['sellername']);
		
		}
		else
		{
			update_post_meta( $order_id, 'wc_order_type', esc_attr($_SESSION['sellername']));
			unset($_SESSION['sellername']);
		}
// 		if ( ! function_exists('WC') || ! WC()->session ) {
//     return;
// }

// $seller_name = WC()->session->get('seller_name');

// if ( ! empty( $seller_name ) ) {
//     update_post_meta( $order_id, 'wc_order_type', $seller_name );

//     // Optional: clear WC session value after order is placed
//     WC()->session->__unset('seller_name');
// }
	if ($_POST['wc_checkbox_field']) update_post_meta( $order_id, 'wc_checkbox_field', esc_attr($_POST['wc_checkbox_field']));
	
	
	if($_POST['wc_checkbox_field']=="Yes")
	{
		if ($_POST['fundraiser_field']) update_post_meta( $order_id, 'fundraiser_field', esc_attr($_POST['fundraiser_field']));
		if ($_POST['wc_seller_field']) update_post_meta( $order_id, 'wc_seller_field', esc_attr($_POST['wc_seller_field']));
		if ($_POST['wc_seller_field_custom']) update_post_meta( $order_id, 'wc_seller_field_custom', esc_attr($_POST['wc_seller_field_custom']));
		
	}
	if($_SESSION['glow_catalog_session']=="Yes")
	{
		
		$new_catalog_order=get_user_meta( $user->ID, 'new_catalog_order_50_percent', true);
		if(empty($new_catalog_order))
		{
			update_user_meta( $user->ID, 'new_catalog_order_50_percent', esc_attr($_SESSION['glow_catalog_session']));
		}
		update_post_meta( $order_id, 'new_catalog_order_50_percent', esc_attr($_SESSION['glow_catalog_session']));
	}
	
  }

//function pk_add_buyer_data($cart_item_data, $product_id, $variation_id)
//{
//    if(!isset($_COOKIE["massBuyerName"])) {
//    if(!isset($_COOKIE[$v_username])) {
//        $cart_item_data['mass_buyer_name'] = sanitize_text_field($_REQUEST['wdm_name']);
//    }
//    }
//
//    return $cart_item_data;
//}
//
//add_filter('woocommerce_get_item_data','wdm_add_item_meta',10,2);

add_action( 'admin_menu', 'linked_url' );
    function linked_url() {
    add_menu_page( 'linked_url', 'Custom Reports', 'read', 'my_slug', '', 'dashicons-text', 1 );
    }

    add_action( 'admin_menu' , 'linkedurl_function' );
    function linkedurl_function() {
    global $menu;
    $menu[1][2] = "/admin-custom-reports/";
    }
