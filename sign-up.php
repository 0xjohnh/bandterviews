<?php 
session_start();
$page_title="Sign Up";
include_once('config.php');
include('includes/header.php'); ?>
	<body>

	<?php 

		require_once 'includes/db-config.php';
		if($user->is_loggedin()!=""){
			$user->redirect('home.php');
		} 


		$emailError = $usernameError = $passwordError = $passwordConfError = $matchError = false ;
		$email = $username = $password = $passwordConf = "";
		$warningState=false;


		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["email"])){
				$emailError = true;
			} else {
				$email = test_input($_POST["email"]);
			}

			if (empty($_POST["username"])){
				$usernameError = true;
			} else {
				$username = test_input($_POST["username"]);
			}

			if (empty($_POST["password"])){
				$passwordError = true;
			} else {

				$password = test_input($_POST["password"]);

				if (empty($_POST["passwordConf"])){

					$passwordConfError = true;

				} else {

					$passwordConf = test_input($_POST["passwordConf"]);

					//if here, both pwd forms not empty, so check to see if match=true
					if( !($password===$passwordConf)){
						$matchError=true;
					}

				}
			}
		}

		function test_input($stringdata){
			$stringdata = trim($stringdata); //trims away spaces
			$stringdata = stripslashes($stringdata); //strips out slashes
			$stringdata = htmlspecialchars($stringdata); //prevents attacks
			return $stringdata;
		}

		

	?>
		<div style="margin: 4%;">
			<h1 style="text-align: center; padding-bottom: 10px;">
				Sign Up!
			</h1>



			<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" style="padding-left: 30%; padding-right: 30%;">
				<div class="form-group">
					<label for="email">Email address:</label>
					<input type="email" class="form-control" id="email" name="email" value="<?php echo $email?>">
				</div>
				<div class="form-group">
					<label for="user"> Create Username:</label>
					<input type="username" class="form-control" id="username" name="username" value="<?php echo $username?>">
				</div>
				<div class="form-group">
					<label for="pwd">Password:</label>
					<input type="password" class="form-control" id="pwd" name="password" value="<?php echo $password?>">
				</div>
				<div class="form-group">
					<label for="pwd_confirm">Confirm Password:</label>
					<input type="password" class="form-control" id="passwordConf" name="passwordConf" value="<?php echo $passwordConf?>">
				</div>
				<button type="submit" name="submit-btn" class="btn btn-default">Submit</button>
			</form>

			<p style="text-align: center; padding-bottom: 10px;"></br><a href="login.php">Already registered? Click here to login</a>!</p>
		</div>




			
			<?php 

				if(isset($_GET['joined'])){
							echo "<div class='alert alert-success' style='text-align: center; margin:2px; font-size: 20px;'><a href='login.php'>You're registered! Click here to login! </a><span class='glyphicon glyphicon-ok'></span></div>";

						}

				/*********************WARNINGS*****************************************/
				if($emailError==true){ 
					echo "<div class='alert alert-warning' style='text-align:center; margin:10px;''>email address is required &nbsp<span class='glyphicon glyphicon-alert'></span></div>";

					$warningState=true;
				}
				if($usernameError==true){
					echo "<div class='alert alert-warning' style='text-align: center; margin:10px;''>username required &nbsp<span class='glyphicon glyphicon-alert'></span> </div>";
					$warningState=true;
				}
				if($passwordError==true){
					echo "<div class='alert alert-warning' style='text-align: center; margin:10px;''>password required &nbsp<span class='glyphicon glyphicon-alert'></span></div>";
					$warningState=true;
				}
				if($passwordConfError==true){
					echo "<div class='alert alert-warning' style='text-align: center; margin:10px;''>must confirm password &nbsp<span class='glyphicon glyphicon-alert'></span></div>";
					$warningState=true;
				}
				if($matchError==true){
					echo "<div class='alert alert-danger' style='text-align: center; margin:10px;''>passwords do not match &nbsp<span class='glyphicon glyphicon-alert'></span></div>";
					$warningState=true;
				}
				/*******************************WARNINGS************************************/
			?>


			<?php 

				//if no warnings are engaged and the submit button has been pressed
				if($warningState==false && isset($_POST['submit-btn']))
				{
	
					try
					{
						$stmt = $DB_con->prepare("SELECT user_username, user_email FROM User WHERE user_username=:username OR user_email=:useremail");
						$stmt->execute(array(':username'=>$username, ':useremail'=>$email));
						$row=$stmt->fetch(PDO::FETCH_ASSOC);

						//if the username exists already
						if($row['user_username']==$username)
						{ 
							echo "<div class='alert alert-danger' style='text-align: center; margin:10px;''>sorry, that username is already taken &nbsp<span class='glyphicon glyphicon-alert'></span></div>";

						} 

						if ($row['user_email']==$email)//if the email exists already
						{ 
							echo "<div class='alert alert-danger' style='text-align: center; margin-bottom:10px;''>sorry, that email is already taken &nbsp<span class='glyphicon glyphicon-alert'></span></div>";
						} else 
							{
								if($user->register($username, $email, $password))
								{
									// $user->redirect('sign-up.php?joined');
									// echo "<div class='alert alert-success' style='text-align:center; margin:2px;''>Success &nbsp<span class='glyphicon glyphicon-alert'></span></div>";
    								echo "<script> window.location.assign('sign-up.php?joined'); </script>";
								}
							}					
					}

					catch(PDOException $e)
					{
						echo $e->getMessage();
					}
				}
			?>
	
	</body>
</html>


