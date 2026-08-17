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
		<title> Quantum Group | Sign In </title>

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

		<div class="square-box">
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
			<div></div>
		</div>

		<div class="page" >
			<div class="page-single">
				<div class="container">
					<div class="row">
						<div class="col-xl-5 col-lg-6 col-md-8 col-sm-8 col-xs-10 card-sigin-main mx-auto my-auto py-4 justify-content-center">
							<div class="card-sigin">
								 <!-- Demo content-->
								 <div class="main-card-signin d-md-flex">
									 <div class="wd-100p"><div class="d-flex mb-4"><a href="index"><img src="assets/img/brand/favicon.png" class="sign-favicon ht-40" alt="logo"></a></div>
										 <div class="">
											<div class="main-signup-header">
												<h2>Welcome back!</h2>
												<h6 class="font-weight-semibold mb-4">Please sign in to continue.</h6>
												<div class="panel panel-primary">
												   <div class=" tab-menu-heading mb-2 border-bottom-0">
													   <div class="tabs-menu1">
														   <ul class="nav panel-tabs">
															   <li class="me-2"><a href="#tab5" class="active" data-bs-toggle="tab">Email</a></li>
															  
														   </ul>
													   </div>
												   </div>
												   <div class="panel-body tabs-menu-body border-0 p-3">
													   <div class="tab-content">
														   <div class="tab-pane active" id="tab5">
															   <form method="POST">
																   <div class="form-group">
																	   <label>Email</label> <input class="form-control" required  placeholder="Enter your email" type="email" name="email">
																   </div>
																   <div class="form-group">
																	   <label>Password</label> <input class="form-control" placeholder="Enter your password" name="password"  required type="password">
																   </div><button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
																   <div class="mt-4 d-flex text-center justify-content-center mb-2">
																		
																   </div>
																</form>
														   </div>
														  
													   </div>
												   </div>
											   </div>

												<div class="main-signin-footer text-center mt-3">
													<p><a href="forgot" class="mb-3">Forgot password?</a></p>
													<p>Don't have an account? <a href="register">Create an Account</a></p>
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

		<!-- generate-otp js -->
		<script src="assets/js/generate-otp.js"></script>


				<!--Internal  Notify js -->
				<script src="assets/plugins/notify/js/notifIt.js"></script>
		<script src="assets/plugins/notify/js/notifit-custom.js"></script>

		<!--Internal  Perfect-scrollbar js -->
		<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>

		<!-- Theme Color js -->
		<script src="assets/js/themecolor.js"></script>

		<!-- custom js -->
		<script src="assets/js/custom.js"></script>








		<?php


if(isset($_POST['login'])){


//retrive the inut from user
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$password = mysqli_real_escape_string($mysqli,$_POST['password']);


$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (filter_var($email, FILTER_VALIDATE_EMAIL)){


//check if email exist aready
$check_email = mysqli_query($mysqli,"SELECT id, `password`, userstatus, `status`, `2fa`, `email_otp` FROM users WHERE email='$email'");

$row = mysqli_fetch_assoc($check_email);

//if one it exist proceed to login
if(mysqli_num_rows($check_email) > 0){

    //check if password is correct
    if(password_verify($password, $row['password']) ){

        //the user has verified his account
        if($row['userstatus'] ==1){

            //check if admin has approved the account
            // if($row['status'] ==1){


                //attempt to enforce password reset and 2fa activiattion??
                if($row['email_otp'] =="Quantumgroup"){


                  $otp = mt_rand(111222, 912219);

                  //send otp to email


                  $update = mysqli_query($mysqli,"UPDATE `users` SET `email_otp`='$otp' WHERE email='$email' ");






                  $_SESSION['pendingid']=$row['id']; 
                  //$_SESSION['2fa']='no'; 

                   ?>

                <script>
                location = 'enforce-2fa';
                </script>

                <?php




                }else{



                //check if tow factor authetication is enabled
                if($row['2fa']==1){

                    $_SESSION['id']=$row['id']; 
                    $_SESSION['2fa']='pending'; 

                     ?>

    <script>
    location = 'authenticate';
    </script>

    <?php


                }else{
                //its not enabled
                //redirect to admin 

                   $_SESSION['id']=$row['id']; 
                   $_SESSION['2fa']='no'; 
                ?>

    <script>
    location = 'dashboard';
    </script>

    <?php



                }




              }



            
    //     }else{
    //             //account not yet approved by admin

    //             ?>
    // <script>
   

	// notif({
	// 	msg: "<b>Pending Approval Account</b> <br/> Your uploaded ID-Card is pending approval by Admin",
	// 	width: 250,
	// 	position: "center",
	// 	type: "error",
	// 	fade: true
	// });
    // </script>

    // <?php  





    //         }





        }else{

      //acc not yet verified by user    
?>
    <script>
    
	notif({
		msg: "<b>Unverified Account</b> <br/> You have not yet verified your account, please check your email for verification link",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});
    </script>

    <?php  




        }





    }else{


?>
    <script>
   


	notif({
		msg: "<b>Incorrect Password</b> <br/> The password supplied is wrong",
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
		msg: "<b>Unregistered Email</b> <br/> This email is not yet registered with an account!",
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
		msg: "<b>Not a Valid Email Address</b> <br/> This is not a valid email Address",
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



	</body>
</html>