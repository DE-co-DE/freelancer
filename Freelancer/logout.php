<?php

require_once '../core/init.php';

$freelancer = new Freelancer();
$freelancer->logout();
Redirect::to('../index.php');
?>