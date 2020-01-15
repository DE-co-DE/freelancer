<?php
//Check if init.php exists
if(!file_exists('../core/init.php')){
	header('Location: ../install/');        
    exit;
}else{
 require_once '../core/init.php';	
}

//Start new Client object
$client = new Client();

//Check if Client is logged in
if (!$client->isLoggedIn()) {
  Redirect::to('../index.php');	
}

//Get Freelancer's Data
$query = DB::getInstance()->get("profile", "*", ["userid" => $client->data()->clientid, "LIMIT" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$nid = $row->id;
 	$location = $row->location;
 	$city = $row->city;
 	$country = $row->country;
 	$about = $row->about;
 }			
}	

//Edit Profile Data
if(isset($_POST['form_profile'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'location' => [
	     'required' => true,
	     'maxlength' => 200,
	     'minlength' => 2
	  ],
	  'city' => [
	     'required' => true,
	     'maxlength' => 200,
	     'minlength' => 2
	  ],
	  'country' => [
	     'required' => true,
	     'maxlength' => 200,
	     'minlength' => 2
	  ]	
	]);
		 
    if (!$validation->fails()) {
		
		$query = DB::getInstance()->get("profile", "*", ["userid" => $client->data()->clientid, "LIMIT" => 1]);
		if ($query->count() === 1) {
			
			//Update Profile
			$profileUpdate = DB::getInstance()->update('profile',[
			   'location' => Input::get('location'),
			   'city' => Input::get('city'),
			   'country' => Input::get('country')
			],[
			    'userid' => $client->data()->clientid
			  ]);
			
		   if (count($profileUpdate) > 0) {
				$updatedError = true;
			} else {
				$hasError = true;
			}
							
		} else {
			
			try{
			   $profileid = uniqueid();	
			   $profileInsert = DB::getInstance()->insert('profile', array(
				   'profileid' => $profileid,
				   'userid' => $client->data()->clientid,
				   'location' => Input::get('location'),
				   'city' => Input::get('city'),
				   'country' => Input::get('country'),
				   'active' => 1,
				   'delete_remove' => 0,
				   'date_added' => date('Y-m-d H:i:s')
			    ));	
					
			  if (count($profileInsert) > 0) {
				$noError = true;
			  } else {
				$hasError = true;
			  }
				  
			  
			}catch(Exception $e){
			 die($e->getMessage());	
			}
						
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

//Edit About Data
if(isset($_POST['form_about'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'about_desc' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		$query = DB::getInstance()->get("profile", "*", ["userid" => $client->data()->clientid, "LIMIT" => 1]);
		if ($query->count() === 1) {
			
			//Update About
			$aboutUpdate = DB::getInstance()->update('profile',[
			   'about' => Input::get('about_desc')
			],[
			    'userid' => $client->data()->clientid
			  ]);
			
		   if (count($aboutUpdate) > 0) {
				$updatedError = true;
			} else {
				$hasError = true;
			}
							
		} else {
			
			try{
			   $profileid = uniqueid();	
			   $profileInsert = DB::getInstance()->insert('profile', array(
				   'profileid' => $profileid,
				   'userid' => $client->data()->clientid,
				   'about' => Input::get('about_desc'),
				   'active' => 1,
				   'delete_remove' => 0,
				   'date_added' => date('Y-m-d H:i:s')
			    ));	
					
			  if (count($profileInsert) > 0) {
				$noError = true;
			  } else {
				$hasError = true;
			  }
				  
			  
			}catch(Exception $e){
			 die($e->getMessage());	
			}
						
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
          <h1><?php echo $lang['overview']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['oveview']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  	
		 <div class="row">	
		 	
		 <div class="col-lg-12">
		  <?php if (isset($error)) {
			  echo $error;
		  } ?>
	
		  <?php if(isset($updatedError) && $updatedError == true) { //If email is sent ?>
		   <div class="alert alert-success fade in">
		   <a href="#" class="close" data-dismiss="alert">&times;</a>
		   <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?></strong>
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
		   <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['saved_success']; ?></strong>
		   </div>
		  <?php } ?>		 	
		  
          </div>	
           
          <div class="col-lg-4">
          	<?php $profile = (Input::get('a') == 'profile') ? ' active' : ''; ?>
          	<?php $selected = (Input::get('a') == 'about') ? ' active' : ''; ?>
	         <div class="list-group">
	         <a href="overview.php?a=profile" class="list-group-item<?php echo $profile; ?>">
	          <em class="fa fa-fw fa-info text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['profile']; ?>
			 </a>
	         <a href="overview.php?a=about" class="list-group-item<?php echo $selected; ?>">
	          <em class="fa fa-fw fa-user-md text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['about']; ?>
			 </a>
			 
	         </div>
          </div>
          
		 <div class="col-lg-8">
		 <?php if (Input::get('a') == 'profile') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['profile']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="addstudentform"> 
                  <div class="form-group">	
				    <label><?php echo $lang['location']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="location" class="form-control" value="<?php
                         if (isset($_POST['form_profile'])) {
							 echo escape(Input::get('location')); 
						  } else {
						  echo escape($location); 
						  }
					  ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
				    <label><?php echo $lang['city']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="city" class="form-control" value="<?php
                         if (isset($_POST['form_profile'])) {
							 echo escape(Input::get('city')); 
						  } else {
						  echo escape($city); 
						  }
					  ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
				    <label><?php echo $lang['country']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-flag"></i></span>
                    <input type="text" name="country" class="form-control" value="<?php
                         if (isset($_POST['form_profile'])) {
							 echo escape(Input::get('country')); 
						  } else {
						  echo escape($country); 
						  }
					  ?>">
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="form_profile" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              		 	
		 <?php elseif (Input::get('a') == 'about') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['about']; ?> <?php echo $lang['details']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform">              
                  
                  <div class="form-group">	
				    <label><?php echo $lang['about']; ?> <?php echo $lang['description']; ?></label>
                      <textarea type="text" id="summernote" name="about_desc" class="form-control">
                      	<?php
                         if (isset($_POST['form_about'])) {
							 echo escape(Input::get('about_desc')); 
						  } else {
						  echo escape($about); 
						  }
					  ?></textarea>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="form_about" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
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
    
</body>
</html>
