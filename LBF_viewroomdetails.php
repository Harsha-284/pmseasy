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

	if(isset($_FILES['roompics']))

	{

		$i=0;

		foreach($_FILES['roompics']['name'] as $key => $newpic)

		{

			if($_FILES['roompics']['name'][$key]!="")

			{

				$title = nosql($_POST['pictitles'][$key]);

				insert("insert into pictures (propertyid,type,title,path) values ($id,'Room','$title','".saveimage("roompics","images/propertypics/room/",0,$key)."')");

				$i++;

			}

		}

		if($i > 0)

		{

			$clrclass= "success";

			$msg	 = "Room picture(s) added successfully";?>

			<script type="text/javascript">

				window.parent.location="admin.php?Pg=roomdetails";

			</script>

			<?php

		}

	}



	if(isset($_POST['deleteid']))

	{

		if(content_access("pictures",$_POST['deleteid'],"Room"))

		{

			$row = execute("select path from pictures where id=$_POST[deleteid]");

			$path = $row['path'];

			$conn->query("delete from pictures where id=$_POST[deleteid]");

			deletefile("images/propertypics/room/big/$path");

			deletefile("images/propertypics/room/medium/$path");

			deletefile("images/propertypics/room/small/$path");

			

			$msg = "Picture has been deleted";

			$clrclass = "success";

		}

		else

		{

			$msg = "You do not have permission to delete this picture";

			$clrclass = "danger";

		}

	}

	$row = execute("select x.*,group_concat(f.facility SEPARATOR ', ')roomfacilities from (select r.id,r.adults,r.children,r.roomtype,r.v360,h.allowbulkbooking, group_concat(rn.roomnumber SEPARATOR ', ')roomnumbers from roomtypes r join roomnumbers rn on rn.roomtype=r.id join hotels h on h.id=r.hotel where r.id=$id group by r.id)x join room_facilities rf on (rf.roomtype=x.id) join facilities f on rf.facility=f.id");

	$temp = explode("v=",$row['v360']);

	if(isset($temp[1]))

		$v360 = $temp[1];

	else

		$v360 = "";?>

	<!DOCTYPE html>

	<html lang="en">

		<head>

			<?php include("head.php");?>

		</head>

		<body style="padding-top:0px;">

			<div class="the-box" style="margin-bottom:0px;padding-bottom:0px;">

				<?php alertbox($clrclass,$msg);

				modal("Image","image")?>

				<form name="filter_form" method="POST">

					<input type="hidden" name="deleteid" id="deleteid">

					<input type="hidden" name="cnt" id="cnt" value="<?=col("cnt")?>">

				</form>

				<h4 class="small-text">ROOM DETAILS OF '<?=strtoupper($row['roomtype'])?>'</h4>

				<div class="row">

					<div class="col-xs-12">

						<div class="form-group">

							<label>Room Numbers</label>

							<input type="text" class="form-control" value="<?=$row['roomnumbers']?>" disabled>

						</div>

					</div>

					<div class="col-xs-12">

						<div class="form-group">

							<label>Room Amenities</label>

							<textarea class="form-control" disabled><?=$row['roomfacilities']?></textarea>

						</div>

					</div>

					<div class="col-xs-4">

						<div class="form-group">

							<label>Max. No. of Adults</label>

							<input type="text" class="form-control" value="<?=$row['adults']?>" disabled>

						</div>

					</div>

					<div class="col-xs-4">

						<div class="form-group">

							<label>Max. No. of Children</label>

							<input type="text" class="form-control" value="<?=$row['children']?>" disabled>

						</div>

					</div>

					<?php if($row['allowbulkbooking'])

					{?>

						<div class="col-xs-4">

							<div class="form-group">

								<label>Max. No. of Guests in Bulk Booking</label>

								<input type="text" class="form-control" value="<?=$row['bulk']?>" disabled>

							</div>

						</div>

						<?php

					}?>

				</div>

				<?php $result = $conn->query("select id,title,path,approved from pictures where type='Room' and propertyid=$id");

				if($result->num_rows > 0)

				{?>

					<h4>Room Pictures</h4>

					<div class="row magnific-popup-wrap">

						<?php while($row = $result->fetch_assoc())

						{

							if($row['approved'] == 1)

								$approvalflag = "<i class='fa fa-flag' style='color:#8CC152;float:right;padding-right:2px;' title='Approved Image'></i>";

							else

								$approvalflag = "<i class='fa fa-flag' style='color:#E9573F;float:right;padding-right:2px;' title='Admin Approval Pending'></i>";?>

							<div class="col-xs-2" style="width:20%">

								<label><?=$row['title']?></label>

								<div style="height:129px;">

									<a class="zooming" href="images/propertypics/room/medium/<?=$row['path']?>" title="<?=$row['title']?>">

										<img src="images/propertypics/room/medium/<?=$row['path']?>" alt="Image" class="mfp-fade img-responsive">

									</a>

								</div>

							</div>

							<?php

						}?>

					</div>

					<?php

				}

				if($v360!="")

				{?>

					<div class="row">

						<div class="col-xs-12">

							<div class="form-group">

								<h4>360&deg; Video</h4>

								<iframe style="width:100%; min-height:300px" src="https://www.youtube.com/embed/<?=$v360?>"></iframe>

							</div>

						</div>

					</div>

					<?php

				}?>

			</div>

		</body>

		<?php include("js.php");?>

	</html>

	<?php

}

ob_end_flush();?>