<html>
    <body>
        <?php
            require 'globalClasses.php';
            $adminUsername = $_POST["userName"];
            $adminPassword = $_POST["password"];
            $hash = md5($adminPassword);
            echo $hash;
            
            $dba = new databaseAcessor();
            $users = $dba->getUserRole($adminUsername, md5($adminPassword));
            echo "here";
            print_r($users);
            $adminLoginValid = false;
            
            if(count($users) == 0)
            {
                echo "No one found with those credentials";
            }
            else
            {
                echo "Found a user<br>";
                $role = $users[0]->role;
                echo "after<br>";
                echo $role . "<br>";
                if($role == "ADMIN")
                {
                    echo "Is admin<br>";
                    
                    $adminLoginValid = true;
                }
            }
            
            if($adminLoginValid)
            {
                session_start();
                $attemptedOrderType = $_SESSION["attemptedOrderType"];
                $errorType = $_SESSION["errorType"];
                //echo $attemptedOrderType . "<br>";
                //echo $errorType . "<br>";
                
                if($attemptedOrderType == "food")
                {
                    if($errorType == "previouslyMadeClothingOrder")
                    {
                        echo "Adding a food order to this house with a pre-existing clothing order <br>";
                        header('Location: clothingForm.php');
                    }
                    else
                    {
                        echo "Changing the number of people in this food order<br>";
                        //need to add logic to not allow anything
                        
                    }
                
                }
                else if($attemptedOrderType == "clothes")
                {
                    if($errorType == "previouslyMadeFoodOrder")
                    {
                        echo "Allowing for a food order after a clothing order has been made<br>";
                        echo $_SESSION["addressKey"] . "<br>";
                        echo $_SESSION["needDelivery"];
                        $dba->addChristmasFoodOrder($_SESSION["addressKey"],$_SESSION["needDelivery"]);
                        //header('Location: christmasDriveForm.php');
                    }
                    else
                    {
                        echo "This should have been impossible to have been to<br>";
                        echo "type is ". $errorType . "<br>";
                        print_r($_SESSION);
                    }
                }
                else
                {
                    echo "Nothing to do here";
                }
                
            }
        ?>
    </body>
</html>