<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'src/PHPMailer.php';
require_once 'src/SMTP.php';
require_once 'src/Exception.php';

function myemail($to45,$subj45,$data45,$cc45,$bcc45,$from45,$attachment="")
{
	global $send_emails,$default_email_from;
	$mail = new PHPMailer(true);
	try
	{
		//Server settings
		$mail->SMTPDebug = 2;									// Enable verbose debug output
		//$mail->isSMTP();										// Set mailer to use SMTP
		$mail->Host = 'smtp.banqueteasy.com';					// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;									// Enable SMTP authentication
		//$mail->SMTPSecure = 'tls';							// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 25;	
		$mail->DKIM_domain = 'banqueteasy.com';
		
		if($from45 == "bookings@banqueteasy.com")
		{
			$mail->Username = "bookings@banqueteasy.com";  // SMTP username
			$mail->Password = "india@2012AWS#2008"; // SMTP password
			$mail->setFrom('bookings@banqueteasy.com');
		}
		else if($from45 == "highfive@banqueteasy.com")
		{
			$mail->Username = "highfive@banqueteasy.com";  // SMTP username
			$mail->Password = "india@2012AWS#2008"; // SMTP password
			$mail->setFrom('highfive@banqueteasy.com');
		}
		
		if(isset($_SESSION['header_banquet']) and strpos($data45,"patronage"))
		{
			$property = execute("select u.property from banquets b join users u on u.id=b.userx where b.id=$_SESSION[header_banquet]")['property'];
			$default_email_from = htmlspecialchars_decode($property);
		}

		$mail->FromName = $default_email_from;
		
		$to_arr = explode(",",$to45);
		$cc_arr	= explode(",",$cc45);
		$bcc_arr= explode(",",$bcc45);
		
		foreach($to_arr as $to)
			$mail->AddAddress($to);
		
		if($cc45!="")
		{
			foreach($cc_arr as $cc)
				$mail->AddCC(trim($cc));
		}
		
		if($bcc45!="")
		{
			foreach($bcc_arr as $bcc)
				$mail->AddBcc(trim($bcc));
		}
		
		/*
		$mail->addAddress($to45);	// Name is optional
		
		if($cc45!="")
			$mail->addCC($cc45);

		if($bcc45!="")
			$mail->addBCC($bcc45);
		*/
		
		//echo $attachment;
		
		if($attachment!="")
		{
			$attachments = explode(",",$attachment);
			
			foreach($attachments as $attm)
				$mail->addAttachment($attm);
		}
		
		//Content
		$mail->isHTML(true);										// Set email format to HTML
		$mail->Subject = $subj45;
		$mail->Body    = $data45;
		$mail->AltBody = '';
		
		if($send_emails)
		{
			if($mail->send())
				return true;
			else
				return false;
		}
		else
			return true;
	}
	catch(Exception $e)
	{
		//echo 'Message could not be sent.';
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
		return false;
	}
}?>