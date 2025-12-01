<?php include 'conn.php';
include 'udf.php';

if(!isset($_SESSION['groupid']))
{?>	
	<script type="text/javascript">
		window.parent.location="login.php";
	</script>
	<?php
}
else if(page_access("roomdetails") and content_access("roomtypes",$id))
{
	if(isset($_POST['oldroomnumbers']))
	{
		$conn->query("update roomnumbers set deleted=1 where roomtype=$id");
		
		foreach($_POST['oldroomnumbersid'] as $key => $oldroomnumberid)
		{
			$oldroomnumber = $_POST['oldroomnumbers'][$key];
			if(content_access("roomnumbers",$oldroomnumberid))
				$conn->query("update roomnumbers set roomnumber='$oldroomnumber',deleted=0 where id=$oldroomnumberid");
		}
		
		$result = $conn->query("select rn.id,(case when exists(select 1 from room_distribution rd join bookings b on rd.bookingid=b.id where b.paid=1 or b.ticker>now()) then 1 else 0 end)deletable from roomnumbers rn where rn.roomtype=$id and rn.deleted=1");
		
		while($row = $result->fetch_assoc())
		{
			if($row['deletable'])
				$conn->query("delete from roomnumbers where id=$row[id]");
		}
		
		$msg = "Room numbers are set";
		$clrclass = "success";
	}
	if(isset($_POST['newroomnumbers']))
	{
		foreach($_POST['newroomnumbers'] as $newroomnumber)
		{
			if($newroomnumber!="")
				insert("insert into roomnumbers (roomtype,roomnumber) values ($id,'".nosql($newroomnumber)."')");
		}
		$msg = "Room numbers are set";
		$clrclass = "success";
	}
	
	if($msg == "Room numbers are set")
	{?>
		<script type="text/javascript">
			window.parent.location="admin.php?Pg=roomdetails";
		</script>
		<?php
	}
	
	$row = execute("select roomtype from roomtypes where id=$id");?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box" style="padding-bottom:0px;">
				<?php alertbox($clrclass,$msg);?>
				<h4 class="small-text">SET ROOM NUMBERS OF '<?=strtoupper($row['roomtype'])?>'</h4>
				<div class="row">
					<form method="post" action="?id=<?=$id?>" id="rnform">
						<?php $result = $conn->query("select id,roomnumber from roomnumbers where roomtype=$id and deleted=0");
						if($result->num_rows > 0)
						{
							$i = 0;
							while($row=$result->fetch_assoc())
							{?>
								<div id="rn<?=$i?>">
									<div class="col-xs-10">
										<div class="form-group">
											<input type="text" name="oldroomnumbers[]" class="form-control" placeholder="E.g 01" value="<?=$row['roomnumber']?>" required>
											<input type="hidden" name="oldroomnumbersid[]" value="<?=$row['id']?>">
										</div>
									</div>
									<div class="col-xs-2" style="margin-top: 7px;">
										<a href="javascript:void(0)" title="Delete this room number" onClick="deleteroom(<?=$i?>)">
											<span class="label label-danger"><i class="fa fa-times"></i></span>
										</a>
									</div>
								</div>
								<?php $i++;
							}
						}?>
						<div id="newroomnumbers"></div>
						<div class="col-xs-12">
							<div class="form-group">
								<a href="javascript:void(0)" onClick="addroomnumber()">+Add more room number(s)</a>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<input type="submit" value="Save" class="btn btn-primary">
							</div>
						</div>
					</form>
				</div>
			</div>
		</body>
		<?php include("js.php");?>
	</html>
	<?php
}
ob_end_flush();?>