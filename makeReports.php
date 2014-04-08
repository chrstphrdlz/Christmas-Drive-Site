<?php

/** Include PHPExcel */
require_once '/Additional Libraries/PHPExcel.php';
require_once 'globalClasses.php';

Class MakeReport {



	

	public function makePersonTable() {
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		$stmt = "SELECT * FROM PersonOrdering;";
		$dba = new databaseAcessor();
		$people = $dba->getPeopleOrdering($stmt);

		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'id')
				->setCellValue('B1', 'firstname')
				->setCellValue('C1', 'lastname')
				->setCellValue('D1', 'email')
				->setCellValue('E1', 'primary phone');
				
		$i = 2;
		foreach($people as $person) {
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$i, $person->id)
				->setCellValue('B'.$i, $person->firstName)
				->setCellValue('C'.$i, $person->lastName)
				->setCellValue('D'.$i, $person->email)
				->setCellValue('E'.$i, $person->primaryPhoneNum);
			$i++;
		}

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('People');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Save Excel 2007 file
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		//CREATE FILE NAME
		$fileName = dirname(__FILE__) . '\myFile.xlsx';
		echo "$fileName <br>";

		//SAVE AT FILE NAME
		$objWriter->save($fileName);

	}
	

	public function makeGeneralSignUp() {
	
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Verification')
				->setCellValue('B1', 'Type of House Hold')
				->setCellValue('C1', 'Head of House Hold First Name')
				->setCellValue('D1', 'Head of House Hold Last Name')
				->setCellValue('E1', 'Second Family First Name ')
				->setCellValue('F1', 'Second Family Last Name')
				->setCellValue('G1', 'Third Family First Name ')
				->setCellValue('H1', 'Third Family Last Name')
				->setCellValue('I1', 'Fourth Family First Name ')
				->setCellValue('J1', 'Fourth Family Last Name')
				->setCellValue('K1', 'Fifth Family First Name ')
				->setCellValue('L1', 'Fifth Family Last Name')
				->setCellValue('M1', 'House Number')
				->setCellValue('N1', 'Street')
				->setCellValue('O1', 'City')
				->setCellValue('P1', 'Zip Code')
				->setCellValue('Q1', 'Primary Phone')
				->setCellValue('R1', 'Primary Phone Type')
				->setCellValue('T1', 'Secondary Phone')
				->setCellValue('U1', 'Secondary Phone Type')
				->setCellValue('V1', 'Number of Family Members')
				->setCellValue('W1', 'Language')
				->setCellValue('X1', 'Delivery')
				->setCellValue('Y1', 'Store Selection')
				->setCellValue('Z1', 'Children Under 12')
				->setCellValue('AA1', 'Notes');
				
		
	
		$dba = new databaseAcessor();
		$headsOfHH = $dba->getHeadsOfHouseHold();
		
		$i = 2;
		foreach($headsOfHH as $h) {
			//get address
			$addr = $dba->getAddressByHead($h);
			//get number of family members
			$numFam = $addr->numPeopleInHouse;
			
			//get number of children "orders"
			//$numChild = $dba->getNumChild($addr);
			
			//Set cell values
			$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue('A'. $i, 'Verification')
				//->setCellValue('B'. $i, 'Type of House Hold')
				->setCellValue('C'. $i, $h->firstName)
				->setCellValue('D'. $i, $h->lastName)
				//->setCellValue('E'. $i, 'Second Family First Name ')
				//->setCellValue('F'. $i, 'Second Family Last Name')
				//->setCellValue('G'. $i, 'Third Family First Name ')
				//->setCellValue('H'. $i, 'Third Family Last Name')
				//->setCellValue('I'. $i, 'Fourth Family First Name ')
				//->setCellValue('J'. $i, 'Fourth Family Last Name')
				//->setCellValue('K'. $i, 'Fifth Family First Name ')
				//->setCellValue('L'. $i, 'Fifth Family Last Name')
				->setCellValue('M'. $i, $h->email)
				->setCellValue('N'. $i, $addr->houseNumber)
				->setCellValue('O'. $i, $addr->streetName)
				->setCellValue('P'. $i, $addr->city)
				->setCellValue('Q'. $i, $addr->zipCode)
				->setCellValue('R'. $i, $h->primaryPhoneNum)
				->setCellValue('S'. $i, $h->primaryPhoneId)
				->setCellValue('T'. $i, $h->secondaryPhoneNum)
				->setCellValue('U'. $i, $h->secondaryPhoneId)
				->setCellValue('V'. $i, $addr->numPeopleInHouse)
				//->setCellValue('W'. $i, 'Language')
				->setCellValue('X'. $i, $h->delivery)
				//->setCellValue('Y'. $i, 'Store Selection')
				//->setCellValue('Z'. $i, 'Children Under 12')
				->setCellValue('AA'. $i, $h->notes);
			$i++;
		}
		 
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('General Sign-up');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Save Excel 2007 file
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		//CREATE FILE NAME
		$fileName = dirname(__FILE__) . '\General_Sign_up.xlsx';

		//SAVE AT FILE NAME
		$objWriter->save($fileName);
	}
	
	public function printClothingOrders() {
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Head of Household First Name')
				->setCellValue('B1', 'Head of Household Last Name')
				->setCellValue('C1', 'Parent First Name')
				->setCellValue('D1', 'Parent Last Name')
				->setCellValue('E1', 'Child First Name')
				->setCellValue('F1', 'Child Last Name')
				->setCellValue('G1', 'gender')
				->setCellValue('H1', 'infantOutfitSize')
				->setCellValue('I1', 'infantOutfitSpecial')
				->setCellValue('J1', 'jeansSize')
				->setCellValue('K1', 'jeansSpecial')
				->setCellValue('L1', 'shirtSize')
				->setCellValue('M1', 'shirtSpecial')
				->setCellValue('N1', 'socksSize')
				->setCellValue('O1', 'socksSpecial')
				->setCellValue('P1', 'underwearSize')
				->setCellValue('Q1', 'diaperSize')
				->setCellValue('R1', 'uodSpecial')
				->setCellValue('S1', 'uniIO')
				->setCellValue('T1', 'uniSocks')
				->setCellValue('U1', 'uniDiapers')
				->setCellValue('V1', 'notes');
				
				
		$dba = new databaseAcessor();
		$orders = $dba->getAllClothingOrders();
		$i = 2;
		foreach($orders as $order) {
		
			$parent = $dba->getPersonById($order->orderedById);
			$child = $dba->getChildById($order->orderedForId);
			//$headHH= $dba->getHeadOfHouseholdByPersonId($order->orderedById);

			$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue('A'.$i, $headHH->firstName)
				//->setCellValue('B'.$i, $headHH->lastName)
				->setCellValue('C'.$i, $parent->firstName)
				->setCellValue('D'.$i, $parent->lastName)
				->setCellValue('E'.$i, $child->firstName)
				->setCellValue('F'.$i, $child->lastName)
				->setCellValue('G'.$i, $order->gender)
				->setCellValue('H'.$i, $order->infantOutfitSize)
				->setCellValue('I'.$i, $order->infantOutfitSpecial)
				->setCellValue('J'.$i, $order->jeansSize)
				->setCellValue('K'.$i, $order->jeansSpecial)
				->setCellValue('L'.$i, $order->shirtSize)
				->setCellValue('M'.$i, $order->shirtSpecial)
				->setCellValue('N'.$i, $order->socksSize)
				->setCellValue('O'.$i, $order->socksSpecial)
				->setCellValue('P'.$i, $order->underwearSize)
				->setCellValue('Q'.$i, $order->diaperSize)
				->setCellValue('R'.$i, $order->uodSpecial)
				->setCellValue('S'.$i, $order->uniIO)
				->setCellValue('T'.$i, $order->uniSocks)
				->setCellValue('U'.$i, $order->uniDiapers)
				->setCellValue('V'.$i, $order->notes);
			$i++;
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Clothing Orders');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Save Excel 2007 file
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		//CREATE FILE NAME
		$fileName = dirname(__FILE__) . '\Clothing_Orders.xlsx';

		//SAVE AT FILE NAME
		$objWriter->save($fileName);
	
	
	}
	
}





















