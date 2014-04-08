<?php
echo "test";
require_once 'membership.php';
echo "test1";

require_once 'makeReports.php';
echo "test2";
$membership = new Membership();

//confirm that user has logged in before displaying page(if they have not redirect to login page)
$membership->confirm_member();

$report = new MakeReport();

if( isset($_POST['generalSignup']) ) {
	$report->makeGeneralSignUp();
}

if( isset($_POST['clothingOrders']) ) {
	$report->printClothingOrders();
}





//$report->makePersonTable();
//$report->makeClothingShoppingList();


?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="CSS/login.css" />
	<title>Admin Page</title>
</head>
<body>
	<div>
		<h3>Congradulations! You have successfully logged-in to the Admin page.</h3>
		<a href="login.php?status=loggedout">Log out</a>
		
		
		<form action="" method="post" >
			<h4>Select forms to export:</h4>
			<input id = "generalSignup" type="checkbox" name="generalSignup" value="1">General Sign-up<br>
			<input id = "clothingOrders" type="checkbox" name="clothingOrders" value="1">Clothing Orders<br><br>
			<input type="submit" id="submit" value="makeForms" name="submit" />
		</form>
	</div>
</body>
</html>