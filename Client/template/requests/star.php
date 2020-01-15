<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['userid']) && !empty($_POST['state']))
 {
	 // get id value
	 $messageid = $_POST['id'];
	 $userid = $_POST['userid'];
	 $state = $_POST['state'];
	 
	 $actions = star($messageid, $userid, $state);
	 echo $actions;
	
 }
 
?>