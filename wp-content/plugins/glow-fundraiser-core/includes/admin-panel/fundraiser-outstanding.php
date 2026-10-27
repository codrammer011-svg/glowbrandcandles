<?php 
function theme_fundraiser_outstanding_options()
{
?>
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
		
	    padding: 50px 10px;
    text-align: center;
    font-size: 23px;
    font-weight: 500;
    color: #000;
	}
	#search_table table tbody tr
	{
		display:none;
	}
	#search_table table tbody tr.payDue
	{
		display:table-row;
	}
	#loading_fout
	{
		    margin: 10% 45%;
    text-align: center;
	}
	.show_load_fout
	{
		display:block;
	}
	.hide_load_fout
	{
		display:none;
	}
	.search_Fout
	{
		    margin: 0;
    font-size: 20px;
    line-height: 1.1;
    font-weight: 700;
    margin-bottom: 10px;
}
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
			
			var ajaxurl="<?php echo admin_url('admin-ajax.php'); ?>";
	
         jQuery.ajax({

         type : "post",

         url : ajaxurl,

         data : {action: 'fund_outstanding'},

         // Stop the spinner and say so if the report cannot be loaded, instead of loading forever.
         error: function() {
						jQuery("#loading_fout").removeClass("show_load_fout").addClass("hide_load_fout");
						jQuery(".widget.widget-table").append('<p style="margin:16px;color:#b42318;">The report could not be loaded. Please reload the page; if it keeps happening, the report may still be building in the background.</p>');
         },

         success: function(response) {
						
						console.log(response);
							jQuery("#loading_fout").removeClass("show_load_fout");
				          jQuery("#loading_fout").addClass("hide_load_fout");
						if(response!=0){
						
						 jQuery(".widget.widget-table").append(response);
						 
					
						}
						}
				});
			});
			
	</script>

<div class="widget widget-table">	
    
	<div id="wrap" class="widget-content search-section" style="clear: both;">
	<div class="messageBox"><strong>Error!!</strong> Please Enter Fundraiser ID.</div>
		<form action="#" name="fund_search" id="fund_search" method="get">
			<div class="row">
				<div class="span6">
					
					<div class="search-item"><p class="search_Fout">Search Fundraiser</p> Enter Fundraiser ID:</div> <input type="text" name="search_id" size="100" value="" id="search_id"><input type="submit" value="Submit" class="btn btn-primary search-btn">
				</div>
				
			</div>
		</form>
		
	</div>
	<div class="widget-header">						
		<h3>Fundraiser Outstanding</h3>
	</div>
	<div id="loading_fout" class="show_load_fout">
		<img  src="<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2022/05/ajax-loader.gif">
		<p style="    font-size: 17px;margin: 0;">Please wait....</p>
	</div>
</div>
<?php
}
