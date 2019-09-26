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
 	$job_limit = $row->job_limit;
 	$service_limit = $row->service_limit;
 	$proposal_limit = $row->proposal_limit;
 	$top_title = $row->top_title;
 	$show_downtitle = $row->show_downtitle;
 	$down_title = $row->down_title;
 	$searchterm = $row->searchterm;
 	$header_img = $row->header_img;
 	$cattagline = $row->cattagline;
 	$testtagline = $row->testtagline;
 	$statstagline = $row->statstagline;
 	$about_top_title = $row->about_top_title;
 	$about_header_img = $row->about_header_img;
 	$about_hello = $row->about_hello;
 	$about_whitebg = $row->about_whitebg;
 	$teamtagline = $row->teamtagline;
 	$timelinetagline = $row->timelinetagline;
 	$how_top_title = $row->how_top_title;
 	$how_header_img = $row->how_header_img;
 	$faq_top_title = $row->faq_top_title;
 	$faq_header_img = $row->faq_header_img;
 	$faq_hello = $row->faq_hello;
 	$contact_top_title = $row->contact_top_title;
 	$contact_header_img = $row->contact_header_img;
 	$contact_location = $row->contact_location;
 	$contact_phone = $row->contact_phone;
 	$contact_email = $row->contact_email;
 	$contact_map = $row->contact_map;
 	$footer_about = $row->footer_about;
 	$facebook = $row->facebook;
 	$twitter = $row->twitter;
 	$google = $row->google;
 	$instagram = $row->instagram;
 	$linkedin = $row->linkedin;
 	$pricing_top_title = $row->pricing_top_title;
 	$pricing_header_img = $row->pricing_header_img;
 	$services_header_img = $row->services_header_img;
 	$jobs_header_img = $row->jobs_header_img;
 	$google_analytics = $row->google_analytics;
 }			
}

//Get Payments Settings Data
$q1 = DB::getInstance()->get("payments_settings", "*", ["id" => 1]);
if ($q1->count()) {
 foreach($q1->results() as $r1) {
 	$currency = $r1->currency;
 	$membershipid = $r1->membershipid;
 }			
}

//Get Payments Settings Data
$q2 = DB::getInstance()->get("currency", "*", ["id" => $currency]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
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
		Favicons
		=============================================== --> 
		<link rel="shortcut icon" href="img/favicons/favicon.ico">
		<link rel="apple-touch-icon" href="img/favicons/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="img/favicons/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="img/favicons/apple-touch-icon-114x114.png">
		
	    <!-- ==============================================
		CSS
		=============================================== -->
        <!-- Style-->
        <link href="assets/css/hopler.css" rel="stylesheet" type="text/css" />
				
		<!-- ==============================================
		Feauture Detection
		=============================================== -->
		<script src="/js/modernizr-custom.js"></script>
		
		<!--[if lt IE 9]>
		 <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->		
		
</head>