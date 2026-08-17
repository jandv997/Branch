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
		<title>Expired Purchase | Quantum Scalp </title>

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
<style>
    /* empty / loading state */
		.empty-state {
			text-align: center;
			padding: 3rem 1rem;
			background: rgba(12, 20, 28, 0.6);
			border-radius: 2rem;
			color: #9ca3af;
			backdrop-filter: blur(4px);
		}

		.empty-state i {
			font-size: 3rem;
			margin-bottom: 1rem;
			color: #4ade80;
			opacity: 0.6;
		}

</style>
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
						  <span class="main-content-title mg-b-0 mg-b-lg-1">Expired Purchase </span>
						</div>
						<div class="justify-content-center mt-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Quantum</a></li>
								<li class="breadcrumb-item active" aria-current="page">Expire Purchase  </li>
							</ol>
						</div>
					</div>
					<!-- /breadcrumb -->

						<!-- Row -->
						<div class="row row-sm">


						<?php

                                                                                                                    
$getinvest = mysqli_query($mysqli, "SELECT * FROM investment_old WHERE userid='".$rows['id']."' ORDER BY id DESC");
$i=0;


while($row = mysqli_fetch_assoc($getinvest)){
$i++;



$getp = mysqli_query($mysqli,"SELECT * FROM investment_packages where id ='".$row['investmentid']."'");
$invest = mysqli_fetch_assoc($getp);



?>





							<div class="col-lg-4">


							<div class="card">
								<div class="card-body">
									<div class="plan-card text-center">
										<i class="fe fe-eye plan-icon text-primary"></i>
										<h6 class="text-drak text-uppercase mt-2"><?php echo $row['name']; ?></h6>
										<h2 class="mb-2">$<?php echo $row['amount']; ?></h2>
										<span class="badge badge-danger">   </span>
										<span class="text-muted"><?php echo $row['date']; ?></span>
									</div>
								</div>
							</div>


							<div class="row">


							<div class="col-6 ">
								<div class=" card">
									<div class="card-body">
										<div class="row">
											<div class="col">
												<div class=""> ROI</div>
												<div class="h3 mt-2 mb-2"><b>$<?php echo $row['daily_roi']; ?></b><span class="text-success tx-13 ms-2">(*)</span></div>
											</div>
											
										</div>
										
									</div>
								</div>
							</div>



							<div class="col-6 ">
								<div class="  card">
									<div class="card-body">
										<div class="row">
											<div class="col">
												<div class=""> Duration</div>
												<div class="h3 mt-2 mb-2"><b><?php echo $row['duration']; ?></b><span class="text-success tx-13 ms-2">(days)</span></div>
											</div>
											
										</div>
										
									</div>
								</div>
							</div>







							</div>




						</div>



						<?php } ?>



						</div>


						<?php if(mysqli_num_rows($getinvest) == 0) { ?>
                        	<div class="empty-state">
								<i class="fas fa-satellite-dish"></i>
								<h5>No Expired Purchase</h5>
								<p>Waiting for you to make purchase ...</p>
							</div>
						<?php } ?>

						<!-- End Row -->
				</div>
				<!-- Container closed -->
			</div>
			<!-- main-content closed -->

			
			<!-- Footer opened -->
			<div class="main-footer">
				<div class="container-fluid pt-0 ht-100p">
					Copyright © <?php echo date('Y'); ?>  All rights reserved
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

		<!-- Internal Data tables -->
		<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
		<script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
		<script src="assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
		<script src="assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
		<script src="assets/plugins/datatable/js/jszip.min.js"></script>
		<script src="assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
		<script src="assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
		<script src="assets/plugins/datatable/js/buttons.html5.min.js"></script>
		<script src="assets/plugins/datatable/js/buttons.print.min.js"></script>
		<script src="assets/plugins/datatable/js/buttons.colVis.min.js"></script>
		<script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
		<script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
		<script src="assets/js/table-data.js"></script>

		<!-- INTERNAL Select2 js -->
		<script src="assets/plugins/select2/js/select2.full.min.js"></script>

		<!-- Sidebar js -->
		<script src="assets/plugins/side-menu/sidemenu.js"></script>

		<!-- Sticky js -->
		<script src="assets/js/sticky.js"></script>

		<!-- Right-sidebar js -->
		<script src="assets/plugins/sidebar/sidebar.js"></script>
		<script src="assets/plugins/sidebar/sidebar-custom.js"></script>

		<!-- eva-icons js -->
		<script src="assets/js/eva-icons.min.js"></script>

		<!-- Theme Color js -->
		<script src="assets/js/themecolor.js"></script>

		<!-- custom js -->
		<script src="assets/js/custom.js"></script>

	</body>
</html>