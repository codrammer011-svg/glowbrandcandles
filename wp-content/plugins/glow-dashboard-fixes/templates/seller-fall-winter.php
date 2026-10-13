<?php
/**
 * Template Name: Seller — Fall-Winter Brochure Order
 */
defined( 'ABSPATH' ) || exit;

$glow_title = 'Fall-Winter Brochure Order';

$glow_sections = array(

   array(
      'menu'     => 'Classic Candles',
      'heading'  => 'Classic Candles $15.00',
      'category' => 'classic-candles',
      ),

   array(
      'menu'     => 'Signature 2 Wick',
      'heading'  => 'Signature 2 Wick Candles $25.00',
      'category' => '2-wick-signature-candles',
      ),

   array(
      'menu'     => 'Large Wax Melts',
      'heading'  => 'Large Wax Melts $15.00',
      'category' => 'large-wax-melts-6-cubes',
      ),

   array(
      'menu'     => 'Room Sprays',
      'heading'  => 'Room Sprays $15.00',
      'category' => 'room-sprays-5oz',
      ),

   array(
      'menu'     => 'Essential Oils',
      'heading'  => 'Essential Oils $15.00',
      'category' => 'essential-oils',
      ),

   array(
      'menu'     => 'Let it Snow',
      'heading'  => 'Let it Snow $40.00',
      'category' => 'let-it-snow-candle-16-oz',
      /* allow list nahi — poori category */
   ),

   array(
      'menu'     => 'Celebrate The Moments',
      'heading'  => 'Celebrate the Moments $25.00',
      'category' => 'celebrate-the-moments',
      ),

);

$glow_catalog = 'fall-winter';

include WP_PLUGIN_DIR . '/Fundraiser-Modules/templates/glow-order-grid.php';
include WP_PLUGIN_DIR . '/Fundraiser-Modules/templates/glow-tour.php';