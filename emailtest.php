<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'udf.php';


/////////////// You may change your mail ID here ///////////////
if(col("to")!="" and col("password")=="abc123xyz")
{
	if(myemail(col("to"),col("subject"),col("message"),col("cc"),col("bcc"),"highfive@pmseasy.in"))
		echo '<script type="text/javascript">alert("Email sent successfully")</script>';
	else
		echo '<script type="text/javascript">alert("Email was not sent")</script>';
}?>
<style type="text/css">
	input{width: 250px; height: 30px; padding: 5px; float:left}
	select{width: 250px; height: 30px; padding: 5px; float:left}
	.class1{width: 68px; float: left;}
</style>
<div style="font-size:16px; margin-bottom:5px; font-weight:bold">EMAIL TEST</div>
<form method="post" action="">
	<div style="width:320px; float:left">
		<div class="class1">To</div><input type="email" name="to" value="<?=col("to")?>" required>
		<div class="class1">Subject</div><input type="text" name="subject" value="<?=col("subject")?>" required>
		<div class="class1">From</div>
		<div class="class1">Cc</div><input type="text" name="cc" value="<?=col("cc")?>">
		<div class="class1">Bcc</div><input type="text" name="bcc" value="<?=col("bcc")?>">
		<div class="class1">Message</div><textarea name="message" cols="33" rows="3" required><?=col("message")?></textarea>
		<div class="class1">Password</div><input type="text" name="password" value="<?=col("password")?>">
		<input type="submit" value="Send" style="width:320px">
	</div>
</form>