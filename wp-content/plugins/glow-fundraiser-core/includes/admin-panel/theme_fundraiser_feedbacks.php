<?php 
function theme_fundraiser_feedbacks()
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
.pagaination_section {
    position: relative;
    width: 100%;
    margin: 0 auto;
    text-align: center;
    padding: 40px 0px;
    display: inline-block;
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
</style>
<div class="widget widget-table">	
	<div class="widget-header">						
		<h3 class="card-title">Fundraiser Feedbacks</h3>
	</div>
	<div id="search_table" class="widget-content" style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
		   
			<th id="" style="vertical-align: top; text-align: center;">Fund #ID</th>
			<th id="" style="vertical-align: top; text-align: left;">How do you feel about <br>your visit on our site today?</th>
			<th id="" style="vertical-align: top; text-align: left;">How kind of feedback <br>would you like to give?</th>
			<th id="" style="vertical-align: top; text-align: center;">Would you like to <br>share something with us?</th>
			<th id="" style="vertical-align: top; text-align: center;">Would you like us <br>to contact you by email?</th>
			<th id="" style="vertical-align: top; text-align: center;">How likely are you to recommend <br>us to your friends and colleagues?</th>
			<th id="" style="vertical-align: top; text-align: center;"><i class="fa fa-calendar" aria-hidden="true"></i> Feedback <br> Date</th>
		 </tr>
		</thead>
		<tbody>
	<?php 
		
		$paged = isset($_GET['paged']) ? $_GET['paged'] : 1;
		 $cno=1;
		 $args = array(
					'post_type' => 'feedback',
					'post_status' => "draft",
					'order' => 'DESC',
					'posts_per_page'  => 10,
					'paged' => $paged 
				);
				
			
			/*	$my_posts = get_posts( $args ); */
			 $the_query = new WP_Query($args);
   $max_num_pages = $the_query->max_num_pages;
   
  if ( $the_query->have_posts() ) {
   while ($the_query -> have_posts()) : $the_query -> the_post(); 
			/*	if( ! empty( $my_posts ) ){
					
					 foreach ( $my_posts as $p ){  */
	
					$ID=get_the_ID();	
					$userId=get_post_meta($ID,'feedback_user_id',true);
					$feel=get_post_meta($ID,'feedback_feel',true);
					if($feel==1)
					{
						$n="&#128522;";
					}
					else if($feel==2)
					{
						$n="&#128525;";
					}
					else if($feel==3)
					{
						$n="&#128528;";
					}
					else if($feel==4)
					{
						$n="&#128549;";
					}
					else
					{
						
					}
					$type=get_post_meta($ID,'feedback_type',true);
					if($type=="Bad")
					{
						$t="Bad";
					}
					else if($type=="Good")
					{
						$t="Good";
					}
					else if($type=="Very Good")
					{
						$t="Very Good";
					}
					else if($type=="Excellent")
					{
						$t="Excellent";
					}
					else
					{
						
					}
					$share=get_post_meta($ID,'feedback_share',true);
					$email=get_post_meta($ID,'feedback_email',true);
					$friends=get_post_meta($ID,'feedback_friends',true);
				
				   $post_date = get_the_date( 'l F j, Y' );
				   $post_time = get_the_time();
					
					?>
				<tr id="<?php echo "fund_".$ID; ?>" class="<?php if($cno % 2 == 0){ echo "even "; } else{ echo "odd "; }  ?>">
				  
					<td id="1_" style="vertical-align: top; text-align: center;"><?php echo "#".$userId; ?></td>
					<td id="2_" style="vertical-align: top; text-align: left;width:220px;"><?php if(!empty($feel)){ echo $feel; } else{ echo ""; } ?></td>
					<td id="3_" style="vertical-align: top; text-align: left;width:180px;"><?php if(!empty($type)){ echo $type; } else{ echo ""; } ?></td>
					<td id="4_" style="vertical-align: top; text-align: center;"><?php if(!empty($share)){ echo $share; } else{ echo ""; } ?></td>
					<td id="5_" style="vertical-align: top; text-align: center;"><?php if(!empty($email)){ echo $email; } else{ echo ""; } ?></td>
					<td id="6_" style="vertical-align: top; text-align: center;"><?php if(!empty($friends)) { echo $friends; } else{ echo ""; } ?></td>
					<td id="7_" style="vertical-align: top; text-align: center;"><?php echo $post_date." ".$post_time; ?></td>
				</tr>
				<?php  
				$cno++;  
			endwhile;
					
			/*	} */ 				
				?>
					
		</tbody>
		
		</table>
	<?php
		// grab the current query parameters
$query_string = $_SERVER['QUERY_STRING'];

// The $base variable stores the complete URL to our page, including the current page arg

// if in the admin, your base should be the admin URL + your page
$base = admin_url('admin.php') . '?' . remove_query_arg('paged', $query_string) . '%_%';

// if on the front end, your base is the current page
//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';
?>
<div class="pagaination_section">
<?php 

echo paginate_links( array(
    'base' => $base, // the base URL, including query arg
    'format' => '&paged=%#%', // this defines the query parameter that will be used, in this case "p"
	'prev_next' => true,
    'prev_text' => __('&laquo; Previous'), // text for previous page
    'next_text' => __('Next &raquo;'), // text for next page
    'current' => $paged,
    'total' =>  $the_query->max_num_pages,
	'end_size' => 1,
    'mid_size' => 10
    
));

		?>
		</div>
		
  <?php  }   ?>
	</div>
<?php wp_reset_postdata(); ?>
<?php
}
?>