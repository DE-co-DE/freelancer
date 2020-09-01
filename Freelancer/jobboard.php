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

//Getting Job Data
$jobid = Input::get('id');
$query = DB::getInstance()->get("job", "*", ["jobid" => $jobid, "LIMIT" => 1]);
if ($query->count() === 1) {
 foreach($query->results() as $row) {
  $clientid = $row->clientid;
  $freelancerid = $row->freelancerid;
  $jobid = $row->jobid;
  $catid = $row->catid;
  $job_title = $row->title;
  $job_type = $row->job_type;
  $job_budget = $row->budget;
  $job_description = $row->description;
  $job_start_date = $row->start_date;
  $job_end_date = $row->end_date;
  $public = $row->public;
  $skills = $row->skills;
  $arr=explode(',',$skills);
  $job_completed = $row->completed;
 }
} else {
  Redirect::to('joblist.php');
}	

$q1 = DB::getInstance()->get("client", "*", ["clientid" => $clientid, "LIMIT" => 1]);
if ($q1->count()) {
 foreach($q1->results() as $r1) {
 	$client_name = $r1->name;
 	$client_imagelocation = $r1->imagelocation;
 }			
}
	
$q2 = DB::getInstance()->get("category", "*", ["catid" => $catid, "LIMIT" => 1]);
if ($q2->count()) {
 foreach($q2->results() as $r2) {
 	$category_name = $r2->name;
 }			
}

$q3 = DB::getInstance()->get("proposal", "*", ["AND" => ["jobid" => $jobid, "freelancerid" => $freelancerid], "LIMIT" => 1]);
if ($q3->count()) {
 foreach($q3->results() as $r3) {
 	$proposal_budget = $r3->budget;
 }			
}

$q4 = DB::getInstance()->get("ratings_client", "*", ["AND" => ["jobid" => $jobid, "star_type" => 7], "LIMIT" => 1]);
if ($q4->count()) {
 foreach($q4->results() as $r4) {
 	$message_rating = $r4->message;
 }			
}

$q5 = DB::getInstance()->get("ratings", "*", ["AND" => ["jobid" => $jobid, "star_type" => 7], "LIMIT" => 1]);
if ($q5->count()) {
 foreach($q5->results() as $r5) {
 	$message_rating_client = $r5->message;
 }			
}

//Edit Message Rating Data
if(isset($_POST['site'])){
if (Input::exists()) {
 if(Token::check(Input::get('token'))){

	$errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'message_rating' => [
	     'required' => true,
	     'minlength' => 2
	  ]
	]);
		 
    if (!$validation->fails()) {
    	
			$query = DB::getInstance()->get("ratings_client", "*", ["AND" => ["jobid" => Input::get('jobid'), "star_type" => 7], "LIMIT" => 1]);
			if ($query->count() === 0) {
			 
			   $Insert = DB::getInstance()->insert('ratings_client', array(
				   'jobid' => Input::get('jobid'),
				   'freelancerid' => $freelancer->data()->freelancerid,
				   'clientid' => Input::get('clientid'),
				   'star' => 0,
				   'message' => Input::get('message_rating'),
				   'star_type' => 7,
				   'date_added' => date('Y-m-d H:i:s')
			    ));	
				
				  if (count($Insert) > 0) {
				       $noError = true;
				  } else {
				       $hasError = true;
				  }	
		
			}else{
				
			//Update
			$Update = DB::getInstance()->update('ratings_client',[
				 'message' => Input::get('message_rating')
			],[
			    "AND"=>["star_type" => 7, "jobid" => $jobid]
			  ]);
			  
			   if (count($Update) > 0) {
				       $updateError = true;
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
    <!-- AdminLTE CSS -->
    <link href="../assets/css/AdminLTE/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- Datetime Picker -->
    <link href="../assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- Jquery UI CSS -->
    <link href="../assets/css/jquery-ui-1.10.3.custom.css" rel="stylesheet" type="text/css" />
    <!-- Progress CSS -->
    <link href="../assets/css/progress.css" rel="stylesheet" type="text/css" />

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

        <!-- Main content -->
        <section class="content">	
		    <!-- Include currency.php. Contains header content. -->
		    <?php include ('template/currency.php'); ?>  	
		 <div class="row">	
		   
		   <div class="col-lg-12 m-t-sm">	
            <div class="col-sm-12 m-b-xs">
		     <span><?php echo escape($job_title); ?></span>
			 <div class="btn-group pull-right">
			  <?php if($job_completed === '1'): ?>			
              <a class="btn btn-sm btn-danger"> 
               <?php echo $lang['job']; ?> <?php echo $lang['completed']; ?>, 
               <?php echo $lang['now']; ?> <?php echo $lang['rate']; ?> <?php echo $lang['client']; ?> 			
              </a>
			  <?php else: ?>		
              <a class="btn btn-sm btn-success"> 
               <i class="fa fa-spinner"></i> &nbsp; <?php echo $lang['job']; ?> <?php echo $lang['not']; ?> <?php echo $lang['finished']; ?>			
              </a>
			  <?php endif; ?>
             </div>
            </div>
									
									
		  </div>
		 	
		 <div class="col-lg-12 nav-bg">
          	<?php $ov = (Input::get('a') == 'overview') ? ' active' : ''; ?>
          	<?php $mi = (Input::get('a') == 'milestones') ? ' active' : ''; ?>
          	<?php $di = (Input::get('a') == 'discussions') ? ' active' : ''; ?>
          	<?php $ta = (Input::get('a') == 'tasks') ? ' active' : ''; ?>
          	<?php $fi = (Input::get('a') == 'files') ? ' active' : ''; ?>
          	<?php $li = (Input::get('a') == 'links') ? ' active' : ''; ?>
          	<?php $bu = (Input::get('a') == 'bugs') ? ' active' : ''; ?>
          	<?php $fu = (Input::get('a') == 'funds') ? ' active' : ''; ?>
          	<?php $ra = (Input::get('a') == 'ratings') ? ' active' : ''; ?>
          	<?php $cra = (Input::get('a') == 'cratings') ? ' active' : ''; ?>
          <div class="sub-tab" style="margin-bottom:10px;">
           <ul class="nav pro-nav-tabs nav-tabs-dashed">
            <li class="<?php echo $ov; ?>"><a href="jobboard.php?a=overview&id=<?php echo $jobid ?>"><?php echo $lang['overview']; ?></a></li>
            <li class="<?php echo $mi; ?>"><a href="jobboard.php?a=milestones&id=<?php echo $jobid ?>"><?php echo $lang['milestones']; ?></a></li>
            <li class="<?php echo $di; ?>"><a href="jobboard.php?a=discussions&id=<?php echo $jobid ?>"><?php echo $lang['discussions']; ?></a></li>
            <li class="<?php echo $ta; ?>"><a href="jobboard.php?a=tasks&id=<?php echo $jobid ?>"><?php echo $lang['tasks']; ?></a></li>
            <li class="<?php echo $fi; ?>"><a href="jobboard.php?a=files&id=<?php echo $jobid ?>"><?php echo $lang['files']; ?></a></li>
            <li class="<?php echo $li; ?>"><a href="jobboard.php?a=links&id=<?php echo $jobid ?>"><?php echo $lang['links']; ?></a></li>
            <li class="<?php echo $bu; ?>"><a href="jobboard.php?a=bugs&id=<?php echo $jobid ?>"><?php echo $lang['bugs']; ?></a></li>
            <li class="<?php echo $fu; ?>"><a href="jobboard.php?a=funds&id=<?php echo $jobid ?>"><?php echo $lang['funds']; ?></a></li>
            <?php if($job_completed === '1'):
             echo $rt .=' <li class="' . $ra . '">
             	     <a href="jobboard.php?a=ratings&id=' . $jobid . '">
             	     '. $lang['rate'] .' '. $lang['the'] .' '. $lang['client'] .'
             	     </a>
             	   </li>';
             echo $crt .=' <li class="' . $cra . '">
             	     <a href="jobboard.php?a=cratings&id=' . $jobid . '">
             	     '. $lang['your'] .' '. $lang['ratings'] .' '. $lang['by'] .' '. $lang['client'] .'
             	     </a>
             	   </li>';
             else: 
             echo $rt .='';	
             echo $crt .='';	
             endif; ?>	
           </ul>
          </div>		 	
		 </div>	
        </div><!-- /.row -->
        
        <div class="row proj-summary-band nav-bg">

        <div class="col-md-3 text-center">
            <label class="small text-muted"><?php echo $lang['job']; ?> <?php echo $lang['title']; ?></label>
            <h4><strong><?php echo escape($job_title); ?></strong></h4>
        </div>
        
         <div class="col-md-3 text-center">
          <label class="text-muted small"><?php echo $lang['job']; ?> <?php echo $lang['budget']; ?></label>
          <h4><strong>
          <?php echo $currency_symbol; ?> <?php echo escape($job_budget); ?>	
          </strong></h4>
         </div>

        <div class="col-md-3 text-center">
            <label class="small text-muted"><?php echo $lang['job']; ?> <?php echo $lang['type']; ?></label>
            <h4><strong><?php echo escape($job_type); ?></strong></h4>
        </div>

         <div class="col-md-3 text-center">
          <label class="text-muted small"><?php echo $lang['freelancer']; ?> <?php echo $lang['budget']; ?></label>
          <h4><strong>
           <?php echo $currency_symbol; ?> <?php echo escape($proposal_budget); ?>	
          </strong></h4>
         </div>

        </div>        
        
        <br/>
        <div class="row">
		 <?php if (Input::get('a') == 'overview') : ?>        	
         <div class="col-lg-4">
	  		 <div class="box box-info">
	        <div class="box-header">
	          <h3 class="box-title"><?php echo $lang['client']; ?> <?php echo $lang['name']; ?> <?php echo $lang['and']; ?> <?php echo $lang['image']; ?></h3>
	        </div><!-- /.box-header -->
	          <div class="box-body">
	          	<h4 class="text-center"><strong><?php echo $client_name; ?></strong></h4>
	            <div class="form-group">
				 <div class="image text-center">
				  <img src="../Client/<?php echo escape($client_imagelocation); ?>" class="img-thumbnail" width="215" height="215"/>
				 </div>
	            </div>
			  
	          </div><!-- /.box-body -->
	      </div><!-- /.box -->	         	
         </div>	
        	
		 <div class="col-lg-8">
		  
		  <section class="panel panel-default">
           <header class="panel-heading"><?php echo $lang['job']; ?> <?php echo $lang['details']; ?></header>
            <ul class="list-group no-radius">
             <li class="list-group-item">
              <span class="pull-right text"><?php echo escape($job_title); ?></span>
              <span class="text-muted"><?php echo $lang['title']; ?></span>
             </li>
             <li class="list-group-item">
			  <?php if($job_completed === '1'): ?>		
              <span class="pull-right text"><?php echo $lang['job']; ?> <?php echo $lang['completed']; ?></span>
			  <?php else: ?>		
              <span class="pull-right text"><?php echo $lang['job']; ?> <?php echo $lang['not']; ?> <?php echo $lang['finished']; ?></span>
			  <?php endif; ?>
              <span class="text-muted"><?php echo $lang['status']; ?></span>
             </li>
             <li class="list-group-item">
              <span class="pull-right"><a href="../client.php?a=overview&&id=<?php echo escape($clientid); ?>" target="_blank">
              	<?php echo escape($client_name); ?></a></span>
              <span class="text-muted"><?php echo $lang['client']; ?> <?php echo $lang['name']; ?></span>
             </li>
             <li class="list-group-item">
              <span class="pull-right text"><?php echo escape($job_type); ?></span>
              <span class="text-muted"><?php echo $lang['job']; ?> <?php echo $lang['type']; ?></span>
             </li>
             <li class="list-group-item">
              <span class="pull-right"><?php echo escape($category_name); ?></span>
              <span class="text-muted"><?php echo $lang['category']; ?></span>
             </li>
             <li class="list-group-item">
              <span class="pull-right"><?php echo $currency_symbol; ?> <?php echo escape($job_budget); ?></span>
              <span class="text-muted"><?php echo $lang['budget']; ?></span>
             </li>
             <li class="list-group-item">
              <span class="pull-right"><?php echo escape($job_start_date); ?></span>
              <span class="text-muted"><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></span>
             </li>
             <li class="list-group-item">
              <span class="pull-right"><?php echo escape($job_end_date); ?></span>
              <span class="text-muted"><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></span>
             </li>
            </ul>
           </section>	
           
		  <section class="panel panel-default">
           <header class="panel-heading"><?php echo $lang['freelancer']; ?> <?php echo $lang['proposal']; ?></header>
            <ul class="list-group no-radius">
             <li class="list-group-item">
              <span class="pull-right"><?php echo $currency_symbol; ?> <?php echo escape($proposal_budget); ?></span>
              <span class="text-muted"><?php echo $lang['proposal']; ?> <?php echo $lang['budget']; ?></span>
             </li>
            </ul>
           </section>       
           
		 <!-- Input addon -->
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title"><?php echo $lang['job']; ?> <?php echo $lang['skills']; ?></h3>
            </div>
            <div class="box-body">
             
             <?php
             foreach ($arr as $key => $value) {
                 echo '<label class="label label-success">'. $value .'</label> &nbsp;'; 
             }
			?>	                 
            </div><!-- /.box-body -->
          </div><!-- /.box -->                     	 	
		 	
		 </div>        	
        </div>    	 	
		 	
		 </div>        	
        </div>

 
		 <?php elseif (Input::get('a') == 'milestones') : ?> 
         <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 <div class="col-md-12">	
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
		  
		  <?php if(Session::exists(addTask) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['addTask_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(addTask); ?>
		  
		  <?php if(Session::exists(notTask) == true) { //If email is sent ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['notTask_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(notTask); ?>		  
		  
          </div>
		 	<div class="col-md-12">
	      	      	
		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['milestone']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['progress']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['funded']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("milestone", "*", ["AND" => ["jobid" => $jobid, "freelancerid" => $freelancerid, "active" => 1, "delete_remove" => 0 ], "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
						
						$q2 = DB::getInstance()->get("client", "*", ["clientid" => $row->clientid, "LIMIT" => 1]);
						if ($q2->count()) {
						 foreach($q2->results() as $r2) {
						  $client_email = $r2->email;
						 }
						}	
						
					 	$q5 = DB::getInstance()->get("task", "*", ["AND" =>["milestoneid" => $row->id]]);
						$task_count = $q5->count();
						$task_count = $task_count * 100;
							
			            $query = DB::getInstance()->sum("task", "progress", ["AND" => ["milestoneid" => $row->id]]);
						foreach($query->results()[0] as $task_progress) {
							 $tas[] = $task_progress;
						}				
						
						$percentage = array_sum($tas)/$task_count * 100;
						$percentage = round($percentage, 1);					
						
                        if(!$row->funded == "1"):
					    $mark = '';
						else:
					    $mark = '
					      <a onclick="getMid('.$row->id.')" class="btn btn-success btn-xs" data-toggle="modal">' . $lang['add'] . ' ' . $lang['task'] . '</a>';
						endif;							
								
					    echo '<tr>';
					    echo '<td><input class="knob" data-width="75" data-angleOffset="40" data-linecap="round" value="' . $percentage . '" style=""/></td>';
					    echo '<td>'. escape($row->name) .'</td>';
					    echo '<td>'. escape($row->budget) .'</td>';
					    echo '<td>'. escape($row->start_date) .'</td>';
					    echo '<td>'. escape($row->end_date) .'</td>';
						
					    if (escape($row->funded) == 1) {
					    echo '<td><span class="label label-success"> ' . $lang['funded'] . ' </span> </td>';
						} else {
					    echo '<td><span class="label label-success"> ' . $lang['not'] . ' ' . $lang['funded'] . '</span> </td>';
						}
						
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '<td>
					      <a onclick="viewMilestone('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['view'] . '"><span class="fa fa-eye"></span></a>
					    '. $mark .'
                          </td>';
					    echo '</tr>';
						unset($mark);
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['progress']; ?></th>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['budget']; ?></th>
					   <th><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['funded']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->               	
			  
	      <!-- Modal HTML -->
	      <div id="addtask" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['task']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
                  <input type="hidden" id="milestoneid"/>
               <input type="hidden" id="jobid" value="<?php echo escape($jobid); ?>"/>
	         	
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="name" class="form-control" placeholder="<?php echo $lang['name']; ?>" value="<?php echo escape(Input::get('name')); ?>"/>
               </div>
              </div>       
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="description" class="form-control" rows="5"></textarea>
              </div>    
              
			  <div class="form-group">
			   <label><?php echo $lang['progress']; ?></label>
				<div class="slider sliderMin sliderMint" id="progress"></div>
				<div class="field_notice"><?php echo $lang['percent']; ?>: <span class="must sliderMinLabel">0%</span></div>
			  </div>  

			  <div class="form-group">
               <label for="dtp_input1"><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></label>
                <div class="input-group date form_datetime_start" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                <input id="start_date" class="form-control" type="text" value="" readonly>
                <span class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
				<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
			   <input type="hidden" id="dtp_input1" value="" /><br/>
	           <input name="mirror_field_start" type="hidden" id="mirror_field_start" class="form-control" readonly />
	           <input name="mirror_field_start_date" type="hidden" id="mirror_field_start_date" class="form-control" readonly />
              </div>  

			  <div class="form-group">
               <label for="dtp_input1"><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></label>
                <div class="input-group date form_datetime_end" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                <input id="end_date" class="form-control" type="text" value="" readonly>
                <span class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
				<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
			   <input type="hidden" id="dtp_input1" value="" /><br/>
	           <input name="mirror_field_start" type="hidden" id="mirror_field_start" class="form-control" readonly />
	           <input name="mirror_field_start_date" type="hidden" id="mirror_field_start_date" class="form-control" readonly />
              </div> 

	         
             <div class="modal-footer">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addtask()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>
	      
	      <!-- Modal HTML -->
	      <div id="viewmilestone" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['view']; ?> <?php echo $lang['task']; ?></h4>
	         </div>
	         <div class="modal-body">
	           <h4><u> <?php echo $lang['name']; ?> </u></h4>
	           <h5 class="name"></h5>	  
	           <h4><u> <?php echo $lang['budget']; ?> </u></h4>
	           <p class="p"></p>
	           <h4><u> <?php echo $lang['description']; ?> </u></h4>
	           <p class="description"></p>
	           <h4><u> <?php echo $lang['start']; ?> <?php echo $lang['date']; ?> </u></h4>
	           <p class="start_date"></p>   
	           <h4><u> <?php echo $lang['end']; ?> <?php echo $lang['date']; ?> </u></h4>
	           <p class="end_date"></p>
	         
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
             </div>
             
             </div>
             
	        </div>
	       </div>
	      </div>	      
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->		
        </div>
		 <?php elseif (Input::get('a') == 'discussions') : ?> 
         <div class="col-lg-12">		 	
		 <div class="row">	
		 	<div class="col-md-12">
		 		
		  <?php
         $query = DB::getInstance()->get("client", "*", ["clientid" => $clientid, "LIMIT" => 1]);
		 if($query->count()) {
		 	
		    $x = 1;
			foreach($query->results() as $row) {
		    $messageList = '';
			  
		    echo $messageList .= '
          
		 <div class="col-md-8 col-lg-offset-2">
		 	
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title">' . $lang['discussions'] . ' ' . $lang['between'] . ' ' . $lang['you'] . ' ' . $lang['and'] . '  '. escape($row->name) .'</h3>
                </div>
                  <div class="box-footer bg-body-light">
                  
                  <div class="form-group message-reply-container">	
                    <textarea onclick="showMsgButton('. $row->id .')" id="summernote" placeholder="Post Message" class="form-control"></textarea>
                  </div>
                    <div id="message_btn_'. $row->id .'" class="comment-btn">
                     <a onclick="postDiscussion('. $row->id .', '. $freelancer->data()->freelancerid .', '.$clientid.')" class="btn btn-primary">Post</a>
					</div>
                  </div>
                <div class="box-body">
                	
                  <div id="messages-list'.$row->id.'" class="post-comments" style="overflow-y: scroll; height: 600px; width: 100%;">
                  '.getDiscussionF($clientid, $freelancer->data()->freelancerid).'
                  </div>
                  
                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
		 
		</div><!-- /.col -->
				    
					 ';
				
             unset($messageList);	 
			 $x++;		 
		   }
		}else {
		 echo $messageList = '<p>'.$lang['no_content_found'].'</p>';
		}
       ?>		 			
			  
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>
		 <?php elseif (Input::get('a') == 'tasks') : ?> 
         <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 		

		 <div class="col-md-12">	
		  <?php if(Session::exists(addTask) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['addTask_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(addTask); ?>
		  
		  <?php if(Session::exists(notTask) == true) { //If email is sent ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['notTask_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(notTask); ?>
		  	
		  <?php if(Session::exists(updateTask) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updateTask_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(updateTask); ?>	  
		  
          </div>	
          
		 	<div class="col-md-12">	 		

	  		 <div class="box box-info">
	        <div class="box-header">
	          <a href="#addtaskn" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['add']; ?> <?php echo $lang['task']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addtaskn" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['task']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
               <input type="hidden" id="jobid" value="<?php echo escape($jobid); ?>"/>
              	
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="name" class="form-control" placeholder="<?php echo $lang['name']; ?>" value="<?php echo escape(Input::get('name')); ?>"/>
               </div>
              </div>    
                  
              <div class="form-group">	
			    <label><?php echo $lang['milestone']; ?> <?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
				<select id="milestoneid" type="text" class="form-control">
				 <?php
				  $query = DB::getInstance()->get("milestone", "*", ["AND" => ["jobid" => Input::get('id'), "funded" => 1, "active" => 1, "delete_remove" => 0]]);
					if ($query->count()) {
					   $name = '';
					   $x = 1;
						 foreach ($query->results() as $row) {
						  echo $name .= '<option value = "' . $row->id . '">' . $row->name . '</option>';
						  unset($name); 
						  $x++;
					     }
					}
				 ?>	
				</select>
               </div>
              </div>   
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="description" class="form-control" rows="5"></textarea>
              </div>    
              
			  <div class="form-group">
			   <label><?php echo $lang['progress']; ?></label>
				<div class="slider sliderMin sliderMint" id="progress"></div>
				<div class="field_notice"><?php echo $lang['percent']; ?>: <span class="must sliderMinLabel">0%</span></div>
			  </div>  

			  <div class="form-group">
               <label for="dtp_input1"><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></label>
                <div class="input-group date form_datetime_start" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                <input id="start_date" class="form-control" type="text" value="" readonly>
                <span class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
				<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
			   <input type="hidden" id="dtp_input1" value="" /><br/>
	           <input name="mirror_field_start" type="hidden" id="mirror_field_start" class="form-control" readonly />
	           <input name="mirror_field_start_date" type="hidden" id="mirror_field_start_date" class="form-control" readonly />
              </div>  

			  <div class="form-group">
               <label for="dtp_input1"><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></label>
                <div class="input-group date form_datetime_end" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                <input id="end_date" class="form-control" type="text" value="" readonly>
                <span class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
				<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
			   <input type="hidden" id="dtp_input1" value="" /><br/>
	           <input name="mirror_field_start" type="hidden" id="mirror_field_start" class="form-control" readonly />
	           <input name="mirror_field_start_date" type="hidden" id="mirror_field_start_date" class="form-control" readonly />
              </div> 

	         
             <div class="modal-footer">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addtask()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>	      
		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['task']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['milestone']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['progress']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("task", "*", ["AND" => ["jobid" => $jobid, "freelancerid" => $freelancerid, "active" => 1, "delete_remove" => 0 ], "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
                        $done = ($row->progress == 100) ? 'done' : ''; 	
                        $check = ($row->progress == 100) ? 'fa-check-square' : ''; 
							
						$q1 = DB::getInstance()->get("milestone", "*", ["id" => $row->milestoneid, "LIMIT" => 1]);
						if ($q1->count()) {
						 foreach($q1->results() as $r1) {
						  $milestone_name = $r1->name;
						 }
						}	
						

                        if($row->progress == "100"):
						$delete .='';
						else:
						$delete .='
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-task btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>'; 
						endif;													
						
					    echo '<tr>';
					    echo '<td class="'.$done.'"><span class="fa '.$check.'"></span> '. escape($row->name) .'</td>';
					    echo '<td>'. escape($milestone_name) .'</td>';
					    echo '<td>'. escape($row->start_date) .'</td>';
					    echo '<td>'. escape($row->end_date) .'</td>';
						echo '<td>
	                      <div class="progress-xxs not-rounded mb-0 inline-block progress" style="width: '. escape($row->progress) .'%; margin-right: 5px">
	                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;" data-toggle="tooltip" title="'. escape($row->progress) .'%"></div>
	                      </div>
                             </td>';
						
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '<td>
					      <a onclick="viewTask('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['view'] . '"><span class="fa fa-eye"></span></a>
					      <a onclick="getTaskDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      '. $delete .'
                          </td>';
					    echo '</tr>';
						unset($delete);
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['milestone']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></th>
					   <th><?php echo $lang['progress']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->  
              
	      <!-- Modal HTML -->
	      <div id="edittask" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['task']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform"> 
                  <input type="hidden" id="update_taskid" name="taskid"/>
	         	
              <div class="form-group">	
			    <label><?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_name" name="name" class="form-control" placeholder="<?php echo $lang['name']; ?>" value="<?php echo escape(Input::get('name')); ?>"/>
               </div>
              </div>                   
                  
              <div class="form-group">	
			    <label><?php echo $lang['milestone']; ?> <?php echo $lang['name']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
				<select id="update_milestoneid" type="text" class="form-control">
				 <?php
				  $query = DB::getInstance()->get("milestone", "*", ["AND" => ["jobid" => $jobid, "funded" => 1, "active" => 1, "delete_remove" => 0]]);
					if ($query->count()) {
					   $name = '';
					   $x = 1;
						 foreach ($query->results() as $row) {
						  echo $name .= '<option value="' . $row->id . '">' . $row->name . '</option>';
						  unset($name); 
						  $x++;
					     }
					}
				 ?>	
				</select>
               </div>
              </div> 
              
			  <div class="form-group">
			   <label><?php echo $lang['progress']; ?></label>
				<div class="slider sliderMinn sliderMint" id="update_progress"></div>
				<div class="field_notice"><?php echo $lang['percent']; ?>: <span class="must sliderMinnLabel">0%</span></div>
			  </div>
			  
			   <script type="text/javascript">
			   </script>
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="update_description" name="description" class="form-control" rows="5"></textarea>
              </div>     

			  <div class="form-group">
               <label for="dtp_input1"><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></label>
                <div class="input-group date form_datetime_start" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                <input id="update_start_date" name="start_date" class="form-control" type="text" value="" readonly>
                <span class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
				<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
			   <input type="hidden" id="dtp_input1" value="" /><br/>
	           <input name="mirror_field_start" type="hidden" id="mirror_field_start" class="form-control" readonly />
	           <input name="mirror_field_start_date" type="hidden" id="mirror_field_start_date" class="form-control" readonly />
              </div>  

			  <div class="form-group">
               <label for="dtp_input1"><?php echo $lang['end']; ?> <?php echo $lang['date']; ?></label>
                <div class="input-group date form_datetime_end" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                <input id="update_end_date" name="end_date" class="form-control" type="text" value="" readonly>
                <span class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
				<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                </div>
			   <input type="hidden" id="dtp_input1" value="" /><br/>
	           <input name="mirror_field_start" type="hidden" id="mirror_field_start" class="form-control" readonly />
	           <input name="mirror_field_start_date" type="hidden" id="mirror_field_start_date" class="form-control" readonly />
              </div> 

	         
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="updatetask()" class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>    
	      
	      <!-- Modal HTML -->
	      <div id="viewtask" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['view']; ?> <?php echo $lang['task']; ?></h4>
	         </div>
	         <div class="modal-body">
	           <h4><u> <?php echo $lang['name']; ?> </u></h4>
	           <h5 class="name"></h5>	  
	           <h4><u> <?php echo $lang['progress']; ?> </u></h4>
	           <p class="p"></p>
	           <h4><u> <?php echo $lang['description']; ?> </u></h4>
	           <p class="description"></p>
	           <h4><u> <?php echo $lang['start']; ?> <?php echo $lang['date']; ?> </u></h4>
	           <p class="start_date"></p>   
	           <h4><u> <?php echo $lang['end']; ?> <?php echo $lang['date']; ?> </u></h4>
	           <p class="end_date"></p>
	         
             <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
             </div>
             
             </div>
             
	        </div>
	       </div>
	      </div>	                 
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>
		 <?php elseif (Input::get('a') == 'files') : ?> 
         <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 <div class="col-md-12">	
		  <?php if(Session::exists(addFile) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['addFile_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(addFile); ?>
		  
		  <?php if(Session::exists(notFile) == true) { //If email is sent ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['notFile_Error']; ?>
	        <?php echo Session::get(notFile); ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(notFile); ?>
		  	
		  <?php if(Session::exists(updateFile) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updateFile_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(updateFile); ?>	  
		  
          </div>
          		 	
		 	<div class="col-md-12">
		 		


	  		 <div class="box box-info">
	        <div class="box-header">
	          <a href="#addfile" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['upload']; ?> <?php echo $lang['file']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addfile" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['upload']; ?> <?php echo $lang['file']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
               <input type="hidden" id="jobid" value="<?php echo escape($jobid); ?>"/>
              	
              <div class="form-group">	
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div>      
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="description" class="form-control" rows="5"></textarea>
              </div>    
              
               <div style="position:relative;">
                <a class='btn btn-success' href='javascript:;'>
	            <?php echo $lang['choose']; ?> <?php echo $lang['file']; ?>...
	            <input type="file" id="photoimg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
                <input type="hidden" name="image_name" id="image_name"/>
                </a>
                &nbsp;
                <span class='label label-success' id="upload-file-info"></span>
              </div>

	         
             <div class="modal-footer">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addfile()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>		 		


		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['file']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['preview']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['description']; ?></th>
					   <th><?php echo $lang['type']; ?></th>
					   <th><?php echo $lang['size']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("file", "*", ["AND" => ["jobid" => $jobid, "freelancerid" => $freelancerid,], "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {

						switch ($row->extension):
						    case 'gif':
						        $file .='<img src="'. escape($row->fileupload) .'" class="img-thumbnail" width="315" height="315"/>';
						        break;
						    case 'png':
						        $file .='<img src="'. escape($row->fileupload) .'" class="img-thumbnail" width="315" height="315"/>';
						        break;
						    case 'jpg':
						        $file .='<img src="'. escape($row->fileupload) .'" class="img-thumbnail" width="315" height="315"/>';
						        break;
						    case 'jpeg':
						        $file .='<img src="'. escape($row->fileupload) .'" class="img-thumbnail" width="315" height="315"/>';
						        break;
						    case 'doc':
						        $file .='<span><i class="fa fa-file-word-o fa-5x"></i></span>';
						        break;
						    case 'docx':
						        $file .='<span><i class="fa fa-file-word-o fa-5x"></i></span>';
						        break;
						    case 'ppt':
						        $file .='<span><i class="fa fa-file-powerpoint-o fa-5x"></i></span>';
						        break;
						    case 'pptx':
						        $file .='<span><i class="fa fa-file-powerpoint-o fa-5x"></i></span>';
						        break;
						    case 'pdf':
						        $file .='<span><i class="fa fa-file-pdf-o fa-5x"></i></span>';
						        break;
						    case 'zip':
						        $file .='<span><i class="fa fa-file-zip-o fa-5x"></i></span>';
						        break;
						    case 'xls':
						        $file .='<span><i class="fa fa-file-excel-o fa-5x"></i></span>';
						        break;
						    case 'xlsx':
						        $file .='<span><i class="fa fa-file-excel-o fa-5x"></i></span>';
						        break;
						    case 'xlsm':
						        $file .='<span><i class="fa fa-file-excel-o fa-5x"></i></span>';
						        break;
						    default:
						        $file .='<span><i class="fa fa-file-o fa-5x"></i></span>';
						endswitch;
	
					    echo '<tr>';
					    echo '<td class="text-center">'. $file .'</td>';
					    echo '<td>'. escape($row->title) .'</td>';
					    echo '<td>'. escape($row->description) .'</td>';
					    echo '<td>'. escape($row->extension) .'</td>';
					    echo '<td>'. escape(round($row->size,0)) .' KB</td>';
						
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '<td>
					      <a href="'. escape($row->fileupload) .'" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['download'] . '"><span class="fa fa-download"></span></a>
					      <a onclick="getFileDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-file btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>
                          </td>';
					    echo '</tr>';
						unset($file);
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['preview']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['description']; ?></th>
					   <th><?php echo $lang['type']; ?></th>
					   <th><?php echo $lang['size']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
	      <!-- Modal HTML -->
	      <div id="editfile" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['uploaded']; ?> <?php echo $lang['file']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
               <input type="hidden" id="update_fileid"/>
              	
              <div class="form-group">	
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div>      
              
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="update_description" class="form-control" rows="5"></textarea>
              </div>    
              
               <div style="position:relative;">
                <a class='btn btn-success' href='javascript:;'>
	            <?php echo $lang['choose']; ?> <?php echo $lang['file']; ?>...
	            <input type="file" id="update_photoimg" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#update_upload-file-info").html($(this).val());'>
                <input type="hidden" name="image_name" id="update_image_name"/>
                </a>
                &nbsp;
                <span class='label label-success' id="update_upload-file-info"></span>
              </div>

	         
             <div class="modal-footer">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="updatefile()"  class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>              
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>

		 <?php elseif (Input::get('a') == 'links') : ?> 
         <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 <div class="col-md-12">	
		  <?php if(Session::exists(addLink) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['addLink_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(addLink); ?>
		  
		  <?php if(Session::exists(notLink) == true) { //If email is sent ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['notLink_Error']; ?>
	        <?php echo Session::get(notLink); ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(notLink); ?>
		  	
		  <?php if(Session::exists(updateLink) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updateLink_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(updateLink); ?>	  
		  
          </div>
          		 	
		 	<div class="col-md-12">
		 		
	  		 <div class="box box-info">
	        <div class="box-header">
	          <a href="#addlink" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['add']; ?> <?php echo $lang['link']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addlink" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['link']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
               <input type="hidden" id="jobid" value="<?php echo escape($jobid); ?>"/>
              	
              <div class="form-group">	
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div>      
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="description" class="form-control" rows="5"></textarea>
              </div>  
              
              <div class="form-group">	
			    <label><?php echo $lang['link']; ?> <?php echo $lang['url']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="url" class="form-control" placeholder="http://"/>
               </div>
              </div>  

	         
             <div class="modal-footer">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addlink()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>		 		


		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['link']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['description']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("link", "*", ["AND" => ["jobid" => $jobid, "freelancerid" => $freelancerid,], "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
	
					    echo '<tr>';
					    echo '<td><a href="'. escape($row->url) .'" target="_blank">'. escape($row->title) .'</a></td>';
					    echo '<td>'. escape($row->description) .'</td>';
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '<td>
					      <a href="'. escape($row->url) .'" target="_blank" class="btn btn-primary btn-xs" data-toggle="tooltip" title="' . $lang['go'] . ' ' . $lang['to'] . ' ' . $lang['link'] . '"><span class="fa fa-external-link"></span></a>
					      <a onclick="getLinkDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					      <a id="' . escape($row->id) . '" class="btn btn-danger btn-lin btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>
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
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['description']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
	      <!-- Modal HTML -->
	      <div id="editlink" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['link']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
               <input type="hidden" id="update_linkid"/>
              	
              <div class="form-group">	
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div>      
              
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="update_description" class="form-control" rows="5"></textarea>
              </div>    
              
              <div class="form-group">	
			    <label><?php echo $lang['link']; ?> <?php echo $lang['url']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_url" class="form-control" placeholder="http://..."/>
               </div>
              </div>  
	         
             <div class="modal-footer">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="updatelink()"  class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>              
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>	    
	    

		 <?php elseif (Input::get('a') == 'bugs' && !Input::get('bug')) : ?> 
         <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 <div class="col-md-12">	
		  <?php if(Session::exists(addBug) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['addBug_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(addBug); ?>
		  
		  <?php if(Session::exists(notBug) == true) { //If email is sent ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['notBug_Error']; ?>
	        <?php echo Session::get(notBug); ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(notBug); ?>
		  	
		  <?php if(Session::exists(updateBug) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updateBug_Error']; ?>
		   </div>
		  <?php } ?>
		  <?php Session::delete(updateBug); ?>	  
		  
          </div>
          		 	
		 	<div class="col-md-12">
		 		
	  		 <div class="box box-info">
	        <div class="box-header">
	          <a href="#addbug" class="btn btn-success btn-lg" data-toggle="modal"><?php echo $lang['add']; ?> <?php echo $lang['bug']; ?></a>
	        </div><!-- /.box-header -->
	      </div><!-- /.box -->	
	      
	      <!-- Modal HTML -->
	      <div id="addbug" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['add']; ?> <?php echo $lang['bug']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
               <input type="hidden" id="jobid" value="<?php echo escape($jobid); ?>"/>
              	
              <div class="form-group">	
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div>     
              
			  <div class="form-group">
			    <label><?php echo $lang['priority']; ?></label>
				<select id="priority" type="text" class="form-control" >
				  <option value="Low"><?php echo $lang['low']; ?></option>
				  <option value="Medium"><?php echo $lang['medium']; ?></option>
				  <option value="High"><?php echo $lang['high']; ?></option>
				 </select>
			  </div> 
              
			  <div class="form-group">
			    <label><?php echo $lang['severity']; ?></label>
				<select id="severity" type="text" class="form-control" >
				  <option value="Minor"><?php echo $lang['minor']; ?></option>
				  <option value="Major"><?php echo $lang['major']; ?></option>
				  <option value="Show Stopper"><?php echo $lang['show']; ?> <?php echo $lang['stopper']; ?></option>
				  <option value="Must be Fixed"><?php echo $lang['must']; ?> <?php echo $lang['be']; ?> <?php echo $lang['fixed']; ?></option>
				 </select>
			  </div> 
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="description" class="form-control" rows="5" placeholder="<?php echo $lang['bug_desc']; ?>"></textarea>
              </div>  
              
              <div class="form-group">	
			   <label><?php echo $lang['reproducibility']; ?></label>
               <textarea type="text" id="reproducibility" class="form-control" rows="5" placeholder="<?php echo $lang['bug_repro']; ?>"></textarea>
              </div>  
	         
             <div class="modal-footer">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="addbug()"  class="btn btn-success"><?php echo $lang['submit']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>		 		


		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['bug']; ?> <?php echo $lang['list']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                 <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['reporter']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['priority']; ?></th>
					   <th><?php echo $lang['severity']; ?></th>
					   <th><?php echo $lang['fixed']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
                     $query = DB::getInstance()->get("bugs", "*", ["AND" => ["jobid" => $jobid], "ORDER" => "date_added DESC"]);
                     if($query->count()) {
						foreach($query->results() as $row) {
							
						$q1 = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $row->reporter, "LIMIT" => 1]);
						if ($q1->count() === 1) {
						 foreach($q1->results() as $r1) {
						  $reporter .='
						             <img src="../Freelancer/'. escape($r1->imagelocation) .'" class="img-responsive img-thumbnail" width="50" height="40"/>
							             <a href="../freelancer.php?a=overview&id='. escape($r1->freelancerid) .'" target="_blank">'. escape($r1->name) .'</a>
						            ';	
						 }
						}else {
						    $q1 = DB::getInstance()->get("client", "*", ["clientid" => $row->reporter, "LIMIT" => 1]);
							if ($q1->count() === 1) {
							 foreach($q1->results() as $r1) {
							  $reporter .='
							             <img src="../Client/'. escape($r1->imagelocation) .'" class="img-responsive img-thumbnail" width="50" height="40"/>
							             <a href="../client.php?a=overview&id='. escape($r1->clientid) .'" target="_blank">'. escape($r1->name) .'</a>
							            ';	
							 }
							}else {
								$reporter .='';
							}							
							
						}	
					
                        if(!$row->fixed == 1):
					    $mark = '
					    <a id="' . escape($row->id) . '" class="btn btn-info btn-fixed btn-xs" data-toggle="tooltip" title="' . $lang['fixed'] . '"><span class="fa fa-check-square"></span></a>
					    <a onclick="getBugDetails('.$row->id.')" class="btn btn-success btn-xs" data-toggle="tooltip" title="' . $lang['edit'] . '"><span class="fa fa-edit"></span></a>
					    <a id="' . escape($row->id) . '" class="btn btn-danger btn-bug btn-xs" data-toggle="tooltip" title="' . $lang['delete'] . '"><span class="fa fa-trash"></span></a>
					      ';
						else:
					    $mark = '<a id="' . escape($row->id) . '" class="btn btn-default btn-pending btn-xs" data-toggle="tooltip" title="' . $lang['pending'] . '"><span class="fa fa-close"></span></a>';
						endif;												
	
					    echo '<tr>';
					    echo '<td>'. $reporter .'</td>';
					    echo '<td><a href="jobboard.php?a=bugs&bug='. escape($row->id) .'&id='. escape($row->jobid) .'">'. escape($row->title) .'</a></td>';
					    echo '<td>'. escape($row->priority) .'</td>';
					    echo '<td>'. escape($row->severity) .'</td>';
					    
					    if (escape($row->fixed) == 1) {
					    echo '<td><span class="label label-success"> ' . $lang['fixed'] . ' </span> </td>';
						} else {
					    echo '<td><span class="label label-success"> ' . $lang['pending'] . ' </span> </td>';
						}
						
					    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
					    echo '<td>
					    '. $mark .'
					      <a href="jobboard.php?a=bugs&bug='. escape($row->id) .'&id='. escape($row->jobid) .'" class="btn btn-primary btn-xs" data-toggle="tooltip" title="' . $lang['view'] . '"><span class="fa fa-eye"></span></a>
                          </td>';
					    echo '</tr>';
						unset($reporter);
						unset($mark);
					   }
					}else {
						echo $lang['no_results'];
					}
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['reporter']; ?></th>
					   <th><?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['priority']; ?></th>
					   <th><?php echo $lang['severity']; ?></th>
					   <th><?php echo $lang['fixed']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
					   <th><?php echo $lang['action']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              
	      <!-- Modal HTML -->
	      <div id="editbug" class="modal fade">
	       <div class="modal-dialog">
	        <div class="modal-content">
	         <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title"><?php echo $lang['edit']; ?> <?php echo $lang['link']; ?></h4>
	         </div>
	         <div class="modal-body">
              <form role="form" method="post" id="addform" enctype="multipart/form-data"> 
               <input type="hidden" id="update_bugid"/>
              	
              <div class="form-group">	
			    <label><?php echo $lang['title']; ?></label>
               <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-info"></i></span>
                <input type="text" id="update_title" class="form-control" placeholder="<?php echo $lang['title']; ?>"/>
               </div>
              </div>      
              
			  <div class="form-group">
			    <label><?php echo $lang['priority']; ?></label>
				<select id="update_priority" type="text" class="form-control" >
				  <option value="Low"><?php echo $lang['low']; ?></option>
				  <option value="Medium"><?php echo $lang['medium']; ?></option>
				  <option value="High"><?php echo $lang['high']; ?></option>
				 </select>
			  </div> 
              
			  <div class="form-group">
			    <label><?php echo $lang['severity']; ?></label>
				<select id="update_severity" type="text" class="form-control" >
				  <option value="Minor"><?php echo $lang['minor']; ?></option>
				  <option value="Major"><?php echo $lang['major']; ?></option>
				  <option value="Show Stopper"><?php echo $lang['show']; ?> <?php echo $lang['stopper']; ?></option>
				  <option value="Must be Fixed"><?php echo $lang['must']; ?> <?php echo $lang['be']; ?> <?php echo $lang['fixed']; ?></option>
				 </select>
			  </div> 
               
              <div class="form-group">	
			   <label><?php echo $lang['description']; ?></label>
               <textarea type="text" id="update_description" class="form-control" rows="5"></textarea>
              </div>  
              
              <div class="form-group">	
			   <label><?php echo $lang['reproducibility']; ?></label>
               <textarea type="text" id="update_reproducibility" class="form-control" rows="5"></textarea>
              </div>  
	         
             <div class="modal-footer">
              <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['close']; ?></button>
              <button onclick="updatebug()"  class="btn btn-success"><?php echo $lang['update']; ?></button>
             </div>
             
             </div>
             </form> 
             
	        </div>
	       </div>
	      </div>              
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->	
	    </div>	    

		 <?php elseif (Input::get('a') == 'bugs' && Input::get('bug')) : ?> 
         <div class="col-lg-12">		 	
		 <div class="row">	
          		 	
		 	<div class="col-md-12">
		 		
			<?php
			//Get Porfolio Data
			$bugid = Input::get('bug');
			$query = DB::getInstance()->get("bugs", "*", ["id" => $bugid, "LIMIT" => 1]);
			if ($query->count() === 1) {
			 foreach($query->results() as $row) {
			 	$reporter = $row->reporter;
			 	$bug_title = $row->title;
			 	$bug_priority = $row->priority;
			 	$bug_severity = $row->severity;
			 	$bug_description = $row->description;
			 	$bug_reproducibility = $row->reproducibility;
				$bug_date_added = strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added));
			 	$bug_fixed = $row->fixed;
			 }			
			}else {
			  Redirect::to('jobboard.php?a=bugs&id='.$jobid.'');
			}

			$q = DB::getInstance()->get("freelancer", "*", ["freelancerid" => $reporter, "LIMIT" => 1]);
			if ($q->count() === 1) {
			 foreach($q->results() as $r) {
			 	$reporter_freelancer = $r->name;
			 }			
			}else {
				$q = DB::getInstance()->get("client", "*", ["clientid" => $reporter, "LIMIT" => 1]);
				if ($q->count() === 1) {
				 foreach($q->results() as $r) {
				 	$reporter_client = $r->name;
				 }
				} 
			}
			
		    if (escape($row->fixed) == 1) {
		     $fixed .= ' ' . $lang['fixed'] . ' ';
			} else {
		     $fixed .= ' ' . $lang['pending'] . ' ';
			}

			?>		 		
		 		
		 	 <div class="col-lg-6">	
			  <section class="panel panel-default">
	            <ul class="list-group no-radius">
	             <li class="list-group-item">
	              <span class="pull-right text"><?php echo escape($job_title); ?></span>
	              <span class="text-muted"><?php echo $lang['job']; ?> <?php echo $lang['title']; ?></span>
	             </li>
	             <li class="list-group-item">
	              <span class="pull-right"><?php echo escape($bug_title); ?></a></span>
	              <span class="text-muted"><?php echo $lang['title']; ?></span>
	             </li>
	             <li class="list-group-item">
	              <span class="pull-right text"><?php echo escape($reporter_freelancer); ?> <?php echo escape($reporter_client); ?></span>
	              <span class="text-muted"><?php echo $lang['reporter']; ?></span>
	             </li>
	             <li class="list-group-item">
	              <span class="pull-right text"><?php echo escape($fixed); ?></span>
	              <span class="text-muted"><?php echo $lang['fixed']; ?></span>
	             </li>
	            </ul>
	           </section>

		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['bug']; ?> <?php echo $lang['description']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<?php echo escape($bug_description); ?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->	
                         	
	          </div> 
	          
		 	 <div class="col-lg-6">	
			  <section class="panel panel-default">
	            <ul class="list-group no-radius">
	             <li class="list-group-item">
	              <span class="pull-right"><?php echo escape($bug_priority); ?></span>
	              <span class="text-muted"><?php echo $lang['priority']; ?></span>
	             </li>
	             <li class="list-group-item">
	              <span class="pull-right"><?php echo escape($bug_severity); ?></span>
	              <span class="text-muted"><?php echo $lang['severity']; ?></span>
	             </li>
	             <li class="list-group-item">
	              <span class="pull-right"><?php echo escape($bug_date_added); ?></span>
	              <span class="text-muted"><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></span>
	             </li>
	            </ul>
	           </section>	

		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['bug']; ?> <?php echo $lang['reproducibility']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                	<?php echo escape($bug_reproducibility); ?>
                </div><!-- /.box-body -->
              </div><!-- /.box -->	
              
	          </div> 	          
	          

		 		
		  <?php
         $query = DB::getInstance()->get("client", "*", ["clientid" => $clientid, "LIMIT" => 1]);
		 if($query->count()) {
		 	
		    $x = 1;
			foreach($query->results() as $row) {
		    $messageList = '';
			  
		    echo $messageList .= '
          
		 <div class="col-md-8 col-lg-offset-2">
		 	
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title">' . $lang['comments'] . '</h3>
                </div>
                  <div class="box-footer bg-body-light">
                  
                  <div class="form-group message-reply-container">	
                    <textarea onclick="showMsgButton('. $row->id .')" id="summernote" placeholder="Post Message" class="form-control"></textarea>
                  </div>
                    <div id="message_btn_'. $row->id .'" class="comment-btn">
                     <a onclick="postComment('. $row->id .', '. $freelancer->data()->freelancerid .', '.$clientid.', '. $bugid .')" class="btn btn-primary">Post</a>
					</div>
                  </div>
                <div class="box-body">
                	
                  <div id="messages-list'.$row->id.'" class="post-comments" style="overflow-y: scroll; height: 600px; width: 100%;">
                  '.getCommentF($clientid, $freelancer->data()->freelancerid, $bugid ).'
                  </div>
                  
                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
		 
		</div><!-- /.col -->
				    
					 ';
				
             unset($messageList);	 
			 $x++;		 
		   }
		}else {
		 echo $messageList = '<p>'.$lang['no_content_found'].'</p>';
		}
       ?>	          
	         
		 </div><!-- /.col-md-12 -->	 
		 
	    </div><!-- /.row -->	
	    </div>	  			 			    
	    	    
		 <?php elseif (Input::get('a') == 'funds') : ?> 	
         <div class="col-lg-12">		 	
		 <div class="row">	
		 	<div class="col-md-12">
		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['payments']; ?> <?php echo $lang['funds']; ?> <?php echo $lang['paid']; ?> <?php echo $lang['for']; ?> <?php echo $lang['this']; ?> <?php echo $lang['job']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
					   <th><?php echo $lang['milestone']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['job']; ?> <?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['client']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['freelancer']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['transaction']; ?> <?php echo $lang['type']; ?></th>
					   <th><?php echo $lang['payment']; ?></th>
					   <th><?php echo $lang['complete']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
                      </tr>
                    </thead>
                    <tbody>
				    <?php
						$q3 = DB::getInstance()->get("job", "*", ["AND" =>["jobid" => $jobid, "freelancerid" => $freelancer->data()->freelancerid, "invite" => "0", "delete_remove" => 0, "accepted" => 1]]);
						if ($q3->count()) {
						 foreach($q3->results() as $r3) {
							 
							$q4 = DB::getInstance()->get("milestone", "*", ["AND" =>["jobid" => $r3->jobid]]);
							if ($q4->count()) {
							 foreach($q4->results() as $r4) {
							 	$milestoneid = $r4->id;			
							 	$clientid = $r4->clientid;	
								  $milestone_name = $r4->name;	
								 
							    $q6 = DB::getInstance()->get("client", "*", ["clientid" => $clientid]);
								 foreach ($q6->results() as $r6) {
								  $client_name = $r6->name;	
							     }
							 									
                              $query = DB::getInstance()->get("transactions", "*", ["AND" => ["membershipid" => $milestoneid, "freelancerid" => $clientid, "transaction_type" => 4], "ORDER" => "date_added DESC"]);
					    
		                     if($query->count()) {
								foreach($query->results() as $row) {
									
														
							    echo '<tr>';
							    echo '<td>'. escape($milestone_name) .'</td>';
							    echo '<td>'. escape($job_title) .'</td>';
							    echo '<td><a href="../client.php?a=overview&id='. $clientid .'" target="_blank">'. escape($client_name) .'</a></td>';
							    echo '<td><a href="../freelancer.php?a=overview&id='. $r3->freelancerid .'" target="_blank">'. escape($freelancer->data()->name) .'</a></td>';
								if(escape($row->transaction_type) == 1):
							      echo '<td><span class="label label-success"> ' . $lang['payment'] . ' ' . $lang['for'] . ' ' . $lang['membership'] . ' </span> </td>';
								elseif(escape($row->transaction_type) == 2):
							      echo '<td><span class="label label-success"> ' . $lang['payment'] . ' ' . $lang['for'] . ' ' . $lang['boosting'] . ' ' . $lang['of'] . ' ' . $lang['bids'] . ' </span> </td>';
								elseif(escape($row->transaction_type) == 3):
							      echo '<td><span class="label label-success"> ' . $lang['payment'] . ' ' . $lang['for'] . ' ' . $lang['boosting'] . ' ' . $lang['of'] . ' ' . $lang['job'] . ' </span> </td>';
								elseif(escape($row->transaction_type) == 4):
							      echo '<td><span class="label label-success"> ' . $lang['payment'] . ' ' . $lang['for'] . ' ' . $lang['job'] . ' ' . $lang['work'] . ' </span> </td>';
								endif;	
							    echo '<td>'. escape($currency_symbol) .' '. escape($row->payment) .'</td>';
								
							    if (escape($row->complete) == 1) {
							    echo '<td><span class="label label-success"> ' . $lang['complete'] . ' </span> </td>';
								} else {
							    echo '<td><span class="label label-success"> ' . $lang['in_complete'] . ' </span> </td>';
								}
								
							    echo '<td>'. escape(strftime("%b %d, %Y, %H : %M %p ", strtotime($row->date_added))) .'</td>';
							    echo '</tr>';
								unset($view);
						        unset($reporter);
							   }
							}
												  
							  
							 }
							}
						 }
						} 	 
			        ?>
                    </tbody>
                    <tfoot>
                      <tr>
					   <th><?php echo $lang['milestone']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['job']; ?> <?php echo $lang['title']; ?></th>
					   <th><?php echo $lang['client']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['freelancer']; ?> <?php echo $lang['name']; ?></th>
					   <th><?php echo $lang['transaction']; ?> <?php echo $lang['type']; ?></th>
					   <th><?php echo $lang['payment']; ?></th>
					   <th><?php echo $lang['complete']; ?></th>
					   <th><?php echo $lang['date']; ?> <?php echo $lang['added']; ?></th>
                      </tr>
                    </tfoot>
                  </table>
                  </div><!-- /.table-responsive -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->  			  
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->		 		 	    	  				 	 	
		</div> 	

		 <?php elseif (Input::get('a') == 'ratings') : ?> 	
         <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		 <div class="col-md-12">	

		  <?php if(Session::exists(noError) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['saved_success']; ?>
		   </div>
		  <?php Session::get(noError); ?>
		  <?php } ?>
		  <?php Session::delete(noError); ?>
		  
		  <?php if(Session::exists(hasError) == true) { //If email is sent ?>
	       <div class="alert alert-danger fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['hasError']; ?></strong> <?php echo $lang['has_Error']; ?>
		   </div>
		  <?php Session::get(hasError); ?>
		  <?php } ?>
		  <?php Session::delete(hasError); ?>
		  
		  <?php if(Session::exists(updatedError) == true) { //If email is sent ?>
	       <div class="alert alert-success fade in">
	        <a href="#" class="close" data-dismiss="alert">&times;</a>
	        <strong><?php echo $lang['noError']; ?></strong> <?php echo $lang['updated_success']; ?>
		   </div>
		  <?php Session::get(updatedError); ?>
		  <?php } ?>
		  <?php Session::delete(updatedError); ?>	
	      
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

		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"> <?php echo $lang['rate']; ?> <?php echo $lang['the']; ?> <?php echo $lang['client']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div id="result"></div>	
                  <input type="hidden" id="jobid" value="<?php echo escape($jobid); ?>"/>
                  <input type="hidden" id="freelancerid" value="<?php echo escape($clientid); ?>"/>
					<div id="star-container">
					<?php
						$query = DB::getInstance()->get("ratings_client", "*", ["AND" => ["jobid" => $jobid, 
						                                                                  "freelancerid" => $freelancer->data()->freelancerid, 
						                                                                  "star_type" => 1], "LIMIT" => 1]);
						if ($query->count() === 1) {
						 foreach($query->results() as $row) {
						  $star = $row->star;
						 }
						}					
					?>	
					   <?php if($star === '1'): ?>
						<i class="fa fa-star fa-3x star star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star" id="star-2"></i>
						<i class="fa fa-star fa-3x star" id="star-3"></i>
						<i class="fa fa-star fa-3x star" id="star-4"></i>
						<i class="fa fa-star fa-3x star" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php elseif($star === '2'): ?>	
						<i class="fa fa-star fa-3x star star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-2"></i>
						<i class="fa fa-star fa-3x star" id="star-3"></i>
						<i class="fa fa-star fa-3x star" id="star-4"></i>
						<i class="fa fa-star fa-3x star" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php elseif($star === '3'): ?>	
						<i class="fa fa-star fa-3x star star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-2"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-3"></i>
						<i class="fa fa-star fa-3x star" id="star-4"></i>
						<i class="fa fa-star fa-3x star" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php elseif($star === '4'): ?>	
						<i class="fa fa-star fa-3x star star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-2"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-3"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-4"></i>
						<i class="fa fa-star fa-3x star" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php elseif($star === '5'): ?>	
						<i class="fa fa-star fa-3x star star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-2"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-3"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-4"></i>
						<i class="fa fa-star fa-3x star star-checked" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star" id="star-1"></i>
						<i class="fa fa-star fa-3x star" id="star-2"></i>
						<i class="fa fa-star fa-3x star" id="star-3"></i>
						<i class="fa fa-star fa-3x star" id="star-4"></i>
						<i class="fa fa-star fa-3x star" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings_client", "*", ["AND" => ["jobid" => $jobid, 
						                                                                  "freelancerid" => $freelancer->data()->freelancerid, 
						                                                                  "star_type" => 2], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star2 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star2 === '1'): ?>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php elseif($star2 === '2'): ?>	
						<i class="fa fa-star fa-3x star2 star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php elseif($star2 === '3'): ?>	
						<i class="fa fa-star fa-3x star2 star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php elseif($star2 === '4'): ?>	
						<i class="fa fa-star fa-3x star2 star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php elseif($star2 === '5'): ?>	
						<i class="fa fa-star fa-3x star2 star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star2 star-checked" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star2" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star2" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings_client", "*", ["AND" => ["jobid" => $jobid, 
						                                                                  "freelancerid" => $freelancer->data()->freelancerid, 
						                                                                  "star_type" => 3], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star3 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star3 === '1'): ?>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php elseif($star3 === '2'): ?>	
						<i class="fa fa-star fa-3x star3 star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php elseif($star3 === '3'): ?>	
						<i class="fa fa-star fa-3x star3 star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php elseif($star3 === '4'): ?>	
						<i class="fa fa-star fa-3x star3 star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php elseif($star3 === '5'): ?>	
						<i class="fa fa-star fa-3x star3 star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star3 star-checked" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star3" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star3" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings_client", "*", ["AND" => ["jobid" => $jobid, 
						                                                                  "freelancerid" => $freelancer->data()->freelancerid, 
						                                                                  "star_type" => 4], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star4 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star4 === '1'): ?>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['requirements']; ?></span>
					   <?php elseif($star4 === '2'): ?>	
						<i class="fa fa-star fa-3x star4 star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['requirements']; ?></span>
					   <?php elseif($star4 === '3'): ?>	
						<i class="fa fa-star fa-3x star4 star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['requirements']; ?></span>
					   <?php elseif($star4 === '4'): ?>	
						<i class="fa fa-star fa-3x star4 star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['requirements']; ?></span>
					   <?php elseif($star4 === '5'): ?>	
						<i class="fa fa-star fa-3x star4 star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star4 star-checked" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['requirements']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star4" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star4" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['requirements']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings_client", "*", ["AND" => ["jobid" => $jobid, 
						                                                                  "freelancerid" => $freelancer->data()->freelancerid, 
						                                                                  "star_type" => 5], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star5 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star5 === '1'): ?>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['set']; ?> <?php echo $lang['reasonable']; ?> <?php echo $lang['deadlines']; ?></span>
					   <?php elseif($star5 === '2'): ?>	
						<i class="fa fa-star fa-3x star5 star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['set']; ?> <?php echo $lang['reasonable']; ?> <?php echo $lang['deadlines']; ?></span>
					   <?php elseif($star5 === '3'): ?>	
						<i class="fa fa-star fa-3x star5 star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['set']; ?> <?php echo $lang['reasonable']; ?> <?php echo $lang['deadlines']; ?></span>
					   <?php elseif($star5 === '4'): ?>	
						<i class="fa fa-star fa-3x star5 star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['set']; ?> <?php echo $lang['reasonable']; ?> <?php echo $lang['deadlines']; ?></span>
					   <?php elseif($star5 === '5'): ?>	
						<i class="fa fa-star fa-3x star5 star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star5 star-checked" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['set']; ?> <?php echo $lang['reasonable']; ?> <?php echo $lang['deadlines']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star5" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star5" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['set']; ?> <?php echo $lang['reasonable']; ?> <?php echo $lang['deadlines']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings_client", "*", ["AND" => ["jobid" => $jobid, 
						                                                                  "freelancerid" => $freelancer->data()->freelancerid, 
						                                                                  "star_type" => 6], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star6 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star6 === '1'): ?>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php elseif($star6 === '2'): ?>	
						<i class="fa fa-star fa-3x star6 star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php elseif($star6 === '3'): ?>	
						<i class="fa fa-star fa-3x star6 star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php elseif($star6 === '4'): ?>	
						<i class="fa fa-star fa-3x star6 star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php elseif($star6 === '5'): ?>	
						<i class="fa fa-star fa-3x star6 star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star6 star-checked" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star6" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star6" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php endif; ?>	
					</div>
					<br></br>
					<?php
                    $success = DB::getInstance()->sum("ratings_client", "star", ["AND" => ["star_type[!]" => 7,
                                                                                    "jobid" => $jobid,
                                                                                    "clientid" => $clientid,
                                                                                    "freelancerid" => $freelancer->data()->freelancerid]]);
					foreach($success->results()[0] as $suc) {
						$suc_new = $suc;
					}
					
					$percentage = $suc_new/30 * 100;
			        $percentage = round($percentage, 1);
					?>
					<h3><?php echo $lang['job']; ?> <?php echo $lang['success']; ?> <?php echo $lang['score']; ?></h3>
					<input class="knob" data-width="75" data-angleOffset="40" data-linecap="round" value="<?php echo $percentage; ?>" style="margin-bottom: 0px !important;"/>
					
                 <form role="form" method="post" style="position: relative; top: -80px !important;"> 
                  <input type="hidden" name="jobid" value="<?php echo escape($jobid); ?>"/>
                  <input type="hidden" name="clientid" value="<?php echo escape($clientid); ?>"/>
                  <div class="form-group">	
                  
				    <label><?php echo $lang['share']; ?> 
				    	   <?php echo $lang['your']; ?>
				    	   <?php echo $lang['experience']; ?>
				    	   <?php echo $lang['with']; ?>
				    	   <?php echo $lang['this']; ?>
				    	   <?php echo $lang['client']; ?>
				    	   <?php echo $lang['to']; ?>
				    	   <?php echo $lang['other']; ?>
				    	   <?php echo $lang['freelancers']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <textarea type="text" name="message_rating" class="form-control" rows="4"><?php
                         if (isset($_POST['site'])) {
							 echo escape(Input::get('message_rating')); 
						  } else {
						  echo escape($message_rating); 
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
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->		 		 	    	  				 	 	
		</div> 			 
		
		
		<?php elseif (Input::get('a') == 'cratings') : ?> 	
         <div class="col-lg-12">		 	
		 <div class="row">	
		 	
		<div class="col-lg-12">	  

		 		
		 		<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"> <?php echo $lang['your']; ?> 
                  	                     <?php echo $lang['ratings']; ?> 
                  	                     <?php echo $lang['by']; ?>  
                  	                     <?php echo $lang['client']; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div id="result"></div>	
					<div id="star-container">
					<?php
						$query = DB::getInstance()->get("ratings", "*", ["AND" => ["jobid" => $jobid, "star_type" => 1], "LIMIT" => 1]);
						if ($query->count() === 1) {
						 foreach($query->results() as $row) {
						  $star = $row->star;
						 }
						}					
					?>	
					   <?php if($star === '1'): ?>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php elseif($star === '2'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php elseif($star === '3'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php elseif($star === '4'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php elseif($star === '5'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star-null" id="star-1"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['skills']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings", "*", ["AND" => ["jobid" => $jobid, "star_type" => 2], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star2 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star2 === '1'): ?>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['work']; ?></span>
					   <?php elseif($star2 === '2'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['work']; ?></span>
					   <?php elseif($star2 === '3'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['work']; ?></span>
					   <?php elseif($star2 === '4'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['work']; ?></span>
					   <?php elseif($star2 === '5'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['work']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star-null" id="star-1-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-2"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-2"></i>
						&nbsp;&nbsp; <span><?php echo $lang['quality']; ?> <?php echo $lang['of']; ?> <?php echo $lang['work']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings", "*", ["AND" => ["jobid" => $jobid, "star_type" => 3], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star3 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star3 === '1'): ?>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php elseif($star3 === '2'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php elseif($star3 === '3'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php elseif($star3 === '4'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php elseif($star3 === '5'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star-null" id="star-1-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-3"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-3"></i>
						&nbsp;&nbsp; <span><?php echo $lang['availability']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings", "*", ["AND" => ["jobid" => $jobid, "star_type" => 4], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star4 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star4 === '1'): ?>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['adherence']; ?> <?php echo $lang['to']; ?> <?php echo $lang['schedule']; ?></span>
					   <?php elseif($star4 === '2'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['adherence']; ?> <?php echo $lang['to']; ?> <?php echo $lang['schedule']; ?></span>
					   <?php elseif($star4 === '3'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['adherence']; ?> <?php echo $lang['to']; ?> <?php echo $lang['schedule']; ?></span>
					   <?php elseif($star4 === '4'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['adherence']; ?> <?php echo $lang['to']; ?> <?php echo $lang['schedule']; ?></span>
					   <?php elseif($star4 === '5'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['adherence']; ?> <?php echo $lang['to']; ?> <?php echo $lang['schedule']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star-null" id="star-1-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-4"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-4"></i>
						&nbsp;&nbsp; <span><?php echo $lang['adherence']; ?> <?php echo $lang['to']; ?> <?php echo $lang['schedule']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings", "*", ["AND" => ["jobid" => $jobid, "star_type" => 5], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star5 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star5 === '1'): ?>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php elseif($star5 === '2'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php elseif($star5 === '3'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php elseif($star5 === '4'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php elseif($star5 === '5'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star-null" id="star-1-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-5"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-5"></i>
						&nbsp;&nbsp; <span><?php echo $lang['communication']; ?></span>
					   <?php endif; ?>	
					</div>
					<br/>
					<div id="star-container">
					<?php
						$q2 = DB::getInstance()->get("ratings", "*", ["AND" => ["jobid" => $jobid, "star_type" => 6], "LIMIT" => 1]);
						if ($q2->count() === 1) {
						 foreach($q2->results() as $r2) {
						  $star6 = $r2->star;
						 }
						}					
					?>	
					   <?php if($star6 === '1'): ?>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php elseif($star6 === '2'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php elseif($star6 === '3'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php elseif($star6 === '4'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php elseif($star6 === '5'): ?>	
						<i class="fa fa-star fa-3x star-null star-checked" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star-null star-checked" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php else: ?>	
						<i class="fa fa-star fa-3x star-null" id="star-1-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-2-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-3-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-4-6"></i>
						<i class="fa fa-star fa-3x star-null" id="star-5-6"></i>
						&nbsp;&nbsp; <span><?php echo $lang['cooperation']; ?></span>
					   <?php endif; ?>	
					</div>
					<br></br>
					<?php
                    $success = DB::getInstance()->sum("ratings", "star", ["AND" => ["star_type[!]" => 7,
                                                                                    "jobid" => $jobid,
                                                                                    "clientid" => $clientid,
                                                                                    "freelancerid" => $freelancer->data()->freelancerid]]);
					foreach($success->results()[0] as $suc) {
						$suc_new = $suc;
					}
					
					$percentage = $suc_new/30 * 100;
			        $percentage = round($percentage, 1);
					?>
					<h3><?php echo $lang['job']; ?> <?php echo $lang['success']; ?> <?php echo $lang['score']; ?></h3>
					<input class="knob" data-width="75" data-angleOffset="40" data-linecap="round" value="<?php echo $percentage; ?>" style="margin-bottom: 0px !important;"/>
					
                  
				    <label></label>
				    	
                 
                </div><!-- /.box-body -->
              </div><!-- /.box -->  
		 <!-- Input addon -->
              <div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['client']; ?> 
				    	   <?php echo $lang['experience']; ?>
				    	   <?php echo $lang['with']; ?>
				    	   <?php echo $lang['you']; ?></h3>
                </div>
                <div class="box-body">
                	<p><?php echo $message_rating_client; ?></p>
                </div><!-- /.box-body -->
              </div><!-- /.box -->			  
	         
		 </div><!-- /.col-lg-12 -->	 
	    </div><!-- /.row -->		 		 	    	  				 	 	
		</div> 	
         <?php endif; ?>		 	              
        		  		  
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
    <!-- Jquery UI 1.10.3 -->
	<script src="../assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <!-- UI Slider Progress -->
    <script src="../assets/js/ui-sliders-progress.js" type="text/javascript"></script>

    <script src="../assets/js/jquery.knob.min.js"></script>
    <script src="../assets/js/knob.js"></script>    
    <!-- DATA TABES SCRIPT -->
    <script src="../assets/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="../assets/js/app.min.js" type="text/javascript"></script>
    <!-- Ratings JS -->
    <script src="../assets/js/ratings.js" type="text/javascript"></script>
    <!-- page script -->
    <script type="text/javascript">
      $(function () {
        $("#example1").dataTable({
        /* No ordering applied by DataTables during initialisation */
        "order": []
        });
        $("#example2").dataTable({
        /* No ordering applied by DataTables during initialisation */
        "order": []
        });
        $("#example3").dataTable({
        /* No ordering applied by DataTables during initialisation */
        "order": []
        });
      });
    </script>
    <!-- Datetime Picker -->
    <script src="../assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script type="text/javascript">
     $('.form_datetime_start').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1, 
        startDate: new Date(),
        pickTime: false, 
        minView: 2,      
        pickerPosition: "bottom-left",
        linkField: "mirror_field_start",
        linkFormat: "hh:ii",
        linkFieldd: "mirror_field_start_date",
        linkFormatt: "dd MM yyyy"
    });
     $('.form_datetime_end').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1, 
        startDate: new Date(),
        pickTime: false, 
        minView: 2,      
        pickerPosition: "bottom-left",
        linkField: "mirror_field_start",
        linkFormat: "hh:ii",
        linkFieldd: "mirror_field_start_date",
        linkFormatt: "dd MM yyyy"
    });
   </script> 
	<script type="text/javascript">
	$(function() {
	
	$(".btn-fixed").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['bug_fixed']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/bugfixed.php",
				 data: info,
				 success: function()
					   {
						window.location.reload();
					   }
				});
			 
	
			  }
		   return false;
	
		});	
	
	});
	</script>
	
	<script type="text/javascript">
	$(function() {
	
	$(".btn-pending").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['bug_pending']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/actions/bugpending.php",
				 data: info,
				 success: function()
					   {
						window.location.reload();
					   }
				});
			 
	
			  }
		   return false;
	
		});		
	
	});
	</script>	   

    <script type="text/javascript">
	$(function() {
	
	
	$(".btn-task").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_task']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletetask.php",
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
	
	
	$(".btn-file").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_file']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletefile.php",
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
	$(".btn-lin").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_link']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletelink.php",
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
	$(".btn-bug").click(function(){
	
	//Save the link in a variable called element
	var element = $(this);
	
	//Find the id of the link that was clicked
	var id = element.attr("id");
	
	//Built a url to send
	var info = 'id=' + id;
	 if(confirm("<?php echo $lang['delete_bug']; ?>"))
			  {
			var parent = $(this).parent().parent();
				$.ajax({
				 type: "GET",
				 url: "template/delete/deletebug.php",
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
	function viewMilestone(id) {
	    // Add User ID to the hidden field for furture usage
	   // $("#hidden_user_id").val(id);
	    //$("#update_taskid").val(id);
	    $.post("template/requests/readmilestonedetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var milestone = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $(".name").html(milestone.name);
	            $(".description").html(milestone.description);
	            $(".start_date").html(milestone.start_date);
	            $(".end_date").html(milestone.end_date);
	            $(".p").html("$" + milestone.budget);
	                     
	        }
	    );
	    // Open modal popup
	    $("#viewmilestone").modal("show");
	}   
	function getMid(id) {
	    // Add User ID to the hidden field for furture usage
	   // $("#hidden_user_id").val(id);
	    $("#milestoneid").val(id);
	    // Open modal popup
	    $("#addtask").modal("show");
	}   
	// Add Task
	function addtask() {	
		

        var progress = $('#progress').slider("option", "value");

	    // get values
	    var jobid = $("#jobid").val();
	    var milestoneid = $("#milestoneid").val();
	    var name = $("#name").val();
	    var description = $("#description").val();
	    var budget = $("#budget").val();
	    var start_date = $("#start_date").val();
	    var end_date = $("#end_date").val();
	    
		//Built a url to send    
		var info = "milestoneid="+milestoneid+"&jobid="+jobid+"&name="+name+"&description="+description+"&progress="+progress+"&budget="+budget+"&start_date="+start_date+"&end_date="+end_date;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/addtask.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#addtask").modal("hide");
	        $("#addtaskn").modal("hide");
            }
        });
	}   
	function getTaskDetails(id) {
	    // Add User ID to the hidden field for furture usage
	   // $("#hidden_user_id").val(id);
	    $("#update_taskid").val(id);
	    $.post("template/requests/readtaskdetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var task = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_name").val(task.name);
	            $("#update_description").val(task.description);
	            $("#update_start_date").val(task.start_date);
	            $("#update_end_date").val(task.end_date);
	            
	            var $ve = task.milestoneid;
				$('#update_milestoneid option').each(function() {
				    if($(this).val() == $ve) {
				        $(this).prop("selected", true);
				    }
				});	  
		   
	            var $pe = task.progress;
				$(".sliderMinn").slider({
					range: "min",
					value: $pe,
					min: 0,
					max: 100,
					slide: function( event, ui ) {
			            $( ".sliderMinnLabel" ).html( ui.value + "%" );
					}
				});			
                    $('.sliderMinnLabel').html( $pe + "%" ); 
	            
	            //var $ve = 3;
	            //$("#update_m option[value=3]").attr("selected","selected");
	                     
	        }
	    );
	    // Open modal popup
	    $("#edittask").modal("show");
	}  
	function viewTask(id) {
	    // Add User ID to the hidden field for furture usage
	   // $("#hidden_user_id").val(id);
	    //$("#update_taskid").val(id);
	    $.post("template/requests/readtaskdetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var task = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $(".name").html(task.name);
	            $(".description").html(task.description);
	            $(".start_date").html(task.start_date);
	            $(".end_date").html(task.end_date);
	            $(".p").html(task.progress + "%");
	                     
	        }
	    );
	    // Open modal popup
	    $("#viewtask").modal("show");
	}	
	function updatetask() {
	    // get values
        var progress = $('#update_progress').slider("option", "value");
        
	    var milestoneid = $("#update_milestoneid").val();
	    var name = $("#update_name").val();
	    var description = $("#update_description").val();
	    var budget = $("#update_budget").val();
	    var start_date = $("#update_start_date").val();
	    var end_date = $("#update_end_date").val();
	
	    // get hidden field value
	    var id = $("#update_taskid").val();
	    
		//Built a url to send       
		var info = "id="+id+"&milestoneid="+milestoneid+"&name="+name+"&description="+description+"&progress="+progress+"&start_date="+start_date+"&end_date="+end_date;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/updatetask.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#editmilestone").modal("hide");
            }
        });
	}	

	 function showMsgButton(id) {
		$('#message_btn_'+id).fadeIn('slow');
	}   
	function postDiscussion(id, freelancerid, clientid) {
		var message = $('#summernote').val();
		
		$('#post_comment_'+id).html('<div class="preloader-retina-large preloader-center"></div>');
		
		// Remove the post button
		$('#message_btn_'+id).fadeOut('slow');
		
		$.ajax({
			type: "POST",
			url: "template/requests/post_discussion.php",
			data: "clientid="+clientid+"&freelancerid="+freelancerid+"&message="+encodeURIComponent(message), 
			cache: false,
			success: function(html) {
				// Remove the loader animation
				$('#post_comment_'+id).html('');
			
				// Append the new comment to the div id
				$('#messages-list'+id).prepend(html);
				
				// Fade In the style="display: none" class
				$('.message-reply-container').fadeIn(500);
				
				// Empty the text area
                $("#summernote").summernote("reset");			
			}
		});
	}
	function postComment(id, freelancerid, clientid, bugid) {
		var message = $('#summernote').val();
		
		$('#post_comment_'+id).html('<div class="preloader-retina-large preloader-center"></div>');
		
		// Remove the post button
		$('#message_btn_'+id).fadeOut('slow');
		
		$.ajax({
			type: "POST",
			url: "template/requests/post_comment.php",
			data: "clientid="+clientid+"&freelancerid="+freelancerid+"&bugid="+bugid+"&message="+encodeURIComponent(message), 
			cache: false,
			success: function(html) {
				// Remove the loader animation
				$('#post_comment_'+id).html('');
			
				// Append the new comment to the div id
				$('#messages-list'+id).prepend(html);
				
				// Fade In the style="display: none" class
				$('.message-reply-container').fadeIn(500);
				
				// Empty the text area
                $("#summernote").summernote("reset");			
			}
		});
	}
	function delete_the(id, freelancerid) {
		// id = unique id of the message/comment/chat
		// type = type of post: message/comment/chat
		$('#del_comment_'+id).html('<div class="preloader-retina"></div>');
		
		$.ajax({
			type: "POST",
			url: "template/requests/delete.php",
			data: "id="+id+"&freelancerid="+freelancerid, 
			cache: false,
			success: function(html) {
				if(html == '1') {
				   $('#comment'+id).fadeOut(500, function() { $('#comment'+id).remove(); });
				} else {
				   $('#comment'+id).html($('#del_comment_'+id).html('Sorry, the message could not be removed, please refresh the page and try again.'));
				}
			}
		});
	}	
	function report_the(id, freelancerid) {
		// id = unique id of the message/comment
		// type = type of post: message/comment
		
		$('#comment'+id).html('<div class="message-reported"><div class="preloader-retina"></div></div>');

		$.ajax({
			type: "POST",
			url: "template/requests/report.php",
			data: "id="+id+"&freelancerid="+freelancerid, 
			cache: false,
			success: function(html) {
				$('#comment'+id).html('<div class="message-reported">'+html+'</div>');
			}
		});
	}	
	// Add File
	function addfile() {	

		var file_data = $('#photoimg').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('photoimg', file_data);              
		form_data.append('title', $("#title").val());             
		form_data.append('description', $("#description").val());             
		form_data.append('jobid', $("#jobid").val());   
		$.ajax({                      
				type: 'POST',
				url: 'template/requests/addfile.php', // point to server-side PHP script 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,   
				success: function(data){
			        // close the popup
			        $("#addfile").modal("hide");
				}
		 });		
		
	}  
	function getFileDetails(id) {
	    // Add User ID to the hidden field for furture usage
	   // $("#hidden_user_id").val(id);
	    $("#update_fileid").val(id);
	    $.post("template/requests/readfiledetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var file = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_title").val(file.title);
	            $("#update_description").val(file.description);
	            $("#update_photoimg").html(file.file_name);
                $('#update_upload-file-info').html(file.file_name); 
  	            
	        }
	    );
	    // Open modal popup
	    $("#editfile").modal("show");
	}	
	function updatefile() {	

		var file_data = $('#update_photoimg').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('photoimg', file_data);              
		form_data.append('title', $("#update_title").val());             
		form_data.append('description', $("#update_description").val());             
		form_data.append('fileid', $("#update_fileid").val());   
		$.ajax({                      
				type: 'POST',
				url: 'template/requests/editfile.php', // point to server-side PHP script 
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,   
				success: function(data){
			        // close the popup
			        $("#editfile").modal("hide");
				}
		 });		
		
	} 	
	// Add Link
	function addlink() {	
	    // get values
	    var jobid = $("#jobid").val();
	    var title = $("#title").val();
	    var description = $("#description").val();
	    var url = $("#url").val();
	    
		//Built a url to send    
		var info = "jobid="+jobid+"&title="+title+"&description="+description+"&url="+url;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/addlink.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#addlink").modal("hide");
            }
        });
	}   
	function getLinkDetails(id) {
	    // Add User ID to the hidden field for furture usage
	    $("#update_linkid").val(id);
	    $.post("template/requests/readlinkdetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var link = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_title").val(link.title);
	            $("#update_description").val(link.description);
	            $("#update_url").val(link.url);
	        }
	    );
	    // Open modal popup
	    $("#editlink").modal("show");
	}  
	function updatelink() {
	    // get values
	    var linkid = $("#update_linkid").val();
	    var title = $("#update_title").val();
	    var description = $("#update_description").val();
	    var url = $("#update_url").val();
	    
		//Built a url to send       
		var info = "linkid="+linkid+"&title="+title+"&description="+description+"&url="+url;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/updatelink.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#editlink").modal("hide");
            }
        });
	}		
	// Add Bug
	function addbug() {	
	    // get values
	    var jobid = $("#jobid").val();
	    var title = $("#title").val();
	    var description = $("#description").val();
	    var priority = $("#priority").val();
	    var severity = $("#severity").val();
	    var reproducibility = $("#reproducibility").val();
	    
		//Built a url to send    
		var info = "jobid="+jobid+"&title="+title+"&description="+description+"&priority="+priority+"&severity="+severity+"&reproducibility="+reproducibility;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/addbug.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#addbug").modal("hide");
            }
        });
	}   
	function getBugDetails(id) {
	    // Add User ID to the hidden field for furture usage
	    $("#update_bugid").val(id);
	    $.post("template/requests/readbugdetails.php", {
	            id: id
	        },
	        function (data, status) {
	            // PARSE json data
	            var bug = JSON.parse(data);
	            // Assing existing values to the modal popup fields
	            $("#update_title").val(bug.title);
	            $("#update_description").val(bug.description);
	            $("#update_reproducibility").val(bug.reproducibility);
	            
	            var $ve = bug.priority;
				$('#update_priority option').each(function() {
				    if($(this).val() == $ve) {
				        $(this).prop("selected", true);
				    }
				});	       
				
	            var $se = bug.severity;
				$('#update_severity option').each(function() {
				    if($(this).val() == $se) {
				        $(this).prop("selected", true);
				    }
				});	
	        }
	    );
	    // Open modal popup
	    $("#editbug").modal("show");
	}   
	function updatebug() {
	    // get values
	    var bugid = $("#update_bugid").val();
	    var title = $("#update_title").val();
	    var description = $("#update_description").val();
	    var reproducibility = $("#update_reproducibility").val();
	    var priority = $("#update_priority").val();
	    var severity = $("#update_severity").val();
	    
		//Built a url to send       
		var info = "bugid="+bugid+"&title="+title+"&description="+description+"&reproducibility="+reproducibility+"&priority="+priority+"&severity="+severity;
	
	    // Add record
        $.ajax({
           type  : 'POST',
            url  : 'template/requests/updatebug.php',
		    data: info,
            success: function (data) {
	        // close the popup
	        $("#editbug").modal("hide");
            }
        });
	}		
	</script> 
</body>
</html>
