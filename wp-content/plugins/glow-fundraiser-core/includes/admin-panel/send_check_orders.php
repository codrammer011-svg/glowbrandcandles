<?php 
/*
* Ajax For Send Check Order
*/
	add_action('wp_ajax_nopriv_send_check_custom_fund_status', 'send_check_custom_fund_status'); // for not logged in users
	add_action('wp_ajax_send_check_custom_fund_status', 'send_check_custom_fund_status');
	function send_check_custom_fund_status( )
	{
		$orderID = $_POST['send_check_fund_order_id'];
		$action = $_POST['send_check_action'];
		$fundid = $_POST['send_check_fundid']; 
		$action_paid_status= $_POST['send_check_paid_status']; 
		$order_status_send_check_number= $_POST['order_status_send_check_number']; 
		if($action_paid_status==1) // 1 for paid
		{
			//$order = new WC_Order($orderID);
			//$order->update_status($action);
		}
		update_post_meta( $orderID , 'order_status_send_check_number' , $order_status_send_check_number );
		update_post_meta( $orderID , 'order_status_send_check' , $action_paid_status );
		
		 wp_send_json_success(array(
		 'order_id'=>$orderID,
		 'order_status'=>$action,
		'action' => $action_paid_status,
		'cheque' => $order_status_send_check_number,
		'fid' => $fundid		
		));
	}

function theme_fundraiser_send_check_orders()
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
    background: #263849;
    background: -moz-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #3C4C5B), color-stop(100%, #263849));
    background: -webkit-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: -o-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: -ms-linear-gradient(top, #3C4C5B 0%, #263849 100%);
    background: linear-gradient(top, #3C4C5B 0%, #263849 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#3C4C5B', endColorstr='#263849');
    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#3C4C5B', endColorstr='#263849')";
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
    font-size: 13px;
    font-weight: 400;
    color: #444;
    border-left: 1px solid #F1F1F1;
    border-right: 1px solid #CCC;
    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,1);
    box-shadow: inset 0 1px 0 rgba(255,255,255,1);
}
.table-highlight.table-bordered thead th {
    color: #FFF;
    border-right: 1px solid #1C2B37;
    border-left: 1px solid rgba(255,255,255,.15);
    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.25);
    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.25);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.25);
    text-shadow: 1px 1px 2px rgba(0,0,0,.5);
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
    border-top: 1px solid #292C32;
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
    background-color: #F0F3F7;
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
</style>
<style>
	.manual-mass-order-popup a 
	{
		text-decoration: none;
		-webkit-transition: color .25s ease;
		-moz-transition: color .25s ease;
		-o-transition: color .25s ease;
		-ms-transition: color .25s ease;
		transition: color .25s ease;
		color: #000;
		cursor: pointer;
	    margin-top: 15px; 
		outline:none;
	}
	.manual-mass-order-popup a:hover{
			color: #cda85c;
	}
	.manual-mass-order-popup_action a 
	{
		text-decoration: none;
		-webkit-transition: color .25s ease;
		-moz-transition: color .25s ease;
		-o-transition: color .25s ease;
		-ms-transition: color .25s ease;
		transition: color .25s ease;
		color: #000;
		cursor: pointer;
	    margin-top: 15px; 
		outline:none;
	}
	.manual-mass-order-popup_action a:hover{
			color: #cda85c;
	}
	.txt-link
	{
		display:block;
		width:100%;
	}
	.status_type
	{
			margin: 0 10px !important;
	}
	.a_status_type
	{
			margin: 0 10px !important;
	}
	.manual-mass-order-popup 
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
	.manual-mass-order-popup .manual-mass-order-popup-wrapper 
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
	.manual-mass-order-popup a.fr-btn 
	{
		display: block;
		margin: 20px auto;
		width: 100%;
		max-width: 250px;
	}
	.manual-mass-order-popup_action span
	{
		width: 100%;
    display: inline-block;
    margin-bottom: 15px;
    text-align: center;
    text-transform: uppercase;
    font-size: 15px;
    font-weight: 600;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(0,0,0,0.6);
	}
	.manual-mass-order-popup_action 
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
	.manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action 
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
	.manual-mass-order-popup_action a.fr-btn 
	{
		display: block;
		margin: 20px auto;
		width: 100%;
		max-width: 250px;
	}
	.send_report_another
	{
		margin-left: 10px;
	}
	#another_email
	{
		width: 100%;
	}
	.show
	{
		display: block;
	}
	.manual-mass-order-popup span
	{
		width: 100%;
    display: inline-block;
    margin-bottom: 15px;
    text-align: center;
    text-transform: uppercase;
    font-size: 15px;
    font-weight: 600;
    padding-bottom: 10px;
    border-bottom: 2px solid rgba(0,0,0,0.6);
	}
	#submit_status
	{
		margin-left: 10px;
	}
	.manual-mass-order-popup p,
	.manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action p
	{
		display:none;
		position: absolute;
		margin: 0 auto;
		top: 13px;
		color: rgb(111 105 105 / 95%);
		font-size: 15px;
		font-weight: 500;
	}
	.sts
	{
		    margin-top: 15px;
    margin-bottom: 15px;
	}
	.pagaination_section
	{
		position: relative;
		width: 100%;
		margin: 0 auto;
		text-align: center;
		padding: 40px 0px;
		display: inline-block;
	}
	.pagaination_section a.page-numbers {
    color: black;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    border: 1px solid #ddd;
    display: inline-block;
    margin: 0;
    font-size: 14px;
}

	.pagaination_section span.page-numbers.current {
    background-color: #006799;
    color: white;
    border: 1px solid #006799;
    display: inline-block;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
}
   /*Search Option */
	#fund_search_paid .search-btn3
	{
		background-color: #405e6a !important;
		text-shadow: 0px -1px 0px #22404c;
		background-image: none;
		-webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		-moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
		color: #fff;
		padding: 5px 33px;
		border-radius: 3px;
		border: 0;
		display: inline-block;
		height: 30px;
	}
	
	.messageBox2
	{
		color: red;
		margin-bottom: 8px;
		display:none;
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 3px;
		font-size: 14px;
	}
	.messageBox2 strong
	{
		font-weight:800;
	}
	.widget-table .widget-content.search-section
	{
		background: transparent;
		padding: 20px;
	}
	#fund_search_paid
	{
		width: 100%;
		margin: 0 auto;
		text-align: center;
		display: inline-block;
	}
	#fund_search_paid .search-item
	{
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 9px;
		font-size: 14px;
	}
	#main_paid_onerecord
	{
		display:none;
	}

	.hide_load
	{
		display:none;
	}
	.show_load
	{
		display:block;
	}
	#loading_search
	{
		padding: 90px 40px;
		position: absolute;
		text-align: center;
		margin: 0 auto;
		left: 50%;
	}
	</style>
	<script>
		jQuery(document).ready(function () 
		{
			var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery(".search-btn3").click(function(e){
				jQuery("#main_paid_onerecord tbody").empty();
				jQuery("#main_paid_onerecord #loading_search").addClass("show_load");
				e.preventDefault();
				var search_id=jQuery("#search_id").val();
				if(search_id!="")
				{
					 jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wp_send_check_order_ajax_record', search_id:search_id},
			
							success: function(response) {
								console.log(response);
								jQuery("#main_paid_onerecord #loading_search").removeClass("show_load");
								jQuery("#main_paid_onerecord #loading_search").addClass("hide_load");
								if(response!=0)
								{
									jQuery("#main_paid_onerecord tbody").html(response);
									jQuery("#search_table").css("display","none");
									jQuery("#main_paid_onerecord").css("display","block");
								}
								else
								{
									jQuery("#search_table").css("display","none");
									jQuery("#main_paid_onerecord").css("display","block");
									jQuery("#main_paid_onerecord tbody").html("<p style='margin: 0 auto;padding: 50px;position: absolute;left: 44%;'>No Results!!</p>");
								}
								
								
							}
						});
					
				}
				else{
					window.location="<?php echo admin_url(); ?>admin.php?page=send_check_orders";
				}
			});
		});
</script>
	
<script>
jQuery(document).ready(function(){
jQuery(document).on('click', '#submit_status', function (e){
		e.preventDefault();
		var ajaxurl= "<?php echo admin_url('admin-ajax.php'); ?>";
	
		jQuery(".manual-mass-order-popup p").css("display","block");
			jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text("Please Wait..");
			var send_check_fund_order_id=jQuery(this).attr("data-orderid"); 
			var send_check_paid_status=jQuery(this).attr("data-action");
			var order_status_send_check_number=jQuery(this).attr("data-number");
			if(send_check_paid_status==1)
			{
				var send_check_action="processing";
			}
			else{
				
			}
			var fund_id=jQuery(this).attr("data-id"); 
			var fid=jQuery(this).attr("data-id"); 
			var payment_cheque=jQuery(this).attr("data-check"); 
			var payment_sale=jQuery(this).attr("data-sale"); 
			jQuery(this).text("Please Wait..");
			jQuery.ajax({

				 type : "post",

				 url : ajaxurl,

				 data : {action: 'send_check_custom_fund_status', send_check_fundid:fund_id,send_check_fund_order_id:send_check_fund_order_id,send_check_action:send_check_action,order_status_send_check_number:order_status_send_check_number,send_check_paid_status:send_check_paid_status},

				 success: function(response) {
				var fund_id=response.data.order_id;
					console.log(response);
					/*jQuery("#fund_"+fund_id+" #paid_order_status").text("Paid");*/
					/*if(response.data.order_status=="processing")
					{
						jQuery("#fund_"+fund_id+" #5_").html("<span class='hold-pink'>Processing</span>");
					}*/
					setTimeout(function(){ jQuery(".manual-mass-order-popup").removeClass("show");  }, 1000);
			
				/*alert(response.data.action);*/
				if(response.data.action==1)
				{
					jQuery("#fund_"+fund_id+" #8_").html('<span id="" class="label label-success">Paid</span>');
				
				}
				if(response.data.action==2)
				{
					jQuery("#fund_"+fund_id+" #8_").html('<span id="" class="label label-danger">Unpaid</span>');
				}
				jQuery("#fund_"+fund_id+" #9_").text(response.data.cheque);
			
				/*setTimeout(function(){ jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text(""); }, 2000);
				jQuery("#st_1").removeAttr("checked");
				jQuery("#st_2").removeAttr("checked");
				jQuery("#ch_no").val("");
				jQuery("#submit_status").attr("data-action",""); 
				jQuery("#submit_status").attr("data-number",""); 
				*/
					jQuery.ajax({

				 type : "post",

				 url : ajaxurl,

				 data : {action: 'custom_send_check', fid:fid, payment_cheque:order_status_send_check_number, payment_sale:payment_sale},

				 success: function(response) {
					 
				  console.log(response);
				  jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text(response);
				 setTimeout(function(){ jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text(""); }, 2000);
				jQuery("#st_1").removeAttr("checked");
				jQuery("#st_2").removeAttr("checked");
				jQuery("#ch_no").val("");
				jQuery("#submit_status").attr("data-action",""); 
				jQuery("#submit_status").attr("data-number",""); 
				setTimeout(function(){ jQuery(".manual-mass-order-popup").removeClass("show");  }, 2000);
				   
				}
			});
				
				
				}
			});
		});
		jQuery(document).on('click', '#paid_order_status', function (e){
			e.preventDefault();
			jQuery(".manual-mass-order-popup").addClass("show");
			
			var send_check_fund_order_id=jQuery(this).attr("data-orderid"); 
			var fund_id=jQuery(this).attr("data-fundid"); 
			var gname=jQuery(this).attr("data-gname"); 
			var payment_cheque=jQuery(this).attr("data-check"); 
			var payment_sale=jQuery(this).attr("data-sale");
			jQuery('#submit_status').attr("data-orderid",send_check_fund_order_id); 
			jQuery('#submit_status').attr("data-id",fund_id); 
			jQuery('#submit_status').attr("data-check",payment_cheque); 
			jQuery('#submit_status').attr("data-sale",payment_sale);
			jQuery(".poporderID").html('Order ID: #'+send_check_fund_order_id+" <br/> Fundraiser ID: #"+fund_id+" <br/> Group Name: "+gname);
			
		});
		jQuery('input[type=radio][name=paid_type]').change(function() 
		{
			 var radioValue = jQuery("input[type=radio][name=paid_type]:checked").val();
			jQuery("#submit_status").attr("data-action",radioValue);
			
		});
		jQuery('#ch_no').change(function() 
		{
			 var chequeValue = jQuery(this).val();
			jQuery("#submit_status").attr("data-number",chequeValue);
			
		});
		jQuery(document).on('click', '#m-txt-link', function (e) {
			e.preventDefault();
			jQuery(".manual-mass-order-popup").removeClass("show");
	    });
		  });
</script>

<div id="" class="manual-mass-order-popup">
	
        <div class="manual-mass-order-popup-wrapper">
			<p></p>
		<span>Action on Order Satus</span>
		<span class="poporderID" style="border:none;text-transform: capitalize;color: #756d6d;"></span>
			<label>Check : </label> <input type="text" id="ch_no" class="status_type" name="cheque_number" value="" >
          <div class="sts"> <input type="radio" id="st_1" class="status_type" name="paid_type" value="1" >Set paid<input type="radio" id="st_2" class="status_type" name="paid_type" value="2">Unpaid
			 </div> <input id="submit_status" type="submit" name="submit" value="Submit" data-check="" data-sale="" data-id="" data-orderid="" data-action="" data-number="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
<div class="widget widget-table">	
	<div class="widget-header">						
		<h3 class="card-title">Send Check Orders</h3>
	</div>
	<div id="wrap" class="widget-content search-section" style="clear: both;">
			<!--<div class="messageBox"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>-->
				<form action="#" name="fund_search_paid" id="fund_search_paid" method="get">
					<div class="row">
						<div class="span6">
							<div class="search-item">Enter Order ID:</div> <input type="text" name="search_id" size="100" value="" id="search_id"><input type="submit" value="Submit" class="btn btn-primary search-btn3">
						</div>
						
					</div>
				</form>
	</div>
	<div id="main_paid_onerecord" class="widget-content" style="clear: both;">
			<table class="table table-bordered table-striped table-highlight responsive">
				<thead>
				  <tr>
					<!--<th id="" style="vertical-align: top; text-align: center;">Sr No:</th> -->
					<th id="" style="vertical-align: top; text-align: center;">Order #:</th>
					<th id="" style="vertical-align: top; text-align: center;">Fund ID</th>
					<th id="" style="vertical-align: top; text-align: center;">Organization</th>
					<th id="" style="vertical-align: top; text-align: center;">Order Date</th>
					<th id="" style="vertical-align: top; text-align: center;">Customer</th>
					<th id="" style="vertical-align: top; text-align: center;">Product Sale</th>
					<th id="" style="vertical-align: top; text-align: center;">Payment Method</th>
					<!--<th id="" style="vertical-align: top; text-align: center;">Fund Status</th>-->
					<th id="" style="vertical-align: top; text-align: center;">Check</th>
					<th id="" style="vertical-align: top; text-align: center;">Payment Status</th>
					<th id="" style="vertical-align: top; text-align: center;">Order Status</th>
					<th id="" style="vertical-align: top; text-align: center;">Action</th>
					
					<!--<th id="" style="vertical-align: top; text-align: center;">Action:</th>-->
				 </tr>
				</thead>
				<tbody>
		
					<img id="loading_search" src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/08/ajax-loader.gif" class="hide_load">
		
				</tbody>
			</table>
	</div>
	<div id="search_table" class="widget-content " style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
		    <!--<th id="" style="vertical-align: top; text-align: center;">Sr No:</th> -->
			<th id="" style="vertical-align: top; text-align: center;">Order #:</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund ID</th>
			<th id="" style="vertical-align: top; text-align: center;">Organization</th>
			<th id="" style="vertical-align: top; text-align: center;">Order Date</th>
			<th id="" style="vertical-align: top; text-align: center;">Customer</th>
			<th id="" style="vertical-align: top; text-align: center;">Product Sale</th>
			<th id="" style="vertical-align: top; text-align: center;">Payment Method</th>
			<!--<th id="" style="vertical-align: top; text-align: center;">Fund Status</th>-->
			<th id="" style="vertical-align: top; text-align: center;">Check</th>
			<th id="" style="vertical-align: top; text-align: center;">Payment Status</th>
			<th id="" style="vertical-align: top; text-align: center;">Order Status</th>
			<th id="" style="vertical-align: top; text-align: center;">Action</th>
			
			<!--<th id="" style="vertical-align: top; text-align: center;">Action:</th>-->
		 </tr>
		</thead>
		<tbody>
	<?php 
	

					
	 $count_args = array(
                                 'type'  => 'shop_order',
                                'meta_query' => array(
									array( 'key' => 'wc_order_type', 'value' => array( 'Catlog', 'Online' ), 'compare' => 'IN' ),
								),
								'payment_method' => 'cheque',
								'limit' => -1
						   );
        $c_orders = wc_get_orders( $count_args);
		
		// count the number of orders found in the query
	$total_orders = $c_orders ? count($c_orders) : 1;

		// grab the current page number and set to 1 if no page number is set
		$page = isset($_GET['pg']) ? $_GET['pg'] : 1;

		// how many users to show per page
		$order_per_page = 50;

		// calculate the total number of pages.
		$total_pages = 1;
		$offset = $order_per_page * ($page - 1);
	 $total_pages = ceil($total_orders / $order_per_page);  
		$cno=1;
		/*$args = array(
					'role'    => 'Fundraiser',
					'orderby' => 'user_nicename',
					'order'   => 'ASC'
				);
				$all_meta_for_users = get_users($args);
				foreach ( $all_meta_for_users as $all_meta_for_user ) {*/
						$count=0;
						//$ID=$all_meta_for_user->ID;	
						$arr=array();
						$total_sale_so_far=0;
						$rebate_so_far=0;
                        $argss = array(
                               'type'  => 'shop_order',
                                'meta_query' => array(
									array( 'key' => 'wc_order_type', 'value' => array( 'Catlog', 'Online' ), 'compare' => 'IN' ),
								),
								'payment_method' => array('cheque','authorize'),
								'limit' => $order_per_page,
								 'paged' => $page
								
							 );
                        $orders = wc_get_orders($argss);
                        foreach ( $orders as $order ) {
							$moveOrder=0;
							//echo $order->get_id().' ';
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                          /*  if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								$order_status = $order_data['status'];
								$payment_method = $order->get_payment_method(); 
								if ( $order->has_status('on-hold')) {
									if($payment_method=="authorize"){
										$moveOrder=1;
									}
								}
								if($payment_method=="cheque" || $moveOrder==1){
								$payment_title = $order->get_payment_method_title(); 
								if ( $order->has_status('completed') || $order->has_status('processing')  || $order->has_status('on-hold') || $order->has_status('waiting-call')) {
									/* Active/Inactive */	
								    $order_date = ($order->get_date_created() ? $order->get_date_created()->date("Y-m-d H:i:s") : "");
							
								
							      /* if ($frID3 == $order_customer_id) 
									{*/
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									   if ($order_type=='Catlog' || $order_type=='Online')
									  {
										  $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity; 
									/*	if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										} */
									       $total_sale=  get_post_meta( $order->get_id(), '_order_total', true);

				
				   
					
					   $org = !empty($frID3) ? get_user_meta( $frID3 , 'user_registration_name_of_school_business' , true ) : '';
					$action_status = !empty($frID3) ? get_user_meta( $frID3 , 'user_action_status' , true ) : '';
					$active_date = !empty($frID3) ? get_user_meta($frID3, "user_close_out_date_active", true) : '';
					$inactive_date = !empty($frID3) ? get_user_meta($frID3, "user_close_out_date_inactive", true) : '';
					$status = get_post_meta( $order->get_id() , 'order_status_send_check' , true );
					$send_check_number = get_post_meta( $order->get_id() , 'order_status_send_check_number' , true );
					$created_date_str = $order->get_date_created() ? $order->get_date_created()->date('M d Y') : '';
					?>
					
				<tr id="<?php echo "fund_".$order->get_id(); ?>" class="<?php  echo 'sr_'.$cno." "; if($cno % 2 == 0){ echo "even "; } else{ echo "odd "; } echo $payment_method."  ".$payment_title; ?>">
				<!--<td id="<?php echo 'sr_'.$cno." "; ?>" style="vertical-align: top; text-align: center; "><?php echo $cno; ?></td> -->
				<td id="0_" style="vertical-align: top; text-align: center; "><?php echo "#".$order->get_id(); ?></td>
				<td id="1_" style="vertical-align: top; text-align: center;"><?php echo $frID3;?></td>
				<td id="10_" style="vertical-align: top; text-align: center;"><?php echo $org; ?></td>
				<td id="2_" style="vertical-align: top; text-align: center; "><?php echo $created_date_str; ?></td>
				<td id="4_" style="vertical-align: top; text-align: center; "><?php echo isset($order_data['billing']['first_name']) ? $order_data['billing']['first_name'] : ''; ?> <?php echo isset($order_data['billing']['last_name']) ? $order_data['billing']['last_name'] : ''; ?></td>
				<td id="4_" style="vertical-align: top; text-align: center; ">$<?php  echo $total_sale=number_format( (float)$total_sale, 2); ?></td>
				<td id="6_" style="vertical-align: top; text-align: center; "><?php if($payment_title=="Authorize.net" || $moveOrder==1){ echo "Send Check (For Schools, Gyms & Companies)- <b>Moved Order</b>"; } else { echo $payment_title; } ?></td>
				<td id="9_" style="vertical-align: top; text-align: center; "><?php if(!empty($send_check_number)){ echo $send_check_number; } else{ echo "-"; }?></td>
			    <!--<td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>-->
				<td id="8_" style="vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $order->get_id();?>" class="label label-success">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $order->get_id();?>" class="label label-danger">Unpaid</span><?php }  else { ?><span id="action_status_<?php echo $order->get_id();?>" class="label label-danger">Unpaid</span><?php } ?></td>		
				<td id="5_" style="vertical-align: top; text-align: center; "><span class="<?php if($order_status=="on-hold"){ echo 'hold-blue'; } else if($order_status=="completed"){ echo 'hold-green'; } else if($order_status=="processing"){ echo 'hold-pink'; } else{ } ?>"><?php if($order_status=="on-hold"){ echo "On hold"; } else if($order_status=="completed"){ echo 'Completed'; } else if($order_status=="processing"){ echo 'Processing'; } else { echo $order_status; } ?></span></td>
				<td id="7_" style="vertical-align: top; text-align: center; "><div class="btn-toolbar"><div class="first-col" style="margin-bottom:10px;">&nbsp;<a data-fundid="<?php echo $frID3; ?>" data-sale="<?php echo $total_sale; ?>" data-check="<?php echo $send_check_number; ?>" data-gname="<?php echo $org; ?>" data-orderid="<?php echo $order->get_id(); ?>" id="paid_order_status" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Action</a></div></div></td>
			</tr>
			<?php  $cno++; }
								    /* } */
								   

						

						  } } } ?>
						 <?php /*}*/ ?>
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
		
		<div class="pagaination_section">
<?php 
echo paginate_links( array(
    'base' => $base, // the base URL, including query arg
    'format' => '&pg=%#%', // this defines the query parameter that will be used, in this case "p"
    'prev_text' => __('&laquo; Previous'), // text for previous page
    'next_text' => __('Next &raquo;'), // text for next page
    'total' => $total_pages, // the total number of pages we have
    'current' => $page, // the current page
    'show_all' => False,
     'end_size' => 5,
     'mid_size' => 5,
     'type' => 'plain',
     'add_args' => False,
      'add_fragment' => ''
));
		?>
		</div>
	</div>
</div>

<?php
}
?>