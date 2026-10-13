<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<style>
.widget {
    position: relative;
    clear: both;
    width: auto;
    margin-bottom: 2em;
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
	</style>
	<style>
.proccessloader,
.proccessloader2
{
	display:none;
	    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 999;
    background: rgba(0,0,0,0.8);
    top: 0;
    left: 0;
    margin: 0 auto;
    text-align: center;
}
.proccessloader .inner,
.proccessloader2 .inner
{
	    display: inline-block;
    width: 100%;
    max-width: 500px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
    text-align: center;
}
.proccessloader .inner p,
.proccessloader2 .inner p
{
	display:none;
}
.proccessloader.success .inner,
.proccessloader2.success .inner
{
	background: #fff;
    padding-bottom: 30px;
}
.proccessloader.success .inner img,
.proccessloader2.success .inner img
{
	width: 200px;
}
.proccessloader.success .inner p,
.proccessloader2.success .inner p
{
	color: green;
    position: absolute;
    top: 66%;
    left: 32%;
    font-size: 18px;
    margin: 0;
    display:block;
}
</style>
	<script>
		jQuery(document).ready(function () 
		{
        
			var ajaxurl= "<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery(document).on('click', '.status', function (e){
				e.preventDefault();
				jQuery(".manual-mass-order-popup").addClass("show");
				let fund_id = jQuery(this).data('id');
				let fund_action = jQuery(this).data('action');
				let rebate_action = jQuery(this).data('rebate');
				jQuery('#submit_status').attr("data-id",fund_id); 
				jQuery('#submit_status').attr("data-action",fund_action); 
				jQuery('#submit_status').attr("data-rebate",rebate_action); 
				if(fund_action==1)
				{
					
					jQuery("#st_1").attr("checked","checked");
					jQuery('#st_2').removeAttr("checked");
				}
				else if (fund_action==2) {
				/*	jQuery('#st_2').attr("value","2"); */
				  
					jQuery("#st_2").attr("checked","checked");
					jQuery('#st_1').removeAttr("checked");
				}
				else
				{
					/*jQuery("#st_2").attr("checked","checked");*/
					jQuery('#st_1').removeAttr("checked");
					jQuery('#st_2').removeAttr("checked");
				}
				
	    });
		jQuery('input[type=radio][name=paid_type]').change(function() 
		{
			 var radioValue = jQuery("input[type=radio][name=paid_type]:checked").val();
			jQuery("#submit_status").attr("data-action",radioValue);
			
		});
		jQuery(document).on('click', '#submit_status', function (e){
			e.preventDefault();
			jQuery(".manual-mass-order-popup p").css("display","block");
			jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text("Please Wait..");
			let st_id = jQuery(this).attr('data-id');
			let st_ac = jQuery(this).attr('data-action');
				let rebate = jQuery(this).attr('data-rebate');
			    jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'custom_fund_status', fund_id:st_id,st_ac:st_ac,rebate:rebate},

         success: function(response) {

        console.log(response);
		jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text("Successfully Updated!!");
				setTimeout(function(){ jQuery(".manual-mass-order-popup").removeClass("show");  }, 1000);
				
				/*alert(response.data.action);*/
				if(response.data.action==1)
				{
					jQuery("#fund_"+st_id+" #6_").html('<span id="" class="label label-success">Paid</span>');
					/*jQuery('#fund_'+st_id+' #7_ #all_action').css("display","none");*/
				}
				if(response.data.action==2)
				{
					jQuery("#fund_"+st_id+" #6_").html('<span id="" class="label label-danger">Unpaid</span>');
				}
				
				setTimeout(function(){ jQuery(".manual-mass-order-popup .manual-mass-order-popup-wrapper p").text(""); }, 2000);
		/*
		jQuery(".admin-report").append('<p style="padding-top:10px;color:green;">'+response+'</p>');
		jQuery(".manual-mass-order-popup").removeClass("show");
		window.location.href = "<?php echo admin_url(); ?>admin.php?page=fundraiser_operations&paid=true";
		*/
		}
		});
		});
		
		/*Active /Incative */
		jQuery(document).on('click', '.action_status', function (e){
				e.preventDefault();
				jQuery(".manual-mass-order-popup_action").addClass("show");
				let fund_id = jQuery(this).data('id');
				let fund_action = jQuery(this).data('action');
				/*alert(fund_action);*/
				jQuery('#submit_action_status').attr("data-id",fund_id); 
				jQuery('#submit_action_status').attr("data-action",fund_action); 
				if(fund_action==1)
				{
					
					jQuery("#a_st_1").attr("checked","checked");
					jQuery('#a_st_2').removeAttr("checked");
				}
				else if (fund_action==2) {
				/*	jQuery('#st_2').attr("value","2"); */
				  
					jQuery("#a_st_2").attr("checked","checked");
					jQuery('#a_st_1').removeAttr("checked");
				}
				else
				{
					/*jQuery("#a_st_2").attr("checked","checked");*/
					jQuery('#a_st_1').removeAttr("checked");
					jQuery('#a_st_2').removeAttr("checked");
				}
				
	    });
		jQuery('input[type=radio][name=action_type]').change(function() 
		{
			 var radioValue = jQuery("input[type=radio][name=action_type]:checked").val();
			jQuery("#submit_action_status").attr("data-action",radioValue);
			
		});
		jQuery(document).on('click', '#submit_action_status', function (e){
			e.preventDefault();
			jQuery(".manual-mass-order-popup_action p").css("display","block");
			jQuery(".manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action p").text("Please Wait..");
			let st_id = jQuery(this).attr('data-id');
			let st_ac = jQuery(this).attr('data-action');
			jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'fund_action_status', a_fund_id:st_id,a_st_ac:st_ac},

         success: function(response) {

        console.log(response);
		jQuery(".manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action p").text("Successfully Updated!!");
				setTimeout(function(){ jQuery(".manual-mass-order-popup_action").removeClass("show");  }, 1000);
				
				/*alert(response.data.action);*/
				if(response.data.action==1)
				{
					jQuery("#fund_"+st_id+" #8_").html('<span id="action_st" class="label label-success">Active</span>');
				}
				if(response.data.action==2)
				{
					jQuery("#fund_"+st_id+" #8_").html('<span id="action_s2" class="label label-danger">Inactive</span>');
				}
				setTimeout(function(){ jQuery(".manual-mass-order-popup_action .manual-mass-order-popup-wrapper_action p").text(""); }, 2000);
		/*jQuery(".admin-report").append('<p style="padding-top:10px;color:green;">'+response+'</p>');
		jQuery(".manual-mass-order-popup_action").removeClass("show");
		window.location.href = "<?php echo admin_url(); ?>admin.php?page=fundraiser_operations&action=true";
		*/
		}
		});
		});
		/* End */
	  	jQuery(document).on('click', '#m-txt-link', function (e) {
			e.preventDefault();
			jQuery(".manual-mass-order-popup,.manual-mass-order-popup_action").removeClass("show");
	    });
	
		});
		jQuery(document).on('click', '#send_report', function (e){
			e.preventDefault();
			jQuery(".proccessloader2").css("display","block");
			let fund_id = jQuery(this).data('fund_id');
         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'custom_fund_report_mail', fund_id:fund_id},

         success: function(response) {
		/*alert(response);*/
        console.log(response);
		jQuery(".proccessloader2").addClass("success");
				
				jQuery(".proccessloader2.success img").attr("src","https://glowscentedcandles.com/wp-content/uploads/2020/12/giphy.gif");
				jQuery(".proccessloader2.success").delay(4000).fadeOut();
				setTimeout(function(){ jQuery(".proccessloader2").removeClass("success"); jQuery(".proccessloader2 img").attr("src","https://glowscentedcandles.com/wp-content/uploads/2020/12/fd420f3a680e39cde8bda39f5f996964.gif"); }, 5000);
		/*jQuery(".admin-report").append('<p style="padding-top:10px;color:green;">'+response+'</p>');*/
		}
		});

});
</script>
<?php  

		 $current_user = wp_get_current_user();
		//print_r($current_user);

		$current_userID = $current_user->ID;
	    if($_GET['userid']){
	    	$current_userID = $_GET['userid'];
	    }else{
	    	$current_userID = $current_user->ID;
	    }
	    
		$current_email = $current_user->user_email;
if($_GET['page']=='fundraiser_operations' && $_GET['closed']=="closed_fundraisers"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
		/*Search Option */
	#fund_search .search-btn2
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
	#fund_search
	{
		width: 100%;
		margin: 0 auto;
		text-align: center;
		display: inline-block;
	}
	#fund_search .search-item
	{
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 9px;
		font-size: 14px;
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
	</style>
	<script>
		jQuery(document).ready(function () 
		{
			jQuery(".search-btn2").click(function(e){
				
				
				e.preventDefault();
				var search_id=jQuery("#search_id2").val();
				if(search_id!="")
				{
					jQuery(".messageBox2").css("display","none");
					jQuery("#search_table2 table tbody tr").css("display","none");
					jQuery("#search_table2 table tbody tr#fd_"+search_id).css("display","table-row");
					
				}
				else{
					jQuery(".messageBox2").css("display","block");
					jQuery("#search_table2 table tbody tr").css("display","table-row");
				}
			});
		});
</script>
	
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
	</div><?php */?>
	<div class="widget1 widget-table">
		<div id="wrap" class="widget-content search-section" style="clear: both;">
			<div class="messageBox2"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>
			<form action="#" name="fund_search" id="fund_search" method="get">
				<div class="row">
					<div class="span6">
						<div class="search-item">Enter Fundraiser ID:</div> <input type="text" name="search_id2" size="100" value="" id="search_id2"><input type="submit" value="Submit" class="btn btn-primary search-btn2">
					</div>
					
				</div>
			</form>
		</div>
	<div class="widget-header">						
		<h3>Closed Fundraisers</h3>
	</div>	
	</div>
	<div id="search_table2" class="widget-content " style="clear: both;padding: 0;">
		<table class="table table-bordered table-striped table-highlight responsive" style="margin: 0;">
		<thead>
		  <tr>
		   <th id="" style="vertical-align: top; text-align: center;">Fund ID:</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization:</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<!--<th id="" style="vertical-align: top; text-align: center;">Members:</th>-->
			<th id="" style="vertical-align: top; text-align: center;">Period:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Closed Date:</th>
			<th id="" style="vertical-align: top; text-align: center;">Confirm:</th>
			<th id="" style="vertical-align: top; text-align: center;">Rebook Date:</th>
			<th id="" style="vertical-align: top; text-align: center;">Print Order:</th>
		 </tr>
		</thead>
		<tbody>
		 <?php 

	
		 $count_args = array(
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'orderby' => 'ID',
					'order'   => 'DESC'
				);
		$user_count_query = new WP_User_Query($count_args);
		$total_users = $user_count_query->get_total();

		// count the number of users found in the query
		$total_users = $user_count ? count($user_count) : 1;

		// grab the current page number and set to 1 if no page number is set
		$page = isset($_GET['pg']) ? max(1, intval($_GET['pg'])) : 1;

		// how many users to show per page
		$users_per_page = 20;

		// calculate the total number of pages.
		$total_pages = 1;
		$offset = $users_per_page * ($page - 1);
		$total_pages = ceil($total_users / $users_per_page); 
		$args = array(
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'orderby' => 'ID',
					'order'   => 'DESC',
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'number'    => $users_per_page,
					'offset'    => $offset  // skip the number of users that we have per page  
				);
			/*$users = get_users( $args ); */
			// Create the WP_User_Query object
				$wp_user_query = new WP_User_Query($args);

				// Get the results
				$authors = $wp_user_query->get_results();
				/* $all_meta_for_users = get_users($args); */
		
	/*	$args = array(
					'role'    => 'Fundraiser',
					'orderby' => 'user_nicename',
					'order'   => 'ASC'
				);
				$all_meta_for_users = get_users($args);
				foreach ( $all_meta_for_users as $all_meta_for_user ) {
					*/
					foreach ( $authors as $user ) 
		            {
						$count=0;
						$ID=$user->ID;	
						
						$status= get_user_meta( $ID , 'user_status' , true );
					    $action_status= get_user_meta( $ID , 'user_action_status' , true );
						if($status==1 && $action_status==2)
					    {
							
						$arr=array();
						$total_sale_so_far=0;
						$rebate_so_far=0;
                        $argss = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                        $orders = wc_get_orders($argss);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                          /*  if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) 
								  {
                               $total_sale_so_far+=$order->get_subtotal();
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                   /*} */
						}
							else if ($frID3 == $order_customer_id) 
									{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}

						  } } }

				
				   
					$all_seller = get_users(array(
					'meta_key'     => 'user_registration_seller_fundraiser',
					'meta_value'   => $ID,
				));
				if ( ! empty( $all_seller ) && is_iterable( $all_seller ) ) {
					foreach ( $all_seller as $seller_item ) {
						$arr[] = $seller_item->ID;
					}
				}
					
				    // $org=get_user_meta( $ID , 'user_registration_input_company' , true );
				    $org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
				    
					$member=count($arr);
					$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
					$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
					
					$currentDate=date("Y-m-d");
					$currentDate = date('Y-m-d', strtotime($currentDate));
					$startDate = date('Y-m-d', strtotime($sd));
					$endDate = date('Y-m-d', strtotime($ed));
					date_default_timezone_set('America/Chicago');
					$active_date=get_user_meta($ID, "user_close_out_date_active", true);
					$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
					$rebate= get_user_meta( $ID , 'user_rebate' , true );
					$rebook_yes= get_user_meta( $ID , 'rebook_yes' , true );
					$rebook_date=get_user_meta($ID, "rebook_date", true);
					
				?>						
			<tr id="<?php echo "fd_".$ID; ?>" style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
				<td id="0_" style="vertical-align: top; text-align: center; "><?php echo $ID;?></td>
				<td id="1_" style="vertical-align: top; text-align: left;width: 180px;"><?php echo $org; ?></td>
				<td id="2_" style="vertical-align: top; text-align: left; "><?php echo $user->user_firstname; ?> <?php echo $user->user_lastname; ?></td>
				<!--<td id="4_" style="vertical-align: top; text-align: center; "><?php echo $member; ?></td>-->
				<td id="5_" style="vertical-align: top; text-align: center; "><span class="label <?php if (($currentDate >= $startDate) && ($currentDate <= $endDate)){ ?>label-success<?php } else { echo "label-danger"; } ?> "><?php echo date( "M d, Y", strtotime( $sd )); ?> - <?php echo date( "M d, Y", strtotime( $ed )); ?></span></td>
				<td id="6_" class="<?php echo $rebate; ?>" style="vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $ID;?>" class="label label-success">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php }  else { ?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php } ?></td>
                <td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
				<td id="11_" style="vertical-align: top; text-align: center; "><?php if(!empty($inactive_date)){  echo $inactive_date;  } ?></td>
				<td id="7_" style="vertical-align: top; text-align: center; "><?php if($rebook_yes=="yes"){ echo "Yes"; } ?></td>
				<td id="9_" style="vertical-align: top; text-align: center; "><?php if(!empty($rebook_date)){ echo $rebook_date ; } ?></td>
				<td id="10_" style="vertical-align: top; text-align: center; ">
					<div class="btn-toolbar ram1"><div class="first-col" style="margin-bottom:10px;">&nbsp;<a href="https://glowbrandscandles.com/print?fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print</a></div></div>
				</td>
			</tr>
		<?php } ?>
						 <?php } ?>
		</tbody>
	</table>
	<?php
		// grab the current query parameters
$query_string = $_SERVER['QUERY_STRING'];

// The $base variable stores the complete URL to our page, including the current page arg

// if in the admin, your base should be the admin URL + your page
$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';
if (strpos($base, '?') === false) {
    $base .= '?pg=%#%';
} else {
    $base .= '&pg=%#%';
}

// if on the front end, your base is the current page
//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';
?>
<div class="pagaination_section">
<?php 
echo paginate_links(array(
    'base'      => $base,
    'format'    => '&pg=%#%',
    'prev_text' => __('&laquo; Previous'),
    'next_text' => __('Next &raquo;'),
    'total'     => $total_pages,
    'current'   => $page,
    'end_size'  => 1,
    'mid_size'  => 5,
));
		?>
		</div>
	
	</div>
<?php } else if($_GET['page']=='fundraiser_operations' && $_GET['request']=="close"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	</style>
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
	</div> <?php */?>
	<div class="widget1 widget-table">
	<div class="widget-header">						
		<h3>Close Out Request</h3>
	</div>	
	</div>
	<div id="" class="widget-content " style="clear: both;padding: 0;">
		<table class="table table-bordered table-striped table-highlight responsive" style="    margin: 0;">
		<thead>
		  <tr>
		    <th id="" style="vertical-align: top;text-align: center;">Fund ID:</th>
		    <th id="" style="vertical-align: top;text-align: center;">Date</th>
			<th id="" style="vertical-align: top; text-align: center;">Organizations/Rep:</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<!--<th id="" style="vertical-align: top; text-align: center;">Action:</th>-->
			
		 </tr>
		</thead>
		<tbody>
		     <?php
			 $arg=array('post_type'=>'close_out','posts_per_page' => -1,'order' => 'DSC');
			 $table_query= new WP_Query();
			 $table_query->query($arg);
			 if ($table_query->have_posts()) : while ($table_query->have_posts()) : $table_query->the_post();
				$postid = $table_query->post->ID; 
				$ID= get_post_meta( $postid, 'close out_fundraiser_id', true );
				$date= get_post_meta( $postid, 'close out_fundraiser_date', true );
				// $org=get_user_meta( $ID , 'user_registration_input_company' , true );
                $org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
				 
				 $action_status= get_user_meta( $ID , 'user_action_status' , true );
					$active_date=get_user_meta($ID, "user_close_out_date_active", true);
					$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
			 ?>
								
			<tr id="<?php echo $postid; ?>" style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
				<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo "#".$ID; ?></td>
			   <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo $date; ?></td>
				<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><?php echo $org; ?></td>
				 <td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
				<!--<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border-right: 1px solid #ddd;"><div class="btn-toolbar ram2">&nbsp;<a href="#" class="btn btn-inverse btn-mini padtop btn-custom action_status" data-id="<?php echo $ID;?>" data-action="<?php echo $action_status;?>" data-active="<?php echo $active_date;?>" data-inactive="<?php echo $inactive_date;?>">Status</a></div></td>-->
			</tr>
			<?php
			 endwhile;
			endif;
			?>
		</tbody>
	</table>
	</div>


<?php } else if($_GET['page']=='fundraiser_operations' && $_GET['print_order']=="true"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	#wrap
	{
		padding: 20px;
	}
	.search-item
	{
		    padding-bottom: 7px;
	}
	#ajax-order
	{
		color: red;
    padding: 17px 0px 0px;
    font-size: 16px;
	}
</style>
<script>
		jQuery(document).ready(function () 
		{
			 var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery(".submit-btn").click(function(e){
			e.preventDefault();
			var orderid=jQuery("#orderid").val();
			 jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'order_ajax', orderid:orderid},
			
							success: function(response) {
								console.log(response);
								if(response.data=="Online")
								{
									jQuery("#ajax-order").text("This is Online Order");
								}
								else if(response.data=="Catlog")
								{
									jQuery("#ajax-order").text("");
									window.location="https://glowbrandscandles.com/print-order/?order_id="+orderid+"&q=full";
								}
								else
								{
									jQuery("#ajax-order").text("Something went wrong!");
								}
								
							}
						});
			
			});
		});
</script>
<div class="widget widget-table">
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
	</div><?php */?>
	<div class="widget-header">						
		<h3>Print Order</h3>
	</div>	
	
	
	<div id="wrap" class="widget-content " style="clear: both;">
		<form action="#" name="printOrder" id="printOrder" method="get">
			<div class="row">
				<div class="span6">
					<div class="search-item">Order ID:</div> <input type="text" name="orderid" size="10" value="" id="orderid"><input type="submit" value="Submit" class="btn btn-primary submit-btn">
					<div id="ajax-order"></div>
				</div>
				
			</div>
			
		</form>
	</div>
	</div>
<?php } else if($_GET['page']=='fundraiser_operations' && $_GET['fundraiser_report']=="true"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	</style>
	<div class="widget widget-table">
	<div class="widget-header">						
		<h3>Fundraisers Report</h3>
	</div>	
	</div>
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
	</div><?php */?>
	<?php 
			$year=$_GET['syear']; 
			$month=$_GET['smonth'];
			$frep=$_GET['rep'];
			$f_id=$_GET['fundid'];
			
			?>
	<div id="" class="widget-content " style="clear: both;">
		<form action="?page=fundraiser_operations&fundraiser_report=true" name="fund_search" id="fund_search" method="get">
			<div class="row">
				<div class="span6">
					<div class="search-item">Fund ID:</div> <input type="text" name="fundid" size="10" value="<?php echo $f_id; ?>" id="fundraiserid">
				</div>
				<div class="span6">
					<div class="search-item">Rep Name:</div> <input type="text" name="rep" size="40" value="<?php echo $frep; ?>" id="rep">
				</div>
			</div>
			

			<div class="row">
				<div class="span6">
					<div class="search-item">Period:</div> 
					<select name="smonth" id="smonth" style="max-width: 120px;" onchange="" tabindex="1">
						<option value="">Not Selected</option>
						<option value="01" <?php if($month =='01') { echo 'selected'; } ?>>January</option>
						<option value="02" <?php if($month =='02') { echo 'selected'; }?>>February</option>
						<option value="03" <?php if($month =='03') { echo 'selected'; }?>>March</option>
						<option value="04" <?php if($month =='04') { echo 'selected';} ?>>April</option>
						<option value="05" <?php if($month =='05') { echo 'selected';} ?>>May</option>
						<option value="06" <?php if($month =='06') { echo 'selected';} ?>>June</option>
						<option value="07" <?php if($month =='07') { echo 'selected';} ?>>July</option>
						<option value="08" <?php if($month =='08') { echo 'selected'; }?>>August</option>
						<option value="09" <?php if($month =='09') { echo 'selected'; }?>>September</option>
						<option value="10" <?php if($month =='10') { echo 'selected'; }?>>October</option>
						<option value="11" <?php if($month =='11') { echo 'selected'; }?>>November</option>
						<option value="12" <?php if($month =='12') { echo 'selected'; }?>>December</option>
					</select> &nbsp; 
					<select name="syear" id="syear" style="max-width: 120px;" onchange="" tabindex="1">
						<option value="2020" selected="">2020</option>
						<option value="2021">2021</option>
						<option value="2022">2022</option>
						<option value="2023">2023</option>
						<option value="2024">2024</option>
						<option value="2025">2025</option>
						<option value="2026">2026</option>
						<option value="2027">2027</option>
					</select>
				</div>
			</div>

			<div class="row" style="margin-top: 10px;">
				<div class="span4">
				<input type="submit" value="Search" class="btn btn-primary search-btn">
				</div>
			</div>
			<input type="hidden" value="fundraiser_operations" name="page">
			<input type="hidden" value="true" name="fundraiser_report">
		</form>
	</div>
	
	<script>
	/*$(document).ready(function(){
		$(".search-btn").on("click", function(){
			var id==$("#fundraiserid").val();
			var rep==$("#rep").val();
			var smonth=$("#smonth option:selected").val();
			var syear=$("#syear option:selected").val();
		    window.location.href = "<?php echo admin_url(); ?>admin.php?page=fundraiser_operations&fundraiser_report=true&fundid="+id+"&rep="+rep+"&smonth="+month+"&syear"+year;
			});
		
	});*/

	</script>
	<?php  if(!empty($_GET['syear'])){ ?>
	<div id="<?php echo $_GET['syear']?>" class="widget widget-table">
<!--<div class="widget-header">						
		<h3>Fundraisers Summery</h3>
	</div>	-->
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;" class="widget-content ">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:0;width: 100%;overflow: hidden;" class="table table-bordered table-striped table-highlight responsive">
       <!-- <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px"> PAID Fundraisers </h1>
		  	</td>
		</tr>-->
		<thead>
		<tr style=" border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
								<th class="fr-order-col fr-order-id" style=" vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Fund ID</th>
                                <th class="fr-order-col fr-order-date" style=" vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Organization/Rep:</th>
                                 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Orders</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;   width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Rebate</th>
								<th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Order Details</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr></thead>
		<?php
		$total_sales=array();
		$total_rebate=array(); 
			$args = array(
			'role'    => 'Fundraiser',
		   'status'   => "Approved",
			'order'   => 'ASC'
			);
			$users = get_users( $args );
	foreach ( $users as $user ) {
		$status= get_user_meta( $user->ID , 'user_status' , true );
		
	      $ID=$_GET['fundid'];
		 
		 $rep=$_GET['rep'];
		  $smonth=$_GET['smonth'];
		  $syear=$_GET['syear'];
		  $ID2=0;
		  $Idd=$user->ID;
		  //$org=get_user_meta( $Idd , 'user_registration_input_company' , true );
		  $org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
		
		  $sd=get_user_meta( $Idd , 'user_registration_start_date' , true );
		  $ed=get_user_meta( $Idd , 'user_registration_end_date' , true );
		  
		  $sdate = explode('-', $sd);
		
		  $total_sale_so_far=0;
		  $rebate_so_far_n=0;
		  if($sdate[0]== $syear)
		  {
			  if(empty($rep) && empty($smonth) && empty($ID) )
			  {
				 $ID2=$user->ID; 
			  }
			  else if(empty($rep) && !empty($smonth) && empty($ID))
			  {
				  if($sdate[1]==$smonth)
				  {
					 $ID2=$user->ID;  
				  }
			  }
			   else if(empty($rep) && !empty($smonth) && !empty($ID))
			  {
				  if($sdate[1]==$smonth && $ID == $Idd)
				  {
					 $ID2=$user->ID;  
				  }
			  }
			   else if(!empty($rep) && !empty($smonth) && !empty($ID))
			  {
				  //$org=get_user_meta( $Idd , 'user_registration_input_company' , true );
				  $org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
				  
				  if($sdate[1]==$smonth && $org == $rep && $ID == $Idd )
				  {
					 $ID2=$user->ID;  
				  }
			  }
			   else if(!empty($rep) && empty($smonth) && empty($ID))
			  {
				  //$org=get_user_meta( $Idd , 'user_registration_input_company' , true );
				  $org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
				  
				  if($org == $rep)
				  {
					 $ID2=$user->ID;  
				  }
			  }
			   else if(empty($rep) && empty($smonth) && !empty($ID))
			   {
				   if($Idd==$ID)
				   {
					    $ID2=$user->ID;
				   }
			   }
			  else{
				  
			  }
			  
			/*	if($ID==$Idd || $rep==$org || $sdate[1]==$smonth || $sdate[0]==$syear){
		  if(empty($ID))
		  {
			 $ID=$user->ID; 
		  }
		  if(empty($org))
		  {
			 
		 
		  }*/
		
		?>
		
		 <?php
		 if($ID2 !=0) {
                               $arr=array();
                              /*  $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID2,
                                ));
							
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; */?>
									  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID2,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                          /*  if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                             $tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                   /*} */
		 } 
		 else if ($frID3 == $order_customer_id) 
									{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}
		 
		 
						  } } } /* } */
									if($total_sale_so_far!=0 && $rebate_so_far!=0){
										$total_sale[]=$total_sale_so_far;
										$total_rebate[]=$rebate_so_far;
										//$org=get_user_meta( $Idd , 'user_registration_input_company' , true );
						    		    $org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
				    
									?>
								
		 <tr style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
       
								
							
								  <?php 
								  $arr_order_id=array();
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID2,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
								$arr_order_id[]=$order->get_id();
                               
                                  ?>
								  	<?php   
						} 
						else if($frID3 == $order_customer_id){
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										  $arr_order_id[]=$order->get_id();
									  }
									}
									else{}
						
								} } }
                        ?>
								
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $ID2; ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $org; ?></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo count($arr_order_id);?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?> </td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><div class="btn-toolbar ram3">&nbsp;<a href="?page=fundraiser_operations&view=true&fund_id=<?php echo $ID2;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom" style="">View</a></div>
									</td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								
							
			 
							
						
			
					
			 
				
          
			
		</tr>
		  <?php  }  } } }
                             if(count($total_sale)>0) { ?>
							   <tr>
							  <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><b> Total Fundraisers: <?php echo $totalF=count($total_sale); ?></b></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> </td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><b>Total Sales : $<?php $totalSales=array_sum($total_sale); echo $Total_sale_so_far=number_format($totalSales, 2, '.', ''); ?></b></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><b>Total Rebate : $<?php $totalRebate=array_sum($total_rebate);echo $Total_rebate_so_far=number_format($totalRebate, 2, '.', ''); ?> </b></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"></div>
									</td>
							  </tr>
							 <?php  } ?>
    </table>
	</div>
    </div>
	<?php } ?>
<?php }  elseif($_GET['page']=='fundraiser_operations' && $_GET['fundraiser_summery']=="true"){ ?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	</style>
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
	</div> <?php */?>
	<div class="widget widget-table">
<div class="widget-header">						
		<h3>Fundraisers Summery</h3>
	</div>	
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;" class="widget-content ">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:0;width: 100%;overflow: hidden;" class="table table-bordered table-striped table-highlight responsive">
       <!-- <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px"> PAID Fundraisers </h1>
		  	</td>
		</tr>-->
		<thead>
		<tr style=" border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
								<th class="fr-order-col fr-order-id" style=" vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Fund ID</th>
                                <th class="fr-order-col fr-order-date" style=" vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Organization/Rep:</th>
                                 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Orders</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;   width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Rebate</th>
								<th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Rebate Details</th>
								<th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Order Details</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr></thead>
		<?php
		

			$args = array(
			'role'    => 'Fundraiser',
			'order'   => 'ASC'
			);
			$users = get_users( $args );
foreach ( $users as $user ) {
		$title="";
		$status= get_user_meta( $user->ID , 'user_status' , true );
		
		 if ($status!=1) { 
		 $ID=$user->ID;
		  //$org=get_user_meta( $ID , 'user_registration_input_company' , true );
		    $org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
				    
		 $total_sale_so_far=0;
							  $rebate_so_far_n=0;
		
		?>
		
		 <?php
                               $arr=array();
                               /* $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
							
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID;*/ ?>
									  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                          /*  if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
									$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                     $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                   /*} */
								} 
								else if ($frID3 == $order_customer_id) 
									{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}
		 
		 
		 
						  } } } /*}*/
									 if ($status==1) { 
									$rebate= get_user_meta( $ID , 'user_rebate' , true );
									$new_rebate=round($rebate_so_far-$rebate, 2);
									
									if($new_rebate!=0)
									{
										$title="*Ext*";
									}	
									else{
										$title="";
									}	
									 }									
									if($total_sale_so_far!=0 && $rebate_so_far!=0){
									?>
								
		 <tr style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
       
								
							
								  <?php 
								  $arr_order_id=array();
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
								$arr_order_id[]=$order->get_id();
                               
								
                                  ?>
								  	<?php   
						} 
						else if($frID3 == $order_customer_id){
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										  $arr_order_id[]=$order->get_id();
									  }
									}
									else{}
						
						
								} } }
                        ?>
								
									<td class="fr-order-col fr-order-id <?php echo $new_rebate." ".$rebate_so_far." ".$rebate; ?>" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $ID.$title; ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $org; ?></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo count($arr_order_id);?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far = number_format($total_sale_so_far, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far = number_format($rebate_so_far, 2, '.', ''); ?> </td>
								     <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><div class="btn-toolbar">&nbsp;<a href="?page=fundraiser_operations&rebate=true&fund_id=<?php echo $ID;?>"  class="btn btn-inverse btn-mini padtop btn-custom" style="">View</a></div>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><div class="btn-toolbar">&nbsp;<a href="?page=fundraiser_operations&view=true&fund_id=<?php echo $ID;?>"  class="btn btn-inverse btn-mini padtop btn-custom" style="">View</a></div>
									</td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								
							
			 
							
						
			
					
			 
				
          
			
		</tr>
<?php  } 
}
 }
                              ?>
    </table>
	</div>
    </div>
<?php
}
elseif($_GET['page']=='fundraiser_operations' && $_GET['paid']=="true"){
?>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
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
	</style>
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
	</div> <?php */?>
	<div class="widget widget-table">
<div class="widget-header">						
		<h3>PAID Fundraisers</h3>
	</div>	
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;" class="widget-content ">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:0;width: 100%;overflow: hidden;" class="table table-bordered table-striped table-highlight responsive">
       <!-- <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px"> PAID Fundraisers </h1>
		  	</td>
		</tr>-->
		<thead>
		<tr style=" border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
								<th class="fr-order-col fr-order-id" style=" vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Fund ID</th>
                                <th class="fr-order-col fr-order-date" style=" vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Organization/Rep:</th>
                                 <th class="fr-order-col fr-order-seller" style=" vertical-align: top; text-align: left;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Orders</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;   width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Total Sales</th>
                                <th class="fr-order-col fr-order-total" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Rebate</th>
								<th class="fr-order-col fr-order-customer" style=" vertical-align: top; text-align: center;  width: 16%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">Order Details</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr></thead>
		<?php
			
			
		$count_args = array(
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'meta_key'     => 'user_status',
					'meta_value'   => '1',
					 'count_total' => true,
					'order'   => 'DESC',
					'number' => 1,
				);
		$user_count_query = new WP_User_Query($count_args);
		$total_users = $user_count_query->get_total();

		// Then count:
		$total_users = $user_count ? count($user_count) : 0;

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
					'role'    => 'Fundraiser',
					 'status'   => "published",
					'order'   => 'DESC',
					'meta_key'     => 'user_status',
					'meta_value'   => '1',
					'number'    => $users_per_page,
					'offset'    => $offset  // skip the number of users that we have per page  
				);
			/*$users = get_users( $args ); */
			// Create the WP_User_Query object
				$wp_user_query = new WP_User_Query($args);

				// Get the results
				$authors = $wp_user_query->get_results();
				/* $all_meta_for_users = get_users($args); */
			
			
		foreach ( $authors as $user ) 
		{
			$status= get_user_meta( $user->ID , 'user_status' , true );
			if ($status==1) { 
				$ID=$user->ID;
				//$org=get_user_meta( $ID , 'user_registration_input_company' , true );
				$org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
				
		
		?>
		
		 <?php $total_sale_so_far=0;
							  $rebate_so_far=0;
                               $arr=array();
                               /* $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
							
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; */ ?>
									  <?php  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                          /*  if ($ID3 == $IDD) { */
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                               /*$total_sale_so_far+=$order->get_subtotal();*/
							        $tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
                                   /*} */
		                        } 
								else if ($frID3 == $order_customer_id) 
									{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}
								
								
								
						  } } } /* } */?>
								<?php if($total_sale_so_far!=0 && $rebate_so_far!=0){ ?>
		 <tr style="width: 100%;padding: 0px; max-width: 1000px;margin: 0 auto;">
       
								
							
								  <?php 
								  $arr_order_id=array();
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                           
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
								  
								$arr_order_id[]=$order->get_id();
                             
                                  ?>
								  	<?php   
		 } 
									else if($frID3 == $order_customer_id){
									$order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										  $arr_order_id[]=$order->get_id();
									  }
									}
									else{}
		 
		 
								} } }
                        ?>
								
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $ID; ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><?php echo $org; ?></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"> <?php echo count($arr_order_id);?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $total_sale_so_far = number_format($total_sale_so_far, 2, '.', ''); ?></td>
									<td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;">$<?php echo $rebate_so_far = number_format($rebate_so_far, 2, '.', ''); ?></td>
								    <td class="fr-order-col fr-order-id" style="vertical-align: top; text-align: center;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;"><div class="btn-toolbar">&nbsp;<a href="?page=fundraiser_operations&view=true&fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom" style="">View</a>
									</td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								
							
			 
							
						
			
					
			 
				
          
			
		</tr>
								<?php } ?>
<?php  } }
                              ?>
    </table>
	<?php
		// grab the current query parameters
$query_string = $_SERVER['QUERY_STRING'];

// The $base variable stores the complete URL to our page, including the current page arg

// if in the admin, your base should be the admin URL + your page
$base = admin_url('admin.php') . '?' . remove_query_arg('pg', $query_string) . '%_%';
if (strpos($base, '?') === false) {
    $base .= '?pg=%#%';
} else {
    $base .= '&pg=%#%';
}

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
    'end_size' => 1,
    'mid_size' => 5,
));
		?>
		</div>
		
	</div>
    </div>
<?php } elseif($_GET['page']=='fundraiser_operations' && $_GET['view']=="true") {
	$ID=$_GET['fund_id'];
	?>
	<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	</style>
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
	</div> <?php */?>
	<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:0;width: 98%;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px"> Order Details </h1>
		  	</td>
		</tr>
		
		 <?php
		 /* Start Print Order List of All Seller */
                               $arr=array();
                                $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
							
						$registered_seller=count($all_meta_for_users);
					if($registered_seller==0)
					{
								?>
								<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale">0.00</span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate">0.00</span></strong></td>
														</tr>
														<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
															<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
															<th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
															<th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
															<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
															<!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
										
														</tr>
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
							<?php								
					}
					else
					{
						$total_sale_so_far=0;
							  $rebate_so_far=0;
						 foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; 
						  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
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
								  if ($frID3 != $order_customer_id) 
								  { 
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									 $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								} } } }
						}
						 }
					?>
					
					<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_registered=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_registered=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
													
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
					
					<?php	
					}	
							
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; ?>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                               $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == $IDD) { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        $slr_info  = get_userdata($slrID);
                                       ?>
                                       <?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  } } } }  
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   <?php } 
           /* End Print Order List of All Seller */  ?>
		     <?php  
           /* Start Print Order List of Manually Added Seller */  ?>
		    <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == "custom") { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                               $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } }
						}
						
						
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
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
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									 }
									}
							}
						}
						/* Calculate seller Total*/
							$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
						for($i=0;$i<count($seller_name);$i++) 
						{
						$seller_rebate=0;
							$seller_total_sale=0;
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
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
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														  foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
														 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
														 {
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
														 }
														 else
														 {
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
															
														 }
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
									}
									}
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						}
						
						
						
						
						
						
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:blue;">B: Manually Added Seller</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							<?php
								$exist_seller=count($seller_name);
								if($exist_seller==0){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
								}
								?>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							for($i=0;$i<count($seller_name);$i++)
							{ ?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 18px;margin-top:15px;color:blue;"><?php echo $seller_name[$i]; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $seller_t=number_format($seller_total_Sale_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $seller_r=number_format($seller_rebate_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
						
							<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
						
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) {
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
							     if ($frID3 != $order_customer_id) { 
								 
								 $s_name_meta=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
								 if($seller_name[$i] == strtolower($s_name_meta)) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
											
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                             /*  } */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php }  } } } }
                        }
							}
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		    <?php  
           /* End Print Order List of Manually Added Seller */  ?> 
		   
		    <?php  
           /* Start Print Order List of Seller not mentioned */  ?>
		    <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if (empty($ID3)) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                                   $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:purple;">C: Seller not mentioned</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if (empty($ID3)) { 
								
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  }  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		    <?php  
           /* End Print Order List of Seller not mentioned */  ?> 
		   	<!-- Fundraiser Online Sales-->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='Online') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
						} } } }  
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#f54298;">D: Fundraiser Online Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
									if ( $order->has_status('completed') || $order->has_status('processing')) {
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
							     if ($order_type=='Online') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		 
		 
		 <!-- Catalog Sale -->
		
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  	$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										$order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
										

								 }
							
							} }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#a84032;">E: Fundraiser Catalogue Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Customer</th>
                                <!--<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller</th>-->
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>-->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							$new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
									if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							     if ($order_type=='Catlog') { 
								   $order_item_quantity = 0; 
										 $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>-->
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$<?php  echo $total_sale=number_format( $total_sale, 2); ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS || !empty($new_catalog_order) && $new_catalog_order == "Yes")
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>-->
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							<?php  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		
    </table>
	
    </div>
<?php } elseif($_GET['page']=='fundraiser_operations' && $_GET['rebate']=="true")
	{ $ID=$_GET['fund_id'];?>
<style>
.pay_now
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
    margin-left: 1.3em;
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
    text-transform: capitalize;
}
</style>
<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	.wp_cart_button_wrapper
	{
		display: inline-block;
		margin-left: 10px;
	}
	</style>
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
	</div><?php */?>
<div marginwidth="0" marginheight="0" style="font-family:Arial,sans-serif;background:#f2f2f2;width:100%;float:left;">
   <table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" bgcolor="#fff" style="background:#fff;margin:40px auto;width: 98%;overflow: hidden;">
        <tr>
             <td align="center" style="padding:30px; text-align:center;background: -webkit-linear-gradient(top, #FAFAFA 0%,#E9E9E9 100%);color: #000;">
				<h1 style="color:#555;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;line-height: 18px"> Rebate </h1>
		  	</td>
		</tr>
		<?php
		
		 
		 /* rebate Total */
							$total_sale_so_far=0;
							$rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) 
							{
								$frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
								$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
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
								  if ($frID3 != $order_customer_id) 
								  {
									$tdiscount=$order->get_total_discount();
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
											 $total_sale_so_far+=$order->get_subtotal();
										}
										foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                        endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									   
									} 
									else if ($frID3 == $order_customer_id) 
									{
									  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
									  if($order_type=="Online") 
									  {
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
										$total_product = $order->get_total();   ///$order_data->get_total();


										$percentage  = 40;
										$percentFifty= 50;

									   $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
							
								 
										 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
										 {
											$Discount    = ($percentFifty / 100) * $totalAmount;
											$rebate_so_far+=$Discount;
										 }
										 else
										 {
											$Discount    = ($percentage / 100) * $totalAmount;
											$Discount = number_format($Discount, 2, '.', '');
											 $rebate_so_far+=$Discount;
										 }
									}
								} 
								else{}
								} }
                            
						}
		 
		 
								 $status= get_user_meta( $ID , 'user_status' , true );
								 if( $status==1)
								 {
								 $rebate= get_user_meta( $ID , 'user_rebate' , true );
								 $remaining_rebate=round($rebate_so_far-$rebate, 2);
								 }
								 $rebate_option = get_user_meta($ID, 'user_registration_rebate_option', true );
		?>
		<tr>
		<?php if($rebate_option=="Check"){  $option_val = get_user_meta($ID, 'user_registration_check_payable', true ); ?>
			<td style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 18px 26px 5px;color: green;">Rebate Pay By Cheque: <span style="color: green;text-transform: capitalize;"><?php echo $option_val; ?><span></td>
		<?php } if($rebate_option=="PayPal"){ $option_val = get_user_meta($ID, 'user_registration_paypal_email', true );  ?>
		   <td style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 18px 26px 5px;color: green;">Rebate Pay By PayPal: <span style="color: green;text-transform: lowercase;"><?php echo $option_val; ?><span></td>
		<?php } ?>
			<td style="display: block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 5px 26px 5px;color: green;">Total Rebate: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far = number_format($rebate_so_far, 2, '.', '');?></span></strong><?php if($rebate_option=="PayPal"){ echo  do_shortcode('[wp_cart_button name="Fund ID: '.$ID.'" price="'.$rebate_so_far.'"]'); } ?>
			</td>
			<?php  if( $status==1 && $remaining_rebate <= 0 ){ ?>
			 <td class="<?php echo $remaining_rebate; ?> gg" style="display: inline-block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 5px 26px 5px;color: green;">Paid Rebate: <span style="color: #333;text-transform: lowercase;"><?php echo "$".$rebate; ?><span></td>
			
	<?php } else{ ?>
	<td class="<?php echo $remaining_rebate; ?>" style="display: block;margin-bottom: 0px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;padding: 5px 26px 5px;color: green;">Remaining Rebate: <span style="color: green;text-transform: lowercase;"><?php $remaining_r = abs($remaining_rebate); $remaing_rebate_cal=number_format($remaining_r, 2, '.', ''); ?><?php echo "$".$remaing_rebate_cal; ?></span></td>
	<?php } ?>
		</tr>
		 <?php
		 /* Start Print Order List of All Seller */
                               $arr=array();
                                $all_meta_for_users = get_users(array(
                                    'meta_key'     => 'user_registration_seller_fundraiser',
                                    'meta_value'   => $ID,
                                ));
							
						$registered_seller=count($all_meta_for_users);
					if($registered_seller==0)
					{
								?>
								<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale">0.00</span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate">0.00</span></strong></td>
														</tr>
														<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
															<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
															<th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
															<th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
															<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
															<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
															<!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
										
														</tr>
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
							<?php								
					}
					else
					{
						$total_sale_so_far=0;
							  $rebate_so_far=0;
						 foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; 
						  
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) 
								{
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
								  if ($frID3 != $order_customer_id) 
								  { 
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
									/*$total_sale_so_far+=$order->get_subtotal();*/
									$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									 $tdiscount=$order->get_total_discount();
											
										if(!empty($tdiscount))
										{
											 $newamt=$order->get_subtotal()-$tdiscount;
											 $totalAmount = $newamt;
										}
										else
										{
										 $totalAmount = $order->get_subtotal();
										}
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount_d = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount_d;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount_d = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount_d;
								 }
                               
							  
								} } } }
						}
						 }
					?>
					
					<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
								   <td align="center">
										<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:0px 30px;">
														<tr>
														   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;">A: <?php echo "Registered Seller"; ?></td>
														</tr>
														<<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_registered=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
														<tr>
															<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_registered=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
														</tr>
													
													</table>
												</td>
									
											</tr>
									 
										</table>
								  
									</td>
								</tr>
					
					<?php	
					}	
					/*Registered Seller */
							
                                    foreach ( $all_meta_for_users as $all_meta_for_user ) { $IDD=$all_meta_for_user->ID; ?>
		 <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == $IDD) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                               $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:red;"><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:red;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == $IDD) { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid red;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        $slr_info  = get_userdata($slrID);
                                       ?>
                                       <?php echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:red;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  } } } }  
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   <?php } 
           /* End Print Order List of All Seller */  ?>
		     <?php  
           /* Start Print Order List of Manually Added Seller */  ?>
		    <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if ($ID3 == "custom") { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                               $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } }
						}
						
						
						 $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
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
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												if($slrID=="custom")
												{
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if(!in_array($s_name_lower,$seller_name))
													{
														$seller_name[]=strtolower($s_name);
													}
												}
											 
										 }
									 }
									}
							}
						}
						/* Calculate seller Total*/
							$args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							
						for($i=0;$i<count($seller_name);$i++) 
						{
						$seller_rebate=0;
							$seller_total_sale=0;
							
                        foreach ( $orders as $order ) 
						{
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") 
							{ 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) 
									{
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
										 if ($frID3 != $order_customer_id) 
										 {
												$slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
												
													$s_name=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
													$s_name_lower= strtolower($s_name);
													if($seller_name[$i] == $s_name_lower) 
													{
														  foreach ($order->get_items() as $item_key => $item_values): 
															$item_data = $item_values->get_data();  
															endforeach; 
															$total_product = $order->get_total();   ///$order_data->get_total();


															$percentage  = 40;
															$percentFifty= 50;

															/*$totalAmount = $order->get_subtotal();*/
										
															$tdiscount=$order->get_total_discount();
										
															if(!empty($tdiscount))
															{
															  $newamt=$order->get_subtotal()-$tdiscount;
															  $totalAmount=$newamt;
															}
															else
															{
															 $totalAmount=$order->get_subtotal();
															}													/*   if ( $order_customer_id != $ID  ) { //
														 $totalAmt    = ($percentage / 100) * $totalAmount;
														   $Discount = number_format($totalAmt, 2, '.', '');
													   }
													   else{*/
														 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
														 {
															$Discount    = ($percentFifty / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
														 }
														 else
														 {
															$Discount    = ($percentage / 100) * $totalAmount;
															$Discount = number_format($Discount, 2, '.', '');
															
														 }
													  /* }*/
													   $seller_rebate+=$Discount;
													   $seller_total_sale+=$totalAmount;
														
													}
												
											 
										 }
									} }
							}
						}
						$seller_total_Sale_array[]=$seller_total_sale;
						$seller_rebate_array[]=$seller_rebate;
						}
						
						
						
						
						
						
						?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:blue;">B: Manually Added Seller</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							<?php
								$exist_seller=count($seller_name);
								if($exist_seller==0){
								?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #cdcdcd;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
								}
								?>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
							for($i=0;$i<count($seller_name);$i++)
							{ ?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 18px;margin-top:15px;color:blue;"><?php echo $seller_name[$i]; ?></td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $seller_t=number_format($seller_total_Sale_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:blue;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $seller_r=number_format($seller_rebate_array[$i], 2, '.', ''); ?></span></strong></td>
								</tr>
						
							<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								<?php
						
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if ($ID3 == "custom") { 

                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
									if ( $order->has_status('completed') || $order->has_status('processing')) {
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
							     if ($frID3 != $order_customer_id) { 
								 
								 $s_name_meta=get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
								 if($seller_name[$i] == strtolower($s_name_meta)) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid blue;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
											
										}
										else
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:blue;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{ */
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                             /*  } */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							<?php }  } } } }
                        }
							}
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		    <?php  
           /* End Print Order List of Manually Added Seller */  ?> 
		   
		    <?php  
           /* Start Print Order List of Seller not mentioned */  ?>
		    <tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );

                            if (empty($ID3)) { 
								$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                                   $tdiscount=$order->get_total_discount();
										
									  if(!empty($tdiscount))
									  {
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
									  }
									  else
									  {
										 $total_sale_so_far+=$order->get_subtotal();
									  }
							 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS){
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
								 $rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
								} } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:purple;">C: Seller not mentioned</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:purple;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
                            if (empty($ID3)) { 
								
                                $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 != $order_customer_id) {
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid purple;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if($slrID=="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else if(is_numeric($slrID))
										{
                                         $slr_info  = get_userdata($slrID);
                                         echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
										else
										{
											echo "N/A";
										}  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:purple;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                    $tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
										 $newamt=$order->get_subtotal()-$tdiscount;
										 $totalAmount = $newamt;
									}
									else
									{
                                     $totalAmount = $order->get_subtotal();
                                    }
                        
                               if ( $order_customer_id != $ID  ) { //
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
                                 else
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
                               }
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
								<?php  }  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		    <?php  
           /* End Print Order List of Seller not mentioned */  ?> 
		   	<!-- Fundraiser Online Sales-->
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							  
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing')) {
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
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
                            if ($order_type=='Online') {

                               /*$total_sale_so_far+=$order->get_subtotal();*/
										$tdiscount=$order->get_total_discount();
										
										if(!empty($tdiscount))
										{
										  $newamt=$order->get_subtotal()-$tdiscount;
										  $total_sale_so_far+=$newamt;
										}
										else
										{
										 $total_sale_so_far+=$order->get_subtotal();
										}
										$total_sale_so_far = number_format($total_sale_so_far, 2, '.', '');

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                     
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}            
                             
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
									$rebate_so_far+=$Discount;
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
									 $rebate_so_far+=$Discount;
								 }
                               
							  
						} } } }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#f54298;">D: Fundraiser Online Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#f54298;">Total Rebate So Far: <strong>$<span class="fr-total-rebate"><?php echo $rebate_so_far_seller_not=number_format($rebate_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
							
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Customer</th>
                                <th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Seller</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Product Sales</th>
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
									if ( $order->has_status('completed') || $order->has_status('processing')) {
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
							     if ($order_type=='Online') { 
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #f54298;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$<?php $tdiscount=$order->get_total_discount();if(!empty($tdiscount)) { $sub=$order->get_subtotal()-$tdiscount; echo $decimal = number_format( $sub, 2); } else { $sub=$order->get_subtotal(); echo $decimal = number_format( $sub, 2); }  ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS)
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
									<?php  } } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>
		   
		   
		 <!-- Catalog Sale -->
		
		<tr style="width: 100%;padding: 25px; max-width: 1000px;margin: 0 auto;">
           <td align="center">
				<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="border-color: #e0dede;border-right:2px solid #939598;border-left:2px solid #939598;border-bottom:2px solid #939598;border: 0;width: 96%;margin: 20px;">
					<tr>
						<td align="center">
							<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%" style="padding:30px 30px;">
							  <?php  $total_sale_so_far=0;
							  $rebate_so_far=0;
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							  $ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							   $new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
							$order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
								if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
								  if ($frID3 == $order_customer_id) { 
								  $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
								  if($order_type=="Catlog") 
									 {
										
                                       $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes") 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale_so_far+=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
										

								 }
							
							} }
						}?>
								<tr>
								   <td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 700;text-transform: uppercase;line-height: 1.3;font-size: 24px;color:#a84032;">E: Fundraiser Catalogue Sales</td>
                                </tr>
								<tr>
									<td style="display: inline-block;margin-bottom: 10px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;font-weight: 400;text-transform: uppercase;line-height: 1.3;font-size: 14px;color:#a84032;">Total Sale So Far: <strong>$<span class="fr-total-sale"><?php echo $total_sale_so_far_seller_not=number_format($total_sale_so_far, 2, '.', ''); ?></span></strong></td>
								</tr>
								
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<th class="fr-order-col fr-order-id" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Order</th>
                                <th class="fr-order-col fr-order-date" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Date</th>
                                <th class="fr-order-col fr-order-customer" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Customer</th>
                                <!--<th class="fr-order-col fr-order-seller" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Seller</th>-->
                                <th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">Product Sales</th>
                                <!--<th class="fr-order-col fr-order-total" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#f54298;">Rebate</th>-->
                                <!--<th class="fr-order-col fr-order-action" style="    text-align: left;width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';">Details</th>-->
			
								</tr>
								  <?php 
                            $args = array(
                                'status'   => "published",
                                'meta_key' => 'fundraiser_field',
								'meta_value' => $ID,
								'limit' => -1
                            );
                            $orders = wc_get_orders($args);
                        foreach ( $orders as $order ) {
							
                            $frID3 = get_post_meta($order->get_id(), 'fundraiser_field', true );
							$ID3 = get_post_meta($order->get_id(), 'wc_seller_field', true );
							 $order_type = get_post_meta($order->get_id(), 'wc_order_type', true );
							  $new_catalog_order=get_post_meta( $order->get_id(), 'new_catalog_order_50_percent',true);
							  $order_data = $order->get_data();
                                $order_customer_id = $order_data['customer_id'];
                             if ($frID3 == $order_customer_id)  
							 {

                               
									if ( $order->has_status('completed') || $order->has_status('processing') || $order->has_status('on-hold')) {
							     if ($order_type=='Catlog') { 
								  $order_item_quantity = 0; 
										  foreach ($order->get_items() as $item_key => $item_values): 
                                         $item_id   = $item_values->get_id();
                                         $item_data = $item_values->get_data();
											$order_item_quantity += $item_data['quantity']; 										 
										  endforeach;
										  $qty= $order_item_quantity; 
										if( $qty <= (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) && empty($new_catalog_order)) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (40) / 100; 
											$total_sale=$subtot-$to_widthdraw; 
										}  
										else if($qty > (GLOW_BROCHURE_PROFIT_MIN_ITEMS - 1) || !empty($new_catalog_order) && $new_catalog_order == "Yes" ) 
										{ 
											$subtot=$order->get_subtotal(); 
											$to_widthdraw = floatval ($subtot) * floatval (50) / 100;
											$total_sale=$subtot-$to_widthdraw; 
										} 
										else
										{ 
						
										}
								 
                                  ?>
								<tr style="display: flex;flex-flow: wrap; border-bottom: 1px solid #a84032;transition: all 0.1s ease 0s;">
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order->get_id(); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['date_created']->date('Y-m-d'); ?></td>
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"><?php echo $order_data['billing']['first_name']; ?> <?php echo $order_data['billing']['last_name']; ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;"> <?php 
                                        $slrID     = get_post_meta($order->get_id(), 'wc_seller_field', true );
                                        if(empty($slrID))
										{
											echo "N/A";
										}
										else if($slrID =="custom")
										{
											echo get_post_meta($order->get_id(), 'wc_seller_field_custom', true);
										}
										else
										{
											$slr_info  = get_userdata($slrID);
											echo $slr_info->first_name; ?> <?php echo $slr_info->last_name; 
										}
                                       ?>
                                       </td>-->
									<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$<?php  echo $total_sale=number_format( $total_sale, 2); ?></td>
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;color:#a84032;">$
                                       <?php 

                                       foreach ($order->get_items() as $item_key => $item_values): 
                                                $item_data = $item_values->get_data();  
                                             endforeach; 
                                       ?>
                                <?php          
                            
                              ///////////////////////////////////////////////////////////////////////////////
                                    $total_product = $order->get_total();   ///$order_data->get_total();


                                    $percentage  = 40;
                                    $percentFifty= 50;

                                   /* $totalAmount = $order->get_subtotal();*/
									$tdiscount=$order->get_total_discount();
										
									if(!empty($tdiscount))
									{
											$newamt=$order->get_subtotal()-$tdiscount;
											$totalAmount=$newamt;
									}
									else
									{
											$totalAmount=$order->get_subtotal();
									}
                              /* if ( $order_customer_id != $ID  ) { 
                                 $totalAmt    = ($percentage / 100) * $totalAmount;
                                   $Discount = number_format($totalAmt, 2, '.', '');
                               }
                               else{*/
							   
                                 if (glow_brochure_order_quantity($order) >= GLOW_BROCHURE_PROFIT_MIN_ITEMS || !empty($new_catalog_order) && $new_catalog_order == "Yes")
								 {
                                    $Discount    = ($percentFifty / 100) * $totalAmount;
									$Discount = number_format($Discount, 2, '.', '');
								 }
                                 else
								 {
                                    $Discount    = ($percentage / 100) * $totalAmount;
                                    $Discount = number_format($Discount, 2, '.', '');
								 }
                               /*} */
                               echo $Discount;?> </td>-->
									<!--<td class="fr-order-col fr-order-id" style="width: 12%;padding: 10px 5px;font-size: 16px;font-family: 'Roboto';"><span style="color: #fff;background: #000;padding: 7px 15px;font-weight: 500;font-size: 14px;cursor: pointer;position: relative;">Expand</span></td>-->
								</tr>
							<?php  } } }
                        }
                        ?>
			 
							</table>
						</td>
			
					</tr>
			 
				</table>
          
			</td>
		</tr>   
		   
		   
    </table>
	
    </div>
	<?php }  elseif($_GET['page']=='fundraiser_operations' && $_GET['search']=="true"){ ?>
	<style>
	.fundraiser_summery
	{
		margin: 21px;
		margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		padding: 10px;
	}
	#wrap
	{
		padding: 20px;
	}
	.search-item
	{
		padding-bottom: 7px;
	}
	#fund_search .search-btn
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
	#search_table
	{
		    margin-top: 20px;
	}
	#search_table, #search_table table tbody tr
	{
		display:none;
	}
	.messageBox
	{
		color: red;
		margin-bottom: 8px;
		display:none;
	}
	.messageBox strong
	{
		font-weight:800;
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
	#search_table
	{
		display:block;
	}
	#search_table table tbody tr {
    display: table-row;
}
</style>
<script>
		jQuery(document).ready(function () 
		{
			var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
			jQuery(".search-btn").click(function(e){
				jQuery("#search_table tbody").empty();
				jQuery("#search_table #loading_search").addClass("show_load");
				e.preventDefault();
				var search_fund_id=jQuery("#search_id").val();
				if(search_fund_id!="")
				{
					jQuery(".messageBox").css("display","none");
					jQuery.ajax({

							type : "post",

							url : ajaxurl,

							data : {action: 'wdm_search_fundraisers', search_fund_id:search_fund_id},
			
							success: function(response) {
								console.log(response);
								jQuery("#search_table #loading_search").removeClass("show_load");
								jQuery("#search_table #loading_search").addClass("hide_load");
								if(response!=0)
								{
									jQuery("#search_table tbody").html(response);
									/*jQuery("#search_table").css("display","block");*/
								}
								else
								{
									jQuery("#search_table tbody").html("<p style='margin: 0 auto;padding: 50px;position: absolute;left: 44%;'>No Results!!</p>");
								}
							}
						});
					
				}
				else{
					jQuery(".messageBox").css("display","block");
					jQuery("#search_table #loading_search").removeClass("show_load");
					jQuery("#search_table #loading_search").addClass("hide_load");
				
				}
			});
		});
</script>
<div class="widget widget-table">
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&search=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Search</a>
	</div> <?php */?>
	<div class="widget-header">						
		<h3>Search Fundraiser</h3>
	</div>	
	
	
	<div id="wrap" class="widget-content " style="clear: both;">
	<div class="messageBox"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>
		<form action="#" name="fund_search" id="fund_search" method="get">
			<div class="row">
				<div class="span6">
					<div class="search-item">Enter Fundraiser ID:</div> <input type="text" name="search_id" size="30" value="" id="search_id"><input type="submit" value="Submit" class="btn btn-primary search-btn">
				</div>
				
			</div>
			
		</form>
	</div>
	<div id="" class="manual-mass-order-popup">
        <div class="manual-mass-order-popup-wrapper">
		<span>Status Action</span>
           <input type="radio" id="st_1" class="status_type" name="paid_type" value="1" >Set paid<input type="radio" id="st_2" class="status_type" name="paid_type" value="2">Unpaid
			   <input id="submit_status" type="submit" name="submit" value="Submit" data-id="" data-action="" data-rebate="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
	<div id="" class="manual-mass-order-popup_action">
        <div class="manual-mass-order-popup-wrapper_action">
		<span>Active/Inactive Action</span>
           <input type="radio" id="a_st_1" class="a_status_type" name="action_type" value="1" >Active<input type="radio" id="a_st_2" class="a_status_type" name="action_type" value="2">Inactive
			   <input id="submit_action_status" type="submit" name="submit" value="Submit" data-id="" data-action="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
<div id="search_table xcvzxvczxcz1" class="widget widget-table">	
    <div class="admin-report"></div>
	<div id="" class="widget-content " style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			<th id="" style="vertical-align: top; text-align: center;">Fund ID:</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization:</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<th id="" style="vertical-align: top; text-align: center;">Members:</th>
			<th id="" style="vertical-align: top; text-align: center;">Registered:</th>
			<th id="" style="vertical-align: top; text-align: center;">Period:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Action:</th>
		 </tr>
		</thead>
		<tbody>
		
			<img id="loading_search" src="https://glowscentedcandles.com/wp-content/uploads/2020/08/ajax-loader.gif" class="hide_load">
		
		</tbody>
		</table>
	</div>
	</div>
</div>
	
<?php } else { ?>
	<style>
	.fundraiser_summery
	{
		margin: 21px;
    margin-left: 0;
	}
	.fundraiser_summery .btn-custom
	{
		    padding: 10px;
	}
	/*Search Option */
	#fund_search .search-btn
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
	
	.messageBox
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
	.messageBox strong
	{
		font-weight:800;
	}
	.widget-table .widget-content.search-section
	{
		background: transparent;
		padding: 20px;
	}
	#fund_search
	{
		width: 100%;
		margin: 0 auto;
		text-align: center;
		display: inline-block;
	}
	#fund_search .search-item
	{
		width: 750px;
		margin: 0 auto;
		text-align: left;
		margin-bottom: 9px;
		font-size: 14px;
	}
	.payOwe
	{
	    padding: 5px 15px;
		margin-left: 5px;
	}
	#out_F
	{
		display:none;
		    padding: 10px;
    text-align: center;
    font-size: 20px;
	}
	</style>
	<script>
		jQuery(document).ready(function () 
		{
			jQuery(".search-btn").click(function(e){
				
				
				e.preventDefault();
				var search_id=jQuery("#search_id").val();
				if(search_id!="")
				{
					jQuery(".messageBox").css("display","none");
					jQuery("#search_table table tbody tr").css("display","none");
					jQuery("#search_table table tbody tr#fund_"+search_id).css("display","table-row");
					
				}
				else{
					jQuery(".messageBox").css("display","block");
					jQuery("#search_table table tbody tr").css("display","table-row");
				}
			});
			
			jQuery(".payOwe").click(function(e){
				
				
				e.preventDefault();
				
					
					jQuery("#search_table table tbody tr").css("display","none");
					jQuery("#search_table table tbody tr.payDue").css("display","table-row");
					jQuery("#out_F").css("display","block");
					
				
			});
			var sale = [];
			var rebate = [];
			jQuery("#search_table .payDue").each(function(){
				  var v_rebate=jQuery(this).attr("data-rebate");
				  rebate.push(v_rebate);
				  var v_sale=jQuery(this).attr('data-sale');
				  sale.push(v_sale);
			});
			var sumRebate = 0.00;
			var sumSale=0.00;
			jQuery.each(rebate,function(){sumRebate+=parseFloat(this) || 0;});
			jQuery.each(sale,function(){sumSale+=parseFloat(this) || 0;});
			console.log(sumRebate);
			console.log(sumSale);
			
			
			jQuery("#out_F").html("Total Sale: $"+sumSale.toFixed(2)+" and Total Rebate: $"+sumRebate.toFixed(2));
			
			
			
		});
</script>
<div class="proccessloader">
	<div class="inner">
		<img src="https://glowscentedcandles.com/wp-content/uploads/2020/12/fd420f3a680e39cde8bda39f5f996964.gif"/>
		<p>Successfully Updated!!</p>
		</div>
	</div>
	
	 <div class="proccessloader2">
	<div class="inner">
		<img src="https://glowscentedcandles.com/wp-content/uploads/2020/12/fd420f3a680e39cde8bda39f5f996964.gif"/>
		<p>Email Successfully Sent.</p>
		</div>
	</div>
	<div id="" class="manual-mass-order-popup">
        <div class="manual-mass-order-popup-wrapper">
		<p></p>
		<span>Status Action</span>
           <input type="radio" id="st_1" class="status_type" name="paid_type" value="1" >Set paid<input type="radio" id="st_2" class="status_type" name="paid_type" value="2">Unpaid
			   <input id="submit_status" type="submit" name="submit" value="Submit" data-id="" data-action="" data-rebate="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
	<div id="" class="manual-mass-order-popup_action">
        <div class="manual-mass-order-popup-wrapper_action">
		<p></p>
		<span>Active/Inactive Action</span>
           <input type="radio" id="a_st_1" class="a_status_type" name="action_type" value="1" >Active<input type="radio" id="a_st_2" class="a_status_type" name="action_type" value="2">Inactive
			   <input id="submit_action_status" type="submit" name="submit" value="Submit" data-id="" data-action="">
			 <a href="#" id="m-txt-link" class="txt-link">Cancel</a>
        </div>
    </div>
<div class="widget widget-table">	
    <div class="admin-report"></div>
	<?php /*?><div class="btn-toolbar fundraiser_summery">
		<a href="?page=fundraiser_operations" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Operations</a>
		<a href="?page=fundraiser_operations&fundraiser_report=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Report</a>
		<a href="?page=fundraiser_operations&paid=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Paid Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&fundraiser_summery=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Fundraiser Summery</a>
		<a href="?page=fundraiser_operations&print_order=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print Order</a>
		<a href="?page=fundraiser_operations&request=close" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Close Out</a>
		<a href="?page=fundraiser_operations&closed=closed_fundraisers" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Closed Fundraisers</a>
		<a href="?page=fundraiser_operations&search=true" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Search</a>
	</div>	<?php */?>
	<div id="wrap" class="widget-content search-section" style="clear: both;">
	<div class="messageBox"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>
		<form action="#" name="fund_search" id="fund_search" method="get">
			<div class="row">
				<div class="span6">
					<div class="search-item">Enter Fundraiser ID:</div> <input type="text" name="search_id" size="100" value="" id="search_id"><input type="submit" value="Submit" class="btn btn-primary search-btn">
				</div>
				
			</div>
		</form>
		<!--<div class="row" style="padding-top: 10px;">
				<div class="span6">
				Filter : <input type="button" value="Outstanding Fundraisers" class="btn btn-primary payOwe"/>
				</div>
		</div>-->
	</div>
	<div class="widget-header">						
		<h3>Fundraiser Operations</h3>
	</div>
	
	<div id="search_table" class="widget-content" style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
			<th id="" style="vertical-align: top; text-align: center;">Fund ID:</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization:</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<th id="" style="vertical-align: top; text-align: center;">Members:</th>
			<th id="" style="vertical-align: top; text-align: center;">Registered:</th>
			<th id="" style="vertical-align: top; text-align: center;">Period:</th>
			<th id="" style="vertical-align: top; text-align: center;">Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Representative Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund Status:</th>
			<th id="" style="vertical-align: top; text-align: center;">Action:</th>
		 </tr>
		</thead>
		<tbody>
		<?php 
		 
		
		$args = array(
					'role'    => 'Fundraiser',
					'orderby' => 'user_registered',
					'meta_key'     => 'user_registration_referral_email',
					'meta_value'   => $current_email,
					'order'   => 'DESC'
				);
				$all_meta_for_users = get_users($args);
				foreach ( $all_meta_for_users as $all_meta_for_user ) {
						$count=0;
						$ID=$all_meta_for_user->ID;	
						
						$status= get_user_meta( $ID , 'user_status' , true );
						$repre_status= get_user_meta( $ID , 'user_representative_status' , true );
					    $action_status= get_user_meta( $ID , 'user_action_status' , true );
						$close_out = get_user_meta($ID, "user_close_out_value", true);
						if((empty($status) && empty($action_status)) || ($status!=1 && $action_status!=2) || ($status!=2 && $action_status!=2) || ($status!=1 && $action_status==2 && $close_out==1))
						{
						
								$arr=array();
								$total_sale_so_far=0;
								$rebate_so_far=0;
								

						
						   
							$all_seller = get_users(array(
					'meta_key'     => 'user_registration_seller_fundraiser',
					'meta_value'   => $ID,
				));
				if ( ! empty( $all_seller ) && is_iterable( $all_seller ) ) {
					foreach ( $all_seller as $seller_item ) {
						$arr[] = $seller_item->ID;
					}
				}
						//	$org=get_user_meta( $ID , 'user_registration_input_company' , true );
					        $org = get_user_meta($ID, 'user_registration_name_of_school_business' , true );
				    
							$member=count($arr);
							$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
							$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
							
							$currentDate=date("Y-m-d");
							$currentDate = date('Y-m-d', strtotime($currentDate));
							$startDate = date('Y-m-d', strtotime($sd));
							$endDate = date('Y-m-d', strtotime($ed));
							
							$active_date=get_user_meta($ID, "user_close_out_date_active", true);
							$inactive_date=get_user_meta($ID, "user_close_out_date_inactive", true);
							$rebate= get_user_meta( $ID , 'user_rebate' , true );
							$udata = get_userdata( $ID );

							$registered = $udata->user_registered;
					
					?>
			<tr id="<?php echo "fund_".$ID; ?>" class="<?php echo $startDate; ?> <?php if($rebate_so_far>0){ echo 'payDue'; } ?>" <?php if($rebate_so_far>0){ echo 'data-rebate="'.$rebate_so_far.'"';  echo 'data-sale="'.number_format($total_sale_so_far, 2, '.', '').'"'; } ?>>
				<td id="0_" style="vertical-align: top; text-align: center; "><?php echo $ID;?></td>
				<td id="1_" style="vertical-align: top; text-align: left;width: 180px;"><?php echo $org; ?></td>
				<td id="2_" style="vertical-align: top; text-align: left; "><?php echo $all_meta_for_user->user_firstname; ?> <?php echo $all_meta_for_user->user_lastname; ?></td>
				<td id="4_" style="vertical-align: top; text-align: center; "><?php echo $member; ?></td>
				<td id="9_" style="vertical-align: top; text-align: center; "><?php echo date( "M d, Y", strtotime( $registered )); ?></td>
				<td id="5_" style="vertical-align: top; text-align: center; "><span class="label <?php if (($currentDate >= $startDate) && ($currentDate <= $endDate)){ ?>label-success<?php } else { echo "label-danger"; } ?> "><?php echo $sd; ?> - <?php echo $ed; ?></span></td>
				<td id="6_" class="<?php echo $rebate; ?>" style="vertical-align: top; text-align: center; "><?php if($status==1){?><span id="action_status_<?php echo $ID;?>" class="label label-success">Paid</span><?php }  elseif($status==2){?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php }  else { ?><span id="action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php } ?></td>
                <td id="10_" class="" style="vertical-align: top; text-align: center; "><?php if($repre_status==1){?><span id="repre_action_status_<?php echo $ID;?>" class="label label-success">Paid</span><?php }  elseif($repre_status==2){?><span id="repre_action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php }  else { ?><span id="repre_action_status_<?php echo $ID;?>" class="label label-danger">Unpaid</span><?php } ?></td>
                <td id="8_" class="" style="vertical-align: top; text-align: center; "><?php if($action_status==1){?><span id="action_s" class="label label-success">Active</span><?php }  elseif($action_status==2){?><span id="action_s2" class="label label-danger">Inactive</span><?php }  else { ?><span id="action_st" class="label label-success">Active</span><?php } ?></td>
				<td id="7_" style="vertical-align: top; text-align: center; ">
					<div class="btn-toolbar ramsingh"><div class="first-col" style="margin-bottom:10px;">&nbsp;<a href="https://glowbrandscandles.com/print?fund_id=<?php echo $ID;?>" target="_FUND" class="btn btn-inverse btn-mini padtop btn-custom">Print</a>&nbsp;
<?php
      $letter_path = "/wp-content/uploads/fundraisers/$ID/welcome-letter.docx";
      $letter_full = ABSPATH . "wp-content/uploads/fundraisers/$ID/welcome-letter.docx";
      if (file_exists($letter_full)) {
    ?>
      <a href="<?php echo $letter_path; ?>" class="btn btn-success btn-mini padtop btn-custom" target="_blank">Letter</a>
    <?php } else { ?>
      <a href="<?php echo admin_url("admin-post.php?action=generate_fund_letter&fund_id=$ID"); ?>" class="btn btn-warning btn-mini padtop btn-custom" target="_blank">Generate Letter</a>
<?php } ?>
<a href="#" class="btn btn-inverse btn-mini padtop btn-custom status" data-id="<?php echo $ID;?>" data-action="<?php echo $status;?>" data-rebate="<?php echo $rebate_so_far=number_format($rebate_so_far, 2, '.', ''); ?>">Status</a>&nbsp;<a id="send_report" class="btn btn-inverse btn-mini padtop btn-custom" data-fund_id="<?php echo $ID; ?>">Email Report</a></div><div class="second-col">&nbsp;<a href="?page=fundraiser_operations&rebate=true&fund_id=<?php echo $ID;?>" id="rebate_pay" class="btn btn-inverse btn-mini padtop btn-custom">Rebate</a>&nbsp;<a href="#" class="btn btn-inverse btn-mini padtop btn-custom action_status" data-id="<?php echo $ID;?>" data-action="<?php echo $action_status;?>" data-active="<?php echo $active_date;?>" data-inactive="<?php echo $inactive_date;?>">Action</a></div></div>
				</td>
			</tr>
		
			<?php   }   ?>
<?php   }  ?>
			
		</tbody>
		</table>
		<div id="out_F"></div>
	</div>
</div>

<?php
}