<?php
error_reporting(E_ALL);
include('includes/dbconnection.php');
require "vendor/autoload.php";
  
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
if(isset($_POST['submit']))
  {
$email=$_POST['email'];
  $sql ="SELECT email FROM users WHERE email=:email";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
if($query -> rowCount() > 0)
{
$con="update users set password=:newpassword where email=:email";
$chngpwd1 = $dbh->prepare($con);
$chngpwd1-> bindParam(':email', $email, PDO::PARAM_STR);
$chngpwd1-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
$chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
$chngpwd1->execute();
  $mail = new PHPMailer(true);
  
  // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
  
  $mail->isSMTP();
  $mail->SMTPAuth = true;
  
  $mail->Host = "smtp.gmail.com";
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;
  
  $mail->Username = "eazysurvey123@gmail.com";
  $mail->Password = "cqlprqrgtttssphq";
  
  $mail->setFrom($email, "EazySurvey | Survey Management System");
  $mail->addAddress($_POST["email"]);
  
  $mail->Subject = "Your password has changed successfully";
  $mail->Body = "Dear $email,

Your password has been changed successfully. If you did not change your password, please contact us immediately through the below email.Thank You

Your Sincerely
EazySurvey Team 
easysurvey123@gmail.com";
  
$mail->send();
echo "<script>alert('You have changed your password Successfully');</script>";
}
else {
echo "<script>alert('Email id or Mobile no is invalid');</script>"; 
}
}

?>
<!doctype html>
<html>
<head>
<title>EazySurvey | Reset Password</title>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--bootstrap-->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!--coustom css-->
<link href="css/style.css" rel="stylesheet" type="text/css"/>
<!--script-->
<script src="js/jquery-1.11.0.min.js"></script>
<!-- js -->
<script src="js/bootstrap.js"></script>
<!-- /js -->
<!--fonts-->
<link href='//fonts.googleapis.com/css?family=Open+Sans:300,300italic,400italic,400,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<!--/fonts-->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<!--script-->
<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},900);
				});
			});
</script>
<!--/script-->
</head>
	<body>
<!--header-->
		<?php include_once('includes/header.php');?>
<!-- Top Navigation -->
<div class="banner banner5">
	<div class="container">
	<h2>Reset Password</h2>
	</div>
</div>
<!--header-->
		<!-- contact -->
		<div class="contact">
			<!-- container -->
			<div class="container">
				<div class="contact-info">
					<h3 class="c-text">Please reset your password here!!</h3>
				</div>
				<h4>RECOVER PASSWORD</h4>
                <h6 class="font-weight-light">Enter your email address to reset password!</h6>
                <form class="pt-3" id="login" method="post" name="login">
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" placeholder="Email Address" required="true" name="email">
                  </div>
                  <div class="form-group">
                   
                    <input class="form-control form-control-lg" type="password" name="newpassword" placeholder="New Password" required="true"/>
                  </div>
                  <div class="form-group">
                    
                   <input class="form-control form-control-lg" type="password" name="confirmpassword" placeholder="Confirm Password" required="true" />
                  </div>
                  <div class="form-group">
                  <div class="mt-3">
                    <button class="btn btn-success btn-block loginbtn" name="submit" type="submit">Change Password</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                  </div>
                  <div class="mb-2">
                    <a href="homepage.php" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="icon-social-home mr-2"></i>Back Home </a>
                  </div>
				  </form>
			
			</div>
			<!-- //container -->
		</div>
		<!-- //contact -->
<?php include_once('includes/footer.php');?>
<!--/copy-rights-->
	</body>
</html>
