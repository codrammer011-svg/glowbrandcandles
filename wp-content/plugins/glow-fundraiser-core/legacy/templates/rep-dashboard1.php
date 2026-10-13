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
    
    $fundraisers_list = get_users(array( 
 		'role__in' => array( 'fundraiser' ),
 		'meta_key'     => 'user_registration_referral_email',
 		'meta_value'   => $current_email ,
 		'meta_compare' => '='
 	));
?>
<button type="button" class="btn btn-dark mb-4 lineinbtn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Create A New Fundraisers</button>

			<div class="modal fade popsection" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
			</div>
			<table>
				<tr>
					<th>Fundraisers ID</th>
					<th>Fundraisers Name</th>
					<th>Fundraisers Email</th>					
					<th>Firstname</th>
					<th>Lastname</th>
				</tr>
				<?php foreach($fundraisers_list as $fundraisers) {
					$ID = $fundraisers->data->ID;
	            	$name = $fundraisers->data->user_login;
	            	$email = $fundraisers->data->user_email;
	            	$firstname =  get_user_meta($fundraisers->data->ID,'first_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);
	            	$lastname =  get_user_meta($fundraisers->data->ID,'last_name',true);
				?>
				<tr>
					<td><?php echo $ID;?></td>
					<td><?php echo $name;?></td>
					<td><?php echo $email;?></td>
					<td><?php echo $firstname;?></td>
					<td><?php echo $lastname;?></td>
				</tr>
			<?php } ?>
				
			</table>