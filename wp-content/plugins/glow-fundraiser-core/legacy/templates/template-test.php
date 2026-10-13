<?php
/**
 * Template Name: Test Page
 */
 get_header();

require_once( get_template_directory() . '/phpqrcode/qrlib.php' );
$user_id=206552;
$text = "https://glowbrandscandles.com/profile/".$user_id;
$arrayUploadPath = wp_upload_dir();
$fileUploadSubDir = $arrayUploadPath['basedir']."/qrcodes/image_".$user_id.".png";
QRcode::png($text, $fileUploadSubDir, QR_ECLEVEL_L, 3, 4);
$fileUpload_full_link = $arrayUploadPath['baseurl']."/qrcodes/image_".$user_id.".png";
if(!empty($fileUpload_full_link)) {
	?>
	<img src="<?php echo $fileUpload_full_link; ?>" />
	<?php
}
/* Register link for dashboard */
			$text2 = "https://glowbrandscandles.com/seller-register/?frid=" . $user_id;

$arrayUploadPath2 = wp_upload_dir();
$tempFile = $arrayUploadPath2['basedir'] . "/qrcodes/temp_" . $user_id . ".png";
$fileUploadSubDir2 = $arrayUploadPath2['basedir'] . "/qrcodes/image_seller_register" . $user_id . ".png";

// 1. Generate QR code to temp file
QRcode::png($text2, $tempFile, QR_ECLEVEL_L, 3, 4);

// 2. Resize to 111x111 px with GD
$src = imagecreatefrompng($tempFile);
$dst = imagecreatetruecolor(111, 111);
imagecopyresized($dst, $src, 0, 0, 0, 0, 111, 111, imagesx($src), imagesy($src));
imagepng($dst, $fileUploadSubDir2);

// 3. Cleanup
imagedestroy($src);
imagedestroy($dst);
unlink($tempFile);
			
			$fileUpload_full_link2 = $arrayUploadPath2['baseurl']."/qrcodes/image_seller_register".$user_id.".png";
			if(!empty($fileUpload_full_link2)) {
			?>
			<img width="111" height="111" src="<?php echo $fileUpload_full_link2; ?>" />
			<?php
			}
?>

<?php
			$arr_FIDD=array();
			$c=0;
			$arg=array('post_type'=>'close_out','posts_per_page' => 20,'order' => 'DESC');
			$table_query= new WP_Query();
			$table_query->query($arg);
			if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
					$postid = $table_query->post->ID; 
					$ID = get_post_meta( $postid, 'close out_fundraiser_id', true );
					$order_quantity_test=0;
					$args = array(
									'status' => array('wc-waiting-call'),
									'meta_key' => 'fundraiser_field',
									'meta_value' => $ID,
									'limit' => -1
							);
					$orders = wc_get_orders($args);
					$totalOrdersQ=count($orders);
					$mID=get_user_meta($ID, "wc_ship_mergeOrderID", true);
					$mQanty=get_user_meta($ID, "wc_ship_mergeOrderQuanty", true);
					$first_catlog_order=get_user_meta($ID, "wc_first_catlog_order", true);
					
					if(empty($mID) && empty($mQanty) && ($first_catlog_order=="Yes") && !empty($totalOrdersQ))
					{
						if(!in_array($ID,$arr_FIDD)){
							$arr_FIDD[$c]=$ID;
							$c++;
						}
					} 
					
				endwhile;
			endif;
			print_r($arr_FIDD);
?>
<?php 
function get_fundraiser_30days_completed_campaign_test()
{
    /*
    Cron Job: Trigger when 30 days are completed after fundraiser end date
    */

    $queue = array();

    $args = array(
        'role'    => 'Fundraiser',
        'orderby' => 'user_registered',
        'order'   => 'DESC'
    );

    $users = get_users($args);

    date_default_timezone_set('America/Chicago');
    $current_date = date('Y-m-d');
    $now = strtotime($current_date);

    foreach ($users as $user) {
		$inacive_check=get_user_meta($user->ID, 'user_action_status' , true );
		if(empty($inacive_check) || $inacive_check==1)
		{

        $ed = get_user_meta($user->ID, 'user_registration_end_date', true);

        if (!empty($ed)) {

            $end_date = strtotime($ed);

            if ($now > $end_date) {

                $datediff = $now - $end_date;
                $days_completed = floor($datediff / (60 * 60 * 24));

                if ($days_completed >= 31) {

                    $meta_check = get_user_meta(
                        $user->ID,
                        '30_days_completed_cron_job',
                        true
                    );

                    if (empty($meta_check)) {
						$IDT=$user->ID;
						$mID=get_user_meta($IDT, "wc_ship_mergeOrderID", true);
						$mQanty=get_user_meta($IDT, "wc_ship_mergeOrderQuanty", true);
						$first_catlog_order_for_cron_job=get_user_meta($IDT, "wp_meta_first_catlog_order_for_cron_job", true);
						$args = array(
										'status' => array('wc-waiting-call'),
										'meta_key' => 'fundraiser_field',
										'meta_value' => $IDT,
										'limit' => -1
								);
						$orders = wc_get_orders($args);
						$totalOrdersQ=count($orders);
						if(empty($mID) && empty($mQanty) && empty($first_catlog_order_for_cron_job)  && !empty($totalOrdersQ))
						{
                        $queue[] = array(
                            'userid'   => $user->ID,
                            'end_date' => $ed,
							'diff' => $days_completed
                        );
					}
                    }
                }
            }
        }
	  }
	}

print_r($queue);
   
}
get_fundraiser_30days_completed_campaign_test();
?>

 <?php 
get_footer();
?>