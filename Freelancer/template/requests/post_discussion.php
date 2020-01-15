<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['clientid']) && !empty($_POST['message']) && !empty($_POST['freelancerid']))
 {
	 // get id value
	 $clientid = $_POST['clientid'];
	 $freelancerid = $_POST['freelancerid'];
	 $message = $_POST['message'];
	 
	   $Insert = DB::getInstance()->insert('message', array(
		   'user_from' => $freelancerid,
		   'user_to' => $clientid,
		   'message' => $message,
		   'opened' => 0,
		   'active' => 1,
		   'bugid' => 0,
		   'disc' => 1,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
	 if ($Insert) {
		 $messages = getLastDiscussionF($freelancerid, $clientid);
		 echo $messages;
		 unset($messages);
	 }		 
	
 }
 
?>