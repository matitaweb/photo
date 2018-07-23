
<?php 

	/*
	Parse http request, store request in a json file and send mail
	to photo manager and a reminder to the sender.
	
	*/
	
	//error_reporting(E_ALL);
	
	function sendMailAruba($to, $subject, $sender, $html_msg_par){
		
		// Genera un boundary
		$mail_boundary = "=_NextPart_" . md5(uniqid(time()));
		 
		//$to = "mattia.chiarini82@gmail.com";
		//$subject = "Testing e-mail";
		//$sender = "postmaster@artedanzabologna.com";
		
		 
		$headers = "From: $sender\n";
		$headers .= "Reply-To: " . $sender."\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: multipart/alternative;\n\tboundary=\"$mail_boundary\"\n";
		$headers .= "X-Mailer: PHP " . phpversion();
		 
		// Corpi del messaggio nei due formati testo e HTML
		$text_msg = "messaggio in formato testo";
		$html_msg = "<b>messaggio</b> in formato <p><a href='http://www.aruba.it'>html</a><br><img src=\"http://hosting.aruba.it/image_top/top_01.gif\" border=\"0\"></p>";
		 
		// Costruisci il corpo del messaggio da inviare
		$msg = "This is a multi-part message in MIME format.\n\n";
		$msg .= "--$mail_boundary\n";
		$msg .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
		$msg .= "Content-Transfer-Encoding: 8bit\n\n";
		$msg .= "Questa &egrave; una e-Mail di test inviata dal servizio Hosting di Aruba.it per la verifica del corretto funzionamento di PHP mail()function .
		
		Aruba.it";  // aggiungi il messaggio in formato text
		 
		$msg .= "\n--$mail_boundary\n";
		$msg .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
		$msg .= "Content-Transfer-Encoding: 8bit\n\n";
		$msg .= "Questa &egrave; una e-Mail di test inviata dal servizio Hosting di Aruba.it per la verifica del corretto funzionamento di PHP mail()function .
		
		Aruba.it";  // aggiungi il messaggio in formato HTML
		 
		// Boundary di terminazione multipart/alternative
		$msg .= "\n--$mail_boundary--\n";
		 
		// Imposta il Return-Path (funziona solo su hosting Windows)
		//ini_set("sendmail_from", $sender);
		 
		// Invia il messaggio, il quinto parametro "-f$sender" imposta il Return-Path su hosting Linux
		return mail($to, $subject, $msg, $headers, "-f$sender");
		
	}
	
	function writeFile($filePath, $data, $formdata){
		$handle = fopen($filePath, 'w');
		if ( !$handle ) {
	        $formdata['status']=0;
		    $formdata['error'].='Cannot open file:  '.$filePath;
		    return FALSE;
	    }  
	      
		try {
		   fwrite($handle, $data);
		   fclose($handle);
		} catch (Exception $e) {
			$formdata['status']=0;
		    $formdata['error']='Caught exception: ' .  $e->getMessage();
		    return FALSE;
		}	
		return TRUE;
		
	}
	
	
	function replace_tags($template, $placeholders){
		
		foreach($placeholders as $key => $value){
    		$template = str_replace('${'.strtoupper($key).'}', $value, $template);
		}
		return $template;
	}
	
	$config_ini_array = parse_ini_file("..". DIRECTORY_SEPARATOR ."config.ini");
	$request_dir_name = 'requests';
	$template_dir_name = 'templates';
	$upper_dir = str_replace(DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR."", dirname(realpath( __FILE__ )).DIRECTORY_SEPARATOR);
	
	
	
	// parsing data from form
	$formdata = array(
	      'name'=> $_POST['name'],
	      'email'=>$_POST['email'],
	      'imagePathList'=> $_POST['imagePathList'],
	      'insertDate'=>date("Y-m-d H:i:s"),
	      'status'=>1,
	      'error'=>''
	   );

	$template_file_name_mail_body_info = $config_ini_array['template_file_name_mail_body_info'];
	$template = file_get_contents($upper_dir.$template_dir_name.DIRECTORY_SEPARATOR.$template_file_name_mail_body_info);
	
	$imagePathListHtml = "<ul>";
	foreach( $_POST['imagePathList'] as  $imagePathListEl){
    	$imagePathListHtml .= '<li>';
    	$imagePathListHtml .= '<img src="https://gallery-selectable-matitaweb.c9users.io/photo/'.$imagePathListEl['path'].'" height="60" width="60" alt="'.$imagePathListEl['imagetitle'].'" />';
    	$imagePathListHtml .= '<h3>ALBUM '.$imagePathListEl['album'].':</h3><p>FOTO: '.$imagePathListEl['imagetitle'].' </p>';
    	$imagePathListHtml .= '</li>';		
	}
	$imagePathListHtml .= "</ul>";
	
	// parsing data from form
	$placeholders = array(
	      'name'=> $_POST['name'],
	      'email'=>$_POST['email'],
	      'imagePathList'=> $_POST['imagePathList'],
	      'tot_photo'=>count($_POST['imagePathList']),
	      'insertDate'=>date("Y-m-d H:i:s"),
	      'imagePathListHtml'=> $imagePathListHtml,
	      'receivername'=>$config_ini_array["receiver_name"]
	   );
	$email_body = replace_tags($template, $placeholders);
	
	// send to photo manager
	$email_subject = replace_tags($config_ini_array["receiver_email_subject"], $placeholders);
	try {
		$filePathMail = $upper_dir . $request_dir_name. DIRECTORY_SEPARATOR .'mail.html';
		writeFile($filePathMail, $email_body, $formdata);
		if(!sendMailAruba($config_ini_array['receiver_email'], $email_subject, $_POST['email'], $email_body)){
			$formdata['status']=0;	
			$formdata['error'].='Error sending mail to' .  $config_ini_array['receiver_email'];
		}
	} catch (Exception $e) {
		$formdata['status']=0;
	    $formdata['error'].='Caught exception: ' .  $e->getMessage();
	}
	
	// send recap to mail client
	//$email_body = "You have received a new message from your website contact form.\n\n";
	$email_subject = replace_tags($config_ini_array["sender_email_subject"], $placeholders);
	try {
		if(!sendMailAruba($_POST['email'],$email_subject, $config_ini_array['receiver_email'], $email_body)){
			$formdata['status']=0;	
			$formdata['error'].='Error sending mail to' .  $_POST['email'];
		}

	} catch (Exception $e) {
		$formdata['status']=0;
	    $formdata['error'].='Caught exception: ' .  $e->getMessage();
	}
	
	// write data request to file (log request)
	$jsondata = json_encode($formdata, JSON_PRETTY_PRINT);
	
	$fileName = strtolower (preg_replace('/[[:^print:]]/', '', str_replace(" ", "_", $formdata['name']))).'_'.date("Y-m-d_His").'.json';
	$filePath = $upper_dir . $request_dir_name. DIRECTORY_SEPARATOR .$fileName;
	writeFile($filePath, $jsondata, $formdata);
	echo $jsondata;
?>