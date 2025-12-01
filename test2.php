<?//sprintf('%03d', 1234);?>

<?php require_once ("frotels/PHPMailer_5.2.0/class.phpmailer.php");
function myemail($to,$subject,$data,$cc,$bcc,$from,$attachment="")
{
	$mail = new PHPMailer();

	$mail->IsSMTP();						// set mailer to use SMTP
	$mail->Host = "smtp.banqueteasy.com";	// specify main and backup server
	$mail->SMTPAuth = true;					// turn on SMTP authentication
	$mail->Port = 25;
	$mail->SMTPSecure = 'tls';
	
	$mail->Username = "bookings@banqueteasy.com";  // SMTP username
	$mail->Password = "india@2012AWS#2008"; // SMTP password
	$mail->From		= "bookings@banqueteasy.com";
	
	$mail->FromName = "Frotels";
	
	$to_arr = explode(",",$to);
	$cc_arr	= explode(",",$cc);
	$bcc_arr= explode(",",$bcc);
	
	foreach($to_arr as $to)
		$mail->AddAddress($to);

	if($cc!="")
	{
		foreach($cc_arr as $cc)
			$mail->AddCC($cc);
	}
	
	if($bcc!="")
	{
		foreach($bcc_arr as $bcc)
			$mail->AddBcc($bcc);
	}
	
	$mail->WordWrap = 80;                                 // set word wrap to 50 characters
	if($attachment!="")
	{
		$attarray = explode("||DELIMITER||",$attachment);
		for($i=0; $i<sizeof($attarray); $i++)
			$mail->AddAttachment($attarray[$i]);
	}
	$mail->IsHTML(true);                                  // set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $data;
	//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

	if($mail->Send())
		return true;
	else
		return false;
}


/*
if(myemail("hrushikesh@aspiringwebsolutions.com","Booking related","Emails are working!!!"))
	echo "Sent";
else
	echo "Failed";
*/

echo "A".date("d-m-Y H:i")."B";
?>