<?php
//Check if init.php exists
if(!file_exists('../../core/binit.php')){
	header('Location: ../../install/');        
    exit;
}else{
 require_once '../../core/binit.php';	
}

//Start new Freelancer object
$freelancer = new Freelancer();

//Check if Freelancer is logged in
if (!$freelancer->isLoggedIn()) {
  Redirect::to('../index.php');	
}

//Getting Membershipid Data
$membershipid = Input::get('id');
$query = DB::getInstance()->get("membership_freelancer", "*", ["membershipid" => $membershipid]);
if ($query->count() === 1) {
  $q1 = DB::getInstance()->get("membership_freelancer", "*", ["membershipid" => $membershipid]);
} else {
  $q1 = DB::getInstance()->get("membership_agency", "*", ["membershipid" => $membershipid]);
}
if ($q1->count() === 1) {
 foreach($q1->results() as $r1) {
  $price = $r1->price;
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
      ->setTotal($price)
	  ->setDetails($details);
	  
//Transaction	  	   
$transaction->setAmount($amount)
         ->setDescription('Membership');
		 
$payment->setIntent('sale')
       ->setPayer($payer)
	   ->setTransactions([$transaction]);

//Redirect URls
$redirectUrls->setReturnUrl('http://localhost/projects/workspace/Kafe-Design/Freelancer/paypal/pay.php?approved=true')	   
	   ->setCancelUrl('http://localhost/projects/workspace/Kafe-Design/Freelancer/paypal/pay.php?approved=false');
	   
$payment->setRedirectUrls($redirectUrls);	   
	   
try{
	
 $payment->create($api);
 
 //Generate and store Hash
 $hash = md5($payment->getId());
 $_SESSION['paypal_hash'] = $hash;
 
   //Insert
   $Insert = DB::getInstance()->insert('transactions', array(
	   'membershipid' => $membershipid,
	   'freelancerid' => $freelancer->data()->freelancerid,
	   'paymentid' => $payment->getId(),
	   'hash' => $hash,
	   'payment' => $price,
	   'complete' => 0,
	   'transaction_type' => 1,
	   'paypal' => 1,
	   'date_added' => date('Y-m-d H:i:s')
    ));	 
 
	
}catch(PPConnectionException $e){
		
		unset($_SESSION['paypal_hash']);
		
		// redirect back to the view page
		Session::put("hasError", $membershipid);
		Redirect::to('../membership.php');
}	   
	   
foreach ($payment->getLinks() as $link) {
	if ($link->getRel() == 'approval_url') {
		$redirectUrl = $link->getHref();
	}
	
}   
	   
header('Location: ' . $redirectUrl);	   
	   
	   
	   
	   
	   
	   
	   
	   		 

?>