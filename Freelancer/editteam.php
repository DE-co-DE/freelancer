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

//Getting Team Data
$teamid = Input::get('id');
$query = DB::getInstance()->get("team", "*", ["teamid" => $teamid, "LIMIT" => 1]);
if ($query->count() === 1) {
 foreach($query->results() as $row) {
  $nid = $row->id;
  $name = $row->name;
  $team_title = $row->title;
  $team_desc = $row->description;
  $facebook = $row->facebook;
  $twitter = $row->twitter;
  $linkedin = $row->linkedin;
  $imagelocation = $row->imagelocation;
 }
} else {
  Redirect::to('teamlist.php');
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
		
		//Update Team
		$teamUpdate = DB::getInstance()->update('team',[
		   'description' => Input::get('team_desc'),
		   'name' => Input::get('name'),
		   'title' => Input::get('title'),
           'facebook' => Input::get('facebook'),
		   'twitter' => Input::get('twitter'),
		   'linkedin' => Input::get('linkedin')
		],[
		    'teamid' => $teamid
		  ]);
		
	   if (count($teamUpdate) > 0) {
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
  	
	$path = "uploads/team/";
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
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {
	      	
				unlink($imagelocation);
				$teamUpdate = DB::getInstance()->update('team',[
				    'imagelocation' => $newname
				],[
				    'teamid' => $teamid
				]);
				
			   if (count($teamUpdate) > 0) {
					$updatedError = true;
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
            <li class="active"><?php echo $lang['edit']; ?> <?php echo $lang['teammate']; ?></li>
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
	         <div class="list-group">
	         <a href="editteam.php?a=data&id=<?php echo $teamid ?>" class="list-group-item<?php echo $selected; ?>">
	          <em class="fa fa-fw fa-user-md text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['freelancer']; ?> <?php echo $lang['details']; ?>
			 </a>
	         <a href="editteam.php?a=image&id=<?php echo $teamid ?>" class="list-group-item<?php echo $image; ?>">
	          <em class="fa fa-fw fa-image text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['freelancer']; ?> <?php echo $lang['image']; ?>
			 </a>
			 
	         </div>
          </div>
          
		 <div class="col-lg-8">
		 <?php if (Input::get('a') == 'data') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['team']; ?> <?php echo $lang['details']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                  <div class="form-group">	
				    <label><?php echo $lang['name']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="name" class="form-control" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('name')); 
						  } else {
						  echo escape($name); 
						  }
					  ?>"/>
                   </div>
                  </div> 
                  <div class="form-group">	
				    <label><?php echo $lang['title']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-edit"></i></span>
                    <input type="text" name="title" class="form-control" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('title')); 
						  } else {
						  echo escape($team_title); 
						  }
					  ?>"/>
                   </div>
                  </div> 
                  <div class="form-group">	
				    <label><?php echo $lang['description']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea class="form-control" name="team_desc" rows="4"><?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('team_desc')); 
						  } else {
						  echo escape($team_desc); 
						  }
					  ?></textarea>
                   </div>
                  </div>    
                  <div class="form-group">	
				    <label><?php echo $lang['facebook_url']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" name="facebook" class="form-control" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('facebook')); 
						  } else {
						  echo escape($facebook); 
						  }
					  ?>"/>
                   </div>
                  </div>     
                  <div class="form-group">	
				    <label><?php echo $lang['twitter_url']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" name="twitter" class="form-control" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('twitter')); 
						  } else {
						  echo escape($twitter); 
						  }
					  ?>"/>
                   </div>
                  </div>     
                  <div class="form-group">	
				    <label><?php echo $lang['linkedin_url']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-link"></i></span>
                    <input type="text" name="linkedin" class="form-control" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('linkedin')); 
						  } else {
						  echo escape($linkedin); 
						  }
					  ?>"/>
                   </div>
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
                  <input type="hidden" name="imagelocation" class="form-control" value="<?php echo escape($imagelocation); ?>"/>
                  <div class="box-body">
                    <div class="form-group">
					 <div class="image text-center">
					  <img src="<?php 
					  if (isset($_POST['picture'])) {
						 echo escape($newname); 
					  } else {
					  echo escape($imagelocation); 
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
