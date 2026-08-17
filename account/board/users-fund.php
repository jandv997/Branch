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
	<title>Users</title>

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
							<h2 class="main-content-title tx-24 mg-b-5">Users</h2>
							<ol class="breadcrumb">
							
								<li class="breadcrumb-item active" aria-current="page"> All Users</li>
							</ol>
						</div>
						<div class="d-flex">
							
						</div>
					</div>
					<!-- End Page Header -->


					<div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                            <center>

                                <form id="navbar-search"  class="navbar-form search-form row">
									<div  class="col-6">
                                    <input required class="form-control" name="metadata" placeholder=" Search User..."
                                        type="text">
									</div>
									<div  class="col-2">
                                    <button type="submit" name="trigggerseach" class="btn btn-default btn-primary"><i
                                            class="icon-magnifier"></i></button>
											</div>
                                </form>

                            </center>

                    </div>

					
					<!-- Row -->
					<div class="row row-sm mt-3 ">
						<div class="col-lg-12">
							<div class="card custom-card overflow-hidden">
								<div class="card-body">
									<div>
									
									<?php

											if (isset($_GET['pageno'])) {
												$pageno = $_GET['pageno'];
											} else {
												$pageno = 1;
											}
											$no_of_records_per_page = 30;
											$offset = ($pageno-1) * $no_of_records_per_page;



											$total_pages_sql = "SELECT COUNT(*) FROM users";
											$result = mysqli_query($mysqli,$total_pages_sql);
											$total_rows = mysqli_fetch_array($result)[0];
											$total_pages = ceil($total_rows / $no_of_records_per_page);



                        			?>



                                <ul class="pagination">
                                    <li><a class="btn btn-primary m-2" style="color:white" href="?pageno=1">First</a>
                                    </li>
                                    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                                        <a class="btn btn-primary m-2" style="color:white"
                                            href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
                                    </li>
                                    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                                        <a class="btn btn-primary m-2" style="color:white"
                                            href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
                                    </li>
                                    <li><a class="btn btn-primary m-2" style="color:white"
                                            href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                                </ul>
										
									</div>
									<div class="table-responsive">
										<table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
											<thead class="">
												<tr>
                                                <th>Fullname</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>Wallet</th>
                                                <th>Status</th>


                                                <th>Block Withdrawal</th>

                                                <th>More Actions</th>
												</tr>
											</thead>
											<tbody>


												
											
                                            <?php



												if(isset($_GET['metadata'])){
													
													$data = $_GET['metadata'];

													//start the loop for see all users
													$get_users = mysqli_query($mysqli,"SELECT * FROM users WHERE  (email LIKE '%".$data."%' OR firstname LIKE '%".$data."%' )");

												}else{

													//start the loop for see all users
													$get_users = mysqli_query($mysqli,"SELECT * FROM users ORDER BY id DESC LIMIT $offset, $no_of_records_per_page");

												}




                                       

												$i=0;
												while($row= mysqli_fetch_assoc($get_users)){
													$i++;
	
													
	
													$getrefer = mysqli_query($mysqli,"SELECT * FROM users WHERE referal_link='".$row['referred']."' ");
													$rr = mysqli_fetch_assoc($getrefer);
												?>


                                                <tr>
                                                <td><?php echo $row['firstname']." ".$row['lastname']; ?>
                                                </td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['phone']; ?></td>
                                                <td>$<?php echo $row['wallet']; ?></td>
                                                <td><?php if($row['status'] ==0){
                                                   echo '<span class=" badge bg-danger">Pending</span>'; 
                                                }else{
                                                     echo '<span class=" badge bg-info">Verified</span>';
                                                   
                                                } ?></td>
                                                <td> <form method="POST">
                                                        <input type="hidden" value="<?php echo $row['id']; ?>"
                                                            name="id" />
                                                        <input type="hidden" value="<?php echo $row['email']; ?>"
                                                            name="email" />
                                                        <br />
                                                        <?php  if($row['can_withdraw'] == 1){ ?>
                                                        <button type="submit" name="block-withdrawal"
                                                            class="btn btn-danger ">Block
                                                            Withdrawal</button>
                                                        <?php  }else{ ?>
                                                        <button type="submit" name="enable-withdrawal"
                                                            class="btn btn-danger ">Enable
                                                            Withdrawal</button>
                                                        <?php } ?>
                                                    </form>
                                                </td>




                                                <td>
                                                   
                                                <a class='btn btn-info' data-bs-toggle="modal" href="javascript:;"
                                                        data-bs-target='#pinewith<?php echo $row['id']; ?>'>Update
                                                        withdrawal limit</a>
                                                    <br /> <br />

                                                    <a class='btn btn-info' data-bs-toggle="modal" href="javascript:;"
                                                        data-bs-target='#pinerefer<?php echo $row['id']; ?>'>Assign
                                                        Referal Upline</a>


                                                       


                                                </td>



                                            </tr>









                                            <!-- Default Size -->
                                            <div class="modal fade" id="pinewith<?php echo $row['id']; ?>" tabindex="-1"
                                                role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="title" id="defaultModalLabel">
                                                                Set withdrawal limit for
                                                                <?php echo $row['firstname']." ".$row['lastname']; ?>
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">






                                                            <form method="POST">

                                                                <input type="hidden" value="<?php echo $row['id']; ?>"
                                                                    name="id" />
                                                                <input type="hidden"
                                                                    value="<?php echo $row['email']; ?>" name="email" />

                                                                <div class="form-group">
                                                                    <label for="state">Withdrawal
                                                                        limit</label>
                                                                    <input name="amount" id="amount"
                                                                        class="form-control" placeholder="Enter amount"
                                                                        value="<?php echo $row['possible_withdrawal_amount']; ?>"
                                                                        type="number">

                                                                </div>


                                                                <div class=" form-group">
                                                                    <button type="submit" class="btn btn-warning"
                                                                        value=""
                                                                        name="set-withdraw-limit">Update</button>


                                                            </form>








                                                        </div>
                                                        <div class="modal-footer">


                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">CLOSE</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                </div>







                                <!-- Default Size -->
                                <div class="modal fade" id="pinerefer<?php echo $row['id']; ?>" tabindex="-1"
                                    role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="title" id="defaultModalLabel">
                                                    Add referal upline for
                                                    <?php echo $row['firstname']." ".$row['lastname']; ?>
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <?php if($row['referred'] !=""){echo "<h4>This user already has an upline</h4>"; } ?>






                                                <form method="POST">

                                                    <input type="hidden" value="<?php echo $row['id']; ?>" name="id" />
                                                    <input type="hidden" value="<?php echo $row['email']; ?>"
                                                        name="email" />

                                                    <div class="form-group">
                                                        <label for="state">Enter Upline
                                                            email</label>
                                                        <input name="upline" id="upline" class="form-control"
                                                            placeholder="Enter email" type="email" required>

                                                    </div>


                                                    <div class=" form-group">
                                                        <button type="submit" class="btn btn-warning" value=""
                                                            name="assign-upline">Update</button>
                                                    </div>


                                                </form>









                                            </div>
                                            <div class="modal-footer">


                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">CLOSE</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>





                                            <?php

                                            }

                                             ?>


												
											</tbody>
										</table>
									</div>
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



//to block a user withdrawal
if(isset($_POST['block-withdrawal'])){
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);

$block = mysqli_query($mysqli,"UPDATE users SET can_withdraw=0 WHERE id='$id'");

if($block){

  ?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Withdrawal Blocked',
  text: 'The Account with <?php echo $email; ?> has been blocked from withdrawing'
});
    
setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php

}
//successful

}






//to block a user affiliate
if(isset($_POST['block-affiliate'])){

    $email = mysqli_real_escape_string($mysqli,$_POST['email']);
    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
    
    $block = mysqli_query($mysqli,"UPDATE users SET affiliate=0 WHERE id='$id'");
    
    if($block){
    
      ?>
    <script>
 

    Swal.fire({
  icon: 'success',
  title: 'Affiliate Blocked',
  text: 'The Account with <?php echo $email; ?> has been blocked from affiliate'
});
    
    setTimeout(() => {
        location = location;
    }, 3000);
    </script>
    
    <?php
    
    }
    //successful
    
    }


//to enable a user withdrawal
if(isset($_POST['enable-withdrawal'])){

$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);

$block = mysqli_query($mysqli,"UPDATE users SET can_withdraw=1 WHERE id='$id'");

if($block){ 

  ?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Withdrawal Enabled',
  text: 'The Account with <?php echo $email; ?> has been Enabled for withdrawing'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php

}
//successful

}



//to enable a user affiliate
if(isset($_POST['enable-affiliate'])){

    $email = mysqli_real_escape_string($mysqli,$_POST['email']);
    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
    
    $block = mysqli_query($mysqli,"UPDATE users SET affiliate=1 WHERE id='$id'");
    
    if($block){ 
    
      ?>
    <script>
 

    Swal.fire({
  icon: 'success',
  title: 'Affiliate Enabled',
  text: 'The Account with <?php echo $email; ?> has been Enabled for affiliate.'
});
    
    setTimeout(() => {
        location = location;
    }, 3000);
    </script>
    
    <?php
    
    }
    //successful
    
    }



//set withdrawal limit
if(isset($_POST['set-withdraw-limit'])){

$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);
$amount = mysqli_real_escape_string($mysqli,$_POST['amount']);

$change = mysqli_query($mysqli,"UPDATE users SET possible_withdrawal_amount='$amount' WHERE id='$id'");

if($change){

  ?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Withdrawal Limit Updated',
  text: 'The Account with <?php echo $email; ?> has a new withdrawal limit of <?php echo $amount; ?>.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php

}
//successful



}


//assing  upline for a user 
if(isset($_POST['assign-upline'])){

    $email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);
$upline = mysqli_real_escape_string($mysqli,$_POST['upline']);

//go and get upline referal code
$getcode = mysqli_query($mysqli,"SELECT * from users WHERE email='$upline' ");
$g = mysqli_fetch_assoc($getcode);

if(mysqli_num_rows($getcode) > 0){

$change = mysqli_query($mysqli,"UPDATE users SET referred='".$g['referal_link']."' WHERE id='$id'");

if($change){

  ?>
<script>

Swal.fire({
  icon: 'success',
  title: 'Upline referal set successfully',
  text: 'The Account with <?php echo $email; ?> has a referal.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php

}
//successful

}else{
//upline does not exist

 ?>
<script>


Swal.fire({
  icon: 'warning',
  title: 'Upline Email is wrong',
  text: 'The email enter for upline does not exist.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php

}



}




//assing bouns to user 
if(isset($_POST['assign-bouns'])){

$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);
$amount = mysqli_real_escape_string($mysqli,$_POST['amount']);
$detail = mysqli_real_escape_string($mysqli,$_POST['detail']);
$date= date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a");

echo $email." ".$amount;

$add = mysqli_query($mysqli,"INSERT INTO referal(`date`, `claimerid`, `amount`, `detail`) VALUES('$date', '$id', '$amount', '$detail')");

if($add){

  ?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Referal Bonus added',
  text: 'The Account with <?php echo $email; ?> has given a referal bouns of  $<?php echo $amount; ?>.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php

}
//successful


}



//when the user wants to withdraw
if(isset($_POST['withdraw'])){

//retrive inputs
$debit_wallet = mysqli_real_escape_string($mysqli,$_POST['debit_wallet']);
$amount = mysqli_real_escape_string($mysqli,$_POST['amount']);
$wallet_address= mysqli_real_escape_string($mysqli,$_POST['wallet_address']);
$method = mysqli_real_escape_string($mysqli,$_POST['method']);
$username = mysqli_real_escape_string($mysqli,$_POST['username']);
$userid = mysqli_real_escape_string($mysqli,$_POST['id']);


$getwithdraw = mysqli_query($mysqli,"INSERT INTO `withdrawal`(`userid`, `wallet_address`, `amount`, `username`, `method`, `debit_wallet`)  VALUES('$userid', '$wallet_address', '$amount', '$username', '$method', '$debit_wallet') ");

if($getwithdraw){

 //start email sending



            //end of email sending

             ?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Withdrawal Request Successfull!',
  text: 'Your request to withdraw $<?php echo $amount; ?> has been sent successfully.'
});


setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php


}



}







?>

</body>

</html>