<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']))
 {
	 // get id value
	 $id = $_POST['id'];

	$query = DB::getInstance()->get("milestone", "*", ["id" => $id, "LIMIT" => 1]);
	if ($query->count() === 1) {
	 foreach($query->results() as $row) {
	  $response = $row;
	 }
	}
	 
    // display JSON data
    echo json_encode($response);
	
 }
 
?>