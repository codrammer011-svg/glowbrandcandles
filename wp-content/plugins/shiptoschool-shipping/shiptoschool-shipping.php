<?php
/**
 * Plugin Name: Ship To School Free Shipping 
 * Plugin URI: 
 * Description: Custom Shipping Method for WooCommerce
 * Version: 1.0.1
 * Author: 3DGeeks Web Solution
 * Author URI: https://3dgeeks.in
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path: /lang
 * Text Domain: shiptoschool-shipping
 */
 
if ( ! defined( 'WPINC' ) && ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Declare WooCommerce HPOS compatibility
 */
add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

/*
 * Register Shipping Method on woocommerce_shipping_init
 */
add_action( 'woocommerce_shipping_init', 'shiptoschool_shipping_method' );

function shiptoschool_shipping_method() {
    if ( ! class_exists( 'ShipToSchool_Shipping_Method' ) ) {
        class ShipToSchool_Shipping_Method extends WC_Shipping_Method {
            /**
             * Constructor for shipping class
             */
            public function __construct() {
                $this->id                 = 'shiptoschool'; 
                $this->method_title       = __( 'ShipToSchool Shipping Methods', 'shiptoschool' );  
                $this->method_description = __( 'Custom Shipping Method for ShipToSchool Orders', 'shiptoschool' ); 

                // Availability & Countries
                $this->availability = 'including';
                $this->countries = array(
                    'US', // United States of America
                    'CA', // Canada
                    'DE', // Germany
                    'GB', // United Kingdom
                    'IT', // Italy
                    'ES', // Spain
                    'BD'  // Croatia
                );

                $this->init();

                $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
                $this->title   = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'Free Shipping', 'shiptoschool' );
            }

            /**
             * Init settings
             */
            function init() {
                // Load the settings API
                $this->init_form_fields(); 
                $this->init_settings(); 

                // Save settings in admin
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }

            /**
             * Define settings fields
             */
            function init_form_fields() { 
                $this->form_fields = array(
                    'enabled' => array(
                        'title'       => __( 'Enable', 'shiptoschool' ),
                        'type'        => 'checkbox',
                        'description' => __( 'Enable this shipping method.', 'shiptoschool' ),
                        'default'     => 'yes'
                    ),
                    'title' => array(
                        'title'       => __( 'Title', 'shiptoschool' ),
                        'type'        => 'text',
                        'description' => __( 'Title to display on site', 'shiptoschool' ),
                        'default'     => __( 'Free Shipping', 'shiptoschool' )
                    ),
                    'amtlimit' => array(
                        'title'       => __( 'Amount Limit', 'shiptoschool' ),
                        'type'        => 'text',
                        'description' => __( 'Minimum amount limit required for free shipping', 'shiptoschool' ),
                        'default'     => __( '100', 'shiptoschool' )
                    ),
                );
            }

            /**
             * Calculate shipping rate
             */
            public function calculate_shipping( $package = array() ) {
                $cost = 0;
                
                $rate = array(
                    'id'    => $this->id,
                    'label' => $this->title,
                    'cost'  => $cost
                );

                $this->add_rate( $rate );
            }
        }
    }
}

function add_shiptoschool_shipping_method( $methods ) {
    $methods['shiptoschool'] = 'ShipToSchool_Shipping_Method';
    return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'add_shiptoschool_shipping_method' );

/**
 * Validate minimum order amount for Ship to School
 */
function shiptoschool_validate_order( $posted ) {
    if ( ! function_exists( 'WC' ) || ! WC()->session || ! WC()->cart || ! WC()->shipping ) {
        return;
    }

    if ( WC()->session->get( 'installment_fee' ) != 1 ) {
        $total = WC()->cart->cart_contents_total;
        $packages = WC()->shipping->get_packages();
        $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
        
        if ( is_array( $chosen_methods ) && in_array( 'shiptoschool', $chosen_methods ) ) {
            foreach ( $packages as $i => $package ) {
                if ( isset( $chosen_methods[ $i ] ) && $chosen_methods[ $i ] != 'shiptoschool' ) {
                    continue;
                }

                $amt_limit = 100;
                $title = __( 'Free Shipping', 'shiptoschool' );

                $settings = get_option( 'woocommerce_shiptoschool_settings', null );
                if ( is_array( $settings ) ) {
                    if ( ! empty( $settings['amtlimit'] ) ) {
                        $amt_limit = (int) $settings['amtlimit'];
                    }
                    if ( ! empty( $settings['title'] ) ) {
                        $title = $settings['title'];
                    }
                }

                $amt_limit_str = '$' . $amt_limit;
                
                if ( $total < $amt_limit ) {
                    $message = sprintf( __( 'Sorry, %s for %s', 'shiptoschool' ), $amt_limit_str, $title );
                    $messageType = 'error';

                    if ( ! wc_has_notice( $message, $messageType ) ) {
                        wc_add_notice( $message, $messageType );
                    }
                }
            }      
        }
    }
}

add_action( 'woocommerce_review_order_before_cart_contents', 'shiptoschool_validate_order', 10 );
add_action( 'woocommerce_after_checkout_validation', 'shiptoschool_validate_order', 10 );
