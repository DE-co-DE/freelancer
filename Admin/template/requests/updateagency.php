<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['name']) && !empty($_POST['agencyid']) && !empty($_POST['bids'])
 && !empty($_POST['rollover']) && !empty($_POST['buy']) && !empty($_POST['see']) && !empty($_POST['team']))
 {
	 // get values
	 $agencyid = $_POST['agencyid'];
	 $name = $_POST['name'];
	 $price = $_POST['price'];
	 $bids = $_POST['bids'];
	 $rollover = ($_POST['rollover'] === 'true') ? 1 : 0;
	 $buy = ($_POST['buy'] === 'true') ? 1 : 0;
	 $see = ($_POST['see'] === 'true') ? 1 : 0;
	 $team = ($_POST['team'] === 'true') ? 1 : 0;

	//Update
	$Update = DB::getInstance()->update('membership_agency',[
	   'name' => $name,
	   'price' => $price,
	   'bids' => $bids,
	   'rollover' => $rollover,
	   'buy' => $buy,
	   'see' => $see,
	   'team' => $team
	],[
	    'id' => $agencyid
	  ]);
	  
	   if (count($Update) > 0) {
	        Session::put("updatedError", $name);
		} else {
	        Session::put("hasError", $name);
		}
	  
 }else {
	 $name = $_POST['name'];
	  Session::put("hasError", $title);
 }
 
	  
?>