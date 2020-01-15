<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['id']) && !empty($_POST['name']) && !empty($_POST['description']) && !empty($_POST['budget']) && !empty($_POST['start_date'])
 && !empty($_POST['end_date']))
 {
	 // get id value
	 $id = $_POST['id'];
	 $name = $_POST['name'];
	 $description = $_POST['description'];
	 $budget = $_POST['budget'];
	 $start_date = $_POST['start_date'];
	 $end_date = $_POST['end_date'];

	//Update
	$Update = DB::getInstance()->update('milestone',[
	   'name' => $name,
	   'description' => $description,
	   'budget' => $budget,
	   'start_date' => $start_date,
	   'end_date' => $end_date
	],[
	    'id' => $id
	  ]);
	  
	   if (count($Update) > 0) {
	        Session::put("updatedError", $name);
		} else {
	        Session::put("hasError", $name);
		}
		
 }else {
	 $name = $_POST['name'];
	  Session::put("hasError", $name);
 }
	  
?>