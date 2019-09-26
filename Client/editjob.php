<?php
//Check if init.php exists
if(!file_exists('../core/init.php')){
	header('Location: ../install/');        
    exit;
}else{
 require_once '../core/init.php';	
}

//Start new Client object
$client = new Client();

//Check if Client is logged in
if (!$client->isLoggedIn()) {
  Redirect::to('../index.php');	
}

//Getting Job Data
$jobid = Input::get('id');
$query = DB::getInstance()->get("job", "*", ["jobid" => $jobid, "LIMIT" => 1]);
if ($query->count() === 1) {
 foreach($query->results() as $row) {
  $jobid = $row->jobid;
  $catid = $row->catid;
  $job_title = $row->title;
  $country = $row->country;
  $job_type = $row->job_type;
  $job_budget = $row->budget;
  $job_description = $row->description;
  $job_start_date = $row->start_date;
  $job_end_date = $row->end_date;
  $public = $row->public;
  $skills = $row->skills;
  $arr=explode(',',$skills);
 }
} else {
  Redirect::to('joblist.php');
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
	  'country' => [
	     'required' => true
	   ],
	  'category' => [
	     'required' => true
	   ],
	  'job_type' => [
	     'required' => true
	   ],
	  'budget' => [
	     'required' => true,
	     'digit' => true,
		 'minlength' => 1,
		 'maxlength' => 200
	   ],
	  'start_date' => [
	     'required' => true
	   ],
	  'end_date' => [
	     'required' => true
	   ],
	  'skills_name[]' => [
	     'required' => true,
	     'minlength' => 2
	  ],
	  'description' => [
	     'required' => true
	   ]
	]);
		 
    if (!$validation->fails()) {
		
		//Update Job
		$skills = Input::get('skills_name');
        $choice1=implode(',',$skills);
		$slug = seoUrl(Input::get('title'));	
		$jobUpdate = DB::getInstance()->update('job',[
		   'description' => Input::get('description'),
		   'catid' => Input::get('category'),
		   'title' => Input::get('title'),
		   'slug' => $slug,
		   'country' => Input::get('country'),
		   'job_type' => Input::get('job_type'),
		   'budget' => Input::get('budget'),
		   'start_date' => Input::get('start_date'),
		   'end_date' => Input::get('end_date'),
		   'skills' => $choice1,
		   'public' => Input::get('make_public')
		],[
		    'jobid' => $jobid
		  ]);
		
	   if (count($jobUpdate) > 0) {
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
    <!-- Theme style -->
    <link href="../assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Select CSS-->
    <link  href="../assets/css/bootstrap-select.css" rel="stylesheet" type="text/css" />

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
          <h1><?php echo $lang['job']; ?><small><?php echo $lang['section']; ?></small></h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> <?php echo $lang['home']; ?></a></li>
            <li class="active"><?php echo $lang['edit']; ?> <?php echo $lang['job']; ?></li>
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
                  <h3 class="box-title"><?php echo $lang['edit']; ?> <?php echo $lang['job']; ?> <?php echo $lang['details']; ?></h3>
                </div>
                <div class="box-body">
                 <form role="form" method="post" id="editform"> 
                  
                  <div class="form-group">	
				    <label><?php echo $lang['title']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-info"></i></span>
                    <input type="text" name="title" class="form-control" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('title')); 
						  } else {
						  echo escape($job_title); 
						  }
					  ?>"/>
                   </div>
                  </div>

                  <div class="form-group">	
				    <label><?php echo $lang['country']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
					<select name="country" type="text" class="form-control" >
                    <?php 
                     $jl = [
                           "Remote"=>"Remote",
							"Afghanistan"=>"Afghanistan",
							"Albania"=>"Albania",
							"Algeria"=>"Algeria",
							"Algeria"=>"Algeria",
							"Algeria"=>"Algeria",
							"Antigua and Barbuda"=>"Antigua and Barbuda",
							"Argentina"=>"Argentina",
							"Armenia"=>"Armenia",
							"Australia"=>"Australia",
							"Austria"=>"Austria",
							"Azerbaijan"=>"Azerbaijan",
							"Bahamas"=>"Bahamas",
							"Bahrain"=>"Bahrain",
							"Bangladesh"=>"Bangladesh",
							"Barbados"=>"Barbados",
							"Belarus"=>"Belarus",
							"Belgium"=>"Belgium",
							"Belize"=>"Belize",
							"Benin"=>"Benin",
							"Bhutan"=>"Bhutan",
							"Bolivia"=>"Bolivia",
							"Bosnia and Herzegovina"=>"Bosnia and Herzegovina",
							"Botswana"=>"Botswana",
							"Brazil"=>"Brazil",
							"Brunei"=>"Brunei",
							"Bulgaria"=>"Bulgaria",
							"Burkina Faso"=>"Burkina Faso",
							"Burundi"=>"Burundi",
							"Cambodia"=>"Cambodia",
							"Cameroon"=>"Cameroon",
							"Canada"=>"Canada",
							"Cape Verde"=>"Cape Verde",
							"Central African Republic"=>"Central African Republic",
							"Chad"=>"Chad",
							"Chile"=>"Chile",
							"China"=>"China",
							"Colombi"=>"Colombi",
							"Comoros"=>"Comoros",
							"Congo (Brazzaville)"=>"Congo (Brazzaville)",
							"Congo"=>"Congo",
							"Costa Rica"=>"Costa Rica",
							"Cote d'Ivoire"=>"Cote d'Ivoire",
							"Croatia"=>"Croatia",
							"Cuba"=>"Cuba",
							"Cyprus"=>"Cyprus",
							"Czech Republic"=>"Czech Republic",
							"Denmark"=>"Denmark",
							"Djibouti"=>"Djibouti",
							"Dominica"=>"Dominica",
							"Dominican Republic"=>"Dominican Republic",
							"East Timor (Timor Timur)"=>"East Timor (Timor Timur)",
							"Ecuador"=>"Ecuador",
							"Egypt"=>"Egypt",
							"El Salvador"=>"El Salvador",
							"Equatorial Guinea"=>"Equatorial Guinea",
							"Eritrea"=>"Eritrea",
							"Estonia"=>"Estonia",
							"Ethiopia"=>"Ethiopia",
							"Fiji"=>"Fiji",
							"Finland"=>"Finland",
							"France"=>"France",
							"Gabon"=>"Gabon",
							"Gambia, The"=>"Gambia, The",
							"Georgia"=>"Georgia",
							"Germany"=>"Germany",
							"Ghana"=>"Ghana",
							"Greece"=>"Greece",
							"Grenada"=>"Grenada",
							"Guatemala"=>"Guatemala",
							"Guinea"=>"Guinea",
							"Guinea-Bissau"=>"Guinea-Bissau",
							"Guyana"=>"Guyana",
							"Haiti"=>"Haiti",
							"Honduras"=>"Honduras",
							"Hungary"=>"Hungary",
							"Iceland"=>"Iceland",
							"India"=>"India",
							"Indonesia"=>"Indonesia",
							"Iran"=>"Iran",
							"Iraq"=>"Iraq",
							"Ireland"=>"Ireland",
							"Israel"=>"Israel",
							"Italy"=>"Italy",
							"Jamaica"=>"Jamaica",
							"Japan"=>"Japan",
							"Jordan"=>"Jordan",
							"Kazakhstan"=>"Kazakhstan",
							"Kenya"=>"Kenya",
							"Kiribati"=>"Kiribati",
							"Korea, North"=>"Korea, North",
							"Korea, South"=>"Korea, South",
							"Kuwait"=>"Kuwait",
							"Kyrgyzstan"=>"Kyrgyzstan",
							"Laos"=>"Laos",
							"Latvia"=>"Latvia",
							"Lebanon"=>"Lebanon",
							"Lesotho"=>"Lesotho",
							"Liberia"=>"Liberia",
							"Libya"=>"Libya",
							"Liechtenstein"=>"Liechtenstein",
							"Lithuania"=>"Lithuania",
							"Luxembourg"=>"Luxembourg",
							"Macedonia"=>"Macedonia",
							"Madagascar"=>"Madagascar",
							"Malawi"=>"Malawi",
							"Malaysia"=>"Malaysia",
							"Maldives"=>"Maldives",
							"Mali"=>"Mali",
							"Malta"=>"Malta",
							"Marshall Islands"=>"Marshall Islands",
							"Mauritania"=>"Mauritania",
							"Mauritius"=>"Mauritius",
							"Mexico"=>"Mexico",
							"Micronesia"=>"Micronesia",
							"Moldova"=>"Moldova",
							"Monaco"=>"Monaco",
							"Mongolia"=>"Mongolia",
							"Morocco"=>"Morocco",
							"Mozambique"=>"Mozambique",
							"Myanmar"=>"Myanmar",
							"Namibia"=>"Namibia",
							"Nauru"=>"Nauru",
							"Nepa"=>"Nepa",
							"Netherlands"=>"Netherlands",
							"New Zealand"=>"New Zealand",
							"Nicaragua"=>"Nicaragua",
							"Niger"=>"Niger",
							"Nigeria"=>"Nigeria",
							"Norway"=>"Norway",
							"Oman"=>"Oman",
							"Pakistan"=>"Pakistan",
							"Palau"=>"Palau",
							"Panama"=>"Panama",
							"Papua New Guinea"=>"Papua New Guinea",
							"Paraguay"=>"Paraguay",
							"Peru"=>"Peru",
							"Philippines"=>"Philippines",
							"Poland"=>"Poland",
							"Portugal"=>"Portugal",
							"Qatar"=>"Qatar",
							"Romania"=>"Romania",
							"Russia"=>"Russia",
							"Rwanda"=>"Rwanda",
							"Saint Kitts and Nevis"=>"Saint Kitts and Nevis",
							"Saint Lucia"=>"Saint Lucia",
							"Saint Vincent"=>"Saint Vincent",
							"Samoa"=>"Samoa",
							"San Marino"=>"San Marino",
							"Sao Tome and Principe"=>"Sao Tome and Principe",
							"Saudi Arabia"=>"Saudi Arabia",
							"Senegal"=>"Senegal",
							"Serbia and Montenegro"=>"Serbia and Montenegro",
							"Seychelles"=>"Seychelles",
							"Sierra Leone"=>"Sierra Leone",
							"Singapore"=>"Singapore",
							"Slovakia"=>"Slovakia",
							"Slovenia"=>"Slovenia",
							"Solomon Islands"=>"Solomon Islands",
							"Somalia"=>"Somalia",
							"South Africa"=>"South Africa",
							"Spain"=>"Spain",
							"Sri Lanka"=>"Sri Lanka",
							"Sudan"=>"Sudan",
							"Suriname"=>"Suriname",
							"Swaziland"=>"Swaziland",
							"Sweden"=>"Sweden",
							"Switzerland"=>"Switzerland",
							"Syria"=>"Syria",
							"Taiwan"=>"Taiwan",
							"Tajikistan"=>"Tajikistan",
							"Tanzania"=>"Tanzania",
							"Thailand"=>"Thailand",
							"Togo"=>"Togo",
							"Tonga"=>"Tonga",
							"Trinidad and Tobago"=>"Trinidad and Tobago",
							"Tunisia"=>"Tunisia",
							"Turkey"=>"Turkey",
							"Turkmenistan"=>"Turkmenistan",
							"Tuvalu"=>"Tuvalu",
							"Uganda"=>"Uganda",
							"Ukraine"=>"Ukraine",
							"United Arab Emirates"=>"United Arab Emirates",
							"United Kingdom"=>"United Kingdom",
							"United States"=>"United States",
							"Uruguay"=>"Uruguay",
							"Uzbekistan"=>"Uzbekistan",
							"Vanuatu"=>"Vanuatu",
							"Vatican City"=>"Vatican City",
							"Venezuela"=>"Venezuela",
							"Vietnam"=>"Vietnam",
							"Yemen"=>"Yemen",
							"Zambia"=>"Zambia",
							"Zimbabwe"=>"Zimbabwe"
                           ];
					
					$x = 1;
					foreach ($jl as $key => $value) {
	                     if (isset($_POST['details'])) {
                           $selected = ($value === Input::get('country')) ? ' selected="selected"' : '';
						  } else {
                           $selected = ($value === $country) ? ' selected="selected"' : '';
						  }
					  echo $opt .= '<option value = "' . $value . '" '.$selected.'>' . $key . '</option>';
					  unset($opt); 
					  $x++;
					} ?>
					 </select>
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
				    <label><?php echo $lang['job']; ?> <?php echo $lang['type']; ?></label>
					<div class="radio">
					  <label><input type="radio" name="job_type" checked="checked" value="Fixed Price"><?php echo $lang['fixed_price']; ?></label>
					</div>								    
                  </div> 
                  
                  <div class="form-group">	
				    <label><?php echo $lang['budget']; ?></label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                    <input type="text" name="budget" class="form-control" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('budget')); 
						  } else {
						  echo escape($job_budget); 
						  }
					  ?>"/>
                   </div>
                  </div>

				  <div class="form-group">
                   <label for="dtp_input1"><?php echo $lang['start']; ?> <?php echo $lang['date']; ?></label>
                    <div class="input-group date form_datetime_start" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                    <input name="start_date" class="form-control" type="text" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('start_date')); 
						  } else {
						  echo escape($job_start_date); 
						  }
					  ?>" readonly>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
					<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                    </div>
				   <input type="hidden" id="dtp_input1" value="" /><br/>
		           <input name="mirror_field_start" type="hidden" id="mirror_field_start" class="form-control" readonly />
		           <input name="mirror_field_start_date" type="hidden" id="mirror_field_start_date" class="form-control" readonly />
                  </div>       
                  
				  <div class="form-group">
                   <label for="dtp_input1"><?php echo $lang['estimated']; ?> <?php echo $lang['end']; ?> <?php echo $lang['date']; ?></label>
                    <div class="input-group date form_datetime_start" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                    <input name="end_date" class="form-control" type="text" value="<?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('end_date')); 
						  } else {
						  echo escape($job_end_date); 
						  }
					  ?>" readonly>
                    <span class="input-group-addon"><i class="glyphicon glyphicon-remove"></i></span>
					<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                    </div>
				   <input type="hidden" id="dtp_input1" value="" /><br/>
		           <input name="mirror_field_start" type="hidden" id="mirror_field_start" class="form-control" readonly />
		           <input name="mirror_field_start_date" type="hidden" id="mirror_field_start_date" class="form-control" readonly />
                  </div> 
                  
                  
				  <div class="form-group">	
				   <div class="input-group">
					<span class="input-group-addon"><i class="fa fa-pencil-square"></i></span>
				   <select class="selectpicker form-control" name="skills_name[]" type="text" title="Choose one of the following..." data-live-search="true" data-width="30%" data-selected-text-format="count > 3" multiple="multiple">
					 <?php
					 
					$query = DB::getInstance()->get("skills", "*", ["ORDER" => "name ASC"]);
					if ($query->count()) {
					 foreach($query->results() as $row) {
					 	$names[] = $row->name;
					 }			
					}	
					
					foreach($names as $key=>$name){
						if(in_array($name,$arr)){
					   echo $skills .= '<option value = "'.$name.'" data-tokens="'.$name.'" selected="selected">'.$name.'</option>';
					  unset($skills);
						}else{
					   echo $skills .= '<option value = "'.$name.'" data-tokens="'.$name.'" >'.$name.'</option>';
					  unset($skills);
					  }
					  unset($name);
					}	
							
					 ?>	
					</select>
				   </div>
				  </div>                        
                  
                  <div class="form-group">	
				    <label><?php echo $lang['description']; ?></label>
                      <textarea type="text" id="summernote" name="description" class="form-control"><?php
                         if (isset($_POST['details'])) {
							 echo escape(Input::get('description')); 
						  } else {
						  echo escape($job_description); 
						  }
					  ?></textarea>
                  </div>
                  
                  <div class="form-group">	
				    <label><?php echo $lang['make']; ?> <?php echo $lang['job']; ?> <?php echo $lang['public']; ?></label>
				    <div class="checkbox">
				    <?php if($public === "1"){ ?>	
					  <label><input type="hidden" name="make_public" value="0"></label>
					  <label><input type="checkbox" name="make_public" value="1" checked><?php echo $lang['make_public']; ?></label>
					<?php } else { ?> 
					  <label><input type="hidden" name="make_public" value="0"></label>
					  <label><input type="checkbox" name="make_public" value="1"><?php echo $lang['make_public']; ?></label>
					<?php } ?> 
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
    <!-- Datetime Picker -->
    <script src="../assets/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script type="text/javascript">
     $('.form_datetime_start').datetimepicker({
        //language:  'fr',
        showToday: false,                 
        useCurrent: false,
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1, 
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
        pickTime: false, 
        minView: 2,      
        pickerPosition: "bottom-left",
        linkField: "mirror_field_start",
        linkFormat: "hh:ii",
        linkFieldd: "mirror_field_start_date",
        linkFormatt: "dd MM yyyy"
    });
   </script>
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
    <!-- Bootstrap Select JS-->
    <script src="../assets/js/bootstrap-select.js"></script>
</body>
</html>
