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
		  $membershipid = $row->membershipid;
		  $paymentid = $row->paymentid;
		 }
		} 		
		
		$qt = DB::getInstance()->get("membership_freelancer", "*", ["membershipid" => $membershipid]);
		if ($qt->count() === 1) {
		  $q1 = DB::getInstance()->get("membership_freelancer", "*", ["membershipid" => $membershipid]);
		} else {
		  $q1 = DB::getInstance()->get("membership_agency", "*", ["membershipid" => $membershipid]);
		}
		if ($q1->count() === 1) {
		 foreach($q1->results() as $r1) {
		  $bids = $r1->bids;
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
		$Update = DB::getInstance()->update('freelancer',[
		   'membershipid' => $membershipid,
		   'membership_bids' => $bids,
		   'membership_date' => date('Y-m-d H:i:s')
		],[
		    'freelancerid' => $freelancer->data()->freelancerid
		  ]);		  
		
		//Unset Paypal Hash
		unset($_SESSION['paypal_hash']);
		
        Redirect::to('../membership.php');
		
		
		
		
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