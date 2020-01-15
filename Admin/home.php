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
 	$top_title = $row->top_title;
 	$show_downtitle = $row->show_downtitle;
 	$down_title = $row->down_title;
 	$searchterm = $row->searchterm;
 	$header_img = $row->header_img;
 	$cattagline = $row->cattagline;
 	$testtagline = $row->testtagline;
 	$statstagline = $row->statstagline;
 }			
}	

//Edit Site Settings Data
if(isset($_POST['site'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'top_title' => [
	     'required' => true,
	     'minlength' => 2
	  ],
	  'show_downtitle' => [
	     'required' => true
	  ],
	  'down_title' => [
	     'required' => true,
	     'minlength' => 2
	  ],
	  'searchterm' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$show_downtitle = (Input::get('show_downtitle') === 'on') ? 1 : 0;
			$siteUpdate = DB::getInstance()->update('settings',[
			    'top_title' => Input::get('top_title'),
			    'show_downtitle' => $show_downtitle,
			    'down_title' => Input::get('down_title'),
			    'searchterm' => Input::get('searchterm')
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
  	
	$path = "../assets/img/header/";
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
		 $path_new = "assets/img/header/";
		 $newname=$path_new.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {
	       if ($header_img !== 'assets/img/header/1.jpg') {
	       	$new_bgimage = '../'.$header_img;
			unlink($new_bgimage);
				$sid = Input::get('sid');
				$siteUpdate = DB::getInstance()->update('settings',[
				    'header_img' => $newname
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
				    'header_img' => $newname
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

//Edit Category Tagline Data
if(isset($_POST['cat_tagline'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'cattagline' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'cattagline' => Input::get('cattagline')
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

//Edit Category Tagline Data
if(isset($_POST['test_tagline'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'testtagline' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'testtagline' => Input::get('testtagline')
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

//Edit Stats Tagline Data
if(isset($_POST['stats_tagline'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'statstagline' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'statstagline' => Input::get('statstagline')
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
          	<?php $selected = (Input::get('a') == 'details') ? ' active' : ''; ?>
          	<?php $headerimg = (Input::get('a') == 'headerimg') ? ' active' : ''; ?>
          	<?php $cattag = (Input::get('a') == 'cattag') ? ' active' : ''; ?>
          	<?php $testtag = (Input::get('a') == 'testtag') ? ' active' : ''; ?>
          	<?php $testimonies = (Input::get('a') == 'testimonies') ? ' active' : ''; ?>
          	<?php $stats = (Input::get('a') == 'statstag') ? ' active' : ''; ?>
	         <div class="list-group">
	         <a href="home.php?a=details" class="list-group-item<?php echo $selected; ?>">
	          <em class="fa fa-fw fa-info-circle text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['header']; ?> <?php echo $lang['details']; ?>
			 </a>
	         <a href="home.php?a=headerimg" class="list-group-item<?php echo $headerimg; ?>">
	          <em class="fa fa-fw fa-image text-white"></em>&nbsp;&nbsp;&nbsp; <?php echo $lang['header']; ?> <?php echo $lang['image']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="home.php?a=cattag" class="list-group-item<?php echo $cattag; ?>">
	          <em class="fa fa-fw fa-info-circle text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['categories']; ?> <?php echo $lang['tagline']; ?>
			 </a>
	         <a href="home.php?a=testtag" class="list-group-item<?php echo $testtag; ?>">
	          <em class="fa fa-fw fa-info-circle text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['testimonies']; ?> <?php echo $lang['tagline']; ?>
			 </a>
	         <a href="home.php?a=testimonies" class="list-group-item<?php echo $testimonies; ?>">
	          <em class="fa fa-fw fa-plus-square text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['testimonies']; ?>
			 </a>
	         <a href="home.php?a=statstag" class="list-group-item<?php echo $stats; ?>">
	          <em class="fa fa-fw fa-plus-square text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['stats']; ?> <?php echo $lang['tagline']; ?>
			 </a>
			 
	         </div>
          </div>
          
		 <div class="col-lg-8">
		 <?php if (Input::get('a') == 'details') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['site']; ?> <?php echo $lang['settings']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
				    <label><?php echo $lang['top']; ?> <?php echo $lang['title']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="top_title" class="form-control" rows="3"><?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('top_title')); 
						  } else {
						  echo escape($top_title); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                  
				 <div class="form-group">
				   <label><?php echo $lang['show']; ?> <?php echo $lang['down']; ?> <?php echo $lang['title']; ?></label>
		            <ul class="list-group">
		             <li class="list-group-item flr">
		              <div class="material-switch text-center">
			           <span class="pull-left"><?php echo $lang['no']; ?></span>
		                <input id="update_rollover" name="show_downtitle" type="checkbox" <?php echo $active = ($show_downtitle == '1') ? ' checked' : ''; ?>/>
		                <label for="update_rollover" class="label-mint"></label>
			           <span class="pull-right"><?php echo $lang['yes']; ?></span>
		              </div>
		             </li>
		            </ul>  
				 </div><!-- /.form-row -->
				 
                  <div class="form-group">	
				    <label><?php echo $lang['down']; ?> <?php echo $lang['title']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="down_title" class="form-control" rows="5"><?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('down_title')); 
						  } else {
						  echo escape($down_title); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                  <div class="form-group">	
				    <label><?php echo $lang['search']; ?> <?php echo $lang['term']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="searchterm" class="form-control" rows="5"><?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('searchterm')); 
						  } else {
						  echo escape($searchterm); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="site" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
 		 <?php elseif (Input::get('a') == 'headerimg') : ?>
		  
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
					  echo escape($header_img); 
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
              
		 <?php elseif (Input::get('a') == 'cattag') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['category']; ?> <?php echo $lang['tagline']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
				    <label><?php echo $lang['tagline']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="cattagline" class="form-control" rows="3"><?php
                         if (isset($_POST['cat_tagline'])) {
							 echo escape(Input::get('cattagline')); 
						  } else {
						  echo escape($cattagline); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="cat_tagline" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
		 <?php elseif (Input::get('a') == 'testtag') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['testimonies']; ?> <?php echo $lang['tagline']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
				    <label><?php echo $lang['tagline']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="testtagline" class="form-control" rows="3"><?php
                         if (isset($_POST['test_tagline'])) {
							 echo escape(Input::get('testtagline')); 
						  } else {
						  echo escape($testtagline); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="test_tagline" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->   
              
		 <?php elseif (Input::get('a') == 'testimonies') : ?>
		 	
	  		 <div class="box box-info">
	        <div class="box-header">
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
			  
			  <?php if(Session::exists(selectError) == true) { //If email is sent ?>
		       <div class="alert alert-danger fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['image_select']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(selectError); ?>
			  
			  <?php if(Session::exists(formatError) == true) { //If email is sent ?>
		       <div class="alert alert-danger fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['image_format']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(formatError); ?>
			  
			  <?php if(Session::exists(imageError) == true) { //If email is sent ?>
		       <div class="alert alert-danger fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['image_upload']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(imageError); ?>
			  	
			  <?php if(Session::exists(updatedError) == true) { //If email is sent ?>
		       <div class="alert alert-success fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(updatedError); ?>	  
			  <br />
	          <a href="#addtestimony" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['add']; ?> <?php echo $lang['testimony']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addtestimony" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['testimony']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
              	
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="name" class="form-control" placeholder="<?php echo $lang['name']; ?>"/>
               </div>
              </div>     
              
              <div class="form-group">	
			    <label><?php echo $lang['company']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="company" class="form-control" placeholder="<?php echo $lang['company']; ?>"/>
               </div>
              </div> 
               
              <div class="form-group">	
			   <label><?php echo $lang['message']; ?></label>
               <textarea type="text" id="message" class="form-control" rows="5"></textarea>
              </div>    
              
               <div style="position:relative;">
                <a class='btn btn-success' href='javascript:;'>
	            <?php echo $lang['choose']; ?> <?php echo $lang['image']; ?>...
	            <input type="file" id="photoimg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
                <input type="hidden" name="image_name" id="image_name"/>
                </a>
                &nbsp;
                <span class='label label-success' id="upload-file-info"></span>
              </div>

	         
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addtestimony()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>
	      
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['testimony']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['image']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['company']; ?></th>
					   <th><?php echo $lang['message']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("team", "*", ["AND" => ["testimony" => 1, "active" => 1, "delete_remove" => 0 ], "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
							
					    echo '<tr>';
					    echo '<td>'. escape($row->name) .'</td>';
					    echo '<td><img src="'. escape($row->imagelocation) .'" width="50" height="30" /></td>';
					    echo '<td>'. escape($row->title) .'</td>';
					    echo '<td>'. escape($row->description) .'</td>';
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '<td>
					      <a onclick="getTestimonyDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-test btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>
                          </td>';
					    echo '</tr>';
						unset($delete);
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['image']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['company']; ?></th>
					   <th><?php echo $lang['message']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->  
              
	      <!-- Modal HTML -->
	      <div id="edittestimony" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['testimony']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
               <input type="hidden" id="update_testimonyid"/>    
              
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_name" class="form-control" placeholder="<?php echo $lang['name']; ?>"/>
               </div>
              </div>     
              
              <div class="form-group">	
			    <label><?php echo $lang['company']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_company" class="form-control" placeholder="<?php echo $lang['company']; ?>"/>
               </div>
              </div> 
               
              <div class="form-group">	
			   <label><?php echo $lang['message']; ?></label>
               <textarea type="text" id="update_message" class="form-control" rows="5"></textarea>
              </div>      
              
               <div style="position:relative;">
                <a class='btn btn-success' href='javascript:;'>
	            <?php echo $lang['choose']; ?> <?php echo $lang['file']; ?>...
	            <input type="file" id="update_photoimg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#update_upload-file-info").html($(this).val());'>
                <input type="hidden" name="image_name" id="update_image_name"/>
                </a>
                &nbsp;
                <span class='label label-success' id="update_upload-file-info"></span>
              </div>

	         
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="updatetestimony()"  class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>    
	      
		 <?php elseif (Input::get('a') == 'statstag') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['stats']; ?> <?php echo $lang['tagline']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
				    <label><?php echo $lang['tagline']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="statstagline" class="form-control" rows="3"><?php
                         if (isset($_POST['stats_tagline'])) {
							 echo escape(Input::get('statstagline')); 
						  } else {
						  echo escape($statstagline); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="stats_tagline" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->              	      
                                       
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
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>
    <script type="text/javascript">
	$(function() {
	
	
	$(".btn-test").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_testimony']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletetestimony.php",
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
	// Add File
	function addtestimony() {	

		var file_data = $('#photoimg').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('photoimg', file_data);              
		form_data.append('name', $("#name").val());             
		form_data.append('company', $("#company").val());             
		form_data.append('message', $("#message").val());   
		$.ajax({                      
				type: 'POST',
				url: 'template/requests/addtestimony.php', // point to server-side PHP script 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,   
				success: function(data){
			        // close the popup
			        $("#addtestimony").modal("hide");
				}
		 });		
		
	}  
	function getTestimonyDetails(id) {
	    // Add User ID to the hidden field for furture usage
	   // $("#hidden_user_id").val(id);
	    $("#update_testimonyid").val(id);
	    $.post("template/requests/readtestimonydetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var test = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_name").val(test.name);
	            $("#update_company").val(test.title);
	            $("#update_message").val(test.description);
	            $("#update_photoimg").html(test.imagename);
                $('#update_upload-file-info').html(test.imagename); 
  	            
	        }
	    );
	    // Open modal popup
	    $("#edittestimony").modal("show");
	}	
	function updatetestimony() {	

		var file_data = $('#update_photoimg').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('photoimg', file_data);              
		form_data.append('name', $("#update_name").val());             
		form_data.append('company', $("#update_company").val());   
		form_data.append('message', $("#update_message").val());           
		form_data.append('testimonyid', $("#update_testimonyid").val());   
		$.ajax({                      
				type: 'POST',
				url: 'template/requests/edittestimony.php', // point to server-side PHP script 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,   
				success: function(data){
			        // close the popup
			        $("#editfile").modal("hide");
				}
		 });		
		
	} 	
	</script>
    
</body>
</html>
