<?php

session_start();							//create a session
require_once 'classes/membership.php';		//indicate required files
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
}
?>



<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="CSS/login.css" />
	<title>Login Page</title>
</head>
<body>
	<div class="login_container">
		<form method="post" action="">
		<fieldset>
			<h1>Login</h1>
			<ul>
				<li>
					<label for="username">Username</label>
					<input type="text" name="username" required="required" />
				</li>
				
				<li>
					<label for="pwd">Password</label>
					<input type="password" name="pwd" required="required" />
				</li>
				
				<li>
					<input type="submit" id="submit" value="login" name="submit" />
				</li>
			</ul>
			<?php 
			if( isset($response) ) {
				echo '<h4 class="active">' . $response . '</h4>';	
			}
			?>
			<h3 class="sign_up"><a href="volunteer_sign_up.php">Sign-up</a></h3>
		</fieldset>
		</form>
		
	</div>
</body>
</html>