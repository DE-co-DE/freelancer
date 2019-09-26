<?php

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

require 'start.php';

if (isset($_GET['approved'])) {
	
	$approved = $_GET['approved'] == 'true';
	if ($approved) {
		
		$payerId = $_GET['PayerID'];
		
		//Getting Payement Id from Database
		$query = DB::getInstance()->get("transactions", "*", ["hash" => $_SESSION['paypal_hash'], "LIMIT" => 1]);
		if ($query->count() === 1) {
		 foreach($query->results() as $row) {
		  $milestoneid = $row->membershipid;
		  $paymentid = $row->paymentid;
		 }
		} 		
		
		//Get the PayPal payment
		$payment = Payment::get($paymentid, $api);
		
		$execution = new PaymentExecution();
		$execution->setPayerId($payerId);
		
		//Execute PayPal payment
		$payment->execute($execution, $api);
		
		//Update Transaction
		$Update = DB::getInstance()->update('transactions',[
		   'complete' => 1
		],[
		    'paymentid' => $paymentid
		  ]);		
		  
		//Update Milestone
		$Update = DB::getInstance()->update('milestone',[
		   'funded' => 1
		],[
		    'id' => $milestoneid
		  ]);		  
		  
		$query = DB::getInstance()->get("milestone", "*", ["id" => $milestoneid, "LIMIT" => 1]);
		if ($query->count() === 1) {
		 foreach($query->results() as $row) {
		  $jobid = $row->jobid;
		 }
		}
		
		//Unset Paypal Hash
		unset($_SESSION['paypal_hash']);
		
        Redirect::to('../jobboard.php?a=milestones&id='.$jobid.'');
		
		
		
		
	}else {
				
		//Getting Payement Id from Database
		$query = DB::getInstance()->get("transactions", "*", ["hash" => $_SESSION['paypal_hash'], "LIMIT" => 1]);
		if ($query->count() === 1) {
		 foreach($query->results() as $row) {
		  $paymentid = $row->paymentid;
		 }
		}
		
		unset($_SESSION['paypal_hash']);
		
        Redirect::to('cancelled.php?id='.$paymentid.'');
	}
	
}

?>