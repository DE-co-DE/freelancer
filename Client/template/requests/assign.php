<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['userid']) && !empty($_POST['state']))
 {
	 // get id value
	 $id = $_POST['id'];
	 $jobid = $_POST['jobid'];
	 $userid = $_POST['userid'];
	 $state = $_POST['state'];
	 
	 $actions = assign($id, $jobid, $userid, $state);
	 echo $actions;
	
 }
 
?>