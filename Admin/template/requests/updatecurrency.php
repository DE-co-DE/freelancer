<?php

 // connect to the database
 require_once '../../../core/backinit.php';
 
 
 
 // check if the 'serial' variable is set in URL, and check that it is valid
 if (!empty($_POST['currency_code']) && !empty($_POST['currency_name']) && !empty($_POST['currency_symbol']) 
 && !empty($_POST['currencyid']))
 {
	 // get values
	 $currencyid = $_POST['currencyid'];
	 $currency_code = $_POST['currency_code'];
	 $currency_name = $_POST['currency_name'];
	 $currency_symbol = $_POST['currency_symbol'];

	//Update
	$Update = DB::getInstance()->update('currency',[
	   'currency_code' => $currency_code,
	   'currency_name' => $currency_name,
	   'currency_symbol' => $currency_symbol,
	],[
	    'id' => $currencyid
	  ]);
	  
	   if (count($Update) > 0) {
	        Session::put("updatedError", $currency_code);
		} else {
	        Session::put("hasError", $currency_code);
		}
		
 }else {
	 $currency_code = $_POST['currency_code'];
	  Session::put("hasError", $currency_code);
 }
 
	  
?>