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
		<title>Quantum VENTURE FUND | Quantum Scalp </title>

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
						  <span class="main-content-title mg-b-0 mg-b-lg-1">LUPA Diversified fund </span>
						</div>
						<div class="justify-content-center mt-2">
							<ol class="breadcrumb">
								<li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Lupa</a></li>
								<li class="breadcrumb-item active" aria-current="page">Lupa Diversified </li>
							</ol>
						</div>
					</div>
					<!-- /breadcrumb -->

					<div class="row row-sm mb-4">


						<div class="col-xl-12 col-md-12">
	
							<img src="img/lupa-ventures.jpeg" />
	
							

						</div>

					</div>

					<h3>&nbsp;</h3>

						<!-- Row -->
						<div class="row row-sm">


							<?php

																																					
								$getinvest = mysqli_query($mysqli, "SELECT * FROM investment_packages WHERE `type`=4 ");
								$i=0;


								while($row = mysqli_fetch_assoc($getinvest)){
								$i++;

							?>



							<div class="col-lg-6">

								<div class="card  card-img-top-1" style="height:700px; overflow:scroll">
									<img class="card-img-top w-100" src="<?php echo $row['img']; ?>" alt="">
									<div class="card-body" >
										<h4 class="card-title mb-3"><?php echo $row['name']; ?></h4>
										<p class="card-text"><?php echo $row['info1']; ?></p>

										<hr style="border: solid black 1px;" />

									
										<div class="row mb-4">

											<div class="col-6">
												<h4 class="card-title mb-3">Invesment Horizon </h4>
												<p class="card-text"><?php echo $row['duration']; ?> MONTHS</p>

											</div>

											<div class="col-6">
												<h4 class="card-title mb-3">Target Returns</h4>
												<p class="card-text"><?php echo $row['percent']; ?>%</p>

											</div>

										</div>

										<div class="row mb-4">

											<div class="col-6">
												<h4 class="card-title mb-3">Slots </h4>
												<p class="card-text"><?php echo $row['slots']; ?></p>

											</div>

											<div class="col-6">
												<h4 class="card-title mb-3">Min. Buy </h4>
												<p class="card-text">$<?php echo $row['min_amount']; ?></p>

											</div>

										</div>

									


										<a href="purchase?id=<?php echo $row['id']; ?>" class="btn btn-primary mb-3 shadow">Make Purchase</a>


										<hr style="border: solid black 1px;" />

										<p class="card-text"><?php echo $row['infohead1']; ?></p>





									</div>
								</div>

							</div>



						<?php } ?>



						</div>
						
						
						

					<br/>
					


	





						
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