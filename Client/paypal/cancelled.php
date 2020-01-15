<?php

//Check if init.php exists
if(!file_exists('../../core/binit.php')){
	header('Location: ../../install/');        
    exit;
}else{
 require_once '../../core/binit.php';	
}

$paymentid = Input::get('id');

$q1 = DB::getInstance()->get("transactions", "*", ["paymentid" => $paymentid, "LIMIT" => 1]);
if ($q1->count() === 1) {
 foreach($q1->results() as $r1) {
  $milestoneid = $r1->membershipid;
 }
} 		
    
//Getting Payement Id from Database
$query = DB::getInstance()->get("milestone", "*", ["id" => $milestoneid, "LIMIT" => 1]);
if ($query->count() === 1) {
 foreach($query->results() as $row) {
  $jobid = $row->jobid;
 }
}
	
// delete the entry
$query = DB::getInstance()->delete('transactions', ["paymentid" => $paymentid]);


// redirect back to the view page
Session::put("Cancelled", $paymentid);
Redirect::to('../jobboard.php?a=milestones&id='.$jobid.'');

 
?>