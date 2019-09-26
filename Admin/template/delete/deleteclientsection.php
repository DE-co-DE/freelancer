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
     $query = DB::getInstance()->get("section_client", "*", ["id" => $id, "LIMIT" => 1]);
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
				 $query = DB::getInstance()->delete('section_client', ["id" => $id]);
			}

	 // redirect back to the view page
	 header("Location: ../../how.php?a=client");
 }else
 // if id isn't set, or isn't valid, redirect back to view page
 {
	 header("Location: ../../how.php?a=client");
 }
 
?>