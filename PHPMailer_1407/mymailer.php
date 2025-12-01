<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'src/PHPMailer.php';
require_once 'src/SMTP.php';
require_once 'src/Exception.php';

function myemail($to45,$subj45,$data45,$cc45,$bcc45,$from45)
{//$subj45 = 'fkdfdfdkjdkjfdjfd';
	$mail = new PHPMailer(true);
	try
	{
		//Server settings
		$mail->SMTPDebug = 0;									// Enable verbose debug output
		//$mail->isSMTP();										// Set mailer to use SMTP
		$mail->Host = 'smtp.zoho.com';							// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;									// Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';								// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 465;		
		
		if($from45 == "booking@frotels.com")
		{
			$mail->Username = "booking@frotels.com";  // SMTP username
			$mail->Password = "Just@Freshen#Up$5555"; // SMTP password
			$mail->setFrom('booking@frotels.com');
			//$mail->AddReplyTo('booking@frotels.com','Frotels');
		}
		else if($from45 == "noreply@frotels.com")
		{
			$mail->Username = "noreply@frotels.com";  // SMTP username
			$mail->Password = "Just@Freshen#Up$1979"; // SMTP password
			$mail->setFrom('noreply@frotels.com');
			//$mail->AddReplyTo('noreply@frotels.com','Frotels');
		}
		else if($from45 == "registration@frotels.com")
		{
			$mail->Username = "registration@frotels.com";  // SMTP username
			$mail->Password = "Just@Freshen#Up$5555"; // SMTP password
			$mail->setFrom('registration@frotels.com');
			//$mail->AddReplyTo('registration@frotels.com','Frotels');
		}
		
		$mail->FromName = "Frotels";
		
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

		//Content
		$mail->isHTML(true);										// Set email format to HTML
		$mail->Subject = $subj45;
		$mail->Body    = $data45;
		$mail->AltBody = '';
		
		if($mail->send())
			return true;
		else
			return false;
	}
	catch(Exception $e)
	{
		//echo 'Message could not be sent.';
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
		return false;
	}
}
?>