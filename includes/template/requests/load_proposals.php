<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['jobid']) && !empty($_POST['limit']))
 {
	 // get id value
	 $id = $_POST['id'];
	 $jobid = $_POST['jobid'];
	 $limit = $_POST['limit'];
	 
	 $proposals = getALLProposals($id, $jobid, $limit);
	 echo $proposals;
	
 }
 
?>