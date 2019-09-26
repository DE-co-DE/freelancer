<?php
/* 
 DELETE.PHP
 Deletes a specific entry.
*/

 // connect to the database
 require_once '../../../core/backinit.php';
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (Input::get('id') && is_numeric(Input::get('id')))
 {
	 // get id value
	 $id = Input::get('id');
	 
	 //Get Data
     $query = DB::getInstance()->get("team", "*", ["id" => $id, "LIMIT" => 1]);
	  if ($query->count()) {
	   foreach($query->results() as $row) {
	    $imagelocation = $row->imagelocation;
	   }			
	  }
		  $back = "../../";
	      $location = $back.$imagelocation;

			if(file_exists($location)){
		       unlink($location);
				 //Delete User 
				 $query = DB::getInstance()->delete('team', ["id" => $id]);
			}

	 // redirect back to the view page
	 header("Location: ../../about.php?a=team");
 }else
 // if id isn't set, or isn't valid, redirect back to view page
 {
	 header("Location: ../../about.php?a=team");
 }
 
?>