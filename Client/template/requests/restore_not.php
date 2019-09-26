<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['clientid']))
 {
	 // get id value
	 $id = $_POST['id'];
	 $clientid = $_POST['clientid'];
	 
	// delete the entry
	$query = DB::getInstance()->delete('trash', ["AND" => ["messageid" => $id, "userid" => $clientid]]);
			 
	 
	 if ($query) {
		 echo 1;
	 } else {
		 echo 0;
	 }		 
	
 }
 
?>