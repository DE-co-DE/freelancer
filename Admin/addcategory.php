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

//Add Category Function
if (Input::exists()) {
 if(Token::check(Input::get('token'))){
 	
	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'icon' => [
		 'required' => true,
	  ],
	  'name' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 200
	  ],
	  'sub_category' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 300
	  ]
	]);
		 
    if (!$validation->fails()) {
    	
			try{
			   $catid = time();	
			   $catInsert = DB::getInstance()->insert('category', array(
				   'catid' => $catid,
				   'icon' => Input::get('icon'),
				   'name' => Input::get('name'),
				   'sub_category' => Input::get('sub_category'),
				   'active' => 1,
				   'delete_remove' => 0,
				   'date_added' => date('Y-m-d H:i:s')
			    ));	
					
			  if (count($catInsert) > 0) {
				$noError = true;
			  } else {
				$hasError = true;
			  }
				  
			  
			}catch(Exception $e){
			 die($e->getMessage());	
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
          <h1><?php echo $lang['category']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['add']; ?> <?php echo $lang['category']; ?></li>
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
	
		  <?php if(isset($noError) && $noError == true) { //If email is sent ?>
		   <div class="alert alert-success fade in">
		   <a href="#" class="close" data-dismiss="alert">&times;</a>
		   <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['saved_success']; ?></strong>
		   </div>
		  <?php } ?>
		 	
		  <?php if (isset($error)) {
			  echo $error;
		  } ?>
		  
		  <?php if(Session::exists(addCategory) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['addBug_Error']; ?>
	        <?php echo Session::get(addCategory); ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(addCategory); ?>
		  
		  <?php if(Session::exists(notCategory) == true) { //If email is sent ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['notBug_Error']; ?>
	        <?php echo Session::get(notCategory); ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(notCategory); ?>
          </div>
           
		 <div class="col-lg-12">
		 
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['add']; ?> <?php echo $lang['category']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="addform"> 
                 	
                 <div class="form-group">
                  <label><?php echo $lang['choose']; ?> <?php echo $lang['icon']; ?></label>
                   <div class="input-group">
                    <input data-placement="bottomRight" name="icon" class="form-control icp icp-auto" value="fa-archive" type="text" />
                    <span class="input-group-addon"></span>
                    </div>
                  </div>
                  
                  <div class="form-group">	
				    <label><?php echo $lang['category']; ?> <?php echo $lang['name']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="name" class="form-control" placeholder="<?php echo $lang['name']; ?>" value="<?php echo escape(Input::get('name')); ?>"/>
                   </div>
                  </div>
                  
                  <div class="form-group">	
				    <label><?php echo $lang['sub']; ?> <?php echo $lang['categories']; ?></label>
                      <textarea type="text" name="sub_category" class="form-control" rows="5"></textarea>
                  </div>
                           
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="submit"  class="btn btn-success full-width"><?php echo $lang['submit']; ?></button>
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
    <!-- FontAwesome Icon Picker -->
    <script src="../assets/js/fontawesome-iconpicker.min.js" type="text/javascript"></script>
	<script type="text/javascript">
    $('.icp-auto').iconpicker();	
	</script>    
</body>
</html>
