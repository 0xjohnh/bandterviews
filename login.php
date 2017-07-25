<?php
session_start();
$page_title="Login";
include_once('config.php');
require_once 'includes/db-config.php';


$username="";
$password="";

?>


<?php  include('includes/header.php');  ?>
	<body>



		<div style="margin: 4%;">
			<h1 style="text-align: center; padding-bottom: 30px;">Login to Your Account</h1>
			<form style="padding-left: 30%; padding-right: 30%;" method="POST">
				<div class="form-group">
					<label for="username"> Username:</label>
					<input type="username" name = "text_username" class="form-control" id="username">
				</div>
				<div class="form-group">
					<label for="pwd">Password:</label>
					<input type="password" name = "text_password" class="form-control" id="pwd">
				</div>
				<div class="checkbox">
					<label><input type="checkbox">Remember me</label>
				</div>
				<button type="submit" name ="btn-login" class="btn btn-default">Submit</button>
			</form>
			<p style="text-align: center; padding-bottom: 10px;"></br><a href="sign-up.php">Need a username? Click here to sign up</a>!</p>
		</div>



<?php


if(isset($_POST['btn-login'])){

	$username = $_POST['text_username'];
	$password = $_POST['text_password'];

	//Warning if password not filled out
	if($password==""){
		echo "<div class='alert alert-warning' style='text-align:center; margin:2px;''>password required! &nbsp<span class='glyphicon glyphicon-alert'></span></div>";	
	}

} 



if($user->login($username, $password)){
	// $user->redirect('index.php');
	echo "<div class='alert alert-success' style='text-align:center; margin:2px;''>Success &nbsp<span class='glyphicon glyphicon-alert'></span></div>";
    echo "<script> window.location.assign('index.php'); </script>";
	
} else {
	$loginError=true;
}

if($loginError==true && ($username!="" && $password!="") ){
		echo "<div class='alert alert-warning' style='text-align:center; margin:2px;''>Sorry, wrong details. Try again! &nbsp<span class='glyphicon glyphicon-alert'></span></div>";	
	}




?>

	</body>
</html>
