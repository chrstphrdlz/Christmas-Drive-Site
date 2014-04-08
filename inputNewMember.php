<?php

require_once 'membership.php';          //indicate required files

 

 

//Check that user has submitted the form

if( !empty($_POST) ) {

 
echo "here";
        //add new user to DB

        $result = $membership->add_new_user($_POST);

        

        if( $result === true ) {

                //redirect to login page
echo "there";
//                header("location: login.php");

        }

}

 

//Verify that username is not already taken

 

//Verify that email has not already been used

 

 

?>
