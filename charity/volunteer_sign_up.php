<?php
require_once 'classes/membership.php';		//indicate required files
$membership = new Membership();				//create new Membership object


//Check that user has submitted the form
if( !empty($_POST) ) {

	//add new user to DB
	$result = $membership->add_new_user($_POST);
	
	if( $result === true ) {
		//redirect to login page
		header("location: login.php");
	}
}

//Verify that username is not already taken

//Verify that email has not already been used


?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="CSS/volunteer_sign_up.css" />
	<title>Volunteer Sign-up</title>
	
</head>
<body>
	<div>
		<h1>Volunteer Sign-up</h1>
		<form action="" method="post">
			<fieldset>
				<h3>Personal Information:</h3>
				<ul>
					<li>
						<label for="firstname">First Name:</label>
						<input type="firstname" name="firstname" id="firstname" required="required"> 
					</li>
					<li>
						<label for="lastname">Last Name:</label>
						<input type="lastname" name="lastname" id="lastname" required="required">
						
					</li>
					<li>
						<label for="initials">Initials:</label>
						<input type="initials" name="initials" id="initials" required="required">
					</li>
					<li>
						<label for="email">Email:</label>
						<input type="text" name="email" id="email" value="johnsmith@gmail.com" required="required">
						<?php if( isset($result) && $result !== true && $result['email'] ) echo '<h5 class="alert"> ' . $result['email'] . '</h5>'; ?>
					</li>
				</ul>
			</fieldset>
			<fieldset>
				<h3>Login Information:</h3>
				<h4>Choose your username and password.</h4>
				<ul>
					<li>
						<label for="username">Username:</label>
						<input type="username" name="username" id="username" required="required">
						<?php if( isset($result) && $result !== true && $result['username'] ) echo '<h5 class="alert"> ' . $result['username'] . '</h5>'; ?>
					</li>
					<li>
						<label for="password">Password:</label>
						<input type="password" name="password" id="password" required="required">
					</li>
					<li>
						<label for="access_code">Access Code:</label>
						<input type="access_code" name="access_code" id="access_code" required="required">
						<?php if( isset($result) && $result !== true && $result['access_code'] ) echo '<h5 class="alert"> ' . $result['access_code'] . '</h5>'; ?>
					</li>
					<li>
						<input type="submit" value="Submit">
					</li>
				</ul>
			</fieldset>
		</form>
		<a href="login.php">Return to Login</a>
	</div>
</body>
</html>