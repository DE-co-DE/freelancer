<?php
//Check if init.php exists
if(!file_exists('../../core/binit.php')){
	header('Location: ../../install/');        
    exit;
}else{
 require_once '../../core/binit.php';	
}

//Get Payments Settings Data
$query = DB::getInstance()->get("payments_settings", "*", ["id" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$currency = $row->currency;
 	$paypal_client_id = $row->paypal_client_id;
 	$paypal_secret = $row->paypal_secret;
 	$jobs_cost = $row->jobs_cost;
 }			
}

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

require __DIR__ . '/../../assets/plugins/vendor/autoload.php';

//API
$api = new ApiContext(
   new OAuthTokenCredential(
     $paypal_client_id,
     $paypal_secret
   )
);

$api->setConfig([
    //'mode' => 'sandbox',
    'http.ConnectionTimeOut' => 30,
    'log.LogEnabled' => false,
    'log.FileName' => '',
    'log.LogLevel' => 'FINE',
    'validation.level' => 'log' 
]);

?>