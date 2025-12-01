<?php include 'conn.php';
include 'udf.php';

if(!isset($_SESSION['groupid']))
{?>	
	<script type="text/javascript">
		window.parent.location="login.php";
	</script>
	<?php
}
else if(1)
{?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box" style="padding-bottom:12px;">
				<form name="filter_form" method="POST" action="?id=<?=$id?>&cntr=<?=$cntr?>">
					<input type="hidden" name="deleteid" id="deleteid">
					<input type="hidden" name="deleteid2" id="deleteid2">
					<input type="hidden" name="verifyid" id="verifyid">
					<input type="hidden" name="blockid" id="blockid">
					<input type="hidden" name="approvenshowid" id="approvenshowid">
					<input type="hidden" name="cnt" id="cnt" value="<?=col("cnt")?>">
				</form>
				<div class="magnific-popup-wrap">
					<?php if(col("approvenshowid") != "")
					{
						$conn->query("update pictures set approved=1 where id=$_POST[approvenshowid]");
						alertbox("success","Image has been approved");?>
						<script type="text/javascript">
							window.parent.document.getElementById("td1id<?=$cntr?>").innerHTML = parseInt(window.parent.document.getElementById("td1id<?=$cntr?>").innerHTML)-1;
						</script>
						<?php
					}
					approvenshowmodal("Image","image");
					$pending= 0;
					$result	= $conn->query("select p.id,p.path,p.title from hotels h join pictures p on (p.propertyid=h.id and p.type='Hotel' and p.approved=0) where h.id=$id");
					if($result->num_rows > 0)
					{?>
						<h4 class="small-title">Hotel Pictures</h4>
						<div class="row">
							<?php while($row = $result->fetch_assoc())
							{?>
								<div class="col-xs-4">
									<div style="height:129px">
										<a class="zooming" href="images/propertypics/hotel/big/<?=$row['path']?>" title="<?=$row['title']?>">
											<img src="images/propertypics/hotel/medium/<?=$row['path']?>" alt="Image" class="mfp-fade img-responsive">
										</a>
									</div>
									<div style="margin:5px 0px">
										<a href="javascript:void(0)" class="redlink" onClick="modal_button_updater5(<?=$row['id']?>)">
											<span class="label label-warning" data-toggle="modal" data-target="#WarningModalColor2" title="Delete this image">Approve</span>
										</a>
									</div>
								</div>
								<?php
							}?>
						</div>
						<?php $pending++;
					}
					$result = $conn->query("select p.id,p.path,p.title from hotels h join roomtypes r on r.hotel=h.id join pictures p on (p.propertyid=r.id and p.type='Room' and p.approved=0) where h.id=$id");
					if($result->num_rows > 0)
					{?>
						<h4 class="small-title">Room Pictures</h4>
						<div class="row">
							<?php while($row = $result->fetch_assoc())
							{?>
								<div class="col-xs-4">
									<div style="height:129px">
										<a class="zooming" href="images/propertypics/room/big/<?=$row['path']?>" title="<?=$row['title']?>">
											<img src="images/propertypics/room/medium/<?=$row['path']?>" alt="Image" class="mfp-fade img-responsive">
										</a>
									</div>
									<div style="margin: 5px 0px">
										<a href="javascript:void(0)" class="redlink" onClick="modal_button_updater5(<?=$row['id']?>)">
											<span class="label label-warning" data-toggle="modal" data-target="#WarningModalColor2" title="Approve this image">Approve</span>
										</a>
									</div>
								</div>
								<?php 
							}?>
						</div>
						<?php $pending++;
					}
					if(!$pending)
					{?>
						No more images to approve for this partner!
						<?php
					}?>
				</div>
			</div>
		</body>
		<?php include("js.php");?>
	</html>
	<?php
}
ob_end_flush();?>