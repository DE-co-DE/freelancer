<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['clientid']) && !empty($_POST['message']) && !empty($_POST['freelancerid']) && !empty($_POST['bugid']))
 {
	 // get id value
	 $clientid = $_POST['clientid'];
	 $freelancerid = $_POST['freelancerid'];
	 $bugid = $_POST['bugid'];
	 $message = $_POST['message'];
	 
	   $Insert = DB::getInstance()->insert('message', array(
		   'user_from' => $freelancerid,
		   'user_to' => $clientid,
		   'message' => $message,
		   'opened' => 0,
		   'active' => 1,
		   'bugid' => $bugid,
		   'disc' => 2,
		   'date_added' => date('Y-m-d H:i:s')
	    ));	
	 if ($Insert) {
		 $messages = getLastCommentF($freelancerid, $clientid, $bugid);
		 echo $messages;
		 unset($messages);
	 }		 
	
 }
 
?>