<?php
/* Template Name: Fundraising Representative Template */ 

get_header();

if ( is_user_logged_in()) {
		
	$current_user = wp_get_current_user();
	//print_r($current_user);

	$current_userID = $current_user->ID;
	$user_info = get_userdata($current_userID);
	if(in_array( 'representative', (array) $user_info->roles )){
 ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/css/custom-rep.css">
	
<style>
.fade{opacity:1;}
table tr:first-child {
    background-color: transparent;
}
thead {
    background-color: #E6EEF7;
}
a.nav-link {
    font-weight: 600;
    font-size: 18px;
    line-height: 22px;
    padding: 17px 35px;
    background-color: #f5f7fb !important;
    color: #000;
    border-radius: 0px !important;
    border-color: #dfe0eb !important;
    height: fit-content;
    //margin: auto 0 !important;
    height: 58px;
}
a.nav-link.active {
    background-color: #000 !important;
    color: #fff !important;
}
a.nav-link span {
    font-weight: 600;
    font-size: 12px;
    line-height: 22px;
    background: #800303;
    border-radius: 200px;
    padding: 5px;
    margin-left: 6px;
    color: #fff;
}
a.nav-link b {
    -webkit-text-stroke: 0.5px #000;
    letter-spacing: 1px;
}
a.nav-link.active b {
    -webkit-text-stroke: 0.5px #fff;
}
#inner-tabContent a.nav-link {
    padding: 6px 12px;
    height: auto;
    border-radius: 10px 10px 0px 0px !important;
    border-bottom: 0px;
    margin-bottom: -1px;
    margin-left: 8px;
    margin-right: 8px;
    /*background-color: #e2e4e5 !important; */
}
#inner-tabContent a.nav-link.active {
    background-color: #000 !important;
}
.tab-content table tr td a.btn:hover {
    border-bottom: 1px solid;
}
.tab-content table tr td a.btn {
    padding: 0;
    border-radius: 0;
}
#inner-tabContent a.nav-link
{
	padding: 5px 20px;
}
a.nav-link {
    font-size: 16px;
}
h5.inner-tab-title {
    font-size: 16px;
}
.nav-link:focus, .nav-link:hover
{
	color: inherit;
}
h5.inner-tab-title {
    font-weight: 500;
    line-height: 21px;
    font-size: 16px;
}
#inner-tabContent a.nav-link {
    padding: 10px 20px;
}
#inner-tabContent a.nav-link {
  /*  background-color: rgb(226 228 229 / 20%) !important;
    box-shadow: 0 2px 2px 0 rgb(0 0 0 / 4%); */
    border: 1px solid #E6EEF7!important;
	background-color: #E6EEF7 !important;
    letter-spacing: 0.2px;
    font-weight: 500;
}

div.inner-tabContent
{
	border: 1px solid #eaeaea;
    border-radius: 0.5rem;
    border-color: #eaeaea;
    padding: 30px 30px 60px;
    background-color: #ffffff;
    box-shadow: 0 5px 18px 0 rgb(0 0 0 / 8%)
}
.account-info-img-new
{
	position: absolute;
    top: 50%;
    right: 20px;
    transform: translateY(-50% );
}
#manage-fundraisers-tab-pane .lineinbtn
{
	font-size: 15px;
    text-transform: capitalize;
    padding: 10px 20px;
    font-weight: 500;
}
p.inner-tab-label {
    font-size: 16px;
    line-height: 22px;
    color: #262a2e;
}
table.manage-tab-22
{
	margin-top: 15px;
}
table th{

    background-color: rgb(226 228 229 / 20%) !important;	
}
.new-heading
{
	padding: 10px 0px 22px;
}
.new-heading.firsttabbtn {
    padding: 10px 0px 22px;
    display: inline-block;
    position: absolute;
    margin-top: 50px;
    margin-left: 20px;
    text-transform: capitalize;
}
#fundraisers-reports-tab-pane table tr td a{
	     text-decoration: none;
 }
/*.tab-content .nav-item
 {
	 display:none;
 }
 */
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
	    font-size: 15px;
    font-weight: 600;
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
a.btn-primary {
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
a.btn-primary:hover {
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out,-webkit-box-shadow .15s ease-in-out;
    opacity: 0.95;
}
#inner-tabContent .nav-link.active-content-show
{
	box-shadow: 0 2px 2px 0 rgb(0 0 0 / 4%);
    border: 1px solid rgb(226 228 229 / 42%) !important;
	background-color: #000 !important;
	    color: #fff;
}
#inner-tabContent a.nav-link.active-content-show:hover
{
	color:#fff;
}
.main-content-representative .tab-content>.active-content
{
	opacity: 1;
}
.tab-content table tr td a.btn:hover
{
	    border: 0;
}
.tab-content table tr td a.btn
{
	color: #000;
    text-decoration: none;
    text-align: center;
    font-weight: 500;
}
#manage-fundraisers-tab-pane .lineinbtn:focus {
    color: #fff;
}
#inner-tabContent a.nav-link:hover {
    background-color: #000 !important;
    color: #fff;
}
.your-account-balance
{
	    background-color: #E6EEF7 !important;
    color: #000;
}
.your-account-balance h5, .your-account-balance h1 {
    color: #000;
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
@media(max-width:990px){
.tab-content-new
{
	    width: 100%;
    display: inline-block;
    position: relative;
    overflow: scroll;
}
}
@media(max-width:767.98px){
 
    #inner-tabContent a.nav-link {
        font-size: 16px !important;
        border-radius: 10px 10px 10px 10px !important;
    }

}
/*    .page-id-38050 .header-wrapper{display:none;}
    .page-id-38050 .main-footer{display:none;}
    .page-id-38050 .copyright{display:none;}*/
   </style>
	
    <?php
	
    
	$current_email = $current_user->user_email;
	$ref_firstname_2311 = get_user_meta($current_userID,'first_name',true);
	$ref_lastname_2311 = get_user_meta($current_userID,'last_name',true);
	$phone = get_user_meta($current_userID,'phone',true);
	$sex = get_user_meta($current_userID,'sex',true);
	$reps_company = get_user_meta($current_userID,'billing_company',true);
	$reps_country = get_user_meta($current_userID,'billing_country',true);
	$reps_city = get_user_meta($current_userID,'billing_city',true);
	$reps_address = get_user_meta($current_userID,'billing_address_1',true);
	$reps_commission = get_user_meta($current_userID,'reps_commission',true);
		
?>

<?php if($_GET['manage']=="true"){ ?>
<section class="main-content-representative">
<div class="container my-5 py-5">
    
	<div class="tab-content res-tabingcls" id="inner-tabContent">
	  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-outstanding-tab" tabindex="0">
		<ul class="nav nav-tabs justify-content-center border-0" id="myTab" role="tablist">
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/" class="nav-link border-0" id="account-information-tab">Account Information</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?manage=true" class="active-content-show nav-link border-0" id="manage-fundraisers-tab">Manage Fundraisers</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?payment_historytab=true" class="nav-link border-0" id="payment-history-tab" >Payment History</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_reports=true"  class="nav-link border-0" id="fundraisers-reports-tab">Fundraisers Reports</a>
		  </li>
		
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_operation=true" class="nav-link border-0" id="fundraisers-operation-entry-tab" >Fundraisers Operation</a>
		  </li>
		</ul>
		<div class="tab-content tab-content-new inner-tabContent" id="myTabContent">
		  <div class="active-content" id="manage-fundraisers-tab-pane" role="tabpanel" aria-labelledby="manage-fundraisers-tab" tabindex="0">
		  <h1 class="new-heading ft-size">Manage Fundraisers</h1>
			<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>fundraiser-register/?repID=<?php echo $current_userID; ?>" class="btn btn-dark mb-4 lineinbtn" >
				<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
				  <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"></path>
				  <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"></path>
				</svg>
				<span style="
				width: 100%;
				display: block;
				margin-top: 5px;">Create A New Fundraisers</span>
			</a>

			<!--<div class="modal fade popsection" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			    <div class="modal-dialog modal-dialog-centered">
			        <div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Create A New Fundraisers</h5>
							<button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="frontend_fundraiser_regi"><?php echo do_shortcode('[user_registration_form id="21998"]'); ?> </div>
						</div>			      
					</div>
			    </div>
			</div>-->
			<table class="manage-tab-22">
			<thead>
				<tr>
					<th>Fundraisers ID</th>
					<th>Organization</th>
					<th>Fundraisers Email</th>					
					<th>First Name</th>
					<th>Last Name</th>
					<th>Active Status</th>
					<th>Action</th>
				</tr>
				</thead>
				<?php 
				$total_users=0;
				$total_query=0;
				$exist_fids_arr=array();
				$current_payment_postid=get_user_meta($current_userID,"um_unique_payment_id",true);
				$fundraiser_ids=get_post_meta($current_payment_postid,"fundraiser_ids",true);
				if(!empty($fundraiser_ids))
				{
					$exist_fids_arr=explode(",",$fundraiser_ids);
					$q=1;
				}
				
				if($q==1)
				{
					$number = 20;
					$page = isset($_GET['pg']) ? $_GET['pg'] : 1;
					$offset = ($page - 1) * $number;
					
					/*get fundraiser list which connected representative */
					$fundraisers_users = get_users(array( 
						'role' => array( 'fundraiser' ),
						'include' => $exist_fids_arr,
						'meta_key'     => 'user_registration_referral_email',
						'meta_value'   => $current_email,
						'meta_compare' => '='
					));
					
					$fundraisers_list = get_users(array( 
						'role' => array( 'fundraiser' ),
						'include' => $exist_fids_arr,
						'meta_key'     => 'user_registration_referral_email',
						'meta_value'   => $current_email,
						'meta_compare' => '=',
						'number' => $number,
						'offset' => $offset
					));	
					$fundraiserTotal = count($fundraisers_list);
					
					$total_users = count($fundraisers_users);
					$total_query = count($fundraisers_list);
					$total_pages = intval($total_users / $number);
				
				
				foreach($fundraisers_list as $fundraisers) {
					$ID = $fundraisers->data->ID;
	            //	$name = $fundraisers->data->user_login;
				    $org = get_user_meta($ID,'user_registration_name_of_school_business',true );
					$action_status= get_user_meta($ID,'user_action_status',true );
					if(empty($org))
					{
						$org=get_user_meta($ID,'user_registration_input_company',true );
					}
	            	$email = $fundraisers->data->user_email;
	            	$firstname =  get_user_meta($fundraisers->data->ID,'first_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);
				?>
				<tr>
					<td><?php echo $ID;?></td>
					<td><?php echo $org;?></td>
					<td><?php echo $email;?></td>
					<td><?php echo $firstname;?></td>
					<td><?php echo $lastname;?></td>
					<td id="4_" style="vertical-align: top; text-align: left; "><?php if($action_status==1){?><span id="action_s" class="">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="">Inactive</span><?php }  else { ?><span id="action_st" class="">Active</span><?php } ?></td>							
					<td id="5_" style="vertical-align: top; text-align: left; "><?php if(empty($action_status) || $action_status==1){ ?><span id="close_out" data-repsid="<?php echo $current_userID; ?>" data-id="<?php echo $fundraisers->data->ID; ?>" class="">Close Out</span><?php }  elseif($action_status==2){ ?><span id="action_s2_22" class=""></span><?php }  else { } ?></td>							
				</tr>
			<?php } ?>
		
			
				<?php  } ?>
			</table>
			<?php
						// grab the current query parameters
				$query_string = $_SERVER['QUERY_STRING'];

				// The $base variable stores the complete URL to our page, including the current page arg

				// if in the admin, your base should be the admin URL + your page
				//$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

				// if on the front end, your base is the current page
				$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('pg', $query_string) . '%_%';
			?>
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
		  
		  </div>
		
	  </div>
	  
	</div>
</div>
  <div class="manual-mass-close_out-popup">
        <div class="manual-mass-order-popup-wrapper">
		<div class="close_btn" style="
    position: absolute;
    right: 10px;
    top: 5px;
    color: #cda85c;
    font-weight: 400;
    cursor: pointer;
"><i class="fa fa-close" style="font-size:24px;"></i></div>
		<span id="heading_dds2">Fundraiser #ID:<b id="heading_dds"></b></span>
		<span>Do you want to close fundraiser campaign?</span>
            <a id="close_out_ajax" class="txt-link" data-id="" data-repsid="">Yes</a>
            <a href="#" id="m-txt-link" class="txt-link">No</a>
        </div>
    </div>
	<style>
		#close_out
	{
		cursor: pointer;
	}
	.manual-mass-close_out-popup
	{
		position: fixed;
		width: 100%;
		height: 100%;
		z-index: 999;
		background: rgba(0,0,0,0.6);
		top: 0;
		left: 0;
		display: none;
	}
	.manual-mass-close_out-popup .manual-mass-order-popup-wrapper
	{
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
		padding: 30px 25px;
		max-width: 600px;
		border: 3px solid #cda85c; 
	}
	.manual-mass-close_out-popup span
	{
		padding-bottom: 6px;
		display: inline-block;
		font-size: 18px;
		font-weight: 700;
		border-bottom: 2px dotted #cda85c;
		color: #cda85c;
	}
	.manual-mass-close_out-popup #m-txt-link
	{
		background: #cda85c;
		color: #fff;
		padding: 6px 30px;
		display: inline-block;
		transition: all 0.5s ease 0s;
	}
	.manual-mass-close_out-popup #m-txt-link:hover
	{
		   background: #fff;
		color: #cda85c;
		border:1px solid #cda85c;
			box-sizing: border-box;
	}
	.manual-mass-close_out-popup span
	{
		width: 100%;
		margin-bottom: 20px;
	}
	#close_out_ajax
	{
		background: #cda85c;
		color: #fff;
		padding: 6px 30px;
		display: inline-block;
		transition: all 0.5s ease 0s;
	}
	.manual-mass-close_out-popup #close_out_ajax:hover
	{
		   background: #fff;
		color: #cda85c;
		border:1px solid #cda85c;
			box-sizing: border-box;
	}
	.show_load
	{
		display:inline-block;
	}
	.hide_load
	{
		display:none;
	}
	#heading_dds2
	{
		margin-bottom: 5px;
	}
	</style>
	<script>
		jQuery(document).on('click', '.close_btn,#m-txt-link', function (e) 
		{
			e.preventDefault();
			jQuery("#close_out_ajax").attr('data-id','');
			jQuery("#heading_dds").text("");
			jQuery(".manual-mass-close_out-popup").removeClass("show");
				
		});
		jQuery(document).on('click', '#close_out', function (e) 
		{
			e.preventDefault();
			
			
			var fid_21= jQuery(this).attr('data-id');
			var repsid_21= jQuery(this).attr('data-repsid');
			let fund_id = jQuery("#close_out_ajax").attr('data-id');
			//alert(fund_id);
			if(fund_id=="")
			{
				//alert("ok");
				jQuery("#heading_dds").append(fid_21);
			}
			jQuery("#close_out_ajax").attr('data-id',fid_21);
			jQuery("#close_out_ajax").attr('data-repsid',repsid_21);
			
		
			jQuery(".manual-mass-close_out-popup").addClass("show");
		});
	
		jQuery(document).on('click', '#close_out_ajax', function (e) 
		{
				e.preventDefault();
				let fund_id = jQuery(this).attr('data-id');
				let reps_id = jQuery(this).attr('data-repsid');
				jQuery("#loading").removeClass("hide_load");
				jQuery("#loading").addClass("show_load");
				jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper").html('<p style="padding-top:6px;color:green;font-size: 16px;">Please Wait...</p>');
				
						
				jQuery.ajax({

					type : "post",

					url : ajaxurl,

					data : {action: 'close_out_fundraiser', fund_id:fund_id,reps_id:reps_id},
	
					success: function(response) {
						console.log(response);
						jQuery("#loading").removeClass("show_load");
						jQuery("#loading").addClass("hide_load");
						console.log(response);
						if(response.data.action=="yes")
						{
								let fund_id=response.data.fund_id;
								let msg=response.data.msg;
								jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper").html('<p style="padding-top:6px;color:green;font-size: 16px;">'+msg+'</p>');
								jQuery.ajax({

								type : "post",

								url : ajaxurl,

								data : {action: 'wpm_order_merge_ajax', fid:fund_id},
				
								success: function(response) {
									console.log(response);
									jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper").html('<p style="padding-top:6px;color:green;font-size: 16px;">'+response.data.action+'</p>');
									setTimeout(function(){ jQuery(".manual-mass-close_out-popup").removeClass("show"); location.reload(); }, 2000);
								
								}
							});
						}							
						else{
							jQuery(".manual-mass-close_out-popup .manual-mass-order-popup-wrapper").html('<p style="padding-top:6px;color:green;font-size: 16px;">'+response+'</p>');
							setTimeout(function(){ jQuery(".manual-mass-close_out-popup").removeClass("show"); location.reload(); }, 2000);
								
							}
					
					}
				});

			});
	</script>

</section>

<?php } else if($_GET['payment_historytab']=="true"){ ?>
<section class="main-content-representative">
<div class="container my-5 py-5">
    
	<div class="tab-content res-tabingcls" id="inner-tabContent">
	  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-outstanding-tab" tabindex="0">
		<ul class="nav nav-tabs justify-content-center border-0" id="myTab" role="tablist">
		  <li class="nav-item" style="display:block;" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/" class="nav-link border-0" id="account-information-tab">Account Information</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?manage=true" class="nav-link border-0" id="manage-fundraisers-tab">Manage Fundraisers</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?payment_historytab=true" class="active-content-show nav-link border-0" id="payment-history-tab" >Payment History</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_reports=true"  class="nav-link border-0" id="fundraisers-reports-tab">Fundraisers Reports</a>
		  </li>
		
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_operation=true" class="nav-link border-0" id="fundraisers-operation-entry-tab" >Fundraisers Operation</a>
		  </li>
		</ul>
		<div class="tab-content tab-content-new inner-tabContent" id="myTabContent">
		  <div class="active-content" id="payment-history-tab-pane" role="tabpanel" aria-labelledby="payment-history-tab" tabindex="0">
		   <h1 class="new-heading ft-size">Payment History</h1>
		   <table class="">
		<thead>
		  <tr>
			
			<th id="" style="vertical-align: top; text-align: left;">#Payment ID</th>
			<th id="" style="vertical-align: top; text-align: left;">Reps ID</th>
			<th id="" style="vertical-align: top; text-align: left;">Payment Status</th>
			<th id="" style="vertical-align: top; text-align: left;">Payment Date</th>
			<th id="" style="vertical-align: top; text-align: left;">Total Fundraiser</th>
			<th id="" style="vertical-align: top; text-align: left;">Referral Commission Amount</th>
			<th id="" style="vertical-align: top; text-align: left;">Fundraisers List</th>
			<th id="" style="vertical-align: top; text-align: left;">Final Report</th>
		 </tr>
		</thead>
		<tbody>
	<?php 
		$cno=1;
		$a=0;
		$userdata = get_userdata( $current_userID );
        $m=array();
		$payment_history_arr=array();
		$current_email=$userdata->user_email;
		
		$current_payment_id=get_user_meta($current_userID, "um_unique_payment_id", true );
		$payment_history_str=get_user_meta($current_userID,'um_unique_payment_id_arr',true);
	
		if(!empty($payment_history_str))
		{
			$payment_history_arr=explode(",",$payment_history_str);
			if (in_array($current_payment_id,$payment_history_arr)) {
				//array_push($payment_history_arr, $current_payment_id);
				$a=1;
			}
		}
	 
	
		 if(!empty($payment_history_arr))
		 {
				$the_query = new WP_Query( array( 
								'post_type' => 'payment_history',
								'posts_per_page' => 10,
								'order' => 'DESC',
								'post_status' =>  array('publish','draft'),
								'post__in'   => $payment_history_arr,
								'paged' => get_query_var('paged') ? get_query_var('paged') : 1)  
							);
				if ( $the_query->have_posts() ):
					while ( $the_query->have_posts() ) : $the_query->the_post();
						 $ID=get_the_ID();
						 $payment_id = get_field( 'payment_id' );
						 $payment_date = get_field( 'payment_date' );
						 $reps_id = get_field( 'reps_id' );
						 $fundraiser_ids_all = get_field( 'fundraiser_ids' );
						 $fundraiser_ids = get_field( 'after_payment_fundraiser_ids_inactive' );
						 $paid_status = get_field( 'paid_status' );
						 $total_commission = get_field( 'total_commission' );
						 $total_amount = get_field( 'total_amount' );
						 if($fundraiser_ids)
						 {
							$m=explode(",",$fundraiser_ids);
							$m_fund=count($m);
						 }
						
				?>
				
				<tr id="<?php echo "rep_".$ID; ?>" class="<?php if($cno % 2 == 0){ echo "even "; } else{ echo "odd "; }  if($a==1) { echo "current_payment_id"; } else{} ?>">
					<td id="1_" style="vertical-align: top; text-align: left;"><?php if($ID){ echo "#".$ID; } else{ } ?></td>
					<td id="2_" style="vertical-align: top; text-align: left;"><?php if($current_userID){ echo '#'.$current_userID; } else{  } ?></td>	
					<td id="7_" style="vertical-align: top; text-align: left;"><?php if($paid_status){ echo "<b class='paid'>Paid</b>"; } else{ echo "<b class='unpaid'>Unpaid</b>"; } ?></td>					
					<td id="3_" style="vertical-align: top; text-align: left;"><?php if($payment_date){ echo $payment_date; } else{ } ?></td>					
					<td id="4_" style="vertical-align: top; text-align: left;"><?php if($m_fund){ echo $m_fund; } else{ } ?></td>
					<td id="5_" style="vertical-align: top; text-align: left;"><?php if($total_commission){ echo '$'.$total_commission; } else{ } ?></td>
					<td><a style="text-decoration: none;" href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?<?php  echo "fundraisers_list=true&PID=".$ID; ?>"><?php echo "View"; ?></a></td>
					<td><a style="text-decoration: none;" href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-print/?<?php  echo "r_id=".$current_userID."&pid=".$ID; ?>"><?php echo "View"; ?></a></td>
				</tr>
				<?php  $cno++;
						endwhile;
						wp_reset_postdata();
						endif;
				}
                ?>
				
			
		
		
		</table>
		<div class="page_links">
		<?php
							$big = 999999999; // need an unlikely integer
							 echo paginate_links( array(
								'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
								'format' => '?paged=%#%',
								'current' => max( 1, get_query_var('paged') ),
								'total' => $the_query->max_num_pages
							) );
						
					
				?>
			 </div>	
		  </div>
		  
		  </div>
		
	  </div>
	  
	</div>
</div>
</section>
<style>
.current_payment_id
{
	display:none; 
}
a.next.page-numbers, a.prev.page-numbers, a.page-numbers {
    font-size: 16px;
}
span.page-numbers.current {
    font-size: 18px;
}
</style>
<?php } else if($_GET['fundraisers_list']=="true"){
	
	$PID=$_GET['PID'];
	if(!empty($PID))
	{
	$paid_status_check=get_post_meta($PID,"paid_status",true);
	/* New Start */
	$total_referral_comm = 0;
	$totalAmount_full=0;
	$fundraisers_list=array();
	$fundraisers_list2=array();
	
	$exist_fids_arr=array();
	$q=0;
	$fundraiserTotal2=0;
	$fundraiserTotal=0;
	$total_users=0;
	$total_users=0;
	$total_query=0;

	//$current_payment_postid=get_user_meta($current_userID,"um_unique_payment_id",true);
	
	if($paid_status_check==0)
	{
		$fundraiser_ids=get_post_meta($PID,"fundraiser_ids",true);
		if(!empty($fundraiser_ids))
		{
			$exist_fids_arr=explode(",",$fundraiser_ids);
			$q=1;
		}
	}
	else if($paid_status_check==1)
	{
		$fundraiser_ids=get_post_meta($PID,"after_payment_fundraiser_ids_inactive",true);
		if(!empty($fundraiser_ids))
		{
			$exist_fids_arr=explode(",",$fundraiser_ids);
			$q=1;
		}
	}
	else{
		$fundraiser_ids=get_post_meta($PID,"fundraiser_ids",true);
		if(!empty($fundraiser_ids))
		{
			$exist_fids_arr=explode(",",$fundraiser_ids);
			$q=1;
		}	
	}
	
?>
<section class="main-content-representative">
<div class="container my-5 py-5">
    
	<div class="tab-content res-tabingcls" id="inner-tabContent">
	  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-outstanding-tab" tabindex="0">
		<ul class="nav nav-tabs justify-content-center border-0" id="myTab" role="tablist">
		  <li class="nav-item" style="display:block;" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/" class="nav-link border-0" id="account-information-tab">Account Information</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?manage=true" class="nav-link border-0" id="manage-fundraisers-tab">Manage Fundraisers</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?payment_historytab=true" class="active-content-show  nav-link border-0" id="payment-history-tab" >Payment History</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_reports=true"  class="nav-link border-0" id="fundraisers-reports-tab">Fundraisers Reports</a>
		  </li>
		
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_operation=true" class="nav-link border-0" id="fundraisers-operation-entry-tab" >Fundraisers Operation</a>
		  </li>
		</ul>
		<div class="tab-content tab-content-new inner-tabContent" id="myTabContent">
		  <div class="active-content" id="fundraisers-reports-tab-pane2" role="tabpanel" aria-labelledby="fundraisers-reports-tab" tabindex="0">
		  <h1 class="new-heading ft-size"><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?payment_historytab=true"><i title="Back" class="fa fa-chevron-left" style="font-size:24px;padding-right: 20px;color: #000000;cursor: pointer;"></i></a>Fundraisers List</h1>
			<table>
				<thead>
				<tr>
					<th>Fundraisers ID</th>
					<th>Organization</th>
					<th>Online Payment</th>
					<th>Ship to School Payment</th>
					<th>Catalog Payment</th>
					<th>Total Payment</th>
					<th>Referral Commission Amount</th>
				</tr>
				</thead>
				<?php 
				
				if($q==1)
				{
				$i=0;
				$full_fundraiser_reports=array();
				$number   = 10;
				$page = isset($_GET['pg']) ? $_GET['pg'] : 1;
				$offset   = ($page - 1) * $number;
				
				/*get fundraiser list which connected representative */
				$fundraisers_users = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '='
				));
				
				$fundraisers_list = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '=',
					'number' => $number,
					'offset' => $offset
				));	
				$fundraiserTotal = count($fundraisers_list);
				
				$total_users = count($fundraisers_users);
				$total_query = count($fundraisers_list);
				$total_pages = intval($total_users / $number);
			
				$fundraisers_list2 = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '=',
				));	
				$fundraiserTotal2 = count($fundraisers_list2);
						
				$fundraisers_list = get_users(array( 
							'role' => array( 'fundraiser' ),
							'include' => $exist_fids_arr,
							'meta_key'     => 'user_registration_referral_email',
							'meta_value'   => $current_email,
							'meta_compare' => '=',
							'number' => $number,
							'offset' => $offset
				));	
		
	            
				foreach($fundraisers_list as $fundraisers) {
							$ID = $fundraisers->data->ID;
							$action_status= get_user_meta($ID,'user_action_status',true );
							
							if($action_status==2)
							{
							//$name = $fundraisers->data->user_login;
									$action_status= get_user_meta($ID,'user_action_status',true );
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
							$subtot=0;

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
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
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
									if($in_s==1){
										
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
										else if($frID3 == $order_customer_id){
											if($order_type=="Catlog"){
												$subtot=$order->get_subtotal(); 
												$total_sale_so_far_Catlog+=$subtot; 
											}
										}
										else{
										
										}
									}
									else {
										if($frID3 == $order_customer_id){
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
							
				?>
				<tr>
					<td><?php echo $ID;?></td>
					<td><?php echo $org;?></td>	
					<td>$<?php echo $total_sale_so_far = number_format($total_sale_so_far, 2, '.', ''); ?></td>		
					<td>$<?php echo $total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', ''); ?></td>							
					<td>$<?php echo $total_sale_so_far_Catlog = number_format($total_sale_so_far_Catlog, 2, '.', ''); ?></td>
					<td>$<?php echo $totalAmount = number_format($totalAmount, 2, '.', ''); ?></td>
					<td>$<?php echo $get_referral_commission_amount; ?></td>
					
				</tr>
				<?php  } }
				}
				
			?>
			
			
			</table>
				<?php
						// grab the current query parameters
				$query_string = $_SERVER['QUERY_STRING'];

				// The $base variable stores the complete URL to our page, including the current page arg

				// if in the admin, your base should be the admin URL + your page
				//$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

				// if on the front end, your base is the current page
				$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('pg', $query_string) . '%_%';
			?>
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
		 
		 </div>
		
	  </div>
	  
	</div>
</div>
</section>
<?php } } else if($_GET['fundraisers_reports']=="true"){

/* New Start */
	$total_referral_comm = 0;
	$totalAmount_full=0;
	$fundraisers_list=array();
	$fundraisers_list2=array();
	
	$exist_fids_arr=array();
	$q=0;
	$fundraiserTotal2=0;
	$fundraiserTotal=0;
	$total_users=0;
	$total_users=0;
	$total_query=0;

	$current_payment_postid=get_user_meta($current_userID,"um_unique_payment_id",true);
	$fundraiser_ids=get_post_meta($current_payment_postid,"fundraiser_ids",true);
	if(!empty($fundraiser_ids))
	{
		$exist_fids_arr=explode(",",$fundraiser_ids);
		$q=1;
	}
	else{
			$exist_fids_arr=0;
	}
	
?>
<style>
#nav-tab-sub
{
	margin-bottom: 35px !important;
    border-radius: 10px;
    overflow: hidden;
    border: none;
    margin-top: 10px;
    padding-bottom: 2px;
}
</style>
<section class="main-content-representative">
<div class="container my-5 py-5">
    
	<div class="tab-content res-tabingcls" id="inner-tabContent">
	  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-outstanding-tab" tabindex="0">
		<ul class="nav nav-tabs justify-content-center border-0" id="myTab" role="tablist">
		  <li class="nav-item" style="display:block;" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/" class="nav-link border-0" id="account-information-tab">Account Information</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?manage=true" class="nav-link border-0" id="manage-fundraisers-tab">Manage Fundraisers</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?payment_historytab=true" class="nav-link border-0" id="payment-history-tab" >Payment History</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_reports=true"  class="active-content-show nav-link border-0" id="fundraisers-reports-tab">Fundraisers Reports</a>
		  </li>
		
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_operation=true" class="nav-link border-0" id="fundraisers-operation-entry-tab" >Fundraisers Operation</a>
		  </li>
		</ul>
		<div class="tab-content tab-content-new inner-tabContent" id="myTabContent">
		  <div class="active-content" id="fundraisers-reports-tab-pane" role="tabpanel" aria-labelledby="fundraisers-reports-tab" tabindex="0">
		  <h1 class="new-heading ft-size">Fundraisers Reports</h1>
		  <nav>
			  <div class="nav nav-tabs" id="nav-tab-sub" role="tablist">
				<button class="nav-link active" id="nav-active-tab" data-bs-toggle="tab" data-bs-target="#nav-active" type="button" role="tab" aria-controls="nav-active" aria-selected="true">Active</button>
				<button class="nav-link" id="nav-inactive-tab" data-bs-toggle="tab" data-bs-target="#nav-inactive" type="button" role="tab" aria-controls="nav-inactive" aria-selected="false">Inactive</button>
			  </div>
		 </nav>
		 <div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active" id="nav-active" role="tabpanel" aria-labelledby="nav-active-tab">
			
			<!-- Active Fundraiser -->
			<table>
				<thead>
				<tr>
					<th>Fundraisers ID</th>
					<th>Organization</th>
					<th>Active Status</th>
					<th>Online Payment</th>
					<th>Ship to School Payment</th>
					<th>Catalog Payment</th>
					<th>Total Payment</th>
					<th>Referral Commission Amount</th>
					<th>Order Detail</th>
				</tr>
				</thead>
				<?php 
				
				if($q==1)
				{
				$i=0;
				$full_fundraiser_reports=array();
				$number   = 20;
				$page = isset($_GET['pg']) ? $_GET['pg'] : 1;
				$offset   = ($page - 1) * $number;
				
				/*get fundraiser list which connected representative */
				$fundraisers_users = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '='
				));
				
				$fundraisers_list = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '=',
					'number' => $number,
					'offset' => $offset
				));	
				$fundraiserTotal = count($fundraisers_list);
				
				$total_users = count($fundraisers_users);
				$total_query = count($fundraisers_list);
				$total_pages = intval($total_users / $number);
			
				$fundraisers_list2 = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '=',
				));	
				$fundraiserTotal2 = count($fundraisers_list2);
						
				$fundraisers_list = get_users(array( 
							'role' => array( 'fundraiser' ),
							'include' => $exist_fids_arr,
							'meta_key'     => 'user_registration_referral_email',
							'meta_value'   => $current_email,
							'meta_compare' => '=',
							'number' => $number,
							'offset' => $offset
				));	
		
	            
				foreach($fundraisers_list as $fundraisers) {
							$ID = $fundraisers->data->ID;
							//$name = $fundraisers->data->user_login;
							$action_status= get_user_meta($ID,'user_action_status',true );
							
							if( empty($action_status) || $action_status==1)
							{
								
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
							$subtot=0;

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
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
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
									if($in_s==1){
										
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
										else if($frID3 == $order_customer_id){
											if($order_type=="Catlog"){
												$subtot=$order->get_subtotal(); 
												$total_sale_so_far_Catlog+=$subtot; 
											}
										}
										else{
										
										}
									}
									else {
										if($frID3 == $order_customer_id){
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
							
				?>
				<tr>
					<td><?php echo $ID;?></td>
					<td><?php echo $org;?></td>	
					<td id="4_" style="vertical-align: top; text-align: left; "><?php if($action_status==1){?><span id="action_s" class="">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="">Inactive</span><?php }  else { ?><span id="action_st" class="">Active</span><?php } ?></td>							
					<td>$<?php echo $total_sale_so_far = number_format($total_sale_so_far, 2, '.', ''); ?></td>		
					<td>$<?php echo $total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', ''); ?></td>							
					<td>$<?php echo $total_sale_so_far_Catlog = number_format($total_sale_so_far_Catlog, 2, '.', ''); ?></td>
					<td>$<?php echo $totalAmount = number_format($totalAmount, 2, '.', ''); ?></td>
					<td>$<?php echo $get_referral_commission_amount; ?></td>
					<td><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?<?php  echo "order_detail=true&frid=".$ID; ?>"><?php echo "View"; ?></a></td>
				</tr>
			<?php  
			
				}
			}
				}
				
			?>
			
			
			</table>
				<?php
						// grab the current query parameters
				$query_string = $_SERVER['QUERY_STRING'];

				// The $base variable stores the complete URL to our page, including the current page arg

				// if in the admin, your base should be the admin URL + your page
				//$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

				// if on the front end, your base is the current page
				$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('pg', $query_string) . '%_%';
			?>
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
			<!-- End -->
			
			
			</div>
			<div class="tab-pane fade" id="nav-inactive" role="tabpanel" aria-labelledby="nav-inactive-tab">
			<!-- Inactive Fundraiser -->
			
			<table>
				<thead>
				<tr>
					<th>Fundraisers ID</th>
					<th>Organization</th>
					<th>Active Status</th>
					<th>Online Payment</th>
					<th>Ship to School Payment</th>
					<th>Catalog Payment</th>
					<th>Total Payment</th>
					<th>Referral Commission Amount</th>
					<th>Order Detail</th>
				</tr>
				</thead>
				<?php 
				
				if($q==1)
				{
				$i=0;
				$full_fundraiser_reports=array();
				$number   = 10;
				$page = isset($_GET['pg']) ? $_GET['pg'] : 1;
				$offset   = ($page - 1) * $number;
				
				/*get fundraiser list which connected representative */
				$fundraisers_users = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '='
				));
				
				$fundraisers_list = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '=',
					'number' => $number,
					'offset' => $offset
				));	
				$fundraiserTotal = count($fundraisers_list);
				
				$total_users = count($fundraisers_users);
				$total_query = count($fundraisers_list);
				$total_pages = intval($total_users / $number);
			
				$fundraisers_list2 = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '=',
				));	
				$fundraiserTotal2 = count($fundraisers_list2);
						
				$fundraisers_list = get_users(array( 
							'role' => array( 'fundraiser' ),
							'include' => $exist_fids_arr,
							'meta_key'     => 'user_registration_referral_email',
							'meta_value'   => $current_email,
							'meta_compare' => '=',
							'number' => $number,
							'offset' => $offset
				));	
		
	            
				foreach($fundraisers_list as $fundraisers) {
							$ID = $fundraisers->data->ID;
							//$name = $fundraisers->data->user_login;
							$action_status= get_user_meta($ID,'user_action_status',true );
							if( !empty($action_status) && $action_status==2)
							{
								
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
							$subtot=0;

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
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
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
									if($in_s==1){
										
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
										else if($frID3 == $order_customer_id){
											if($order_type=="Catlog"){
												$subtot=$order->get_subtotal(); 
												$total_sale_so_far_Catlog+=$subtot; 
											}
										}
										else{
										
										}
									}
									else {
										if($frID3 == $order_customer_id){
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
							
				?>
				<tr>
					<td><?php echo $ID;?></td>
					<td><?php echo $org;?></td>	
					<td id="4_" style="vertical-align: top; text-align: left; "><?php if($action_status==1){?><span id="action_s" class="">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="">Inactive</span><?php }  else { ?><span id="action_st" class="">Active</span><?php } ?></td>							
					<td>$<?php echo $total_sale_so_far = number_format($total_sale_so_far, 2, '.', ''); ?></td>		
					<td>$<?php echo $total_sale_so_far2 = number_format($total_sale_so_far2, 2, '.', ''); ?></td>							
					<td>$<?php echo $total_sale_so_far_Catlog = number_format($total_sale_so_far_Catlog, 2, '.', ''); ?></td>
					<td>$<?php echo $totalAmount = number_format($totalAmount, 2, '.', ''); ?></td>
					<td>$<?php echo $get_referral_commission_amount; ?></td>
					<td><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?<?php  echo "order_detail=true&frid=".$ID; ?>"><?php echo "View"; ?></a></td>
				</tr>
			<?php 
				}

			}
				}
				
			?>
			
			
			</table>
				<?php
						// grab the current query parameters
				$query_string = $_SERVER['QUERY_STRING'];

				// The $base variable stores the complete URL to our page, including the current page arg

				// if in the admin, your base should be the admin URL + your page
				//$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';

				// if on the front end, your base is the current page
				$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('pg', $query_string) . '%_%';
			?>
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
			
			<!-- End -->
			
			
			</div>
		</div>
			
		  </div>
		 
		 </div>
		
	  </div>
	  
	</div>
</div>
</section>
<?php } else if($_GET['fundraisers_operation']=="true"){ 
/* New Start */
	$q=0;
	$exist_fids_arr=array();
	$current_payment_postid=get_user_meta($current_userID,"um_unique_payment_id",true);
	$fundraiser_ids=get_post_meta($current_payment_postid,"fundraiser_ids",true);
	if(!empty($fundraiser_ids))
	{
		$exist_fids_arr=explode(",",$fundraiser_ids);
		$q=1;
	}
	else{
			$exist_fids_arr=0;
	}
	

?>
<section class="main-content-representative">
<div class="container my-5 py-5">
    
	<div class="tab-content res-tabingcls" id="inner-tabContent">
	  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-outstanding-tab" tabindex="0">
		<ul class="nav nav-tabs justify-content-center border-0" id="myTab" role="tablist">
		  <li class="nav-item" style="display:block;" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/" class="nav-link border-0" id="account-information-tab">Account Information</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?manage=true" class="nav-link border-0" id="manage-fundraisers-tab">Manage Fundraisers</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?payment_historytab=true" class="nav-link border-0" id="payment-history-tab" >Payment History</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_reports=true"  class="nav-link border-0" id="fundraisers-reports-tab">Fundraisers Reports</a>
		  </li>
		
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_operation=true" class="active-content-show nav-link border-0" id="fundraisers-operation-entry-tab" >Fundraisers Operation</a>
		  </li>
		</ul>
		<div class="tab-content tab-content-new inner-tabContent" id="myTabContent">
		  <div class="active-content" id="fundraisers-operation-tab-pane" role="tabpanel" aria-labelledby="fundraisers-operation-entry-tab" tabindex="0">
				<h1 class="new-heading ft-size">Fundraisers Operation</h1>
				<div class="tab-content" id="myTabContent">
				<table>
				<thead>
				<tr>
					<th style="vertical-align: top; text-align: left;">Fundraisers ID</th>
					<th style="vertical-align: top; text-align: left;">Organization</th>
					<th style="vertical-align: top; text-align: left;">Director</th>
					<th id="" style="vertical-align: top; text-align: left;">Active Status</th>
					<th style="vertical-align: top; text-align: left;">Paid</th>
					<th id="" style="vertical-align: top; text-align: left;">Registered</th>
					<th id="" style="vertical-align: top; text-align: left;">Period</th>
					<!--<th id="" style="vertical-align: top; text-align: left;">Representative Payment</th>-->
					<th id="" style="vertical-align: top; text-align: left;">Action</th>
				</tr>
				</thead>
				<?php 
				if($q==1){
				//print_r($full_fundraiser_reports);
				for($k=0; $k<count($exist_fids_arr); $k++) {
					
					$ID = $exist_fids_arr[$k];
					$f=get_user_meta($ID,'first_name',true );		
					$l=get_user_meta($ID,'last_name',true );	
					$status= get_user_meta($ID,'user_status',true );
					$referral_payment_postid=get_user_meta($ID,'referral_payment_postid',true );
					
					//$paid_status=get_user_meta($referral_payment_postid,'paid_status',true );
					
					$action_status= get_user_meta($ID,'user_action_status',true );
	            	$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
					if(empty($org))
					{
						$org=get_user_meta( $ID , 'user_registration_input_company' , true );
					}
				
	            
					$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
					$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
							
					$currentDate=date("Y-m-d");
					$currentDate = date('Y-m-d', strtotime($currentDate));
					$startDate = date('Y-m-d', strtotime($sd));
					$endDate = date('Y-m-d', strtotime($ed));
							
					$active_date=get_user_meta($ID, "user_close_out_date_active", true);
					$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
					$udata = get_userdata( $ID );
					$registered = $udata->user_registered;
	            	
				?>
				<tr>
					<td id="1_" style="vertical-align: top; text-align: left; "><?php echo $ID;?></td>
					<td id="2_" style="vertical-align: top; text-align: left; "><?php echo $org;?></td>	
					<td id="3_" style="vertical-align: top; text-align: left; "><?php echo $f." ".$l; ?></td>
					<td id="4_" style="vertical-align: top; text-align: left; "><?php if($action_status==1){?><span id="action_s" class="">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="">Inactive</span><?php }  else { ?><span id="action_st" class="">Active</span><?php } ?></td>							
					<td id="5_fund"  style="vertical-align: top; text-align: left; "><?php if($status==1){?><span id="action_status_<?php echo $ID;?>" class="">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $ID;?>" class="">Unpaid</span><?php }  else { ?><span id="action_status_<?php echo $ID;?>" class="">Unpaid</span><?php } ?></td>
					<td id="6_" style="vertical-align: top; text-align: left; "><?php echo date( "M d, Y", strtotime( $registered )); ?></td>
					<td id="7_" style="vertical-align: top; text-align: left; "><span class="label <?php if (($currentDate >= $startDate) && ($currentDate <= $endDate)){ ?>label-success<?php } else { echo "label-danger"; } ?> "><?php echo $sd; ?> - <?php echo $ed; ?></span></td>
					<!--<td id="8__referral" style="vertical-align: top; text-align: left;"><?php if($paid_status){ echo "<span class=''>Paid</span>"; } else{ echo "<span class=''>Unpaid</span>"; } ?></td>-->					
					<td id="9_" style="vertical-align: top; text-align: left;">
						<div class="btn-toolbar"><div class="first-col" style="margin-bottom:0px;">&nbsp;<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>print?fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print</a></div></div>
					</td>
				</tr>
			<?php } } ?>
			
			
			</table>
				</div>

		  </div>
		
		
		
		
		</div>
		
	  </div>
	  
	</div>
</div>
</section>
<?php } else if($_GET['order_detail']==true) { 
	$u_id=$_GET['frid']; 
	$user_info = get_userdata($u_id);
	if(in_array('fundraiser', (array) $user_info->roles ))
	{
	?>
<style>
#nav-tab-orderlist {
    margin-bottom: 35px !important;
    border-radius: 10px;
    overflow: hidden;
    border: none;
    margin-top: 10px;
    padding-bottom: 2px;
}
</style>
<section class="main-content-representative">
<div class="container my-5 py-5">
    
	<div class="tab-content res-tabingcls" id="inner-tabContent">
	  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-outstanding-tab" tabindex="0">
		<ul class="nav nav-tabs justify-content-center border-0" id="myTab" role="tablist">
		  <li class="nav-item" style="display:block;" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/" class="nav-link border-0" id="account-information-tab">Account Information</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?manage=true" class="nav-link border-0" id="manage-fundraisers-tab">Manage Fundraisers</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?payment_historytab=true" class="nav-link border-0" id="payment-history-tab" >Payment History</a>
		  </li>
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_reports=true"  class="active-content-show nav-link border-0" id="fundraisers-reports-tab">Fundraisers Reports</a>
		  </li>
		
		  <li class="nav-item" role="presentation">
		    <a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_operation=true" class="nav-link border-0" id="fundraisers-operation-entry-tab" >Fundraisers Operation</a>
		  </li>
		</ul>
		<div class="tab-content tab-content-new inner-tabContent" id="myTabContent">
		  <div class="active-content" id="fundraisers-reports-tab-pane" role="tabpanel" aria-labelledby="fundraisers-reports-tab" tabindex="0">
		  <h1 class="new-heading ft-size"><a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_reports=true"><i title="Back" class="fa fa-chevron-left" style="font-size:24px;padding-right: 20px;color: #000000;cursor: pointer;"></i></a>Fundraisers Orders</h1>
			<!-- Expand Feature Start -->	
			<div class="accordion" id="accordionPanelsStayOpenExample">
	<?php 
				
					
						$count_args = array(
                            'type'  => 'shop_order',
                            'meta_key' => 'fundraiser_field',
							'meta_value' => $u_id,
							'limit' => -1
						   );
						$c_orders = wc_get_orders( $count_args);
				?>	
  <div class="accordion-item">
    <h2 class="accordion-header"><span>SHIP TO HOME</span>
      <button class="accordion-button" type="button" data-bs-toggle="" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
        Expand
      </button>
    </h2>
    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
      <div class="accordion-body">
        <table class="order_roports_header" border=1>
					<thead>
					<!--<tr>
					<th colspan=11><center><h3>Check fundraiser order details and tracking information</h3></center></th>
					</tr>-->
					<tr>
						<th style="vertical-align: top; text-align: left; ">Order ID</th>
						<!-- <th><center>Seller ID</center></th> -->
						<!--<th style="vertical-align: top; text-align: left; ">Seller Name</th>-->
						<th style="vertical-align: top; text-align: left; ">Customer Name</th>
						<th style="vertical-align: top; text-align: left; ">Fund #ID</th>
						<th style="vertical-align: top; text-align: left; ">Order Date</th>
						<th style="vertical-align: top; text-align: left; ">Order Status</th>
						<!-- <th><center>Order Status</center></th> -->
						<th style="vertical-align: top; text-align: left; ">Tracking No.</th>
						<th style="vertical-align: top; text-align: left; ">Detail</th>
						
					</tr>
					</thead>
				
				<tbody>
					<?php 
					
				foreach( $c_orders as $c_order ){
					$order_id = $c_order->get_id();
					$frID3 = get_post_meta($c_order->ID, 'fundraiser_field', true );
					$order_status2="";
					$order_status1="";
					$order_status1 = $c_order->get_status(); 
					$order_status2 = str_replace(' ', '', $order_status1);
					if($order_status2=="processing" || $order_status2=="completed" || $order_status2=="waiting-call" || $order_status2=="on-hold"){
						$results = $wpdb->get_results("
					select comment_content from wp_comments where comment_post_id=$order_id AND  `comment_content` LIKE  '%tracking number%';
					");
					
					$order_full_date =$c_order->get_date_created();
					$timestamp = strtotime($order_full_date);
					$new_short_date_format = date('Y-m-d', $timestamp);
					$order_data = $c_order->get_data();
					$order_billing_first_name = $order_data['billing']['first_name'];
					$order_billing_last_name = $order_data['billing']['last_name'];
					 //echo $new_short_date_format ."<br>";
					$c_order_id = $c_order->get_id();
					
					$order_customer_id = $order_data['customer_id'];
					$order_type = get_post_meta($c_order->ID, 'wc_order_type', true );
					
					if(($order_type=="Online") && ($frID3 != $order_customer_id)){
						?>
						
						<tr>
						<?php
							$s_id = get_post_meta( $c_order_id, 'wc_seller_field',true ); 
							if($s_id=="custom")
							{
								$s_name=get_post_meta($c_order_id, 'wc_seller_field_custom', true);
							}
							else if(is_numeric($s_id))
							{
								$s_first_name = get_user_meta($s_id, 'first_name', true);
								$s_last_name = get_user_meta($s_id, 'last_name', true);  
								$s_name = $s_first_name.' '.$s_last_name;
							}
							else{}
						?>
							<td style="vertical-align: top; text-align: left; ">#<?php echo $c_order->get_id();  ?></td>
							<!-- <td><?php echo $s_id[0];  ?></td> -->
							<!--<td style="vertical-align: top; text-align: left; "><?php echo $s_name;  ?></td>-->
							<td style="vertical-align: top; text-align: left; "><?php echo $order_billing_first_name . ' '. $order_billing_last_name;  ?></td>
							<td style="vertical-align: top; text-align: left; "><?php  echo "#".$u_id; ?></td>
							<td style="vertical-align: top; text-align: left; "><?php echo $new_short_date_format;  ?></td>
							<!--<td><?php //echo $c_order->get_id();  ?></td> -->
							<td style="vertical-align: top; text-align: left; ">
							<?php 
								if($c_order->get_status() =='waiting-call'){
									echo 'Ship To School';  
								}
								else{
									echo $c_order->get_status();
								}
							?>
							</td>
							<td><?php foreach( $results as $result ){
						$data_track = $result->comment_content;
						$tack_number = explode(" ",$data_track);
						$name = "number"; //name to be searched
						$foundIndex = array_search($name, $tack_number);
						$remove_data =  $tack_number[$foundIndex+1];
						$final_value = substr($remove_data, 0, -1);
						?>
						<a href="https://www.ups.com/track?loc=en_US&tracknum=<?php echo $final_value; ?>&requester=WT/trackdetails\" target="_blank"><?php echo $final_value; ?></a>
						<?php 
						}  ?></td>
						
						<td  style="vertical-align: top; text-align: left; ">
							<button class='myBtn'value=<?php echo $c_order->get_id();?> style="border-radius:8px; background-color: #17c671;">check</button>
							<div id="myModal" class="modal">
								<!-- Modal content -->
								<div class="modal-content">
									<span class="close">&times;</span>
									<div class="order_details">
										<h3 style="text-align: center;margin-top: 20px;margin-bottom: 20px;"><u>ORDER DETAILS</u></h3>
										<h4>
										Order No : <?php echo "#".$c_order->get_id(); ?></h4>
										<!--<b>Seller name : <?php echo $s_name;  ?>
										</b>-->
										<table style="margin-top: 10px;">
										<?php 
										$order_data = $c_order->get_data();
										
										$order_billing_first_name = $order_data['billing']['first_name'];
										$order_billing_last_name = $order_data['billing']['last_name'];
										$billing_name = $order_billing_first_name.' '.$order_billing_last_name;
										$order_billing_address_1 = $order_data['billing']['address_1'];
										$order_billing_address_2 = $order_data['billing']['address_2'];
										$order_billing_city = $order_data['billing']['city'];
										$order_billing_state = $order_data['billing']['state'];
										$order_billing_postcode = $order_data['billing']['postcode'];
										$order_billing_country = $order_data['billing']['country'];
										
										$billing_address = $order_billing_address_1.'<br>'. $order_billing_address_2.' '. $order_billing_city.' '.$order_billing_state.' '.$order_billing_postcode.' '.$order_billing_country; 
										
										$order_billing_email = $order_data['billing']['email'];
										$order_billing_phone = $order_data['billing']['phone'];
										
										$order_shipping_first_name = $order_data['shipping']['first_name'];
										$order_shipping_last_name  = $order_data['shipping']['last_name'];
										$shipping_name = $order_shipping_first_name.' '.$order_shipping_last_name ;
										$order_shipping_address_1 = $order_data['shipping']['address_1'];
										$order_shipping_address_2 = $order_data['shipping']['address_2'];
										$order_shipping_city = $order_data['shipping']['city'];
										$order_shipping_state = $order_data['shipping']['state'];
										$order_shipping_postcode = $order_data['shipping']['postcode'];
										$order_shipping_country = $order_data['shipping']['country'];
										
										$shipping_address = $order_shipping_address_1.'<br>'. $order_shipping_address_2.' '. $order_shipping_city.' '.$order_shipping_state.' '.$order_shipping_postcode.' '.$order_shipping_country;
										
										 
										?>
											<tr>
												<td><b>Billing details</b><br><?php echo $billing_name; ?><br><?php echo $billing_address; ?><br><br><b>E-mail</b><br><?php echo $order_billing_email;  ?></td>
												<td style='padding-left: 275px;'><b>Shipping details</b><br><?php echo $shipping_name; ?><br><?php echo $shipping_address; ?><br><br><b>Shipping Method</b><br><?php echo $c_order->get_shipping_method();  ?></td>
											</tr>
											<tr>
											<td style='border-top: 0px solid #efefef;'><b>Phone</b><br><?php echo $order_billing_phone; ?></td>
											</tr>
										</table><br>
										<table>
										<tr>
										<td><b>Product</b></td>
										<td><b>Qty.</b></td>
										<td><b>Tax</b></td>
										<td><b>Total</b></td>
										</tr>
										<?php
										$t_product =0;
										$t_amount =0;
										foreach( $c_order->get_items() as $item_id => $item ){
										$product_id    = $item['product_id']; // Get the product ID
										
										$variation_id  = $item['variation_id']; // Get the variation ID

										$product_name  = $item['name']; 
										$item_qty      = $item['quantity']; 

										// Get line item totals (non discounted)
										$line_total     = $item['subtotal']; 
										$line_total_tax = $item['subtotal_tax']; 

										// Get line item totals (discounted)
										$line_total2     = $item['total']; 
										$line_total_tax2 = $item['total_tax']; 
										
										?>
											<tr>
											<td><?php echo $product_name ;?></td>
											<td><?php echo $item_qty ;?></td>
											<td><?php echo $line_total_tax;?></td>
											<td>$<?php echo $line_total;?></td>
											</tr>
										<?php 
										$t_product = $t_product + $item_qty;
										$t_amount = $t_amount + $line_total;
									}
										
										?>
										<tr>
										<th>Total</th>
										<td><b><?php echo $t_product; ?></b></td>
										<td></td>
										<td><b>$<?php echo $t_amount; ?></b></td>
										</tr>
										</table>
									</div>
									
									
								</div>

							</div>
						</td>
						
						</tr>
					<?php 	} 
					}				
						}
						?>
					</tbody>
				</table>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header"><span>SHIP TO GROUP</span>
      <button class="accordion-button collapsed" type="button" data-bs-toggle="" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
         Expand
      </button>
    </h2>
    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
      <div class="accordion-body">
      <table class="order_roports_header" border=1>
					<thead>
					<!--<tr>
					<th colspan=11><center><h3>Check fundraiser order details and tracking information</h3></center></th>
					</tr>-->
					<tr>
						<th style="vertical-align: top; text-align: left; ">Order ID</th>
						<!-- <th><center>Seller ID</center></th> -->
						<!--<th style="vertical-align: top; text-align: left; ">Seller Name</th>-->
						<th style="vertical-align: top; text-align: left; ">Customer Name</th>
						<th style="vertical-align: top; text-align: left; ">Fund #ID</th>
						<th style="vertical-align: top; text-align: left; ">Order Date</th>
						<th style="vertical-align: top; text-align: left; ">Order Status</th>
						<!-- <th><center>Order Status</center></th> -->
						<th style="vertical-align: top; text-align: left; ">Tracking No.</th>
						<th style="vertical-align: top; text-align: left; ">Detail</th>
						
					</tr>
					</thead>
				
				<tbody>
					<?php 
					
				foreach( $c_orders as $c_order ){
					$order_id = $c_order->get_id();
					$frID3 = get_post_meta($c_order->ID, 'fundraiser_field', true );
					$order_status2="";
					$order_status1="";
					$order_status1 = $c_order->get_status(); 
					$order_status2 = str_replace(' ', '', $order_status1);
					if($order_status2=="processing" || $order_status2=="completed" || $order_status2=="waiting-call" || $order_status2=="on-hold"){
						$results = $wpdb->get_results("
					select comment_content from wp_comments where comment_post_id=$order_id AND  `comment_content` LIKE  '%tracking number%';
					");
					
					$order_full_date =$c_order->get_date_created();
					$timestamp = strtotime($order_full_date);
					$new_short_date_format = date('Y-m-d', $timestamp);
					$order_data = $c_order->get_data();
					$order_billing_first_name = $order_data['billing']['first_name'];
					$order_billing_last_name = $order_data['billing']['last_name'];
					 //echo $new_short_date_format ."<br>";
					$c_order_id = $c_order->get_id();
					
					$order_customer_id = $order_data['customer_id'];
					$order_type = get_post_meta($c_order->ID, 'wc_order_type', true );
					
					if(($order_type=="ship-to-school") && ($frID3 != $order_customer_id)){
						?>
						
						<tr>
						<?php
							$s_id = get_post_meta( $c_order_id, 'wc_seller_field',true ); 
							if($s_id=="custom")
							{
								$s_name=get_post_meta($c_order_id, 'wc_seller_field_custom', true);
							}
							else if(is_numeric($s_id))
							{
								$s_first_name = get_user_meta($s_id, 'first_name', true);
								$s_last_name = get_user_meta($s_id, 'last_name', true);  
								$s_name = $s_first_name.' '.$s_last_name;
							}
							else{}
						?>
							<td style="vertical-align: top; text-align: left; ">#<?php echo $c_order->get_id();  ?></td>
							<!-- <td><?php echo $s_id[0];  ?></td> -->
							<!--<td style="vertical-align: top; text-align: left; "><?php echo $s_name;  ?></td>-->
							<td style="vertical-align: top; text-align: left; "><?php echo $order_billing_first_name . ' '. $order_billing_last_name;  ?></td>
							<td style="vertical-align: top; text-align: left; "><?php  echo "#".$u_id; ?></td>
							<td style="vertical-align: top; text-align: left; "><?php echo $new_short_date_format;  ?></td>
							<!--<td><?php //echo $c_order->get_id();  ?></td> -->
							<td style="vertical-align: top; text-align: left; ">
							<?php 
								if($c_order->get_status() =='waiting-call'){
									echo 'Ship To School';  
								}
								else{
									echo $c_order->get_status();
								}
							?>
							</td>
							<td><?php foreach( $results as $result ){
						$data_track = $result->comment_content;
						$tack_number = explode(" ",$data_track);
						$name = "number"; //name to be searched
						$foundIndex = array_search($name, $tack_number);
						$remove_data =  $tack_number[$foundIndex+1];
						$final_value = substr($remove_data, 0, -1);
						?>
						<a href="https://www.ups.com/track?loc=en_US&tracknum=<?php echo $final_value; ?>&requester=WT/trackdetails\" target="_blank"><?php echo $final_value; ?></a>
						<?php 
						}  ?></td>
						
						<td  style="vertical-align: top; text-align: left; ">
							<button class='myBtn'value=<?php echo $c_order->get_id();?> style="border-radius:8px; background-color: #17c671;">check</button>
							<div id="myModal" class="modal">
								<!-- Modal content -->
								<div class="modal-content">
									<span class="close">&times;</span>
									<div class="order_details">
										<h3 style="text-align: center;margin-top: 20px;margin-bottom: 20px;"><u>ORDER DETAILS</u></h3>
										<h4>
										Order No : <?php echo "#".$c_order->get_id(); ?></h4>
										<!--<b>Seller name : <?php echo $s_name;  ?>
										</b>-->
										<table style="margin-top: 10px;">
										<?php 
										$order_data = $c_order->get_data();
										
										$order_billing_first_name = $order_data['billing']['first_name'];
										$order_billing_last_name = $order_data['billing']['last_name'];
										$billing_name = $order_billing_first_name.' '.$order_billing_last_name;
										$order_billing_address_1 = $order_data['billing']['address_1'];
										$order_billing_address_2 = $order_data['billing']['address_2'];
										$order_billing_city = $order_data['billing']['city'];
										$order_billing_state = $order_data['billing']['state'];
										$order_billing_postcode = $order_data['billing']['postcode'];
										$order_billing_country = $order_data['billing']['country'];
										
										$billing_address = $order_billing_address_1.'<br>'. $order_billing_address_2.' '. $order_billing_city.' '.$order_billing_state.' '.$order_billing_postcode.' '.$order_billing_country; 
										
										$order_billing_email = $order_data['billing']['email'];
										$order_billing_phone = $order_data['billing']['phone'];
										
										$order_shipping_first_name = $order_data['shipping']['first_name'];
										$order_shipping_last_name  = $order_data['shipping']['last_name'];
										$shipping_name = $order_shipping_first_name.' '.$order_shipping_last_name ;
										$order_shipping_address_1 = $order_data['shipping']['address_1'];
										$order_shipping_address_2 = $order_data['shipping']['address_2'];
										$order_shipping_city = $order_data['shipping']['city'];
										$order_shipping_state = $order_data['shipping']['state'];
										$order_shipping_postcode = $order_data['shipping']['postcode'];
										$order_shipping_country = $order_data['shipping']['country'];
										
										$shipping_address = $order_shipping_address_1.'<br>'. $order_shipping_address_2.' '. $order_shipping_city.' '.$order_shipping_state.' '.$order_shipping_postcode.' '.$order_shipping_country;
										
										 
										?>
											<tr>
												<td><b>Billing details</b><br><?php echo $billing_name; ?><br><?php echo $billing_address; ?><br><br><b>E-mail</b><br><?php echo $order_billing_email;  ?></td>
												<td style='padding-left: 275px;'><b>Shipping details</b><br><?php echo $shipping_name; ?><br><?php echo $shipping_address; ?><br><br><b>Shipping Method</b><br><?php echo $c_order->get_shipping_method();  ?></td>
											</tr>
											<tr>
											<td style='border-top: 0px solid #efefef;'><b>Phone</b><br><?php echo $order_billing_phone; ?></td>
											</tr>
										</table><br>
										<table>
										<tr>
										<td><b>Product</b></td>
										<td><b>Qty.</b></td>
										<td><b>Tax</b></td>
										<td><b>Total</b></td>
										</tr>
										<?php
										$t_product =0;
										$t_amount =0;
										foreach( $c_order->get_items() as $item_id => $item ){
										$product_id    = $item['product_id']; // Get the product ID
										
										$variation_id  = $item['variation_id']; // Get the variation ID

										$product_name  = $item['name']; 
										$item_qty      = $item['quantity']; 

										// Get line item totals (non discounted)
										$line_total     = $item['subtotal']; 
										$line_total_tax = $item['subtotal_tax']; 

										// Get line item totals (discounted)
										$line_total2     = $item['total']; 
										$line_total_tax2 = $item['total_tax']; 
										
										?>
											<tr>
											<td><?php echo $product_name ;?></td>
											<td><?php echo $item_qty ;?></td>
											<td><?php echo $line_total_tax;?></td>
											<td>$<?php echo $line_total;?></td>
											</tr>
										<?php 
										$t_product = $t_product + $item_qty;
										$t_amount = $t_amount + $line_total;
									}
										
										?>
										<tr>
										<th>Total</th>
										<td><b><?php echo $t_product; ?></b></td>
										<td></td>
										<td><b>$<?php echo $t_amount; ?></b></td>
										</tr>
										</table>
									</div>
									
									
								</div>

							</div>
						</td>
						
						</tr>
					<?php 	} 
					}				
						}
						?>
					</tbody>
				</table>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header"><span>BROCHURE SALES</span>
      <button class="accordion-button collapsed" type="button" data-bs-toggle="" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
        Expand
      </button>
    </h2>
    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
      <div class="accordion-body">
       <table class="order_roports_header" border=1>
					<thead>
					<!--<tr>
					<th colspan=11><center><h3>Check fundraiser order details and tracking information</h3></center></th>
					</tr>-->
					<tr>
						<th style="vertical-align: top; text-align: left; ">Order ID</th>
						<!-- <th><center>Seller ID</center></th> -->
						<!--<th style="vertical-align: top; text-align: left; ">Seller Name</th>-->
						<th style="vertical-align: top; text-align: left; ">Customer Name</th>
						<th style="vertical-align: top; text-align: left; ">Fund #ID</th>
						<th style="vertical-align: top; text-align: left; ">Order Date</th>
						<th style="vertical-align: top; text-align: left; ">Order Status</th>
						<!-- <th><center>Order Status</center></th> -->
						<th style="vertical-align: top; text-align: left; ">Tracking No.</th>
						<th style="vertical-align: top; text-align: left; ">Detail</th>
						
					</tr>
					</thead>
				
				<tbody>
					<?php 
					
				foreach( $c_orders as $c_order ){
					$order_id = $c_order->get_id();
					$frID3 = get_post_meta($c_order->ID, 'fundraiser_field', true );
					$order_status2="";
					$order_status1="";
					$order_status1 = $c_order->get_status(); 
					$order_status2 = str_replace(' ', '', $order_status1);
					if($order_status2=="processing" || $order_status2=="completed" || $order_status2=="waiting-call" || $order_status2=="on-hold"){
						$results = $wpdb->get_results("
					select comment_content from wp_comments where comment_post_id=$order_id AND  `comment_content` LIKE  '%tracking number%';
					");
					
					$order_full_date =$c_order->get_date_created();
					$timestamp = strtotime($order_full_date);
					$new_short_date_format = date('Y-m-d', $timestamp);
					$order_data = $c_order->get_data();
					$order_billing_first_name = $order_data['billing']['first_name'];
					$order_billing_last_name = $order_data['billing']['last_name'];
					 //echo $new_short_date_format ."<br>";
					$c_order_id = $c_order->get_id();
					
					$order_customer_id = $order_data['customer_id'];
					$order_type = get_post_meta($c_order->ID, 'wc_order_type', true );
					
					if (($order_type=="Catlog") && ($frID3 == $order_customer_id)){
						?>
						
						<tr>
						<?php
							$s_id = get_post_meta( $c_order_id, 'wc_seller_field',true ); 
							if($s_id=="custom")
							{
								$s_name=get_post_meta($c_order_id, 'wc_seller_field_custom', true);
							}
							else if(is_numeric($s_id))
							{
								$s_first_name = get_user_meta($s_id, 'first_name', true);
								$s_last_name = get_user_meta($s_id, 'last_name', true);  
								$s_name = $s_first_name.' '.$s_last_name;
							}
							else{}
						?>
							<td style="vertical-align: top; text-align: left; ">#<?php echo $c_order->get_id();  ?></td>
							<!-- <td><?php echo $s_id[0];  ?></td> -->
							<!--<td style="vertical-align: top; text-align: left; "><?php echo $s_name;  ?></td>-->
							<td style="vertical-align: top; text-align: left; "><?php echo $order_billing_first_name . ' '. $order_billing_last_name;  ?></td>
							<td style="vertical-align: top; text-align: left; "><?php  echo "#".$u_id; ?></td>
							<td style="vertical-align: top; text-align: left; "><?php echo $new_short_date_format;  ?></td>
							<!--<td><?php //echo $c_order->get_id();  ?></td> -->
							<td style="vertical-align: top; text-align: left; ">
							<?php 
								if($c_order->get_status() =='waiting-call'){
									echo 'Ship To School';  
								}
								else{
									echo $c_order->get_status();
								}
							?>
							</td>
							<td><?php foreach( $results as $result ){
						$data_track = $result->comment_content;
						$tack_number = explode(" ",$data_track);
						$name = "number"; //name to be searched
						$foundIndex = array_search($name, $tack_number);
						$remove_data =  $tack_number[$foundIndex+1];
						$final_value = substr($remove_data, 0, -1);
						?>
						<a href="https://www.ups.com/track?loc=en_US&tracknum=<?php echo $final_value; ?>&requester=WT/trackdetails\" target="_blank"><?php echo $final_value; ?></a>
						<?php 
						}  ?></td>
						
						<td  style="vertical-align: top; text-align: left; ">
							<button class='myBtn'value=<?php echo $c_order->get_id();?> style="border-radius:8px; background-color: #17c671;">check</button>
							<div id="myModal" class="modal">
								<!-- Modal content -->
								<div class="modal-content">
									<span class="close">&times;</span>
									<div class="order_details">
										<h3 style="text-align: center;margin-top: 20px;margin-bottom: 20px;"><u>ORDER DETAILS</u></h3>
										<h4>
										Order No : <?php echo "#".$c_order->get_id(); ?></h4>
										<!--<b>Seller name : <?php echo $s_name;  ?>
										</b>-->
										<table style="margin-top: 10px;">
										<?php 
										$order_data = $c_order->get_data();
										
										$order_billing_first_name = $order_data['billing']['first_name'];
										$order_billing_last_name = $order_data['billing']['last_name'];
										$billing_name = $order_billing_first_name.' '.$order_billing_last_name;
										$order_billing_address_1 = $order_data['billing']['address_1'];
										$order_billing_address_2 = $order_data['billing']['address_2'];
										$order_billing_city = $order_data['billing']['city'];
										$order_billing_state = $order_data['billing']['state'];
										$order_billing_postcode = $order_data['billing']['postcode'];
										$order_billing_country = $order_data['billing']['country'];
										
										$billing_address = $order_billing_address_1.'<br>'. $order_billing_address_2.' '. $order_billing_city.' '.$order_billing_state.' '.$order_billing_postcode.' '.$order_billing_country; 
										
										$order_billing_email = $order_data['billing']['email'];
										$order_billing_phone = $order_data['billing']['phone'];
										
										$order_shipping_first_name = $order_data['shipping']['first_name'];
										$order_shipping_last_name  = $order_data['shipping']['last_name'];
										$shipping_name = $order_shipping_first_name.' '.$order_shipping_last_name ;
										$order_shipping_address_1 = $order_data['shipping']['address_1'];
										$order_shipping_address_2 = $order_data['shipping']['address_2'];
										$order_shipping_city = $order_data['shipping']['city'];
										$order_shipping_state = $order_data['shipping']['state'];
										$order_shipping_postcode = $order_data['shipping']['postcode'];
										$order_shipping_country = $order_data['shipping']['country'];
										
										$shipping_address = $order_shipping_address_1.'<br>'. $order_shipping_address_2.' '. $order_shipping_city.' '.$order_shipping_state.' '.$order_shipping_postcode.' '.$order_shipping_country;
										
										 
										?>
											<tr>
												<td><b>Billing details</b><br><?php echo $billing_name; ?><br><?php echo $billing_address; ?><br><br><b>E-mail</b><br><?php echo $order_billing_email;  ?></td>
												<td style='padding-left: 275px;'><b>Shipping details</b><br><?php echo $shipping_name; ?><br><?php echo $shipping_address; ?><br><br><b>Shipping Method</b><br><?php echo $c_order->get_shipping_method();  ?></td>
											</tr>
											<tr>
											<td style='border-top: 0px solid #efefef;'><b>Phone</b><br><?php echo $order_billing_phone; ?></td>
											</tr>
										</table><br>
										<table>
										<tr>
										<td><b>Product</b></td>
										<td><b>Qty.</b></td>
										<td><b>Tax</b></td>
										<td><b>Total</b></td>
										</tr>
										<?php
										$t_product =0;
										$t_amount =0;
										foreach( $c_order->get_items() as $item_id => $item ){
										$product_id    = $item['product_id']; // Get the product ID
										
										$variation_id  = $item['variation_id']; // Get the variation ID

										$product_name  = $item['name']; 
										$item_qty      = $item['quantity']; 

										// Get line item totals (non discounted)
										$line_total     = $item['subtotal']; 
										$line_total_tax = $item['subtotal_tax']; 

										// Get line item totals (discounted)
										$line_total2     = $item['total']; 
										$line_total_tax2 = $item['total_tax']; 
										
										?>
											<tr>
											<td><?php echo $product_name ;?></td>
											<td><?php echo $item_qty ;?></td>
											<td><?php echo $line_total_tax;?></td>
											<td>$<?php echo $line_total;?></td>
											</tr>
										<?php 
										$t_product = $t_product + $item_qty;
										$t_amount = $t_amount + $line_total;
									}
										
										?>
										<tr>
										<th>Total</th>
										<td><b><?php echo $t_product; ?></b></td>
										<td></td>
										<td><b>$<?php echo $t_amount; ?></b></td>
										</tr>
										</table>
									</div>
									
									
								</div>

							</div>
						</td>
						
						</tr>
					<?php 	} 
					}				
						}
						?>
					</tbody>
				</table>
      </div>
    </div>
  </div>
</div>
			
			<!-- Expand Feature End -->
			
			</div>
		 
		 </div>
		
	  </div>
	  
	</div>
</div>
</section>
<script>
	jQuery(document).ready(function() {
		
		jQuery(".accordion-item .accordion-button").on("click",function(){
		let check=jQuery(this).attr("aria-expanded");
		//alert(check);
		
		let target_val=jQuery(this).attr("data-bs-target");
		//alert(target_val);
		if (check==="true") {
			
			jQuery(target_val).removeClass("show");
			jQuery(this).attr("aria-expanded","false");
			
			jQuery(this).text("Expand");
			jQuery(this).removeClass("collapsed");
		}
		else{
			jQuery(this).attr("aria-expanded","true");
			jQuery(this).addClass("collapsed");
			jQuery(this).text("Close");
			jQuery(target_val).addClass("show");
			
		}
	});
	});
	</script>
<style>

	.accordion-item
	{
		margin-bottom: 20px;
	}
	.accordion-header {
		margin-bottom: 0;
		padding: 13px 20px;
		background: #e5edf4;
	}
	.accordion-button
	{
		float: right;
		padding: 0;
		font-size: 20px;
		display: inline-table;
		width: auto;
		    background-color: transparent;
	}
	button:hover, button:focus
	{
		background-color: transparent;
		color: #000;
	}
	.collapse {
  
    margin: 20px 20px;
    margin-bottom: 0;
	}
	.accordion-button:not(.collapsed) {
		color: #000;
		background-color: transparent;
		box-shadow: none;
	}
	.accordion-button:focus {
		border-color: unset;
		box-shadow: none;
	}
	.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 100; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: Hidden; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }
  
  /* Modal Content */
  .modal-content {
    background-color: #fefefe;
    margin: 10% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 50%; /* Could be more or less, depending on screen size */
	height:70%;
	overflow-y:scroll;
  }
  
  /* The Close Button */
.close {
    color: #aaa;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
    position: absolute;
}
  
 .close:hover,
 .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }
.order_details thead tr > th:last-child, .order_details tbody > tr > td:last-child, .order_details tr > td {
	padding-left: 10px;
}
</style>

		<?php } } else {
	/* New Start */
	$total_referral_comm = 0;
	$totalAmount_full=0;
	$fundraisers_list=array();
	$fundraisers_list2=array();
	
	$exist_fids_arr=array();
	$q=0;
	$fundraiserTotal2=0;
	$fundraiserTotal=0;
	$total_users=0;
	$total_users=0;
	$total_query=0;

	$current_payment_postid=get_user_meta($current_userID,"um_unique_payment_id",true);
	$fundraiser_ids=get_post_meta($current_payment_postid,"fundraiser_ids",true);
	if(!empty($fundraiser_ids))
	{
		$exist_fids_arr=explode(",",$fundraiser_ids);
		$q=1;
	}
	
	if($q==1)
	{
		$i=0;
		$full_fundraiser_reports=array();
		$number   = 10;
		$paged    = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$offset   = ($paged - 1) * $number;
		
		/*get fundraiser list which connected representative */
		$fundraisers_users = get_users(array( 
			'role' => array( 'fundraiser' ),
			'include' => $exist_fids_arr,
			'meta_key'     => 'user_registration_referral_email',
			'meta_value'   => $current_email,
			'meta_compare' => '='
		));
		
		$fundraisers_list = get_users(array( 
			'role' => array( 'fundraiser' ),
			'include' => $exist_fids_arr,
			'meta_key'     => 'user_registration_referral_email',
			'meta_value'   => $current_email,
			'meta_compare' => '='
			
		));	
		$fundraiserTotal = count($fundraisers_list);
		
		$total_users = count($fundraisers_users);
		$total_query = count($fundraisers_list);
		$total_pages = intval($total_users / $number) + 1;
	
		$fundraisers_list2 = get_users(array( 
			'role' => array( 'fundraiser' ),
			'include' => $exist_fids_arr,
			'meta_key'     => 'user_registration_referral_email',
			'meta_value'   => $current_email,
			'meta_compare' => '=',
		));	
		$fundraiserTotal2 = count($fundraisers_list2);
				
		$fundraisers_list = get_users(array( 
					'role' => array( 'fundraiser' ),
					'include' => $exist_fids_arr,
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'meta_compare' => '='
					
		));	
		foreach($fundraisers_list as $fundraisers) {
				$ID = $fundraisers->data->ID;
	            //$name = $fundraisers->data->user_login;
				$action_status= get_user_meta($ID,'user_action_status',true );
				if( !empty($action_status) && $action_status==2)
				{
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
				}
		}
		?>
		<section class="main-content-representative">
				<div class="container my-5 py-5">
					
					<div class="tab-content res-tabingcls" id="inner-tabContent">
					  <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-outstanding-tab" tabindex="0">
						<ul class="nav nav-tabs justify-content-center border-0" id="myTab" role="tablist">
							  <li class="nav-item" style="display:block;" role="presentation">
								<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/" class="active-content-show nav-link border-0" id="account-information-tab">Account Information</a>
							  </li>
							  <li class="nav-item" role="presentation">
								<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?manage=true" class="nav-link border-0" id="manage-fundraisers-tab">Manage Fundraisers</a>
							  </li>
							  <li class="nav-item" role="presentation">
								<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?payment_historytab=true" class="nav-link border-0" id="payment-history-tab" >Payment History</a>
							  </li>
							  <li class="nav-item" role="presentation">
								<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_reports=true"  class="nav-link border-0" id="fundraisers-reports-tab">Fundraisers Reports</a>
							  </li>
							
							  <li class="nav-item" role="presentation">
								<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>representative-dashboard/?fundraisers_operation=true" class=" nav-link border-0" id="fundraisers-operation-entry-tab" >Fundraisers Operation</a>
							  </li>
							</ul>
						<div class="tab-content tab-content-new inner-tabContent" id="myTabContent">
						
						  <div class="active-content" id="account-information-tab-pane" role="tabpanel" aria-labelledby="account-information-tab" tabindex="0">
						 
						
							<div class="row justify-content-center mb-5 m-auto w-100">
								<div class="col p-0 your-account-balance m-2">
									<h1 class="inner-tab-label mb-2 "><?php echo $fundraiserTotal2;?></h1>
									<h5 class="inner-tab-title">Total Fundraisers</h5>
									<!--<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2023/04/avatar.png" class="account-info-img">-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" class="icon text-muted-400 size-5 account-info-img-new" data-v-257aca76=""  width="50px" height="50px" viewBox="0 0 256 256" data-v-b4402e20=""><g fill="#000"><path d="M136 108a52 52 0 1 1-52-52a52 52 0 0 1 52 52" opacity=".2"></path><path d="M117.25 157.92a60 60 0 1 0-66.5 0a95.83 95.83 0 0 0-47.22 37.71a8 8 0 1 0 13.4 8.74a80 80 0 0 1 134.14 0a8 8 0 0 0 13.4-8.74a95.83 95.83 0 0 0-47.22-37.71M40 108a44 44 0 1 1 44 44a44.05 44.05 0 0 1-44-44m210.14 98.7a8 8 0 0 1-11.07-2.33A79.83 79.83 0 0 0 172 168a8 8 0 0 1 0-16a44 44 0 1 0-16.34-84.87a8 8 0 1 1-5.94-14.85a60 60 0 0 1 55.53 105.64a95.83 95.83 0 0 1 47.22 37.71a8 8 0 0 1-2.33 11.07"></path></g></svg>
								</div>
								<div class="col p-0 your-account-balance m-2">
									<h1 class="inner-tab-label mb-2 ">$<?php echo $total_referral_comm=number_format($total_referral_comm, 2, '.', ''); ?></h1>
									<h5 class="inner-tab-title">Total Balance:</h5>
									<!--<img src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2023/04/money.png" class="account-info-img">-->
									<svg xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" fill="#000" class="bi bi-coin account-info-img-new" viewBox="0 0 16 16">
									  <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z"></path>
									  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"></path>
									  <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12"></path>
									</svg>
								</div>
								<!-- <div class="col p-0 your-account-balance m-2">
									<h1 class="inner-tab-label mb-2 ">80$</h1>
									<h5 class="inner-tab-title">Your A/C Balance:</h5>
								</div> -->
							</div>
							<div class="row justify-content-center mb-5 m-auto w-100">
								<div  class="col p-0">
									<h5 class="inner-tab-title">First Name:</h5>
									<p class="inner-tab-label mb-0"><?php echo $ref_firstname_2311;?></p>
								</div>
								<div  class="col p-0">
									<h5 class="inner-tab-title">Last Name:</h5>
									<p class="inner-tab-label mb-0"><?php echo $ref_lastname_2311;?></p>
								</div>
								<div  class="col p-0">
									<h5 class="inner-tab-title">Email:</h5>
									<p class="inner-tab-label mb-0"><?php echo $current_email;?></p>
								</div>
							</div>
							<div class="row justify-content-center mb-5 m-auto w-100">
								<div  class="col p-0">
								<!--	<h5 class="inner-tab-title">Sex:</h5>
									<p class="inner-tab-label mb-0"><?php // echo $sex;?></p>-->
									<h5 class="inner-tab-title">Phone Number:</h5>
									<p class="inner-tab-label mb-0"><?php echo $phone;?></p>
								</div>
								<div  class="col p-0">
										<h5 class="inner-tab-title">Company:</h5>
									<p class="inner-tab-label mb-0"><?php echo $reps_company;?></p>
								</div>
								<div  class="col p-0">
								
								</div>
							</div>
							<div class="row justify-content-center mb-1	 m-auto w-100">
								<div  class="col p-0">
									<h5 class="inner-tab-title">Address:</h5>
									<p class="inner-tab-label mb-0"><?php echo $reps_address;?></p>
								</div>
								<div  class="col p-0">
									<h5 class="inner-tab-title">City:</h5>
									<p class="inner-tab-label mb-0"><?php echo $reps_city;?></p>
								</div>
								<div  class="col p-0">
									<h5 class="inner-tab-title">Country:</h5>
									<p class="inner-tab-label mb-0"><?php echo $reps_country;?></p>
								</div>
							</div>
						  </div>
						  </div>
						
					  </div>
					  
					</div>
				</div>
		</section>
		<?php
} 
?>

<!-- <img src="footer.jpg" class="account-banner-img"> -->


<script>
	jQuery(document).ready(function() {
		jQuery('.myBtn').on('click', function() {
			// Target the .modal sibling and change its CSS
			$(this).siblings(".modal").css({
				'display': 'block',      // for example, to show the modal
				 /* 'background-color': '#fff', // set other CSS properties as needed
				'border-radius': '5px',
				'padding': '10px'  */
				// add other CSS properties you want to change
			});
		});
		jQuery('.close').on('click', function() {
			//alert('I am here');
			// Target the .modal sibling and change its CSS
			$(this).parent().parent().css({
				'display': 'none',      // for example, to show the modal
				/* 'background-color': '#fff', // set other CSS properties as needed
				'border-radius': '5px',
				'padding': '10px' */
				// add other CSS properties you want to change
			});
		});
		
	});
/*
	jQuery(".resultshow").click(function(){
		jQuery(".resulttabel965").css("display","block");
	});
	jQuery(".close").click(function(){
		jQuery(".popsection.modal").css("display","none");
		jQuery(".popsection.modal").removeClass("show");
		jQuery(".modal-backdrop").removeClass("modal-backdrop fade show");
	});


$("#sectionChooser").change(function(){
    if($(this).val() == "brochures-show") {
       $('.product-list').addClass('show');
    } else {
       $('.product-list').removeClass('show');
    }
});
*/
/*
$('#cat_type').on('change', function() {
    // Save value in localstorage
    localStorage.setItem("cat_type", $(this).val());
    $('.product-list').addClass('show');
    
    
    var catname = $(this).val();
    var ajaxurl = '<?php echo admin_url( "admin-ajax.php" ); //must echo it ?>';
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "load-filter", cat: catname },
        success: function(response) {
            jQuery("#category-post-content").html(response);
            jQuery("#loading-animation").hide();
            return false;
        }
    });
 });
*/
/*
$('#fundraisersList').on('change', function() {
    // Save value in localstorage
    localStorage.setItem("fundraisersList", $(this).val());
    
    var fundraisersName = $(this).val();
    var ajaxurl = '<?php echo admin_url( "admin-ajax.php" ); //must echo it ?>';
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {"action": "save_fundraisers", name: fundraisersName },
        success: function(response){
			alert("fundraisers added successfully");
        }
    });
 });


jQuery(document).ready(function($){
	$(document).on('click','button.nav-link',function(e){
		localStorage.setItem('activeTab', $(e.target).attr('id'));		
	});
	
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        $('button.nav-link[id="' + activeTab + '"]').tab('show');
    }else{
    	$('#account-information-tab').tab('show');
    }
    
    
  if ($('#cat_type').length) {
        $('#cat_type').val(localStorage.getItem("cat_type"));
        $('.current_category').html(localStorage.getItem("cat_type"));
        $('.product-list').addClass('show');
    } 
});
*/
</script>
<?php } 
$current_user = wp_get_current_user();
	//print_r($current_user);

	$current_userID = $current_user->ID;
	$user_info = get_userdata($current_userID);
 if(!in_array( 'representative', (array) $user_info->roles )){
?>
<h1 style="
    padding: 20px;
    margin: 20px auto;
    width: 100%;
    display: inline-block;
    text-align: center;
    font-size: 21px;
    text-transform: initial;
">You are not representative</h1>
<?php
	 }
}
 else{

		wp_redirect( ("" . glow_dynamic_url_base('home') . "my-account"), 301 );
	 
} ?>

<?php get_footer();?>