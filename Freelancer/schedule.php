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

//Getting Proposal Data
$query = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $freelancer->data()->freelancerid, "LIMIT" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$schedule_payments = $row->schedule_payments;
 }
}	

//Edit Data
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'schedule' => [
	     'required' => true,
		 'minlength' => 1
	   ],
	]);
		 
    if (!$validation->fails()) {
		
		//Update
		$Update = DB::getInstance()->update('freelancer',[
		   'schedule_payments' => Input::get('schedule'),
		],[
		    'freelancerid' => $freelancer->data()->freelancerid
		  ]);
		
	   if (count($Update) > 0) {
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
          <h1><?php echo $lang['schedule']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['edit']; ?> <?php echo $lang['schedule']; ?></li>
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
                  <h3 class="box-title"><?php echo $lang['schedule']; ?> 
                  	                    <?php echo $lang['payments']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                                    
                  <div class="form-group">	
				    <label><?php echo $lang['schedule']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select name="schedule" type="text" class="form-control" >
                    <?php 
                     $jl = [
                           'Weekly'=>'Weekly',
                           'Twice per Month'=>'Twice per Month',
                           'Monthly'=>'Monthly',
                           'Quartely'=>'Quartely',
                           ];
					
					$x = 1;
					foreach ($jl as $key => $value) {
	                     if (isset($_POST['details'])) {
                           $selected = ($value === Input::get('schedule')) ? ' selected="selected"' : '';
						  } else {
                           $selected = ($value === $schedule_payments) ? ' selected="selected"' : '';
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
</body>
</html>
