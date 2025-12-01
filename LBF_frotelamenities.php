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
	if(col('parking') or col('lobbywifi') or col('roomwifi') or col('gym') or col('swimmingpool') or col('welcomedrink') or col('breakfast') or col('airportshuttle') or col('cloakroom'))
	{
		$parking		= col('parking',0);
		$lobbywifi		= col('lobbywifi',0);
		$roomwifi		= col('roomwifi',0);
		$gym			= col('gym',0);
		$swimmingpool	= col('swimmingpool',0);
		$welcomedrink	= col('welcomedrink',0);
		$breakfast		= col('breakfast',0);
		$airportshuttle = col('airportshuttle',0);
		$cloakroom		= col('cloakroom',0);
		
		$conn->query("update hotelrates set parking=$parking,lobbywifi=$lobbywifi,roomwifi=$roomwifi,swimmingpool=$swimmingpool,welcomedrink=$welcomedrink, breakfast=$breakfast,airportshuttle=$airportshuttle,cloakroom=$cloakroom,gym=$gym where roomtype=$id and hours=$_GET[hours]");
		
		$msg = "Hourly amenities are updated";
		$clrclass = "success";?>
		<script type="text/javascript">
			//window.parent.location="admin.php?Pg=frotelnormalrates";
		</script>
		<?php
	}?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
			<style type="text/css">
				.facilitylabel{width:100%; padding-left:8px; padding-top:8px; float:left}
			</style>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box" style="padding-bottom:0px; margin-bottom:0px;">
				<?php alertbox($clrclass,$msg);
				$row = execute("select r.roomtype,hr.parking,hr.lobbywifi,hr.roomwifi,hr.swimmingpool,hr.welcomedrink,hr.breakfast,hr.airportshuttle,hr.cloakroom,hr.gym from roomtypes r join hotelrates hr on r.id=hr.roomtype where r.id=$id and hr.hours=$_GET[hours]");?>
				<h4 class="small-text">AMENITIES OF '<?=strtoupper($row['roomtype'])?>' FOR <?=$_GET['hours']?> HOURS</h4>
				<div class="row">
					<form method="post" action="?id=<?=$id?>&hours=<?=$_GET['hours']?>">
						<div class="radio-12">
							<input type="checkbox" class="form-control" name="parking" value="1" <?=checked($row['parking'],1)?>> Parking
						</div>
						<div class="radio-12">
							<input type="checkbox" class="form-control" name="lobbywifi" value="1" <?=checked($row['lobbywifi'],1)?>> Free Wifi in the lobby
						</div>
						<div class="radio-12">
							<input type="checkbox" class="form-control" name="roomwifi" value="1" <?=checked($row['roomwifi'],1)?>> Free Wifi in the room
						</div>
						<div class="radio-12">
							<input type="checkbox" class="form-control" name="gym" value="1" <?=checked($row['gym'],1)?>> Free access to gym
						</div>
						<div class="radio-12">
							<input type="checkbox" class="form-control" name="swimmingpool" value="1" <?=checked($row['swimmingpool'],1)?>> Free access to swimming pool
						</div>
						<div class="radio-12">
							<input type="checkbox" class="form-control" name="welcomedrink" value="1" <?=checked($row['welcomedrink'],1)?>> Free welcome drink
						</div>
						<div class="radio-12">
							<input type="checkbox" class="form-control" name="breakfast" value="1" <?=checked($row['breakfast'],1)?>> Free breakfast included (available only upto 10:00 AM)
						</div>
						<div class="radio-12">
							<input type="checkbox" class="form-control" name="airportshuttle" value="1" <?=checked($row['airportshuttle'],1)?>> Airport shuttle available (Free)
						</div>
						<div class="radio-12">
							<input type="checkbox" class="form-control" name="cloakroom" value="1" <?=checked($row['cloakroom'],1)?>> Free cloak room facility
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