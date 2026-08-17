<?php
session_start();


//check if session id is set if it is redirect to dashboard
if(isset($_SESSION['id']) and (isset($_SESSION['2fa']) and $_SESSION['2fa'] == "yes")){
	
	header("location:dashboard");
}else{
     

    if(isset($_SESSION['2fa'])){

        if($_SESSION['2fa'] == "pending"){
        header("location:authenticate");
    }

    if($_SESSION['2fa'] == "no"){
        header("location:dashboard");
    }

    }

}


include('connection.php');

?>
<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
	

		<!-- Title -->
		<title>Forgot | Quantum Scalp </title>

		<!-- Favicon -->
		<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon"/>

		<!-- Icons css -->
		<link href="assets/css/icons.css" rel="stylesheet">

		<!--  bootstrap css-->
		<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

		<!--- Style css --->
		<link href="assets/css/style.css" rel="stylesheet">
		<link href="assets/css/style-dark.css" rel="stylesheet">
		<link href="assets/css/style-transparent.css" rel="stylesheet">

		<!---Skinmodes css-->
		<link href="assets/css/skin-modes.css" rel="stylesheet" />

		<!--- Animations css-->
		<link href="assets/css/animate.css" rel="stylesheet">

<!-- Start of LiveChat (www.livechat.com) code -->
<script>
    window.__lc = window.__lc || {};
    window.__lc.license = 19834219;
    window.__lc.integration_name = "manual_onboarding";
    window.__lc.product_name = "livechat";
    ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
</script>
<noscript><a href="https://www.livechat.com/chat-with/19834219/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
<!-- End of LiveChat code -->


	</head>
	<body class="ltr error-page1 bg-primary">

		<!-- Loader -->
		<div id="global-loader">
        <img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
		</div>
		<!-- /Loader -->

			<div class="page" >
				<div class="page-single">
					<div class="container">
						<div class="row">
							<div class="col-xl-5 col-lg-6 col-md-8 col-sm-8 col-xs-10 card-sigin-main py-4 justify-content-center mx-auto">
								<div class="card-sigin">
									 <!-- Demo content-->
									 <div class="main-card-signin d-md-flex">
										 <div class="wd-100p">
											 <div class="mb-3 d-flex"> <a href="index"><img src="assets/img/brand/favicon.png" class="sign-favicon ht-40" alt="logo"></a></div>
												 <div class="main-card-signin d-md-flex bg-white">
													 <div class="wd-100p">

                                                     <?php 
										if(!isset($_GET['token'])){
									?>
														 <div class="main-signin-header">
															 <h2>Forgot Password!</h2>
															 <h4>Please Enter Your Email</h4>
															 <form method="POST">
																 <div class="form-group">
																	 <label>Email</label> <input class="form-control" name="email" placeholder="Enter your email" type="email">
																 </div>
																 <button type="submit" name="forgot" class="btn btn-primary btn-block">Send</button>
															 </form>
														 </div>



										<?php
									}
	
									if(isset($_GET['token'])){
	
								
								?>






													<div class="main-signin-header">
															 <h2>Reset Password!</h2>
															 <h4>Please New Password</h4>
															 <form method="POST">
																 <div class="form-group">
																	 <label>Password</label> <input class="form-control" name="password" placeholder="Enter your Password" type="password">
																 </div>

                                                                 <div class="form-group">
																	 <label>Confirm Password</label> <input class="form-control" name="confirm" placeholder="Enter your Password" type="password">
																 </div>
																 <button type="submit"  name="reset" class="btn btn-primary btn-block">Change Password</button>
															 </form>
														 </div>







<?php  } ?>


														 <div class="main-signup-footer mg-t-20 text-center">
															 <p>Forget it, <a href="index"> Send me back</a> to the sign in screen.</p>
														 </div>
													 </div>
												 </div>
										 </div>
									 </div>
								 </div>
							 </div>
						</div>
					</div>
				 </div>
			</div>
		

		<!-- JQuery min js -->
		<script src="assets/plugins/jquery/jquery.min.js"></script>

		<!-- Bootstrap js -->
		<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
		<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

		<!-- Moment js -->
		<script src="assets/plugins/moment/moment.js"></script>

		<!-- eva-icons js -->
		<script src="assets/js/eva-icons.min.js"></script>


        		<!--Internal  Notify js -->
				<script src="assets/plugins/notify/js/notifIt.js"></script>
		<script src="assets/plugins/notify/js/notifit-custom.js"></script>
		<!--Internal  Perfect-scrollbar js -->
		<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

		<!-- Theme Color js -->
		<script src="assets/js/themecolor.js"></script>

		<!-- custom js -->
		<script src="assets/js/custom.js"></script>

	</body>







    <?php  
	include_once("email-handler.php");
	
if(isset($_POST['forgot'])){

$email = $_POST['email'];

$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (filter_var($email, FILTER_VALIDATE_EMAIL)){

//check if email is registered
$check= mysqli_query($mysqli,"SELECT id FROM users WHERE email='$email' ");

if(mysqli_num_rows($check) > 0 ){

    $token = "QS-pass-".mt_rand(138998, 999998);

    $update = mysqli_query($mysqli,"UPDATE `users` SET `forgot`='$token' WHERE email='$email' ");


	sendForgotPasswordEmail(
		$email,

		'https://quantumscalp.io/account/forgot?email=' .($email) . '&token='.$token
	);
   




?>
    <script>
 
    
    notif({
		msg: "<b>Forgot Password Requested</b> <br/> Token sent successfully",
		width: 250,
		position: "center",
		type: "success",
		fade: true
	});
    </script>

    <?php


}else{

    ?>
    <script>
  

    notif({
		msg: "<b>Email is not Register</b> <br/> This is not a valid email Address!",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});
    </script>

    <?php

}



}else{

    //its zero email does not exit show warning
    
    ?>
      <script>


      
	notif({
		msg: "<b>Not a Valid Email Address</b> <br/> This is not a valid email Address!",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});
      </script>
  
      <?php
    
    
    }
  

}




if(isset($_POST['reset'])){

           $password = $_POST['password']; 
           $confirm = $_POST['confirm']; 
           $email = $_GET['email'];
           
           if($password == $confirm){

                $hashpassword = password_hash($password, PASSWORD_DEFAULT);

                $update = mysqli_query($mysqli,"UPDATE `users` SET `password`='$hashpassword' WHERE email='$email' ");

                if($update){

                    ?>
    <script>
   


    notif({
		msg: "<b>Password reset successful</b> <br/> Password has been reset successfully",
		width: 250,
		position: "center",
		type: "success",
		fade: true
	});



    </script>

    <?php

                }


           }else{

            ?>
    <script>
  
    notif({
		msg: "<b>Password do not match</b> <br/> Password you entered do not match!",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});

    </script>

    <?php


           }

          

}


?>






</html>