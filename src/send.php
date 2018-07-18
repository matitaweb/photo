
<?php 

	/*
	Parse http request, store request in a json file and send mail
	to photo manager and a reminder to the sender.
	
	*/
	
	$config_ini_array = parse_ini_file("..". DIRECTORY_SEPARATOR ."config.ini");
	
	// parsing data from form
	$formdata = array(
	      'name'=> $_POST['name'],
	      'email'=>$_POST['email'],
	      'imagePathList'=> $_POST['imagePathList'],
	      'insertDate'=>date("Y-m-d H:i:s"),
	      'status'=>1,
	      'error'=>''
	   );
	 
	
	// send to photo manager
	$to = $config_ini_array['receiver_email'];
	$email_subject = "Request from: ". $_POST['name'];
	$email_body = "You have received a new message from photo selection.\n\n";
	$headers = "From: " . $_POST['email'] . "\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
	$headers .= "Reply-To: ".$_POST['email'];
	
	try {
		if(mail($to,$email_subject,$email_body,$headers)){
			$formdata['status']=0;
	    	$formdata['error'].='Error sending mail 1';	
		}
	} catch (Exception $e) {
		$formdata['status']=0;
	    $formdata['error'].='Caught exception: ' .  $e->getMessage();
	}
	
	// send recap to mail client
	$headers = "From: " . $config_ini_array['receiver_email'] . "\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
	$headers .= "Reply-To: " . $config_ini_array['receiver_email'];
	$email_body = "You have received a new message from your website contact form.\n\n";
	$email_subject = "Request  photo to : " . $config_ini_array["$config_ini_array"] . " for ". $_POST['name'];
	try {
		if(!mail($_POST['email'],$email_subject,$email_body,$headers)){
			$formdata['status']=0;
	    	$formdata['error'].='Error sending mail 2';
		}
	} catch (Exception $e) {
		$formdata['status']=0;
	    $formdata['error'].='Caught exception: ' .  $e->getMessage();
	}
	
	// write data request to file (log request)
	$jsondata = json_encode($formdata, JSON_PRETTY_PRINT);
	$request_dir_name = 'requests';
	$fileName = strtolower (preg_replace('/[[:^print:]]/', '', str_replace(" ", "_", $formdata['name']))).'_'.date("Y-m-d_His").'.json';
	$filePath = '..'. DIRECTORY_SEPARATOR . $request_dir_name. DIRECTORY_SEPARATOR .$fileName;
	$handle = fopen($filePath, 'w');
	if ( !$handle ) {
        $formdata['status']=0;
	    $formdata['error'].='Cannot open file:  '.$filePath;
	    echo $jsondata;
	    exit(0);
    }  
      
	try {
	   fwrite($handle, $jsondata);
	   fclose($handle);
	} catch (Exception $e) {
		$formdata['status']=0;
	    $formdata['error']='Caught exception: ' .  $e->getMessage();
	    echo $jsondata;
	    exit(0);
	}	  
	echo $jsondata;
?>