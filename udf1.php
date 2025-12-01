<?php /********************** SYSTEM RULES, ASSUMPTIONS & ID Mapping ***********************
USER GROUPIDs (User Types)
Superadmin(Fadmin)	0
Hoteladmin			1
Hotelsubadmin		2
Agent				3
Corporate			4
Traveler			5

FACILITY TYPE IDs
Good For						1 Note:This is not a facility
HOTEL FACILITIES
HOTEL Facilities				2
Food & Beverages				3
For Children					4
Wellness Centre / Spa			5
Activities & Sports Facility	6
Business						7
Travel							8

ROOM FACILITIES
Room Facilities					101
Amenities(Standard)				102
Amenities(Executive)			103

Good For have id = 1
Room Facilities are those which have id = 2
Hotel Facilities are those which have id > 2

PAYMENT POLICY IDs
Recocilation between Frotels.com & us within 7 days is ok with us.					1
Ok if Frotels.com pays us within 2-3 days after guest checks out					2
We want to get paid before guest arrives. After booking is done & guest arrives.	3
We allow "Pay At Hotel", in this situation reconciliation of 7 days is ok with us.	4

************************************************************************************/

date_default_timezone_set("Asia/Kolkata");

/******************************* System Variables *******************************/

$frotel_commission= 0.2;
$agent_commission = 0;

if(isset($_SESSION['groupid']))
{
	if($_SESSION['groupid'] == 4)
		$frotel_commission = $agent_commission = $frotel_commission/2;
	else if($_SESSION['groupid'] == 3)
		$agent_commission = $_SESSION['commission'];
}

$system_block_time = 12; // For timer shown on booking page(in miutes). System will automatically substract 2 minutes.

$google_playstore_link	= "https://play.google.com/store/apps/details?id=frotel.version.one";
$apple_playstore_link	= "https://play.google.com/store/apps/details?id=frotel.version.one";
$fb_link				= "https://www.facebook.com/frotels.pvt.ltd/";
$yt_link				= "https://www.youtube.com/channel/UCOKMHLgw2QB7CqFJVTvGoDg";
$tw_link				= "https://twitter.com/frotels";

/***************************** //System Variables *******************************/

function nosql($str)
{
	$str = str_replace("'","&#39",$str);/// '
	//$str = str_replace('"',"&#34",$str);/// "
	$str = str_replace("--","&#45&#45",$str);/// --
	//$str = str_replace(";","&#59",$str);/// ;
	return $str;
}

foreach($_POST as $key => $v)
{
	if(!is_array($_POST[$key]))
		$_POST[$key] = nosql(htmlspecialchars($_POST[$key]));
}

foreach($_GET as $key => $v)
{
	if(!is_array($_GET[$key]))
		$_GET[$key] = nosql(htmlspecialchars($_GET[$key]));
}

if(!isset($_GET['Pg']))
	$_GET['Pg'] = "";

/******************** VARIABLE/CONSTANT INITIALIZATION **************/
$msg = "";
$clrclass = "";

if(isset($_GET['id']))
	$id = $_GET['id'];

if(isset($_GET['cntr']))
	$cntr = $_GET['cntr'];
/***************** VARIABLE/CONSTANT INITIALIZATION ENDS ************/

class Crypter
{
  private $key = '';
  private $iv = '';
  function __construct($key,$iv){
    $this->key = $key;
    $this->iv  = $iv;
  }
  protected function getCipher(){
     $cipher = mcrypt_module_open(MCRYPT_BLOWFISH,'','cbc','');
     mcrypt_generic_init($cipher, $this->key, $this->iv);
     return $cipher;
  }
  function encrypt($string){
     $binary = mcrypt_generic($this->getCipher(),$string);
     $string = '';
     for($i = 0; $i < strlen($binary); $i++){
        $string .=  str_pad(ord($binary[$i]),3,'0',STR_PAD_LEFT);
     }
     return $string;
  }
  function decrypt($encrypted){
     //check for missing leading 0's
     $encrypted = str_pad($encrypted, ceil(strlen($encrypted) / 3) * 3,'0', STR_PAD_LEFT);
     $binary = '';
     $values = str_split($encrypted,3);
     foreach($values as $chr){
        $chr = ltrim($chr,'0');
        $binary .= chr($chr);
     }
     return mdecrypt_generic($this->getCipher(),$binary);
  }
}

class pagination
{
	var $offset_;
	var $total_pages_;
	var $row_count_;
	var $result_;
	var $row_;

	function pagination($q0001,$c0001=1,$page_size_=10)
	{
		global $conn;
		
		$this->result_ = $conn->query("select count(*)cnt from ($q0001)bq");
				
		$this->row_count_ = $this->result_->fetch_assoc();

		$this->total_pages_ = floor($this->row_count_['cnt']/$page_size_);
		
		if($this->total_pages_ < ($this->row_count_['cnt']/$page_size_)){	$this->total_pages_++;	}
		
		if($c0001 != "")
		{	
			if($c0001>$this->total_pages_)
			{	
				if($this->total_pages_ == 0)
					$this->current_page_ = 1;
				else
					$this->current_page_ = $this->total_pages_;
			}
			else{	$this->current_page_ = $c0001;	}
		}
		else
		{	$this->current_page_ = 1;	}
		
		$this->offset_ = ($this->current_page_ - 1) * $page_size_ ;
		$this->row_count_ = $this->row_count_['cnt'];
	}
}

function mypagination($row_count, $page_size, $qstring, $class_name)
{
	if($row_count > $page_size)
	{
		$ax_1 = $row_count / $page_size;
		$ax_2 = floor($ax_1);
		
		if($ax_1 == $ax_2)
			$page_count = floor($row_count / $page_size);
		else
			$page_count = floor($row_count / $page_size) + 1;
		
		if( col("cnt")!="" )
		{	
			if($page_count>=$_POST['cnt'])
			{	
				if($_POST['cnt'] < 1)
					$current_page = 1;
				else
					$current_page = $_POST['cnt'];	
			}
			else{$current_page = $page_count;}
		}
		else
		{	$current_page = 1;	}?>

		<ul class="pagination <?php echo $class_name; ?>">
			<?php if($current_page>1)
			{	echo "<li><a href='javascript:void(0)' onClick=sendf(".($current_page-1).",'".$qstring."')>&lsaquo;</a></li>";	}
			else
			{	echo "<li class='disabled'><a href='javascript:void(0)'>&lsaquo;</a></li>";	}

			if(($current_page-2)>1)
			{	
				echo "<li><a href='javascript:void(0)' onClick=sendf(1,'".$qstring."')>1</a></li>";
				if(($current_page-2)>2){	echo "<li><a href='javascript:void(0)'>...</a></li>";	}
			}
			
			if(($current_page-2)>0)
			{	echo "<li><a href='javascript:void(0)' onClick=sendf(".($current_page-2).",'".$qstring."')>".($current_page-2)."</a></li>";	}

			if(($current_page-1)>0)
			{	echo "<li><a href='javascript:void(0)' onClick=sendf(".($current_page-1).",'".$qstring."')>".($current_page-1)."</a></li>";	}

			echo "<li class='active'><a href='javascript:void(0)' onClick=sendf(".$current_page.",'".$qstring."')>".$current_page."</a></li>";

			if(($current_page+1) <= $page_count)
			{	echo "<li><a href='javascript:void(0)' onClick=sendf(".($current_page+1).",'".$qstring."')>".($current_page+1)."</a></li>";	}

			if(($current_page+2) <= $page_count)
			{	echo "<li><a href='javascript:void(0)' onClick=sendf(".($current_page+2).",'".$qstring."')>".($current_page+2)."</a></li>";	}

			if(($current_page+2) < $page_count)
			{	
				if(($current_page+3) < $page_count){	echo "<li><a href='javascript:void(0)'>...</a></li>";	}
				echo "<li><a href='javascript:void(0)' onClick=sendf(".$page_count.",'".$qstring."')>".$page_count."</a></li>";
			}
			
			if($current_page < $page_count)
			{	echo "<li><a href='javascript:void(0)' onClick=sendf(".($current_page+1).",'".$qstring."')>&rsaquo;</a></li>";	}
			else
			{	echo "<li class='disabled'><a href='javascript:void(0)'>&rsaquo;</a></li>";	}?>
		</ul>
		<script type="text/javascript">
			function sendf(pgcnt,qstring)
			{
				document.filter_form.cnt.value = pgcnt;
				document.filter_form.submit();
			}
		</script>
		<?php 
	}
}

function col($varname,$default_return='')
{
	if(isset($_POST[$varname]))
	{
		if($_POST[$varname]=='')
			return $default_return;
		else
			return $_POST[$varname];
	}
	else if(isset($_GET[$varname]))
	{
		if($_GET[$varname]=='')
			return $default_return;
		else
			return $_GET[$varname];
	}
	else
		return $default_return;
}

function alt_val($val,$altval)
{
	if($val==0 or $val=="")
		return $altval;
	else
		return $val;
}

function mysession($s)
{
	if(isset($_SESSION[$s]))
		return $_SESSION[$s];
	else
		return "";
}

function dateindia($dt)
{
	if($dt!="")
	{
		if(stripos($dt,"-")){	$temp = explode("-",$dt); }
		else if(stripos($dt,"/")){	$temp = explode("/",$dt); }
		
		if( is_array($temp) )
		{
			$newtemp = explode(' ',$temp[2]);
			if(isset($newtemp[1]))
				$indian = $newtemp[0]."-".$temp[1]."-".$temp[0]." ".$newtemp[1];
			else
				$indian = $newtemp[0]."-".$temp[1]."-".$temp[0];

			return $indian;
		}
	}
	else
		return "";
}

function pushdate($date4895,$format985)
{
	if(strpos($date4895,"-"))
		$delim = "-";
	else if(strpos($date4895,"/"))
		$delim = "/";
	else
		$delim = "";
	
	if($delim != "")
	{
		$temp658 = explode($delim,$date4895);
		
		if(strtoupper($format985) == "DMY")
		{
			$newdatestr = $temp658[2]."-".$temp658[1]."-".$temp658[0];
		}
		else if(strtoupper($format985) == "MDY")
		{
			$newdatestr = $temp658[2]."-".$temp658[0]."-".$temp658[1];
		}
		else
			$newdatestr = '';
		
		return $newdatestr;
	}
	else
		return null;
}

function popdate($date4895,$formmat265="dMY")
{
	$a695 = str_split($formmat265);
	$formatstring65425 = $a695[0]."-".$a695[1]."-".$a695[2];

	if($date4895 != "0000-00-00")
		return date_format(date_create($date4895),$formatstring65425);
	else
		return "";
}

function alertbox($class_456,$msg_795)
{
	if($class_456!="" and $msg_795!="")
	{
		  echo "<div class='alert alert-".$class_456." alert-block square fade in alert-dismissable'>
					<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
					<p>".$msg_795."</p>
				</div>";
	}
}


/***************************************** Hrushikesh ************************************************/
if(0)
{
	require_once (mailer_path);
	function myemail2($to45,$subject45,$data45,$attachment45="")
	{
		$mail = new PHPMailer();
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Port = 465;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com"; // SMTP server
		$mail->Username = "hrushikesh@aspiringwebsolutions.com"; // SMTP account username
		$mail->Password = "india@2012"; // SMTP account password
		//$mail->AddReplyTo('hrushikesh@aspiringwebsolutions.com', 'Shree Ganesh Builders');
		$mail->From = "hrushikesh@aspiringwebsolutions.com";
		$mail->FromName = "Fadmin";
		$mail->AddAddress($to45); // Receiving Mail ID, it can be either domain mail id (or ) any other mail id i.e., gmail id
		//if($actbcc=='on')
			//$mail->AddBcc("hrushikesh@aspiringwebsolutions.com");
		$mail->Subject =$subject45;
		$mail->AltBody = " ";
		$mail->WordWrap = 80;
		if($attachment45!="")
		{
			$attarray = explode("||DELIMITER||",$attachment45);
			for($i=0; $i<sizeof($attarray); $i++)
				$mail->AddAttachment($attarray[$i]);
		}
		$body = $data45;
		$mail->Body = $body;
		$mail->IsHTML(true);
		$mail->send();
	}
}
/***************************************** Hrushikesh ************************************************/

if(1)
{
	require_once (mailer_path);
	function myemail($to,$subject,$data,$cc,$bcc,$from,$attachment="")
	{
		$mail = new PHPMailer();

		$mail->IsSMTP();                                      // set mailer to use SMTP
		$mail->Host = "smtp.zoho.com";  // specify main and backup server
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->Port = 465;
		$mail->SMTPSecure = 'ssl';

		if($from == "booking@frotels.com")
		{
			$mail->Username = "booking@frotels.com";  // SMTP username
			$mail->Password = "Just@Freshen#Up$5555"; // SMTP password
			$mail->From = "booking@frotels.com";
		}
		else if($from == "noreply@frotels.com")
		{
			$mail->Username = "noreply@frotels.com";  // SMTP username
			$mail->Password = "Just@Freshen#Up$1979"; // SMTP password
			$mail->From = "noreply@frotels.com";
		}
		else if($from == "registration@frotels.com")
		{
			$mail->Username = "registration@frotels.com";  // SMTP username
			$mail->Password = "Just@Freshen#Up$5555"; // SMTP password
			$mail->From = "registration@frotels.com";
		}
		
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
}

function fn_resize($image_resource_id,$width,$height,$target_width,$target_height) 
{
	$target_layer = imagecreatetruecolor($target_width,$target_height);
	imagecopyresampled($target_layer,$image_resource_id,0,0,0,0,$target_width,$target_height, $width,$height);
	return $target_layer;
}

function shortamount($amount598)
{
	if($amount598 >= 10000000)
		return round(($amount598/10000000),2)."<span style='color:#FF4040'>Cr</sapn>";
	else if($amount598 >= 100000)
		return round(($amount598/100000),2)."<span style='color:#FF4040'>L</sapn>";
	else
		return $amount598;
}

function mysms($cellno,$smstext)
{
	if(1)
	{
	$varsms="user=justfreshenup&pass=JFU123&sender=Frotel&phone=".$cellno."&text=".urlencode($smstext)."&priority=ndnd&stype=normal";

	//echo $varsms;
		$curl=curl_init('http://bhashsms.com/api/sendmsg.php?'.$varsms);
	//$curl=curl_init('http://support.bhashsms.com/api/sendmsg.php?'.$varsms);
	  curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
	  $response=curl_exec($curl);
	  curl_close($curl);
	  //echo $response;
	}
}

function weekdayname($weekno,$nametype="long")
{
	if($nametype == "short")
	{
		if($weekno == 0)
			return "Sun";
		else if($weekno == 1)
			return "Mon";
		else if($weekno == 2)
			return "Tue";
		else if($weekno == 3)
			return "Wed";
		else if($weekno == 4)
			return "Thu";
		else if($weekno == 5)
			return "Fri";
		else if($weekno == 6)
			return "Sat";
		else
			return "Invalid week day";
	}
	else
	{
		if($weekno == 0)
			return "Sunday";
		else if($weekno == 1)
			return "Monday";
		else if($weekno == 2)
			return "Tuesday";
		else if($weekno == 3)
			return "Wednesday";
		else if($weekno == 4)
			return "Thursday";
		else if($weekno == 5)
			return "Friday";
		else if($weekno == 6)
			return "Saturday";
		else
			return "Invalid week day";
	}
}

function indianamount($amt5695)
{
	$a012365 = (string)$amt5695;
	$tx126	= explode(".",$a012365);
	
	$a921563 = str_split($tx126[0]);
	
	$ystr56 = "";
	for($i56=(sizeof($a921563)-1); $i56>=0 ; $i56--)
		$ystr56 .= $a921563[$i56];

	$a921563 = str_split($ystr56);

	$xstr5 = "";
	for($i56=0; $i56<sizeof($a921563) ; $i56++)
	{
		if(($i56==2 or $i56==4 or $i56==6 or $i56==8 or $i56==10 or $i56==12) and $i56!=(sizeof($a921563)-1))
			$xstr5 .= $a921563[$i56].",";
		else
			$xstr5 .= $a921563[$i56];
	}
	$xstrarray = str_split($xstr5);
	
	$ystr56 = "";
	for($i56=(sizeof($xstrarray)-1); $i56>=0 ; $i56--)
		$ystr56 .= $xstrarray[$i56];
	
	if(isset($tx126[1]))
		$ystr56 .= ".".$tx126[1];

	return $ystr56;
}

function modal($title665,$msg989)
{?>
	<script type="text/javascript">
		function modal_button_updater(hid)
		{	
			document.getElementById('deleteid').value = hid;
			document.getElementById('deleteid2').value = "";
			document.getElementById('verifyid').value = "";
			document.getElementById('blockid').value = "";
			document.getElementById('approvenshowid').value = "";
		}
	</script>
	<div class="modal fade" id="DangerModalColor2" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-no-shadow modal-no-border">
				<div class="modal-header bg-danger no-border">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Delete <?=$title665?></h4>
				</div>
				<div class="modal-body">
					This will delete the <?=$msg989?> permanently. Press <b>'Delete'</b> button if you want to continue. Otherwise press <b>'Close'</b> button.
				</div>
				<div class="modal-footer">
					<a href="javascript:void(0)" id="modalbuttonlink" onClick="document.filter_form.submit()"><button type="button" class="btn btn-danger">Delete</button></a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function modal2($title665,$msg989)
{?>
	<script type="text/javascript">
		function modal_button_updater2(hid)
		{	
			document.getElementById('deleteid').value = "";
			document.getElementById('deleteid2').value = hid;
			document.getElementById('verifyid').value = "";
			document.getElementById('blockid').value = "";
			document.getElementById('approvenshowid').value = "";
		}
	</script>
	<div class="modal fade" id="InfoModalColor2" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-no-shadow modal-no-border">
				<div class="modal-header bg-danger no-border">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Delete <?=$title665?></h4>
				</div>
				<div class="modal-body">
					This will delete the <?=$msg989?> permanently. Press <b>'Delete'</b> button if you want to continue. Otherwise press <b>'Close'</b> button.
				</div>
				<div class="modal-footer">
					<a href="javascript:void(0)" onClick="document.filter_form.submit()"><button type="button" class="btn btn-danger">Delete</button></a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function verifymodal($titleC,$titleS)
{?>
	<script type="text/javascript">
		function modal_button_updater3(hid)
		{	
			document.getElementById('deleteid').value = "";
			document.getElementById('deleteid2').value = "";
			document.getElementById('verifyid').value = hid;
			document.getElementById('blockid').value = "";
			document.getElementById('approvenshowid').value = "";
		}
	</script>
	<div class="modal fade" id="WarningModalColor2" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-no-shadow modal-no-border">
				<div class="modal-header bg-warning no-border">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Verify <?=$titleC?></h4>
				</div>
				<div class="modal-body">
					Verify <?=$titleS?> and allow them to access the system?<br>Press <b>'Verify'</b> button if you want to continue. Otherwise press <b>'Close'</b> button.
				</div>
				<div class="modal-footer">
					<a href="javascript:void(0)" onClick="document.filter_form.submit()"><button type="button" class="btn btn-warning">Verify</button></a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function approvenshowmodal($thingC,$thingS)
{?>
	<script type="text/javascript">
		function modal_button_updater5(hid)
		{	
			document.getElementById('deleteid').value = "";
			document.getElementById('deleteid2').value = "";
			document.getElementById('verifyid').value = "";
			document.getElementById('blockid').value = "";
			document.getElementById('approvenshowid').value = hid;
		}
	</script>
	<div class="modal fade" id="WarningModalColor2" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-no-shadow modal-no-border">
				<div class="modal-header bg-warning no-border">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Approve <?=$thingC?></h4>
				</div>
				<div class="modal-body">
					Approve this <?=$thingS?> the start showing on system?<br>Press <b>'Approve'</b> button if you want to continue. Otherwise press <b>'Close'</b> button.
				</div>
				<div class="modal-footer">
					<a href="javascript:void(0)" onClick="document.filter_form.submit()"><button type="button" class="btn btn-warning">Approve</button></a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function blockmodal($titleC,$titleS)
{?>
	<script type="text/javascript">
		function modal_button_updater4(hid)
		{	
			document.getElementById('deleteid').value = "";
			document.getElementById('deleteid2').value = "";
			document.getElementById('verifyid').value = "";
			document.getElementById('blockid').value = hid;
			document.getElementById('approvenshowid').value = "";
		}
	</script>
	<div class="modal fade" id="DangerModalColor4" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-no-shadow modal-no-border">
				<div class="modal-header bg-danger no-border">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Block <?=$titleC?></h4>
				</div>
				<div class="modal-body">
					Block <?=$titleS?> and forbid them from accessing the system?<br>Press <b>'Block'</b> button if you want to continue. Otherwise press <b>'Close'</b> button.
				</div>
				<div class="modal-footer">
					<a href="javascript:void(0)" onClick="document.filter_form.submit()"><button type="button" class="btn btn-danger">Block</button></a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function unblockmodal($titleC,$titleS)
{?>
	<script type="text/javascript">
		function modal_button_updater5(hid)
		{	
			document.getElementById('deleteid').value = "";
			document.getElementById('deleteid2').value = "";
			document.getElementById('verifyid').value = "";
			document.getElementById('blockid').value = hid;
			document.getElementById('approvenshowid').value = "";
		}
	</script>
	<div class="modal fade" id="SuccessModalColor5" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-no-shadow modal-no-border">
				<div class="modal-header bg-success no-border">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Unblock <?=$titleC?></h4>
				</div>
				<div class="modal-body">
					Unblock <?=$titleS?> and allow them to access the system?<br>Press <b>'Unlock'</b> button if you want to continue. Otherwise press <b>'Close'</b> button.
				</div>
				<div class="modal-footer">
					<a href="javascript:void(0)" onClick="document.filter_form.submit()"><button type="button" class="btn btn-success">Unblock</button></a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<?php
}

function execute($q,$printq=0)
{
	if($printq == 1)echo $q;
	global $conn;
	$result = $conn->query($q);
	if($result = $conn->query($q))
	{
		if(isset($result->num_rows))
		{
			if($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();
				return $row;
			}
			else
				return null;
		}
		else
			return null;
	}
	else
		return null;
}

function insert($q,$printq=0)
{
	if($printq == 1)echo $q;
	global $conn;
	if($conn->query($q))
		return $conn->insert_id;
	else
	{
		echo "Insert operation failed for ( ".$q." )";
		return null;
	}
}

function smart_resize_image($file,$width=125,$height = 0, $proportional = true, $output = '1.png', $delete_original = false, $use_linux_commands = false) 
{      
	if ( $height <= 0 && $width <= 0 ) return false;
    # Setting defaults and meta
    $info                         = getimagesize($file);
    $image                        = '';
    $final_width                  = 0;
    $final_height                 = 0;
    list($width_old, $height_old) = $info;
    # Calculating proportionality
    if ($proportional)
	{
      if      ($width  == 0)  $factor = $height/$height_old;
      elseif  ($height == 0)  $factor = $width/$width_old;
      else                    $factor = min( $width / $width_old, $height / $height_old );
      $final_width  = round( $width_old * $factor );
      $final_height = round( $height_old * $factor );
    }
    else
	{
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
    }
    # Loading image to memory according to type
    switch ( $info[2] )
	{
      case IMAGETYPE_GIF:   $image = imagecreatefromgif($file);   break;
      case IMAGETYPE_JPEG:  $image = imagecreatefromjpeg($file);  break;
      case IMAGETYPE_PNG:   $image = imagecreatefrompng($file);   break;
      default: return false;
    }
    
    
    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) )
	{
		$transparency = imagecolortransparent($image);
		if ($transparency >= 0)
		{
			$transparent_color  = imagecolorsforindex($image, $trnprt_indx);
			$transparency       = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			imagefill($image_resized, 0, 0, $transparency);
			imagecolortransparent($image_resized, $transparency);
		}
		elseif ($info[2] == IMAGETYPE_PNG)
		{
			imagealphablending($image_resized, false);
			$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
			imagefill($image_resized, 0, 0, $color);
			imagesavealpha($image_resized, true);
		}
    }
    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
    
    # Taking care of original, if needed
    if ( $delete_original )
	{
      if ( $use_linux_commands ) exec('rm '.$file);
      else @unlink($file);
    }
    # Preparing a method of providing result
    switch ( strtolower($output) )
	{
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }
    
    # Writing image according to type to the output destination
    switch ( $info[2] )
	{
      case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
      case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output);   break;
      case IMAGETYPE_PNG:   imagepng($image_resized, $output);    break;
      default: return false;
    }
    return true;
}

function saveimage($filefieldname,$fpath,$width,$key56= -1)
{
	if($key56 == -1)
	{
		if(getimagesize( $_FILES[$filefieldname]["tmp_name"]) )	/// To only check if its really an image
		{
			$newfilename = str_replace(" ","",rand().basename($_FILES[$filefieldname]["name"]));
			
			$file = $_FILES[$filefieldname]["tmp_name"];
			$source_properties = getimagesize($file);
			$image_type = $source_properties[2];
			$asp_ratio =  $source_properties[0]/$source_properties[1];
			
			if($width > 0)
			{
				if( $image_type == IMAGETYPE_JPEG ) 
				{
					$image_resource_id = imagecreatefromjpeg($file);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],$width,($width/$asp_ratio));
					imagejpeg($target_layer,$fpath.$newfilename);
				}
				else if( $image_type == IMAGETYPE_GIF )  
				{
					$image_resource_id = imagecreatefromgif($file);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],$width,($width/$asp_ratio));
					imagejpeg($target_layer,$fpath.$newfilename);
				}
				else if( $image_type == IMAGETYPE_PNG )
				{
					smart_resize_image($file,$width,0,true,$fpath.$newfilename);
				}
			}
			else
			{
				if( $image_type == IMAGETYPE_JPEG ) 
				{
					$image_resource_id = imagecreatefromjpeg($file);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],1200,(1200/$asp_ratio));
					imagejpeg($target_layer,$fpath."big/".$newfilename);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],400,(400/$asp_ratio));
					imagejpeg($target_layer,$fpath."medium/".$newfilename);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],100,(100/$asp_ratio));
					imagejpeg($target_layer,$fpath."small/".$newfilename);
				}
				else if( $image_type == IMAGETYPE_GIF )  
				{
					$image_resource_id = imagecreatefromgif($file);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],1200,(1200/$asp_ratio));
					imagejpeg($target_layer,$fpath."big/".$newfilename);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],400,(400/$asp_ratio));
					imagejpeg($target_layer,$fpath."medium/".$newfilename);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],100,(100/$asp_ratio));
					imagejpeg($target_layer,$fpath."small/".$newfilename);

				}
				else if( $image_type == IMAGETYPE_PNG )
				{
					smart_resize_image($file,1200,0,true,$fpath."big/".$newfilename);
					smart_resize_image($file,400,0,true,$fpath."medium/".$newfilename);
					smart_resize_image($file,100,0,true,$fpath."small/".$newfilename);
				}
			}
			return $newfilename;
		}
		else
			return null;
	}
	else
	{
		if(getimagesize( $_FILES[$filefieldname]["tmp_name"][$key56]) )	/// To only check if its really an image
		{
			$newfilename = str_replace(" ","",rand().basename($_FILES[$filefieldname]["name"][$key56]));
			
			$file = $_FILES[$filefieldname]["tmp_name"][$key56];
			$source_properties = getimagesize($file);
			$image_type = $source_properties[2];
			$asp_ratio =  $source_properties[0]/$source_properties[1];
			
			if($width > 0)
			{
				if( $image_type == IMAGETYPE_JPEG ) 
				{
					$image_resource_id = imagecreatefromjpeg($file);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],$width,($width/$asp_ratio));
					imagejpeg($target_layer,$fpath.$newfilename);
				}
				else if( $image_type == IMAGETYPE_GIF )
				{
					$image_resource_id = imagecreatefromgif($file);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],$width,($width/$asp_ratio));
					imagejpeg($target_layer,$fpath.$newfilename);
				}
				else if( $image_type == IMAGETYPE_PNG )
				{
					smart_resize_image($file,$width,0,true,$fpath.$newfilename);
				}
			}
			else
			{
				if( $image_type == IMAGETYPE_JPEG ) 
				{
					$image_resource_id = imagecreatefromjpeg($file);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],1200,(1200/$asp_ratio));
					imagejpeg($target_layer,$fpath."big/".$newfilename);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],400,(400/$asp_ratio));
					imagejpeg($target_layer,$fpath."medium/".$newfilename);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],100,(100/$asp_ratio));
					imagejpeg($target_layer,$fpath."small/".$newfilename);
				}
				else if( $image_type == IMAGETYPE_GIF )
				{
					$image_resource_id = imagecreatefromgif($file);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],1200,(1200/$asp_ratio));
					imagejpeg($target_layer,$fpath."big/".$newfilename);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],400,(400/$asp_ratio));
					imagejpeg($target_layer,$fpath."medium/".$newfilename);
					$target_layer = fn_resize($image_resource_id,$source_properties[0],$source_properties[1],100,(100/$asp_ratio));
					imagejpeg($target_layer,$fpath."small/".$newfilename);
				}
				else if( $image_type == IMAGETYPE_PNG )
				{
					smart_resize_image($file,1200,0,true,$fpath."big/".$newfilename);
					smart_resize_image($file,400,0,true,$fpath."medium/".$newfilename);
					smart_resize_image($file,100,0,true,$fpath."small/".$newfilename);
				}
			}
			return $newfilename;
		}
		else
			return null;
	}
}

function ntow($num652)
{
	$str56 = "";
	if($num652 == 0)
		$str56 = "Zero";
	else if($num652 == 1)
		$str56 = "One";
	else if($num652 == 2)
		$str56 = "Two";
	else if($num652 == 3)
		$str56 = "Three";
	else if($num652 == 4)
		$str56 = "Four";
	else if($num652 == 5)
		$str56 = "Five";
	else if($num652 == 6)
		$str56 = "Six";
	else if($num652 == 7)
		$str56 = "Seven";
	else if($num652 == 8)
		$str56 = "Eight";
	else if($num652 == 9)
		$str56 = "Nine";
	else if($num652 == 10)
		$str56 = "Ten";
	else if($num652 == 11)
		$str56 = "Eleven";
	else if($num652 == 12)
		$str56 = "Twelve";
	else if($num652 == 13)
		$str56 = "Thirteen";
	else if($num652 == 14)
		$str56 = "Fourteen";
	else if($num652 == 15)
		$str56 = "Fifteen";
	else if($num652 == 16)
		$str56 = "Sixteen";
	else if($num652 == 17)
		$str56 = "Seventeen";
	else if($num652 == 18)
		$str56 = "Eighteen";
	else if($num652 == 19)
		$str56 = "Nineteen";
	else if($num652 == 20)
		$str56 = "Twenty";
	else if($num652 == 30)
		$str56 = "Thirty";
	else if($num652 == 40)
		$str56 = "Fourty";
	else if($num652 == 50)
		$str56 = "Fifty";
	else if($num652 == 60)
		$str56 = "Sixty";
	else if($num652 == 70)
		$str56 = "Seventy";
	else if($num652 == 80)
		$str56 = "Eighty";
	else if($num652 == 90)
		$str56 = "Ninety";
	else if($num652 >= 100)
	{
		if($num652>=10000000)
		{
			$floor56 = floor($num652/10000000);
			if($floor56*10000000 == $num652)
				$str56 = ntow($floor56)." Crore";
			else
				$str56 = ntow($floor56)." Crore ".ntow($num652-$floor56*10000000);
		}
		else if($num652>=100000)
		{
			$floor56 = floor($num652/100000);
			if($floor56*100000 == $num652)
				$str56 = ntow($floor56)." Lakh";
			else
				$str56 = ntow($floor56)." Lakh ".ntow($num652-$floor56*100000);
		}
		else if($num652>=1000)
		{
			$floor56 = floor($num652/1000);
			if($floor56*1000 == $num652)
				$str56 = ntow($floor56)." Thousand";
			else
				$str56 = ntow($floor56)." Thousand ".ntow($num652-$floor56*1000);
		}
		else if($num652>=100)
		{
			$floor56 = floor($num652/100);
			if($floor56*100 == $num652)
				$str56 = ntow($floor56)." Hundred";
			else
				$str56 = ntow($floor56)." Hundred ".ntow($num652-($floor56*100));
		}
	}
	else if($num652 > 19)
	{
		if($num652 < 30)
			$str56 = "Twenty ".ntow($num652-20);
		else if($num652 < 40)
			$str56 = "Thirty ".ntow($num652-30);
		else if($num652 < 50)
			$str56 = "Fourty ".ntow($num652-40);
		else if($num652 < 60)
			$str56 = "Fifty ".ntow($num652-50);
		else if($num652 < 70)
			$str56 = "Sixty ".ntow($num652-60);
		else if($num652 < 80)
			$str56 = "Seventy ".ntow($num652-70);
		else if($num652 < 90)
			$str56 = "Eighty ".ntow($num652-80);
		else if($num652 < 100)
			$str56 = "Ninety ".ntow($num652-90);
	}

	return $str56;
}

function page_access($Pg658)
{
	if($_SESSION['groupid']==2)
	{
		$mod69 = explode(",",$_SESSION['modules']);
		$access835 = false;
		foreach($mod69 as $md69)
		{
			if($md69 == $Pg658)
			{
				$access835 = true;
				break;
			}
		}
		if($access835)
			return true;
		else
			return true;/// Temporarly made true
	}
	else if($_SESSION['groupid']==1)
		return true;
	else if($_SESSION['groupid']==0)
		return true;
}

function content_access($tablename94,$id37,$proptype="Hotel")
{
	global $conn;
	if($tablename94 == "roomnumbers")
	{
		$res_row = execute("select count(*)cnt from roomnumbers rn join roomtypes r on rn.roomtype=r.id join hotels h on r.hotel=h.id where h.id=$_SESSION[hotel] and rn.id=$id37");
		if($res_row['cnt'] == 1)
			return true;
		else
			return false;
	}
	else if($tablename94 == "roomtypes")
	{
		$res_row = execute("select count(*)cnt from roomtypes r join hotels h on r.hotel=h.id where h.id=$_SESSION[hotel] and r.id=$id37");
		if($res_row['cnt'] == 1)
			return true;
		else
			return false;
	}
	else if($tablename94 == "room_facillities")
	{
		$res_row = execute("select count(*)cnt from room_facillities rf join roomtypes r on r.id=rf.roomtype join hotels h on r.hotel=h.id where h.id=$_SESSION[hotel] and rf.id=$id37");
		if($res_row['cnt'] == 1)
			return true;
		else
			return false;
	}
	else if($tablename94 == "seasonalrates")
	{
		$res_row = execute("select count(*)cnt from seasonalrates where hotel=$_SESSION[hotel] and id=$id37");
		if($res_row['cnt'] == 1)
			return true;
		else
			return false;
	}
	else if($tablename94 == "discounts")
	{
		$res_row = execute("select count(*)cnt from discounts where hotel=$_SESSION[hotel] and id=$id37");
		if($res_row['cnt'] == 1)
			return true;
		else
			return false;
	}
	else if($tablename94 == "pictures")
	{
		if($proptype=="Hotel")
		{
			$res_row = execute("select count(*)cnt from pictures where propertyid=$_SESSION[hotel] and id=$id37");
		}
		else if($proptype=="Room")
		{
			$res_row = execute("select count(*)cnt from pictures p join roomtypes r on (r.id=p.propertyid and p.type='Room') where r.hotel=$_SESSION[hotel] and p.id=$id37");
		}
		if($res_row['cnt'] == 1)
			return true;
		else
			return false;
	}
}

function checked($val100,$val200)
{
	if($val100 == $val200)
		return "checked";
	else
		return null;
}

function selected($val100,$val200)
{
	if($val100 == $val200)
		return "selected";
	else
		return null;
}

function deletefile($delimagepath956)
{
	if(file_exists($delimagepath956))
		unlink($delimagepath956);
}

function limittext($a35)
{
	$str2365 = substr($a35, 0, 20);
	if(strlen($a35)>20)
		return "<span title='".$a35."'>".$str2365."...</span>";
	else
		return "<span title='".$a35."'>".$str2365."</span>";
}

function get_hotelid($uid)
{
	global $conn;
	$res926 = $conn->query("select id from hotels where user = $uid");
	if($res926->num_rows > 0)
	{
		$row926 = $res926->fetch_assoc();
		return $row926['id'];
	}
	else
		return 0;
}

function imageblock($title69)
{
	global $conn;
	$res625 = $conn->query("select id,title,path,approved from pictures where propertyid=$_SESSION[hotel] and type='Hotel' and title='$title69'");
	if($res625->num_rows > 0)
	{	
		$row93 = $res625->fetch_assoc();
		$path = $row93['path'];
		$picid= $row93['id'];
		
		if($row93['approved'] == 1)
			$approvalflag = "<i class='fa fa-flag' style='color:#8CC152;float:right;padding-right:2px;' title='Approved Image'></i>";
		else
			$approvalflag = "<i class='fa fa-flag' style='color:#E9573F;float:right;padding-right:2px;' title='Admin Approval Pending'></i>";

		$image = "<div style='height:129px;'>
					<a class='zooming' href='images/propertypics/hotel/big/$path' title='$title69'>
						<img src='images/propertypics/hotel/medium/$path' alt='Image' class='mfp-fade img-responsive'>
					</a>
				  </div>
				  <div style='margin: 5px 0px;'>
					<a href='javascript:void(0)' class='redlink' onClick='modal_button_updater($picid)'>
						<span class='label label-danger' data-toggle='modal' data-target='#DangerModalColor2' title='Delete this image'>Remove</span>
					</a>
					$approvalflag
				  </div>";
		$added = true;
	}
	else
	{
		$image = "<div style='height:159px;'><img src='images/NOIMAGE568555985.png' alt='Image' class='mfp-fade img-responsive' style='margin-bottom:30px;'></div>";
		$added = false;
	}
	echo $image;
	return $added;
}

function random_num($size) {
	$alpha_key = '';
	$keys = range('A', 'Z');

	for ($i = 0; $i < 2; $i++) {
		$alpha_key .= $keys[array_rand($keys)];
	}

	$length = $size - 2;

	$key = '';
	$keys = range(0, 9);

	for ($i = 0; $i < $length; $i++) {
		$key .= $keys[array_rand($keys)];
	}

	return $alpha_key . $key;
}

function cur_year($format="yyyy")
{
	if($format=="yyyy" or $format=="Y")
		return (new \DateTime())->format("Y");
	else
		return (new \DateTime())->format("y");
}

function cur_month($format="m")
{
	return (new \DateTime())->format($format);
}

function get_month_name($mn=0)
{
	if($mn >=0 and $mn <=12)
	{
		if($mn == 1)
			return "January";
		else if($mn == 2)
			return "February";
		else if($mn == 3)
			return "March";
		else if($mn == 4)
			return "April";
		else if($mn == 5)
			return "May";
		else if($mn == 6)
			return "June";
		else if($mn == 7)
			return "July";
		else if($mn == 8)
			return "August";
		else if($mn == 9)
			return "September";
		else if($mn == 10)
			return "October";
		else if($mn == 11)
			return "November";
		else if($mn == 12)
			return "December";
		else
			return cur_month("F");
	}
	else
		return "";
}

/********************************************* DATA ACCESS CONTROL ********************************************/
if(isset($_SESSION['groupid']))
{
	if(!isset($_SESSION['hotel']))
		$_SESSION['hotel'] = 0;
	
	if(col('current_hotel')!="")
	{
		if(col('current_hotel')!="0")
		{
			if($_SESSION['groupid'] == 0)
				$_SESSION['hotel'] = col('current_hotel');
			else if($_SESSION['groupid'] == 1)
			{
				$row = execute("select count(id)cnt from hotels where admin=$_SESSION[id] and id=".col('current_hotel'));
				if($row['cnt'] == 1)
					$_SESSION['hotel'] = col('current_hotel');
			}
		}
		else
			$_SESSION['hotel'] = 0;
	}
	
	if($_SESSION['groupid']==0 or $_SESSION['groupid']==1)
	{
		if($_SESSION['groupid']==0)
		{
			if(col('current_city')!="")
			{
				$_SESSION['city'] = col('current_city');
				$_SESSION['hotel'] = 0;
			}
			else if(!isset($_SESSION['city']))
				$_SESSION['city'] = 0;
			
			$current_city = "<select class='form-control chosen-select' name='current_city' style='width:220px;' onChange='form.submit()'><option value='0'>-- Select City --</option>";
			$result = $conn->query("select id,city from cities order by city");
			if($result->num_rows > 0)
			{
				while($row=$result->fetch_assoc())
					$current_city .= "<option value='".$row['id']."' ".selected($row['id'],$_SESSION['city']).">".$row['city']."</option>";
			}
			$current_city .= "</select>";
						
			$current_hotel = "<select class='form-control chosen-select' name='current_hotel' style='width:220px;' onChange='form.submit()'><option value='0'>-- Select Hotel --</option>";
			$result = $conn->query("select h.id,u.company from hotels h left join users u on u.id=h.user left join locations l on h.location=l.id where u.city=$_SESSION[city] order by u.company");
			if($result->num_rows > 0)
			{
				while($row=$result->fetch_assoc())
					$current_hotel .= "<option value='".$row['id']."' ".selected($row['id'],col("current_hotel",$_SESSION['hotel'])).">".$row['company']."</option>";
			}
			$current_hotel .= "</select>";
			$_SESSION['hotel'] = col("current_hotel",$_SESSION['hotel']);
		}
		else if(($_SESSION['groupid']==1 or $_SESSION['groupid']==0) and col("Pg")=="myhotels")
		{
			if($_SESSION['groupid']==1)
			{
				$current_hotel = "<select class='form-control chosen-select' name='current_hotel' style='width:220px;' onChange='form.submit()'><option value='0'>-- Select Hotel --</option>";
				
				$result = $conn->query("select h.id,u.company from hotels h join users u on u.id=h.user where h.admin=$_SESSION[id]");
				while($row=$result->fetch_assoc())
				{
					$current_hotel .= "<option value='".$row['id']."' ".selected($row['id'],$_SESSION['hotel']).">".$row['company']."</option>";
				}
				$current_hotel .= "</select>";
			}
			else
			{
				$current_hotel = "<select class='form-control chosen-select' name='current_hotel' style='width:220px;' onChange='form.submit()'><option value='0'>-- Select Hotel --</option>";
				
				$hadmin = execute("select h.admin from users u join hotels h on h.user=u.id where h.id=$_SESSION[hotel]");

				$result = $conn->query("select h.id,u.company from hotels h join users u on u.id=h.user where h.admin=$hadmin[admin]");
				while($row=$result->fetch_assoc())
				{
					$current_hotel .= "<option value='".$row['id']."' ".selected($row['id'],$_SESSION['hotel']).">".$row['company']."</option>";
				}
				$current_hotel .= "</select>";
				
				$current_city = "<select class='form-control chosen-select' name='current_city' style='width:220px;' onChange='form.submit()'><option value='0'>-- Select City --</option>";
				$result = $conn->query("select id,city from cities order by city");
				if($result->num_rows > 0)
				{
					while($row=$result->fetch_assoc())
						$current_city .= "<option value='".$row['id']."' ".selected($row['id'],$_SESSION['city']).">".$row['city']."</option>";
				}
				$current_city .= "</select>";
			}
		}
		else
		{
			$current_hotel = "<select class='form-control chosen-select' name='current_hotel' style='width:220px;' onChange='form.submit()'><option value='0'>-- Select Hotel --</option>";
				
			$result = $conn->query("select h.id,u.company from hotels h join users u on u.id=h.user where h.admin=$_SESSION[id]");
			while($row=$result->fetch_assoc())
			{
				$current_hotel .= "<option value='".$row['id']."'>".$row['company']."</option>";
			}
			$current_hotel .= "</select>";
		}
	}
	
	if($_SESSION['hotel']>0 and col("Pg")!="myhotels" and $_SESSION['groupid']==1)
	{
		$current_hotel = "<select class='form-control chosen-select' name='current_hotel' style='width:220px;' onChange='form.submit()'><option value='0'>-- Select Hotel --</option>";
		
		$result = $conn->query("select h.id,u.company from hotels h join users u on u.id=h.user where h.admin=$_SESSION[id]");
		while($row=$result->fetch_assoc())
		{
			$current_hotel .= "<option value='".$row['id']."' ".selected($row['id'],$_SESSION['hotel']).">".$row['company']."</option>";
		}
		$current_hotel .= "</select>";
	}
}
/****************************************** // DATA ACCESS CONTROL ********************************************/

if(col("checkindate")!="" or col("hcheckindate")!="" or col("hcheckoutdate")!="" or col("checkin_time")!="")
{
	$curdate = date_create(date("Y-m-d"));
	
	if(col("checkindate")!="" and col("place")!="")
	{
		$checkindate = date_create(dateindia($_GET['checkindate']));
		
		if($checkindate)
		{
			if(!($checkindate>=$curdate))
			{
				parse_str($_SERVER['QUERY_STRING'], $outputArray);
				$outputArray['checkindate'] = $curdate->modify("+1 days")->format("d-m-Y");

				if(strpos($_SERVER['PHP_SELF'],"listing"))
					header("Location:listing.php?".http_build_query($outputArray));
				else if(strpos($_SERVER['PHP_SELF'],"frotel.php"))
					header("Location:frotel.php?".http_build_query($outputArray));
			}
		}
		else
			header("Location:index.php");
	}
	
	if(col("hcheckindate")!="" and col("hcheckoutdate")!="")
	{
		$hcheckindate	= date_create(dateindia($_GET['hcheckindate']));
		$hcheckoutdate	= date_create(dateindia($_GET['hcheckoutdate']));
		
		if($hcheckindate and $hcheckoutdate)
		{
			if(!($hcheckindate >= $curdate))
			{
				parse_str($_SERVER['QUERY_STRING'], $outputArray);
				$outputArray['hcheckindate'] = $curdate->modify("+1 days")->format("d-m-Y");
				$outputArray['hcheckoutdate'] = $curdate->modify("+1 days")->format("d-m-Y");
				if(strpos($_SERVER['PHP_SELF'],"hotels_listing"))
					header("Location:hotels_listing.php?".http_build_query($outputArray));
				else if(strpos($_SERVER['PHP_SELF'],"hotel.php"))
					header("Location:hotel.php?".http_build_query($outputArray));
			}
			else if($hcheckindate>=$hcheckoutdate)
				header("Location:index.php");
		}
		else
			header("Location:index.php");
	}
}?>