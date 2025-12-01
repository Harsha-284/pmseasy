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
	if(isset($_POST['amenities']))
	{
		$conn->query("delete from room_facilities where roomtype=$id");
		foreach($_POST['amenities'] as $amenity)
			insert("insert into room_facilities (roomtype,facility) values ($id,$amenity)");

		$msg = "Room facilities are set";
		$clrclass = "success";?>
		<script type="text/javascript">
			window.parent.location="admin.php?Pg=roomdetails";
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
				$row = execute("select roomtype from roomtypes where id=$id");?>
				<h4 class="small-text">SET ROOM AMENITIES OF '<?=strtoupper($row['roomtype'])?>'</h4>
				<div class="row">
					<form method="post" action="?id=<?=$id?>&cntr=<?=$cntr?>">
						<div class="facilitylabel"><label>Room Facilities</label></div>
						<?php $result = $conn->query("select f.id,f.type,f.facility,coalesce(rf.facility,0)matched from facilities f left join room_facilities rf on (rf.facility=f.id and rf.roomtype=$id) where f.type=101");
						if($result->num_rows > 0)
						{
							while($row=$result->fetch_assoc())
							{?>
								<div class="radio-4">
									<input type="checkbox" class="form-control" name="amenities[]" value="<?=$row['id']?>" <?=checked($row['id'],$row['matched'])?>><?=$row['facility']?>
								</div>
								<?php
							}
						}?>
						<div class="facilitylabel"><label>Amenities (Standard)</label></div>
						<?php $result = $conn->query("select f.id,f.type,f.facility,coalesce(rf.facility,0)matched from facilities f left join room_facilities rf on (rf.facility=f.id and rf.roomtype=$id) where f.type=102");
						if($result->num_rows > 0)
						{
							while($row=$result->fetch_assoc())
							{?>
								<div class="radio-4">
									<input type="checkbox" class="form-control" name="amenities[]" value="<?=$row['id']?>" <?=checked($row['id'],$row['matched'])?>><?=$row['facility']?>
								</div>
								<?php
							}
						}?>
						<div class="facilitylabel"><label>Amenities (Executive)</label></div>
						<?php $result = $conn->query("select f.id,f.type,f.facility,coalesce(rf.facility,0)matched from facilities f left join room_facilities rf on (rf.facility=f.id and rf.roomtype=$id) where f.type=103");
						if($result->num_rows > 0)
						{
							while($row=$result->fetch_assoc())
							{?>
								<div class="radio-4">
									<input type="checkbox" class="form-control" name="amenities[]" value="<?=$row['id']?>" <?=checked($row['id'],$row['matched'])?>><?=$row['facility']?>
								</div>
								<?php
							}
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