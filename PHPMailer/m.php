<?php 

	
	$ch=curl_init('https://banqueteasy.com/erp/PHPMailer/awsmailapi.php?toemail=shekhar@aspiringwebsolutions.com&subject=emailtest&emailbody=testmessage&fromemail=bookings@banqueteasy.com');
	$data = curl_exec($ch);
	print($data); /* result of API call*/


?>