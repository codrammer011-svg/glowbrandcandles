<?php 
function custom_representative_list_data()
{
?>
<style>
.widget {
    position: relative;
    clear: both;
    width: auto;
    margin-bottom: 2em;
	    margin-top: 40px;
}
.widget-header {
    position: relative;
    height: 40px;
    line-height: 40px;
    background: #E9E9E9;
    background: -moz-linear-gradient(top, #FAFAFA 0%, #E9E9E9 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#FAFAFA), color-stop(100%,#E9E9E9));
    background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
    background: -o-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
    background: -ms-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
    background: linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9');
    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#FAFAFA', endColorstr='#E9E9E9')";
    border: 1px solid #CCC;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    -webkit-background-clip: padding-box;
    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    box-shadow: inset 0 1px 0 rgba(255,255,255,1);
}
.widget-header h3 {
    position: relative;
    top: 0px;
    left: 10px;
    float: left;
    display: inline-block;
    margin-right: 3em;
    font-size: 13px;
    font-weight: 600;
    color: #555;
    line-height: 18px;
    text-shadow: 1px 1px 2px rgba(255,255,255,1);
}
.widget-content {
    padding: 20px 15px 15px;
    background: #FFF;
    border: 1px solid #CCC;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
}
.widget-header+.widget-content, .widget-tabs+.widget-content, .widget-toolbar+.widget-content {
    border-top: none;
    -webkit-border-top-left-radius: 0;
    -webkit-border-top-right-radius: 0;
    -moz-border-radius-topleft: 0;
    -moz-border-radius-topright: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.widget-table .widget-content {
    padding: 0;
}
.widget-content:before, .widget-content:after {
    content: "";
    display: table;
}
table {
    max-width: 100%;
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
}
.table {
    width: 100%;
    margin-bottom: 18px;
}
.table-bordered {
    border: 1px solid #dddddd;
    border-collapse: separate;
    *border-collapse: collapsed;
    border-left: 0;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
}
.table-highlight.table-bordered {
    border-top: 1px solid #1C2B37;
}
.widget-table .table-bordered {
    border-top: none;
    border-right: none;
    border-bottom: none;
    margin-bottom: 0;
}
.widget-table .widget-header+.widget-content .table-bordered {
    -moz-border-radius: 0 !important;
    -webkit-border-radius: 0 !important;
    border-radius: 0 !important;
}
.table-highlight.table-bordered thead tr {
  /*  background: #263849;
    background: -moz-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #3C4C5B), color-stop(100%, #263849));
    background: -webkit-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: -o-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: -ms-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: linear-gradient(top, #3C4C5B 0%, #263849 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#3C4C5B', endColorstr='#263849');
    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#3C4C5B', endColorstr='#263849')";
	*/
	background: -webkit-linear-gradient(top, #2ea2cc 0%, #2ea2cc 100%);
}
.table th, .table td {
    padding: 8px;
    line-height: 18px;
    text-align: left;
    vertical-align: top;
    border-top: 1px solid #dddddd;
}
.table th {
    font-weight: bold;
}
.table-bordered thead th {
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #444;
  /*  border-left: 1px solid #F1F1F1;
    border-right: 1px solid #CCC;
    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    box-shadow: inset 0 1px 0 rgba(255,255,255,1);*/
}
.table-highlight.table-bordered thead th {
    color: #FFF;
  /*  border-right: 1px solid #1C2B37;
	*/
	 border-right: 1px solid #2ea2cc;
    border-left: 1px solid rgba(255,255,255,.15);
 /*  -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.25);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.25);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.25);
    text-shadow: 1px 1px 2px rgba(0,0,0,.5);*/
}
.widget-table .table-bordered tr td:first-child, .widget-table .table-bordered tr th:first-child {
    border-left: none;
}
.widget-table .widget-header+.widget-content thead th {
    -moz-border-radius: 0 !important;
    -webkit-border-radius: 0 !important;
    border-radius: 0 !important;
}
.table-bordered thead:first-child tr:first-child th:first-child, .table-bordered tbody:first-child tr:first-child td:first-child {
    -webkit-border-top-left-radius: 4px;
    border-top-left-radius: 4px;
    -moz-border-radius-topleft: 4px;
}
.widget-table .table-highlight thead:first-child tr:first-child th {
   /* border-top: 1px solid #292C32;
	*/
	 border-top: 1px solid #2ea2cc;
}
.widget-table tbody td {
    border-left: 1px solid #FFF;
    border-right: 1px solid #DDD;
}
.widget-table tbody tr:first-child td {
    border-top-color: #CCC;
}
.widget-table .table-bordered tr td:first-child, .widget-table .table-bordered tr th:first-child {
    border-left: none;
}
.table-highlight.table-bordered tbody tr:nth-child(odd) td, .table-highlight.table-bordered tbody tr:nth-child(odd) th {
    background-color: #F0F3F7;
}
.table-highlight.table-bordered tbody tr:nth-child(odd) td, .table-highlight.table-bordered tbody tr:nth-child(odd) th {
    background-color: #FFFFFF;
}
.label, .badge {
    font-size: 10.998px;
    font-weight: bold;
    line-height: 14px;
    color: #ffffff;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    white-space: nowrap;
    vertical-align: baseline;
    background-color: #999999;
}
.label {
    padding: 1px 4px 2px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}
.label-success, .badge-success {
    background-color: #468847;
}
.label-danger, .badge-danger {
    background-color: #bd362f;
}
.paid {
    background-color: #468847;
}
.unpaid {
    background-color: #bd362f;
}
.btn-custom
{
	background-color: #405e6a !important;
    text-shadow: 0px -1px 0px #22404c;
    background-image: none;
    -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
    color: #fff;
    display: inline-block;
    padding: 4px 10px 4px;
    margin-bottom: 0;
    margin-left: .3em;
    font-size: 13px;
    line-height: 18px;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    background-repeat: repeat-x;
    border: 0;
    border-color: #e6e6e6 #e6e6e6 #bfbfbf;
    border-bottom-color: #b3b3b3;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    text-decoration: none;
    display: inline;
}
.hold-blue{
background-color: #198ae3;
    color: #fff;
    padding: 0px 8px;
    border: 1px solid #198ae3;
    font-size: 11px;
    display: inline-block;
    border-radius: 3px;
}
.hold-green{
	    background-color: #1bcfb4;
    color: #fff;
  padding: 0px 8px;
    border: 1px solid #1bcfb4;
   font-size: 11px;
    display: inline-block;
    border-radius: 3px;
}
.hold-pink{
	    background-color: #fe7c96;
    color: #fff;
    padding: 0px 8px;
    border: 1px solid #fe7c96;
   font-size: 11px;
    display: inline-block;
    border-radius: 3px;
}
.even
{
	background-color: rgb(120 123 128 / 20%);
}
a.btn-primary
{
    --bs-btn-hover-bg: #20bb59;
    --bs-btn-hover-border-color: #1fb155;
	color: #fff;
    background-color: var(--bs-btn-hover-bg);
    border-color: var(--bs-btn-hover-border-color);
   box-shadow: 0 1px 2px 0 rgb(34 197 94 / 35%);
    padding: 4px 20px;
    display: inline-block;
	    text-decoration: none;
}
a.btn-primary:hover
{
	    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
		opacity:0.95;
}
.widget {
    margin-top: 19px;
    width: 98%;
}
.btn-sec
{
	display: inline-block;
    color: #fff;
    position: relative;
}
.btn-sec a
{
	padding: 10px 35px;
    color: #fff;
    text-decoration: none;
    background: #2ea2cc;
    border: 1px solid #fff;
    border-radius: 8px;
    margin: 15px 0px 0px 13px;
    display: block;
    font-weight: 500;
    text-transform: uppercase;
    box-shadow: inset 0 8px 0 rgb(46 162 204 / 63%);
}
.btn-sec a:hover {
    opacity: 0.9;
}
.btn-sec a span
{
	width: 100%;
    display: block;
    text-align: center;
}
.fade
{
	opacity:1;
}
.unpaid
{
	color: #fff;
	border: 1px solid red;
	font-size: 12px;
	padding: 3px 10px;
	border-radius: 8px;
	background: #d63638;
}
.paid
{
	border: 1px solid #20bb59;
    background: #20bb59;
    color: #fff;
    font-size: 12px;
    padding: 3px 10px;
    border-radius: 8px;
}
.table-bordered thead th, .table th, .table td
{
	font-size: 14px;
    font-weight: 400;
}
#action_st,#action_s
{
	border: 1px solid #20bb59;
    background: #20bb59;
    color: #fff;
    font-size: 12px;
    padding: 3px 10px;
    border-radius: 8px;
}
#action_s2
{
	color: #fff;
    border: 1px solid red;
    font-size: 12px;
    padding: 3px 10px;
    border-radius: 8px;
    background: #d63638;
}
.print_report
{
	--bs-btn-hover-bg: #20bb59;
    --bs-btn-hover-border-color: #1fb155;
    color: #fff;
    background-color: var(--bs-btn-hover-bg);
    border-color: var(--bs-btn-hover-border-color);
    box-shadow: 0 1px 2px 0 rgb(34 197 94 / 35%);
    padding: 4px 20px;
    display: inline-block;
    text-decoration: none;
}
a.print_report:hover, a.print_report:active, a.print_report:focus {
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    opacity: 0.95;
    color: #fff;
    box-shadow: none;
    outline: none;
}
.page_links {
	padding: 10px 8px 10px;
    /* background: #e6eef7; */
     text-align: right;
}
prev.page-numbers, a.page-numbers, span.page-numbers.current {
    font-size: 15px;
    text-decoration: none;
    color: #000;
}
span.page-numbers.current {
  
    text-decoration: underline;
  
}
</style>
<?php if($_GET['page']=='representative' && $_GET['payment_history']=="true" && !empty($_GET['repsID']) && $_GET['view']=="true"){ 

$current_userID=$_GET['repsID'];
$payment_id=$_GET['payID'];
$paid_status_check=get_post_meta($payment_id,"paid_status",true);

?>
<div class="row text-right position-relative" style="
   display: inline-block;
   width: 93%;
   background-color: #2ea2cc!important;
   padding: 15px 30px;
   color: #fff;
   position: relative;
   margin: 35px 0px 0px 15px;
   box-shadow: inset 0 8px 0 rgb(46 162 204 / 63%);
   ">
   <div class="left-side" style="
      width: 48%;
      float: left;
      ">
      <h1 style="
         position: absolute;
         top: 44%;
         margin: 0;
         color: #fff;
         ">Fundraiser Lists</h1>
		 <b>Reps ID: <?php echo "#".$current_userID; ?></b>
		  <b style="display: block;">Payment ID: <?php echo "#".$payment_id; ?></b>
   </div>
   <div class="right-side breadcrumb-icon" style="
      width: 48%;
      float: right;
      "><img src="/wp-content/uploads/2024/08/login-bg.png" alt="breadcrumb" width="" style="
      float: right;
      width: 120px;
      margin-top: 8px;
      "></div>
</div>
<div class="btn-sec"><a href="<?php echo admin_url(); ?>admin.php?page=representative">Home</a></div>
<div class="widget widget-table">	
	<!--<div class="widget-header">						
		<h3 class="card-title">Representative Users</h3>
	</div>-->
	<div id="search_table" class="widget-content " style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			
			<th id="" style="vertical-align: top; text-align: left;">#ID</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization</th>
			<th id="" style="vertical-align: top; text-align: left;">Referral Email</th>
			<th id="" style="vertical-align: top; text-align: left;">Status</th>
			<th style="vertical-align: top; text-align: left;">Paid</th>
			<th id="" style="vertical-align: top; text-align: left;">Online Payment</th>
			<th id="" style="vertical-align: top; text-align: left;">Ship To School Payment</th>
			<th id="" style="vertical-align: top; text-align: left;">Catalog Payment</th>
			<th id="" style="vertical-align: top; text-align: left;">Total Payment</th>
			<th id="" style="vertical-align: top; text-align: left;">Referral Commission Amount</th>
			<th id="" style="vertical-align: top; text-align: center;"><img src="/wp-content/uploads/2024/08/icon-connect.png" style="width: 30px;">Report</th>
		 </tr>
		</thead>
		<tbody>
	<?php 
		$cno=1;
		$q=0;
		$exist_fids_arr=array();
		$userdata = get_userdata( $current_userID );
 
		$current_email=$userdata->user_email;
		//$current_email = get_user_meta($current_userID,'user_email',true);
		//$current_email="3geeks75@gmail.com";
		$firstname = get_user_meta($current_userID,'first_name',true);
		$lastname = get_user_meta($current_userID,'last_name',true);
		$phone = get_user_meta($current_userID,'phone',true);
		$sex = get_user_meta($current_userID,'sex',true);
		$reps_company = get_user_meta($current_userID,'billing_company',true);
		$reps_country = get_user_meta($current_userID,'billing_country',true);
		$reps_city = get_user_meta($current_userID,'billing_city',true);
		$reps_address = get_user_meta($current_userID,'billing_address_1',true);
		$reps_commission = get_user_meta($current_userID,'reps_commission',true);
		
		if($paid_status_check==0)
		{
			$fundraiser_ids=get_post_meta($payment_id,"fundraiser_ids",true);
			if(!empty($fundraiser_ids))
			{
				$exist_fids_arr=explode(",",$fundraiser_ids);
				$q=1;
			}
		}
		else if($paid_status_check==1)
		{
			$fundraiser_ids=get_post_meta($payment_id,"after_payment_fundraiser_ids_inactive",true);
			if(!empty($fundraiser_ids))
			{
				$exist_fids_arr=explode(",",$fundraiser_ids);
				$q=1;
			}
		}
		else{
			$fundraiser_ids=get_post_meta($payment_id,"fundraiser_ids",true);
			if(!empty($fundraiser_ids))
			{
				$exist_fids_arr=explode(",",$fundraiser_ids);
				$q=1;
			}
		}
		
		$count_args = array(
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '=',
				);
		$user_count_query = new WP_User_Query($count_args);
		$user_count = $user_count_query->get_results();

		// count the number of users found in the query
		$total_users = $user_count ? count($user_count) : 1;

		// grab the current page number and set to 1 if no page number is set
		$page = isset($_GET['pg']) ? $_GET['pg'] : 1;

		// how many users to show per page
		$users_per_page = 20;

		// calculate the total number of pages.
		$total_pages = 1;
		$offset = $users_per_page * ($page - 1);
		$total_pages = ceil($total_users / $users_per_page); 
		$args = array(
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '=',
					'number'    => $users_per_page,
					'offset'    => $offset  // skip the number of users that we have per page  
				);
		
		// Create the WP_User_Query object
		$wp_user_query = new WP_User_Query($args);

		// Get the results
		$fundraisers_list = $wp_user_query->get_results();
				
		
		if($q==1){
		/*get fundraiser list which connected representative */
		$fundraisers_users = get_users(array( 
			'role' => array( 'fundraiser' ),
			'include' => $exist_fids_arr,
			'meta_key'     => 'user_registration_referral_email',
			'meta_value'   => $current_email,
			'meta_compare' => '=',
		));
		
	/*	$fundraisers_list = get_users(array( 
			'role' => array( 'fundraiser' ),
			'include' => $exist_fids_arr,
			'meta_key'     => 'user_registration_referral_email',
			'meta_value'   => $current_email,
			'meta_compare' => '=',
			'number' => $number,
			'offset' => $offset,
		));	
		*/
		$fundraiserTotal = count($fundraisers_list);

		$fundraisers_list2 = get_users(array( 
			'role' => array( 'fundraiser' ),
			'include' => $exist_fids_arr,
			'meta_key'     => 'user_registration_referral_email',
			'meta_value'   => $current_email,
			'meta_compare' => '=',
			
		));	
		$fundraiserTotal2 = count($fundraisers_list2);
	
	//	$total_users = count($fundraisers_users);
	//	$total_query = count($fundraisers_list);
		//$total_pages = intval($total_users / $number) + 1;
	//	$total_pages = ceil($total_users / $number); 
	
	
		$total_referral_comm = 0;
		
		foreach($fundraisers_list as $fundraisers) {
					$ID = $fundraisers->data->ID;
					$action_status= get_user_meta($ID,'user_action_status',true );
					//$name = $fundraisers->data->user_login;
					$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
					if(empty($org))
					{
						$org=get_user_meta( $ID , 'user_registration_input_company' , true );
					}
	            	$email = $fundraisers->data->user_email;
	            	$firstname =  get_user_meta($fundraisers->data->ID,'first_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);

	            	$get_referral = get_user_meta($ID,'user_registration_referral_email',true);
			        $referralUser = get_user_by( 'email', $get_referral );        
					$referralUserID = $referralUser->data->ID;


			        $get_referral_commission_amount = 0;
					$totalAmount = 0;
					$total_sale_so_far_Catlog = 0;
					$total_sale_so_far = 0;


			        if(get_user_meta($referralUserID,'reps_commission',true)){
			         $get_referral_commission = get_user_meta($referralUserID,'reps_commission',true);       
			        }else{
			        $get_referral_commission = 0;
			        }

	            	$args = array(
					'status'   => "published",
					'meta_key' => 'fundraiser_field',
					'meta_value' => $ID,
					'limit' => -1
					);
					
					$orders = wc_get_orders($args);
				foreach ( $orders as $order ){
						
						$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
						$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
						$order_data = $order->get_data();
						$order_customer_id = $order_data['customer_id'];
						if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) 
						{
							/* Active/Inactive */	
							$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
							$extract_date=$order_date;
							$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
							$inactive_s=get_user_meta($frID3, "user_action_status", true);
							$in_s=1;
							if ($inactive_date < $extract_date && $inactive_s==2){
								$in_s=2;
							}
							else if($inactive_s==1 || empty($inactive_s)){
								$in_s=1;
							}
							else{
								$in_s=1;
							}
							/*$inactive_date="2020-08-29 01:00:01";*/
							$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							if ($in_s ==1){
								if ($frID3 != $order_customer_id){
									if($order_type=="Online"){
										$tdiscount=$order->get_total_discount();
										if(!empty($tdiscount)){
											$newamt=$order->get_subtotal()-$tdiscount;
											$total_sale_so_far+=$newamt;
										}else{
											$total_sale_so_far+=$order->get_subtotal();
										}
									}
								}
								else if ($frID3 == $order_customer_id){
									if($order_type=="Catlog"){
										$subtot=$order->get_subtotal(); 
										$total_sale_so_far_Catlog+=$subtot; 
									}
								}
								else{
								
								}
							}
							else {
								if ($frID3 == $order_customer_id){
									if($order_type=="Catlog"){
										$subtot=$order->get_subtotal(); 
										$total_sale_so_far_Catlog+=$subtot; 
									}
								}
								else{
								
								}
							}
						}
					
					}
					/* Calculate Ship to school Total Sales */
						$total_sale_so_far2=0;
						  $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
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
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id){ 
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if ($order_type=='ship-to-school') {
								
										$tdiscount=$order->get_total_discount();
										if(!empty($tdiscount))
										{
										  $newamt2=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt2;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                    } 
								} 
							} 
						}
					}
					$totalAmount = $total_sale_so_far_Catlog + $total_sale_so_far + $total_sale_so_far2;
					$get_referral_commission_amount = floatval ($totalAmount) * floatval ($get_referral_commission) / 100;
					
					$action_status= get_user_meta($ID,'user_action_status',true );
					$status= get_user_meta($ID,'user_status',true );
				?>
				<tr id="<?php echo "rep_".$ID; ?>" class="<?php if($cno % 2 == 0){ echo "even "; } else{ echo "odd "; }  ?>">
					<td id="1_" style="vertical-align: top; text-align: left;"><?php echo "#".$ID; ?></td>
					<td id="2_" style="vertical-align: top; text-align: left;"><?php echo $org;?></td>	
					<td id="2_" style="vertical-align: top; text-align: left;"><?php echo $get_referral;?></td>	
					<td id="11_" class="" style="vertical-align: top; text-align: left; "><?php if($action_status==1){?><span id="action_s" class="">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="">Inactive</span><?php }  else { ?><span id="action_st" class="">Active</span><?php } ?></td>			
					<td id="5_fund" style="vertical-align: top; text-align: left; "><?php if($status==1){ ?><span id="action_status_<?php echo $ID;?>" class="paid">Paid</span><?php }  elseif($status==2){?><span id="action_s2" class="">Unpaid</span><?php }  else { ?><span id="action_s2" class="">Unpaid</span><?php } ?></td>
					<td id="3_" style="vertical-align: top; text-align: left;">$<?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', '');?></td>	
					<td id="9_" style="vertical-align: top; text-align: left;">$<?php echo $total_sale_so_far2=number_format($total_sale_so_far2, 2, '.', ''); ?></td>						
					<td id="4_" style="vertical-align: top; text-align: left;">$<?php echo $total_sale_so_far_Catlog=number_format($total_sale_so_far_Catlog, 2, '.', ''); ?></td>
					<td id="5_" style="vertical-align: top; text-align: left;">$<?php echo $totalAmount=number_format($totalAmount, 2, '.', ''); ?></td>
					<td id="6_" style="vertical-align: top; text-align: left;">$<?php echo $get_referral_commission_amount=number_format($get_referral_commission_amount, 2, '.', ''); ?></td>
					<td id="10_" style="vertical-align: top; text-align: left;"><a href="/print?fund_id=<?php echo $ID;?>" target="_FUND" class="print_report">View</a></td>
				</tr>
		<?php $cno++;   } 
			
				 ?>
			<?php
						// grab the current query parameters
				$query_string = $_SERVER['QUERY_STRING'];

				// The $base variable stores the complete URL to our page, including the current page arg

				// if in the admin, your base should be the admin URL + your page
				$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

				// if on the front end, your base is the current page
				//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';
			?>
			<!--<tfoot><tr>
				<td colspan="5">
				
	
				</td>
				</tr>
			</tfoot>-->
				<?php
					}
		
				?>
			
			
		
		</table>
		
	</div>
	<div class="page_links">
		<?php 
		if($q==1){
						echo paginate_links( array(
							'base' => $base, // the base URL, including query arg
							'format' => '&pg=%#%', // this defines the query parameter that will be used, in this case "p"
							'prev_text' => __('&laquo; Previous'), // text for previous page
							'next_text' => __('Next &raquo;'), // text for next page
							'total' => $total_pages, // the total number of pages we have
							'current' => $page, // the current page
							'end_size' => 1,
							'mid_size' => 5,
						));
						}
					?>
					</div>
</div>
<style>

</style>
<?php }
 else if($_GET['page']=='representative' && $_GET['payment_history']=="true" && !empty($_GET['repsID'])){ 

$current_userID=$_GET['repsID'];
?>
<div class="row text-right position-relative" style="
   display: inline-block;
   width: 93%;
   background-color: #2ea2cc!important;
   padding: 15px 30px;
   color: #fff;
   position: relative;
   margin: 35px 0px 0px 15px;
   box-shadow: inset 0 8px 0 rgb(46 162 204 / 63%);
   ">
   <div class="left-side" style="
      width: 48%;
      float: left;
      ">
      <h1 style="
         position: absolute;
         top: 44%;
         margin: 0;
         color: #fff;
         ">Payment History</h1>
   </div>
   <div class="right-side breadcrumb-icon" style="
      width: 48%;
      float: right;
      "><img src="/wp-content/uploads/2024/08/login-bg.png" alt="breadcrumb" width="" style="
      float: right;
      width: 120px;
      margin-top: 8px;
      "></div>
</div>
<div class="btn-sec"><a href="<?php echo admin_url(); ?>admin.php?page=representative">Back</a></div>
<div class="widget widget-table">	
	<!--<div class="widget-header">						
		<h3 class="card-title">Representative Users</h3>
	</div>-->
	<div id="search_table" class="widget-content " style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			
			<th id="" style="vertical-align: top; text-align: left;">#Payment ID</th>
			<th id="" style="vertical-align: top; text-align: left;">Reps ID</th>
			<th id="" style="vertical-align: top; text-align: left;">Payment Status</th>
			<th id="" style="vertical-align: top; text-align: left;">Payment Date</th>
			<th id="" style="vertical-align: top; text-align: left;">Total Fundraiser</th>
			<th id="" style="vertical-align: top; text-align: left;">Referral Commission Amount</th>
			<th id="" style="vertical-align: top; text-align: left;">Fundraiser Lists</th>
		 </tr>
		</thead>
		<tbody>
	<?php 
		$cno=1;
		$userdata = get_userdata( $current_userID );
        $m=array();
		$payment_history_arr=array();
		$current_email=$userdata->user_email;
		
		$current_payment_id=get_user_meta( $current_userID, "um_unique_payment_id", true );
		$payment_history_str=get_user_meta($current_userID,'um_unique_payment_id_arr',true);
		if(!empty($payment_history_str))
		{
			$payment_history_arr=explode(",",$payment_history_str);
			if (!in_array($current_payment_id,$payment_history_arr)) {
				array_push($payment_history_arr, $current_payment_id);
			}
		}
	    else if(empty($payment_history_str))
		{
			if (!in_array($current_payment_id,$payment_history_arr)) {
				array_push($payment_history_arr, $current_payment_id);
			}
		}
		else{
			
		}
		// $pagedNEW = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$page = isset($_GET['paged']) ? $_GET['paged'] : 1;
		$the_query = new WP_Query( array( 
						'post_type' => 'payment_history',
						'posts_per_page' => 20,
						'order' => 'DESC',
						'post__in'   => $payment_history_arr,
						'paged' => $page) 
					);
		if ( $the_query->have_posts() ):
			while ( $the_query->have_posts() ) : $the_query->the_post();
				 $ID=get_the_ID();
			     $payment_id = get_field( 'payment_id' );
				 $payment_date = get_field( 'payment_date' );
				 $reps_id = get_field( 'reps_id' );
				 $paid_status = get_field( 'paid_status' );
				 $total_commission = get_field( 'total_commission' );
				 $total_amount = get_field( 'total_amount' );
				 $paid_status_check=get_post_meta($ID,"paid_status",true);
				 
				if($paid_status_check==0)
				{
					 $fundraiser_ids = get_field( 'fundraiser_ids' );
					 if($fundraiser_ids)
					 {
						$m=explode(",",$fundraiser_ids);
						
						$m_fund=count($m);
					 }
					 if($m_fund>0)
					 {
							$inactiveUsers=0;
							for($a=0;$a<$m_fund;$a++)
							{
								$cntID = $m[$a];
								$action_status = get_user_meta($cntID,'user_action_status',true );
								if($action_status==2)
								{ 
									$inactiveUsers++;
								}
							}
					}
				}
				else if($paid_status_check==1)
				{
					 $fundraiser_ids = get_field( 'after_payment_fundraiser_ids_inactive' );
					 if($fundraiser_ids)
					 {
						$m=explode(",",$fundraiser_ids);
						
						$m_fund=count($m);
					 }
					 if($m_fund>0)
					 {
							$inactiveUsers=0;
							for($a=0;$a<$m_fund;$a++)
							{
								$cntID = $m[$a];
								$action_status = get_user_meta($cntID,'user_action_status',true );
								if($action_status==2)
								{ 
									$inactiveUsers++;
								}
							}
					}
				}
				else{
					 $fundraiser_ids = get_field( 'fundraiser_ids' );
					 if($fundraiser_ids)
					 {
						$m=explode(",",$fundraiser_ids);
						
						$m_fund=count($m);
					 }
					 if($m_fund>0)
					 {
							$inactiveUsers=0;
							for($a=0;$a<$m_fund;$a++)
							{
								$cntID = $m[$a];
								$action_status = get_user_meta($cntID,'user_action_status',true );
								if($action_status==2)
								{ 
									$inactiveUsers++;
								}
							}
					}
				}
				 
				 
				
		?>
				
				<tr <?php if($paid_status_check==0) { ?> style="display:none;" <?php } ?>id="<?php echo "rep_".$ID; ?>" class="<?php if($cno % 2 == 0){ echo "even "; } else{ echo "odd "; }  ?>">
					<td id="1_" style="vertical-align: top; text-align: left;"><?php if($ID){ echo "#".$ID; } else{ } ?></td>
					<td id="2_" style="vertical-align: top; text-align: left;"><?php if($current_userID){ echo '#'.$current_userID; } else{  } ?></td>	
					<td id="7_" style="vertical-align: top; text-align: left;"><?php if($paid_status){ echo "<b class='paid'>Paid</b>"; } else{ echo "<b class='unpaid'>Unpaid</b>"; } ?></td>					
					<td id="3_" style="vertical-align: top; text-align: left;"><?php if($payment_date){ echo $payment_date; } else{ } ?></td>					
					<td id="4_" style="vertical-align: top; text-align: left;"><?php if(!empty($inactiveUsers)){ echo $inactiveUsers; } else { } ?></td>
					<td id="5_" style="vertical-align: top; text-align: left;"><?php if($total_commission){ echo '$'.$total_commission; } else{ } ?></td>
					<td id="6_" style="vertical-align: top; text-align: left;"><a class="btn-primary" href="?page=representative&payment_history=true&repsID=<?php echo $current_userID; ?>&view=true&payID=<?php echo $payment_id; ?>">View</a></td>
				</tr>
				<?php  $cno++;
						endwhile;
						wp_reset_postdata();
						endif;
                ?>
		
			
			
		
		</table>
		
	</div>
	<div class="page_links">
			<?php
							$query_string = $_SERVER['QUERY_STRING'];
							
							$base = admin_url('admin.php') . '?' . remove_query_arg('paged', $query_string) . '%_%';
							$big = 999999999; // need an unlikely integer
							/* echo paginate_links( array(
								'base' => $base,
								'format' => '&paged=%#%',
								'current' => max( 1, get_query_var('paged') ),
								'total' => $the_query->max_num_pages
							) ); */
						 echo paginate_links( array(
								'base'         => $base,
								'total'        => $the_query->max_num_pages,
								'current'      => $page,
								'format'       => '&paged=%#%',
								'end_size'     => 2,
								'mid_size'     => 1,
								'prev_next'    => true,
								'add_args'     => true,
								'add_fragment' => '',
							) );
					
				?>
				</div>
</div>
<?php }
else {
?>

<div class="row text-right position-relative" style="
   display: inline-block;
   width: 93%;
   background-color: #2ea2cc!important;
   padding: 15px 30px;
   color: #fff;
   position: relative;
   margin: 35px 0px 0px 15px;
   box-shadow: inset 0 8px 0 rgb(46 162 204 / 63%);
   ">
   <div class="left-side" style="
      width: 48%;
      float: left;
      ">
      <h1 style="
         position: absolute;
         top: 44%;
         margin: 0;
         color: #fff;
         ">Representative ♦ Users</h1>
   </div>
   <div class="right-side breadcrumb-icon" style="
      width: 48%;
      float: right;
      "><img src="/wp-content/uploads/2024/08/ChatBc.png" alt="breadcrumb" width="" style="
      float: right;
      width: 120px;
      margin-top: 8px;
      "></div>
</div>
<div class="btn-sec"><a href="<?php echo admin_url(); ?>admin.php?page=new_user_custom-submenu-page"><span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
				  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"></path>
				  <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"></path>
				</svg></span>Add New Reps</a></div>
<div class="widget widget-table">	
	<!--<div class="widget-header">						
		<h3 class="card-title">Representative Users</h3>
	</div>-->
	<div id="search_table" class="widget-content " style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			
			<th id="" style="vertical-align: top; text-align: left;">#ID</th>
			<th id="" style="vertical-align: top; text-align: left;">Full Name</th>
			<th id="" style="vertical-align: top; text-align: left;">Email</th>
			<th id="" style="vertical-align: top; text-align: left;">Commission %</th>
			<th id="" style="vertical-align: top; text-align: left;">Total Amount</th>
			<th id="" style="vertical-align: top; text-align: left;">Total Commission</th>
			<th id="" style="vertical-align: top; text-align: left;">Pay Now</th>
			<th id="" style="vertical-align: top; text-align: left;">Payment History</th>
			<th id="" style="vertical-align: top; text-align: left;">Fundraiser Lists</th>
			<th id="" style="vertical-align: top; text-align: left;">Final Report</th>
		 </tr>
		</thead>
		<tbody>
	<?php 
			$cno=1;
			
			$c_args=array( 
			'role' => array( 'representative' )
			);
			//$users_data = get_users($args);
			$user_count_query = new WP_User_Query($c_args);
			$users_count = $user_count_query->get_results();

			// count the number of users found in the query
			$total_users = $users_count ? count($users_count) : 1;

			// grab the current page number and set to 1 if no page number is set
			$page = isset($_GET['pg']) ? $_GET['pg'] : 1;

			// how many users to show per page
			$users_per_page = 20;

			// calculate the total number of pages.
			$offset = $users_per_page * ($page - 1);
			$total_pages = ceil($total_users / $users_per_page); 
			
			$args=array( 
			'role' => array( 'representative' ),
			'number'    => $users_per_page,
			'offset'    => $offset 
			);
			$users_data = get_users($args);
			
			foreach($users_data as $user){
				$IDD=$user->data->ID;	
			    $f=get_user_meta($IDD,'first_name',true);
				$l=get_user_meta($IDD,'last_name',true);
				$get_referral_commission_new = get_user_meta($IDD,'reps_commission',true);  
				$current_payment_postid=get_user_meta($IDD,"um_unique_payment_id",true);
				$paidSt=get_post_meta($current_payment_postid,'paid_status',true);
				
				$email_reps = $user->data->user_email;
				$total_referral_comm = 0;
				$totalAmount_full=0;
				$get_referral_commission_amount = 0;
				$totalAmount = 0;
				$total_sale_so_far_Catlog = 0;
				$total_sale_so_far = 0;
				$q=0;
				$exist_fids_arr=array();
				// Older unpaid records have no paid_status metadata; PHP 8 no longer treats '' == 0 as true.
				if($paidSt === '' || $paidSt === '0' || $paidSt === 0){
					
				$fundraiser_ids=get_post_meta($current_payment_postid,"fundraiser_ids",true);
				if(!empty($fundraiser_ids))
				{
					$exist_fids_arr=explode(",",$fundraiser_ids);
					$q=1;
				}
				
				if($q==1)
				{	
			    $fundraisers_list = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $email_reps,
					'meta_compare' => '=',
				));	
				$w=0;
				$new_inactive_fund_ids=array();
				$new_inactive_fund_ids_sTr="";
				
				foreach($fundraisers_list as $fundraisers) {
					$ID = $fundraisers->data->ID;
					
					$action_status= get_user_meta($ID,'user_action_status',true );
							
					if($action_status==2)
					{
					$new_inactive_fund_ids[$w]=$ID;
	            	//$name = $fundraisers->data->user_login;
					$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
					if(empty($org))
					{
						$org=get_user_meta( $ID , 'user_registration_input_company' , true );
					}
	            	$email = $fundraisers->data->user_email;
	            	$firstname =  get_user_meta($fundraisers->data->ID,'first_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);

	            	$get_referral = get_user_meta($ID,'user_registration_referral_email',true);
			        $referralUser = get_user_by( 'email', $get_referral );        
					$referralUserID = $referralUser->data->ID;


			        $get_referral_commission_amount = 0;
					$totalAmount = 0;
					$total_sale_so_far_Catlog = 0;
					$total_sale_so_far = 0;


			        if(get_user_meta($referralUserID,'reps_commission',true)){
			         $get_referral_commission = get_user_meta($referralUserID,'reps_commission',true);       
			        }else{
			        $get_referral_commission = 0;
			        }

	            	$args = array(
					'status'   => "published",
					'meta_key' => 'fundraiser_field',
					'meta_value' => $ID,
					'limit' => -1
					);
					
					$orders = wc_get_orders($args);
					foreach ( $orders as $order ){
						
						$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
						$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
						$order_data = $order->get_data();
						$order_customer_id = $order_data['customer_id'];
						if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) 
						{
							/* Active/Inactive */	
							$order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
							$extract_date=$order_date;
							$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
							$inactive_s=get_user_meta($frID3, "user_action_status", true);
							$in_s=1;
							if ($inactive_date < $extract_date && $inactive_s==2){
								$in_s=2;
							}
							else if($inactive_s==1 || empty($inactive_s)){
								$in_s=1;
							}
							else{
								$in_s=1;
							}
							/*$inactive_date="2020-08-29 01:00:01";*/
							if ($in_s ==1){
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								if ($frID3 != $order_customer_id){
									if($order_type=="Online"){
										$tdiscount=$order->get_total_discount();
										if(!empty($tdiscount)){
											$newamt=$order->get_subtotal()-$tdiscount;
											$total_sale_so_far+=$newamt;
										}else{
											$total_sale_so_far+=$order->get_subtotal();
										}
									}
								}
								else if ($frID3 == $order_customer_id){
									if($order_type=="Catlog"){
										$subtot=$order->get_subtotal(); 
										$total_sale_so_far_Catlog+=$subtot; 
									}
								}
								else{
								
								}
							}
							else {
								$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								if ($frID3 == $order_customer_id){
									if($order_type=="Catlog"){
										$subtot=$order->get_subtotal(); 
										$total_sale_so_far_Catlog+=$subtot; 
									}
								}
								else{
								
								}
							}
						}
					
					}
					/* Calculate Ship to school Total Sales */
						$total_sale_so_far2=0;
						  $args = array(
                                 'status' => array('wc-waiting-call'),
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   	$scat = get_post_meta($order->get_id(), 'ship_with_catalog', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ($scat==1) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
									$extract_date=$order_date;
									$inactive_date=get_user_meta($frID3, "user_close_out_date_inactive", true);
									$inactive_s=get_user_meta($frID3, "user_action_status", true);
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
									/*$inactive_date="2020-08-29 01:00:01";*/
								if ($in_s ==1){
								  if ($frID3 != $order_customer_id){ 
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									if ($order_type=='ship-to-school') {
								
										$tdiscount=$order->get_total_discount();
										if(!empty($tdiscount))
										{
										  $newamt2=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far2+=$newamt2;
										}
										else
										{
										 $total_sale_so_far2+=$order->get_subtotal();
										}
										$total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', '');

                                    } 
								} 
							} 
						}
					}
					$totalAmount = $total_sale_so_far_Catlog + $total_sale_so_far + $total_sale_so_far2;
					$totalAmount_full+=$totalAmount;
					$get_referral_commission_amount = floatval ($totalAmount) * floatval ($get_referral_commission) / 100;
					$total_referral_comm = $total_referral_comm + $get_referral_commission_amount; 
				
				}
					$w++;
				}
					if(!empty($new_inactive_fund_ids))
					{
					 $new_inactive_fund_ids_sTr = implode(",",$new_inactive_fund_ids);
					}
				}
			} 
				?>
				<tr id="<?php echo "rep_".$IDD; ?>" class="<?php if($cno % 2 == 0){ echo "even "; } else{ echo "odd "; }  ?>">
					<td id="1_" style="vertical-align: top; text-align: left;"><?php echo "#".$IDD; ?></td>
					<td id="2_" style="vertical-align: top; text-align: left;"><?php echo $f." ".$l; ?></td>
					<td id="3_" style="vertical-align: top; text-align: left; "><?php echo $email_reps; ?></td>
					<td id="8_" style="vertical-align: top; text-align: left; "><?php if($get_referral_commission_new){ echo $get_referral_commission_new."%"; } else{} ?></td>
					<td id="4_" style="vertical-align: top; text-align: left; "><?php $totalAmount_full=number_format($totalAmount_full, 2, '.', ''); echo "$".$totalAmount_full; ?></td>
					<td id="5_" style="vertical-align: top; text-align: left; "><?php $total_referral_comm=number_format($total_referral_comm, 2, '.', ''); echo "$".$total_referral_comm; ?></td>
					<td id="6_" style="vertical-align: top; text-align: center;"><a class="btn-primary"  data-name="<?php echo $current_payment_postid; ?>" data-val="<?php echo $total_referral_comm; ?>" data-paidstatus="<?php if($paidSt){ echo "Paid"; } else { echo "Unpaid"; }?>" data-id="<?php echo $IDD; ?>" data-fundids="<?php echo $new_inactive_fund_ids_sTr; ?>" id="pay_now">Pay</a></td>
					<td id="7_" style="vertical-align: top; text-align: center;"><a class="btn-primary" href="?page=representative&payment_history=true&repsID=<?php echo $IDD; ?>">View</a></td>
					<td id="9_" style="vertical-align: top; text-align: center;"><a class="btn-primary" href="<?php echo admin_url(); ?>admin.php?page=representative&payment_history=true&repsID=<?php echo $IDD; ?>&view=true&payID=<?php if(!empty($current_payment_postid)) { echo $current_payment_postid; } else { } ?>">List</a></td>
					<td id="10_" style="vertical-align: top; text-align: center;"><a class="btn-primary" target="_blank" href="/representative-print/?r_id=<?php echo $IDD; ?>&pid=<?php if(!empty($current_payment_postid)) { echo $current_payment_postid; } else { } ?>">View</a></td>
			</tr>
				<?php  
					$cno++;  
				}
				?>
					
			</tbody>
		</table>
		<?php
						// grab the current query parameters
				$query_string = $_SERVER['QUERY_STRING'];

				// The $base variable stores the complete URL to our page, including the current page arg

				// if in the admin, your base should be the admin URL + your page
				$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

				// if on the front end, your base is the current page
				//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';
			?>
			
	</div>
	<div class="page_links">
				<?php 
				
								echo paginate_links( array(
									'base' => $base, // the base URL, including query arg
									'format' => '&pg=%#%', // this defines the query parameter that will be used, in this case "p"
									'total' => $total_pages, // the total number of pages we have
									'current' => $page, // the current page
									'prev_text' => __('&laquo; Previous'), // text for previous page
									'next_text' => __('Next &raquo;') // text for next page
								));
				
				?>
		</div>
</div>
 <div class="manual-mass-close_out-popup">
        <div class="manual-mass-order-popup-wrapper">
		<div class="close_btn" style="
		position: absolute;
		right: 10px;
		top: 5px;
		color: #fff;
		font-weight: 300;
		cursor: pointer;"><i class="fa fa-close" style="font-size:24px;"></i></div>
		<span>Confirm Your Payment!</span>
		<div class="r_pay">
		<!--<div><b>Payment ID:</b> <div style="display:inline-block;" class="valueData"></div></div><div><b>Payment Status:</b> <div style="display:inline-block;" class="valueData3"></div></div><div><b>Payment Amount:</b> <div style="display:inline-block;" class="valueData2"></div></div>-->
		<table>
			  <caption>Details</caption>
			  <tr>
				<td>Payment ID</td>
				<td class="valueData"></td>
			  </tr>
			  <tr>
				<td>Payment Status</td>
				<td class="valueData3"></td>
			  </tr>
			  <tr>
				<td>Payment Amount</td>
				<td class="valueData2"></td>
			  </tr>
			   <!--<tr>
				<td>Inactive Fundraiser IDs</td>
				<td class="valueData4"></td>
			  </tr>-->
		</table>
		</div>
		
            <a id="close_out_ajax" data-id="" data-action="1" data-paidstatus="" class="txt-link"></a>
            <a id="m-txt-link" class="txt-link">Cancel</a>
        <p class="msg"></p>
		</div>
		
</div> 
<style>
#pay_now
{
	cursor: pointer;
}
a{
	cursor:pointer;
}
.manual-mass-close_out-popup{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgb(0 0 0 / 90%);
		top: 0;
		left: 0;
		display: none;
}
.manual-mass-close_out-popup .manual-mass-order-popup-wrapper{
    display: block;
    width: 100%;
    max-width: 500px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
    text-align: center;
    padding: 40px 25px;
    background: #fff;
    border: 3px solid #000;
    border-radius: 5px;
	}
.manual-mass-close_out-popup .manual-mass-order-popup-wrapper
{
    padding: 0;
    max-width: 500px;
    border: 1px solid #444;
    min-height: 196px;
}
.manual-mass-close_out-popup span
{
    padding-bottom: 6px;
	display: inline-block;
    font-size: 18px;
    font-weight: 500;
    border-bottom: 1px solid #444;
    color: #fff;
    background: #444;
    padding: 13px 0;
}

.manual-mass-close_out-popup #m-txt-link
{
	background: #444;
    color: #fff;
    padding: 6px 30px;
    display: inline-block;
    transition: all 0.5s ease 0s;
	border:1px solid #444;
}
.manual-mass-close_out-popup #m-txt-link:hover
{
	background: #fff;
    color: #444;	 
}
.manual-mass-close_out-popup span
{
	width: 100%;
    margin-bottom: 20px;
}
#close_out_ajax {
    background: #444;
    color: #fff;
    padding: 6px 30px;
    display: inline-block;
    transition: all 0.5s ease 0s;
    border: 1px solid #444;
}
.manual-mass-close_out-popup #close_out_ajax:hover
{
	background: #fff;
    color: #444;	
}
.show
{
	display:inline-block;
}
.hide
{
	display:none;
}
#loading_ajax
{
	float: right;
    position: absolute;
    bottom: 7px;
    right: 64px;
}
.r_pay {
    margin-bottom: 16px;
    padding: 5px 14px;
	font-size: 14px;
    line-height: 21px;
}

.r_pay p {
    color: #000;
    font-size: 14px;
    margin: 0;
}
.manual-mass-close_out-popup table
{
	    border: 1px solid black;
    width: 100%;
    height: 115px;
    background: #f6f6f6;
}
.manual-mass-close_out-popup table caption
{
	text-align: left;
    font-size: 20px;
    font-weight: 600;
    padding-bottom: 12px;
}
.manual-mass-close_out-popup td {
    text-align: left;
    padding-left: 45px;
    font-size: 18px;
    font-weight: 600;
}

</style>
<script>
	jQuery(document).ready(function () 
	{
		jQuery(document).on('click', '#pay_now', function (e) 
		{
			e.preventDefault();
			let ref_id = jQuery(this).attr('data-id');
			let ref_name = jQuery(this).attr('data-name');
			let ref_paid = jQuery(this).attr('data-paidstatus');
			let ref_comm = jQuery(this).attr('data-val');
		//	let ref_fundids = jQuery(this).attr('data-fundids');
			if(ref_comm!=null)
			{
				var ref_commNew="Pay $"+parseFloat(ref_comm).toFixed(2);
			}
			jQuery('#close_out_ajax').attr('data-id',ref_id);
			jQuery('#close_out_ajax').attr('data-paidstatus',ref_paid);
		//	jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper .r_pay .valueData4").text(ref_fundids);
			jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper .r_pay .valueData").text("#"+ref_name);
			jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper .r_pay .valueData3").text(ref_paid);
			jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper .r_pay .valueData2").text("$"+ref_comm);
			jQuery("#close_out_ajax").text(ref_commNew);
			if(ref_comm==0)
			{
				jQuery("#close_out_ajax").css("display","none");
			}
			else if(ref_comm>0)
			{
				jQuery("#close_out_ajax").css("display","inline-block");
			}
			else{
				jQuery("#close_out_ajax").css("display","none");
			}
			jQuery(".manual-mass-close_out-popup").addClass("show");
		});
			jQuery(document).on('click', '#m-txt-link, .close_btn', function (e) 
			{
				e.preventDefault();
				jQuery(".manual-mass-close_out-popup").removeClass("show");
			});
	});	
	var ajax_url= "<?php echo admin_url('admin-ajax.php'); ?>";
	jQuery(document).on('click', '#close_out_ajax', function (e){
			e.preventDefault();
			jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper p.msg").text("");
			jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper p.msg").text("Please Wait..");
			jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper p.msg").css("display","block");
			
			let ref_id_n = jQuery(this).attr('data-id');
			let ref_act_n = jQuery(this).attr('data-action');
			let ref_paidCheck = jQuery(this).attr('data-paidstatus');
			
			if(ref_paidCheck=="Unpaid"){
			jQuery.ajax({
				 type : "post",
				 url : ajaxurl,
				 data : {action: 'representative_paynow', referralID:ref_id_n,paid:ref_act_n},
				 success: function(response) {

					console.log(response);
					jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper p.msg").text("Successfully Updated!!");
					setTimeout(function(){ jQuery(".manual-mass-close_out-popup").removeClass("show");  location.reload(); }, 3000);
				
					/*if(response.data.action==1)
					{
						jQuery("#fund_"+st_id+" #6_").html('<span id="" class="label label-success">Paid</span>');
						
					}
					if(response.data.action==2)
					{
						jQuery("#fund_"+st_id+" #6_").html('<span id="" class="label label-danger">Unpaid</span>');
					}
					*/
					//setTimeout(function(){ jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper p.msg").text(""); }, 2000);
	
				},
				 error: function(jqXHR,error, errorThrown) {  
					   if(jqXHR.status&&jqXHR.status==400){
							console.log(jqXHR.responseText); 
							jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper p.msg").text("Something went wrong!");
					   }else{
						   jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper p.msg").text("Something went wrong!");
					   }
				}
			});
	    }
		});
	</script>
<?php } }
function custom_add_new_reps_submenu_page_callback(){

    include_once __DIR__ . '/add-reps.php';
}
?>
