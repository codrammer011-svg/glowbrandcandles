
<head>
	<style>
	body { font-family: Arial;    line-height: 30px; color: #333;	}

	#payment-box { padding: 40px;margin: 20px;  border: #E4E4E4 1px solid; display: inline-block; text-align: center; border-radius: 3px;}

	#pay_now { padding: 10px 30px;background: #09f;border: #038fec 1px solid;border-radius: 3px;color: #FFF;width: 100%;cursor: pointer;}

	.txt-title {  margin: 25px 0px 0px 0px;    color: #4e4e4e;	}

	.txt-price {  margin-bottom: 20px;    color: #08926c;    font-size: 1.1em;	}
	</style>
   <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
   <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
	<script>
		$(document).ready(function(){
		    $('input[type="checkbox"]').click(function(){
		        var inputValue = $(this).attr("value");
		        $("." + inputValue).toggle();
		    });


   //  		$(function() {
			//   $("#coupon_question").on("click",function() {
			//     $(".answer").toggle(this.checked);
			//   });
			// });

		});
	</script>
</head>

<?php 
	/* 
	* Template Name: PageWithoutSidebar 
	*/ 

	get_header();
?>

<?php  ?>

<body>
	<!-- <label><input type="checkbox" name="colorCheckbox" value="green"> green</label>
 
    <div class="green box">You have selected <strong>green checkbox</strong> so i am here 
	    <form action="/action_page.php">
		    <div class="form-group">
		      <label for="email">Email:</label>
		      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
		    </div>
		    <div class="form-group">
		      <label for="pwd">Password:</label>
		      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd">
		    </div>
		    <div class="checkbox">
		      <label><input type="checkbox" name="remember"> Remember me</label>
		    </div>
		    <button type="submit" class="btn btn-default">Submit</button>
	    </form>
    </div> -->

<div class="col-md-12"> 
	<label><input type="checkbox" name="colorCheckbox" value="green"> Select to Pay, before register! </label>
	<div class="green box">You have selected <strong>green checkbox</strong> so i am here 
	    <div id="payment-box" class="answer">
	     <img src=""/> <!-- images/camera.jpg -->
	     <h4 class="txt-title" id="coupon_question" > Pay before Register! </h4>
	     <div class="txt-price"> </div>
	     <form action="https://www.sandbox.paypal.com/cgi-bin/webscr"  method="post" target="_top">
	         <input type='hidden' name='business' value='prince@devexhub.in'>  <!--  prince@devexhub.in -->

	         <input type='hidden' name='item_name'     value='Camera'> 
	         <input type='hidden' name='item_number'   value='CAM#N1'> 
	         <input type='hidden' name='amount'        value='10'> 
	         <input type='hidden' name='no_shipping'   value='0.0001'> 
	         <input type='hidden' name='currency_code' value='USD'> 

	         <input type='hidden' name='notify_url' value='http://localhost/paypal-payment-gateway-integration-in-php/notify.php'>
	         <input type='hidden' name='cancel_return' value='http://localhost/paypal-payment-gateway-integration-in-php/cancel.php'>
	         <input type='hidden' name='return' value='http://localhost/paypal-payment-gateway-integration-in-php/return.php'>

	         <input type="hidden" name="cmd" value="_xclick"> 
	         <input type="submit" name="pay_now" id="pay_now" Value="Pay Now">
	     </form>
	    </div>
	</div>
</div>
</body>
 


<?php get_footer(); ?>