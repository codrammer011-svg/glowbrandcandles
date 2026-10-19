<?php
/** CLI only: merge production business data from an isolated staging schema. */
if(PHP_SAPI!=='cli')exit;
mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
$config=json_decode(file_get_contents($argv[1]),true,512,JSON_THROW_ON_ERROR);
foreach(['database','stage'] as $key)if(!preg_match('/^[a-zA-Z0-9_]+$/',$config[$key]))throw new Exception('Invalid schema');
$dst=$config['database'];$src=$config['stage'];if($src===$dst)throw new Exception('Source equals target');
if(!in_array($config['host'],['127.0.0.1','localhost'],true))throw new Exception('Local database only');
$db=new mysqli($config['host'],$config['user'],$config['password'],$dst,$config['port']);$db->set_charset('utf8mb4');
$db->query("SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO'");
function rows($sql){global $db;return $db->query($sql)->fetch_all(MYSQLI_ASSOC);}
function val($sql){global $db;return $db->query($sql)->fetch_row()[0];}
function cols($schema,$table){return array_column(rows("SHOW COLUMNS FROM `$schema`.`$table`"),'Field');}
function ids($array){return $array?implode(',',array_map('intval',$array)):'0';}
function fingerprint($sql){global $db;$h=hash_init('sha256');$r=$db->query($sql,MYSQLI_USE_RESULT);while($row=$r->fetch_assoc())hash_update($h,serialize($row));$r->free();return hash_final($h);}
$business="'shop_order','shop_order_refund','fundraiser','close_out','payment_history','feedback','wc_user_membership','shop_coupon','wpsc_cart_orders'";
$protectedIds=ids(array_column(rows("SELECT ID FROM wp_posts WHERE post_type NOT IN ($business)"),'ID'));
function protectedHash(){global $protectedIds;return [fingerprint("SELECT * FROM wp_posts WHERE ID IN ($protectedIds) ORDER BY ID"),fingerprint("SELECT * FROM wp_postmeta WHERE post_id IN ($protectedIds) ORDER BY meta_id"),fingerprint("SELECT * FROM wp_term_relationships WHERE object_id IN ($protectedIds) ORDER BY object_id,term_taxonomy_id"),fingerprint('SELECT * FROM wp_options ORDER BY option_id')];}
if(val("SELECT COUNT(*) FROM `$src`.wp_options WHERE option_name='woocommerce_custom_orders_table_enabled' AND option_value='yes'"))throw new Exception('HPOS-only source is unsupported; no changes made');
$since=$db->real_escape_string($config['since']);
$selected=rows("SELECT * FROM `$src`.wp_posts WHERE post_type IN ($business) AND (post_type NOT IN ('shop_order','shop_order_refund') OR post_date >= '$since' OR post_modified >= '$since') ORDER BY ID");
$orderIds=[];foreach($selected as $p)if(in_array($p['post_type'],['shop_order','shop_order_refund'],true))$orderIds[]=$p['ID'];
// Include parents and refunds together, even when the parent was created before the cutoff.
if($orderIds){$extra=rows("SELECT * FROM `$src`.wp_posts WHERE (post_type='shop_order_refund' AND post_parent IN (".ids($orderIds).")) OR ID IN(SELECT post_parent FROM `$src`.wp_posts WHERE ID IN(".ids($orderIds).") AND post_parent>0)");foreach($extra as $p)$selected[]=$p;}
$selected=array_column($selected,null,'ID');$orderIds=[];foreach($selected as $p)if(in_array($p['post_type'],['shop_order','shop_order_refund'],true))$orderIds[]=$p['ID'];
$map=[];if(val("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$dst' AND table_name='glow_post_map'"))foreach(rows('SELECT * FROM glow_post_map') as $r)$map[(int)$r['old_id']]=(int)$r['new_id'];
foreach(rows("SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='_glow_production_original_id'") as $r)$map[(int)$r['meta_value']]=(int)$r['post_id'];
$occupied=array_column(rows('SELECT ID,post_type FROM wp_posts'), 'post_type','ID');$next=max(array_keys($occupied))+1;
$next=max($next,1+(int)val("SELECT MAX(ID) FROM `$src`.wp_posts"));
foreach($selected as $id=>$p){
 if(isset($map[$id])&&isset($occupied[$map[$id]])&&$occupied[$map[$id]]!==$p['post_type'])throw new Exception("Existing ID mapping conflicts: $id");
 if(!isset($map[$id])){$map[$id]=!isset($occupied[$id])||$occupied[$id]===$p['post_type']?(int)$id:$next++;}
}
$localSku=[];$localSlug=[];
foreach(rows("SELECT p.ID,p.post_name,m.meta_value sku FROM wp_posts p LEFT JOIN wp_postmeta m ON m.post_id=p.ID AND m.meta_key='_sku' WHERE p.post_type IN('product','product_variation')") as $r){if($r['sku']!=='')$localSku[$r['sku']]=$r['ID'];$localSlug[$r['post_name']]=$r['ID'];}
$products=[];foreach(rows("SELECT p.ID,p.post_name,m.meta_value sku FROM `$src`.wp_posts p LEFT JOIN `$src`.wp_postmeta m ON m.post_id=p.ID AND m.meta_key='_sku' WHERE p.post_type IN('product','product_variation')") as $r)$products[$r['ID']]=$localSku[$r['sku']]??$localSlug[$r['post_name']]??0;
$report=['since'=>$config['since'],'orders_and_refunds'=>count($orderIds),'context_posts'=>count($selected)-count($orderIds),'users'=>(int)val("SELECT COUNT(*) FROM `$src`.wp_users"),'target'=>$dst,'applied'=>false];
if(empty($config['apply'])){echo json_encode($report,JSON_PRETTY_PRINT)."\nPreview only. Use -Apply to import.\n";exit;}
if(val("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$dst' AND table_name LIKE 'wp\\_%' AND engine IS NOT NULL AND engine<>'InnoDB'"))throw new Exception('Transactional InnoDB tables are required');
// This script only continues on an existing, prepared local mirror.
if(!val("SELECT COUNT(*) FROM wp_options WHERE option_name IN('home','siteurl') AND option_value LIKE '%glow.local%'"))throw new Exception('Target does not look like the local Glow site');
if(val("SELECT COUNT(*) FROM `$src`.wp_users s JOIN wp_users l ON l.ID=s.ID WHERE BINARY l.user_login<>BINARY s.user_login"))throw new Exception('User ID collision: use a full migration instead');
$db->query('CREATE TABLE IF NOT EXISTS glow_post_map(old_id BIGINT UNSIGNED PRIMARY KEY,new_id BIGINT UNSIGNED NOT NULL) ENGINE=InnoDB');
function insertRow($table,$r){global $db;$c=array_keys($r);$s=$db->prepare("INSERT INTO `$table` (`".implode('`,`',$c)."`) VALUES(".implode(',',array_fill(0,count($c),'?')).")");$v=array_values($r);$s->bind_param(str_repeat('s',count($v)),...$v);$s->execute();$id=$db->insert_id;$s->close();return $id;}
function remapMeta($value,$key){global $map,$products;
 if(in_array($key,['_product_id','_variation_id','product_id','variation_id'],true))return (string)($products[(int)$value]??0);
 if(preg_match('/order_?id|post_?id|um_unique_payment_id/i',$key)){
  if(ctype_digit((string)$value))return (string)($map[(int)$value]??$value);
  if(preg_match('/^\d+(,\d+)+$/',(string)$value))return implode(',',array_map(fn($v)=>$map[(int)$v]??$v,explode(',',$value)));
  $decoded=@unserialize($value,['allowed_classes'=>false]);
  if(is_array($decoded)&&!preg_match('/[Rr]:[0-9]+;/',$value)){
   $walk=function($v)use(&$walk,$map){if(is_array($v)){foreach($v as &$item)$item=$walk($item);return $v;}return (is_int($v)||ctype_digit((string)$v))?($map[(int)$v]??$v):$v;};
   return serialize($walk($decoded));
  }
 }
 if($key==='_wcpdf_invoice_number_data'){$v=@unserialize($value,['allowed_classes'=>false]);if(is_array($v)&&isset($v['order_id'])){$v['order_id']=$map[$v['order_id']]??$v['order_id'];return serialize($v);}}
 return $value;
}
$sourceIds=ids(array_keys($selected));$targetIds=ids(array_map(fn($id)=>$map[$id],array_keys($selected)));$sourceOrderIds=ids($orderIds);$targetOrderIds=ids(array_map(fn($id)=>$map[$id],$orderIds));
$db->begin_transaction();$before=protectedHash();try{
 foreach($selected as $id=>$p){$old=$map[$id];$db->query("DELETE FROM wp_postmeta WHERE post_id=$old");$db->query("DELETE FROM wp_posts WHERE ID=$old");$p['ID']=$old;$p['post_parent']=$map[$p['post_parent']]??$p['post_parent'];insertRow('wp_posts',$p);$db->query("INSERT INTO glow_post_map VALUES(".(int)$id.",$old) ON DUPLICATE KEY UPDATE new_id=VALUES(new_id)");}
 foreach(rows("SELECT * FROM `$src`.wp_postmeta WHERE post_id IN($sourceIds)") as $r){unset($r['meta_id']);$r['post_id']=$map[$r['post_id']];$r['meta_value']=remapMeta($r['meta_value'],$r['meta_key']);insertRow('wp_postmeta',$r);}
 foreach($selected as $id=>$p)if($map[$id]!=$id)insertRow('wp_postmeta',['post_id'=>$map[$id],'meta_key'=>'_glow_production_original_id','meta_value'=>$id]);
 // Users are shared fundraiser/seller/rep context: refresh all source users, keep local-only users.
 foreach(rows("SELECT * FROM `$src`.wp_users") as $r){$id=(int)$r['ID'];$db->query("DELETE FROM wp_usermeta WHERE user_id=$id");$db->query("DELETE FROM wp_users WHERE ID=$id");insertRow('wp_users',$r);}
 foreach(rows("SELECT * FROM `$src`.wp_usermeta") as $r){unset($r['umeta_id']);$r['meta_value']=remapMeta($r['meta_value'],$r['meta_key']);insertRow('wp_usermeta',$r);}
 $db->query("DELETE m FROM wp_woocommerce_order_itemmeta m JOIN wp_woocommerce_order_items i ON i.order_item_id=m.order_item_id WHERE i.order_id IN($targetOrderIds)");
 $db->query("DELETE FROM wp_woocommerce_order_items WHERE order_id IN($targetOrderIds)");
 $itemMap=[];foreach(rows("SELECT * FROM `$src`.wp_woocommerce_order_items WHERE order_id IN($sourceOrderIds)") as $r){$old=$r['order_item_id'];unset($r['order_item_id']);$r['order_id']=$map[$r['order_id']];$itemMap[$old]=insertRow('wp_woocommerce_order_items',$r);}
 foreach(rows("SELECT m.* FROM `$src`.wp_woocommerce_order_itemmeta m JOIN `$src`.wp_woocommerce_order_items i ON i.order_item_id=m.order_item_id WHERE i.order_id IN($sourceOrderIds)") as $r){unset($r['meta_id']);$r['order_item_id']=$itemMap[$r['order_item_id']];$r['meta_value']=remapMeta($r['meta_value'],$r['meta_key']);insertRow('wp_woocommerce_order_itemmeta',$r);}
 $db->query("DELETE m FROM wp_commentmeta m JOIN wp_comments c ON c.comment_ID=m.comment_id WHERE c.comment_post_ID IN($targetIds)");$db->query("DELETE FROM wp_comments WHERE comment_post_ID IN($targetIds)");
 $commentMap=[];foreach(rows("SELECT * FROM `$src`.wp_comments WHERE comment_post_ID IN($sourceIds) ORDER BY comment_ID") as $r){$old=$r['comment_ID'];unset($r['comment_ID']);$r['comment_post_ID']=$map[$r['comment_post_ID']];$r['comment_parent']=$commentMap[$r['comment_parent']]??0;$commentMap[$old]=insertRow('wp_comments',$r);}
 foreach(rows("SELECT m.* FROM `$src`.wp_commentmeta m JOIN `$src`.wp_comments c ON c.comment_ID=m.comment_id WHERE c.comment_post_ID IN($sourceIds)") as $r){unset($r['meta_id']);$r['comment_id']=$commentMap[$r['comment_id']];insertRow('wp_commentmeta',$r);}
 // Refresh shared seller/payment snapshots, but never replace catalog or design tables.
 $sourceTables=array_column(rows("SHOW TABLES FROM `$src`"),"Tables_in_$src");
 foreach(['wp_glow_seller_orders','wp_glow_seller_order_items','wp_woocommerce_payment_tokens','wp_woocommerce_payment_tokenmeta','wp_pms_payments','wp_pms_paymentmeta','wp_pms_member_subscriptions','wp_pms_member_subscriptionmeta','wp_wc_customer_lookup'] as $table){
  if(!in_array($table,$sourceTables,true))continue;
  if(!val("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='$dst' AND table_name='$table'"))throw new Exception("Missing target table $table");
  $common=array_intersect(cols($src,$table),cols($dst,$table));$db->query("DELETE FROM `$table`");
  foreach(rows("SELECT * FROM `$src`.`$table`") as $r){$r=array_intersect_key($r,array_flip($common));foreach(['wc_order_id'] as $k)if(isset($r[$k]))$r[$k]=$map[$r[$k]]??$r[$k];foreach(['product_id','variation_id'] as $k)if(isset($r[$k]))$r[$k]=$products[$r[$k]]??0;insertRow($table,$r);}
 }
 foreach(['wp_wc_order_stats','wp_wc_order_product_lookup','wp_wc_order_tax_lookup','wp_wc_order_coupon_lookup','wp_woocommerce_downloadable_product_permissions'] as $table){
  if(!in_array($table,$sourceTables,true))continue;
  $db->query("DELETE FROM `$table` WHERE order_id IN($targetOrderIds)");
  foreach(rows("SELECT * FROM `$src`.`$table` WHERE order_id IN($sourceOrderIds)") as $r){$r['order_id']=$map[$r['order_id']];if(isset($r['order_item_id']))$r['order_item_id']=$itemMap[$r['order_item_id']]??$r['order_item_id'];foreach(['parent_id'] as $k)if(isset($r[$k]))$r[$k]=$map[$r[$k]]??$r[$k];foreach(['product_id','variation_id'] as $k)if(isset($r[$k]))$r[$k]=$products[$r[$k]]??0;insertRow($table,$r);}
 }
 if(protectedHash()!==$before)throw new Exception('Protected local design/catalog changed');
 $expected=val("SELECT SUM(CAST(meta_value AS DECIMAL(18,2))) FROM `$src`.wp_postmeta WHERE post_id IN($sourceOrderIds) AND meta_key='_order_total'");
 $actual=val("SELECT SUM(CAST(meta_value AS DECIMAL(18,2))) FROM wp_postmeta WHERE post_id IN($targetOrderIds) AND meta_key='_order_total'");
 if($expected!==$actual)throw new Exception('Order total verification failed');
 $db->commit();$report['applied']=true;$report['protected_unchanged']=true;$report['order_total']=$actual;file_put_contents($config['report'],json_encode($report,JSON_PRETTY_PRINT));echo json_encode($report,JSON_PRETTY_PRINT)."\n";
}catch(Throwable $e){$db->rollback();throw $e;}
