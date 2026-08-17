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




$userid = $_GET['userid'];
$ref_code = $_GET['ref_code'];

$getreff =  mysqli_query($mysqli,"SELECT * FROM `users` WHERE id='".$userid."' ");

$row= mysqli_fetch_assoc($getreff);





?>
<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		

		<!-- Title -->
		<title>My Referral </title>

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
				<?php include('header.php'); ?>
			</div>

			<!-- main-content -->
			<div class="main-content app-content">

				<!-- container -->
				<div class="main-container container-fluid">

					<!-- breadcrumb -->
					<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
						  <span class="main-content-title mg-b-0 mg-b-lg-1">Referral</span>
						</div>
						<div class="justify-content-center mt-2">
							<button type="button" class="btn btn-primary">
								<i class="fe fe-link me-1"></i> <?php echo $rows['referal_link']; ?>
							</button>
						</div>
					</div>
					<!-- /breadcrumb -->

					<!-- row -->
					<div class="row row-sm">

						<div class="col-sm-12 col-lg-12 col-xl-12">
						
								<div class="card custom-card">
									<div class="card-header">Referrals of
                                <?php  echo $row['firstname']." ".$row['secondname']." ".$row['lastname']; ?></div>
									<div class="card-body">
										<div class="row">


										<?php
															//start the loop for see all users
															$get_users = mysqli_query($mysqli,"SELECT * FROM users WHERE referred='".$row['referal_link']."' ORDER BY id DESC");
																	$i=0;
																while($row= mysqli_fetch_assoc($get_users)){
																	$i++;
																?>



											<div class="col-md-12 col-lg-12 col-xl-6 col-xxl-4">
												<div class="border d-flex p-2 br-5 mb-2">
													<div class="recent-contacts me-3">
														<div class="main-img-user avatar-md">
															<img alt="avatar" class="rounded-circle" src="<?php echo $row['img']; ?>">
														</div>
													</div>
													<div>
														<h6 class="mt-1 mb-1"> <?php echo $row['firstname']." ".$row['secondname']." ".$row['lastname'] ; ?></h6>
														<p class="mb-0 text-muted"><?php echo $row['email']; ?></p>
													</div>
													<div class="my-auto ms-auto">
														<nav class="contact-info d-flex">
															<a href="ref?userid=<?php echo $row['id']; ?>&ref_code=<?php echo $row['referal_link']; ?>" class="contact-icon border tx-inverse rounded-pill" data-bs-toggle="tooltip" title="View Referrals"><i class="fe fe-eye tx-12"></i></a>
															
														</nav>
													</div>
												</div>
											</div>


											<?php } ?>



										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<!-- End Row -->
				</div>
				<!-- Container closed -->
			</div>
			<!-- main-content closed -->


			
			<!-- Footer opened -->
			<div class="main-footer">
				<div class="container-fluid pt-0 ht-100p">
					 Copyright © <?php echo date('Y'); ?> All rights reserved
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

		<!-- Eva-icons js -->
		<script src="assets/js/eva-icons.min.js"></script>

		<!-- P-scroll js -->
		<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="assets/plugins/perfect-scrollbar/p-scroll.js"></script>

		<!-- Sidebar js -->
		<script src="assets/plugins/side-menu/sidemenu.js"></script>

		<!-- Sticky js -->
		<script src="assets/js/sticky.js"></script>

		<!-- Right-sidebar js -->
		<script src="assets/plugins/sidebar/sidebar.js"></script>
		<script src="assets/plugins/sidebar/sidebar-custom.js"></script>

		<!--Internal  Contact js -->
		<script src="assets/js/contact.js"></script>

		<!--Internal  Contact js -->
		<script src="assets/js/contact.js"></script>

		<!-- Theme Color js -->
		<script src="assets/js/themecolor.js"></script>

		<!-- custom js -->
		<script src="assets/js/custom.js"></script>

	</body>
</html>