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

//Getting Service Data
$serviceid = Input::get('id');
$query = DB::getInstance()->get("service", "*", ["serviceid" => $serviceid, "LIMIT" => 1]);
if ($query->count() === 1) {
 foreach($query->results() as $row) {
  $service_title = $row->title;
  $catid = $row->catid;
  $rate = $row->rate;
  $service_desc = $row->description;
 }
} else {
  Redirect::to('servicelist.php');
}	

//Edit Category Data
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'title' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 200
	   ],
	  'category' => [
	     'required' => true
	   ],
	  'rate' => [
	     'required' => true,
	     'maxlength' => 200,
	     'minlength' => 2
	  ],
	  'service_desc' => [
		 'required' => true,
		 'minlength' => 2
	   ]
	]);
		 
    if (!$validation->fails()) {
		
		//Update Service
		$serviceUpdate = DB::getInstance()->update('service',[
		    'description' => Input::get('service_desc'),
		   'catid' => Input::get('category'),
		    'title' => Input::get('title'),
		    'rate' => Input::get('rate')
		],[
		    'serviceid' => $serviceid
		  ]);
		
	   if (count($serviceUpdate) > 0) {
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
          <h1><?php echo $lang['service']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['edit']; ?> <?php echo $lang['service']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
		 <div class="row">	
		 	
		 <div class="col-lg-12">
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
		 	
		  <?php if (isset($error)) {
			  echo $error;
		  } ?>  
          </div>	
           
          
		 <div class="col-lg-12">
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['service']; ?> <?php echo $lang['details']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                  <div class="form-group">	
				    <label><?php echo $lang['title']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="title" class="form-control" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('title')); 
						  } else {
						  echo escape($service_title); 
						  }
					  ?>"/>
                   </div>
                  </div>  
                                    
                  <div class="form-group">	
				    <label><?php echo $lang['category']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select name="category" type="text" class="form-control">
					 <?php
					  $query = DB::getInstance()->get("category", "*", ["AND" => ["active" => 1, "delete_remove" => 0]]);
						if ($query->count()) {
						   $categoryname = '';
						   $x = 1;
							 foreach ($query->results() as $row) {

	                         if (isset($_POST['details'])) {
	                         	$selected = (Input::get('category') === $catid) ? ' selected="selected"' : '';
							  } else {
							  	$selected = ($row->catid === $catid) ? ' selected="selected"' : '';
							  }
							  
							  echo $categoryname .= '<option value = "' . $row->catid . '" '.$selected.'>' . $row->name . '</option>';
							  unset($categoryname); 
							  $x++;
						     }
						}
					 ?>	
					</select>
                   </div>
                  </div>
                  
                  <div class="form-group">	
				    <label><?php echo $lang['rate']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input type="text" name="rate" class="form-control" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('rate')); 
						  } else {
						  echo escape($rate); 
						  }
					  ?>"/>
                   </div>
                  </div> 
                  <div class="form-group">	
				    <label><?php echo $lang['description']; ?></label>
                      <textarea type="text" id="summernote" name="service_desc" class="form-control">
                      	<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('service_desc')); 
						  } else {
						  echo $service_desc; 
						  }
					  ?></textarea>
                  </div>      
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="details" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
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
    <!-- Summernote WYSIWYG-->
    <script src="../assets/js/summernote.min.js" type="text/javascript"></script>    
    <script>
    $(document).ready(function() {
     $('#summernote').summernote({
		  height: 300,                 // set editor height
		
		  minHeight: null,             // set minimum height of editor
		  maxHeight: null,             // set maximum height of editor
		
		  focus: true,                 // set focus to editable area after initializing summernote
		});    
    });
    </script>
</body>
</html>
