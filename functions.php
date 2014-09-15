<?php
//Supress Errors
ini_set("display_errors", "off");
/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
/** PHPExcel_IOFactory */
include 'PHPExcel/IOFactory.php';
//Read & Upload Excel File
require 'PHPExcel.php';
$uploaddir = 'uploads/';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
  echo "File is valid, and was successfully uploaded.<br><br>";
} else {
   echo "Upload failed";
}

$objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
//End Read Excel File
//Start Retrieve Data From Worksheets & Place Each In Array
$worksheet= $objPHPExcel->setActiveSheetIndex(0);
$cellone = $worksheet->getCellByColumnAndRow(8, 7);
$celltwo = $worksheet->getCellByColumnAndRow(0, 14);
echo "<br>".$cellone." ".$celltwo."<br>";
switch (true){
	case $cellone == '' && $celltwo == ' ':
		one($uploadfile, $uploaddir);
		break;
	case $cellone == '' && $celltwo == 'Sample Type:':
		two($uploadfile, $uploaddir);
		break;
	case $cellone == '' && $celltwo == 'Test Code:':
		three($uploadfile, $uploaddir);
		break;
	case $cellone == 'CAS Project ID:' && $celltwo == 'Test Code:':
		four($uploadfile, $uploaddir);
		break;
	default:
		unlink($uploadfile);
		echo 'However, your spreadsheet does not follow the predefined format and was removed. Please try uploading the file again.';
		break;
}

/**Start Function One**/
function one($uploadfile, $uploaddir){
require"connection.php";
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
$objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
//Start Connect to Database
try {
  $db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';chadbnamerset=utf8', $dbuser, $dbpass, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e) {
  die('Unable to open database connection');
}
//End Connect to Database


//Start Retrieve Data From Worksheets & Place Each In Array

//Sample Worksheet
$worksheet= $objPHPExcel->setActiveSheetIndex(0);
for ($row = 5; $row <= 154; ++ $row) {
		for ($col = 1; $col <= 14; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
            $sample_array[$col][$row]= $cell->getValue();
        }
    }

//TIC Worksheet	
$worksheet= $objPHPExcel->setActiveSheetIndex(1);
$tic_array=array();
$rowstop = true;
$tic_row_count=0;
for ($row = 22; $rowstop; ++$row) {
	$cell = $worksheet->getCellByColumnAndRow(1, $row);
	if ($cell->getValue()){
		++$tic_row_count;
	for ($col = 1; $col <= 12; ++ $col) {
	$cell = $worksheet->getCellByColumnAndRow($col, $row);
		  $tic_array[$col][$row]= $cell->getValue();
	  }
	} else {
		$rowstop = false;
	}
}

//MBlank Worksheet	
$worksheet= $objPHPExcel->setActiveSheetIndex(2);
for ($row = 22; $row <= 154; ++ $row) {
		for ($col = 1; $col <= 14; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
            $mblank_array[$col][$row]= $cell->getValue();
        }
    }
	
	
//MBLANK TIC Worksheet	
$worksheet= $objPHPExcel->setActiveSheetIndex(3);
$mblank_tic_array=array();
$mblank_tic_rowstop = true;
$mblank_tic_row_count=0;
for ($row = 22; $mblank_tic_rowstop; ++$row) {
	$cell = $worksheet->getCellByColumnAndRow(1, $row);
	if ($cell->getValue()){
		++$mblank_tic_row_count;
	for ($col = 1; $col <= 12; ++ $col) {
	$cell = $worksheet->getCellByColumnAndRow($col, $row);
		  $mblank_tic_array[$col][$row]= $cell->getValue();
	  }
	} else {
		$mblank_tic_rowstop = false;
	}
}
	
//Surrogates Worksheet
$worksheet= $objPHPExcel->setActiveSheetIndex(4);
for ($row = 21; $row <= 22; ++ $row) {
		for ($col = 1; $col <= 16; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
            $surrogates_array[$col][$row]= $cell->getValue();
        }
}
//End Retrieve Data From Worksheets & Place Each In Array

//Start Create SQL Query With PDO & Iterate Through The Array, Placing The Retrieved Data Into Database
try {
$db->beginTransaction();
//Insert CLient Info
$sql="INSERT INTO client_info (project_id, client, client_sample_id, client_project_id) VALUES (?, ?, ?, ?)";
$query= $db->prepare($sql);
$query->execute(array($sample_array[10][6], $sample_array[3][5], $sample_array[3][6], $sample_array[3][7]));
//Insert Information
$sqltwo="INSERT INTO information (project_id, sample_id, test_code, instrument_id, sample_type, test_notes, date_collected, date_received, date_analyzed, volume_analyzed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$querytwo= $db->prepare($sqltwo);
$querytwo->execute(array($sample_array[10][6], $sample_array[10][7], $sample_array[3][9], $sample_array[3][10], $sample_array[3][12], $sample_array[3][13], $sample_array[10][9], $sample_array[10][10], $sample_array[10][11], $sample_array[10][12]));
//Insert Sample Worksheet Part 1
$sqlthree="INSERT INTO sample (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$querythree= $db->prepare($sqlthree);
	for ($row = 22; $row <= 46; ++ $row) {
	$querythree->execute(array($sample_array[10][6], $sample_array[1][$row], $sample_array[3][$row], floatval($sample_array[4][$row]), floatval($sample_array[6][$row]), floatval($sample_array[8][$row]), floatval($sample_array[10][$row])));    
	}
//Insert Sample Worksheet Part 2
$sqlfour="INSERT INTO sample (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$queryfour= $db->prepare($sqlfour);
	for ($row = 76; $row <= 100; ++ $row) {
	$queryfour->execute(array($sample_array[10][6], $sample_array[1][$row], $sample_array[3][$row], floatval($sample_array[4][$row]), floatval($sample_array[6][$row]), floatval($sample_array[8][$row]), floatval($sample_array[10][$row])));        
	}
//Insert sample Worksheet Part 3
$sqlfive="INSERT INTO sample (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$queryfive= $db->prepare($sqlfive);
	for ($row = 130; $row <= 154; ++ $row) {
	$queryfive->execute(array($sample_array[10][6], $sample_array[1][$row], $sample_array[3][$row], floatval($sample_array[4][$row]), floatval($sample_array[6][$row]), floatval($sample_array[8][$row]), floatval($sample_array[10][$row])));      
	}
//Insert tic Worksheet
if($tic_row_count>0){
$sqlsix="INSERT INTO tic (project_id, retention, compound, concentration_ugm3, data_qualifier) VALUES (?, ?, ?, ?, ?)";
$querysix= $db->prepare($sqlsix);
for ($row = 22; $row <= ($tic_row_count + 21); ++ $row) {
	$querysix->execute(array($sample_array[10][6], floatval($tic_array[1][$row]), $tic_array[3][$row], floatval($tic_array[8][$row]), $tic_array[12][$row]));    
}
}
//Insert mblank_tic Worksheet
if($mblank_tic_row_count>0){
$sqlseven="INSERT INTO mblank_tic (project_id, retention, compound, concentration_ugm3, data_qualifier) VALUES (?, ?, ?, ?, ?)";
$queryseven= $db->prepare($sqlseven);
for ($row = 22; $row <= ($mblank_tic_row_count + 21); ++ $row) {
	$queryseven->execute(array($sample_array[10][6], floatval($mblank_tic_array[1][$row]), $mblank_tic_array[3][$row], floatval($mblank_tic_array[8][$row]), $mblank_tic_array[12][$row]));    
}
}
//Insert mblank Worksheet Part One
$sqleight="INSERT INTO mblank (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$queryeight= $db->prepare($sqleight);
	for ($row = 22; $row <= 46; ++ $row) {
	$queryeight->execute(array($sample_array[10][6], $mblank_array[1][$row], $mblank_array[3][$row], floatval($mblank_array[4][$row]), floatval($mblank_array[6][$row]), floatval($mblank_array[8][$row]), floatval($mblank_array[10][$row])));        
		}
//Insert mblank Worksheet Part 2
$sqlnine="INSERT INTO mblank (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$querynine= $db->prepare($sqlnine);
	for ($row = 76; $row <= 100; ++ $row) {
	$querynine->execute(array($sample_array[10][6], $mblank_array[1][$row], $mblank_array[3][$row], floatval($mblank_array[4][$row]), floatval($mblank_array[6][$row]), floatval($mblank_array[8][$row]), floatval($mblank_array[10][$row])));        
		}
//Insert mblank Worksheet Part 3
$sqlten="INSERT INTO mblank (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$queryten= $db->prepare($sqlten);
	for ($row = 130; $row <= 154; ++ $row) {
	$queryten->execute(array($sample_array[10][6], $mblank_array[1][$row], $mblank_array[3][$row], floatval($mblank_array[4][$row]), floatval($mblank_array[6][$row]), floatval($mblank_array[8][$row]), floatval($mblank_array[10][$row])));        
		}
//Insert surrogates Worksheet
$sqleleven="INSERT INTO surrogates (project_id, client_sample_id, cas_sample_id, dichlor, toluene, bromo, limits, data_qualifier) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$queryeleven= $db->prepare($sqleleven);
	for ($row = 21; $row <= 22; ++ $row) {
	$queryeleven->execute(array($sample_array[10][6], $surrogates_array[1][$row], $surrogates_array[5][$row], floatval($surrogates_array[6][$row]), floatval($surrogates_array[9][$row]), floatval($surrogates_array[12][$row]), $surrogates_array[15][$row], $surrogates_array[16][$row] ));
}

//Start Ends & Commits To Transactions
$db->commit();
} catch(PDOException $e) {
    //Something went wrong rollback!
    $db->rollBack();
    echo 'Things got nasty.';
}
//End Ends & Commits To Transactions

//Start Convert XLS File To CSV
function convertXLStoCSV($infile,$outfile)
{
    $fileType = PHPExcel_IOFactory::identify($infile);
    $objReader = PHPExcel_IOFactory::createReader($fileType);
 
    $objReader->setReadDataOnly(true);   
    $objPHPExcel = $objReader->load($infile);    
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save($outfile);
}
convertXLStoCSV($uploadfile, $uploaddir.$sample_array[10][6].'.csv');
//End Convert XLS File To CSV

}
/**End Function One**/

/**Start Function Two**/
function two($uploadfile, $uploaddir){
require"connection.php";
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
$objPHPExcel = PHPExcel_IOFactory::load($uploadfile);

//Start Connect to Database
try {
  $db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';chadbnamerset=utf8', $dbuser, $dbpass, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e) {
  die('Unable to open database connection');
}
//End Connect to Database

//Start Retrieve Data From Table Set & Place In Array
//Sample Worksheet
$worksheet= $objPHPExcel->setActiveSheetIndex(0);
for ($row = 6; $row <= 44; ++ $row) {
		for ($col = 1; $col <= 12; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
            $sampletwo_array[$col][$row]= $cell->getValue();
        }
    }
	
//MBlank Worksheet
$worksheet= $objPHPExcel->setActiveSheetIndex(1);
for ($row = 6; $row <= 43; ++ $row) {
		for ($col = 1; $col <= 12; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
            $mblanktwo_array[$col][$row]= $cell->getValue();
        }
    }
//End Retrieve Data From Table Set & Place In Array

//Start Inserting Data Into Database
//Insert client_info
$sql="INSERT INTO client_info (project_id, client, client_sample_id, client_project_id) VALUES (?, ?, ?, ?)";
$query= $db->prepare($sql);
$query->execute(array($sampletwo_array[11][7], $sampletwo_array[3][6], $sampletwo_array[3][7], $sampletwo_array[3][8]));

//Insert Information
$sqltwo="INSERT INTO information (project_id, sample_id, test_code, instrument_id, sample_type, test_notes, date_collected, date_received, date_analyzed, volume_analyzed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$querytwo= $db->prepare($sqltwo);
$querytwo->execute(array($sampletwo_array[11][7], $sampletwo_array[11][8], $sampletwo_array[3][11], $sampletwo_array[3][12], $sampletwo_array[3][14], $sampletwo_array[3][15], $sampletwo_array[11][11], $sampletwo_array[11][13], $sampletwo_array[11][14], $sampletwo_array[11][16]));

//Insert Sample Two
$sqlthree="INSERT INTO sampletwo (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$querythree= $db->prepare($sqlthree);
	for ($row = 24; $row <= 43; ++ $row) {
	$querythree->execute(array($sampletwo_array[11][7], $sampletwo_array[1][$row], $sampletwo_array[3][$row], floatval($sampletwo_array[4][$row]), floatval($sampletwo_array[6][$row]), floatval($sampletwo_array[8][$row]), floatval($sampletwo_array[10][$row]))); 
	}

//Insert MBlank Two
$sqlfour="INSERT INTO mblanktwo (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv, data_qualifier) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$queryfour= $db->prepare($sqlfour);
	for ($row = 24; $row <= 43; ++ $row) {
	$queryfour->execute(array($sampletwo_array[11][7], $mblanktwo_array[1][$row], $mblanktwo_array[3][$row], floatval($mblanktwo_array[4][$row]), floatval($mblanktwo_array[6][$row]), floatval($mblanktwo_array[8][$row]), floatval($mblanktwo_array[10][$row]), $mblanktwo_array[12][$row]));        
		}
//End Inserting Data Into Database

//Start Convert XLS File To CSV
function convertXLStoCSV($infile,$outfile)
{
    $fileType = PHPExcel_IOFactory::identify($infile);
    $objReader = PHPExcel_IOFactory::createReader($fileType);
 
    $objReader->setReadDataOnly(true);   
    $objPHPExcel = $objReader->load($infile);    
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save($outfile);
}
convertXLStoCSV($uploadfile, $uploaddir.$sampletwo_array[11][7].'.csv');
//End Convert XLS File To CSV

/**End Function Two**/
}


/**Start Function Three**/
function three($uploadfile, $uploaddir){
require"connection.php";
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
$objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
//Start Connect to Database
try {
  $db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';chadbnamerset=utf8', $dbuser, $dbpass, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e) {
  die('Unable to open database connection');
}
//End Connect to Database


//Start Retrieve Data From Worksheets & Place Each In Array

//Sample Worksheet
$worksheet= $objPHPExcel->setActiveSheetIndex(0);
for ($row = 5; $row <= 154; ++ $row) {
		for ($col = 1; $col <= 14; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
            $sample_array[$col][$row]= $cell->getValue();
        }
    }

//TIC Worksheet	
$worksheet= $objPHPExcel->setActiveSheetIndex(1);
$tic_array=array();
$rowstop = true;
$tic_row_count=0;
for ($row = 26; $rowstop; ++$row) {
	$cell = $worksheet->getCellByColumnAndRow(1, $row);
	if ($cell->getValue()){
		++$tic_row_count;
	for ($col = 1; $col <= 12; ++ $col) {
	$cell = $worksheet->getCellByColumnAndRow($col, $row);
		  $tic_array[$col][$row]= $cell->getValue();
	  }
	} else {
		$rowstop = false;
	}
}

//MBlank Worksheet	
$worksheet= $objPHPExcel->setActiveSheetIndex(4);
for ($row = 26; $row <= 154; ++ $row) {
		for ($col = 1; $col <= 14; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
            $mblank_array[$col][$row]= $cell->getValue();
        }
    }
	
	
//MBLANK TIC Worksheet	
$worksheet= $objPHPExcel->setActiveSheetIndex(5);
$mblank_tic_array=array();
$mblank_tic_rowstop = true;
$mblank_tic_row_count=0;
for ($row = 26; $mblank_tic_rowstop; ++$row) {
	$cell = $worksheet->getCellByColumnAndRow(1, $row);
	if ($cell->getValue()){
		++$mblank_tic_row_count;
	for ($col = 1; $col <= 12; ++ $col) {
	$cell = $worksheet->getCellByColumnAndRow($col, $row);
		  $mblank_tic_array[$col][$row]= $cell->getValue();
	  }
	} else {
		$mblank_tic_rowstop = false;
	}
}
//End Retrieve Data From Worksheets & Place Each In Array

//Start Create SQL Query With PDO & Iterate Through The Array, Placing The Retrieved Data Into Database
try {
$db->beginTransaction();
//Insert CLient Info
$sql="INSERT INTO client_info (project_id, client, client_sample_id, client_project_id) VALUES (?, ?, ?, ?)";
$query= $db->prepare($sql);
$query->execute(array($sample_array[11][7], $sample_array[3][6], $sample_array[3][7], $sample_array[3][8]));
//Insert Information
$sqltwo="INSERT INTO information (project_id, sample_id, test_code, instrument_id, sample_type, test_notes, date_collected, date_received, date_analyzed, volume_analyzed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$querytwo= $db->prepare($sqltwo);
$querytwo->execute(array($sample_array[11][7], $sample_array[11][8], $sample_array[3][14], $sample_array[3][15], $sample_array[3][17], $sample_array[3][18], $sample_array[11][14], $sample_array[11][15], $sample_array[11][16], $sample_array[11][17]));
//Insert Sample Worksheet Part 1
$sqlthree="INSERT INTO sample (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$querythree= $db->prepare($sqlthree);
	for ($row = 26; $row <= 47; ++ $row) {
	$querythree->execute(array($sample_array[11][7], $sample_array[1][$row], $sample_array[4][$row], floatval($sample_array[7][$row]), floatval($sample_array[8][$row]), floatval($sample_array[10][$row]), floatval($sample_array[11][$row])));    
	}
//Insert Sample Worksheet Part 2
if ($sample_array[1][79] == ' '){
$sqlfour="INSERT INTO sample (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$queryfour= $db->prepare($sqlfour);
	for ($row = 82; $row <= 102; ++ $row) {
	$queryfour->execute(array($sample_array[11][7], $sample_array[1][$row], $sample_array[4][$row], floatval($sample_array[7][$row]), floatval($sample_array[8][$row]), floatval($sample_array[10][$row]), floatval($sample_array[11][$row])));
		}
		}
		else {
			$sqlfour="INSERT INTO sample (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$queryfour= $db->prepare($sqlfour);
	for ($row = 81; $row <= 101; ++ $row) {
	$queryfour->execute(array($sample_array[11][7], $sample_array[1][$row], $sample_array[4][$row], floatval($sample_array[7][$row]), floatval($sample_array[8][$row]), floatval($sample_array[10][$row]), floatval($sample_array[11][$row])));
		}
	}
//Insert tic Worksheet
//******Start Back On TIC*******

if($tic_row_count>0){
$sqlsix="INSERT INTO tic (project_id, retention, compound, concentration_ugm3, data_qualifier) VALUES (?, ?, ?, ?, ?)";
$querysix= $db->prepare($sqlsix);
for ($row = 26; $row <= ($tic_row_count + 25); ++ $row) {
	$querysix->execute(array($sample_array[11][7], floatval($tic_array[1][$row]), $tic_array[3][$row], floatval($tic_array[6][$row]), $tic_array[9][$row]));    
}
}
//Insert mblank_tic Worksheet
if($mblank_tic_row_count>0){
$sqlseven="INSERT INTO mblank_tic (project_id, retention, compound, concentration_ugm3, data_qualifier) VALUES (?, ?, ?, ?, ?)";
$queryseven= $db->prepare($sqlseven);
for ($row = 26; $row <= ($mblank_tic_row_count + 25); ++ $row) {
	$queryseven->execute(array($sample_array[11][7], floatval($mblank_tic_array[1][$row]), $mblank_tic_array[3][$row], floatval($mblank_tic_array[6][$row]), $mblank_tic_array[9][$row]));    
}
}
//Insert mblank Worksheet Part One
$sqleight="INSERT INTO mblank (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$queryeight= $db->prepare($sqleight);
	for ($row = 26; $row <= 47; ++ $row) {
	$queryeight->execute(array($sample_array[11][7], $mblank_array[1][$row], $mblank_array[4][$row], floatval($mblank_array[7][$row]), floatval($mblank_array[8][$row]), floatval($mblank_array[10][$row]), floatval($mblank_array[11][$row])));        
		}
//Insert mblank Worksheet Part 2
$sqlnine="INSERT INTO mblank (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$querynine= $db->prepare($sqlnine);
	for ($row = 82; $row <= 102; ++ $row) {
	$querynine->execute(array($sample_array[11][7], $mblank_array[1][$row], $mblank_array[4][$row], floatval($mblank_array[7][$row]), floatval($mblank_array[8][$row]), floatval($mblank_array[10][$row]), floatval($mblank_array[11][$row])));        
		}

//Start Ends & Commits To Transactions
$db->commit();
} catch(PDOException $e) {
    //Something went wrong rollback!
    $db->rollBack();
    echo 'Things got nasty.';
}
//End Ends & Commits To Transactions

//Start Convert XLS File To CSV
function convertXLStoCSV($infile,$outfile)
{
    $fileType = PHPExcel_IOFactory::identify($infile);
    $objReader = PHPExcel_IOFactory::createReader($fileType);
 
    $objReader->setReadDataOnly(true);   
    $objPHPExcel = $objReader->load($infile);    
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save($outfile);
}
convertXLStoCSV($uploadfile, $uploaddir.$sample_array[11][7].'.csv');
//End Convert XLS File To CSV

}
/**End Function Three**/





/**Start Function Four**/
function four($uploadfile, $uploaddir){
require"connection.php";
set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');
$objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
//Start Connect to Database
try {
  $db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';chadbnamerset=utf8', $dbuser, $dbpass, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e) {
  die('Unable to open database connection');
}
//End Connect to Database


//Start Retrieve Data From Worksheets & Place Each In Array

//Sample Worksheet
$worksheet= $objPHPExcel->setActiveSheetIndex(0);
for ($row = 5; $row <= 154; ++ $row) {
		for ($col = 1; $col <= 14; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
            $sample_array[$col][$row]= $cell->getValue();
        }
    }

//MBlank Worksheet	
$worksheet= $objPHPExcel->setActiveSheetIndex(1);
for ($row = 27; $row <= 154; ++ $row) {
		for ($col = 1; $col <= 12; ++ $col) {
			$cell = $worksheet->getCellByColumnAndRow($col, $row);
            $mblank_array[$col][$row]= $cell->getValue();
        }
    }
	
//End Retrieve Data From Worksheets & Place Each In Array

//Start Create SQL Query With PDO & Iterate Through The Array, Placing The Retrieved Data Into Database
try {
$db->beginTransaction();
//Insert CLient Info
$sql="INSERT INTO client_info (project_id, client, client_sample_id, client_project_id) VALUES (?, ?, ?, ?)";
$query= $db->prepare($sql);
$query->execute(array($sample_array[9][7], $sample_array[3][6], $sample_array[3][7], $sample_array[3][8]));
//Insert Information
$sqltwo="INSERT INTO information (project_id, sample_id, test_code, instrument_id, sample_type, test_notes, date_collected, date_received, date_analyzed, volume_analyzed) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$querytwo= $db->prepare($sqltwo);
$querytwo->execute(array($sample_array[9][7], $sample_array[9][8], $sample_array[3][14], $sample_array[3][15], $sample_array[3][17], $sample_array[3][18], $sample_array[9][14], $sample_array[9][15], $sample_array[9][16], floatval($sample_array[9][17])));
//Insert Sample Worksheet
$sqlthree="INSERT INTO sample (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$querythree= $db->prepare($sqlthree);
	for ($row = 26; $row <= 47; ++ $row) {
	$querythree->execute(array($sample_array[9][7], $sample_array[1][$row], $sample_array[4][$row], floatval($sample_array[7][$row]), floatval($sample_array[8][$row]), floatval($sample_array[10][$row]), floatval($sample_array[11][$row])));    
	}
//Insert mblank Worksheet
$sqleight="INSERT INTO mblank (project_id, cas, compound, result_ugm3, mrl_ugm3, result_ppbv, mrl_ppbv) VALUES (?, ?, ?, ?, ?, ?, ?)";
$queryeight= $db->prepare($sqleight);
	for ($row = 27; $row <= 47; ++ $row) {
	$queryeight->execute(array($sample_array[9][7], $mblank_array[1][$row], $mblank_array[3][$row], floatval($mblank_array[5][$row]), floatval($mblank_array[6][$row]), floatval($mblank_array[8][$row]), floatval($mblank_array[9][$row])));        
		}
		
//Start Ends & Commits To Transactions
$db->commit();
} catch(PDOException $e) {
    //Something went wrong rollback!
    $db->rollBack();
    echo 'Things got nasty.';
}
//End Ends & Commits To Transactions

//Start Convert XLS File To CSV
function convertXLStoCSV($infile,$outfile)
{
    $fileType = PHPExcel_IOFactory::identify($infile);
    $objReader = PHPExcel_IOFactory::createReader($fileType);
 
    $objReader->setReadDataOnly(true);   
    $objPHPExcel = $objReader->load($infile);    
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save($outfile);
}
convertXLStoCSV($uploadfile, $uploaddir.$sample_array[11][7].'.csv');
//End Convert XLS File To CSV

}
/**End Function Four**/

?>
