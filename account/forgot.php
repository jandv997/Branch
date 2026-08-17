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
$authPageTitle = 'Quantum Scalp | Forgot Password';
$authTitle = 'Reset access';
$authSubtitle = 'Recover your Q-Core account.';
include('inc/auth-head.php');
include('inc/auth-open.php');
?>
<?php 
										if(!isset($_GET['token'])){
									?>
														 <div class="main-signin-header">
															 <h2>Forgot Password!</h2>
															 <h4>Please Enter Your Email</h4>
															 <form method="POST">
																 <div class="form-group">
																	 <label>Email</label> <input class="form-control" name="email" placeholder="Enter your email" type="email">
																 </div>
																 <button type="submit" name="forgot" class="btn btn-primary btn-block">Send</button>
															 </form>
														 </div>



										<?php
									}
	
									if(isset($_GET['token'])){
	
								
								?>






													<div class="main-signin-header">
															 <h2>Reset Password!</h2>
															 <h4>Please New Password</h4>
															 <form method="POST">
																 <div class="form-group">
																	 <label>Password</label> <input class="form-control" name="password" placeholder="Enter your Password" type="password">
																 </div>

                                                                 <div class="form-group">
																	 <label>Confirm Password</label> <input class="form-control" name="confirm" placeholder="Enter your Password" type="password">
																 </div>
																 <button type="submit"  name="reset" class="btn btn-primary btn-block">Change Password</button>
															 </form>
														 </div>







<?php  } ?>

<p class="qs-auth-foot">Forget it, <a href="index">Send me back</a> to the sign in screen.</p>
<?php
include('inc/auth-close.php');
include('inc/auth-scripts.php');
?>
<?php  
	include_once("email-handler.php");
	
if(isset($_POST['forgot'])){

$email = $_POST['email'];

$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (filter_var($email, FILTER_VALIDATE_EMAIL)){

//check if email is registered
$check= mysqli_query($mysqli,"SELECT id FROM users WHERE email='$email' ");

if(mysqli_num_rows($check) > 0 ){

    $token = "QS-pass-".mt_rand(138998, 999998);

    $update = mysqli_query($mysqli,"UPDATE `users` SET `forgot`='$token' WHERE email='$email' ");


	sendForgotPasswordEmail(
		$email,

		'https://quantumscalp.io/account/forgot?email=' .($email) . '&token='.$token
	);
   




?>
    <script>
 
    
    notif({
		msg: "<b>Forgot Password Requested</b> <br/> Token sent successfully",
		width: 250,
		position: "center",
		type: "success",
		fade: true
	});
    </script>

    <?php


}else{

    ?>
    <script>
  

    notif({
		msg: "<b>Email is not Register</b> <br/> This is not a valid email Address!",
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
		msg: "<b>Not a Valid Email Address</b> <br/> This is not a valid email Address!",
		width: 250,
		position: "center",
		type: "error",
		fade: true
	});
      </script>
  
      <?php
    
    
    }
  

}




if(isset($_POST['reset'])){

           $password = $_POST['password']; 
           $confirm = $_POST['confirm']; 
           $email = $_GET['email'];
           
           if($password == $confirm){

                $hashpassword = password_hash($password, PASSWORD_DEFAULT);

                $update = mysqli_query($mysqli,"UPDATE `users` SET `password`='$hashpassword' WHERE email='$email' ");

                if($update){

                    ?>
    <script>
   


    notif({
		msg: "<b>Password reset successful</b> <br/> Password has been reset successfully",
		width: 250,
		position: "center",
		type: "success",
		fade: true
	});



    </script>

    <?php

                }


           }else{

            ?>
    <script>
  
    notif({
		msg: "<b>Password do not match</b> <br/> Password you entered do not match!",
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