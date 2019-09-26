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

//Getting freelancer Data
$freelancerid = Input::get('id');
$query = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $freelancerid, "LIMIT" => 1]);
if ($query->count() === 1) {
 foreach($query->results() as $row) {
  $nid = $row->id;
  $freelancerid = $row->freelancerid;
  $freelancer_password = $row->password;
  $freelancer_salt = $row->salt;
  $name = $row->name;
  $username = $row->username;
  $email = $row->email;
  $phone = $row->phone;
  $freelancer_imagelocation = $row->imagelocation;
 }
} else {
  Redirect::to('freelancerlist.php');
}	

//Edit Profile Data
if(isset($_POST['details'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'name' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 200
	   ],
	  'email' => [
	     'required' => true,
	     'email' => true,
	     'maxlength' => 100,
	     'minlength' => 2
	  ],
	   'phone' => [
	     'required' => true,
	     'digit' => true
	   ],	
	   'payment' => [
	     'required' => true,
	     'digit' => true
	   ],		 
	  'username' => [
	     'required' => true,
	     'maxlength' => 100,
	     'minlength' => 3,
	     'alnum' => true
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		//Update Freelancer
		$freelancerUpdate = DB::getInstance()->update('freelancer',[
		   'username' => Input::get('username'),
		   'name' => Input::get('name'),
           'email' => Input::get('email'),
		   'phone' => Input::get('phone')
		],[
		    'freelancerid' => $freelancerid
		  ]);
		
	   if (count($freelancerUpdate) > 0) {
			$updatedError = true;
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

/*Edit Image Data*/
if(isset($_POST['picture'])){
if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
  	
	$path = "../Freelancer/uploads/";
	$path_new = "uploads/";
	$path_small = "../Freelancer/";
	$imagelocation = Input::get('imagelocation');
    $valid_formats = array("jpg", "png", "gif", "bmp");
   
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];

    if(strlen($name))
	{
	  list($txt, $ext) = explode(".", $name);
      if(in_array($ext,$valid_formats) && $size<(3024*3024))
	   {
	     $actual_image_name = time().substr($txt, 5).".".$ext;
		 $image_name = $actual_image_name;
		 $newname=$path.$image_name;
		 $newname_path = $path_new.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {
	      	
	       if ($imagelocation !== 'uploads/default.png') {
				unlink($path_small.$imagelocation);
				$freelancerUpdate = DB::getInstance()->update('freelancer',[
				    'imagelocation' => $newname_path
				],[
				    'freelancerid' => $freelancerid
				]);
				
			   if (count($freelancerUpdate) > 0) {
					$updatedError = true;
				} else {
					$hasError = true;
				}	 
		   } else {
				$freelancerUpdate = DB::getInstance()->update('freelancer',[
				    'imagelocation' => $newname_path
				],[
				    'freelancerid' => $freelancerid
				]);
				
			   if (count($freelancerUpdate) > 0) {
					$updatedError = true;
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

/*Edit Password Data*/
if(isset($_POST['register'])){
if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
 
 	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'password_current' => [
	     'required' => true,
	     'maxlength' => 300
	  ],
	   'password' => [
	     'required' => true,
	     'minlength' => 6
	   ],
	   'confirmPassword' => [
	     'required' => true,
	     'match' => 'password'
	   ]
	]);
		 
    if (!$validation->fails()) {
  	
		if (Hash::make(Input::get('password_current'), $freelancer_salt) !== $freelancer_password) {
			$passError = true;
		} else {
		  $salt = Hash::salt(32);			
		  
		  $passwordUpdate = DB::getInstance()->update('freelancer',[
		   'password' => Hash::make(Input::get('password'), $salt),
		   'salt' => $salt
			],[
			    'freelancerid' => $freelancerid
			]);
			
		   if (count($passwordUpdate) > 0) {
				$updatedError = true;
			} else {
				$hasError = true;
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
          <h1><?php echo $lang['freelancer']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['edit']; ?> <?php echo $lang['freelancer']; ?></li>
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
		 	
		  <?php if (isset($error)) {
			  echo $error;
		  } ?>  
		  
	     <?php if(isset($passError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['pass_Error']; ?>
		   </div>
	      <?php } ?>  
		  
	     <?php if(isset($hasError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
		   </div>
	      <?php } ?>
	
		  <?php if(isset($updatedError) && $updatedError == true) { //If email is sent ?>
		   <div class="alert alert-success fade in">
		   <a href="#" class="close" data-dismiss="alert">&times;</a>
		   <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?></strong>
		   </div>
		  <?php } ?>
          </div>	
           
          <div class="col-lg-4">
          	<?php $selected = (Input::get('a') == 'data') ? ' active' : ''; ?>
          	<?php $image = (Input::get('a') == 'image') ? ' active' : ''; ?>
          	<?php $active = (Input::get('a') == 'password') ? ' active' : ''; ?>
	         <div class="list-group">
	         <a href="editfreelancer.php?a=data&id=<?php echo $freelancerid ?>" class="list-group-item<?php echo $selected; ?>">
	          <em class="fa fa-fw fa-user-md text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['freelancer']; ?> <?php echo $lang['details']; ?>
			 </a>
	         <a href="editfreelancer.php?a=image&id=<?php echo $freelancerid ?>" class="list-group-item<?php echo $image; ?>">
	          <em class="fa fa-fw fa-image text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['freelancer']; ?> <?php echo $lang['image']; ?>
			 </a>
	         <a href="editfreelancer.php?a=password&id=<?php echo $freelancerid ?>" class="list-group-item<?php echo $active; ?>">
	          <em class="fa fa-fw fa-lock text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['change']; ?> <?php echo $lang['password']; ?>
			 </a>
			 
	         </div>
          </div>
          
		 <div class="col-lg-8">
		 <?php if (Input::get('a') == 'data') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['freelancer']; ?> <?php echo $lang['details']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="name" class="form-control" value="<?php echo escape($name); ?>" />
                   </div>
                    <p class="help-block"><?php echo $lang['freelancer']; ?> <?php echo $lang['name']; ?></p>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-mail-reply"></i></span>
                    <input type="text" name="email" class="form-control" value="<?php echo escape($email); ?>"/>
                   </div>
                    <p class="help-block"><?php echo $lang['freelancer']; ?> <?php echo $lang['email']; ?></p>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    <input type="text" name="phone" class="form-control" value="<?php echo escape($phone); ?>"/>
                   </div>
                    <p class="help-block"><?php echo $lang['freelancer']; ?> <?php echo $lang['phone']; ?></p>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="username" class="form-control" value="<?php echo escape($username); ?>"/>
                   </div>
                    <p class="help-block"><?php echo $lang['freelancer']; ?> <?php echo $lang['username']; ?></p>
                  </div>      
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="details" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
		 <?php elseif (Input::get('a') == 'image') : ?>
		  
		  		 <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['default_profile']; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="imagelocation" class="form-control" value="<?php echo escape($freelancer_imagelocation); ?>"/>
                  <div class="box-body">
                    <div class="form-group">
					 <div class="image text-center">
					  <img src="../Freelancer/<?php 
					  if (isset($_POST['picture'])) {
						 echo escape($newname); 
					  } else {
					  echo escape($freelancer_imagelocation); 
					  } ?>" class="img-thumbnail" width="215" height="215"/>
					 </div>
                    </div>
                   <div style="position:relative;">
	                <a class='btn btn-primary' href='javascript:;'>
		            <?php echo $lang['choose']; ?> <?php echo $lang['file']; ?>...
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

		 <?php elseif (Input::get('a') == 'password') : ?>	 
			 
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['password']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editpassform"> 
                  <input type="hidden" name="nid" value="<?php echo escape($nid); ?>"/>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password_current" class="form-control" placeholder="<?php echo $lang['current_password']; ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="<?php echo $lang['new_password']; ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="confirmPassword" class="form-control" placeholder="<?php echo $lang['confirm_new_password']; ?>"/>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="register" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
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
</body>
</html>
