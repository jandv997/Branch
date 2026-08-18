<?php
session_start();

include('connection.php');
include_once __DIR__ . '/../email-handler.php';


//check if session id is set if it is redirect to login
if(!isset($_SESSION['adminid'])){
    
    header("location:index");
    
}


$get_admin = mysqli_query($mysqli,"SELECT * FROM admins WHERE id='".$_SESSION['adminid']."' ");
$rows = mysqli_fetch_assoc($get_admin);



?>
<?php
//to login into a user account
if(isset($_POST['user-login'])){

    $id = $_POST['id'];

    $_SESSION['id'] = $id;

    ?>
<script>
window.open('../dashboard', '_blank');
</script>

<?php

}


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
                                                    <th>S/N</th>
													<th>Fullname</th>
													<th>Email</th>
													<th>Phone Number</th>
													<th>Referred By</th>
													<th>Referral ID</th>
													<th>Wallet</th>
													<th>Status</th>

													<th>Action</th>

													<th>Access User</th>
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
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $row['firstname']." ".$row['lastname']; ?>
                                                </td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['phone']; ?></td>
                                                <td><?php if(mysqli_num_rows($getrefer)>0){  echo $rr['email']."(".$rr['firstname'].")"; } ?>
                                                </td>
                                                <td><?php echo $row['referal_link']; ?></td>
                                                <td>$<?php echo $row['wallet']; ?></td>
                                                <td><?php if($row['status'] ==0){
                                                   echo '<span class=" badge badge-danger">Pending</span>'; 
                                                }else{
                                                     echo '<span class=" badge badge-success">Verified</span>';
                                                   
                                                } ?></td>
                                                <td><?php if($row['status'] ==0){ 
                                                    echo "<a class='btn btn-small btn-info' data-bs-target='#pineuser".$row['id']."' data-bs-toggle='modal'  href='javascript:;' > View Account</a>";
                                                    ?>
                                                    <form method="POST" id="form-approve-<?php echo $row['id']; ?>">

                                                        <input type="hidden" name="id"
                                                            value="<?php echo $row['id']; ?>" />
                                                        <input type="hidden" name="email"
                                                            value="<?php echo $row['email']; ?>" />
                                                        <input type="hidden" name="approve-user"
                                                            value="<?php echo $row['id']; ?>" />

                                                        <button type='submit' class='btn btn-success mt-2 '>Approve
                                                            Account</button>

                                                    </form>

                                                    <?php }else{
                                                   echo "<a class='btn btn-small btn-warning '  data-bs-target='#pineuser".$row['id']."' data-bs-toggle='modal'  href='javascript:;'  > Block Account</a>"; 
                                                } ?>
                                                    <form method="POST">
                                                        <input type="hidden" value="<?php echo $row['id']; ?>"
                                                            name="id" />
                                                        <br />
                                                        <button type="submit" name="verify"
                                                            class="btn btn-small btn-info ">Verify Account</button>
                                                    </form>
                                                </td>




                                                <td>
                                                    <form method="POST">
                                                        <input type="hidden" value="<?php echo $row['id']; ?>"
                                                            name="id" />
                                                        <br />
                                                        <button type="submit" name="user-login"
                                                            class="btn btn-danger ">Login to user
                                                            Account</button>
                                                    </form> <br />

                                                    
                                                    <a class="btn btn-primary" target="_blank"
                                                        href="../<?php echo $row['idcard']; ?>">View Idcard</a>

                                                    <a class="btn btn-primary"  href="javascript:;"
                                                        data-bs-target='#user<?php echo $row['id']; ?>'
                                                        data-bs-toggle='modal'>Update Password </a>

                                                      

                                                        <a class="btn btn-primary"  href="javascript:;"
                                                        data-bs-target='#email<?php echo $row['id']; ?>'
                                                        data-bs-toggle='modal'>Edit Email</a>


                                                    

                                                        <a class="btn btn-danger"  href="javascript:;"
                                                        data-bs-target='#del<?php echo $row['id']; ?>'
                                                        data-bs-toggle='modal'>Delete Account  </a>

                                                       


                                                </td>



                                            </tr>










                                            <!-- Default Size -->
                                            <div class="modal fade" id="user<?php echo $row['id']; ?>" tabindex="-1"
                                                role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="title" id="defaultModalLabel">
                                                                Update
                                                                <?php echo $row['firstname']." ".$row['lastname']; ?> Password
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">


                                                           
                                                              
                                                            <form method="POST">

                                                            <div class="row">

                                                            <input type="hidden" name="id"
                                                            value="<?php echo $row['id']; ?>" />

                                                                <div class="col-12 form-group">                                    
                                                                    <input type="password" class="form-control" id="signup-password" placeholder="Enter New password">
                                                                </div>
                                                                <div class="col-12 form-group">                                    
                                                                    <input type="password" class="form-control" id="signup-password" placeholder="Confirm New password">
                                                                </div>



                                                                <button type="submit" name="change" class="btn btn-primary btn-lg btn-block">Change Password</button>



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






											 <!-- Default Size -->
											 <div class="modal fade" id="email<?php echo $row['id']; ?>" tabindex="-1"
                                                role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="title" id="defaultModalLabel">
                                                                Edit  
                                                                <?php echo $row['firstname']." ".$row['lastname']; ?> 
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">


                                                           
                                                              
                                                            <form method="POST">

                                                            <input type="hidden" name="id"
                                                            value="<?php echo $row['id']; ?>" />

                                                            <div class="row">

                                                            
                                                            <div class="col-12 form-group">                                    
                                                                    <input type="text" class="form-control"  name="firstname" id="signup-password" placeholder="Enter Firstname" value="<?php echo $row['firstname']; ?>" >
                                                                </div>

                                                                <div class="col-12 form-group">                                    
                                                                    <input type="text" class="form-control" name="lastname" id="signup-password" placeholder="Enter Lastname" value="<?php echo $row['lastname']; ?>" >
                                                                </div>


                                                                <div class="col-12 form-group">                                    
                                                                    <input type="email" class="form-control" name="email" id="signup-password" placeholder="Enter Email" value="<?php echo $row['email']; ?>" >
                                                                </div>
                                                              


                                                                <button type="submit" name="updateprofile" class="btn btn-primary btn-lg btn-block">Update Now</button>


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




                                              <!-- Default Size -->
                                              <div class="modal fade" id="del<?php echo $row['id']; ?>" tabindex="-1"
                                                role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="title" id="defaultModalLabel">
                                                                Delete
                                                                <?php echo $row['firstname']." ".$row['lastname']; ?> 
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">


                                                           
                                                              
                                                            <form method="POST">

                                                            <div class="row">

                                                            <input type="hidden" name="id"
                                                            value="<?php echo $row['id']; ?>" />

                                                                


                                                                <button type="submit" name="delete" class="btn btn-primary btn-lg btn-block">Delete Account</button>



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



											     <!-- Default Size -->
											   <div class="modal fade" id="pineuser<?php echo $row['id']; ?>" tabindex="-1"
                                                role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="title" id="defaultModalLabel">
                                                                View
                                                                <?php echo $row['firstname']." ".$row['lastname']; ?>
                                                            </h4>
                                                        </div>
                                                        <div class="modal-body">


                                                            <div class="row">

                                                                <div class="col-md-12 ">

                                                                    <h6>Account Status <?php if($row['status'] ==0){
                                                                        echo '<span class=" badge badge-danger">Pending</span>'; 
                                                                        }else{
                                                                            echo '<span class="badge badge-info">Verified </span>';
                                                                        
                                                                        } ?></h6>
                                                                    <br />

                                                                    <p>Uses Two Factor Authentication For login
                                                                    </p>
                                                                    <?php if($row['2fa'] ==0){
                                                                        echo '<span class=" badge badge-danger"> No </span>'; 
                                                                        }else{
                                                                            echo '<span class="badge badge-info" style="float:left"> Yes </span>';
                                                                        
                                                                        } ?>

                                                                </div>



                                                                <div class="col-md-6 ">
                                                                    <div class="form-group "><label
                                                                            for="example-text-input"
                                                                            class=" col-form-label">Email</label>
                                                                        <div class=""><input class="form-control"
                                                                                id="email" type="email" name="email"
                                                                                value="<?php echo $row['email']; ?>"
                                                                                disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group "><label
                                                                            for="example-text-input"
                                                                            class=" col-form-label">Phone
                                                                            Number</label>
                                                                        <div class=""><input class="form-control"
                                                                                id="phone" name="phone" type="number"
                                                                                value="<?php echo $row['phone']; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group "><label
                                                                            for="example-text-input"
                                                                            class=" col-form-label">Address</label>
                                                                        <div class=""><input class="form-control"
                                                                                type="text"
                                                                                value="<?php echo $row['address']; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group "><label
                                                                            for="example-text-input"
                                                                            class=" col-form-label">State</label>
                                                                        <div class=""><input class="form-control"
                                                                                type="text"
                                                                                value="<?php echo $row['state']; ?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group "><label
                                                                            for="example-text-input"
                                                                            class=" col-form-label">Zip
                                                                            Code</label>
                                                                        <div class=""><input class="form-control"
                                                                                type="text"
                                                                                value="<?php echo $row['zip']; ?>">
                                                                        </div>
                                                                    </div>





                                                                </div>

                                                                <div class="col-md-6 ">
                                                                    <h4>Idcard </h4>

                                                                    <a class="btn btn-primary" target="_blank"
                                                                        href="../<?php echo $row['idcard']; ?>">View
                                                                        Idcard</a><br />




                                                                    <form method="POST"
                                                                        id="form-disable-<?php echo $row['id']; ?>">

                                                                        <input type="text" name="note" class="form-control mt-4" placeholder="Message for Account Blocking." />
                                                                        <input type="hidden" name="id"
                                                                            value="<?php echo $row['id']; ?>" />
                                                                        <input type="hidden" name="email"
                                                                            value="<?php echo $row['email']; ?>" />
                                                                        <input type="hidden" name="disable-user"
                                                                            value="<?php echo $row['id']; ?>" />

                                                                    </form>


                                                                </div>



                                                            </div>




                                                        </div>
                                                        <div class="modal-footer">
                                                            <?php if($row['status'] ==0){
                                                    echo " <button type='button' onclick="."document.getElementById('form-approve-".$row['id']."').submit()"." class='btn btn-success '>Approve Account</button>";
                                                }else{
                                                   echo "<button class='btn btn-danger' onclick="."document.getElementById('form-disable-".$row['id']."').submit()"." >Block Account</button>"; 
                                                } ?>

                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">CLOSE</button>
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


if(isset($_POST['approve-user'])){

// to Approve person account
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);

$approve = mysqli_query($mysqli,"UPDATE users SET status=1 WHERE id='$id' and email='$email' ");
    
if($approve){

        $pick = mysqli_query($mysqli,"SELECT * FROM users WHERE id='$id' and email='$email' ");
        $r = mysqli_fetch_assoc($pick);
		$name = $r['firstname']." ".$r['lastname'];
		//send the welcome email with verification link here
		//start email sending



        



//send welcome email
sendWelcomeEmail($email);













    ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Approval Successful',
        text: 'Account with <?php echo $email; ?>, has been approved successfully.'
    });


    setTimeout(() => {
        location = location;
    }, 3000);
    </script>

    <?php


}



}


?>






<?php




if(isset($_POST['approve-kyc'])){
// to Approve person account
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);

$approve = mysqli_query($mysqli,"UPDATE users SET kycstatus=1 WHERE id='$id' and email='$email' ");
    
if($approve){

        $pick = mysqli_query($mysqli,"SELECT * FROM users WHERE id='$id' and email='$email' ");
        $r = mysqli_fetch_assoc($pick);
    $name = $r['firstname']." ".$r['lastname'];
//send the welcome email with verification link here
//start email sending









    ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Kyc Approval Successful',
    text: 'Account with <?php echo $email; ?>, has been approved successfully'
});


setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php


}



}



if(isset($_POST['delete'])){
    // to Approve person account
  
    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
    
    $disbale = mysqli_query($mysqli,"DELETE FROM users WHERE id='$id'  ");
    
    if($disbale){
    
        ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Account Deleted',
    text: 'Account  has been deleted successfully.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php
    
    
    }
    
    
    
    }



    


    if(isset($_POST['activatehigher'])){
        // to Approve person account
      
        $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
        
        $disbale = mysqli_query($mysqli,"UPDATE users SET higherbonus=1 WHERE id='$id'  ");
        
        if($disbale){
        
            ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Higher ROI Bonus Activated ',
        text: 'Account  has been Activated for higher bonus .'
    });
    
    setTimeout(() => {
        location = location;
    }, 3000);
    </script>
    
    <?php
        
        
        }
        
        
        
        }




if(isset($_POST['verify'])){
    // to Approve person account
  
    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
    
    $disbale = mysqli_query($mysqli,"UPDATE users SET userstatus=1 WHERE id='$id'  ");
    
    if($disbale){
    
        ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Account Verifed',
    text: 'Account  has been verified successfully.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php
    
    
    }
    
    
    
    }



    



if(isset($_POST['updateprofile'])){
    // to Approve person account
  
    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);
    $firstname =  mysqli_real_escape_string($mysqli,$_POST['firstname']);
    $lastname =  mysqli_real_escape_string($mysqli,$_POST['lastname']);
    $email =  mysqli_real_escape_string($mysqli,$_POST['email']);




    $disbale = mysqli_query($mysqli,"UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email' WHERE id='$id'  ");
    
    if($disbale){
    
        ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Account Updated',
    text: 'Account  has been Updated successfully.'
});

setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php
    
    
    }
    
    
    
    }







//disbae the user account
if(isset($_POST['disable-user'])){
// to Approve person account
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);
$note =  mysqli_real_escape_string($mysqli,$_POST['note']);

$disbale = mysqli_query($mysqli,"UPDATE users SET `status`=0 WHERE id='$id' and email='$email' ");

if($disbale){

    ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Account Disabled',
    text: 'Account With <?php echo $email; ?>, has been disabled successfully'
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