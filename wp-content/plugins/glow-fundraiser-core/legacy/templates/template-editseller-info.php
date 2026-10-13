<?php 
/* 
	Template Name: Edit Seller Information
*/
	get_header();
	$current_user = wp_get_current_user();
	if(!empty($_GET['seller_ID']))
	{
		$u_id=$_GET['seller_ID'];
	}
	else
	{
		$u_id = $current_user->ID;
	}
	
	/*  Seller Profile Image and Description */
  //Upload Image in media
  if(!empty( $_POST['action'] ) &&  $_POST['action'] == "upload") {
		if ($_FILES) {
					if (!function_exists('wp_generate_attachment_metadata')){
					require_once(ABSPATH . "wp-admin" . '/includes/image.php');
					require_once(ABSPATH . "wp-admin" . '/includes/file.php');
					require_once(ABSPATH . "wp-admin" . '/includes/media.php');
					}
			   
					foreach ($_FILES as $file => $array) {
						if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
						   // return "upload error : " . $_FILES[$file]['error'];
						}
						$attach_id = media_handle_upload( $file, $u_id );
					}   
				
					if ($attach_id > 0){
					//and if you want to set that image as Post  then use:
					//$link = get_permalink( $attach_id );
					$image_attributes = wp_get_attachment_url( $attach_id );
					if(!empty($image_attributes))
					{
						/* update_user_meta($u_id,'seller_profile_image',$image_attributes); */
						update_user_meta($u_id,'user_registration_image_url_profile',$image_attributes);
					}
					
				}
		}
		/* update_user_meta($u_id,'seller_profile_description',$_POST['description']); */
			update_user_meta($u_id,'description',$_POST['description']);
			update_user_meta( $u_id , 'first_name' , $_POST['first_name'] );
			update_user_meta( $u_id , 'last_name' , $_POST['last_name'] );
			update_user_meta( $u_id , 'user_registration_seller_goal' , $_POST['seller_goal'] );
		?>
		<script>
		jQuery(document).ready( function() {
		jQuery("#preview").addClass("show");
		});
		</script>
		<?php
  }
 
?>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script>
jQuery(document).ready( function() {
	function getExtension(filename) {
  var parts = filename.split('.');
  return parts[parts.length - 1];
}

function isImage(filename) {
  var ext = getExtension(filename);
  switch (ext.toLowerCase()) {
    case 'jpg':
    case 'png':
      //etc
      return true;
  }
  return false;
}
	function failValidation(msg) {
      alert(msg); // just an alert for now but you can spice this up later
      return false;
    }
	
	
    	jQuery(document).on('change', '.btn-file :file', function() {
		var input = jQuery(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
		});

		jQuery('.btn-file :file').on('fileselect', function(event, label) {
		    
		    var input = jQuery(this).parents('.input-group').find(':text'),
		        log = label;
		    
		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
	    
		});
		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
					
					 var file = jQuery('#imgInp');
					 if (!isImage(file.val())) 
					 {
						jQuery('#img-upload').attr('src', "<?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/11/account.png");
						jQuery('#imgInp').val("");
						return failValidation('Only .jpg or .png formats are allowed.');
					}
					else
					{
						jQuery('#img-upload').attr('src', e.target.result);
						
						
					}
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
		}

		jQuery("#imgInp").change(function(){
		    readURL(this);
		}); 	
	});
</script>

<style>
.wrap-q .preview
{
    width: 200px;
    margin: 0 auto;
    position: relative;
    padding-bottom: 15px;
    /*display: inline-block;*/
}
.wrap-q .preview-img {
    border-radius: 100%;
    box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.7);
}
.wrap-q img {
    vertical-align: middle;
    border-style: none;
	width: 200px;
    height: 200px;
}
.wrap-q .browse-button {
      width: 200px;
    height: 200px;
    border-radius: 100%;
    position: absolute;
    top: 0px;
    left: 0;
    background: linear-gradient(180deg, transparent, black);
    opacity: 0;
    transition: 0.3s ease;
}
.wrap-q .browse-button:hover {
    opacity: 1;
}
.wrap-q .preview i {
    color: white;
    font-size: 35px;
    transform: translate(50px,130px);
}
.wrap-q .browse-input {
    width: 200px;
    height: 200px;
    border-radius: 100%;
    transform: translate(-1px,-26px);
    opacity: 0;
	    cursor: Pointer;
}
.wrap-q .Error {
    color: crimson;
    font-size: 13px;
}
.wrap-q
{
	padding:50px 20px ;
}
.wrap-q .form-group {
	width: 100%;
    margin: 0px auto;
    padding: 10px 0px;
}
.wrap-q textarea{
	margin: 0px;
    height: 130px;
    width: 100%;
	
}
.wrap-q label{
	font-size: 16px;
}
.wrap-q .form-group .btn
{
	    border-radius: 30px;
}
.wrap-q  .p-form{
	margin-top: 10px;
    border-radius: 5px;
    border: 1px solid rgb(239, 238, 238);
    background-color: rgb(255, 255, 255);
    -webkit-box-align: center;
    align-items: center;
    -webkit-box-pack: justify;
    justify-content: space-between;
    width: 50%;
    padding: 30px;
    margin: 0 auto;
}
.wrap-q small
{
	    text-align: center;
    width: 100%;
    margin: 0 auto;
    font-size: 92%;
    display: block;
}
.hide{
	display:none;
}
.show
{
	display:block;
}
textarea.error{
	    background: #fff;
    padding-left: 25px !important;
}
.browse-button .error
{
	    position: absolute;
    width: 240px;
    top: 30px;
}
input
{
	width: 100%;
    height: 44px;
	padding: 9px 6px;
}
@media only screen and (max-width: 768px){
.wrap-q .container {
    padding-left: 0;
    padding-right: 0;
}
.wrap-q .p-form
{
	    width: 100%;
    padding: 20px;
}
}
</style>

  <?php do_action( 'et_page_heading' ); ?>   
  <div class="wrap-q">
  <div class="container">
     
       
		<?php if(!empty($u_id)){ ?>
        <form name="upload" action="" method="post" class="p-form" id="new_post" enctype="multipart/form-data">
            <div class="preview text-center">
			<?php   
				$desc=get_user_meta($u_id,'seller_profile_description',true);
				$desc_meta=get_user_meta($u_id,'description',true);
				
				$surl=get_user_meta($u_id,'seller_profile_image',true);
				$url=get_user_meta($u_id,"user_registration_image_url_profile",true);
				
				$f=get_user_meta( $u_id , 'first_name' , true );
				$l=get_user_meta( $u_id , 'last_name' , true );
				
				$seller_goal=get_user_meta( $u_id , 'user_registration_seller_goal' , true );
			?>
                <img class="preview-img" src="<?php if(!empty($url)){ echo $url; }  else if(!empty($surl)){ echo $surl; } else{ ?><?php echo esc_url( glow_dynamic_url_base('uploads') ); ?>2020/11/account.png<?php } ?>" alt="Preview Image" width="200" height="200" id='img-upload'/>
                <div class="browse-button">
                    <i class="fa fa-pencil"></i>
                    <input class="browse-input" type="file" name="UploadedFile" id="imgInp"/>
                </div>
                <span class="Error"></span>
            </div>
			<small><b>Note:</b> Upload image size 475px X 475px </small>
			 <div class="form-group">
                <label>First Name:</label>
                <input id="first_name" name="first_name" required placeholder="Enter seller First Name" value="<?php if(!empty($f)){ echo $f; } ?>"/>
              
            </div>
			 <div class="form-group">
                <label>Last Name:</label>
                <input id="last_name" name="last_name" required placeholder="Enter seller Last Name" / value="<?php if(!empty($l)){ echo $l; } ?>">
              
            </div>
			 <div class="form-group">
                <label>Seller Goal: (Dollar Amount):</label>
                <input id="seller_goal" name="seller_goal" required placeholder="Seller Goal: (Dollar Amount)" value="<?php if(!empty($seller_goal)){ echo $seller_goal; } ?>"/>
                
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea id="description" name="description" required placeholder="Enter seller about"><?php if(!empty($desc)){ echo $desc; } if(!empty($desc_meta)){ echo $desc_meta; } else { } ?></textarea>
                <span class="Error"></span>
            </div>
           
            <div class="form-group">
                <input id="submit_form" class="btn btn-primary btn-block" name="submit" type="submit" value="Submit"/>
				<div id="preview" class="hide">
					<p style='padding-top: 15px;color:green;text-align: center;'>Successfully Updated!!</p>
					<p style="padding-top:0px;color: #fff;text-align: center;margin: 0;display: inline-block;width: 100%;">
					<?php   
				
					$userINFO = get_user_by('id', $u_id);
					$username001=$userINFO->user_login;
			?>
						<a href="<?php echo esc_url( glow_dynamic_url_base('home') ); ?>seller/user/<?php echo $username001; ?>" style="text-decoration:none; background:#000;color: #fff;padding: 10px 30px;border-radius: 4px;">Preview Profile</a>
					</p>
				</div>
            </div>
			<input type="hidden" name="action" value="upload" />
        </form>
		<?php }
		else
		{
		?>
		<p class="h2 text-center">Not Found!!</p>
		<?php } ?>
    </div>
	</div>
<?php
	get_footer();
?>