<?php include 'conn.php';
include 'udf.php';

if(!isset($_SESSION['groupid']))
{?>	
	<script type="text/javascript">
		window.parent.location="login.php";
	</script>
	<?php
}
else if(page_access("roomdetails") and content_access("seasonalrates",$id))
{?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box" style="padding-bottom:20px; overflow:hidden">
				<?php if(isset($_POST['fromdate']))
				{
					$conn->query("update seasonalrates set title='$_POST[title]',fromdate='".dateindia($_POST['fromdate'])."',todate='".dateindia($_POST['todate'])."' where id=$id");
					
					//$result = $conn->query("select id from seasonalfrotelratedetails where rateid=$id");

					foreach($_POST['roomtypes'] as $key => $roomtype)
					{
						for($i=2; $i<=10; $i++)
						{
							$day	= $_POST['day'.$i][$key];
							$night	= $_POST['night'.$i][$key];
							$active	= col("activehr".$i,'0');
							
							$row = execute("select count(*)cnt from seasonalrates sr join seasonalfrotelratedetails sfrd on sfrd.rateid=sr.id where sr.id=$id and sfrd.roomtype=$roomtype and sfrd.hours=$i");
							if($row['cnt']>0)
								$conn->query("update seasonalfrotelratedetails set day=$day,night=$night,active=$active where rateid=$id and roomtype=$roomtype and hours=$i");
							else
								$conn->query("insert into seasonalfrotelratedetails (rateid,roomtype,hours,day,night,active) values ($id,$roomtype,$i,$day,$night,$active)");
						}
					}
					
					$clrclass = "success";
					$msg = "Seasonal room rates updated";?>

					<script type="text/javascript">
						window.parent.document.getElementById("td1id<?=$cntr?>").innerHTML = "<?=popdate(dateindia($_POST['fromdate']))?>";
						window.parent.document.getElementById("td2id<?=$cntr?>").innerHTML = "<?=popdate(dateindia($_POST['todate']))?>";
						//setTimeout('parent.$.fancybox.close();', 1200);
					</script>
					<?php
				}
				
				$row = execute("select id,title,fromdate,todate from seasonalrates where id=$id");?>
				<h4 class="small-text">EDIT SEASONAL ROOM RATES</h4>
				<?php alertbox($clrclass,$msg);?>
				<form method="post" action="?id=<?=$id?>&cntr=<?=$cntr?>">
					<div class="row">
						<div class="col-xs-4">
							<div class="form-group">
								<label>Title</label>
								<input type="text" class="form-control" name="title" value="<?=$row["title"]?>">
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<label>From Date</label>
								<input type="text" class="form-control" id="datepicker1" data-date-format="dd-mm-yyyy" name="fromdate" value="<?=popdate($row["fromdate"],"dmY")?>">
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<label>To Date</label>
								<input type="text" class="form-control" id="datepicker2" data-date-format="dd-mm-yyyy" name="todate" value="<?=popdate($row["todate"],"dmY")?>">
							</div>
						</div>
					</div>
					<div style="width:100%; float:left; background-color:#FFC000; border-style:solid; border-color:#ACB5BE; border-width:1px 0px 0px 1px; font-weight:bold; font-size:12px; margin:0px -3px;">
						<div style="width:19%; height:60px; float:left; border-style:solid; border-color:#ACB5BE; border-width:0px 1px 1px 0px; display:table; text-align:center;">
							<span class="txtblock">Room Type</span>
						</div>
						<?php $result = $conn->query("select hours,active from seasonalfrotelratedetails where rateid=$id group by hours,active order by hours");
						while($row=$result->fetch_assoc())
						{?>
							<div class="hrstitlerow">
								<div class="hrsnumber">
									<span class="txtblock"><input type="checkbox" class="normalfrotelhours" name="activehr<?=$row['hours']?>" <?=checked($row['active'],1)?> value="1"><?=$row['hours']?> hrs.</span>
								</div>
								<div class="datnightrow">
									<div class="daytime">
										<span class="txtblock">Day</span>
									</div>
									<div class="daytime">
										<span class="txtblock">Night</span>
									</div>
								</div>
							</div>
							<?php
						}?>
					</div>
					<?php $result = $conn->query("select A.id,A.roomtype,A.hr as hours,srd.day,srd.night from (select hrs.hr,r.id,r.roomtype from (select 2 as hr union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9 union all select 10)hrs,roomtypes r where r.hotel=$_SESSION[hotel])A left join seasonalfrotelratedetails srd on (A.id=srd.roomtype and srd.rateid=$id and A.hr=srd.hours) order by A.id,srd.hours,A.hr");
					$roomtype = 0;
					while($row=$result->fetch_assoc())
					{?>
						<div class="datarow">
							<div class="roomtypedata" style="text-align:left; padding:5px;">
								<span class="txtblock"><?=$row['roomtype']?></span>
								<input type="hidden" name="roomtypes[]" value="<?=$row['id']?>">
							</div>
							<?php if($roomtype!=$row['id'])
							{
								$roomtype=$row['id'];?>
								<div class="hrsdatarow">
									<input type="text" class="dnrates" name="day<?=$row['hours']?>[]" required value="<?=$row['day']?>">
									<input type="text" class="dnrates" name="night<?=$row['hours']?>[]" required value="<?=$row['night']?>">
								</div>
								<?php
							}
							for($i=3; $i<=10; $i++)
							{
								$row=$result->fetch_assoc();?>
								<div class="hrsdatarow">
									<input type="text" class="dnrates" name="day<?=$row['hours']?>[]" required value="<?=$row['day']?>">
									<input type="text" class="dnrates" name="night<?=$row['hours']?>[]" required value="<?=$row['night']?>">
								</div>
								<?php
							}?>
						</div>
						<?php
					}?>
					<div class="row">
						<div class="col-xs-12">
							<br><input type="submit" class="btn btn-primary" value="Save">
						</div>
					</div>
				</form>
			</div>
		</body>
		<?php include("js.php");?>
	</html>
	<?php
}
ob_end_flush();?>