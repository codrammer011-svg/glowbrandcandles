<?php

namespace quickster;

class QS_Ajax {

	private static $instance = null;

	/**
	 * QS_Ajax constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_quickster_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_quickster_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_add_to_cart', array( $this, 'add_to_cart' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
	}

	/**
	 * return instance from class
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * enqueue ajax assets and localize script
	 */
	public function load_assets() {
		wp_enqueue_style( 'datatable-css', QUICKSTER_URL . '/assets/public/datatables/datatables.min.css' );
		wp_enqueue_style( 'quickster-public-css', QUICKSTER_URL . '/assets/public/quickster-public.css' );

		wp_enqueue_script(
			'datatable-js',
			QUICKSTER_URL . '/assets/public/datatables/datatables.min.js',
			array( 'jquery' ),
			false,
			true
		);

		wp_enqueue_script(
			'jquery-number-js',
			QUICKSTER_URL . '/assets/public/jquery.number.js',
			array( 'jquery' ),
			false,
			true
		);

		wp_enqueue_script(
			'quickster-public-js',
			QUICKSTER_URL . '/assets/public/quickster-public.js',
			array(
				'jquery',
				'datatable-js',
				'jquery-number-js'
			),
			false,
			true
		);

		$additional_settings = get_option( 'quickster_additional_settings' );

		if ( is_array( $additional_settings ) && array_key_exists( 'toggle_cart_message', $additional_settings ) && $additional_settings['toggle_cart_message'] == 'on' ) {
			$cart_message = array(
				'wysiwyg_cart_message'          => isset( $additional_settings['wysiwyg_cart_message'] ) ? $additional_settings['wysiwyg_cart_message'] : '',
				'cart_message_background_color' => isset( $additional_settings['cart_message_background_color'] ) ? $additional_settings['cart_message_background_color'] : '#4CAF50',
				'cart_message_font_color'       => isset( $additional_settings['cart_message_font_color'] ) ? $additional_settings['cart_message_font_color'] : '#ffffff',
				'go_to_cart'                    => __( 'Go to Cart', 'quick-orders-for-woocommerce' ),
				'cart_url'                      => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ),
			);
		} else {
			$cart_message = false;
		}

		$container_height = ( is_array( $additional_settings ) && isset( $additional_settings['infinity_scroll_container_height'] ) )
			? $additional_settings['infinity_scroll_container_height']
			: 0;

		$infinity_status = ( is_array( $additional_settings ) && isset( $additional_settings['toggle_infinity_scroll'] ) )
			? $additional_settings['toggle_infinity_scroll']
			: 0;

		wp_localize_script( 'quickster-public-js', 'quickster', array(
			'ajax_url'                         => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'                       => wp_create_nonce( 'quick_order' ),
			'home_url'                         => home_url(),
			'cart_url'                         => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ),
			'cart_message'                     => $cart_message,
			'infinity_scroll_container_height' => $container_height,
			'search_placeholder'               => __( 'Search..', 'quick-orders-for-woocommerce' ),
			'activate_infinity'                => $infinity_status,
			'currency'                         => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '$',
			'currency_position'                => get_option( 'woocommerce_currency_pos', 'left' ),
			'number_decimals'                  => get_option( 'woocommerce_price_num_decimals', 2 ),
			'decimal_separator'                => get_option( 'woocommerce_price_decimal_sep', '.' ),
			'thousand_separator'               => get_option( 'woocommerce_price_thousand_sep', ',' ),
			'bulk_price_text'                  => get_option( 'bm_global_price_label', '' )
		) );
	}

	/**
	 * AJAX add selection to cart
	 */
	public function add_to_cart() {
		// Verify nonce
		if ( ! check_ajax_referer( 'quick_order', 'security', false ) ) {
			wp_send_json_error( array( 'message' => 'Invalid security token' ), 403 );
		}

		if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
			wp_send_json_error( array( 'message' => 'WooCommerce Cart not available' ), 500 );
		}

		$products_raw = isset( $_POST['products'] ) ? $_POST['products'] : '';
		$quantities   = isset( $_POST['quantities'] ) ? $_POST['quantities'] : array();

		$products = array();
		if ( is_array( $products_raw ) ) {
			$products = array_map( 'intval', $products_raw );
		} elseif ( is_string( $products_raw ) && $products_raw !== '' ) {
			$products = array_map( 'intval', array_map( 'trim', explode( ',', $products_raw ) ) );
		}

		$added_count = 0;
		$items_added = array();

		if ( ! empty( $quantities ) && is_array( $quantities ) ) {
			foreach ( $quantities as $item ) {
				$product_id = isset( $item['id'] ) ? absint( $item['id'] ) : 0;
				$quantity   = isset( $item['quantity'] ) ? (int) $item['quantity'] : 0;

				// If quantity is missing/empty but product was checked, default to 1
				if ( $quantity <= 0 && in_array( $product_id, $products, true ) ) {
					$quantity = 1;
				}

				if ( $product_id > 0 && $quantity > 0 ) {
					if ( ! empty( $products ) && ! in_array( $product_id, $products, true ) ) {
						continue;
					}

					$product_obj = wc_get_product( $product_id );
					if ( $product_obj ) {
						$cart_item_data = array();
						// Brochure pages submit seller_name, including an intentionally blank name.
						// Persist context on the line itself instead of depending on browser cookies.
						if ( in_array( 'fundraiser', (array) wp_get_current_user()->roles, true )
							&& ( array_key_exists( 'seller_name', $_POST ) || array_key_exists( 'seller_name', $item ) ) ) {
							$seller = sanitize_text_field( wp_unslash( $item['seller_name'] ?? $_POST['seller_name'] ?? '' ) );
							$cart_item_data = array(
								'glow_order_type' => 'Catlog',
								'wdm_seller_name' => $seller,
								'wdm_seller_display' => $seller,
								'restore_saved_order' => 'Yes',
							);
						}
						if ( $product_obj->is_type( 'variation' ) ) {
							$variation_id = $product_id;
							$parent_id = $product_obj->get_parent_id();
							$cart_item_key = WC()->cart->add_to_cart( $parent_id, $quantity, $variation_id, array(), $cart_item_data );
						} else {
							$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, 0, array(), $cart_item_data );
						}

						if ( $cart_item_key ) {
							$added_count += $quantity;
							$items_added[] = $product_id;
						}
					}
				}
			}
		}

		if ( $added_count > 0 ) {
			wp_send_json_success( array(
				'added'      => $added_count,
				'cart_count' => WC()->cart->get_cart_contents_count(),
				'cart_url'   => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ),
				'message'    => sprintf( _n( '%d item added to cart.', '%d items added to cart.', $added_count, 'quick-orders-for-woocommerce' ), $added_count ),
			) );
		} else {
			wp_send_json_error( array(
				'message' => __( 'No items were selected or added. Please check a product and enter quantity.', 'quick-orders-for-woocommerce' ),
			) );
		}
	}
}
