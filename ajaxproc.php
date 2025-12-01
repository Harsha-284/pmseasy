<?php include 'conn.php';
include 'udf.php';

if(isset($_GET['Pg']))
{
	if($_GET['Pg']=="toggleroomswitch" and col("id")!="")
	{
		$row = execute("select active from roomtypes where id=$_GET[id]");
		if($row['active']==0)
		{
			$conn->query("update roomtypes set active=1 where id=$_GET[id]");
			//$checked = 1;
		}
		else
		{
			$conn->query("update roomtypes set active=0 where id=$_GET[id]");
			//$checked = 0;
		}
		//echo $checked;
	}
	else if($_GET['Pg']=="toggleswitch" and col("flag")!="")
	{
		$flag = $_GET['flag'];
		$row = execute("select $flag from hotels where id=$_SESSION[hotel]");
		if($row[$flag]==0)
		{
			$conn->query("update hotels set $flag=1 where id=$_SESSION[hotel]");
			//$checked = 1;
		}
		else
		{
			$conn->query("update hotels set $flag=0 where id=$_SESSION[hotel]");
			//$checked = 0;
		}
		//echo $checked;
	}
	else if($_GET['Pg']=="getstates" and col("id")!="")
	{
		$str = "<option value=''>Select State</option>";
		$result = $conn->query("select id,state from states where country=".col("id"));
		while($row=$result->fetch_assoc())
			$str .= "<option value='$row[id]'>$row[state]</option>";
		echo $str;
	}
	else if($_GET['Pg']=="getcities" and col("id")!="")
	{
		$str = "<option value=''>Select City</option>";
		$result = $conn->query("select id,city from cities where state=".col("id"));
		while($row=$result->fetch_assoc())
			$str .= "<option value='$row[id]'>$row[city]</option>";
		echo $str;
	}
	else if($_GET['Pg']=="getlocations" and col("id")!="")
	{
		$str = "<option value=''>Select Locality</option><option value='0'>Add new location</option>";
		$result = $conn->query("select id,location from locations where city=".col("id")." order by location");
		while($row=$result->fetch_assoc())
			$str .= "<option value='$row[id]'>$row[location]</option>";
		echo $str;
	}
	else if($_GET['Pg']=="save_roomtype_name")
	{
		$roomtype_id	= str_replace('&quot;','',$_POST['roomtype_id']);
		$roomtype_name	= str_replace('&quot;','',$_POST['roomtype_name']);
		
		$conn->query("update roomtypes set roomtype='$roomtype_name' where id=$roomtype_id");
				
		echo $roomtype_name." <a href='javascript:void(0)' style='color:#808080' onClick='update_roomtype_name(".$roomtype_id.",\"".$roomtype_name."\")'  title='Edit'> <i class='fa fa-pencil'></i></a>";
	}
	else if($_GET['Pg']=="save_fulldaytariff")
	{
		$roomtype_id	= str_replace('&quot;','',$_POST['roomtype_id']);
		$fullday_tariff	= str_replace('&quot;','',$_POST['fullday_tariff']);
		
		$conn->query("update roomtypes set fulldaytariff='$fullday_tariff' where id=$roomtype_id");
		
		echo $fullday_tariff." <a href='javascript:void(0)' style='color:#808080' onClick='update_fullday_tariff(".$roomtype_id.",".$fullday_tariff.")'  title='Edit'> <i class='fa fa-pencil'></i></a>";
	}
	else if($_GET['Pg']=="save_rate_ondate")
	{
		$roomtype_id	= str_replace('&quot;','',$_POST['roomtype_id']);
		$fullday_tariff	= str_replace('&quot;','',$_POST['fullday_tariff']);
		$rdate			= str_replace('&quot;','',$_POST['rdate']);
		
		$row = execute("select count(*)cnt from special_rates where roomtype=$roomtype_id and rdate='$rdate'");
		if(!$row['cnt'])
			$conn->query("insert into special_rates (roomtype,rdate,rate) values ($roomtype_id,'$rdate',$fullday_tariff)");
		else
			$conn->query("update special_rates set rate=$fullday_tariff where roomtype=$roomtype_id and rdate='$rdate'");
		
		echo "<a href='javascript:void(0)' onClick='update_rate_ondate(\"$rdate\",$fullday_tariff,$roomtype_id)'>$fullday_tariff</a>";
	}
	else if($_GET['Pg']=="checkmailavailability")
	{
		$email	= $_GET['email'];
		$id		= $_GET['id'];

		$row = execute("select count(*)cnt from users where id<>$id and email='$email'");

		if($row['cnt']==0)
			echo "1";
		else
			echo "0";
	}
}?>