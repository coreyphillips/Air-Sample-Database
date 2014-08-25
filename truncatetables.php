<?php
require('connection.php');
$tables=array("client_info", "information", "mblank", "mblanktwo", "mblank_tic", "sample", "sampletwo", "surrogates","tic");

foreach($tables as $table){
  try {
	$db->beginTransaction();
	$sql = 'TRUNCATE table '.$table;
	$query = $db->prepare($sql);
	$query->execute();
	$db->commit();
	  echo "Success!<br>";
	}
	 catch(PDOException $e) {
		$db->rollBack();
		echo 'Tables Failed To Truncate.';
  }	
}
?>