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


//Add Instructor Function
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
	     'minlength' => 2,
	     'unique' => 'freelancer'
	  ],
	   'phone' => [
	     'required' => true,
	     'digit' => true,
	     'unique' => 'freelancer'
	   ],	
	   'payment' => [
	     'required' => true,
	     'digit' => true,
	   ],		 
	  'username' => [
	     'required' => true,
	     'maxlength' => 100,
	     'minlength' => 3,
	     'alnum' => true,
	     'unique' => 'freelancer'
	  ],
	   'password' => [
	     'required' => true,
	     'minlength' => 4
	   ],
	   'confirmPassword' => [
	     'match' => 'password'
	   ]
	]);
		 
    if (!$validation->fails()) {

	$path = "../Freelancer/uploads/";
	$path_new = "uploads/";
    $valid_formats = array("jpg", "png", "gif", "bmp");
   
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];

    if(strlen($name))
	{
	  list($txt, $ext) = explode(".", $name);
      if(in_array($ext,$valid_formats) && $size<(700*400))
	   {
	     $actual_image_name = time().substr($txt, 5).".".$ext;
		 $image_name = $actual_image_name;
		 $newname=$path.$image_name;
		 $newname_path = $path_new.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {

			try{
			   $freelancerid = uniqueid();	
		       $salt = Hash::salt(32);  
			   $freelancerInsert = DB::getInstance()->insert('freelancer', array(
				   'freelancerid' => $freelancerid,
				   'username' => Input::get('username'),
				   'password' => Hash::make(Input::get('password'), $salt),
				   'salt' => $salt,
				   'name' => Input::get('name'),
		           'email' => Input::get('email'),
				   'phone' => Input::get('phone'),
				   'account' => 0,
				   'imagelocation' => $newname_path,
		           'joined' => date('Y-m-d H:i:s'),
				   'active' => 1,
		           'user_type' => 1
			    ));	
					
			  if (count($freelancerInsert) > 0) {
				$noError = true;
			  } else {
				$hasError = true;
			  }
				  
			  
			}catch(Exception $e){
			 die($e->getMessage());	
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
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $lang['freelancer']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['add']; ?> <?php echo $lang['freelancer']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
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
           
		 <div class="col-lg-12">
		 
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['add']; ?> <?php echo $lang['freelancer']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
                  
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="<?php echo $lang['full_name']; ?>" value="<?php echo escape(Input::get('name')); ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-mail-reply"></i></span>
                    <input type="text" name="email" class="form-control" placeholder="<?php echo $lang['email']; ?>" value="<?php echo escape(Input::get('email')); ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    <input type="text" name="phone" class="form-control" placeholder="<?php echo $lang['phone']; ?>" value="<?php echo escape(Input::get('phone')); ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="<?php echo $lang['username']; ?>" value="<?php echo escape(Input::get('username')); ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="<?php echo $lang['password']; ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" name="confirmPassword" class="form-control" placeholder="<?php echo $lang['confirm_password']; ?>"/>
                   </div>
                  </div>
                  
                  <br></br>
                   <div style="position:relative;">
	                <a class='btn btn-primary' href='javascript:;'>
		            <?php echo $lang['choose']; ?> <?php echo $lang['freelancer']; ?> <?php echo $lang['image']; ?>...
		            <input type="file" name="photoimg" id="photoimg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
	                <input type="hidden" name="image_name" id="image_name"/>
	                </a>
	                &nbsp;
	                <span class='label label-info' id="upload-file-info"></span>
                  </div>
                  <br></br>
                           
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="data" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
		 
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
