<?php

//Get Site Settings Data
$query = DB::getInstance()->get("settings", "*", ["id" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$sid = $row->id;
 	$title = $row->title;
 	$use_icon = $row->use_icon;
 	$site_icon = $row->site_icon;
 	$tagline = $row->tagline;
 	$url = $row->url;
 	$description = $row->description;
 	$keywords = $row->keywords;
 	$author = $row->author;
 	$mail = $row->mail;
 	$mailpass = $row->mailpass;
 	$google_analytics = $row->google_analytics;
 }			
}

//Get Payments Settings Data
$q1 = DB::getInstance()->get("payments_settings", "*", ["id" => 1]);
if ($q1->count()) {
 foreach($q1->results() as $r1) {
 	$currency = $r1->currency;
 	$jobs_percentage = $row->jobs_percentage;
 }			
}

//Get Payments Settings Data
$q2 = DB::getInstance()->get("currency", "*", ["id" => $currency]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
 	$currency_code = $r2->currency_code;
 	$currency_symbol = $r2->currency_symbol;
 }			
}
?>
	<head>
	
	    <!-- ==============================================
		Title and Meta Tags
		=============================================== -->
		<meta charset="utf-8">
		<title><?php echo escape($title) .' - '. escape($tagline) ; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="<?php echo escape($description); ?>">
        <meta name="keywords" content="<?php echo escape($keywords); ?>">
        <meta name="author" content="<?php echo escape($author); ?>">
		
		<!-- ==============================================
		CSS
		=============================================== --> 
        <!-- Bootstrap 3.3.6-->
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Font-awesome 4.5.0-->
        <link href="../assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Data Tables -->
        <link href="../assets/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <!-- Summernote -->
        <link  href="../assets/css/summernote.css" rel="stylesheet" type="text/css" />
        <!-- Style -->
        <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
        <!-- Skin Green CSS -->
        <link href="../assets/css/skins/skin-green.css" rel="stylesheet" type="text/css" />

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
  </head>