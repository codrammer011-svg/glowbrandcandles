<?php 
function theme_fundraiser_booking_track()
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
<div class="widget widget-table">	
	<div class="widget-header">						
		<h3 class="card-title">Booking List</h3>
	</div>
	<div id="search_table" class="widget-content " style="clear: both;">
		<table class="table table-bordered table-striped table-highlight responsive">
		<thead>
		  <tr>
				<th id="" style="vertical-align: top; text-align: center;">Picture</th>
			<th id="" style="vertical-align: top; text-align: center;">Fund ID</th>
			<th id="" style="vertical-align: top; text-align: left;">Organization</th>
			<th id="" style="vertical-align: top; text-align: left;">Director:</th>
			<th id="" style="vertical-align: top; text-align: left;">Members:</th>
			<th id="" style="vertical-align: top; text-align: center;">Registered:</th>
			<th id="" style="vertical-align: top; text-align: center;">Period:</th>
			<th id="" style="vertical-align: top; text-align: center;">Action</th>
			
		 </tr>
		</thead>
		<tbody>
	<?php 
		$count_args = array(
					'role'    => 'Fundraiser',
					'orderby' =>  'user_registered',
					'order'   => 'DESC',
					'exclude' => array( 122,205974,205874,205821,205893,205983,205987,205931,205989,205995,205998,205999,206024,206044,206046,
					206050, 206054, 206055, 206056, 206058, 206059, 206060, 206061, 206062, 206063, 206065, 206070, 206071, 206072, 206073, 206074, 
					206077, 206081, 206082, 206085, 206086, 206098, 206102, 206103, 206105, 206112, 206120, 206129, 206132, 206137, 206141, 206154,
					206156, 206157, 206160, 206161, 206163, 206164, 206205, 206228, 206230, 206231, 206232, 206234, 206235, 206237, 206238, 206239,
					206241, 206242, 206243, 206246, 206247, 206248, 206249, 206250, 206251, 206253, 206254, 206255, 206256, 206257, 206258, 206260,
					206261, 206262, 206263, 206264, 206265, 206266, 206272, 206275, 206277, 206280, 206281, 206283, 206284, 206285, 206289, 206292, 
					206300, 206301, 206312, 206313, 206316, 206320, 206322, 206335, 206340, 206341, 206344, 206353, 206358, 206366, 206376, 206381,
					206383, 206384, 206385, 206387, 206390, 206395, 206396, 206397, 206399, 206404, 206410, 206411, 206419, 206421, 206422,
					206423, 206426, 206428, 206429, 206431, 206432, 206433, 206435, 206437, 206438, 206440, 206441, 206443, 206461,
					206463, 206464, 206465, 206466, 206467, 206469, 206475, 206476, 206477, 206478, 206480, 206482, 206483, 206484, 206485, 206486,
					206487, 206488, 206489, 206490, 206491, 206492, 206494, 206495, 206498, 206516, 206519, 206521, 206529, 206530, 206533, 206580)
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
		$cno=1;
			$args = array(
					'role'    => 'Fundraiser',
					'orderby' => 'user_registered',
					'order'   => 'DESC',
					'number'    => $users_per_page,
					'offset'    => $offset, 
					'exclude' => array( 122,205974,205874,205821,205893,205983,205987,205931,205989,205995,205998,205999,206024,206044,206046,
					206050, 206054, 206055, 206056, 206058, 206059, 206060, 206061, 206062, 206063, 206065, 206070, 206071, 206072, 206073, 206074, 
					206077, 206081, 206082, 206085, 206086, 206098, 206102, 206103, 206105, 206112, 206120, 206129, 206132, 206137, 206141, 206154,
					206156, 206157, 206160, 206161, 206163, 206164, 206205, 206228, 206230, 206231, 206232, 206234, 206235, 206237, 206238, 206239,
					206241, 206242, 206243, 206246, 206247, 206248, 206249, 206250, 206251, 206253, 206254, 206255, 206256, 206257, 206258, 206260,
					206261, 206262, 206263, 206264, 206265, 206266, 206272, 206275, 206277, 206280, 206281, 206283, 206284, 206285, 206289, 206292, 
					206300, 206301, 206312, 206313, 206316, 206320, 206322, 206335, 206340, 206341, 206344, 206353, 206358, 206366, 206376, 206381,
					206383, 206384, 206385, 206387, 206390, 206395, 206396, 206397, 206399, 206404, 206410, 206411, 206419, 206421, 206422,
					206423, 206426, 206428, 206429, 206431, 206432, 206433, 206435, 206437, 206438, 206440, 206441, 206443, 206461,
					206463, 206464, 206465, 206466, 206467, 206469, 206475, 206476, 206477, 206478, 206480, 206482, 206483, 206484, 206485, 206486,
					206487, 206488, 206489, 206490, 206491, 206492, 206494, 206495, 206498, 206516, 206519, 206521, 206529, 206530, 206533, 206580)
				);
				$all_meta_for_users = get_users($args);
				foreach ( $all_meta_for_users as $all_meta_for_user ) 
				{
					$ID=$all_meta_for_user->ID;	
				// 	$org=get_user_meta( $ID , 'user_registration_input_company' , true );
				
				$org=get_user_meta( $ID , 'user_registration_name_of_school_business' , true );
					$arr=array();
					date_default_timezone_set('America/Chicago');
					$currentDate=date("Y-m-d");
					$sd=get_user_meta( $ID , 'user_registration_start_date' , true );
					$ed=get_user_meta( $ID , 'user_registration_end_date' , true );
					$user_registration_image_url_profile = get_user_meta( $ID , 'user_registration_image_url_profile' , true );
					$currentDate = date('Y-m-d', strtotime($currentDate));
					$startDate = date('Y-m-d', strtotime($sd));
					$endDate = date('Y-m-d', strtotime($ed));
					$all_seller = get_users(array(
						'meta_key'     => 'user_registration_seller_fundraiser',
						'meta_value'   => $ID,
					));
					if ( ! empty( $all_seller ) && is_iterable( $all_seller ) ) {
						foreach ( $all_seller as $seller_item ) {
							$arr[] = $seller_item->ID;
						}
					}
					$member = count($arr);
					$udata = get_userdata( $ID );
					$registered = $udata ? $udata->user_registered : '';
					?>
				<tr id="<?php echo "fund_".$ID; ?>" class="<?php if($cno % 2 == 0){ echo "even "; } else{ echo "odd "; }  ?>">
			        <td id="11_" style="vertical-align: top; text-align: center;"><img style="height: auto;width: 42px;" src="<?php if(!empty($user_registration_image_url_profile)){ echo $user_registration_image_url_profile; } else{ echo ("" . glow_dynamic_url_base('uploads') . "2021/11/3135715.png"); } ?>" width="120" height="120" class="CToWUd"></td>
					<td id="1_" style="vertical-align: top; text-align: center;"><?php echo "#".$ID;?></td>
					<td id="2_" style="vertical-align: top; text-align: left;width:220px;"><?php echo $org; ?></td>
					<td id="3_" style="vertical-align: top; text-align: left;width:180px;"><?php echo !empty($all_meta_for_user->user_firstname) ? $all_meta_for_user->user_firstname : ''; ?> <?php echo !empty($all_meta_for_user->user_lastname) ? $all_meta_for_user->user_lastname : ''; ?></td>
					<td id="9_" style="vertical-align: top; text-align: left;width:100px;"><?php echo $member; ?></td>
					<td id="10_" style="vertical-align: top; text-align: center;"><?php echo !empty($registered) ? date( "M d, Y", strtotime( $registered )) : '-'; ?></td>
					<td id="4_" style="vertical-align: top; text-align: center; "><span class="label <?php if (($currentDate >= $startDate) && ($currentDate <= $endDate)){ ?>label-success<?php } else { echo "label-danger"; } ?> "><?php echo !empty($sd) ? date( "M d, Y", strtotime( $sd )) : ''; ?> - <?php echo !empty($ed) ? date( "M d, Y", strtotime( $ed )) : ''; ?></span></td>
					<td id="5_" style="vertical-align: top; text-align: center;"><a target="_blank" title="View fundraiser detail" href="<?php echo esc_url( home_url( '/fundraiser-detail/?id=' . (int) $ID ) ); ?>" class="action-icon" style="text-decoration:none;"><span class="dashicons dashicons-edit" style="color:#2563eb;font-size:22px;width:22px;height:22px;"></span></a></td>
					
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
<?php
}
?>