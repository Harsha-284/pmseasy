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
	if(isset($_POST['data']))
	{
		$result = $conn->query("select id from hourly_facilities");
		for($i=2; $i<=10; $i++)
		{
			if(!empty($_POST['facilities'.$i]))
			{
				if(in_array(1,$_POST['facilities'.$i]))
					$parking = 1;
				else
					$parking = 0;
				
				if(in_array(2,$_POST['facilities'.$i]))
					$lobbywifi = 1;
				else
					$lobbywifi = 0;
				
				if(in_array(3,$_POST['facilities'.$i]))
					$roomwifi = 1;
				else
					$roomwifi = 0;
				
				if(in_array(4,$_POST['facilities'.$i]))
					$gym = 1;
				else
					$gym = 0;
				
				if(in_array(5,$_POST['facilities'.$i]))
					$swimmingpool = 1;
				else
					$swimmingpool = 0;
				
				if(in_array(6,$_POST['facilities'.$i]))
					$welcomedrink = 1;
				else
					$welcomedrink = 0;
				
				if(in_array(7,$_POST['facilities'.$i]))
					$breakfast = 1;
				else
					$breakfast = 0;
				
				if(in_array(8,$_POST['facilities'.$i]))
					$airportshuttle = 1;
				else
					$airportshuttle = 0;
				
				if(in_array(9,$_POST['facilities'.$i]))
					$cloakroom = 1;
				else
					$cloakroom = 0;
			}
			else
			{
				$parking		= 0;
				$lobbywifi		= 0;
				$roomwifi		= 0;
				$gym			= 0;
				$swimmingpool	= 0;
				$welcomedrink	= 0;
				$breakfast		= 0;
				$airportshuttle = 0;
				$cloakroom		= 0;
			}
			
			$cmd = execute("select count(*)cnt from roomtype_hourly_facilities where roomtype=$id and hours=$i");
			
			if($cmd['cnt'])
			{
				$conn->query("update roomtype_hourly_facilities set parking=$parking,lobbywifi=$lobbywifi,roomwifi=$roomwifi, gym=$gym, swimmingpool=$swimmingpool, welcomedrink=$welcomedrink, breakfast=$breakfast, airportshuttle=$airportshuttle, cloakroom=$cloakroom where roomtype=$id and hours=$i");
			}
			else
			{
				$conn->query("insert into roomtype_hourly_facilities (roomtype, hours, parking, lobbywifi, roomwifi, gym, swimmingpool, welcomedrink, breakfast, airportshuttle, cloakroom) values ($id, $i, $parking, $lobbywifi, $roomwifi, $gym, $swimmingpool, $welcomedrink, $breakfast, $airportshuttle, $cloakroom)");
			}
		}
		
		$clrclass = "success";
		$msg = "Hourly facilities saved succesfully";
	}?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px">
			<div class="the-box" style="padding-bottom:0px; margin-bottom:0px">
				<?php alertbox($clrclass,$msg);
				$row = execute("select roomtype from roomtypes where id=$id");?>
				<h4 class="small-text">SET HOURLY AMENITIES OF '<?=strtoupper($row['roomtype'])?>'</h4>
				<div class="row">
					<form method="post" action="?id=<?=$id?>&cntr=<?=$cntr?>">
						<div class="col-xs-2">
							<div class="form-group">
								<label>Hours</label>
							</div>
						</div>
						<div class="col-xs-10">
							<div class="form-group">
								<label>Amenities</label>
							</div>
						</div>
						<?php $result = $conn->query("select hrs from hours");
						while($row = $result->fetch_assoc())
						{
							$cmd = execute("select parking,lobbywifi,roomwifi,gym,swimmingpool,welcomedrink,breakfast,airportshuttle,cloakroom from roomtype_hourly_facilities where roomtype=$id and hours=$row[hrs]");?>

							<div class="col-xs-1">
								<div class="form-control">
									<center><label><?=$row['hrs']?></label></center>
								</div>
							</div>
							<div class="col-xs-11">
								<div class="form-group">
									<select name="facilities<?=$row['hrs']?>[]" style="width:650px" class="form-control chosen-select" multiple>
										<?php $rs = $conn->query("select hf.id,hf.facility from hourly_facilities hf");
										while($rs_row = $rs->fetch_assoc())
										{
											if($rs_row['id']==1 and $cmd['parking'])
												$selected = "selected";
											else if($rs_row['id']==2 and $cmd['lobbywifi'])
												$selected = "selected";
											else if($rs_row['id']==3 and $cmd['roomwifi'])
												$selected = "selected";
											else if($rs_row['id']==4 and $cmd['gym'])
												$selected = "selected";
											else if($rs_row['id']==5 and $cmd['swimmingpool'])
												$selected = "selected";
											else if($rs_row['id']==6 and $cmd['welcomedrink'])
												$selected = "selected";
											else if($rs_row['id']==7 and $cmd['breakfast'])
												$selected = "selected";
											else if($rs_row['id']==8 and $cmd['airportshuttle'])
												$selected = "selected";
											else if($rs_row['id']==9 and $cmd['cloakroom'])
												$selected = "selected";
											else
												$selected = "";?>
											<option value="<?=$rs_row['id']?>" <?=$selected?>><?=$rs_row['facility']?></option>
											<?php
										}
										mysqli_data_seek($rs,0);?>
									</select>
								</div>
							</div>
							<?php
						}?>
						<div class="col-xs-12">
							<div class="form-group">
								<input type="hidden" name="data" value="1">
								<input type="submit" class="btn btn-primary" value="Save">
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