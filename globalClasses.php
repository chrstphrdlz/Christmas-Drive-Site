<?php
	require 'constants.php';
    class databaseAcessor
    {
        private $username = "root";
        private $password = "password";
        private $dbName = "Christmas";
        private $nameQueryString = "SELECT * FROM PersonOrdering p WHERE p.lastName LIKE ?";
        private $addFullPersonString = "INSERT INTO PersonOrdering (firstName, lastName, email, primaryPhoneId, primaryPhoneNum, secondaryPhoneId, secondaryPhoneNum, languageId, delivery, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        private $addLanguageString = "INSERT INTO Language (languageName) VALUES (?);";
        private $languageQueryString = "SELECT * FROM Language  ORDER BY languageName";
        private $addressAddingString = "INSERT IGNORE INTO Addresses (houseNumber, streetName, city, zipCode, numPeopleInHouse) VALUES (?, ?, ?, ?, ?)";
        private $findAddedAddressString = "SELECT * FROM Addresses WHERE (houseNumber, streetName, city, zipCode) = (?, ?, ?, ?) LIMIT 1";
        private $getColumnMaxStringLength = "SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE (table_name, COLUMN_NAME) = (?, ?)";
        private $addPersonToHousehold = "INSERT INTO peopleInHouse (pid,aid) VALUES (?,?)";
        private $getAllTables = "SHOW TABLES FROM Christmas";
        private $addHeadOfHousehold = "INSERT IGNORE INTO HeadOfHousehold (hid, pid) VALUES (?,?)";
        private $addChildString = "INSERT INTO Children (firstName, lastName, parentFirst, parentLast, age, childID, childIDNo, primaryGaurdianId) VALUES (?,?,?,?,?,?,?,?)";
        private $addClothingOrderString = "INSERT INTO ClothingOrders (orderedById, orderedForId, gender, infantOutfitSize, infantOutfitSpecial, jeansSize, jeansSpecial, shirtSize, shirtSpecial, socksSize, socksSpecial, underwearSize, diaperSize, uodSpecial, uniIO, uniSocks, uniDiapers, notes, checklist, completedBy) VALUES (?, ?, ? ,? ,? ,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        private $addPhoneType = "INSERT INTO PhoneType (description) VALUES (?)";
        private $addChristmasFoodOrderString = "INSERT INTO ChristmasFoodOrder (aid, needDelievery) VALUES (?, ?)";
		private $addThanksGivingFoodOrderString = "INSERT INTO ThanksgivingFoodOrder (aid, needDelievery) VALUES (?, ?)";
        private $getAllClothingOrdersInAddress = "SELECT co.coid FROM ClothingOrders co, peopleInHouse pih WHERE co.orderedById = pih.pid AND pih.aid = (?)";
        private $getNumberOfPeopleInFoodOrder = "SELECT ad.numPeopleInHouse FROM Addresses ad, ChristmasFoodOrder fo WHERE fo.aid = (?) AND ad.aid = fo.aid";
        private $getClothingOrderForPerson = "SELECT co.coid FROM ClothingOrders co WHERE co.orderedById = (?)";
        private $getMemberRoleWithUsernameAndPassword = "SELECT role FROM Members WHERE (username, password) = (?, ?)";
        private $hostname;
        private $mySqlConnection;
        private $preparedStatement;
        private $maxStringLengths;
        
        public function __construct()
        {
            $this->hostname = gethostname();
            
            //manually storing max string lengths (performance will be affected)
            $this->maxStringLengths = array();
            
            $this->maxStringLengths["Addresses"] = array();
            $this->maxStringLengths["Addresses"][0] = 10;
            $this->maxStringLengths["Addresses"][1] = 30;
            $this->maxStringLengths["Addresses"][2] = 20;
            $this->maxStringLengths["Addresses"][3] = 12;
            
            $this->maxStringLengths["Childeren"] = array();
            $this->maxStringLengths["Childeren"]["firstName"] = 20;
            $this->maxStringLengths["Childeren"]["lastName"] = 20;
            
            $this->maxStringLengths["PersonOrdering"] = array();
            $this->maxStringLengths["PersonOrdering"]["lastName"] = 20;
            $this->maxStringLengths["PersonOrdering"]["firstName"] = 20;
            
            $this->maxStringLengths["Language"] = array();
            $this->maxStringLengths["Language"]["languageName"] = 20;
            
            $this->maxStringLengths["PhoneType"] = array();
            $this->maxStringLengths["PhoneType"]["description"] = 20;
            
            
            //performance issues with so many queries at construction
           /* $queryTablesResult = $this->makeStatementSelect($this->getAllTables);
            $tables = array();
            foreach($queryTablesResult as $resultRow)
            {
                $tables[] = $resultRow->Tables_in_Christmas;
            }
            print_r($tables);
           */
            //print_r($tables);
            
        }
        
        //returns array of classes as result
        private function makeStatementSelect($statementString, $params = array())
        {
            $connectingName = 'mysql:host='. $this->hostname . ';dbname=' . $this->dbName;
            try
            {
                $mySqlConnection = new PDO($connectingName,$this->username,$this->password); 
            }
            catch(PDOException $e)
            {
                echo "failed ";
                echo $e->getMessage();
            }
            
            if($mySqlConnection->connect_error)
            {
                die('Could not connect: ' . $mySqlConnection->connect_error);
            }
            
            $preparedStatement = $mySqlConnection->prepare($statementString, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            
            if(empty($params))
            {
                $returnSucess = $preparedStatement->execute();
            }
            else
            {
                $returnSucess = $preparedStatement->execute($params);
            }
            
            if(!$returnSucess)
            {
                echo "<br>select failed for ";
                print_r($params);
                echo " for query: " . $statementString . "<br>";
                print_r($mySqlConnection->errorInfo());
                echo "<br>";
            }
            
            $result = $preparedStatement->fetchAll(PDO::FETCH_CLASS);
            //print_r($result);
            return $result;
        }
        
        //returns id of inserted element
        private function makeStatementInsert($statementString, $params)
        {
            $connectingName = 'mysql:host='. $this->hostname . ';dbname=' . $this->dbName;
            
            try
            {
                $mySqlConnection = new PDO($connectingName,$this->username,$this->password); 
            }
            catch(PDOException $e)
            {
                echo "failed ";
                echo $e->getMessage();
            }
            
            if($mySqlConnection->connect_error)
            {
                die('Could not connect: ' . $mySqlConnection->connect_error);
            }
            
            $preparedStatement = $mySqlConnection->prepare($statementString);
            
            if(empty($params))
            {
                $returnSucess = $preparedStatement->execute();
            }
            else
            {
                $returnSucess = $preparedStatement->execute($params);
            }
            
            if(!$returnSucess)
            {
                echo "<br>insert failed for ";
                print_r($params);
                print_r($mySqlConnection->errorInfo());
                echo "<br>";
            }
            
            $result = $mySqlConnection->lastInsertId();
            
            return $result;
        }
        
        //returns the aprameters with the lengths trimmed
        private function trimParameters($params, $tableName)
        {
            $trimmingLengths = $this->maxStringLengths[$tableName];
            print_r($trimmingLengths);
            foreach($params as $key=>$val)
            {
                //echo "key is : ".$key."<br>";
                //echo "val is : ".$val."<br>";
                //echo "trimmingLengths = ".$trimmingLengths[$key]."<br>";
                //echo "strlen(val) = ".strlen($val)."<br>";
                //echo is_string($val);
                //print_r($trimmingLengths[$key]);
                
                if(is_string($val) && strlen($val) > $trimmingLengths[$key])
                {
                    //echo strlen($val) . "\n";
                    //echo $trimmingLengths[$key];
                    //echo "trimming ".$params[$key]." to ".substr($val, 0, $trimmingLengths[$key])."<br>";
                    $params[$key] = substr($val, 0, $trimmingLengths[$key]);
                }
            }
            return $params;
        }
        
        private function endStatement()
        {
            $this->mySqlConnection = null;
        }
        
        public function searchForName($nameToSearchFor)
        {
            $stringMatching = $nameToSearchFor . "%";
            $returner = $this->makeStatementSelect($this->nameQueryString, array($stringMatching));
            $this->endStatement();
            return $returner;
        } 
        
        public function addPerson($params)
        {
            $returner = $this->makeStatementInsert($this->addFullPersonString, $params);
            $this->endStatement();
            return $returner;
        }
        
        public function getLanguages()
        {
            $returner = $this->makeStatementSelect($this->languageQueryString, array());
            $this->endStatement();
            return $returner;
        }
        
        public function addLanguage($language)
        {
            $returner = $this->makeStatementInsert($this->addLanguageString, array($language));
            $this->endStatement();
            return $returner;
        }
        
        public function getAddresses($params)
        {
            $params = $this->trimParameters($params,"Addresses");
            print_r($params);
            $returner = $this->makeStatementSelect($this->findAddedAddressString, $params);
            $this->endStatement();
            return $returner;
        }
        
        public function addClothingOrder($params)
        {
            $returner = $this->makeStatementInsert($this->addClothingOrderString, $params);
            $this->endStatement();
            return $returner;
        }
        
        public function addChild($params)
        {
            $returner = $this->makeStatementInsert($this->addChildString, $params);
            $this->endStatement();
            return $returner;
        }
        
        //does not work when addresses duplicates
        public function addAddress($params)
        {
            print_r($params);
            $returner = $this->makeStatementInsert($this->addressAddingString, $params);
            $this->endStatement();
            echo "the returner is " . $returner;
            if($returner == 0)
            {
                echo "now slicing";
                print_r(array_slice($params,0 ,-1 ));
                $address = $this->getAddresses(array_slice($params,0 ,-1 ))[0];
                return $address->aid;
            }
            else
            {
                echo "found id " . $returner;
            }
            return $returner;
        }
        
        public function addPersonToHouse($person, $address)
        {
            $this->makeStatementInsert($this->addPersonToHousehold, array($person, $address));
        }
        
        public function addHeadOfHouseHoldIfNotSet($houseId, $personId)
        {
            $this->makeStatementInsert($this->addHeadOfHousehold, array($houseId, $personId));
        }
        
        public function addPhoneType($phoneType)
        {
            return $this->makeStatementInsert($this->addPhoneType, array($phoneType));
        }
        
        public function getClothingOrdersInHouse($addressKey)
        {
            return $this->makeStatementSelect($this->getAllClothingOrdersInAddress, array($addressKey));
        }
        
        public function getNumPeopleInFoodOrder($addressKey)
        {
            return $this->makeStatementSelect($this->getNumberOfPeopleInFoodOrder, array($addressKey));
        }
        
        public function addChristmasFoodOrder($addressKey, $needDelivery)
        {
            $this->makeStatementInsert($this->addChristmasFoodOrderString, array($addressKey, $needDelivery));
        }
		
		public function addThanksgivingFoodOrder($addressKey, $needDelivery)
        {
            $this->makeStatementInsert($this->addThanksGivingFoodOrderString, array($addressKey, $needDelivery));
        }
        
        public function getClothingOrderForPerson($personId)
        {
            return $this->makeStatementSelect($this->getClothingOrderForPerson , array($personId));
        }
        
        public function getUserRole($username, $passwordHash)
        {
            return $this->makeStatementSelect($this->getMemberRoleWithUsernameAndPassword, array($username, $passwordHash));
        }
        
        public function verify_username_and_pass($params) 
        {
				
			$stmt = "SELECT * FROM Members WHERE username = ? AND password = ? LIMIT 1;";
			$result = $this->makeStatementSelect($stmt, $params);
			print_r($result);
			return $result; 
		}		

		public function validate_new_user($username, $email, $access_code) 
		{
		    include("constants.php");
			$query_1 = 'SELECT username FROM Members WHERE username = ?';
			$query_2 = 'SELECT username FROM Members WHERE email = ?';
			
			$errors = array( 'username' => null, 'email' => null, 'access_code' => null);	//associative array containing all possible errors we could find with initial values set to null.
			$found_error = false;															// a boolean variable that tells us if we found an error
			
			//check username is unique
			$result = $this->makeStatementSelect( $query_1, array($username) );
			if( !empty($result) ) {
			    echo "That user name already exists.<br>";
				$errors['username'] = 'That user name already exists.';
				$found_error = true;
			}
			
			//check email is unique
			$result = $this->makeStatementSelect( $query_2, array($email) );
			if( !empty($result) ) {
			    echo "This email already has an account.<br>";
				$errors['email'] =  'This email already has an account.';
				$found_error = true;
			}
			
			//check access_code is valid
			if( $access_code != ADMIN_KEY && $access_code != VOL_KEY) {
			    echo "Invalid access code.<br>";
				$errors['access_code'] =  'Invalid access code.';
				$found_error = true;
			}
			
			//if an error was found return false and print the error
			if($found_error) {
				return false;
			}
			else
			{
			    echo "No error found<br>";
			}
			
			if($true)
			{
			    echo "Works<br>";
			}
			
			//otherwise just return true
			return true;
		}
		
		public function insert_user($params) 
		{
			$stmt = "INSERT INTO Members (fname, lname, initials, email, username, password, role) VALUES( ?, ?, ?, ?, ?, ?, ?);";
			$result = $this->makeStatementInsert($stmt, $params);

			return $result;
		}
		
		public function getAllClothingOrders() {
			$stmt = "SELECT * FROM Clothingorders;";
			$result = $this->makeStatementSelect($stmt);
			return $result;
		}
		
		public function getHeadsOfHouseHold() {
			$stmt = "SELECT * FROM PersonOrdering P, HeadOfHousehold H WHERE P.id = H.pid;";
			$result = $this->makeStatementSelect($stmt);
			return $result;
		}
		
		public function getAddressByHead($h) {
			$stmt = "SELECT hid FROM HeadOfHouseHold H, Addresses A WHERE H.pid = ?;";
			$houseId = $this->makeStatementSelect($stmt, array($h->id));
			$stmt = "SELECT * FROM Addresses A WHERE A.aid = ?;";
			$result = $this->makeStatementSelect($stmt, array($houseId[0]->hid));
			return $result[0];
		
		}
		
		public function getHeadOfHouseholdByPersonId($id) {
			
		}
		
		public function getPersonById($id) {
			$stmt = "SELECT * FROM PersonOrdering P WHERE P.id = ?;";
			$result = $this->makeStatementSelect($stmt, array($id));
			return $result[0];
		}
		
		public function getHeadOfHouseHoldById($id) {
			$stmt = "SELECT * FROM HeadOfHousehold H WHERE H.pid = ?;";
			$result = $this->makeStatementSelect($stmt, array($id));
			return $result[0];
		}
		
		public function getChildById($id) {
			$stmt = "SELECT * FROM Children C WHERE C.cid = ?;";
			$result = $this->makeStatementSelect($stmt, array($id));
			return $result[0];
		}
		
		public function getClothingOrderById($id) {
			$stmt = "SELECT * FROM ClothingOrders CO WHERE CO.coid = ?;";
			$result = $this->makeStatementSelect($stmt, array($id));
			return $result[0];
		}
		
		public function getAddressById($id) {
			$stmt = "SELECT * FROM Addresses A WHERE A.aid = ?;";
			$result = $this->makeStatementSelect($stmt, array($id));
			return $result[0];
		}
		
		public function getPeopleByAddressId($id) {
			$stmt = "SELECT * FROM peopleInHouse PH, PersonOrdering P WHERE PH.pid = P.id? AND PH.aid = ;";
			$result = $this->makeStatementSelect($stmt, array($id));
			return $result;
		
		}
		
		public function getAddressId($num, $steet, $city, $zip) {
			$stmt = "SELECT aid FROM Addresses A WHERE A.houseNumber = ? AND A.streetName = $street AND A.city = ? AND A.zipCode = ? ;";
			$result = $this->makeStatementSelect($stmt, array($num, $steet, $city, $zip));
			return $result[0]->id;
		}
    }
?>