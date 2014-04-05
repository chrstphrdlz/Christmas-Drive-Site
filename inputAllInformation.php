<html>
    <body>
        <?php
            require 'globalClasses.php';
            $dba = new databaseAcessor();
            
            $firstName = $_POST["firstName"];
            $lastName = $_POST["lastName"];
            $email = $_POST["email"];
            $primaryPhoneId = $_POST["primaryPhone"];
            
            //will add new id if there is a new phone type (not publically displayed for privacy reasone)
            if($primaryPhoneId == 4)
            {
                $primaryPhoneId = $dba->addPhoneType($_POST["primaryPhoneType"]);
                echo $primaryPhoneId;
            }
            $primaryPhoneNum = $_POST["primaryPhoneNum"];
            $secondaryPhoneId = $_POST["secondaryPhone"];
            
            //will add new id if there is a new phone type (not publically displayed for privacy reasone)
            if($secondaryPhoneId == 4)
            {
                echo $_POST["secondaryPhoneType"];
                $secondaryPhoneId = $dba->addPhoneType($_POST["secondaryPhoneType"]);
                echo "<br>thing<br>" . $secondaryPhoneId . "<br";
            }
            $secondaryPhoneNum = $_POST["secondaryPhoneNum"];
            $languageId = $_POST["languagesSpoken"];
            $notes = $_POST["notes"];
            
            /*
                add if we want a food or clothing order and do a check
            */
            
            //if inputting new language
            if($languageId == "other")
            {
                $otherLanguageToAdd = $_POST['otherLanguage'];
                $languageId = $dba->addLanguage($otherLanguageToAdd);
            }
            
            
            
            //add person
            $arrayOfValues = array($firstName, $lastName, $email, $primaryPhoneId, $primaryPhoneNum, $secondaryPhoneId, $secondaryPhoneNum, $languageId, $notes);
            if(!$languageId)
            {
                echo "Failed to add language";
            }
            
            $personId = $dba->addPerson($arrayOfValues);
            
            if(!$personId)
            {
                echo "Failed to add person";
            }
            
            $params = array();
            if($_POST["addressType"] == 'apartment')
            {
                $params[] = "Bldg ".$_POST["buildingNumber"]." Apt ".$_POST["apartmentNumber"]." ".$_POST["street_number"]; 
            }
            else
            {
                $params[] = $_POST["street_number"];
            }
            $params[] = $_POST["route"];
            $params[] = $_POST["locality"];
            $params[] = $_POST["postal_code"];
            $addressKey = $dba->addAddress($params);
            $something = $dba->addPersonToHouse($personId,$addressKey);
            //insert ignore into head of household
            $dba->addHeadOfHouseHoldIfNotSet($addressKey, $personId);
            $numPeople = $_POST["numberOfFamilyMembers"];
            $needDelivery = $_POST["deleivery"] == "Yes" ? true:false;
            echo $_POST["foodOrClothing"] . "<br>";
            $orderingFood = $_POST["foodOrClothing"] == "food" ? true:false;
            echo "Key for address is " . $addressKey . "<br>";
            
            //require that no food order has been placed for the house
            //if so, make sure that the number of people in the house is correct

            session_start();
            $_SESSION["attemptedOrderType"] = $orderingFood ? "food" : "clothes";
                    
            if($orderingFood)
            {
                echo "Food order was selected" . "<br>";
                //if clothing order has not been placed for someone in the house
                if(count($dba->getClothingOrdersInHouse($addressKey))==0)
                {
                    echo "No clothing orders Made for this address" . "<br>";
                    echo "The number of people at this house is currently " . $dba->getNumPeopleInFoodOrder($addressKey) . "<br>";
                    //if no previous food order, insert ignore into food order
                    $numFoodOrdersForAddress = count($dba->getNumPeopleInFoodOrder($addressKey));
                    if($numFoodOrdersForAddress==0)
                    {
                        echo "No food order found for this address, adding food order" . "<br>";
                        $dba->addFoodOrder($addressKey, $numPeople, $needDelivery);
                    }
                    else
                    {
                        echo "Food order not added! food order has been made on the address " . $addressKey . "<br>";
                    }
                }
                else
                {
                    echo "Clothing order found for person " . $personId . "<br>";
                    //redirect to login to allow for food and clothing
                }
            }
            else
            //is a clothing order
            {
                echo "Ordering clothing<br>";
                $foodOrdersForAddress = $dba->getNumPeopleInFoodOrder($addressKey);
                echo "here";
                $clothingOrderForPerson = $dba->getClothingOrderForPerson($personId);
                echo "here";
                $isFoodOrderForHouse = count($foodOrdersForAddress) > 0 ? true:false;
                $isClothingOrderForPerson = count($clothingOrderForPerson) > 0 ? true:false;
                if($isFoodOrderForHouse)
                {
                    echo "There is already someone who made a food order for this house<br>";
                }
                else if($isClothingOrderForPerson)
                {
                    echo "This person already made a clothing order<br>";
                }
                else
                {
                    echo "Adding clothing order<br>";
                    $_SESSION["personOrderingClothesId"] = $personId;
                    echo "about to send<br>";
                    header("Location: clothingForm.php");
                }
            }
            
           /* if($personId && $languageId && $addressKey)
            {
                echo "success";
                echo "value is " . $something;
                
                if($_POST["foodOrClothing"] == "food")
                {
                    header("Location: christmasDriveForm.php");
                }
                else
                {
                    header("Location: clothingForm.html");
                }
            }

                header("Location: christmasDriveForm.php");
            }*/
        ?>
    <body>
</html>