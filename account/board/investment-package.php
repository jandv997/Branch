<?php
session_start();

include('connection.php');


//check if session id is set if it is redirect to login
if (!isset($_SESSION['adminid'])) {

    header("location:index");

}


$get_admin = mysqli_query($mysqli, "SELECT * FROM admins WHERE id='" . $_SESSION['adminid'] . "' ");
$rows = mysqli_fetch_assoc($get_admin);


$getinvestment = mysqli_query($mysqli, "SELECT * FROM investment_packages");


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">




    <!-- Favicon -->
    <link rel="icon" href="img/icon.png" type="image/x-icon" />

    <!-- Title -->
    <title>View Investment Packages</title>

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
                            <h2 class="main-content-title tx-24 mg-b-5">Investment Method</h2>
                            <ol class="breadcrumb">

                                <li class="breadcrumb-item active" aria-current="page">Investment Method</li>
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


                                    <form method="POST" enctype="multipart/form-data">


                                        <div class="form-group col s12">
                                            <label for="phone">Package Name</label>
                                            <input type="text" name="name" class="form-control" autofocus
                                                placeholder="Enter Package Name" class="" value="" required>

                                        </div>

                                        <div class="form-group col s12">
                                            <label for="phone">Minimum Investment Amount</label>
                                            <input type="number" name="min_amount" class="form-control"
                                                placeholder="Enter Amount" required>

                                        </div>

                                        <div class="form-group col s12">
                                            <label for="phone">Maximum Investment Amount</label>
                                            <input type="number" name="max_amount" class="form-control"
                                                placeholder="Enter Amount">

                                        </div>

                                        <div class="form-group col s12">
                                            <label for="phone">Min Percentage</label>
                                            <input type="text" name="percent" class="form-control"
                                                placeholder="Enter min Percent" required>

                                        </div>
                                        <div class="form-group col s12">
                                            <label for="phone">Max Percentage</label>
                                            <input type="text" name="percent2" class="form-control"
                                                placeholder="Enter max Percent" required>

                                        </div>

                                        <div class="form-group col s12">
                                            <label for="phone">Duration</label>
                                            <input type="text" name="duration" class="form-control"
                                                placeholder="Enter duration">

                                        </div>

                                    

                                        <div class="form-group col s12">
                                            <label for="phone">Compounding Percent</label>
                                            <input type="text" name="compound_percent" class="form-control"
                                                placeholder="Enter Percent" required>

                                        </div>


                                        <div class="form-group col s12">
                                            <label for="phone">Info Heading 1</label>
                                            <input type="text" name="infohead1" class="form-control"
                                                placeholder="Enter Heading" value="">

                                        </div>
                                        <div class="form-group col s12">
                                            <label for="phone">Info Details 1</label>
                                            <input type="text" name="info1" class="form-control"
                                                placeholder="Enter info" value="">

                                        </div>

                                        <div class="form-group col s12">
                                            <label for="phone">Info Heading 2</label>
                                            <input type="text" name="infohead2" class="form-control"
                                                placeholder="Enter Heading" value="">

                                        </div>
                                        <div class="form-group col s12">
                                            <label for="phone">Info Details 2</label>
                                            <input type="text" name="info2" class="form-control"
                                                placeholder="Enter info" value="">

                                        </div>

                                        <div class="form-group col s12">
                                            <label for="phone">Info Heading 3</label>
                                            <input type="text" name="infohead3" class="form-control"
                                                placeholder="Enter Heading" value="">

                                        </div>
                                        <div class="form-group col s12">
                                            <label for="phone">Info Details 3</label>
                                            <input type="text" name="info3" class="form-control"
                                                placeholder="Enter info" value="">

                                        </div>

                                        <div class="form-group col s12">
                                            <label for="phone">Info Heading 4</label>
                                            <input type="text" name="infohead4" class="form-control"
                                                placeholder="Enter Heading" value="">

                                        </div>
                                        <div class="form-group col s12">
                                            <label for="phone">Info Details 4</label>
                                            <input type="text" name="info4" class="form-control"
                                                placeholder="Enter info" value="">

                                        </div>

                                        <!--
                                        <div class="form-group col s12">
                                            <label for="phone">Info Heading 5</label>
                                            <input type="text" name="infohead5" class="form-control"
                                                placeholder="Enter Heading" value="">

                                        </div>
                                        <div class="form-group col s12">
                                            <label for="phone">Info Details 5</label>
                                            <input type="text" name="info5" class="form-control"
                                                placeholder="Enter info" value="">

                                        </div>
                                        -->





                                        <div class="form-group">
                                            <button type="submit" class="btn btn-info" value="" name="create">Create
                                                Package</button>
                                        </div>


                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>










                    <?php

                                
                                  

while($data = mysqli_fetch_assoc($getinvestment)){

 ?>




                    <div class="row clearfix" id="edit-<?php echo $data['id']; ?>" style="display:none">
                        <div class="col-lg-12">
                            <div class="card custom-card overflow-hidden">
                                <div class="card-body">


                                <form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
                        <div class="form-group col s12">

                            <input type="text" name="name" class="form-control" autofocus
                                placeholder="Enter Package Name" value="<?php echo $data['name']; ?>"  required>
                            <label for="phone">Package Name</label>
                        </div>

                        <div class="form-group col s12">

                            <input type="number" name="min_amount" class="form-control"
                                placeholder="Enter Amount" value="<?php echo $data['min_amount']; ?>" required>
                            <label for="phone">Minimum Investment Amount</label>
                        </div>

                        <div class="form-group col s12">

                            <input type="number" name="max_amount" class="form-control"
                                placeholder="Enter Amount" value="<?php echo $data['max_amount']; ?>" >
                            <label for="phone">Maximum Investment Amount</label>
                        </div>

                        <div class="form-group col s12">

                            <input type="text" name="percent" class="form-control"
                                placeholder="Enter Percent" value="<?php echo $data['percent']; ?>" required>
                            <label for="phone">Percentage</label>
                        </div>

                        <div class="form-group col s12">

                            <input type="text" name="percent2" value="<?php echo $data['percent2']; ?>" class="form-control"
                                placeholder="Enter max Percent" required>
                            <label for="phone">Max Percentage</label>
                        </div>

                        <div class="form-group col s12">

                            <input type="text" name="duration" class="form-control"
                                placeholder="Enter duration" value="<?php echo $data['duration']; ?>"  >
                            <label for="phone">Duration</label>
                        </div>

                        <div class="form-group col s12">

                            <input type="text" name="compound_percent" class="form-control"
                                placeholder="Enter Percent" value="<?php echo $data['compound_percent']; ?>" required>
                            <label for="phone">Compounding Percent</label>
                        </div>




                        <div class="form-group col s12">

                            <input type="text" name="infohead1" class="form-control" 
                                placeholder="Enter Heading" value="<?php echo $data['infohead1']; ?>" >
                            <label for="phone">Info Heading 1</label>
                        </div>
                        <div class="form-group col s12">

                            <input type="text" name="info1" class="form-control" 
                                placeholder="Enter info" value="<?php echo $data['info1']; ?>" >
                            <label for="phone">Info Details 1</label>
                        </div>

                        <div class="form-group col s12">

                            <input type="text" name="infohead2" class="form-control" 
                                placeholder="Enter Heading" value="<?php echo $data['infohead2']; ?>" >
                            <label for="phone">Info Heading 2</label>
                        </div>
                        <div class="form-group col s12">

                            <input type="text" name="info2" class="form-control" 
                                placeholder="Enter info" value="<?php echo $data['info2']; ?>" >
                            <label for="phone">Info Details 2</label>
                        </div>

                        <div class="form-group col s12">

                            <input type="text" name="infohead3" class="form-control" 
                                placeholder="Enter Heading" value="<?php echo $data['infohead3']; ?>" >
                            <label for="phone">Info Heading 3</label>
                        </div>
                        <div class="form-group col s12">

                            <input type="text" name="info3" class="form-control" 
                                placeholder="Enter info" value="<?php echo $data['info3']; ?>" >
                            <label for="phone">Info Details 3</label>
                        </div>

                        <div class="form-group col s12">

                            <input type="text" name="infohead4" class="form-control" 
                                placeholder="Enter Heading" value="<?php echo $data['infohead4']; ?>" >
                            <label for="phone">Info Heading 4</label>
                        </div>
                        <div class="form-group col s12">

                            <input type="text" name="info4" class="form-control" 
                                placeholder="Enter info" value="<?php echo $data['info4']; ?>" >
                            <label for="phone">Info Details 4</label>
                        </div>

                        <!--
                        <div class="form-group col s12">

                            <input type="text" name="infohead5" class="form-control" 
                                placeholder="Enter Heading" value="<?php echo $data['info5']; ?>" >
                            <label for="phone">Info Heading 5</label>
                        </div>
                        <div class="form-group col s12">

                            <input type="text" name="info5" class="form-control" 
                                placeholder="Enter info" value="<?php echo $data['info5']; ?>" >
                            <label for="phone">Info Details 5</label>
                        </div>
                        -->




<div class="form-group">
<button type="submit"
class="btn btn-info"
value="" name="edit" >Edit Package</button>
</div>


</form>


                                </div>
                            </div>
                        </div>
                    </div>


                    <?php
                                                        }

                        ?>





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
                                                            <th>Package Name</th>
                                                            <th>Min Amount</th>
                                                            <th>Percentage </th>
                                                            <th>Duration </th>
                                                            <th>Compounding Percent </th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                            <?php

                                                                                
                                                $getinvest = mysqli_query($mysqli, "SELECT * FROM investment_packages");

                                                while($row = mysqli_fetch_assoc($getinvest)){

                                            ?>
                                                            <tr>
                                                                

                                                                <td><?php echo $row['name']; ?></td>
                                                                <td>$<?php echo $row['min_amount']; ?></td>
                                                                <td>$<?php echo $row['percent']; ?></td>
                                                                <td><?php echo $row['duration']; ?> Months</td>
                                                                <td>$<?php echo $row['compound_percent']; ?></td>

                                                                <td><?php if($row['status'] ==0){
                                                    echo "<p class='badge bg-danger' >Deactivated</p>"; 
                                                    }else{
                                                        echo "<p class='badge bg-success' >Active </p>";
                                                    
                                                    } ?></td>

                                                        <td>
                                                            <a class='btn btn-success btn-block' href='javascript:;'  onclick="document.getElementById('edit-<?php echo $row['id']; ?>').style='display:block';" >Edit</a>
                                                                <br/> <br/>
                                                            <a class='btn btn-warning btn-block' href='javascript:;'  onclick="document.getElementById('delete-<?php echo $row['id']; ?>').submit();"  >Delete</a>
                                                        </td>
                                                            </tr>


                                                            <form method="POST" id="delete-<?php echo $row['id']; ?>">

                                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                                                                <input type="hidden" name="delete" />

                                                            </form>





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
                        <span>Copyright ©
                            <?php echo date('Y'); ?>
                            All rights reserved.
                        </span>
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
//when click to creat new package
if(isset($_POST['create'])){
//retrive inputs


$name =  mysqli_real_escape_string($mysqli,$_POST['name']);
$min_amount =  mysqli_real_escape_string($mysqli,$_POST['min_amount']);
$max_amount =  mysqli_real_escape_string($mysqli,$_POST['max_amount']);
$percent = mysqli_real_escape_string($mysqli,$_POST['percent']);
$percent2 = mysqli_real_escape_string($mysqli,$_POST['percent2']);
$duration = mysqli_real_escape_string($mysqli,$_POST['duration']);
$compound_percent = mysqli_real_escape_string($mysqli,$_POST['compound_percent']);
$infohead1 = mysqli_real_escape_string($mysqli,$_POST['infohead1']);
$info1 = mysqli_real_escape_string($mysqli,$_POST['info1']);
$infohead2 = mysqli_real_escape_string($mysqli,$_POST['infohead2']);
$info2 = mysqli_real_escape_string($mysqli,$_POST['info2']);
$infohead3 = mysqli_real_escape_string($mysqli,$_POST['infohead3']);
$info3 = mysqli_real_escape_string($mysqli,$_POST['info3']);
$infohead4 = mysqli_real_escape_string($mysqli,$_POST['infohead4']);
$info4 = mysqli_real_escape_string($mysqli,$_POST['info4']);
$infohead5 = mysqli_real_escape_string($mysqli,$_POST['infohead5']);
$info5 = mysqli_real_escape_string($mysqli,$_POST['info5']);



//add to database
$create = mysqli_query($mysqli,"INSERT INTO investment_packages (name, min_amount, max_amount, percent, percent2, duration, compound_percent, infohead1, info1, infohead2, info2, infohead3, info3, infohead4, info4, infohead5, info5) VALUES('$name', '$min_amount', '$max_amount', '$percent', '$percent2', '$duration',  '$compound_percent', '$infohead1', '$info1', '$infohead2', '$info2', '$infohead3', '$info3', '$infohead4', '$info4', '$infohead5', '$info5' )");

if($create){

    
?>
<script>

Swal.fire({
  icon: 'success',
  title: 'Package Created Successfully',
  text: 'New Package <?php echo $name; ?> has been created.'
})

setTimeout(() => {
    location='investment-package';
}, 3000);
</script>

<?php

}




}
//end of creating a new package


//start of delete
if(isset($_POST['delete'])){

    $id =  mysqli_real_escape_string($mysqli,$_POST['id']);

    $del = mysqli_query($mysqli,"DELETE FROM `investment_packages` WHERE id='$id'");

if($del){

?>
<script>


Swal.fire({
  icon: 'success',
  title: 'Package Delected Successfully',
  text: 'A package  has been Deleted.'
})


setTimeout(() => {
    location='investment-package';
}, 3000);
</script>

<?php

}


}
//end of delete



//start of editing
if(isset($_POST['edit'])){

$id =  mysqli_real_escape_string($mysqli,$_POST['id']);
$name =  mysqli_real_escape_string($mysqli,$_POST['name']);
$min_amount =  mysqli_real_escape_string($mysqli,$_POST['min_amount']);
$max_amount =  mysqli_real_escape_string($mysqli,$_POST['max_amount']);
$percent = mysqli_real_escape_string($mysqli,$_POST['percent']);
$percent2 = mysqli_real_escape_string($mysqli,$_POST['percent2']);
$duration = mysqli_real_escape_string($mysqli,$_POST['duration']);
$compounding = mysqli_real_escape_string($mysqli,$_POST['compounding']);
$compound_percent = mysqli_real_escape_string($mysqli,$_POST['compound_percent']);
$infohead1 = mysqli_real_escape_string($mysqli,$_POST['infohead1']);
$info1 = mysqli_real_escape_string($mysqli,$_POST['info1']);
$infohead2 = mysqli_real_escape_string($mysqli,$_POST['infohead2']);
$info2 = mysqli_real_escape_string($mysqli,$_POST['info2']);
$infohead3 = mysqli_real_escape_string($mysqli,$_POST['infohead3']);
$info3 = mysqli_real_escape_string($mysqli,$_POST['info3']);
$infohead4 = mysqli_real_escape_string($mysqli,$_POST['infohead4']);
$info4 = mysqli_real_escape_string($mysqli,$_POST['info4']);
$infohead5 = mysqli_real_escape_string($mysqli,$_POST['infohead5']);
$info5 = mysqli_real_escape_string($mysqli,$_POST['info5']);


//add to database
$update = mysqli_query($mysqli,"UPDATE `investment_packages`  SET name='$name', min_amount='$min_amount', max_amount='$max_amount', percent='$percent', percent2='$percent2', duration='$duration', compound_percent='$compound_percent', infohead1='$infohead1', info1='$info1', infohead2='$infohead2', info2='$info2' , infohead3='$infohead3', info3='$info3' , infohead4='$infohead4', info4='$info4' , infohead5='$infohead5', info5='$info5'   WHERE id='$id'  ");


$loop = mysqli_query($mysqli,"SELECT * FROM investment where investmentid='$id'   ");

while($row=mysqli_fetch_assoc($loop)){
//

if($row['payout'] < 4){
    
    $new_daily =  $row['amount']*($percent/100);

}else{
    $new_daily =  $row['amount']*($compound_percent/100);

}

mysqli_query($mysqli,"UPDATE investment SET daily_roi='$new_daily' WHERE id='".$row['id']."' ");


}



if($update){

?>
<script>

  Swal.fire({
  icon: 'success',
  title: 'Package Updated Successfully',
  text: '<?php echo $name; ?>  has been Updated.'
})


setTimeout(() => {
    location='investment-package';
}, 3000);
</script>

<?php

}



}

//end of editnig



?>



</body>

</html>