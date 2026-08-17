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
	<title>View Investments</title>

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
                                    <h4 class=""><b>User Account</b></h4>
		<select    class="form-control show-tick ms select2" data-placeholder="Select"  name="userid" id="userid">
			<option value=""  >Select Account</option>
			<?php  $getaccount = mysqli_query($mysqli,"SELECT id, email FROM `users` WHERE status=1 "); 
			while($row =mysqli_fetch_assoc($getaccount)){
			?>
			<option value="<?php echo $row['id']; ?>"><?php echo $row['email']; ?></option>
			<?php } ?>
			
		</select>
									</div>
									<div  class="col-2">
                                    <button type="submit" name="trigggerseach" class="btn btn-default btn-primary mt-4"><i
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
$no_of_records_per_page = 50;
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
                                                <th>S/N</th>
                                                <th>Client Name</th>
                                                <th>Email</th>
                                                <th>Package</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Duration</th>
                                                <th>Daily Returns(ROI)</th>
                                                <th>Payout</th>
                                                <th>Status</th>
                                                <th>Action</th>
												</tr>
											</thead>
											<tbody>


												
											
                                            <?php
                                        //start the loop for see all users

                                        if(isset($_GET['userid'])){
                                            $get_investment = mysqli_query($mysqli,"SELECT * FROM investment WHERE userid='".$_GET['userid']."' ORDER BY id DESC  LIMIT $offset, $no_of_records_per_page");
                                        }else{
                                            $get_investment = mysqli_query($mysqli,"SELECT * FROM investment ORDER BY id DESC  LIMIT $offset, $no_of_records_per_page");

                                        }
                                       



                                            $i=0;
                                            while($row= mysqli_fetch_assoc($get_investment)){
                                                $i++;

                                                

                                                 $getuser= mysqli_query($mysqli,"SELECT id, email, firstname, lastname FROM users WHERE id='".$row['userid']."'");
                                                $user = mysqli_fetch_assoc($getuser);

                                            ?>








                                            <tr>
                                                <td><?php echo $i; ?></td>

                                                <td><?php echo $user['firstname']."  ".$user['lastname']; ?>
                                                </td>
                                                <td><?php echo $user['email']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['date']; ?></td>
                                                <td>$<?php echo $row['amount']; ?></td>
                                                <td><?php echo $row['duration']; ?> Days</td>
                                                <td>$<?php echo $row['daily_roi']; ?></td>
                                                <td><?php if($row['payout']==1){
                                                    echo "Daily Payout";
                                                }elseif($row['payout']==5){
                                                    echo "6 Months ";
                                               }elseif($row['payout']==6){
                                                    echo "7  Months ";
                                               }elseif($row['payout']==7){
                                                  echo "8  Months ";
                                             }elseif($row['payout']==8){
                                                  echo "9  Months ";
                                             }   ?>





                                                </td>

                                                <td><?php if($row['status'] ==0){
                                                   echo "<span class='badge bg-danger' >Not Active</span>"; 
                                                }else{
                                                     echo "<span class='badge bg-info' >Active</span>";
                                                   
                                                } ?></td>

                                                <td>
                                                
                                                </td>


                                            </tr>

















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










        <!-- Default Size -->
        <div class="modal fade" id="create" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="title" id="defaultModalLabel">
                            Create investment

                        </h4>
                    </div>
                    <div class="modal-body">




                        <form method="POST" style="padding:10px">

                            <div class="row">

                            <div class="col-12 form-group">
                            <label>User Account</label>
                            <select class="form-control show-tick ms select2" data-placeholder="Select" name="userid"
                                id="userid">
                                <option value="">Select Account</option>
                                <?php  $getaccount = mysqli_query($mysqli,"SELECT id, email FROM `users` WHERE status=1 "); 
                                            while($row =mysqli_fetch_assoc($getaccount)){
                                            ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['email']; ?></option>
                                <?php } ?>

                            </select>
                        </div>


                                <div class="col-12 form-group">
                                    <label>Investment Portfolio </label>
                                    <select name="investmentid"  onchange="changename('idname', 'investmentid')"  class="form-control mb-3" required>

                                        <?php  $getinvest = mysqli_query($mysqli, "SELECT * FROM investment_packages");

                                                                            while($in = mysqli_fetch_assoc($getinvest)){ 
                                                                                ?>

                                        <option  value="<?php echo $in['id']; ?>"><?php echo $in['name']." ".$in['percent']." || ".$in['compound_percent']; ?></option>

                                        <?php } ?>


                                    </select>
                                </div>


                                <div class="col-12 form-group">
                                    <label>Investment Package Name</label>
                                    <input type="text" class="form-control" name="name"
                                        id="idname" value=""
                                        placeholder="Enter Name ">
                                </div>




                                <div class="col-12 form-group">
                                    <label>Investment Amount</label>
                                    <input type="number" class="form-control" name="amount"
                                         placeholder="Enter Amount ">
                                </div>
                                <div class="col-12 form-group">
                                    <label>Daily Roi</label>
                                    <input type="text" class="form-control" name="daily_roi"
                                         placeholder="Enter Daily roi ">
                                </div>


                              

                                <div class="col-12 form-group">
                                    <label>Payout </label>
                                    <select name="payout" class="form-control mb-3" id="payout" required>
                                        <option value="">Select Payout</option>
                                        <option  value="1">Daily
                                            Payout</option>
                                        <option  value="5">6 Months
                                        </option>
                                        <option  value="6">7 Months
                                        </option>
                                        <option value="7">8 Months
                                        </option>
                                        <option value="8">9 Months
                                        </option>


                                       
                                    </select>
                                </div>


                               


                                <div class="col-12 form-group">
                                    <label>Date of Investment </label>
                                    <input type="text" class="form-control" name="date"
                                        value="<?php echo  date("d")." ".date("F")." ".date("Y")." , ".date("h")." : ".date("i").date("a"); ?>" placeholder="Enter Date  ">
                                </div>



                                <div class="col-12 form-group">
                                    <label>Can Re-invest </label>
                                    <select name="can_reinvest" class="form-control mb-3" id="can_reinvest" required>
                                        <option value="">Select Option</option>
                                        <option value="1">Yes
                                        </option>
                                        <option value="0">No
                                        </option>



                                    </select>
                                </div>

                                


                                <div class="col-12 form-group">
                                                                        <label>Auto Re-investment </label>
                                                                        <select name="auto_reinvest"
                                                                            class="form-control mb-3" id="auto_reinvest"
                                                                            required>
                                                                            <option value="">Select Option</option>
                                                                            <option
                                                                               
                                                                                value="1">Yes </option>
                                                                            <option
                                                                               
                                                                                value="0">No</option>



                                                                        </select>
                                                                    </div>

                                

                              





                                <div class="col-12">

                                    <button type="submit" name="create" class="btn btn-primary  btn-block">Create
                                        Portfolio
                                    </button>

                                </div>



                            </div>

                        </form>



                    </div>
                    <div class="modal-footer">


                        <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                    </div>
                </div>
            </div>
        </div>














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



    <!-- Select2 js-->
	<script src="assets/plugins/select2/js/select2.min.js"></script>


	<!-- Sidemenu js -->
	<script src="assets/plugins/sidemenu/sidemenu.js"></script>

	<!-- Sidebar js -->
	<script src="assets/plugins/sidebar/sidebar.js"></script>

	<!-- Color Theme js -->

    	<!-- Select2 js-->
	<script src="assets/plugins/select2/js/select2.min.js"></script>

<script src="assets/js/select2.js"></script>


	<script src="assets/js/themeColors.js"></script>

	<!-- Sticky js -->
	<script src="assets/js/sticky.js"></script>

	<!-- swither styles js -->
	<script src="assets/js/swither-styles.js"></script>

	<!-- Custom js -->
	<script src="assets/js/custom.js"></script>


	<script src="swal/sweetalert2.min.js"></script>


    <script>
    function changename(inputid, selectid) {

        var text = $("#"+ selectid + " option:selected").text();

        $('#' + inputid).val(text.trim());


    }
    </script>

   


    <?php


if(isset($_POST['change'])){

    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);

    $investmentid =  mysqli_real_escape_string($mysqli,$_POST['investmentid']);

    $amount =  mysqli_real_escape_string($mysqli,$_POST['amount']);
    
    $name =  mysqli_real_escape_string($mysqli,$_POST['name']);

    
    $date = mysqli_real_escape_string($mysqli,$_POST['date']);
    
    $status = mysqli_real_escape_string($mysqli,$_POST['status']);

    $daily_roi = mysqli_real_escape_string($mysqli,$_POST['daily_roi']);

    $added_roi = mysqli_real_escape_string($mysqli,$_POST['added_roi']);

    $duration = mysqli_real_escape_string($mysqli,$_POST['duration']);
    
    $auto_reinvest = mysqli_real_escape_string($mysqli,$_POST['auto_reinvest']);

    $payout =  mysqli_real_escape_string($mysqli,$_POST['payout']);

    $can_reinvest =  mysqli_real_escape_string($mysqli,$_POST['can_reinvest']);

    $can_restart =  mysqli_real_escape_string($mysqli,$_POST['can_restart']);
    



    $create = mysqli_query($mysqli,"UPDATE `investment` SET  `investmentid`='$investmentid', `name`='$name', `amount`='$amount', `daily_roi`='$daily_roi', `added_roi`='$added_roi', `payout`='$payout', `duration`='$duration', `auto_reinvest`='$auto_reinvest',`date`='$date', `can_reinvest`='$can_reinvest', `can_restart`='$can_restart', `status`='$status'  WHERE `id`='$id' ");



    if($create){

    ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Investment Updated Successfully',
        text: 'Investment Information Adjusted .'
    });

    setTimeout(() => {
        location = location;
    }, 3000);
    </script>

    <?php

    }





}


if(isset($_POST['delete-port'])){

    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);




    $create = mysqli_query($mysqli,"DELETE FROM `investment`  WHERE `id`='$id' ");



    if($create){

    ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Investment Porfolio Deleted Successfully',
        text: 'Investment Porfolio  Deleted .'
    });

    setTimeout(() => {
        location = location;
    }, 3000);
    </script>

    <?php

    }




}








if(isset($_POST['create'])){


    $investmentid =  mysqli_real_escape_string($mysqli,$_POST['investmentid']);

    $amount =  mysqli_real_escape_string($mysqli,$_POST['amount']);
    
    $name =  mysqli_real_escape_string($mysqli,$_POST['name']);
    
    $date = mysqli_real_escape_string($mysqli,$_POST['date']);
    
    $status = mysqli_real_escape_string($mysqli,$_POST['status']);

    $daily_roi = mysqli_real_escape_string($mysqli,$_POST['daily_roi']);

    $added_roi = mysqli_real_escape_string($mysqli,$_POST['added_roi']);

    $duration = mysqli_real_escape_string($mysqli,$_POST['duration']);

    $payout =  mysqli_real_escape_string($mysqli,$_POST['payout']);

    $can_reinvest =  mysqli_real_escape_string($mysqli,$_POST['can_reinvest']);


    $auto_reinvest =  mysqli_real_escape_string($mysqli,$_POST['auto_reinvest']);
    
    $userid =  mysqli_real_escape_string($mysqli,$_POST['userid']);




    //proceed to add into investment
    $addinvest = mysqli_query($mysqli,"INSERT INTO `investment`(`userid`, `investmentid`, `name`, `amount`, `daily_roi`, `payout`, `date`, `can_reinvest`, `auto_reinvest`) VALUES('$userid', '$investmentid', '$name', '$amount',  '$daily_roi', '$payout', '$date' , '$can_reinvest', '$auto_reinvest') ");
    


    if($addinvest){


        ?>
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Investment Porfolio Created Successfully',
            text: 'Investment Porfolio  Created .'
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