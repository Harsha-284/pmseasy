<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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
		$mail->SMTPDebug = 0;								// Enable verbose debug output
		//$mail->isSMTP();									// Set mailer to use SMTP
		$mail->Host = 'smtp.hostinger.com';			// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;								// Enable SMTP authentication
		$mail->SMTPSecure = 'tls';							// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;	
		$mail->DKIM_domain = 'pmseasy.in';
		
		
			$mail->Username = "highfive@pmseasy.in";  // SMTP username
			$mail->Password = "india@2012AWS#2008"; // SMTP password
			$mail->setFrom('highfive@pmseasy.in');
			$mail->FromName = 'pmseasy';

		 ////  Property name entered in property setup
		
		$to_arr = explode(",",$to45);
		$cc_arr	= explode(",",$cc45);
		$bcc_arr= explode(",",$bcc45);
		
		//$mail->AddCC($propertyEmail);  /////// Email entered in company Profile table

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
		
		// echo "hello";
		// exit;
			if($mail->send())
				return true;
			else
				return false;
		
	}
	catch(Exception $e)
	{
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
		return false;
	}
}

function myemail5($to45,$subj45,$data45,$cc45,$bcc45,$from45,$attachment="")
{
	global $send_emails,$default_email_from;
	$mail = new PHPMailer(true);
	try
	{
		//Server settings
		$mail->SMTPDebug = 0;								// Enable verbose debug output
		//$mail->isSMTP();									// Set mailer to use SMTP
		$mail->Host = 'smtp.hostinger.com';			// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;								// Enable SMTP authentication
		$mail->SMTPSecure = 'SSL';							// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;	
		$mail->DKIM_domain = 'pmseasy.in';
		
		
			$mail->Username = "reservations@pmseasy.in";  // SMTP username
			$mail->Password = "India@2012AWS#2008"; // SMTP password
			$mail->setFrom('reservations@pmseasy.in');
			$mail->FromName = 'pmseasy';


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
		
		if($attachment!="")
		{

				$mail->addStringAttachment($attm, 'Booking voucher.pdf');

		}
		
		//Content
		$mail->isHTML(true);										// Set email format to HTML
		$mail->Subject = $subj45;
		$mail->Body    = $data45;
		$mail->AltBody = '';
		
		// echo "hello";
		// exit;
			if($mail->send())
				return true;
			else
				return false;
		
	}
	catch(Exception $e)
	{
		error_log('Message could not be sent.');
		error_log('Mailer Error: ' . $mail->ErrorInfo);
		return false;
	}
}
?>