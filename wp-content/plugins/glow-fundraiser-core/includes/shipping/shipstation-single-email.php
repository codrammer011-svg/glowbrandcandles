<?php
/**
 * Customers get one shipping email: the tracking number, which ShipStation adds as a
 * customer note when it marks the order shipped. WooCommerce's "Completed order" email
 * (sent on the status change, by ShipStation or by hand in admin) is not sent.
 */

defined('ABSPATH') || exit;

add_filter('woocommerce_email_enabled_customer_completed_order', '__return_false');
