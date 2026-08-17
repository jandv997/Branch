<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if(!isset($_SESSION['adminid'])){
    
    header("location:index");
    
}


$get_admin = mysqli_query($mysqli,"SELECT * FROM admins WHERE id='".$_SESSION['adminid']."' ");
$rows = mysqli_fetch_assoc($get_admin);





?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">


	

	<!-- Favicon -->
	<link rel="icon" href="img/icon.png" type="image/x-icon"/>

	<!-- Title -->
	<title>Transactions</title>

	<!-- Bootstrap css-->
	<link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

	<!-- Icons css-->
	<link href="assets/web-fonts/icons.css" rel="stylesheet"/>
	<link href="assets/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
	<link href="assets/web-fonts/plugin.css" rel="stylesheet"/>

	<!-- Style css-->
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/plugins.css" rel="stylesheet">

	<link rel="stylesheet" href="swal/sweetalert2.min.css">

</head>

<body class="main-body leftmenu ltr light-theme dark-menu">

	<!-- Loader -->
	<div id="global-loader">
		<img src="assets/img/loader.svg" class="loader-img" alt="Loader">
	</div>
	<!-- End Loader -->


	<!-- Page -->
	<div class="page">

	
		<?php include('header.php'); ?>

		<!-- Main Content-->
		<div class="main-content side-content pt-0">
			<div class="main-container container-fluid">
				<div class="inner-body">

					<!-- Page Header -->
					<div class="page-header">
						<div>
							<h2 class="main-content-title tx-24 mg-b-5">Transactions</h2>
							<ol class="breadcrumb">
							
								<li class="breadcrumb-item active" aria-current="page"> All Activity</li>
							</ol>
						</div>
						<div class="d-flex">
							
						</div>
					</div>
					<!-- End Page Header -->



					
					<!-- Row -->
					<div class="row row-sm mt-3 ">
						<div class="col-lg-12">
							<div class="card custom-card overflow-hidden">
								<div class="card-body">
									




								<form method="POST" enctype="multipart/form-data">


                                <div class="form-group col s12">
                                        <label>User Account</label>
                                        <select    class="form-control show-tick ms select2" data-placeholder="Select"  name="userid" id="userid">
                                            <option value=""  >Select Account</option>
                                            <?php  $getaccount = mysqli_query($mysqli,"SELECT id, email FROM `users` WHERE status=1 "); 
                                            while($row =mysqli_fetch_assoc($getaccount)){
                                            ?>
                                            <option value="<?php echo $row['id']; ?>"><?php echo $row['email']; ?></option>
                                            <?php } ?>
                                            
                                        </select>

                                    </div>



                                    <div class="form-group col s12">
                                        <label for="phone">Title </label>
                                        <input type="text" name="title" class="form-control" autofocus
                                            placeholder="Enter Title E.G Referral Bonus of 10%, 1st level"  required>

                                    </div>

                                    <div class="form-group col s12">
                                        <label for="phone">Description  </label>
                                        <input type="text" name="info" class="form-control"
                                            placeholder="Enter Description E.G Referral commission of $3.06"  required>

                                    </div>

                                    <div class="form-group col s12">
                                        <label for="phone">Date  </label>
                                        <input type="text" name="date" class="form-control"
                                            placeholder="Enter Date " value="<?php echo date('d')." ".date('M')." ".date('Y')." ".date('h').":".date('i').date('a'); ?>"  required>

                                    </div>



                                    <div class="form-group col s12">
                                        <label>Status</label>
                                        <select  class="form-control" name="status" id="status">
                                            <option value=""  >Select Type</option>
                                            <option value="Credited">Credited</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Successful">Successful</option>
                                        </select>

                                    </div>

                                    <div class="form-group col s12">
                                        <label for="phone">Amount </label>
                                        <input type="number" name="amount" class="form-control"
                                            placeholder="Enter Amount E.G 100" required>

                                    </div>


                                   










                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info" value="" name="create">Add Activity
                                            </button>
                                    </div>


                                </form>










								</div>
							</div>
						</div>
					</div>
					<!-- End Row -->

				

				</div>
			</div>
		</div>
		<!-- End Main Content-->

		<!-- Main Footer-->
		<div class="main-footer text-center">
			<div class="container">
				<div class="row row-sm">
					<div class="col-md-12">
						<span>Copyright © <?php echo date('Y'); ?> 
							All rights reserved.</span>
					</div>
				</div>
			</div>
		</div>
		<!--End Footer-->

	

	</div>
	<!-- End Page -->

	<!-- Back-to-top -->
	<a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>

	<!-- Jquery js-->
	<script src="assets/plugins/jquery/jquery.min.js"></script>

	<!-- Bootstrap js-->
	<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

	<!-- Select2 js-->
	<script src="assets/plugins/select2/js/select2.min.js"></script>

	<!-- Internal Data Table js -->
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


	<!-- Select2 js-->
	<script src="assets/plugins/select2/js/select2.min.js"></script>

	<script src="assets/js/select2.js"></script>

	<!-- Perfect-scrollbar js -->
	<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/pscroll1.js"></script>

	<!-- Sidemenu js -->
	<script src="assets/plugins/sidemenu/sidemenu.js"></script>

	<!-- Sidebar js -->
	<script src="assets/plugins/sidebar/sidebar.js"></script>

	<!-- Color Theme js -->
	<script src="assets/js/themeColors.js"></script>

	<!-- Sticky js -->
	<script src="assets/js/sticky.js"></script>

	<!-- swither styles js -->
	<script src="assets/js/swither-styles.js"></script>

	<!-- Custom js -->
	<script src="assets/js/custom.js"></script>


	<script src="swal/sweetalert2.min.js"></script>



	<?php
//when click to creat new package
if(isset($_POST['create'])){
//retrive inputs


$title =  mysqli_real_escape_string($mysqli,$_POST['title']);
$amount =  mysqli_real_escape_string($mysqli,$_POST['amount']);


$info =  mysqli_real_escape_string($mysqli,$_POST['info']);

$date = mysqli_real_escape_string($mysqli,$_POST['date']);

$status = mysqli_real_escape_string($mysqli,$_POST['status']);

$userid = mysqli_real_escape_string($mysqli,$_POST['userid']);




$create = mysqli_query($mysqli,"INSERT INTO `activity`(`userid`, `action`, `describe`, `date`, `amount`, `status`) VALUES('$userid', '$title', '$info', '$date','$amount', '$status') ");

//echo mysqli_error($mysqli);

if($create){

    
?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Transaction Added Successfully',
    text: 'Transaction log added for user.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php

}




}
//end of creating a new package










?>




</body>

</html>