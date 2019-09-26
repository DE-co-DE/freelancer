<?php

//Check if init.php exists
if(!file_exists('../../core/binit.php')){
	header('Location: ../../install/');        
    exit;
}else{
 require_once '../../core/binit.php';	
}

$paymentid = Input::get('id');		
    	
// delete the entry
$query = DB::getInstance()->delete('transactions', ["paymentid" => $paymentid]);


// redirect back to the view page
Session::put("Cancelled", $paymentid);
Redirect::to('../joblist.php');

 
?>