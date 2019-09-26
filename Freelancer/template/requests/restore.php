<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['freelancerid']))
 {
	 // get id value
	 $id = $_POST['id'];
	 $freelancerid = $_POST['freelancerid'];
	 	
		// delete the entry
		$query = DB::getInstance()->delete('trash', ["AND" => ["messageid" => $id, "userid" => $freelancerid]]);	
	  
		//Update
		$Update = DB::getInstance()->update('message',[
		   'delete_remove' => 0
		],[
		    'id' => $id
		  ]);		
		 
	 
	 if ($Update) {
		 echo 1;
	 } else {
		 echo 0;
	 }		 
	
 }
 
?>