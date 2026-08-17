<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if(!isset($_SESSION['id'])){
	
	header("location:index");
}else{

$get_user = mysqli_query($mysqli,"SELECT * FROM users WHERE id='".$_SESSION['id']."' ");
$rows = mysqli_fetch_assoc($get_user);
    if(isset($_SESSION['2fa'])){

        if( ($_SESSION['2fa'] =="no" or $_SESSION['2fa'] =="pending") and $rows['2fa']==1){
            header("location:index");
        }


    }


}




?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	

	<!-- Title -->
	<title> 2FA | Quantum Scalp </title>

	<!-- Favicon -->
	<link rel="icon" href="assets/img/brand/favicon.png" type="image/x-icon" />

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

</head>

<body class="ltr main-body app sidebar-mini">

	<!-- Loader -->
	<div id="global-loader">
	<img src="img/favicon.png" width="50" class="loader-img" alt="Loader">
	</div>
	<!-- /Loader -->

	<!-- Page -->
	<div class="page">

		<div>
			<!-- main-header -->
			<?php include('header.php'); ?>
		</div>

		<!-- main-content -->
		<div class="main-content app-content">

			<!-- container -->
			<div class="main-container container-fluid" style="background:url(../assets/img/bg/uo_bg.png)">

				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="left-content">
						<span class="main-content-title mg-b-0 mg-b-lg-1"> 2FA </span>
					</div>
					<div class="justify-content-center mt-2">
						<ol class="breadcrumb">
							<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Account</a></li>
							<li class="breadcrumb-item active" aria-current="page">Profile</li>
						</ol>
					</div>
				</div>
				<!-- /breadcrumb -->

				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="card custom-card">
							<div class="card-body d-md-flex">
									

								<div class="">
									<span class="profile-image pos-relative" id="content">
										<img class="br-5" alt="" src="<?php echo $rows['img']; ?>">
										<span class="bg-success text-white wd-1 ht-1 rounded-pill profile-online"></span>
									</span>

									
									
								</div>
							


								<div class="my-md-auto mt-4 prof-details">
									<h4 class="font-weight-semibold ms-md-4 ms-0 mb-1 pb-0"><?php echo $rows['firstname']." ".$rows['lastname']; ?></h4>
									<p class="tx-13 text-muted ms-md-4 ms-0 mb-2 pb-2 ">
										
										<span class="me-3"><i class="fa fa-taxi me-2"></i><?php echo $rows['address']; ?></span>
										<span><i class="far fa-flag me-2"></i></span>
									</p>
									<p class="text-muted ms-md-4 ms-0 mb-2"><span><i
												class="fa fa-phone me-2"></i></span><span
											class="font-weight-semibold me-2">Phone:</span><span><?php echo $rows['phone']; ?></span>
									</p>
									<p class="text-muted ms-md-4 ms-0 mb-2"><span><i
												class="fa fa-envelope me-2"></i></span><span
											class="font-weight-semibold me-2">Email:</span><span><?php echo $rows['email']; ?></span>
									</p>
									
								</div>
							</div>
							<div class="card-footer py-0">
								<div class="profile-tab tab-menu-heading border-bottom-0">
									<nav class="nav main-nav-line p-0 tabs-menu profile-nav-line border-0 br-5 mb-0	">
										
										<a class="nav-link mb-2 mt-2 active" data-bs-toggle="tab" href="#edit">Edit Profile</a>
										
									</nav>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Row -->
				<div class="row row-sm">
					<div class="col-lg-12 col-md-12">
						<div class="custom-card main-content-body-profile">
							<div class="tab-content">
							
							<div class="main-content-body tab-pane border-top-0 active" id="edit">
									<div class="card">
										<div class="card-body border-0">
											<div class="mb-4 main-content-label">Google Auth </div>
											<div class="mb-4 main-content-label">Account Authorization</div>



											<?php

require_once("google_authenticator/index.php"); 

$g = new \Google\Authenticator\GoogleAuthenticator();

$secret = $rows['2fa_key'];


$two_fa_link = $g->getURL($rows['email'], 'quantumscalp.io', $secret);

if($rows['2fa']==0){

?>


	<center>
		<h6>TWO FACTOR AUTHENTICATOR</h6>
		<img class=" mt-2" style="width:30%"
			src="<?php echo $two_fa_link; ?>" alt="">
	</center>


<p>Before enabling two factor authentication ensure you download and install
GOOGLE AUTHENTICATOR App. Then Use it to scan the QR-Code Above</p>

<p>You need to scan the scan the Qrcode to enable you make withdrawals</p>

<div class="form-group mb-2 ">
<label for="key" class="center-align">AUTHENTICATOR KEY</label>
<input id="key" value="<?php echo $rows['2fa_key']; ?>" type="text" name="key"
	class="form-control" disabled>

</div>




<form method="POST">

<div class="form-group mb-3">
	<label>Enable Two Factor Authentication on login</label>
	<br>
	<label class="fancy-checkbox">
		<input type="checkbox" name="2fa"
			data-parsley-errors-container="#error-checkbox"
			data-parsley-multiple="checkbox"
			<?php if($rows['2fa'] == 1){ echo 'checked ';  }  ?>>
		<span><?php if($rows['2fa'] == 1){ echo 'Disable ';  }else{ echo 'Enable'; }  ?></span>
	</label>

</div>

<button type="submit" name="enable-two"  class="btn btn-primary">Update</button>


</form>



<?php } ?>


										
										</div>
									</div>
								</div>
								
								
								
							</div>
						</div>
					</div>
				</div>
				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->

		
		
		<!-- Footer opened -->
		<div class="main-footer">
			<div class="container-fluid pt-0 ht-100p">
				Copyright © <?php echo date('Y'); ?> All rights
				reserved
			</div>
		</div>
		<!-- Footer closed -->

	</div>
	<!-- End Page -->

	<!-- Back-to-top -->
	<a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>

	<!-- JQuery min js -->
	<script src="assets/plugins/jquery/jquery.min.js"></script>

	<!-- Bootstrap js -->
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

	<!-- Moment js -->
	<script src="assets/plugins/moment/moment.js"></script>

	<!-- P-scroll js -->
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>

	<!-- Internal Select2 js-->
	<script src="assets/plugins/select2/js/select2.min.js"></script>
	<script src="assets/js/select2.js"></script>

	<!-- Sidebar js -->
	<script src="assets/plugins/side-menu/sidemenu.js"></script>

	<!-- Sticky js -->
	<script src="assets/js/sticky.js"></script>

	<!-- smart photo master js -->
	<script src="assets/plugins/SmartPhoto-master/smartphoto.js"></script>
	<script src="assets/js/gallery.js"></script>

	<!-- Right-sidebar js -->
	<script src="assets/plugins/sidebar/sidebar.js"></script>
	<script src="assets/plugins/sidebar/sidebar-custom.js"></script>

	<!-- eva-icons js -->
	<script src="assets/js/eva-icons.min.js"></script>


		<!--Internal  Notify js -->
		<script src="assets/plugins/notify/js/notifIt.js"></script>
		<script src="assets/plugins/notify/js/notifit-custom.js"></script>


	<!-- Theme Color js -->
	<script src="assets/js/themecolor.js"></script>

	<!-- custom js -->
	<script src="assets/js/custom.js"></script>




   





    <?php
//code to update two factor authenication
if(isset($_POST['enable-two'])){

    if(isset($_POST['2fa'])){
        $fa = 1;
    }else{
        $fa = 0;
    }

//run update

    $up = mysqli_query($mysqli,"UPDATE users SET 2fa='$fa' WHERE email='".$rows['email']."' ");

    if($up){


    ?>
    <script>
 

	
		notif({
			msg: "<b>Two Factor Authentication Updated</b><br/> Your Login Method has been updated.",
			width: 250,
			position: "center",
			type: "warning"
		});



		setTimeout(() => {
			location = 'logout';
		}, 2000);

    </script>

    <?php


    }





}
//end of 2factor update

?>






</body>

</html>