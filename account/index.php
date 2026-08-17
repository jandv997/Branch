<?php
session_start();


//check if session id is set if it is redirect to dashboard
if(isset($_SESSION['id']) and (isset($_SESSION['2fa']) and $_SESSION['2fa'] == "yes")){
	
	header("location:dashboard");
}else{
     

    if(isset($_SESSION['2fa'])){

        if($_SESSION['2fa'] == "pending"){
        header("location:authenticate");
    }

    if($_SESSION['2fa'] == "no"){
        header("location:dashboard");
    }

    }

}


include('connection.php');

?>
<?php
$authPageTitle = 'Quantum Scalp | Sign In';
$authTitle = 'Sign in';
$authSubtitle = 'Access your Q-Core dashboard.';
include('inc/auth-head.php');
include('inc/auth-open.php');
?>
<form method="POST">
    <div class="form-group">
        <label>Email</label>
        <input class="form-control" required placeholder="you@example.com" type="email" name="email">
    </div>
    <div class="form-group">
        <label>Password</label>
        <div class="input-group auth-pass-inputgroup">
            <input class="form-control" id="login-password" placeholder="••••••••" name="password" required type="password">
            <button class="btn btn-light shadow-none ms-0 qs-pass-toggle" type="button" data-qs-toggle-pass="login-password" aria-label="Show password"><i class="fe fe-eye"></i></button>
        </div>
    </div>
    <button type="submit" name="login" class="btn btn-primary btn-block">Sign In</button>
</form>
<p class="qs-auth-foot" style="margin-top:12px"><a href="forgot">Forgot password?</a></p>
<p class="qs-auth-foot">New here? <a href="register">Create an account</a></p>
<?php
include('inc/auth-close.php');
include('inc/auth-scripts.php');
?>








		<?php


if(isset($_POST['login'])){


//retrive the inut from user
$email = mysqli_real_escape_string($mysqli,$_POST['email']);
$password = mysqli_real_escape_string($mysqli,$_POST['password']);


$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (filter_var($email, FILTER_VALIDATE_EMAIL)){


//check if email exist aready
$check_email = mysqli_query($mysqli,"SELECT id, `password`, userstatus, `status`, `2fa`, `email_otp` FROM users WHERE email='$email'");

$row = mysqli_fetch_assoc($check_email);

//if one it exist proceed to login
if(mysqli_num_rows($check_email) > 0){

    //check if password is correct
    if(password_verify($password, $row['password']) ){

        //the user has verified his account
        if($row['userstatus'] ==1){

            //check if admin has approved the account
            // if($row['status'] ==1){


                //attempt to enforce password reset and 2fa activiattion??
                if($row['email_otp'] =="Quantumgroup"){


                  $otp = mt_rand(111222, 912219);

                  //send otp to email


                  $update = mysqli_query($mysqli,"UPDATE `users` SET `email_otp`='$otp' WHERE email='$email' ");






                  $_SESSION['pendingid']=$row['id']; 
                  //$_SESSION['2fa']='no'; 

                   ?>

                <script>
                location = 'enforce-2fa';
                </script>

                <?php




                }else{



                //check if tow factor authetication is enabled
                if($row['2fa']==1){

                    $_SESSION['id']=$row['id']; 
                    $_SESSION['2fa']='pending'; 

                     ?>

    <script>
    location = 'authenticate';
    </script>

    <?php


                }else{
                //its not enabled
                //redirect to admin 

                   $_SESSION['id']=$row['id']; 
                   $_SESSION['2fa']='no'; 
                ?>

    <script>
    location = 'dashboard';
    </script>

    <?php



                }




              }



            
    //     }else{
    //             //account not yet approved by admin

    //             ?>
    // <script>
   

	// notif({
	// 	msg: "<b>Pending Approval Account</b> <br/> Your uploaded ID-Card is pending approval by Admin",
	// 	width: 250,
	// 	position: "center",
	// 	type: "error",
	// 	fade: true
	// });
    // </script>

    // <?php  





    //         }





        }else{

      //acc not yet verified by user    
?>
    <script>
    
	notif({
		msg: "<b>Unverified Account</b> <br/> You have not yet verified your account, please check your email for verification link",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});
    </script>

    <?php  




        }





    }else{


?>
    <script>
   


	notif({
		msg: "<b>Incorrect Password</b> <br/> The password supplied is wrong",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});
    </script>

    <?php




    }




}else{
//its zero email does not exit show warning

?>
    <script>
  

	notif({
		msg: "<b>Unregistered Email</b> <br/> This email is not yet registered with an account!",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});


    </script>

    <?php


}



}else{

  //its zero email does not exit show warning
  
  ?>
    <script>
  


	notif({
		msg: "<b>Not a Valid Email Address</b> <br/> This is not a valid email Address",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});

    </script>

    <?php
  
  
  }





  }




?>



	</body>
</html>