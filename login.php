<?php
session_start();							//create a session
require_once 'membership.php';		        //indicate required files
$membership = new Membership();				//create new Membership object

//Check if user has logged out
if( isset($_GET['status']) && $_GET['status'] == 'loggedout') {
	$membership->log_user_out();
	header("location: login.php");
}

//Check that user has entered and submitted a correct username and password
if( $_POST && !empty($_POST['username']) && !empty($_POST['pwd']) ) {
	//validate users credentials against DB and if successful login and set session
	$response = $membership->validate_user($_POST['username'], $_POST['pwd']);
	//header("location: login.php");
}
?>


<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="CSS/login.css" />
	<title>Login Page</title>
</head>
<body id="bodyBackground">
    <h3 class="sign_up">Not a volunteer yet? <a href="volunteer_sign_up.php">Sign-up!</a><span class="shortTab"></span></h3>
	<div class="login_container">
		<form method="post" action="">
		<fieldset>
			<h1 style="text-align: center;">Login</h1>
			<ul>
					<input style="text-align: center; margin-left: 25px;" type="text"  placeholder="Username" name="username" required="required" tabindex="1">
				
					<input style="text-align: center; margin-left: 25px;" type="password" placeholder="password" name="pwd" required="required" tabindex="2">	
			</ul>
		
					<p style="text-align: center;"><input type="submit" id="submit" value="sign in" name="submit" align="middle"/></p> 
			<?php 
			if( isset($response) ) {
				echo '<h4 class="active">' . $response . '</h4>';	
			}
			?>
		</fieldset>
		</form>
	</div>
</body>
</html>