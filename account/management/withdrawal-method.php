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
    <link rel="icon" href="img/icon.png" type="image/x-icon" />

    <!-- Title -->
    <title>Withdrawal Method</title>

    <!-- Bootstrap css-->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Icons css-->
    <link href="assets/web-fonts/icons.css" rel="stylesheet" />
    <link href="assets/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
    <link href="assets/web-fonts/plugin.css" rel="stylesheet" />

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
                            <h2 class="main-content-title tx-24 mg-b-5">Withdrawal Method</h2>
                            <ol class="breadcrumb">

                                <li class="breadcrumb-item active" aria-current="page"> Withdrawal Method</li>
                            </ol>
                        </div>
                        <div class="d-flex">

                        </div>
                    </div>
                    <!-- End Page Header -->






                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">

                               
                                </div>
                            </div>
                        </div>
                    </div>







                    <!-- Row -->
                    <div class="row row-sm mt-3 ">
                        <div class="col-lg-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">
                                    <div>






                                    </div>
                                    <div class="table-responsive">
                                        <table id="file-datatable"
                                            class="table table-bordered text-nowrap key-buttons border-bottom">
                                            <thead class="">
                                                <tr>
                                                    <th>S/N</th>
                                                    <th>Method Name</th>
                                                    <th>Fixed Charge</th>
                                                    <th>Minimum Amount</th>
                                                    <th>Maximum Amount</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                                <?php
                                        //start the loop for see all users
                                        $get_users = mysqli_query($mysqli,"SELECT * FROM withdrawal_method ORDER BY id DESC");
                                            $i=0;
                                            while($row= mysqli_fetch_assoc($get_users)){
                                                $i++;
                                            ?>

                                                <tr>
                                                    <td><?php echo $i; ?></td>

                                                    <td><?php echo $row['name']; ?></td>
                                                    <td>$<?php echo $row['charge']; ?></td>
                                                    <td>$<?php echo $row['min_amount']; ?></td>
                                                    <td>$<?php echo $row['max_amount']; ?></td>

                                                    <td><?php if($row['status'] ==0){
                                                   echo "<p class='badge bg-danger' >Deactivated</p>"; 
                                                }else{
                                                     echo "<p class='badge bg-success' >Active </p>";
                                                   
                                                } ?></td>

                                                    <td><?php if($row['status'] ==1){
                                                    echo "<a class='btn btn-success modal-trigger' href='#pineedit". $row['id'] ."' >Edit</a>";
                                                }else{
                                                   echo "<a class='btn btn-warning modal-trigger' href='#pineedit".  $row['id'] ."'  >Edit</a>"; 
                                                } ?></td>
                                                </tr>





                                                <?php
                                                        //end of user loop

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


if(isset($_POST['add-withdrawal'])){
//retrive the inputs
$name =  mysqli_real_escape_string($mysqli,$_POST['name']);
$charge =  mysqli_real_escape_string($mysqli,$_POST['charge']);
$min_amount = mysqli_real_escape_string($mysqli,$_POST['min_amount']);
$max_amount = mysqli_real_escape_string($mysqli,$_POST['max_amount']);


//add this new   payment method
$add = mysqli_query($mysqli, "INSERT INTO withdrawal_method(name, charge, min_amount, max_amount, status) VALUES('$name', '$charge', '$min_amount', '$max_amount', 1)");

mysqli_query($mysqli,"UPDATE `users` SET `possible_withdrawal_amount`='$max_amount'");


if($add){
//method added

?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Withdrawal Method Added!',
  text: 'New withdrawal Method has been added Successfully!'
})


setTimeout(() => {
    location = location
}, 3000);
</script>

<?php


}


}
//end of adding



//start the updates
if(isset($_POST['update-info'])){

//retrive the inputs
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);
$name =  mysqli_real_escape_string($mysqli,$_POST['name']);
$charge =  mysqli_real_escape_string($mysqli,$_POST['charge']);
$min_amount = mysqli_real_escape_string($mysqli,$_POST['min_amount']);
$max_amount = mysqli_real_escape_string($mysqli,$_POST['max_amount']);

$update = mysqli_query($mysqli, "UPDATE `withdrawal_method` SET  name='$name', charge='$charge', min_amount='$min_amount', max_amount='$max_amount'  WHERE id='$id' ");

mysqli_query($mysqli,"UPDATE `users` SET `possible_withdrawal_amount`='$max_amount'");

if($update){

  ?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Withdrawal Method Updated',
  text: 'Withdrawal Method has been Updated Successfully!'
})


setTimeout(() => {
    location = location
}, 3000);
</script>

<?php  

}



}
//end of updated





//enable the payment method
if(isset($_POST['enable-method'])){
// to Approve person account
$name = mysqli_real_escape_string($mysqli,$_POST['name']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);

$enable = mysqli_query($mysqli,"UPDATE withdrawal_method SET status=1 WHERE id='$id' ");


if($enable){

    ?>
<script>

Swal.fire({
  icon: 'success',
  title: 'Method Enable',
  text: 'Withdrawal Method has been enable successfully!'
})


setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php


}



}
//end of disbale





//disable the withdrawal method
if(isset($_POST['disable-method'])){
// to Approve person account
$name = mysqli_real_escape_string($mysqli,$_POST['name']);
$id =  mysqli_real_escape_string($mysqli,$_POST['id']);

$disbale = mysqli_query($mysqli,"UPDATE withdrawal_method SET status=0 WHERE id='$id' ");

if($disbale){

    ?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Method Disabled',
  text: 'Withdrawal Method has been disabled successfully!'
})



setTimeout(() => {
    location = location;
}, 3000);
</script>

<?php


}



}
//end of disbale







?>




</body>

</html>