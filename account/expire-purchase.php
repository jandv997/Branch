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
		<title>Quantum Verse | Quantum Scalp </title>

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
		<link href="assets/css/qs-verse.css" rel="stylesheet">
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

					<div class="qs-verse">
					<?php
					$verseTab = 'expired';
					include('inc/verse-tabs.php');
					?>

						<!-- Row -->
						<div class="qs-verse-grid">


						<?php

                                                                                                                    
$getinvest = mysqli_query($mysqli, "SELECT * FROM investment_old WHERE userid='".$rows['id']."' ORDER BY id DESC");
$i=0;


while($row = mysqli_fetch_assoc($getinvest)){
$i++;



$getp = mysqli_query($mysqli,"SELECT * FROM investment_packages where id ='".$row['investmentid']."'");
$invest = mysqli_fetch_assoc($getp);



?>





							<article class="qs-verse-card">
								<div class="qs-verse-card__icon"><?php echo qs_verse_planet(); ?></div>
								<h3 class="qs-verse-card__name"><?php echo htmlspecialchars($row['name']); ?></h3>
								<div class="qs-verse-owned__date"><?php echo htmlspecialchars($row['date']); ?></div>
								<div class="qs-verse-owned__amount">$<?php echo number_format((float) $row['amount'], 2); ?></div>
								<div class="qs-verse-owned__stats">
									<div class="qs-verse-owned__stat">
										<small>ROI</small>
										<b>$<?php echo htmlspecialchars($row['daily_roi']); ?></b>
									</div>
									<div class="qs-verse-owned__stat">
										<small>Duration</small>
										<b><?php echo htmlspecialchars($row['duration']); ?> days</b>
									</div>
								</div>
							</article>



						<?php } ?>



						</div>


						<?php if(mysqli_num_rows($getinvest) == 0) { ?>
                        	<div class="qs-verse-empty">
								<i class="fas fa-satellite-dish"></i>
								<h5>No Expired Purchase</h5>
								<p>Waiting for you to make purchase ...</p>
							</div>
						<?php } ?>

						<!-- End Row -->
					</div>
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