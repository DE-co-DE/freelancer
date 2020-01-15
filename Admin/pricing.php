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


//Get Site Settings Data
$query = DB::getInstance()->get("settings", "*", ["id" => 1]);
if ($query->count()) {
 foreach($query->results() as $row) {
 	$sid = $row->id;
 	$pricing_top_title = $row->pricing_top_title;
 	$pricing_header_img = $row->pricing_header_img;
 }			
}	

//Edit Site Settings Data
if(isset($_POST['site'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'pricing_top_title' => [
	     'required' => true,
	     'maxlength' => 100,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
		
		    $sid = Input::get('sid');
			$siteUpdate = DB::getInstance()->update('settings',[
			    'pricing_top_title' => Input::get('pricing_top_title')
		    ],[
		    'id' => $sid
		    ]);
			
			if (count($siteUpdate) > 0) {
				$noError = true;
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

/*Edit Background Image Data*/
if(isset($_POST['picture'])){
if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
  	
	$path = "../assets/img/pricing/";
    $valid_formats = array("jpg", "png", "gif", "bmp");
   
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];

    if(strlen($name))
	{
	  list($txt, $ext) = explode(".", $name);
      if(in_array($ext,$valid_formats) && $size<(1920*1280))
	   {
	     $actual_image_name = time().substr($txt, 5).".".$ext;
		 $image_name = $actual_image_name;
		 $path_new = "assets/img/pricing/";
		 $newname=$path_new.$image_name;
         $tmp = $_FILES['photoimg']['tmp_name'];
         if(move_uploaded_file($tmp, $path.$actual_image_name))
	      {
	       	$new_bgimage = '../'.$pricing_header_img;
			unlink($new_bgimage);
				$sid = Input::get('sid');
				$siteUpdate = DB::getInstance()->update('settings',[
				    'pricing_header_img' => $newname
			    ],[
			    'id' => $sid
			    ]);
				
				if (count($siteUpdate) > 0) {
					$noError = true;
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
          <h1><?php echo $lang['settings']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['settings']; ?></li>
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
	      
	      <?php if(isset($hasError)) { //If errors are found ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
		   </div>
	      <?php } ?>
	
		  <?php if(isset($noError) && $noError == true) { //If email is sent ?>
		   <div class="alert alert-success fade in">
		   <a href="#" class="close" data-dismiss="alert">&times;</a>
		   <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?></strong>
		   </div>
		  <?php } ?>
		  
		  <?php if (isset($error)) {
			  echo $error;
		  } ?>
          </div>	
           
          <div class="col-lg-4">
          	<?php $selected = (Input::get('a') == 'details') ? ' active' : ''; ?>
          	<?php $headerimg = (Input::get('a') == 'headerimg') ? ' active' : ''; ?>
          	<?php $free = (Input::get('a') == 'free') ? ' active' : ''; ?>
          	<?php $agency = (Input::get('a') == 'agency') ? ' active' : ''; ?>
	         <div class="list-group">
	         <a href="pricing.php?a=details" class="list-group-item<?php echo $selected; ?>">
	          <em class="fa fa-fw fa-info-circle text-white"></em>&nbsp;&nbsp;&nbsp;<?php echo $lang['pricing']; ?> <?php echo $lang['details']; ?>
			 </a>
	         <a href="pricing.php?a=headerimg" class="list-group-item<?php echo $headerimg; ?>">
	          <em class="fa fa-fw fa-image text-white"></em>&nbsp;&nbsp;&nbsp; <?php echo $lang['pricing']; ?> <?php echo $lang['header']; ?> <?php echo $lang['image']; ?> <?php echo $lang['settings']; ?>
			 </a>
	         <a href="pricing.php?a=free" class="list-group-item<?php echo $free; ?>">
	          <em class="fa fa-fw fa-user text-white"></em>&nbsp;&nbsp;&nbsp; <?php echo $lang['freelancer']; ?> <?php echo $lang['membership']; ?> 
			 </a>
	         <a href="pricing.php?a=agency" class="list-group-item<?php echo $agency; ?>">
	          <em class="fa fa-fw fa-users text-white"></em>&nbsp;&nbsp;&nbsp; <?php echo $lang['agency']; ?> <?php echo $lang['membership']; ?> 
			 </a>
			 
	         </div>
          </div>
          
		 <div class="col-lg-8">
		 <?php if (Input::get('a') == 'details') : ?>
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['pricing']; ?> <?php echo $lang['page']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post"> 
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="form-group">	
				    <label><?php echo $lang['top']; ?> <?php echo $lang['title']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="pricing_top_title" class="form-control" rows="3"><?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('pricing_top_title')); 
						  } else {
						  echo escape($pricing_top_title); 
						  }
					  ?></textarea>
                   </div>
                  </div>
                                   
                  <div class="box-footer">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
                    <button type="submit" name="site" class="btn btn-primary full-width"><?php echo $lang['submit']; ?></button>
                  </div>
                 </form> 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
 		 <?php elseif (Input::get('a') == 'headerimg') : ?>
		  
		  		 <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['bg_image']; ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form role="form" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="sid" value="<?php echo escape($sid); ?>"/>
                  <div class="box-body">
                    <div class="form-group">
					 <div class="image text-center">
					  <img src="../<?php 
					  if (isset($_POST['picture'])) {
						 echo escape($newname); 
					  } else {
					  echo escape($pricing_header_img); 
					  } ?>" class="img-thumbnail" width="515" height="415"/>
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

	      
		 <?php elseif (Input::get('a') == 'free') : ?>
		 	
	  		 <div class="box box-info">
	        <div class="box-header">
			  <?php if(Session::exists(noError) == true) { //If email is sent ?>
		       <div class="alert alert-success fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['saved_success']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(noError); ?>
			  
			  <?php if(Session::exists(hasError) == true) { //If email is sent ?>
		       <div class="alert alert-danger fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
		        <?php echo Session::get(hasError); ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(hasError); ?>
			  	
			  <?php if(Session::exists(updatedError) == true) { //If email is sent ?>
		       <div class="alert alert-success fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(updatedError); ?>	  
			  <br />
	          <a href="#addf" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['add']; ?> <?php echo $lang['freelancer']; ?> <?php echo $lang['membership']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addf" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['freelancer']; ?> <?php echo $lang['membership']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
              	
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="name" class="form-control" placeholder="e.g Freelancer Basic"/>
               </div>
              </div> 
               
              <div class="form-group">	
			    <label><?php echo $lang['membership']; ?> <?php echo $lang['price']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                <input type="text" id="price" class="form-control" placeholder="e.g 20"/>
               </div>
              </div> 
              
              <div class="form-group">	
			    <label><?php echo $lang['bids']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="bids" class="form-control" placeholder="e.g 50"/>
               </div>
              </div> 
              
			 <div class="form-group">
			   <label><?php echo $lang['rollover']; ?><?php echo $lang['of']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="rollover" name="rollover" type="checkbox"/>
	                <label for="rollover" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['buy']; ?> <?php echo $lang['additional']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="buy" name="rollover" type="checkbox"/>
	                <label for="buy" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['see']; ?> <?php echo $lang['other']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="see" name="rollover" type="checkbox"/>
	                <label for="see" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['add']; ?> <?php echo $lang['team']; ?> <?php echo $lang['members']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="team" name="rollover" type="checkbox"/>
	                <label for="team" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addf()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>
	      
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['freelancer']; ?> <?php echo $lang['membership']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['drag']; ?> <?php echo $lang['and']; ?> <?php echo $lang['drop']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['price']; ?></th>
					   <th><?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['rollover']; ?> <?php echo $lang['of']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['buy']; ?> <?php echo $lang['additional']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['see']; ?> <?php echo $lang['other']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['team']; ?></th>
					   <th><?php echo $lang['item']; ?> <?php echo $lang['order']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody id="sortable">
				    <?php
                     $query = DB::getInstance()->get("membership_freelancer", "*", ["ORDER" => "item_order ASC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
							
	                    $rollover = (escape($row->rollover) === '1') ? Yes : No;
	                    $buy = (escape($row->buy) === '1') ? Yes : No;
	                    $see = (escape($row->see) === '1') ? Yes : No;
	                    $team = (escape($row->team) === '1') ? Yes : No;
							
					    echo '<tr id="'. escape($row->id) .'">';
						echo '<td class="drag-handle"><i class="fa fa-reorder"></i></td>';	
						echo '<td>'. escape($row->name) .'</td>';	
					    echo '<td>'. escape($row->price) .'</td>';
					    echo '<td>'. escape($row->bids) .'</td>';
						echo '<td>'. $rollover .'</td>';	
					    echo '<td>'. $buy .'</td>';
					    echo '<td>'. $see .'</td>';
					    echo '<td>'. $team .'</td>';
					    echo '<td>'. escape($row->item_order) .'</td>';
					    echo '<td>
					      <a onclick="getFDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-f btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>
                          </td>';
					    echo '</tr>';
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['drag']; ?> <?php echo $lang['and']; ?> <?php echo $lang['drop']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['price']; ?></th>
					   <th><?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['rollover']; ?> <?php echo $lang['of']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['buy']; ?> <?php echo $lang['additional']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['see']; ?> <?php echo $lang['other']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['team']; ?></th>
					   <th><?php echo $lang['item']; ?> <?php echo $lang['order']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->  
              
	      <!-- Modal HTML -->
	      <div id="editf" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['freelancer']; ?> <?php echo $lang['membership']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
               <input type="hidden" id="update_fid"/>    
              
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_name" class="form-control" placeholder="e.g Freelancer Basic"/>
               </div>
              </div> 
               
              <div class="form-group">	
			    <label><?php echo $lang['membership']; ?> <?php echo $lang['price']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                <input type="text" id="update_price" class="form-control" placeholder="e.g 20"/>
               </div>
              </div> 
              
              <div class="form-group">	
			    <label><?php echo $lang['bids']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_bids" class="form-control" placeholder="e.g 50"/>
               </div>
              </div> 
              
			 <div class="form-group">
			   <label><?php echo $lang['rollover']; ?><?php echo $lang['of']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="update_rollover" name="rollover" type="checkbox"/>
	                <label for="update_rollover" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['buy']; ?> <?php echo $lang['additional']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="update_buy" name="rollover" type="checkbox"/>
	                <label for="update_buy" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['see']; ?> <?php echo $lang['other']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="update_see" name="rollover" type="checkbox"/>
	                <label for="update_see" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['add']; ?> <?php echo $lang['team']; ?> <?php echo $lang['members']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="update_team" name="rollover" type="checkbox"/>
	                <label for="update_team" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->   
              
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="updatef()"  class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>    
	      
		 <?php elseif (Input::get('a') == 'agency') : ?>
		 	
	  		 <div class="box box-info">
	        <div class="box-header">
			  <?php if(Session::exists(noError) == true) { //If email is sent ?>
		       <div class="alert alert-success fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['saved_success']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(noError); ?>
			  
			  <?php if(Session::exists(hasError) == true) { //If email is sent ?>
		       <div class="alert alert-danger fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
		        <?php echo Session::get(hasError); ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(hasError); ?>
			  	
			  <?php if(Session::exists(updatedError) == true) { //If email is sent ?>
		       <div class="alert alert-success fade in">
		        <a href="#" class="close" data-dismiss="alert">&times;</a>
		        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?>
			   </div>
			  <?php } ?>
			  <?php Session::delete(updatedError); ?>	  
			  <br />
	          <a href="#addagency" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['add']; ?> <?php echo $lang['agency']; ?> <?php echo $lang['membership']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addagency" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['agency']; ?> <?php echo $lang['membership']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
              	
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="name" class="form-control" placeholder="e.g Agency Basic"/>
               </div>
              </div> 
               
              <div class="form-group">	
			    <label><?php echo $lang['membership']; ?> <?php echo $lang['price']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                <input type="text" id="price" class="form-control" placeholder="e.g 20"/>
               </div>
              </div> 
              
              <div class="form-group">	
			    <label><?php echo $lang['bids']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="bids" class="form-control" placeholder="e.g 50"/>
               </div>
              </div> 
              
			 <div class="form-group">
			   <label><?php echo $lang['rollover']; ?><?php echo $lang['of']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="rollover" name="rollover" type="checkbox"/>
	                <label for="rollover" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['buy']; ?> <?php echo $lang['additional']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="buy" name="rollover" type="checkbox"/>
	                <label for="buy" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['see']; ?> <?php echo $lang['other']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="see" name="rollover" type="checkbox"/>
	                <label for="see" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['add']; ?> <?php echo $lang['team']; ?> <?php echo $lang['members']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="team" name="rollover" type="checkbox"/>
	                <label for="team" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addagency()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>
	      
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['agency']; ?> <?php echo $lang['membership']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['drag']; ?> <?php echo $lang['and']; ?> <?php echo $lang['drop']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['price']; ?></th>
					   <th><?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['rollover']; ?> <?php echo $lang['of']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['buy']; ?> <?php echo $lang['additional']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['see']; ?> <?php echo $lang['other']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['team']; ?></th>
					   <th><?php echo $lang['item']; ?> <?php echo $lang['order']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody id="sortable-agency">
				    <?php
                     $query = DB::getInstance()->get("membership_agency", "*", ["ORDER" => "item_order ASC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
							
	                    $rollover = (escape($row->rollover) === '1') ? Yes : No;
	                    $buy = (escape($row->buy) === '1') ? Yes : No;
	                    $see = (escape($row->see) === '1') ? Yes : No;
	                    $team = (escape($row->team) === '1') ? Yes : No;
							
					    echo '<tr id="'. escape($row->id) .'">';
						echo '<td class="drag-handle"><i class="fa fa-reorder"></i></td>';	
						echo '<td>'. escape($row->name) .'</td>';	
					    echo '<td>'. escape($row->price) .'</td>';
					    echo '<td>'. escape($row->bids) .'</td>';
						echo '<td>'. $rollover .'</td>';	
					    echo '<td>'. $buy .'</td>';
					    echo '<td>'. $see .'</td>';
					    echo '<td>'. $team .'</td>';
					    echo '<td>'. escape($row->item_order) .'</td>';
					    echo '<td>
					      <a onclick="getAgencyDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-agency btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>
                          </td>';
					    echo '</tr>';
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['drag']; ?> <?php echo $lang['and']; ?> <?php echo $lang['drop']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['price']; ?></th>
					   <th><?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['rollover']; ?> <?php echo $lang['of']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['buy']; ?> <?php echo $lang['additional']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['see']; ?> <?php echo $lang['other']; ?> <?php echo $lang['bids']; ?></th>
					   <th><?php echo $lang['team']; ?></th>
					   <th><?php echo $lang['item']; ?> <?php echo $lang['order']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->  
              
	      <!-- Modal HTML -->
	      <div id="editagency" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['agency']; ?> <?php echo $lang['membership']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
               <input type="hidden" id="update_agencyid"/>    
              
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_name" class="form-control" placeholder="e.g Agency Basic"/>
               </div>
              </div> 
               
              <div class="form-group">	
			    <label><?php echo $lang['membership']; ?> <?php echo $lang['price']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                <input type="text" id="update_price" class="form-control" placeholder="e.g 20"/>
               </div>
              </div> 
              
              <div class="form-group">	
			    <label><?php echo $lang['bids']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_bids" class="form-control" placeholder="e.g 50"/>
               </div>
              </div> 
              
			 <div class="form-group">
			   <label><?php echo $lang['rollover']; ?><?php echo $lang['of']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="update_rollover" name="rollover" type="checkbox"/>
	                <label for="update_rollover" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['buy']; ?> <?php echo $lang['additional']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="update_buy" name="rollover" type="checkbox"/>
	                <label for="update_buy" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['see']; ?> <?php echo $lang['other']; ?> <?php echo $lang['bids']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="update_see" name="rollover" type="checkbox"/>
	                <label for="update_see" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->
              
			 <div class="form-group">
			   <label><?php echo $lang['can']; ?> <?php echo $lang['be']; ?> <?php echo $lang['able']; ?> <?php echo $lang['to']; ?> <?php echo $lang['add']; ?> <?php echo $lang['team']; ?> <?php echo $lang['members']; ?></label>
	            <ul class="list-group">
	             <li class="list-group-item flr">
	              <div class="material-switch text-center">
		           <span class="pull-left"><?php echo $lang['no']; ?></span>
	                <input id="update_team" name="rollover" type="checkbox"/>
	                <label for="update_team" class="label-mint"></label>
		           <span class="pull-right"><?php echo $lang['yes']; ?></span>
	              </div>
	             </li>
	            </ul>  
			 </div><!-- /.form-row -->   
              
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="updateagency()"  class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>                	   	                  	      	                   
                                       
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
    <!-- DATA TABES SCRIPT -->
    <script src="../assets/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>
    <!-- page script -->
    <script type="text/javascript">
      $(function () {
        $("#example1").dataTable({
        /* No ordering applied by DataTables during initialisation */
        "order": []
        });
      });
    </script>
    <!-- Jquery UI 1.10.3 -->
	<script src="../assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- page script -->
    <script type="text/javascript">$(function() {
    $('#sortable').sortable({
        axis: 'y',
        opacity: 0.7,
        update: function(event, ui) {
            var list_sortable = $(this).sortable('toArray').toString();
    		// change order in the database using Ajax
            $.ajax({
                url: 'template/requests/set_f.php',
                type: 'POST',
                data: {list_order:list_sortable},
                success: function(data) {
                    //finished
                }
            });
        }
    }); // fin sortable
   });
    </script>  
    <!-- page script -->
    <script type="text/javascript">$(function() {
    $('#sortable-agency').sortable({
        axis: 'y',
        opacity: 0.7,
        update: function(event, ui) {
            var list_sortable = $(this).sortable('toArray').toString();
    		// change order in the database using Ajax
            $.ajax({
                url: 'template/requests/set_agency.php',
                type: 'POST',
                data: {list_order:list_sortable},
                success: function(data) {
                    //finished
                }
            });
        }
    }); // fin sortable
   });
    </script>
    <script type="text/javascript">
	$(function() {
	
	
	$(".btn-f").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_membership']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletef.php",
				 data: info,
				 success: function()
					   {
						parent.fadeOut('slow', function() {$(this).remove();});
					   }
				});
			 
	
			  }
		   return false;
	
		});
	
	});
	</script>
    <script type="text/javascript">
	$(function() {
	
	
	$(".btn-agency").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_membership']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deleteagency.php",
				 data: info,
				 success: function()
					   {
						parent.fadeOut('slow', function() {$(this).remove();});
					   }
				});
			 
	
			  }
		   return false;
	
		});
	
	});
	</script>
    <script type="text/javascript">
    
	// Add Freelancer Membership
	function addf() {	
	    // get values
	    var name = $("#name").val();
	    var price = $("#price").val();
	    var bids = $("#bids").val();
	    var rollover = $("#rollover").is(':checked');
	    var buy = $("#buy").is(':checked');
	    var see = $("#see").is(':checked');
	    var team = $("#team").is(':checked');
	    
		//Built a url to send    
		var info = "name="+name+"&price="+price+"&bids="+bids+"&rollover="+rollover+"&buy="+buy+"&see="+see+"&team="+team;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/addf.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#addf").modal("hide");
            }
        });
	}   
	function getFDetails(id) {
	    // Add User ID to the hidden field for furture usage
	    $("#update_fid").val(id);
	    $.post("template/requests/readfdetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var freelancer = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_name").val(freelancer.name);
	            $("#update_price").val(freelancer.price);
	            $("#update_bids").val(freelancer.bids);
	            
			    if(freelancer.rollover == 1) {
	                $('#update_rollover').attr('checked', 'checked');
			    }else{
	                $('#update_rollover').removeAttr('checked');
			    }
			    
			    if(freelancer.buy == 1) {
	                $('#update_buy').attr('checked', 'checked');
			    }else{
	                $('#update_buy').removeAttr('checked');
			    }
			    
			    if(freelancer.see == 1) {
	                $('#update_see').attr('checked', 'checked');
			    }else{
	                $('#update_see').removeAttr('checked');
			    }
			    
			    if(freelancer.team == 1) {
	                $('#update_team').attr('checked', 'checked');
			    }else{
	                $('#update_team').removeAttr('checked');
			    }
	        }
	    );
	    // Open modal popup
	    $("#editf").modal("show");
	}  
	function updatef() {
	    // get values
	    var fid = $("#update_fid").val();
	    var name = $("#update_name").val();
	    var price = $("#update_price").val();
	    var bids = $("#update_bids").val();
	    var rollover = $("#update_rollover").is(':checked');
	    var buy = $("#update_buy").is(':checked');
	    var see = $("#update_see").is(':checked');
	    var team = $("#update_team").is(':checked');
	    
		//Built a url to send    
		var info = "name="+name+"&price="+price+"&bids="+bids+"&rollover="+rollover+"&buy="+buy+"&see="+see+"&team="+team+"&fid="+fid;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/updatef.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#editf").modal("hide");
            }
        });
	}		
	// Add Agency Membership
	function addagency() {	
	    // get values
	    var name = $("#name").val();
	    var price = $("#price").val();
	    var bids = $("#bids").val();
	    var rollover = $("#rollover").is(':checked');
	    var buy = $("#buy").is(':checked');
	    var see = $("#see").is(':checked');
	    var team = $("#team").is(':checked');
	    
		//Built a url to send    
		var info = "name="+name+"&price="+price+"&bids="+bids+"&rollover="+rollover+"&buy="+buy+"&see="+see+"&team="+team;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/addagency.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#addagency").modal("hide");
            }
        });
	}   
	function getAgencyDetails(id) {
	    // Add User ID to the hidden field for furture usage
	    $("#update_agencyid").val(id);
	    $.post("template/requests/readagencydetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var freelancer = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_name").val(freelancer.name);
	            $("#update_price").val(freelancer.price);
	            $("#update_bids").val(freelancer.bids);
	            
			    if(freelancer.rollover == 1) {
	                $('#update_rollover').attr('checked', 'checked');
			    }else{
	                $('#update_rollover').removeAttr('checked');
			    }
			    
			    if(freelancer.buy == 1) {
	                $('#update_buy').attr('checked', 'checked');
			    }else{
	                $('#update_buy').removeAttr('checked');
			    }
			    
			    if(freelancer.see == 1) {
	                $('#update_see').attr('checked', 'checked');
			    }else{
	                $('#update_see').removeAttr('checked');
			    }
			    
			    if(freelancer.team == 1) {
	                $('#update_team').attr('checked', 'checked');
			    }else{
	                $('#update_team').removeAttr('checked');
			    }
	        }
	    );
	    // Open modal popup
	    $("#editagency").modal("show");
	}  
	function updateagency() {
	    // get values
	    var agencyid = $("#update_agencyid").val();
	    var name = $("#update_name").val();
	    var price = $("#update_price").val();
	    var bids = $("#update_bids").val();
	    var rollover = $("#update_rollover").is(':checked');
	    var buy = $("#update_buy").is(':checked');
	    var see = $("#update_see").is(':checked');
	    var team = $("#update_team").is(':checked');
	    
		//Built a url to send    
		var info = "name="+name+"&price="+price+"&bids="+bids+"&rollover="+rollover+"&buy="+buy+"&see="+see+"&team="+team+"&agencyid="+agencyid;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/updateagency.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#editagency").modal("hide");
            }
        });
	}			
	</script>
    
</body>
</html>
