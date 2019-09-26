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

//Add Message Function
if (Input::exists()) {
 if(Token::check(Input::get('token'))){
 	
	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'client_name[]' => [
	     'required' => true,
		 'minlength' => 1,
		 'maxlength' => 200
	   ],
	  'message_body' => [
	     'required' => true,
		 'minlength' => 1
	   ]
	]);
		 
    if (!$validation->fails()) {
    	
	  $names = Input::get('client_name');	
      foreach ($names as $value) {
		
      	
			try{
			   $messageInsert = DB::getInstance()->insert('message', array(
			       'message' => Input::get('message_body'),
				   'user_from' => $freelancer->data()->freelancerid,
				   'user_to' => $value,
				   'opened' => 0,
				   'active' => 1,
				   'delete_remove' => 0,
				   'date_added' => date('Y-m-d H:i:s')
			    ));	
					
			  if (count($messageInsert) > 0) {
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

?>
<!DOCTYPE html>
<html lang="en-US" class="no-js">
	
    <!-- Include header.php. Contains header content. -->
    <?php include ('template/header.php'); ?> 
    <!-- Select 2 Plugin-->
    <link href="../assets/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../assets/css/AdminLTE/AdminLTE.min.css" rel="stylesheet" type="text/css" />

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
          <h1><?php echo $lang['mailbox']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['compose']; ?> <?php echo $lang['mail']; ?></li>
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
		   <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['sent_success']; ?></strong>
		   </div>
		  <?php } ?>
		 	
		  <?php if (isset($error)) {
			  echo $error;
		  } ?>
	        
		  
          </div>
           

            <div class="col-md-3">
              <a href="compose.php" class="btn btn-info btn-block margin-bottom"> <?php echo $lang['compose']; ?></a>
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title"> <?php echo $lang['folders']; ?></h3>
                  <div class='box-tools'>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                	<?php $basename = basename($_SERVER["REQUEST_URI"], ".php"); ?>
		          	<?php $inbox = ($basename == 'inbox') ? ' active' : ''; ?>
		          	<?php $sent = ($basename == 'sent') ? ' active' : ''; ?>
		          	<?php $favorite = ($basename == 'favorite') ? ' active' : ''; ?>
		          	<?php $trash = ($basename == 'trash') ? ' active' : ''; ?>
                  <ul class="nav nav-pills nav-stacked">
                    <li class="<?php echo $inbox; ?>"><a href="inbox.php"><i class="fa fa-inbox"></i> <?php echo $lang['inbox']; ?> 
                    <span class="label label-primary pull-right">
                    <?php 	
                     $q1 = DB::getInstance()->get("message", "*", ["AND" => ["user_to" => $freelancer->data()->freelancerid, "opened" => 0, "delete_remove" => 0 ]]);
					 echo $q1->count();
                     ?></span></a></li>
                    <li class="<?php echo $sent; ?>"><a href="sent.php"><i class="fa fa-envelope-o"></i> <?php echo $lang['sent']; ?></a></li>
                    <li class="<?php echo $favorite; ?>"><a href="favorite.php"><i class="fa fa-star-o text-red"></i> <?php echo $lang['favorites']; ?></a></li>
                    <li class="<?php echo $trash; ?>"><a href="trash.php"><i class="fa fa-trash-o"></i> <?php echo $lang['trash']; ?></a></li>
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->
            
            <div class="col-md-9">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $lang['compose']; ?> <?php echo $lang['new']; ?> <?php echo $lang['message']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <form role="form" method="post" id="addform"> 

                  <div class="form-group">	
				    <label><?php echo $lang['to']; ?></label>
					<select name="client_name[]" type="text" class="form-control select" multiple="multiple">
					 <?php
					  $query = DB::getInstance()->get("client", "*", ["AND" => ["active" => 1, "delete_remove" => 0]]);
						if ($query->count()) {
						   $clientname = '';
						   $x = 1;
							 foreach ($query->results() as $row) {
							  echo $clientname .= '<option value = "' . $row->clientid . '">' . $row->name . '</option>';
							  unset($clientname); 
							  $x++;
						     }
						}
					 ?>	
					</select>
                  </div>  
                  <div class="form-group">	
				    <label><?php echo $lang['message_body']; ?></label>
                      <textarea type="text" id="summernote" name="message_body" class="form-control">
                      	<?php echo escape($message_body); ?></textarea>
                  </div>
                <div class="box-footer">
                  <div class="pull-right">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> <?php echo $lang['send']; ?> <?php echo $lang['message']; ?></button>
                  </div>
                </div><!-- /.box-footer -->
                
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /. box -->
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
    <!-- Select 2 JS-->
    <script src="../assets/js/select2.min.js" type="text/javascript"></script> 
	<script type="text/javascript">
	 $(document).ready(function() {
	   $(".select").select2({  
	   	placeholder: "<?php echo $lang['select']; ?> <?php echo $lang['a']; ?> <?php echo $lang['client']; ?>"
	   });
	 });
	</script>      
</body>
</html>
