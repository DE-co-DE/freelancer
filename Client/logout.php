<?php

require_once '../core/init.php';

$client = new Client();
$client->logout();
Redirect::to('../index.php');
?>