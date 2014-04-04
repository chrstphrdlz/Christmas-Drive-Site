<html>
    <body>
        <?php
            require 'globalClasses.php';
            $dba = new databaseAcessor();
            
            $firstName = $_POST["childFirstName"];
            $lastName = $_POST["childLastName"];
            $age = $_POST["age"];
            
            $childArray = array($firstName, $lastName, $age);
            
            if(!$dba->addChild($childArray))
            {
            	echo "Failed to add child";
            }

            $gender = $_POST["sexOfChild"];
            $infantOutfitSize = "";
			$infantOutfitSpecial = "";
			$jeansSize = "";
			$jeansSpecial = "";
			$shirtSize = "";
			$shirtSpecial = "";
			$socksSize = "";
			$socksSpecial = "";
			$underwearSize = "";
			$diaperSize = "";
			$uodSpecial = "";
			$uniIO = "";
			$uniSocks = "";
            $uniDiapers = "";
            
            
            if($gender == "boy")
            {
                if($_POST["boysIOJ"] == 1)
                {
                	if($_POST["boysIO"] == 1)
                	{   
                		$infantOutfitSize = $_POST["boysInfantSize"];   
                	}
                	else
                	{
                		$infantOutfitSpecial = $_POST["boysIOSpecial"];   
                	}
                }
                else
                {
                	if($_POST["boysJeans"] == 1)
                	{   
                		$jeansSize = $_POST["boysJeansSize"];   
                	}
                	else
                	{
                		$jeansSpecial = $_POST["boysJeansSpecial"];   
                	}
                	if($_POST["boysShirt"] == 1)
                	{   
                		$shirtSize = $_POST["boysShirtSize"];   
                	}
                	else
                	{
                		$shirtSpecial = $_POST["boysShirtSpecial"];   
                	}
                }
                
				if($_POST["boysSocks"] == 1)
				{   
					$socksSize = $_POST["boysSocksSize"];   
				}
				else
				{
					$socksSpecial = $_POST["boysSocksSpecial"];   
				}
				
				if($_POST["boysUOD"] == 1)
				{   
					$underwearSize = $_POST["boysUnderwearSize"];   
				}
				else if($_POST["boysUOD"] == 2)
				{
					$diaperSize = $_POST["boysDiaperSize"];
				}
				else
				{
					$uodSpecial = $_POST["boysUODSpecial"];   
				}
                
            } 
            else if($gender == "girl")
            {
                if($_POST["girlsIOJ"] == 1)
                {
                	if($_POST["girlsIO"] == 1)
                	{   
                		$infantOutfitSize = $_POST["girlsInfantSize"];   
                	}
                	else
                	{
                		$infantOutfitSpecial = $_POST["girlsIOSpecial"];   
                	}
                }
                else
                {
                	if($_POST["girlsJeans"] == 1)
                	{   
                		$jeansSize = $_POST["girlsJeansSize"];   
                	}
                	else
                	{
                		$jeansSpecial = $_POST["girlsJeansSpecial"];   
                	}
                	if($_POST["girlsShirt"] == 1)
                	{   
                		$shirtSize = $_POST["girlsShirtSize"];   
                	}
                	else
                	{
                		$shirtSpecial = $_POST["girlsShirtSpecial"];   
                	}
                }
                	
				if($_POST["girlsSocks"] == 1)
				{   
					$socksSize = $_POST["girlsSocksSize"];   
				}
				else
				{
					$socksSpecial = $_POST["girlsSocksSpecial"];   
				}
				
				if($_POST["girlsUOD"] == 1)
				{   
					$underwearSize = $_POST["girlsUnderwearSize"];   
				}
				else if($_POST["girlsUOD"] == 2)
				{
					$diaperSize = $_POST["girlsDiaperSize"];
				}
				else
				{
					$uodSpecial = $_POST["girlsUODSpecial"];   
				}
            }
            else
            {
                $uniIO = $_POST["unisexIOSize"];
                $uniSocks = $_POST["unisexSocksSize"];
                $uniDiapers = $_POST["unisexDiapersSize"];
            }
            
            $arrayOfValues = array($gender, $infantOutfitSize, $infantOutfitSpecial, $jeansSize, $jeansSpecial, $shirtSize, $shirtSpecial, $socksSize, $socksSpecial, $underwearSize, $diaperSize, $uodSpecial, $uniIO, $uniSocks, $uniDiapers);
            
            if(!$dba->addClothingOrder($arrayOfValues))
            {
            	echo "Failed to add order";
            }
            else
            {
                if($_POST["otherChild"] == 2)
                {
                    echo "success";
                    echo "value is ";
                    header("Location: christmasDriveForm.php");
                }
                else
                {
                    echo "success";
                    echo "value is ";
                    header("Location: clothingForm.html");
                }
            }
        ?>
    </body>
</html>