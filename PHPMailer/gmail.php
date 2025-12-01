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
		$mail->SMTPDebug = 2;									// Enable verbose debug output
		//$mail->isSMTP();										// Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';							// Specify main and backup SMTP servers
		$mail->SMTPAuth = true;									// Enable SMTP authentication
		$mail->Username = 'hrushikesh@aspiringwebsolutions.com';// SMTP username
		$mail->Password = 'india@2012AWS#2008';					// SMTP password
		$mail->SMTPSecure = 'tls';								// Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;										// TCP port to connect to
		
		//Recipients
		$mail->setFrom('hrushikesh@aspiringwebsolutions.com', 'Hrushikesh');
		//$mail->addAddress('joe@example.net', 'Joe User');			// Add a recipient
		$mail->addAddress($to45);	// Name is optional
		//$mail->addReplyTo('hrushikesh@aspiringwebsolutions.com', 'Hrushikesh');
		
		if($cc45!="")
			$mail->addCC($cc45);

		if($bcc45!="")
			$mail->addBCC($bcc45);
		
		//Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');				// Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');		// Optional name
		
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