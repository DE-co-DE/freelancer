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
 	$about_top_title = $row->about_top_title;
 	$about_header_img = $row->about_header_img;
 	$about_hello = $row->about_hello;
 	$about_whitebg = $row->about_whitebg;
 	$teamtagline = $row->teamtagline;
 	$timelinetagline = $row->timelinetagline;
 }			
}	

//Edit Site Settings Data
if(isset($_POST['site'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'about_top_title' => [
	     'required' => true,
	     'maxlength' => 100,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'about_top_title' => Input::get('about_top_title')
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
  	
	$path = "../assets/img/about/";
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
		 $path_new = "assets/img/about/";
		 $newname=$path_new.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {
	       	$new_bgimage = '../'.$about_header_img;
			unlink($new_bgimage);
				$sid = Input::get('sid');
				$siteUpdate = DB::getInstance()->update('settings',[
				    'about_header_img' => $newname
			    ],[
			    'id' => $sid
			    ]);
				
				if (count($siteUpdate) > 0) {
					$noError = true;
				} else {
					$hasError = true;
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
if(isset($_POST['abthello'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'about_hello' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'about_hello' => Input::get('about_hello')
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
if(isset($_POST['abtwhitebg'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'about_whitebg' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'about_whitebg' => Input::get('about_whitebg')
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
if(isset($_POST['team_tagline'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'teamtagline' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'teamtagline' => Input::get('teamtagline')
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

//Edit Timeline Tagline Data
if(isset($_POST['timeline_tagline'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'timelinetagline' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'timelinetagline' => Input::get('timelinetagline')
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
    
	<!-- ==============================================
	 Scripts
	 =============================================== -->
	 
    <!-- jQuery 2.1.4 -->
    <script src="../assets/js/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.6 JS -->
    <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>
    <!-- Jquery UI 1.10.3 -->
	<script src="../assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- page script -->
    <script type="text/javascript">$(function() {
    $('#sortable').sortable({
        axis: 'y',
        opacity: 0.7,
        update: function(event, ui) {
            var list_sortable = $(this).sortable('toArray').toString();
    		// change order in the database using Ajax
            $.ajax({
                url: 'template/requests/set_timeline.php',
                type: 'POST',
                data: {list_order:list_sortable},
                success: function(data) {
                    //finished
                }
            });
        }
    }); // fin sortable
   });
    </script>      

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
          	<?php $hello = (Input::get('a') == 'hello') ? ' active' : ''; ?>
          	<?php $whitebg = (Input::get('a') == 'whitebg') ? ' active' : ''; ?>
          	<?php $team = (Input::get('a') == 'team') ? ' active' : ''; ?>
          	<?php $teamtag = (Input::get('a') == 'teamtag') ? ' active' : ''; ?>
          	<?php $timelinetag = (Input::get('a') == 'timelinetag') ? ' active' : ''; ?>
          	<?php $timeline = (Input::get('a') == 'timeline') ? ' active' : ''; ?>
	         <div class="list-group">
	         <a href="about.php?a=details" class="list-group-item<?php echo $selected; ?>">
	          <em class="fa fa-fw fa-info-circle text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['about']; ?> <?php echo $lang['details']; ?>
			 </a>
	         <a href="about.php?a=headerimg" class="list-group-item<?php echo $headerimg; ?>">
	          <em class="fa fa-fw fa-image text-white"></em>&nbsp;&nbsp;&nbsp; <?php echo $lang['about']; ?> <?php echo $lang['header']; ?> <?php echo $lang['image']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="about.php?a=hello" class="list-group-item<?php echo $hello; ?>">
	          <em class="fa fa-fw fa-info-circle text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['about']; ?> <?php echo $lang['hello']; ?>
			 </a>
	         <a href="about.php?a=whitebg" class="list-group-item<?php echo $whitebg; ?>">
	          <em class="fa fa-fw fa-info-circle text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['about']; ?> <?php echo $lang['tagline']; ?>
			 </a>
	         <a href="about.php?a=teamtag" class="list-group-item<?php echo $teamtag; ?>">
	          <em class="fa fa-fw fa-plus-square text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['team']; ?> <?php echo $lang['tagline']; ?>
			 </a>
	         <a href="about.php?a=team" class="list-group-item<?php echo $team; ?>">
	          <em class="fa fa-fw fa-users text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['team']; ?>
			 </a>
	         <a href="about.php?a=timelinetag" class="list-group-item<?php echo $timelinetag; ?>">
	          <em class="fa fa-fw fa-plus-square text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['timeline']; ?> <?php echo $lang['tagline']; ?>
			 </a>
	         <a href="about.php?a=timeline" class="list-group-item<?php echo $timeline; ?>">
	          <em class="fa fa-fw fa-bars text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['timeline']; ?>
			 </a>
			 
	         </div>
          </div>
          
		 <div class="col-lg-8">
		 <?php if (Input::get('a') == 'details') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['about']; ?> <?php echo $lang['page']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
				    <label><?php echo $lang['top']; ?> <?php echo $lang['title']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="about_top_title" class="form-control" rows="3"><?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('about_top_title')); 
						  } else {
						  echo escape($about_top_title); 
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
					  echo escape($about_header_img); 
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
              
		 <?php elseif (Input::get('a') == 'hello') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['about']; ?> <?php echo $lang['hello']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>

                  <div class="form-group">	
				    <label><?php echo $lang['hello']; ?></label>
                      <textarea type="text" id="summernote" name="about_hello" class="form-control"><?php
                         if (isset($_POST['abthello'])) {
							 echo escape(Input::get('about_hello')); 
						  } else {
						  echo escape($about_hello); 
						  }
					  ?></textarea>
                  </div>                   
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="abthello" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
		 <?php elseif (Input::get('a') == 'whitebg') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['about']; ?> <?php echo $lang['tagline']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
				    <label><?php echo $lang['tagline']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="about_whitebg" class="form-control" rows="5"><?php
                         if (isset($_POST['abtwhitebg'])) {
							 echo escape(Input::get('about_whitebg')); 
						  } else {
						  echo escape($about_whitebg); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="abtwhitebg" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->   
              
		 <?php elseif (Input::get('a') == 'teamtag') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['team']; ?> <?php echo $lang['tagline']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
				    <label><?php echo $lang['tagline']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="teamtagline" class="form-control" rows="3"><?php
                         if (isset($_POST['team_tagline'])) {
							 echo escape(Input::get('teamtagline')); 
						  } else {
						  echo escape($teamtagline); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="team_tagline" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->  
		 <?php elseif (Input::get('a') == 'team') : ?>
		 	
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
	          <a href="#addteam" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['add']; ?> <?php echo $lang['teammate']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addteam" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['teammate']; ?></h4>
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
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div> 
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="description" class="form-control" rows="5"></textarea>
              </div>  
              
              <div class="form-group">	
			    <label>Facebook <?php echo $lang['url']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="facebook" class="form-control" placeholder="Facebook <?php echo $lang['url']; ?> eg. http://"/>
               </div>
              </div>   
              
              <div class="form-group">	
			    <label>Twitter <?php echo $lang['url']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="twitter" class="form-control" placeholder="Twitter <?php echo $lang['url']; ?> eg. http://"/>
               </div>
              </div>   
              
              <div class="form-group">	
			    <label>Linkedin <?php echo $lang['url']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="linkedin" class="form-control" placeholder="Linkedin <?php echo $lang['url']; ?> eg. http://"/>
               </div>
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
              <button onclick="addteam()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>
	      
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['team']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['image']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['description']; ?></th>
					   <th>Facebook</th>
					   <th>Twitter</th>
					   <th>Linkedin</th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("team", "*", ["AND" => ["testimony" => 2, "active" => 1, "delete_remove" => 0 ], "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
						$blurb = truncateHtml($row->description, 45);	
							
					    echo '<tr>';
					    echo '<td>'. escape($row->name) .'</td>';
					    echo '<td><img src="'. escape($row->imagelocation) .'" width="50" height="30" /></td>';
					    echo '<td>'. escape($row->title) .'</td>';
					    echo '<td>'. $blurb .'</td>';
					    echo '<td>'. escape($row->facebook) .'</td>';
					    echo '<td>'. escape($row->twitter) .'</td>';
					    echo '<td>'. escape($row->linkedin) .'</td>';
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '<td>
					      <a onclick="getTeamDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-team btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>
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
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['image']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['description']; ?></th>
					   <th>Facebook</th>
					   <th>Twitter</th>
					   <th>Linkedin</th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->  
              
	      <!-- Modal HTML -->
	      <div id="editteam" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['teammate']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
               <input type="hidden" id="update_teamid"/>    
              
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_name" class="form-control" placeholder="<?php echo $lang['name']; ?>"/>
               </div>
              </div>     
              
              <div class="form-group">	
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div> 
              
              <div class="form-group">	
			    <label>Facebook <?php echo $lang['url']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_facebook" class="form-control" placeholder="Facebook <?php echo $lang['url']; ?>"/>
               </div>
              </div>   
              
              <div class="form-group">	
			    <label>Twitter <?php echo $lang['url']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_twitter" class="form-control" placeholder="Twitter <?php echo $lang['url']; ?>"/>
               </div>
              </div> 
              
              <div class="form-group">	
			    <label>Linkedin <?php echo $lang['url']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_linkedin" class="form-control" placeholder="Linkedin <?php echo $lang['url']; ?>"/>
               </div>
              </div>    
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="update_description" class="form-control" rows="5"></textarea>
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
              <button onclick="updateteam()"  class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>    
              
		 <?php elseif (Input::get('a') == 'timelinetag') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['timeline']; ?> <?php echo $lang['tagline']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
				    <label><?php echo $lang['tagline']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="timelinetagline" class="form-control" rows="3"><?php
                         if (isset($_POST['timeline_tagline'])) {
							 echo escape(Input::get('timelinetagline')); 
						  } else {
						  echo escape($timelinetagline); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="timeline_tagline" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->              	      
	      
		 <?php elseif (Input::get('a') == 'timeline') : ?>
		 	
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
			  	
			  <?php if(Session::exists(updatedError) == true) { //If email is sent ?>
		       <div class="alert alert-success fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(updatedError); ?>	  
			  <br />
	          <a href="#addtimeline" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['add']; ?> <?php echo $lang['timeline']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addtimeline" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['timeline']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
              	
             <div class="form-group">
              <label><?php echo $lang['choose']; ?> <?php echo $lang['icon']; ?></label>
               <div class="input-group">
                <input data-placement="bottomRight" id="icon" class="form-control icp icp-auto" value="fa-archive" type="text" />
                <span class="input-group-addon"></span>
                </div>
              </div>

              <div class="form-group">	
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div> 
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="description" class="form-control" rows="5"></textarea>
              </div>  
              
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addtimeline()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>
	      
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['timeline']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['drag']; ?> <?php echo $lang['and']; ?> <?php echo $lang['drop']; ?></th>
					   <th><?php echo $lang['icon']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['description']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['item']; ?> <?php echo $lang['order']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody id="sortable">
				    <?php
                     $query = DB::getInstance()->get("timeline", "*", ["ORDER" => "item_order ASC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
							
					    echo '<tr id="'. escape($row->id) .'">';
						echo '<td class="drag-handle"><i class="fa fa-reorder"></i></td>';	
						echo '<td><i class="fa '. escape($row->icon) .'"></i></td>';	
					    echo '<td>'. escape($row->title) .'</td>';
					    echo '<td>'. escape($row->description) .'</td>';
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '<td>'. escape($row->item_order) .'</td>';
					    echo '<td>
					      <a onclick="getTimelineDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-timeline btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>
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
					   <th><?php echo $lang['drag']; ?> <?php echo $lang['and']; ?> <?php echo $lang['drop']; ?></th>
					   <th><?php echo $lang['icon']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['description']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['item']; ?> <?php echo $lang['order']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->  
              
	      <!-- Modal HTML -->
	      <div id="edittimeline" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['timeline']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
               <input type="hidden" id="update_timelineid"/>    
              
             <div class="form-group">
              <label><?php echo $lang['choose']; ?> <?php echo $lang['icon']; ?></label>
               <div class="input-group">
                <input data-placement="bottomRight" id="update_icon" class="form-control icp icp-auto" value="fa-archive" type="text" />
                <span class="input-group-addon"></span>
                </div>
              </div>
              
              <div class="form-group">	
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div>   
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="update_description" class="form-control" rows="5"></textarea>
              </div>      
              
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="updatetimeline()"  class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>                	      	      
                                       
		 <?php endif; ?>
		 
		</div><!-- /.col -->
		
        
			 
	    </div><!-- /.row -->		  		  
	   </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
	 	
      <!-- Include footer.php. Contains footer content. -->	
	  <?php include 'template/footer.php'; ?>	
	 	
     </div><!-- /.wrapper -->   
 
    <!-- Summernote WYSIWYG-->
    <script src="../assets/js/summernote.min.js" type="text/javascript"></script>    
    <script>
    $(document).ready(function() {
     $('#summernote').summernote({
		  height: 300,                 // set editor height
		
		  minHeight: null,             // set minimum height of editor
		  maxHeight: null,             // set maximum height of editor
		
		  focus: false,                 // set focus to editable area after initializing summernote
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
	
	
	$(".btn-team").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_team']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deleteteam.php",
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
	$(function() {
	
	
	$(".btn-timeline").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_timeline']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletetimeline.php",
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
	function addteam() {	

		var file_data = $('#photoimg').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('photoimg', file_data);              
		form_data.append('name', $("#name").val());             
		form_data.append('title', $("#title").val());             
		form_data.append('description', $("#description").val());   
		form_data.append('facebook', $("#facebook").val());             
		form_data.append('twitter', $("#twitter").val());             
		form_data.append('linkedin', $("#linkedin").val());  
		$.ajax({                      
				type: 'POST',
				url: 'template/requests/addteam.php', // point to server-side PHP script 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,   
				success: function(data){
			        // close the popup
			        $("#addteam").modal("hide");
				}
		 });		
		
	}  
	function getTeamDetails(id) {
	    // Add User ID to the hidden field for furture usage
	   // $("#hidden_user_id").val(id);
	    $("#update_teamid").val(id);
	    $.post("template/requests/readteamdetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var team = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_name").val(team.name);
	            $("#update_title").val(team.title);
	            $("#update_description").val(team.description);
	            $("#update_facebook").val(team.facebook);
	            $("#update_twitter").val(team.twitter);
	            $("#update_linkedin").val(team.linkedin);
	            $("#update_photoimg").html(team.imagename);
                $('#update_upload-file-info').html(team.imagename); 
  	            
	        }
	    );
	    // Open modal popup
	    $("#editteam").modal("show");
	}	
	function updateteam() {	

		var file_data = $('#update_photoimg').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('photoimg', file_data);              
		form_data.append('name', $("#update_name").val());             
		form_data.append('title', $("#update_title").val());   
		form_data.append('description', $("#update_description").val());  
		form_data.append('facebook', $("#update_facebook").val());             
		form_data.append('twitter', $("#update_twitter").val());   
		form_data.append('linkedin', $("#update_linkedin").val());           
		form_data.append('teamid', $("#update_teamid").val());   
		$.ajax({                      
				type: 'POST',
				url: 'template/requests/editteam.php', // point to server-side PHP script 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,   
				success: function(data){
			        // close the popup
			        $("#editteam").modal("hide");
				}
		 });		
		
	} 	
	// Add Timeline
	function addtimeline() {	
	    // get values
	    var icon = $("#icon").val();
	    var title = $("#title").val();
	    var description = $("#description").val();
	    
		//Built a url to send    
		var info = "icon="+icon+"&title="+title+"&description="+description;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/addtimeline.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#addtimeline").modal("hide");
            }
        });
	}   
	function getTimelineDetails(id) {
	    // Add User ID to the hidden field for furture usage
	    $("#update_timelineid").val(id);
	    $.post("template/requests/readtimelinedetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var timeline = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_title").val(timeline.title);
	            $("#update_description").val(timeline.description);
	            $("#update_icon").val(timeline.icon);
	        }
	    );
	    // Open modal popup
	    $("#edittimeline").modal("show");
	}  
	function updatetimeline() {
	    // get values
	    var timelineid = $("#update_timelineid").val();
	    var title = $("#update_title").val();
	    var description = $("#update_description").val();
	    var icon = $("#update_icon").val();
	    
		//Built a url to send       
		var info = "timelineid="+timelineid+"&title="+title+"&description="+description+"&icon="+icon;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/updatetimeline.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#edittimeline").modal("hide");
            }
        });
	}			
	</script>
    
</body>
</html>
