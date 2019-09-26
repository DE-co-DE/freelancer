<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['clientid']))
 {
	 // get id value
	 $id = $_POST['id'];
	 $clientid = $_POST['clientid'];
	 
	   $Insert = DB::getInstance()->insert('trash', array(
		   'messageid' => $id,
		   'userid' => $clientid,
		   'delete_remove' => 0,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
		
		//Update
		$Update = DB::getInstance()->update('message',[
		   'delete_remove' => 1
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