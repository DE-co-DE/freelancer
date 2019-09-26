<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['freelancerid']))
 {
	 // get id value
	 $id = $_POST['id'];
	 $freelancerid = $_POST['freelancerid'];
	 
	   $Insert = DB::getInstance()->insert('trash', array(
		   'messageid' => $id,
		   'userid' => $freelancerid,
		   'delete_remove' => 0,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
			 
	 
	 if ($Insert) {
		 echo 1;
	 } else {
		 echo 0;
	 }		 
	
 }
 
?>