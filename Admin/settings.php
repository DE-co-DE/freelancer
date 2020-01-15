<?php
//Check if init.php exists
if(!file_exists('../core/init.php')){
	header('Location: ../install/');        
    exit;
}else{
 require_once '../core/init.php';	
}

//Start new Admin Object
$admin = new Admin();

//Check if Admin is logged in
if (!$admin->isLoggedIn()) {
  Redirect::to('index.php');	
}


//Get Site Settings Data
$query = DB::getInstance()->get("settings", "*", ["id" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$sid = $row->id;
 	$title = $row->title;
 	$use_icon = $row->use_icon;
 	$site_icon = $row->site_icon;
 	$url = $row->url;
 	$description = $row->description;
 	$keywords = $row->keywords;
 	$author = $row->author;
 	$mail = $row->mail;
 	$mailpass = $row->mailpass;
 	$bgimage = $row->bgimage;
 	$job_limit = $row->job_limit;
 	$service_limit = $row->service_limit;
 	$proposal_limit = $row->proposal_limit;
 	$google_analytics = $row->google_analytics;
 }			
}	

//Get Payments Settings Data
$query = DB::getInstance()->get("payments_settings", "*", ["id" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$pid = $row->id;
 	$currencyid = $row->currency;
 	$paypal_client_id = $row->paypal_client_id;
 	$paypal_secret = $row->paypal_secret;
 	$stripe_secret_key = $row->stripe_secret_key;
 	$stripe_publishable_key = $row->stripe_publishable_key;
 	$membershipid = $row->membershipid;
 	$jobs_cost = $row->jobs_cost;
 	$jobs_pay_limit = $row->jobs_pay_limit;
 	$bids_cost = $row->bids_cost;
 	$bids_limit = $row->bids_limit;
 	$jobs_percentage = $row->jobs_percentage;
 }			
}

//Edit Site Settings Data
if(isset($_POST['site'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'title' => [
	     'required' => true,
	     'maxlength' => 100,
	     'minlength' => 2
	  ],
	  'tagline' => [
	     'required' => true,
	     'maxlength' => 100,
	     'minlength' => 2
	  ],
	  'url' => [
	     'required' => true,
	     'maxlength' => 255,
	     'minlength' => 2
	   ],
	  'description' => [
	     'required' => true,
	     'minlength' => 30
	   ],
	  'keywords' => [
	     'required' => true,
	     'minlength' => 30
	   ],
	  'author' => [
	     'required' => true,
	     'maxlength' => 255,
	     'minlength' => 2
	   ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'title' => Input::get('title'),
			    'tagline' => Input::get('tagline'),
			    'url' => Input::get('url'),
			    'description' => Input::get('description'),
			    'keywords' => Input::get('keywords'),
			    'author' => Input::get('author')
		    ],[
		    'id' => $sid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}

//Edit Icon Settings Data
if(isset($_POST['icon'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'use_icon' => [
	     'required' => true
	  ],
	  'site_icon' => [
	     'required' => true,
	     'maxlength' => 100,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$use_icon = (Input::get('use_icon') === 'on') ? 1 : 0;
			$siteUpdate = DB::getInstance()->update('settings',[
			    'use_icon' => $use_icon,
			    'site_icon' => Input::get('site_icon')
		    ],[
		    'id' => $sid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}

//Edit Mail Site Settings Data
if(isset($_POST['mailsite'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'mail' => [
	     'required' => true,
	     'maxlength' => 100,
	     'minlength' => 2
	  ],
	  'mailpass' => [
	     'required' => true,
	     'maxlength' => 255,
	     'minlength' => 2
	   ]
	]);
		 
    if (!$validation->fails()) {
		
			$sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'mail' => Input::get('mail'),
			    'mailpass' => Input::get('mailpass')
		    ],[
		    'id' => $sid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}

/*Edit Background Image Data*/
if(isset($_POST['picture'])){
if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
  	
	$path = "../assets/img/bg/";
    $valid_formats = array("jpg", "png", "gif", "bmp");
   
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];

    if(strlen($name))
	{
	  list($txt, $ext) = explode(".", $name);
      if(in_array($ext,$valid_formats) && $size<(1920*1280))
	   {
	     $actual_image_name = time().substr($txt, 5).".".$ext;
		 $image_name = $actual_image_name;
		 $path_new = "assets/img/bg/";
		 $newname=$path_new.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {
	       if ($bgimage !== 'assets/img/bg/bg.jpg') {
	       	$new_bgimage = '../'.$bgimage;
			unlink($new_bgimage);
				$sid = Input::get('sid');
				$siteUpdate = DB::getInstance()->update('settings',[
				    'bgimage' => $newname
			    ],[
			    'id' => $sid
			    ]);
				
				if (count($siteUpdate) > 0) {
					$noError = true;
				} else {
					$hasError = true;
				}				 
		   } else {
				$sid = Input::get('sid');
				$siteUpdate = DB::getInstance()->update('settings',[
				    'bgimage' => $newname
			    ],[
			    'id' => $sid
			    ]);
				
				if (count($siteUpdate) > 0) {
					$noError = true;
				} else {
					$hasError = true;
				}
		   }  		  
					
	      }else{
		   $imageError = true;	
     	  }
       }else{
       	  $formatError = true;				
	   }
      }else{
      	  $selectError = true;
      }	
  	
  }
 }	
}

//Edit Site Limits Data
if(isset($_POST['site_limits'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'job_limit' => [
	     'required' => true,
	     'maxlength' => 100
	  ],
	  'service_limit' => [
	     'required' => true,
	     'maxlength' => 100
	  ],
	  'proposal_limit' => [
	     'required' => true,
	     'maxlength' => 100
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'job_limit' => Input::get('job_limit'),
			    'service_limit' => Input::get('service_limit'),
			    'proposal_limit' => Input::get('proposal_limit')
		    ],[
		    'id' => $sid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}

//Edit Payments Settings Data
if(isset($_POST['payments'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'paypal_client_id' => [
	     'required' => true,
	     'minlength' => 2
	  ],
	  'paypal_secret' => [
	     'required' => true,
	     'minlength' => 2
	  ],
	  'stripe_secret_key' => [
	     'required' => true,
	     'minlength' => 2
	  ],
	  'stripe_publishable_key' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $pid = Input::get('pid');
			$siteUpdate = DB::getInstance()->update('payments_settings',[
			    'paypal_client_id' => Input::get('paypal_client_id'),
			    'paypal_secret' => Input::get('paypal_secret'),
			    'stripe_secret_key' => Input::get('stripe_secret_key'),
			    'stripe_publishable_key' => Input::get('stripe_publishable_key')
		    ],[
		    'id' => $pid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}

//Edit Currency Settings Data
if(isset($_POST['currency_submit'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'currency_select' => [
	     'required' => true,
	     'minlength' => 1
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $pid = Input::get('pid');
			$siteUpdate = DB::getInstance()->update('payments_settings',[
			    'currency' => Input::get('currency_select')
		    ],[
		    'id' => $pid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}

//Edit Membership Settings Data
if(isset($_POST['membership_submit'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'membership_select' => [
	     'required' => true,
	     'minlength' => 1
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $pid = Input::get('pid');
			$siteUpdate = DB::getInstance()->update('payments_settings',[
			    'membershipid' => Input::get('membership_select')
		    ],[
		    'id' => $pid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}

//Edit Sponsor Jobs & Bids Settings Data
if(isset($_POST['sponsorship_submit'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'jobs_cost' => [
	     'required' => true,
	     'digit' => true,
	     'minlength' => 1,
	     'digit' => true
	  ],
	  'jobs_pay_limit' => [
	     'required' => true,
	     'minlength' => 1
	  ],
	  'bids_cost' => [
	     'required' => true,
	     'digit' => true,
	     'minlength' => 1,
	     'digit' => true
	  ],
	  'bids_limit' => [
	     'required' => true,
	     'minlength' => 1
	  ],
	]);
		 
    if (!$validation->fails()) {
		
		    $pid = Input::get('pid');
			$siteUpdate = DB::getInstance()->update('payments_settings',[
			    'jobs_cost' => Input::get('jobs_cost'),
			    'jobs_pay_limit' => Input::get('jobs_pay_limit'),
			    'bids_cost' => Input::get('bids_cost'),
			    'bids_limit' => Input::get('bids_limit'),
		    ],[
		    'id' => $pid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}

//Edit Jobs Fee Percentage Settings Data
if(isset($_POST['percentage_submit'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'jobs_percentage' => [
	     'required' => true,
	     'maxlength' => 200
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $pid = Input::get('pid');
			$siteUpdate = DB::getInstance()->update('payments_settings',[
			    'jobs_percentage' => Input::get('jobs_percentage')
		    ],[
		    'id' => $pid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}

//Edit Site Analytics Data
if(isset($_POST['site_analytics'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'google_analytics' => [
	     'required' => true
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'google_analytics' => Input::get('google_analytics')
		    ],[
		    'id' => $sid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
			} else {
				$hasError = true;
			}
			
	 } else {
     $error = '';
     foreach ($validation->errors()->all() as $err) {
     	$str = implode(" ",$err);
     	$error .= '
	           <div class="alert alert-danger fade in">
	            <a href="#" class="close" data-dismiss="alert">&times;</a>
	            <strong>Error!</strong> '.$str.'
		       </div>
		       ';
     }
   }

  }
 }
}
?>
<!DOCTYPE html>
<html lang="en-US" class="no-js">
	
    <!-- Include header.php. Contains header content. -->
    <?php include ('template/header.php'); ?> 
    <!-- Fontawesome Icon Picker CSS -->
    <link href="../assets/css/fontawesome-iconpicker.min.css" rel="stylesheet" type="text/css" />

 <body class="skin-green sidebar-mini">
     
     <!-- ==============================================
     Wrapper Section
     =============================================== -->
	 <div class="wrapper">
	 	
        <!-- Include navigation.php. Contains navigation content. -->
	 	<?php include ('template/navigation.php'); ?> 
        <!-- Include sidenav.php. Contains sidebar content. -->
	 	<?php include ('template/sidenav.php'); ?> 
	 	
	  <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $lang['settings']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['settings']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>   
		 <div class="row">	
		 	
		 <div class="col-lg-12">
		 <?php if(isset($selectError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['image_select']; ?>
		   </div>
	      <?php } ?>	      
	      
		 <?php if(isset($formatError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['image_format']; ?>
		   </div>
	      <?php } ?>
	      
		 <?php if(isset($imageError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['image_upload']; ?>
		   </div>
	      <?php } ?>
	      
	      <?php if(isset($hasError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
		   </div>
	      <?php } ?>
	
		  <?php if(isset($noError) && $noError == true) { //If email is sent ?>
		   <div class="alert alert-success fade in">
		   <a href="#" class="close" data-dismiss="alert">&times;</a>
		   <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?></strong>
		   </div>
		  <?php } ?>
		  
		  <?php if (isset($error)) {
			  echo $error;
		  } ?>
          </div>	
           
          <div class="col-lg-4">
          	<?php $selected = (Input::get('a') == 'site') ? ' active' : ''; ?>
          	<?php $siteicon = (Input::get('a') == 'siteicon') ? ' active' : ''; ?>
          	<?php $sitemail = (Input::get('a') == 'mail') ? ' active' : ''; ?>
          	<?php $bgimg = (Input::get('a') == 'bgimg') ? ' active' : ''; ?>
          	<?php $limits = (Input::get('a') == 'limits') ? ' active' : ''; ?>
          	<?php $payments = (Input::get('a') == 'payments') ? ' active' : ''; ?>
          	<?php $currency = (Input::get('a') == 'currency') ? ' active' : ''; ?>
          	<?php $membership = (Input::get('a') == 'membership') ? ' active' : ''; ?>
          	<?php $sponsorship = (Input::get('a') == 'sponsorship') ? ' active' : ''; ?>
          	<?php $percentage = (Input::get('a') == 'percentage') ? ' active' : ''; ?>
          	<?php $analytics = (Input::get('a') == 'analytics') ? ' active' : ''; ?>
	         <div class="list-group">
	         <a href="settings.php?a=site" class="list-group-item<?php echo $selected; ?>">
	          <em class="fa fa-fw fa-cogs text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['site']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="settings.php?a=siteicon" class="list-group-item<?php echo $siteicon; ?>">
	          <em class="fa fa-fw fa-cogs text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['site']; ?> <?php echo $lang['icon']; ?>
			 </a>
	         <a href="settings.php?a=mail" class="list-group-item<?php echo $sitemail; ?>">
	          <em class="fa fa-fw fa-inbox text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['mail']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="settings.php?a=bgimg" class="list-group-item<?php echo $bgimg; ?>">
	          <em class="fa fa-fw fa-image text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['bg_image']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="settings.php?a=limits" class="list-group-item<?php echo $limits; ?>">
	          <em class="fa fa-fw fa-bars text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['limits']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="settings.php?a=payments" class="list-group-item<?php echo $payments; ?>">
	          <em class="fa fa-fw fa-money text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['payments']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="settings.php?a=currency" class="list-group-item<?php echo $currency; ?>">
	          <em class="fa fa-fw fa-usd text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['currency']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="settings.php?a=membership" class="list-group-item<?php echo $membership; ?>">
	          <em class="fa fa-fw fa-users text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['set']; ?> <?php echo $lang['default']; ?> <?php echo $lang['membership']; ?>
			 </a>
	         <a href="settings.php?a=sponsorship" class="list-group-item<?php echo $sponsorship; ?>">
	          <em class="fa fa-fw fa-usd text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['sponsor']; ?> <?php echo $lang['jobs']; ?> & <?php echo $lang['bids']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="settings.php?a=percentage" class="list-group-item<?php echo $percentage; ?>">
	          <em class="fa fa-fw fa-percent text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['percentage']; ?> 
	                                                                        <?php echo $lang['of']; ?> 
	                                                                        <?php echo $lang['job']; ?> 
	                                                                        <?php echo $lang['fee']; ?>
	                                                                        <?php echo $lang['taken']; ?>
	                                                                        <?php echo $lang['by']; ?>
	                                                                        <?php echo $lang['company']; ?>
			 </a>
	         <a href="settings.php?a=analytics" class="list-group-item<?php echo $analytics; ?>">
	          <em class="fa fa-fw fa-line-chart text-white"></em>&nbsp;&nbsp;&nbsp;Google <?php echo $lang['analytics']; ?> 
			 </a>
			 
	         </div>
          </div>
          
		 <div class="col-lg-8">
		 <?php if (Input::get('a') == 'site') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['site']; ?> <?php echo $lang['settings']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  
			 
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="title" class="form-control" value="<?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('title')); 
						  } else {
						  echo escape($title); 
						  }
					  ?>"/>
                   </div>
                    <p class="help-block"><?php echo $lang['site_name']; ?></p>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="tagline" class="form-control" value="<?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('tagline')); 
						  } else {
						  echo escape($tagline); 
						  }
					  ?>"/>
                   </div>
                    <p class="help-block"><?php echo $lang['site_tagline']; ?></p>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="url" class="form-control" value="<?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('url')); 
						  } else {
						  echo escape($url); 
						  }
					  ?>"/>
                   </div>
                    <p class="help-block"><?php echo $lang['site_url']; ?></p>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-quote-left"></i></span>
                    <textarea type="text" name="description" class="form-control" rows="5"><?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('description')); 
						  } else {
						  echo escape($description); 
						  }
					  ?></textarea>
                   </div>
                    <p class="help-block"><?php echo $lang['site_description']; ?></p>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-quote-left"></i></span>
                    <textarea type="text" name="keywords" class="form-control" rows="5"><?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('keywords')); 
						  } else {
						  echo escape($keywords); 
						  }
					  ?></textarea>
                   </div>
                    <p class="help-block"><?php echo $lang['site_keywords']; ?></p>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="author" class="form-control" value="<?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('author')); 
						  } else {
						  echo escape($author); 
						  }
					  ?>"/>
                   </div>
                    <p class="help-block"><?php echo $lang['site_author']; ?></p>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="site" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
		 <?php elseif (Input::get('a') == 'siteicon') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['site']; ?> <?php echo $lang['icon']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  
				 <div class="form-group">
				   <label><?php echo $lang['site']; ?><?php echo $lang['icon']; ?></label>
		            <ul class="list-group">
		             <li class="list-group-item flr">
		              <div class="material-switch text-center">
			           <span class="pull-left"><?php echo $lang['no']; ?></span>
		                <input id="update_rollover" name="use_icon" type="checkbox" <?php echo $active = ($use_icon == '1') ? ' checked' : ''; ?>/>
		                <label for="update_rollover" class="label-mint"></label>
			           <span class="pull-right"><?php echo $lang['yes']; ?></span>
		              </div>
		             </li>
		            </ul>  
				 </div><!-- /.form-row -->
				 
	             <div class="form-group">
	              <label><?php echo $lang['choose']; ?> <?php echo $lang['icon']; ?></label>
	               <div class="input-group">
	                <input data-placement="bottomRight" name="site_icon" class="form-control icp icp-auto" value="<?php
                         if (isset($_POST['icon'])) {
							 echo escape(Input::get('site_icon')); 
						  } else {
						  echo escape($site_icon); 
						  }
					  ?>" type="text" />
	                <span class="input-group-addon"></span>
	                </div>
	              </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="icon" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
		 <?php elseif (Input::get('a') == 'mail') : ?>
		  
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['site']; ?> <?php echo $lang['mail']; ?> <?php echo $lang['settings']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="mail" class="form-control" value="<?php
                         if (isset($_POST['mailsite'])) {
							 echo escape(Input::get('mail')); 
						  } else {
						  echo escape($mail); 
						  }
					  ?>"/>
                   </div>
                    <p class="help-block"><?php echo $lang['site_email']; ?></p>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="mailpass" class="form-control" value="<?php
                         if (isset($_POST['mailsite'])) {
							 echo escape(Input::get('mailpass')); 
						  } else {
						  echo escape($mailpass); 
						  }
					  ?>"/>
                   </div>
                    <p class="help-block"><?php echo $lang['site_email_pass']; ?></p>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="mailsite" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
 		 <?php elseif (Input::get('a') == 'bgimg') : ?>
		  
		  		 <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['bg_image']; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="box-body">
                    <div class="form-group">
					 <div class="image text-center">
					  <img src="../<?php 
					  if (isset($_POST['picture'])) {
						 echo escape($newname); 
					  } else {
					  echo escape($bgimage); 
					  } ?>" class="img-thumbnail" width="515" height="415"/>
					 </div>
                    </div>
                   <div style="position:relative;">
	                <a class='btn btn-primary' href='javascript:;'>
		            Choose File...
		            <input type="file" name="photoimg" id="photoimg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
	                <input type="hidden" name="image_name" id="image_name"/>
	                </a>
	                &nbsp;
	                <span class='label label-info' id="upload-file-info"></span>
                  </div>
				  
                  </div><!-- /.box-body -->
                  
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="picture" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button><br></br>
                  </div>

                </form>
              </div><!-- /.box -->     
                 
		 <?php elseif (Input::get('a') == 'limits') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['limits']; ?> <?php echo $lang['settings']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  
                  <div class="form-group">	
				    <label><?php echo $lang['job']; ?> <?php echo $lang['list']; ?> <?php echo $lang['limit']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select id="job_limit" name="job_limit" type="text" class="form-control" >
                    <?php 
                     $jl = [
                           '2'=>'2',
                           '3'=>'3',
                           '4'=>'4',
                           '5'=>'5',
                           '6'=>'6',
                           '7'=>'7',
                           '8'=>'8',
                           '9'=>'9',
                           '10'=>'10'
                           ];
					
					$x = 1;
					foreach ($jl as $key => $value) {
	                     if (isset($_POST['site_limits'])) {
                           $selected = ($value === Input::get('job_limit')) ? ' selected="selected"' : '';
						  } else {
                           $selected = ($value === $job_limit) ? ' selected="selected"' : '';
						  }
					  echo $opt .= '<option value = "' . $value . '" '.$selected.'>' . $key . '</option>';
					  unset($opt); 
					  $x++;
					} ?>
					 </select>
                   </div>
                  </div>
                  
                  <div class="form-group">	
				    <label><?php echo $lang['service']; ?> <?php echo $lang['list']; ?> <?php echo $lang['limit']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select name="service_limit" type="text" class="form-control" >
                    <?php 
					$x = 1;
					foreach ($jl as $key => $value) {
	                     if (isset($_POST['site_limits'])) {
                           $selected = ($value === Input::get('service_limit')) ? ' selected="selected"' : '';
						  } else {
                           $selected = ($value === $service_limit) ? ' selected="selected"' : '';
						  }
					  echo $opt .= '<option value = "' . $value . '" '.$selected.'>' . $key . '</option>';
					  unset($opt); 
					  $x++;
					} ?>
					 </select>
                   </div>
                  </div>         
                  
                  <div class="form-group">	
				    <label><?php echo $lang['proposal']; ?> <?php echo $lang['list']; ?> <?php echo $lang['limit']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select name="proposal_limit" type="text" class="form-control" >
                    <?php 
					$x = 1;
					foreach ($jl as $key => $value) {
	                     if (isset($_POST['site_limits'])) {
                           $selected = ($value === Input::get('proposal_limit')) ? ' selected="selected"' : '';
						  } else {
                           $selected = ($value === $proposal_limit) ? ' selected="selected"' : '';
						  }
					  echo $opt .= '<option value = "' . $value . '" '.$selected.'>' . $key . '</option>';
					  unset($opt); 
					  $x++;
					} ?>
					 </select>
                   </div>
                  </div>          

                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="site_limits" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box --> 
              
		 <?php elseif (Input::get('a') == 'payments') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['payments']; ?> <?php echo $lang['settings']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="pid" value="<?php echo escape($pid); ?>"/>
                  
				  <label>Paypal <?php echo $lang['settings']; ?> </label>
                  
                  <div class="form-group">	
				    <label>Paypal <?php echo $lang['client']; ?> <?php echo $lang['id']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="paypal_client_id" class="form-control" rows="2"><?php
                         if (isset($_POST['payments'])) {
							 echo escape(Input::get('paypal_client_id')); 
						  } else {
						  echo escape($paypal_client_id); 
						  }
					  ?></textarea>
                   </div>
                  </div>         
                                
                  <div class="form-group">	
				    <label>Paypal <?php echo $lang['secret']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="paypal_secret" class="form-control" rows="2"><?php
                         if (isset($_POST['payments'])) {
							 echo escape(Input::get('paypal_secret')); 
						  } else {
						  echo escape($paypal_secret); 
						  }
					  ?></textarea>
                   </div>
                  </div>  
                  <br />              
				    <label>Stripe Settings</label>
                  <div class="form-group">	
				    <label>Stripe Secret Key</label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="stripe_secret_key" class="form-control" rows="2"><?php
                         if (isset($_POST['payments'])) {
							 echo escape(Input::get('stripe_secret_key')); 
						  } else {
						  echo escape($stripe_secret_key); 
						  }
					  ?></textarea>
                   </div>
                  </div>         
                  <div class="form-group">	
				    <label>Stripe Publishable Key</label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="stripe_publishable_key" class="form-control" rows="2"><?php
                         if (isset($_POST['payments'])) {
							 echo escape(Input::get('stripe_publishable_key')); 
						  } else {
						  echo escape($stripe_publishable_key); 
						  }
					  ?></textarea>
                   </div>
                  </div>                   

                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="payments" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->   
              
		 <?php elseif (Input::get('a') == 'currency') : ?>
       <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 <div class="col-md-12">	
		  <?php if(Session::exists(noError) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['saved_success']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(noError); ?>
		  
		  <?php if(Session::exists(hasError) == true) { //If email is sent ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(hasError); ?>
		  
		  <?php if(Session::exists(updatedError) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(updatedError); ?>	
		  
          </div>
          		 	
		 	<div class="col-md-12">
		 		

		 
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['select']; ?> <?php echo $lang['default']; ?> <?php echo $lang['currency']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                  <input type="hidden" name="pid" value="<?php echo escape($pid); ?>"/>
                  
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select name="currency_select" type="text" class="form-control">
					 <?php
					  $query = DB::getInstance()->get("currency", "*", ["ORDER" => "date_added DESC"]);
						if ($query->count()) {
						   $name = '';
						   $x = 1;
							 foreach ($query->results() as $row) {
	                          if (isset($_POST['currency_submit'])) {
                                $selected = ($row->id === Input::get('currency_select')) ? ' selected="selected"' : '';
							  } else {
                                $selected = ($row->id === $currencyid) ? ' selected="selected"' : '';
							  }
							  echo $name .= '<option value = "' . $row->id . '" '.$selected.'>' . $row->currency_symbol . ' - ' . $row->currency_name . '</option>';
							  unset($name); 
							  $x++;
						     }
						}
						
					 ?>	
					</select>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="currency_submit" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->		 		
		 		
	  		 <div class="box box-info">
	        <div class="box-header">
	          <a href="#addcurrency" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['add']; ?> <?php echo $lang['currency']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addcurrency" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['currency']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
              	
              <div class="form-group">	
			    <label><?php echo $lang['currency']; ?> <?php echo $lang['code']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="currency_code" class="form-control" placeholder="e.g USD"/>
               </div>
              </div>     
               
              <div class="form-group">	
			    <label><?php echo $lang['currency']; ?> <?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="currency_name" class="form-control" placeholder="e.g Mexican Peso"/>
               </div>
              </div>      
               
              <div class="form-group">	
			    <label><?php echo $lang['currency']; ?> <?php echo $lang['symbol']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                <input type="text" id="currency_symbol" class="form-control" placeholder="e.g $"/>
               </div>
              </div> 
	         
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addcurrency()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>		 		


		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['currency']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['code']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['symbol']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("currency", "*", ["ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
	
					    echo '<tr>';
					    echo '<td>'. escape($row->currency_code) .'</td>';
					    echo '<td>'. escape($row->currency_name) .'</td>';
					    echo '<td>'. escape($row->currency_symbol) .'</td>';
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '<td>
					      <a onclick="getCurrencyDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-currency btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>
                          </td>';
					    echo '</tr>';
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['code']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['symbol']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
	      <!-- Modal HTML -->
	      <div id="editcurrency" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['link']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
               <input type="hidden" id="update_currencyid"/>

              <div class="form-group">	
			    <label><?php echo $lang['currency']; ?> <?php echo $lang['code']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_currency_code" class="form-control" placeholder="e.g USD"/>
               </div>
              </div>     
               
              <div class="form-group">	
			    <label><?php echo $lang['currency']; ?> <?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_currency_name" class="form-control" placeholder="e.g Mexican Peso"/>
               </div>
              </div>      
               
              <div class="form-group">	
			    <label><?php echo $lang['currency']; ?> <?php echo $lang['symbol']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                <input type="text" id="update_currency_symbol" class="form-control" placeholder="e.g $"/>
               </div>
              </div>  
	         
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="updatecurrency()"  class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>              
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>	    		 	
                                             
		 <?php elseif (Input::get('a') == 'membership') : ?>
       <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 	<div class="col-md-12">
		 	
	       <div class="alert alert-warning fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['note']; ?></strong> <?php echo $lang['mem_set']; ?>
		   </div>
		 		
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['set']; ?> <?php echo $lang['default']; ?> <?php echo $lang['membership']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                  <input type="hidden" name="pid" value="<?php echo escape($pid); ?>"/>
                  
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select name="membership_select" type="text" class="form-control">
					 <?php
					  $query = DB::getInstance()->get("membership_freelancer", "*", ["ORDER" => "item_order ASC"]);
						if ($query->count()) {
						   $name = '';
						   $x = 1;
							 foreach ($query->results() as $row) {
	                          if (isset($_POST['membership_submit'])) {
                                $selected = ($row->membershipid === Input::get('membership_select')) ? ' selected="selected"' : '';
							  } else {
                                $selected = ($row->membershipid === $membershipid) ? ' selected="selected"' : '';
							  }
							  echo $name .= '<option value = "' . $row->membershipid . '" '.$selected.'> ' . $row->name . '</option>';
							  unset($name); 
							  $x++;
						     }
						}
						
					 ?>	
					 <?php
					  $query = DB::getInstance()->get("membership_agency", "*", ["ORDER" => "item_order ASC"]);
						if ($query->count()) {
						   $name = '';
						   $x = 1;
							 foreach ($query->results() as $row) {
	                          if (isset($_POST['membership_submit'])) {
                                $selected = ($row->membershipid === Input::get('membership_select')) ? ' selected="selected"' : '';
							  } else {
                                $selected = ($row->membershipid === $membershipid) ? ' selected="selected"' : '';
							  }
							  echo $name .= '<option value = "' . $row->membershipid . '" '.$selected.'> ' . $row->name . '</option>';
							  unset($name); 
							  $x++;
						     }
						}
						
					 ?>	
					</select>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="membership_submit" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->		 		
		 		
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>	    		 	
	    
		 <?php elseif (Input::get('a') == 'sponsorship') : ?>
       <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 	<div class="col-md-12">
		 		
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['sponsor']; ?> <?php echo $lang['jobs']; ?> & <?php echo $lang['bids']; ?> <?php echo $lang['settings']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                  <input type="hidden" name="pid" value="<?php echo escape($pid); ?>"/>
                      
                  <div class="form-group">	
				    <label><?php echo $lang['cost']; ?> <?php echo $lang['to']; ?> <?php echo $lang['sponsor']; ?> <?php echo $lang['a']; ?> <?php echo $lang['job']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                    <input type="text" name="jobs_cost" class="form-control" value="<?php
                         if (isset($_POST['sponsorship_submit'])) {
							 echo escape(Input::get('jobs_cost')); 
						  } else {
						  echo escape($jobs_cost); 
						  }
					  ?>" />
                   </div>
                  </div> 
                  
                  <div class="form-group">	
				    <label><?php echo $lang['job']; ?> <?php echo $lang['list']; ?> <?php echo $lang['time']; ?> <?php echo $lang['limit']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select name="jobs_pay_limit" type="text" class="form-control" >
                    <?php 
                     $jl = [
                           '1 '. $lang['days'] .''=>'1',
                           '2 '. $lang['days'] .''=>'2',
                           '3 '. $lang['days'] .''=>'3',
                           '4 '. $lang['days'] .''=>'4',
                           '5 '. $lang['days'] .''=>'5',
                           '6 '. $lang['days'] .''=>'6',
                           '7 '. $lang['days'] .''=>'7',
                           '8 '. $lang['days'] .''=>'8',
                           '9 '. $lang['days'] .''=>'9',
                           '10 '. $lang['days'] .''=>'10'
                           ];
					$x = 1;
					foreach ($jl as $key => $value) {
	                     if (isset($_POST['sponsorship_submit'])) {
                           $selected = ($value === Input::get('jobs_pay_limit')) ? ' selected="selected"' : '';
						  } else {
                           $selected = ($value === $jobs_pay_limit) ? ' selected="selected"' : '';
						  }
					  echo $opt .= '<option value = "' . $value . '" '.$selected.'>' . $key . '</option>';
					  unset($opt); 
					  $x++;
					} ?>
					 </select>
                   </div>
                  </div>        

                  <div class="form-group">	
				    <label><?php echo $lang['cost']; ?> <?php echo $lang['to']; ?> <?php echo $lang['sponsor']; ?> <?php echo $lang['a']; ?> <?php echo $lang['bid']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                    <input type="text" name="bids_cost" class="form-control" value="<?php
                         if (isset($_POST['sponsorship_submit'])) {
							 echo escape(Input::get('bids_cost')); 
						  } else {
						  echo escape($bids_cost); 
						  }
					  ?>" />
                   </div>
                  </div> 
                  
                  <div class="form-group">	
				    <label><?php echo $lang['bids']; ?> <?php echo $lang['time']; ?> <?php echo $lang['limit']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select name="bids_limit" type="text" class="form-control" >
                    <?php 
					$x = 1;
					foreach ($jl as $key => $value) {
	                     if (isset($_POST['sponsorship_submit'])) {
                           $selected = ($value === Input::get('bids_limit')) ? ' selected="selected"' : '';
						  } else {
                           $selected = ($value === $bids_limit) ? ' selected="selected"' : '';
						  }
					  echo $opt .= '<option value = "' . $value . '" '.$selected.'>' . $key . '</option>';
					  unset($opt); 
					  $x++;
					} ?>
					 </select>
                   </div>
                  </div>                             
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="sponsorship_submit" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->		 		
		 		
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>		
	    
		 <?php elseif (Input::get('a') == 'percentage') : ?>
       <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 	<div class="col-md-12">
		 		
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['set']; ?> 
                  	                    <?php echo $lang['job']; ?> 
                  	                    <?php echo $lang['fee']; ?> 
                  	                    <?php echo $lang['percentage']; ?> 
                  	                    <?php echo $lang['on']; ?> 
                  	                    <?php echo $lang['all']; ?> 
                  	                    <?php echo $lang['jobs']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                  <input type="hidden" name="pid" value="<?php echo escape($pid); ?>"/>
                      
                  <div class="form-group">	
				    <label><?php echo $lang['percentage']; ?> 
                            <?php echo $lang['of']; ?> 
                            <?php echo $lang['job']; ?> 
                            <?php echo $lang['fee']; ?>
                            <?php echo $lang['taken']; ?>
                            <?php echo $lang['by']; ?>
                            <?php echo $lang['company']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                    <input type="text" name="jobs_percentage" class="form-control" value="<?php
                         if (isset($_POST['percentage_submit'])) {
							 echo escape(Input::get('jobs_percentage')); 
						  } else {
						  echo escape($jobs_percentage); 
						  }
					  ?>" />
                   </div>
                  </div>                            
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="percentage_submit" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->		 		
		 		
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>	    
	    
		 <?php elseif (Input::get('a') == 'analytics') : ?>
       <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 	<div class="col-md-12">
		 		
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title">Google <?php echo $lang['analytics']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>   
                                
                  <div class="form-group">	
				    <label>Google <?php echo $lang['analytics']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="google_analytics" class="form-control" rows="6"><?php
                         if (isset($_POST['site_analytics'])) {
							 echo escape(Input::get('google_analytics')); 
						  } else {
						  echo escape($google_analytics); 
						  }
					  ?></textarea>
                   </div>
                  </div>  
                                                 
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="site_analytics" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->		 		
		 		
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>	 
	    			 
		 <?php endif; ?>
		 
		</div><!-- /.col -->
		
        
			 
	    </div><!-- /.row -->		  		  
	   </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
	 	
      <!-- Include footer.php. Contains footer content. -->	
	  <?php include 'template/footer.php'; ?>	
	 	
     </div><!-- /.wrapper -->   

	
	<!-- ==============================================
	 Scripts
	 =============================================== -->
	 
    <!-- jQuery 2.1.4 -->
    <script src="../assets/js/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.6 JS -->
    <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- DATA TABES SCRIPT -->
    <script src="../assets/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>
    <!-- page script -->
    <script type="text/javascript">
      $(function () {
        $("#example1").dataTable({
        /* No ordering applied by DataTables during initialisation */
        "order": []
        });
      });
    </script>
    <!-- FontAwesome Icon Picker -->
    <script src="../assets/js/fontawesome-iconpicker.min.js" type="text/javascript"></script>
	<script type="text/javascript">
    $('.icp-auto').iconpicker();	
	</script> 
    <script type="text/javascript">
	$(function() {
	$(".btn-currency").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_currency']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletecurrency.php",
				 data: info,
				 success: function()
					   {
						parent.fadeOut('slow', function() {$(this).remove();});
					   }
				});
			 
	
			  }
		   return false;
	
		});
	
	});
	</script>  
   <script type="text/javascript">
 	// Add Currency
	function addcurrency() {	
	    // get values
	    var currency_code = $("#currency_code").val();
	    var currency_name = $("#currency_name").val();
	    var currency_symbol = $("#currency_symbol").val();
	    
		//Built a url to send    
		var info = "currency_code="+currency_code+"&currency_name="+currency_name+"&currency_symbol="+currency_symbol;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/addcurrency.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#addcurrency").modal("hide");
            }
        });
	}   
	function getCurrencyDetails(id) {
	    // Add User ID to the hidden field for furture usage
	    $("#update_currencyid").val(id);
	    $.post("template/requests/readcurrencydetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var currency = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_currency_code").val(currency.currency_code);
	            $("#update_currency_name").val(currency.currency_name);
	            $("#update_currency_symbol").val(currency.currency_symbol);
	        }
	    );
	    // Open modal popup
	    $("#editcurrency").modal("show");
	}  
	function updatecurrency() {
	    // get values
	    var currencyid = $("#update_currencyid").val();
	    var currency_code = $("#update_currency_code").val();
	    var currency_name = $("#update_currency_name").val();
	    var currency_symbol = $("#update_currency_symbol").val();
	    
		//Built a url to send       
		var info = "currency_code="+currency_code+"&currency_name="+currency_name+"&currency_symbol="+currency_symbol+"&currencyid="+currencyid;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/updatecurrency.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#editcurrency").modal("hide");
            }
        });
	}		  
   </script>
    
</body>
</html>
