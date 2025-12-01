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
	if(isset($_POST['adults']))
	{
		if(isset($_POST['bulk']))
			$conn->query("update roomtypes set adults=$_POST[adults],children=$_POST[children],extraallowed=$_POST[extra],chargeperchild=$_POST[rateperchild],chargeperextra= $_POST[rateperextra],bulkextra=$_POST[bulk],bulkextracharges=$_POST[chargesforbulkextra] where id=$id");
		else
			$conn->query("update roomtypes set adults=$_POST[adults],children=$_POST[children],extraallowed=$_POST[extra],chargeperchild=$_POST[rateperchild],chargeperextra=$_POST[rateperextra] where id=$id");

		$msg = "Room occupancy details updated";
		$clrclass = "success";?>
		
		<script type="text/javascript">
			window.parent.location="admin.php?Pg=roomdetails";
		</script>
		<?php
	}
	$row = execute("select r.id,r.adults,r.children,r.extraallowed,r.bulkextra,r.roomtype,h.allowbulkbooking,r.chargeperchild,r.chargeperextra,r.bulkextracharges from roomtypes r join hotels h on h.id=r.hotel where r.id=$id");?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box" style="padding-bottom:0px; margin-bottom: 0px;">
				<?php alertbox($clrclass,$msg);?>
				<h4 class="small-text">ROOM OCCUPANCY OF '<?=strtoupper($row['roomtype'])?>'</h4>
				<div class="row">
					<form method="post" action="?id=<?=$id?>">
						<div class="col-xs-12">
							<div class="form-group">
								<label>Maximum no .of adults (base occupancy) allowed</label>
								<select name="adults" class="form-control" title="Maximum number of adults allowed">
									<option <?=selected($row['adults'],1)?> value="1">1</option>
									<option <?=selected($row['adults'],2)?> value="2">2</option>
									<option <?=selected($row['adults'],3)?> value="3">3</option>
									<option <?=selected($row['adults'],4)?> value="4">4</option>
									<option <?=selected($row['adults'],5)?> value="5">5</option>
									<option <?=selected($row['adults'],6)?> value="6">6</option>
								</select>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label>Maximum no of children (upto age 12 years) allowed</label>
								<select name="children" class="form-control" title="Maximum number of children allowed">
									<option <?=selected($row['children'],0)?> value="0">0</option>
									<option <?=selected($row['children'],1)?> value="1">1</option>
									<option <?=selected($row['children'],2)?> value="2">2</option>
								</select>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label>Rate per child (upto age 12 years) (&#8377;)</label>
								<input type="number" name="rateperchild" class="form-control" title="Rate per child (upto age 12 years)" min="0" value="<?=$row['chargeperchild']?>" required>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label>No. of extra people allowed (anyone above 12 yrs.)</label>
								<select name="extra" class="form-control" title="Maximum number of children allowed">
									<option <?=selected($row['extraallowed'],0)?> value="0">0</option>
									<option <?=selected($row['extraallowed'],1)?> value="1">1</option>
									<option <?=selected($row['extraallowed'],2)?> value="2">2</option>
									<option <?=selected($row['extraallowed'],3)?> value="3">3</option>
								</select>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label>Rate per extra person (&#8377;)</label>
								<input type="number" name="rateperextra" class="form-control" title="Rate per extra person" min="0" value="<?=$row['chargeperextra']?>" required>
							</div>
						</div>
						<?php if($row['allowbulkbooking'])
						{?>
							<div class="col-xs-12">
								<div class="form-group">
									<label>No. of guests allowed for bulk booking apart from base occupancy</label>
									<select name="bulk" class="form-control" title="Maximum number of guests allowed in bulk booking">
										<option <?=selected($row['bulkextra'],1)?> value="1">1</option>
										<option <?=selected($row['bulkextra'],2)?> value="2">2</option>
										<option <?=selected($row['bulkextra'],3)?> value="3">3</option>
										<option <?=selected($row['bulkextra'],4)?> value="4">4</option>
										<option <?=selected($row['bulkextra'],5)?> value="5">5</option>
										<option <?=selected($row['bulkextra'],6)?> value="6">6</option>
										<option <?=selected($row['bulkextra'],7)?> value="7">7</option>
										<option <?=selected($row['bulkextra'],8)?> value="8">8</option>
										<option <?=selected($row['bulkextra'],9)?> value="9">9</option>
										<option <?=selected($row['bulkextra'],10)?> value="10">10</option>
									</select>
								</div>
							</div>
							<div class="col-xs-12">
								<div class="form-group">
									<label>Charges per extra person during bulk booking (&#8377;)</label>
									<input type="number" name="chargesforbulkextra" class="form-control" title="Rate per extra person during bulk booking" min="0" value="<?=$row['bulkextracharges']?>" required>
								</div>
							</div>
							<?php
						}?>
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