<?php
require_once 'classes/membership.php';
$membership = new Membership();

//confirm that user has logged in before displaying page(if they have not redirect to login page)
$membership->confirm_member();

?>



<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="login.css" />
	<title>User Page</title>
</head>
<body>
	<div>
		<h3>Congradulations! You have successfully logged-in.</h3>
		<a href="login.php?status=loggedout">Log out</a>
	</div>
</body>
</html>