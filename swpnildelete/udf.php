<?php date_default_timezone_set("Asia/Kolkata");
function no_sql_inject($str)
{
	$str = str_replace("'","&#39",$str);/// '
	$str = str_replace('"',"&#34",$str);/// ;
	$str = str_replace("--","&#45&#45",$str);/// --
	$str = str_replace(";","&#59",$str);/// ;
	return $str;
}

function gop($varname)
{
	if(isset($_POST[$varname]))
		return $_POST[$varname];
	else if(isset($_GET[$varname]))
		return $_GET[$varname];
	else
		return "";
}

function dateindia($dt)
{
	if($dt!="" and !is_null($dt))
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
		
		if( isset($_GET['cnt']) )
		{	
			if($page_count>=$_GET['cnt'])
			{	
				if($_GET['cnt'] < 1)
					$current_page = 1;
				else
					$current_page = $_GET['cnt'];	
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
				document.filter_form.action = qstring+"&cnt="+pgcnt;
				document.filter_form.submit();
			}
		</script>
		<?php 
	}
}

function alertbox($class_456,$msg_795)
{
	echo "<div class='alert alert-".$class_456." alert-block square fade in alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<p>".$msg_795."</p>
	</div>";
}

function alertbox2($msg_795)
{
	echo "<div style='background-color: #bb983a !important;border-color: #aa882a !important;' class='alert alert-success alert-block square fade in alert-dismissable'>
		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
		<p>".$msg_795."</p>
	</div>";
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
		$this->conn_ = new mysqli(db_host,db_user,db_password,db_database);
		
		$this->result_ = $this->conn_->query("select count(*)cnt from ($q0001)bq");
				
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

function myemail2($to45,$subject45,$data45,$attachment45="")
{
	$mail = new PHPMailer();

	$mail->IsSMTP();                                      // set mailer to use SMTP
	$mail->Host = "smtp.gmail.com";  // specify main and backup server
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = "contact@shreeganeshbuilders.com";  // SMTP username
	$mail->Password = "sgblifestyle"; // SMTP password

	$mail->From = "contact@shreeganeshbuilders.com";
	$mail->FromName = "Shree Ganesh Builders";
	$mail->AddAddress($to45);
	
	$mail->WordWrap = 80;                                 // set word wrap to 50 characters
	if($attachment45!="")
	{
		$attarray = explode("||DELIMITER||",$attachment45);
		for($i=0; $i<sizeof($attarray); $i++)
			$mail->AddAttachment($attarray[$i]);
	}
	$mail->IsHTML(true);                                  // set email format to HTML

	$mail->Subject = $subject45;
	$mail->Body    = $data45;
	//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

	$mail->Send();
}

function myemail($to45,$subject45,$data45,$attachment45="")
{
	$mail = new PHPMailer();
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Port = 25;
	//$mail->SMTPSecure = 'ssl';
	$mail->Host = "us2.smtp.mailhostbox.com"; // SMTP server
	$mail->Username = "info@imperiallifestyle.in"; // SMTP account username
	$mail->Password = "sgblifestyle4500"; // SMTP account password
	$mail->AddReplyTo('info@imperiallifestyle.in', 'Imperial Lifestyle Pvt. Ltd.');
	$mail->From = "info@imperiallifestyle.in";
	$mail->FromName = "Imperial Lifestyle Pvt. Ltd.";
	$mail->AddAddress($to45); // Receiving Mail ID, it can be either domain mail id (or ) any other mail id i.e., gmail id
	$mail->AddCC('info@imperiallifestyle.in');
	//if($actbcc=='on')
		//$mail->AddBcc("hrushikesh@aspiringwebsolutions.com");
	$mail->Subject =$subject45;
	$mail->AltBody =" ";
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

function mysms($recepient659,$message563)
{
	$ch				= curl_init();
	$user			= "sgbilpl";
	$receipientno	= $recepient659;
	$senderID		= "SGBILP";
	$msgtxt			= $message563;

	curl_setopt($ch, CURLOPT_URL, "http://bhashsms.com/api/sendmsg.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "user=sgbilpl&pass=123456&sender=SGBILP&phone=".$receipientno."&text=".$msgtxt."&priority=ndnd&stype=normal");

	$buffer = curl_exec($ch);
	//if(empty ($buffer))	echo " buffer is empty "; else echo $buffer;
	curl_close($ch);
}

function smart_resize_image($file,$width= 125, $height = 0, $proportional = true, $output = '1.png', $delete_original = false, $use_linux_commands = false ) 
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

function saveimage($filefieldname,$fpath,$width)
{
	if(getimagesize( $_FILES[$filefieldname]["tmp_name"]) )	/// To only check if its really an image
	{
		$newfilename = str_replace(" ","",rand().basename($_FILES[$filefieldname]["name"]));
		
		$file = $_FILES[$filefieldname]["tmp_name"];
		$source_properties = getimagesize($file);
		$image_type = $source_properties[2];
		$asp_ratio =  $source_properties[0]/$source_properties[1];
		
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
		return $newfilename;
	}
	else
		return null;
}

function execute($q,$printq=0)
{
	if($printq == 1)echo $q;
	global $conn;
	$result = $conn->query($q);
	if($result = $conn->query($q))
	{
		$row = $result->fetch_assoc();
		return $row;
	}
	else
		return null;
}

function deletefile($delimagepath956)
{
	if(file_exists($delimagepath956))
		unlink($delimagepath956);
}
?>