<?php
//Check if init.php exists
if(!file_exists('../../core/binit.php')){
	header('Location: ../../install/');        
    exit;
}else{
 require_once '../../core/binit.php';	
}

//Start new Client object
$client = new Client();

//Check if Client is logged in
if (!$client->isLoggedIn()) {
  Redirect::to('../index.php');	
}

//Getting Milestone Data
$id = Input::get('id');
$query = DB::getInstance()->get("milestone", "*", ["id" => $id, "LIMIT" => 1]);
if ($query->count() === 1) {
 foreach($query->results() as $row) {
  $milestone_budget = $row->budget;
 }
}

use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Exception\PPConnectionException;

require 'start.php';

//Get Payments Settings Data
$q2 = DB::getInstance()->get("currency", "*", ["id" => $currency]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
 	$currency_code = $r2->currency_code;
 }			
}

$payer = new Payer();
$details = new Details();
$amount = new Amount();
$transaction = new Transaction();
$payment = new Payment();
$redirectUrls = new RedirectUrls();

//Payer
$payer->setPaymentMethod('paypal');

//Details
$details->setShipping('0.00')
       ->setTax('0.00')
	   ->setSubtotal('0.00');

//Amount
$amount->setCurrency($currency_code)
      ->setTotal($milestone_budget)
	  ->setDetails($details);
	  
//Transaction	  	   
$transaction->setAmount($amount)
         ->setDescription('Membership');
		 
$payment->setIntent('sale')
       ->setPayer($payer)
	   ->setTransactions([$transaction]);

//Redirect URls
$redirectUrls->setReturnUrl('http://localhost/projects/workspace/Kafe-Design/Client/paypal/pay.php?approved=true')	   
	   ->setCancelUrl('http://localhost/projects/workspace/Kafe-Design/Client/paypal/pay.php?approved=false');
	   
$payment->setRedirectUrls($redirectUrls);	   
	   
try{
	
 $payment->create($api);
 
 //Generate and store Hash
 $hash = md5($payment->getId());
 $_SESSION['paypal_hash'] = $hash;

   //Insert
   $Insert = DB::getInstance()->insert('transactions', array(
	   'membershipid' => $id,
	   'freelancerid' => $client->data()->clientid,
	   'paymentid' => $payment->getId(),
	   'hash' => $hash,
	   'payment' => $milestone_budget,
	   'complete' => 0,
	   'transaction_type' => 4,
	   'paypal' => 1,
	   'date_added' => date('Y-m-d H:i:s')
    ));	
 
	
}catch(PPConnectionException $e){
		
		unset($_SESSION['paypal_hash']);
		
		$query = DB::getInstance()->get("milestone", "*", ["id" => $id, "LIMIT" => 1]);
		if ($query->count() === 1) {
		 foreach($query->results() as $row) {
		  $jobid = $row->jobid;
		 }
		}
		
		// redirect back to the view page
		Session::put("hasError", $id);
		
        Redirect::to('../jobboard.php?a=milestones&&id='.$jobid.'');
}	   
	   
foreach ($payment->getLinks() as $link) {
	if ($link->getRel() == 'approval_url') {
		$redirectUrl = $link->getHref();
	}
	
}   
	   
header('Location: ' . $redirectUrl);	   
	   
	   
	   
	   
	   
	   
	   
	   		 

?>