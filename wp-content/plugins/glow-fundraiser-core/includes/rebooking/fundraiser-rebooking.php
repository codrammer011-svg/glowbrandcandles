<?php
/** Fundraiser and seller rebooking are part of GLOW Fundraiser Core, not a separate plugin. */
defined('ABSPATH') || exit;

require_once __DIR__ . '/class-glow-fc-rebooking-campaigns.php';
require_once __DIR__ . '/class-glow-fc-rebooking.php';
require_once __DIR__ . '/class-glow-fc-seller-rebooking.php';
Glow_FC_Rebooking::boot();
Glow_FC_Seller_Rebooking::boot();
