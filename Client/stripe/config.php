<?php

//Get Payments Settings Data
$query = DB::getInstance()->get("payments_settings", "*", ["id" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$currency = $row->currency;
 	$stripe_secret_key = $row->stripe_secret_key;
 	$stripe_publishable_key = $row->stripe_publishable_key;
 	$jobs_cost = $row->jobs_cost;
 }			
}

//Composer's Autoloader
require_once 'vendor/autoload.php';

$stripe = [
  'publishable' => $stripe_publishable_key,
  'private' => $stripe_secret_key
];

Stripe::setApiKey($stripe['private']);

?>