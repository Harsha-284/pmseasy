<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'src/PHPMailer.php';
require_once 'src/SMTP.php';
require_once 'src/Exception.php';

// echo ini_get('display_errors');

// if (!ini_get('display_errors')) {
//     ini_set('display_errors', '1');
// }

// echo ini_get('display_errors');

function awsmailapi($to45,$subj45,$data45,$cc45,$bcc45,$from45,$fromname,$attachment="")
{
	
	$mail = new PHPMailer(true);
	
	// 	//Server settings
	// 	$mail->SMTPDebug = 1;									// Enable verbose debug output
	// 	//$mail->isSMTP();										// Set mailer to use SMTP
	// 	$mail->Host = 'smtp.banqueteasy.com';					// Specify main and backup SMTP servers
	// 	$mail->SMTPAuth = true;									// Enable SMTP authentication
	// 	//$mail->SMTPSecure = 'tls';							// Enable TLS encryption, `ssl` also accepted
	// 	$mail->Port = 25;

	// 	$mail->Username = "bookings@banqueteasy.com";  // SMTP username
	// 	$mail->Password = "india@2012AWS#2008"; // SMTP password
	// 	$mail->setFrom('bookings@banqueteasy.com');
		


	// 	$mail->FromName = "bookings@banqueteasy.com";

		
	// 	$cc	= "";
	// 	$bcc= "";

	// 	$mail->AddAddress($to45);
		
	// 	// $mail->AddCC(trim($cc));

	// 	// $mail->AddBcc(trim($bcc));


	// 	if($attachment!="")
	// 		$mail->addAttachment($attachment);
		
	// 	//Content
	// 	$mail->isHTML(true);										// Set email format to HTML
	// 	$mail->Subject = $subj45;
	// 	$mail->Body    = $data45;
	// 	$mail->AltBody = '';
		
	// $mail->send();


	//Server settings
	$mail->SMTPDebug = 0;									// Enable verbose debug output
	//$mail->isSMTP();										// Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';							// Specify main and backup SMTP servers
	$mail->SMTPAuth = true;									// Enable SMTP authentication
	$mail->Username = 'general.noreplay.contact@gmail.com';		// SMTP username
	$mail->Password = 'india@2012AWS#2008';					// SMTP password
	$mail->SMTPSecure = 'tls';								// Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;										// TCP port to connect to
	
	if($fromname=="")
		$mail->setFrom('general.noreplay.contact@gmail.com', 'Cars-us-web');
	else
		$mail->setFrom('general.noreplay.contact@gmail.com', $fromname);

	//$mail->addAddress('joe@example.net', 'Joe User');			// Add a recipient
	$mail->addAddress($to45);	// Name is optional
	//$mail->addReplyTo('hrushikesh@aspiringwebsolutions.com', 'Hrushikesh');
	
	$cc	= "";
	$bcc= "";
	
	//Attachments
	//$mail->addAttachment('/var/tmp/file.tar.gz');				// Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');		// Optional name
	
	//Content
	$mail->isHTML(true);										// Set email format to HTML
	$mail->Subject = $subj45;
	$mail->Body    = $data45;
	$mail->AltBody = '';
	
	$mail->send();


		// echo $to45."<br>".$from45."<br>".$subj45."<br>".$data45."<br>";
		
}



// -------------------------------------------------

$toemail 		= $_GET["toemail"];
$fromemail		= $_GET["fromemail"];
$subject 		= $_GET["subject"];
$emailbody 		= $_GET["emailbody"];


awsmailapi($toemail, $subject, $emailbody,'','',$fromemail,$_GET["fromname"]);


echo date("Y-m-d h:i:sa");
// echo $objDateTime = new DateTime('NOW');

?>