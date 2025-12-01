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
				<?php if(isset($_POST['title']))
				{
					$conn->query("update seasonalrates set title='$_POST[title]',fromdate='".dateindia($_POST['validfrom'])."',todate='".dateindia($_POST['validto'])."' where id=$id");
					$result = $conn->query("select r.id as roomtype from roomtypes r left join seasonalratedetails srd on r.id=srd.roomtype left join seasonalrates sr on (srd.rateid=sr.id and rateid=$id) where r.hotel=$_SESSION[hotel]");

					while($row = $result->fetch_assoc())
					{
						$wdrd= $_POST['fdt'.$row['roomtype']];
						$werd= $_POST['wel'.$row['roomtype']];
						$mon = $_POST['mon'.$row['roomtype']];
						$tue = $_POST['tue'.$row['roomtype']];
						$wed = $_POST['wed'.$row['roomtype']];
						$thu = $_POST['thu'.$row['roomtype']];
						$fri = $_POST['fri'.$row['roomtype']];
						$sat = $_POST['sat'.$row['roomtype']];
						$sun = $_POST['sun'.$row['roomtype']];
						
						$rs_row = execute("select count(*)cnt from seasonalrates sr join seasonalratedetails srd on sr.id=srd.rateid where sr.id=$id and srd.roomtype=$row[roomtype]");
						
						if($rs_row['cnt']>0)
							$conn->query("update seasonalratedetails set wdrd=$wdrd,werd=$werd,mon=$mon,tue=$tue,wed=$wed,thu=$thu,fri=$fri,sat=$sat,sun=$sun where roomtype=$row[roomtype] and rateid=$id");
						else
							$conn->query("insert into seasonalratedetails (rateid,roomtype,wdrd,werd,mon,tue,wed,thu,fri,sat,sun) values ($id,$row[roomtype],$wdrd,$werd,$mon,$tue,$wed,$thu,$fri,$sat,$sun)");
					}
					$clrclass = "success";
					$msg = "Seasonal room rates updated";?>
					<script type="text/javascript">
						window.parent.document.getElementById("td1id<?=$cntr?>").innerHTML = "<?=popdate(dateindia($_POST['fromdate']))?>";
						window.parent.document.getElementById("td2id<?=$cntr?>").innerHTML = "<?=popdate(dateindia($_POST['todate']))?>";
						setTimeout('parent.$.fancybox.close();', 1200);
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
								<label>Season / Title</label>
								<input type="text" name="title" class="form-control" value="<?=$row['title']?>" required>
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<label>Valid From</label>
								<input type="text" name="validfrom" data-date-format="dd-mm-yyyy" class="form-control" id="datepicker1" value="<?=popdate($row['fromdate'],"dmY")?>" readonly required>
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<label>Valid Upto</label>
								<input type="text" name="validto" data-date-format="dd-mm-yyyy" class="form-control" id="datepicker2" value="<?=popdate($row['todate'],"dmY")?>" readonly required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div style="border-style:solid; border-color:#ACB5BE; border-width:1px 0px 0px 1px;">
								<div style="height:37px;font-size:12px; font-weight:bold;">
									<div class="roomtype" style="background-color:#FFC000;">
										<span class="txtblock">Room Type</span>
									</div>
									<div class="heads">
										<span class="txtblock">Full Day Tariff</span>
									</div>
									<div class="heads">
										<span class="txtblock">Monday</span>
									</div>
									<div class="heads">
										<span class="txtblock">Tuesday</span>
									</div>
									<div class="heads">
										<span class="txtblock">Wednesday</span>
									</div>
									<div class="heads">
										<span class="txtblock">Thursday</span>
									</div>
									<div class="heads">
										<span class="txtblock">Friday</span>
									</div>
									<div class="heads">
										<span class="txtblock">Weekend Loader (%)</span>
									</div>
									<div class="heads">
										<span class="txtblock">Saturday</span>
									</div>
									<div class="heads">
										<span class="txtblock">Sunday</span>
									</div>
								</div>
								<?php $result = $conn->query("select r.id,r.roomtype,srd.hours,srd.mon,srd.tue,srd.wed,srd.thu,srd.fri,srd.sat,srd.sun,srd.wdrd,srd.werd, srd.extra from roomtypes r left join seasonalratedetails srd on srd.roomtype=r.id left join seasonalrates sr on (srd.rateid=sr.id and srd.rateid=$row[id]) where r.hotel=$_SESSION[hotel] order by r.id");

								while($row = $result->fetch_assoc())
								{?>
									<div>
										<div class="roomtype rtbg" style="border-width: 0px 1px 1px 1px;">
											<span class="txtblock"><?=$row['roomtype']?></span>
										</div>
										<div class="datafields">
											<span class="txtblock">
												<input type="text" class="ratefield2" onKeyup="copyrates(<?=$row['id']?>)" id="fdt<?=$row['id']?>" name="fdt<?=$row['id']?>" style="background-color:#D8D8D8;" value="<?=$row['wdrd']?>" autocomplete="off" required>
											</span>
										</div>
										<div class="datafields">
											<span class="txtblock">
												<input type="text" class="ratefield2" id="mon<?=$row['id']?>" onKeyup="validatehotelrates('mon<?=$row['id']?>')" name="mon<?=$row['id']?>" value="<?=$row['mon']?>" autocomplete="off" required>
											</span>
										</div>
										<div class="datafields">
											<span class="txtblock">
												<input type="text" class="ratefield2" id="tue<?=$row['id']?>" onKeyup="validatehotelrates('tue<?=$row['id']?>')" name="tue<?=$row['id']?>" value="<?=$row['tue']?>" autocomplete="off" required>
											</span>
										</div>
										<div class="datafields">
											<span class="txtblock">
												<input type="text" class="ratefield2" id="wed<?=$row['id']?>" onKeyup="validatehotelrates('wed<?=$row['id']?>')" name="wed<?=$row['id']?>" value="<?=$row['wed']?>" autocomplete="off" required>
											</span>
										</div>
										<div class="datafields">
											<span class="txtblock">
												<input type="text" class="ratefield2" id="thu<?=$row['id']?>" onKeyup="validatehotelrates('thu<?=$row['id']?>')" name="thu<?=$row['id']?>" value="<?=$row['thu']?>" autocomplete="off" required>
											</span>
										</div>
										<div class="datafields">
											<span class="txtblock">
												<input type="text" class="ratefield2" id="fri<?=$row['id']?>" onKeyup="validatehotelrates('fri<?=$row['id']?>')" name="fri<?=$row['id']?>" value="<?=$row['fri']?>" autocomplete="off" required>
											</span>
										</div>
										<div class="datafields">
											<span class="txtblock">
												<input type="text" class="ratefield2" onKeyup="copyrates(<?=$row['id']?>)" id="weekend<?=$row['id']?>" name="wel<?=$row['id']?>" style="background-color:#D8D8D8;" value="<?=$row['werd']?>" autocomplete="off" required>
											</span>
										</div>
										<div class="datafields">
											<span class="txtblock">
												<input type="text" class="ratefield2" id="sat<?=$row['id']?>" onKeyup="validatehotelrates('sat<?=$row['id']?>')" name="sat<?=$row['id']?>" value="<?=$row['sat']?>" autocomplete="off" required>
											</span>
										</div>
										<div class="datafields">
											<span class="txtblock">
												<input type="text" class="ratefield2" id="sun<?=$row['id']?>" onKeyup="validatehotelrates('sun<?=$row['id']?>')" name="sun<?=$row['id']?>" value="<?=$row['sun']?>" autocomplete="off" required>
											</span>
										</div>
									</div>
									<?php
								}?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div style="margin-top:15px; height:30px; overflow:hidden;">
								<input type="submit" class="btn btn-primary" value="Save">
							</div>
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