<?php

require 'globalClasses.php';

Class Membership {

	//Validate user for log-in
	function validate_user($username, $pwd) {
	
		$mysql = new databaseAcessor();
		
		$creds = array($username, md5($pwd));
		
		$ensure_credentials = $mysql->verify_username_and_pass( $creds );		//validate users credentials against DB
		
		//print_r( $ensure_credentials );
		if($ensure_credentials) {
			$role = $ensure_credentials[0];
			print_r( $role );
		}
		
		//if users credentials were successfully validated
		if( !empty($ensure_credentials) ) {
			$_SESSION['status'] = 'authorized';		//set session status to authorized
			$_SESSION['type'] = $role->role;
			
			if( $_SESSION['type'] === 'ADMIN') {
				header("location: admin.php");			//set this to location we want to take user
			} else {
				header("location: index.php");			//set this to location we want to take user
			}
			
		} else {
			return "Please enter a correct username and password.";		//if username and pwd were not found
		}
	}
	
	//attempts to add a new user to the DB. Returns true if successful and false if not successful.
	function add_new_user($user_info) {
		
		//connect to the database
		$mysql = new databaseAcessor();
		
		//make sure username, email, and access code are correct
		$errors = $mysql->validate_new_user($user_info['username'], $user_info['email'], $user_info['access_code']);
		
		//if username, email and access code are NOT correct return the errors
		if( !empty($errors) ) {
			return $errors;
		}
		
		//set appropriate user type
		$role = $user_info['access_code'];
		if( $role === ADMIN_KEY) {
			$role = 'ADMIN';
		} else {
			$role = 'VOL';
		}
		
		//create params array
		$firstName = $user_info['firstname'];
		$lastName = $user_info['lastname'];
		$initials = $user_info['initials'];
		$email = $user_info['email'];
		$username = $user_info['username'];
		$password = md5($user_info['password']);

		$user = array( $firstName, $lastName, $initials, $email, $username, $password, $role );
		
		//if username, email and access code are correct insert user into DB and return true
		$response = $mysql->insert_user($user);
		
		if( $response == true) {
			return true;
		} else {
			echo "failed to insert";
		}
		
	}
	
	//function to log a user out of a session
	function log_user_out() {
		
		//if user had an open session log them out and delete cookie.
		if( isset($_SESSION['status']) ) {
			unset($_SESSION['status']);
		}
			
		if( isset($_COOKIE[session_name()]) ) {
			setcookie(session_name(), '', time() - 1000000);
			session_destroy();
		}
	}
	
	//function that confirms that a user has already logged in
	function confirm_member() {
		session_start();
		
		//if the users session status is not set to authorized redirect the user to the login page
		if($_SESSION['status'] != 'authorized') {
			header("location: login.php");
		}
	}
}