<html>
    <body>
        <?php
            require 'globalClasses.php';
            session_start();
            
            $attemptedOrderType = $_SESSION["attemptedOrderType"];
            $errorType = $_SESSION["errorType"];
            //echo $attemptedOrderType . "<br>";
            //echo $errorType . "<br>";
            
            if($attemptedOrderType == "food")
            {
                if($errorType == "previouslyMadeClothingOrder")
                {
                    echo "There is a clothing order already made for someone at the address given, would you like to allow for a food order as well?<br>";
                }
                else
                {
                    echo "Soemone at this address already made a food order, would you like to increase the total number of people for it?<br>";
                    //need to add logic to not allow anything
                }
            
            }
            else if($attemptedOrderType == "clothes")
            {
                if($errorType == "previouslyMadeFoodOrder")
                {
                    echo "There is a food order already made for the address given, would you like to allow for a clothing order as well?<br>";
                }
                else
                {
                    echo "You have already added a clothing order (this should never happen)<br>";
                }
            }
            else
            {
                echo "Nothing to do here";
            }
            
        ?>
        
      <form name="addressBar" action="adminOverride.php" method="POST">
         Administer username:<br>
            <input type="text" id="userName" name="userName"><br>
            
         Password:<br>
            <input type="text" id="password" name="password"><br>
            <input type="submit" value="Login">
        </form>
        
        
    </body>
</html>