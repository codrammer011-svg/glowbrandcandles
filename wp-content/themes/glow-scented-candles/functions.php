<?php
/*
 * Glow Scented Candles – Child Theme Functions
 * Modular split: each inc/*.php file handles one functional area.
 * DO NOT add code here — edit the relevant inc/ file instead.
 */
$glow_inc = trailingslashit( get_stylesheet_directory() ) . 'inc/';
require_once $glow_inc . 'theme-setup.php';
require_once $glow_inc . 'elementor.php';
require_once $glow_inc . 'auth-login.php';
require_once $glow_inc . 'email-queue.php';
require_once $glow_inc . 'fundraiser-registration.php';
require_once $glow_inc . 'catalog-order.php';
require_once $glow_inc . 'dashboard.php';
require_once $glow_inc . 'seller-forms.php';
require_once $glow_inc . 'shop-display.php';
require_once $glow_inc . 'product-categories.php';
require_once $glow_inc . 'elementor-tags.php';
require_once $glow_inc . 'shop-pagination.php';
require_once $glow_inc . 'cart-checkout.php';
require_once $glow_inc . 'product-meta.php';
