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
$freelancerid = $freelancer->data()->freelancerid;

//Check if Freelancer is logged in
if (!$freelancer->isLoggedIn()) {
  Redirect::to('../index.php');	
}

//Getting Job Data
$jobid = Input::get('id');
$q1 = DB::getInstance()->get("proposal", "*", ["AND" => ["jobid" => $jobid, "freelancerid" => $freelancerid]]);
if ($q1->count() === 1) {
	  Session::put("Posted", $freelancerid);
	  Redirect::to('joblist.php');
} else {
	$query = DB::getInstance()->get("job", "*", ["jobid" => $jobid, "LIMIT" => 1]);
	if ($query->count() === 1) {
	 foreach($query->results() as $row) {
	  $job_title = $row->title;
	  $country = $row->country;
	  $job_catid = $row->catid;
	  $job_budget = $row->budget;
	 }
	} else {
	  Redirect::to('joblist.php');
	}
}

$q2 = DB::getInstance()->get("category", "*", ["catid" => $job_catid, "LIMIT" => 1]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
 	$category_name = $r2->name;
 }			
}	

//Add Category Function
if (Input::exists()) {
 if(Token::check(Input::get('token'))){
 	
 if ($freelancer->data()->membership_bids <= 0) {
	$bidsError = true;
 } else {
     
 	
	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'budget' => [
	     'required' => true,
	     'digit' => true,
		 'minlength' => 1,
		 'maxlength' => 200
	   ],
	  'description' => [
	     'required' => true
	   ]
	]);
		 
    if (!$validation->fails()) {
    	  	 
			try{
			   $proposalid = uniqueid();	
			   $Insert = DB::getInstance()->insert('proposal', array(
				   'description' => Input::get('description'),
				   'proposalid' => $proposalid,
				   'jobid' => Input::get('jobid'),
				   'freelancerid' => $freelancer->data()->freelancerid,
				   'budget' => Input::get('budget'),
				   'accepted' => 0,
				   'active' => 1,
				   'delete_remove' => 0,
				   'opened' => 0,
				   'date_added' => date('Y-m-d H:i:s')
			    ));	
					
			  if (count($Insert) > 0) {
			  	
			     $q1 = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $freelancer->data()->freelancerid, "LIMIT" => 1]);
			     if($q1->count()) {
					foreach($q1->results() as $r1) {
			           $membership_bids = $r1->membership_bids;
					}
				 }	
				 				 
				 $update_bids = $membership_bids - 1; 
				 
				//Update Membership
				$Update = DB::getInstance()->update('freelancer',[
				   'membership_bids' => $update_bids
				],[
				    'freelancerid' => $freelancer->data()->freelancerid
				  ]);			 			
				
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
	 
 }//bids	 
	
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
          <h1><?php echo $lang['proposal']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['add']; ?> <?php echo $lang['proposal']; ?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">	 	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  
		 <div class="row">	
		 	
		 <div class="col-lg-12">
         <?php if(isset($bidsError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['bids_Error']; ?>
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
		 	
		  <?php if (isset($error)) {
			  echo $error;
		  } ?>
	        
		  
          </div>
          
           
		 <div class="col-lg-12">
		 
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo escape($job_title); ?></h3>
                </div>
                <div class="box-body">
            	<h5><b><u><?php echo $lang['category']; ?></u></b></h5>
            	<p><?php echo escape($category_name); ?></p>
            	<h5><b><u><?php echo $lang['country']; ?></u></b></h5>
            	<p><?php echo escape($country); ?></p>
            	<h5><b><u><?php echo $lang['budget']; ?></u></b></h5>
            	<p><?php echo $currency_symbol; ?> <?php echo escape($job_budget); ?></p>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
		 
		</div><!-- /.col -->          
           
		 <div class="col-lg-12">
		 
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['add']; ?> <?php echo $lang['proposal']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="addform"> 
                  <input type="hidden" name="jobid" value="<?php echo escape($jobid); ?>"/>
                 	
                  <div class="form-group">	
				    <label><?php echo $lang['your']; ?> <?php echo $lang['proposal']; ?> <?php echo $lang['budget']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                    <input type="text" name="budget" class="form-control" placeholder="<?php echo $lang['proposal']; ?> <?php echo $lang['budget']; ?>" value="<?php echo escape(Input::get('budget')); ?>"/>
                   </div>
                  </div>           
                  
                  <div class="form-group">	
				    <label><?php echo $lang['proposal']; ?> <?php echo $lang['description']; ?></label>
                      <textarea type="text" id="summernote" name="description" class="form-control"></textarea>
                  </div>     
                           
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
