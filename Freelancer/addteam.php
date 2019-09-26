<?php 
//Check if init.php exists
if(!file_exists('../core/init.php')){
	header('Location: ../install/');        
    exit;
}else{
 require_once '../core/init.php';	
}

//Start new Freelancer object
$freelancer = new Freelancer();

//Check if Freelancer is logged in
if (!$freelancer->isLoggedIn()) {
  Redirect::to('../index.php');	
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
	  'title' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 200
	   ],
	  'team_desc' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 500
	   ],
	  'facebook' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 200
	   ],
	  'twitter' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 200
	   ],
	  'linkedin' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 200
	   ]
	]);
		 
    if (!$validation->fails()) {

	$path = "uploads/team/";
    $valid_formats = array("jpg", "png", "gif", "bmp");
   
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];

    if(strlen($name))
	{
	  list($txt, $ext) = explode(".", $name);
      if(in_array($ext,$valid_formats) && $size<(700*400))
	   {
	     $actual_image_name = uniqid().time().substr($txt, 5).".".$ext;
		 $image_name = $actual_image_name;
		 $newname=$path.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {

			try{
			   $teamid = uniqueid();	
			   $teamInsert = DB::getInstance()->insert('team', array(
				   'description' => Input::get('team_desc'),
				   'teamid' => $teamid,
				   'userid' => $freelancer->data()->freelancerid,
				   'name' => Input::get('name'),
				   'title' => Input::get('title'),
		           'facebook' => Input::get('facebook'),
				   'twitter' => Input::get('twitter'),
				   'linkedin' => Input::get('linkedin'),
				   'imagelocation' => $newname,
				   'active' => 1,
		           'delete_remove' => 0,
		           'date_added' => date('Y-m-d H:i:s')
			    ));	
					
			  if (count($teamInsert) > 0) {
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
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1><?php echo $lang['team']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['add']; ?> <?php echo $lang['teammate']; ?></li>
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
                  <h3 class="box-title"><?php echo $lang['add']; ?> <?php echo $lang['teammate']; ?></h3>
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
                    <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                    <input type="text" name="title" class="form-control" placeholder="<?php echo $lang['title']; ?>" value="<?php echo escape(Input::get('title')); ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea class="form-control" name="team_desc" rows="4" placeholder="<?php echo $lang['description']; ?>"><?php echo escape(Input::get('team_desc')); ?></textarea>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" name="facebook" class="form-control" placeholder="<?php echo $lang['facebook_url']; ?>" value="<?php echo escape(Input::get('facebook')); ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" name="twitter" class="form-control" placeholder="<?php echo $lang['twitter_url']; ?>" value="<?php echo escape(Input::get('twitter')); ?>"/>
                   </div>
                  </div>
                  <div class="form-group">	
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" name="linkedin" class="form-control" placeholder="<?php echo $lang['linkedin_url']; ?>" value="<?php echo escape(Input::get('linkedin')); ?>"/>
                   </div>
                  </div>
                  
                  <br></br>
                   <div style="position:relative;">
	                <a class='btn btn-primary' href='javascript:;'>
		            <?php echo $lang['choose']; ?> <?php echo $lang['teammate']; ?> <?php echo $lang['image']; ?>...
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
