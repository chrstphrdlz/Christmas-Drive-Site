<?php

require 'mysql.php';

Class Membership {

	function validate_user($username, $pwd) {
	
		$mysql = new Mysql();																//create new Mysql object
		$ensure_credentials = $mysql->verify_username_and_pass( $username, md5($pwd) );		//validate users credentials against DB
		
		//if users credentials were successfully validated
		if( !empty($ensure_credentials) ) {
			$_SESSION['status'] = 'authorized';		//set session status to authorized
			$_SESSION['type'] = $ensure_credentials;
			
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
		$mysql = new Mysql();
		
		//make sure username, email, and access code are correct
		$response = $mysql->validate_new_user($user_info['username'], $user_info['email'], $user_info['access_code']);
		
		//if username, email and access code are correct insert user into DB and return true
		if( $response === true ) {
			$response = $mysql->insert_user($user_info);
			return true;
		} else {
			//otherwise return an array of errors now held in $response
			return $response;
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