jQuery(document).ready(function(){
	///// 1.	fundraiser ID Form show hide on form
	jQuery("#fundraiser_form").hover(function(){
		jQuery("#fundraiser_id_form").slideDown("fast");
		clearTimeout(debounce);
	});

	jQuery("#fundraiser_form").mouseleave (function() {
		debounce = setTimeout(closeMenu,400);
	});

	var debounce;
	var closeMenu = function(){
		jQuery("#fundraiser_id_form").slideUp("fast");
		clearTimeout(debounce);
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////////	
	///// 2.	fundraiser ID Form submit via ajax
	jQuery("#fundraiser_form_submit").submit(function(e) {
		e.preventDefault();
		

		jQuery("#fundraiser_status").html("<img src='"+ajax_params.theme_url+"/images/loader.gif'>");

		$fundraiser_id = jQuery("#fundraiser_id").val();
		if($fundraiser_id == "")
		{
			jQuery("#fundraiser_status").html("Pleaer provide a Fundraiser ID to continue.");
		}
		else
		{
			var saveData = jQuery.ajax({
					type: 'POST',
					url: ajax_params.ajax_url,
					data: {
						action:'wp_fundraiser_id_validitor',
						fund_id: $fundraiser_id
					},
					dataType: "text",
					success: function(data) {
						
						jQuery("#show_fundraiser").hide();
						if(data == "fail")
						{
							jQuery("#message_container").html("<p>Please Contact Admin, Either you Funraiser ID is not valid or Your Fundraiser is not active.</p>");
						}
						else
						{
							jQuery("#message_container").html("<h3>Welcome<br> "+data+"<h3>");
						}
						jQuery("#welcome_fundraiser").show();
						jQuery("#fundraiser_status").html("");
					}
			});
			saveData.error(function() {
				jQuery("#fundraiser_status").html("Something went wrong");
			});
		}
	});
	
	
///////////////////////////////////////////////////////////////////////////////////////////////////	
	///// 2.	Change/Remove fundraiser ID via ajax
	jQuery("#change_fundraiser_id").click(function(e) {
		e.preventDefault();
		jQuery("#fundraiser_status").html("<img src='"+ajax_params.theme_url+"/images/loader.gif'>");
		
		var saveData = jQuery.ajax({
				type: 'POST',
				url: ajax_params.ajax_url,
				data: {
					action:'wp_unset_fundraiser_id',
				},
				dataType: "text",
				success: function(data) {
					jQuery("#fundraiser_status").html("");
					jQuery("#welcome_fundraiser").hide();
					jQuery("#show_fundraiser").show();
				}
		});
		saveData.error(function() {
			jQuery("#fundraiser_status").html("Something went wrong"); 
		});
	});
});