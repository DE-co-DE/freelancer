<?php
if(!file_exists('core/frontinit.php')){
	header('Location: install/');        
    exit;
}else{
 require_once 'core/frontinit.php';	
}
//Register Function

if (Input::exists()) {
    
 if(Token::check(Input::get('token'))){
   
    $errorHandler = new ErrorHandler;
	
	$validator = new Validator($errorHandler);
	
	$validation = $validator->check($_POST, [
	  'name' => [
		 'required' => true,
		 'minlength' => 2,
		 'maxlength' => 50
	   ],
	  'email' => [
	     'required' => true,
	     'email' => true,
	     'maxlength' => 100,
	     'minlength' => 2,
	     'unique' => 'freelancer',
	     'unique' => 'client'
	  ],			 
	  'username' => [
	     'required' => true,
	     'maxlength' => 20,
	     'minlength' => 3,
	     'unique' => 'freelancer',
	     'unique' => 'client'
	  ],
	   'password' => [
	     'required' => true,
	     'minlength' => 6
	   ],
	   'confirmPassword' => [
	     'match' => 'password'
	   ]
	]);
   
	  if (!$validation->fails()) {
       
        if(empty(Input::get('otp'))){
          
           
           $otp= $otp=generateOtp();
           if($otp!=''){
               echo 'otp sent';
           }
           exit;
        }else{
            print_r('$_SESSION');
    exit;
            $resp=check_otp(Input::get('otp'));
            if($resp==false){
                echo '<div class="alert alert-danger fade in">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Error!</strong> OTP MISMATCHED<br/>
               </div>';
               exit;
            }
        }
	      if (Input::get('user_type') == 0) {
		        $client = new Client();
		  
				$remember = (Input::get('remember') === 'on') ? true : false;
				$salt = Hash::salt(32);  
				$imagelocation = 'uploads/default.png';
                $clientid = uniqueid(); 
                $otp=generateOtp($clientid,'Client');
				try{
					
				  $client->create(array(
				   'clientid' => $clientid,
				   'username' => Input::get('username'),
				   'password' => Hash::make(Input::get('password'), $salt),
				   'salt' => $salt,
				   'name' => Input::get('name'),
		           'email' => Input::get('email'),
				   'imagelocation' => $imagelocation,
		           'joined' => date('Y-m-d H:i:s'),
				   'active' => 1,
                   'user_type' => 1,
                   'otp' =>$otp,
                   'otp_verified'=>1
				  ));	
				  
				if ($client) {
                    $login = $client->login(Input::get('email'), Input::get('password'), $remember);
                    Redirect::to('Client/');
			    }else {
			     $hasError = true;
			   }
					
				}catch(Exception $e){
				 die($e->getMessage());	
				}				      	
	          
	      } else {
			if($membershipid != ''){
			    
			    $freelancer = new Freelancer();
		  
				$remember = (Input::get('remember') === 'on') ? true : false;
				$salt = Hash::salt(32);  
				$imagelocation = 'uploads/default.png';
				$bgimage = 'uploads/bg/default.jpg';
                $freelancerid = uniqueid(); 
                $otp=generateOtp($freelancerid,'Freelancer');
				try{
					
				  $freelancer->create(array(
				   'freelancerid' => $freelancerid,
				   'username' => Input::get('username'),
				   'password' => Hash::make(Input::get('password'), $salt),
				   'salt' => $salt,
				   'name' => Input::get('name'),
		           'email' => Input::get('email'),
				   'imagelocation' => $imagelocation,
				   'bgimage' => $bgimage,
		           'membershipid' => $membershipid,
		           'membership_bids' => $bids,
		           'membership_date' => date('Y-m-d H:i:s'),
		           'joined' => date('Y-m-d H:i:s'),
				   'active' => 1,
		           'user_type' => 1,
                   'otp' =>$otp,
                   'otp_verified'=>1
				  ));	
				  
				if ($freelancer) {
                    $login = $freelancer->login(Input::get('email'), Input::get('password'), $remember);
				 Redirect::to('Freelancer/');
			    }else {
			     $hasError = true;
			   }
					
				}catch(Exception $e){
				 die($e->getMessage());	
				}	
	          } else {
				  $memError = true;
				}
	      }
       
		
	  } else {
      
	     $error = '';
	     foreach ($validation->errors()->all() as $err) {
          
	     	$str = implode(" ",$err);
	     	$error .= '
		           <div class="alert alert-danger fade in">
		            <a href="#" class="close" data-dismiss="alert">&times;</a>
		            <strong>Error!</strong> '.$str.'<br/>
			       </div>
			       ';
         }
         echo  $error;
         exit;
		 
      }

 }	  
  	
}
function generateOtp(){
    $rand=rand(1000,9999);
    
   return Session::put('otp',$rand);
    
     }

     function check_otp($otp){
        
         $s_otp= $_SESSION['otp'];
         if($s_otp==$otp){
                return true;
         }else{
             return false;
         }
       
         }