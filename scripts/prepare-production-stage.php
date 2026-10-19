<?php
if(PHP_SAPI!=='cli')exit;
set_error_handler(function($severity,$message){throw new RuntimeException($message);});
$source=$argv[1];$target=$argv[2];
$in=fopen($source,'rb');$out=fopen($target,'xb');
if(!$in||!$out)throw new Exception('Cannot open staging files');
fwrite($out,"SET FOREIGN_KEY_CHECKS=0;\nSET NAMES utf8mb4;\n");
$keep=true;$tables=[];
while(($line=fgets($in))!==false){
 if(preg_match('/^(CREATE DATABASE|USE )/',$line))continue;
 if(preg_match('/^(?:CREATE TABLE|INSERT INTO|ALTER TABLE) `([^`]+)`/',$line,$m)||preg_match('/^-- .* (?:table|Table) `([^`]+)`/',$line,$m)){
  $name=$m[1];
  $keep=(bool)preg_match('/^wp_(posts|postmeta|users|usermeta|comments|commentmeta|options|terms|termmeta|term_taxonomy|term_relationships|glow_seller_orders|glow_seller_order_items|woocommerce_order_items|woocommerce_order_itemmeta|woocommerce_payment_tokens|woocommerce_payment_tokenmeta|woocommerce_downloadable_product_permissions|wc_order_stats|wc_order_product_lookup|wc_order_tax_lookup|wc_order_coupon_lookup|wc_customer_lookup|wc_orders|wc_order_addresses|wc_orders_meta|wc_order_operational_data|pms_payments|pms_paymentmeta|pms_member_subscriptions|pms_member_subscriptionmeta|ur_membership_orders|ur_membership_ordermeta)$/',$name);
  if($keep)$tables[$name]=true;
 }
 if($keep||preg_match('/^(COMMIT;|SET |\/\*!)/',$line))fwrite($out,$line);
}
fwrite($out,"\nSET FOREIGN_KEY_CHECKS=1;\n");fclose($out);fclose($in);
echo 'Staging tables: '.count($tables)."\n";
