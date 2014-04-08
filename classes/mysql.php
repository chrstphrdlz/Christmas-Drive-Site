<?php

require 'includes/constants.php';


class Mysql {

	private $conn;
	
	function __construct() {
	
		try {
			//Establish Connection with DB
			$this->conn = new PDO('mysql:host=localhost;dbname=membership', DB_USER, DB_PASSWORD);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e) {
			echo 'ERROR: ' . $e->getMessage();
			die('There was a problem connecting to the database.');
		}
	}

	//verifies user login
	function verify_username_and_pass($username, $pwd) {
	
		$query = "SELECT *
				FROM members
				WHERE username = :un AND password = :pwd
				LIMIT 1";
				
		if($stmt = $this->conn->prepare($query) ) {
			$stmt->bindParam(':un', $username, PDO::PARAM_STR);
			$stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
			$stmt->execute();
			
			$result = $stmt->fetchAll();
			
			if($result) {
				$result = $result[0];		//result is a multi array, need to select the 0th array
				return $result['role'];		//return the role of user (ie admin or vol)
			}
		}		

		return false;
	}
	
	//ensures that the chosen username and email has not already been used and that a valid access code has been entered
	function validate_new_user($username, $email, $access_code) {
		
		$query_1 = 'SELECT username FROM members WHERE username = :username';
		$query_2 = 'SELECT username FROM members WHERE email = :email';
		
		$errors = array( 'username' => null, 'email' => null, 'access_code' => null);	//associative array containing all possible errors we could find with initial values set to null.
		$found_error = false;															// a boolean variable that tells us if we found an error
		
		//check username is unique
		$result = $this->query( $query_1, array( ':username' => $username) );
		if( !empty($result) ) {
			$errors['username'] = 'That user name already exists.';
			$found_error = true;
		}
		
		//check email is unique
		$result = $this->query( $query_2, array( ':email' => $email) );
		if( !empty($result) ) {
			$errors['email'] =  'This email already has an account.';
			$found_error = true;
		}
		
		//check access_code is valid
		if( $access_code != ADMIN_KEY && $access_code != VOL_KEY) {
			$errors['access_code'] =  'Invalid access code.';
			$found_error = true;
		}
		
		//if an error was found return the array of errors
		if($found_error) {
			return $errors;
		}
		
		//otherwise just return true
		return true;
	}
	
	function insert_user($user_info) {
	
		$stmt = $this->conn->prepare("INSERT INTO members VALUES(null, :firstname, :lastname, :initials, :email, :username, :password, :user_type)");
		$stmt->bindParam(':firstname', $user_info['firstname'], PDO::PARAM_STR);
		$stmt->bindParam(':lastname', $user_info['lastname'], PDO::PARAM_STR);
		$stmt->bindParam(':initials', $user_info['initials'], PDO::PARAM_STR);
		$stmt->bindParam(':username', $user_info['username'], PDO::PARAM_STR);
		$stmt->bindParam(':password', md5($user_info['password']), PDO::PARAM_STR);
		
		//set appropriate user type
		if( $user_info['access_code'] === ADMIN_KEY) {
			$stmt->bindParam(':user_type', 'ADMIN', PDO::PARAM_STR);
		} else {
			$val = array( 'user_type' => 'VOL' );
			$stmt->bindParam(':user_type', $val['user_type'], PDO::PARAM_STR);
		}
		
		$stmt->bindParam(':email', $user_info['email'], PDO::PARAM_STR);
		$stmt->execute();
	
	}
	
	function query($query, $bindings = null) {
		
		$stmt = $this->conn->prepare($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		if( empty($bindings) ) {
			$stmt->execute();
		} else {
			$stmt->execute($bindings);
		}
	
		return $stmt->fetchAll();
	}

}













