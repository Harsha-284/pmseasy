<?php

	global $send_emails;

	$send_emails = true;

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require_once 'src/PHPMailer.php';
	require_once 'src/SMTP.php';
	require_once 'src/Exception.php';

	function banqueteasy_email($userid="",$to,$cc="",$bcc="",$from,$subject,$data,$attachment="")
	{
		global $send_emails;
		$mail = new PHPMailer(true);
		try
		{
			//Server settings
			$mail->SMTPDebug = 1;									// Enable verbose debug output
			//$mail->isSMTP();										// Set mailer to use SMTP
			$mail->Host = 'smtp.banqueteasy.com';					// Specify main and backup SMTP servers
			$mail->SMTPAuth = true;									// Enable SMTP authentication
			//$mail->SMTPSecure = 'tls';							// Enable TLS encryption, `ssl` also accepted
			$mail->Port = 25;
			
			if($from == "bookings@banqueteasy.com")
			{
				$mail->Username = "bookings@banqueteasy.com";  		// SMTP username
				$mail->Password = "india@2012AWS#2008"; 			// SMTP password
				$mail->setFrom('bookings@banqueteasy.com');
			}
			else if($from == "highfive@banqueteasy.com")
			{
				$mail->Username = "highfive@banqueteasy.com";  		// SMTP username
				$mail->Password = "india@2012AWS#2008"; 			// SMTP password
				$mail->setFrom('highfive@banqueteasy.com');
			}
			
			$mail->FromName = "BanquetEasy";
			
			$to_arr = explode(",",$to);
			$cc_arr	= explode(",",$cc);
			$bcc_arr= explode(",",$bcc);
			
			foreach($to_arr as $to)
				$mail->AddAddress($to);
			
			if($cc!="")
			{
				foreach($cc_arr as $cc)
					$mail->AddCC(trim($cc));
			}
			
			if($bcc!="")
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
				$mail->addAttachment($attachment);
			
			//Content
			$mail->isHTML(true);										// Set email format to HTML
			$mail->Subject = $subject;
			$mail->Body    = $data;
			$mail->AltBody = '';
			
			if($send_emails)
			{
				echo $date = date('d-m-Y H:i:s')."<br>";
				$mail->send();
				echo $date = date('d-m-Y H:i:s');
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
	}


	banqueteasy_email('','swapnil@aspiringwebsolutions.com','','','highfive@banqueteasy.com','Highfive BanquetEasy','Test message');