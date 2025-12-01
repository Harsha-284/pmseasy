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

				//window.parent.location="admin.php?Pg=roomdetails";

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

	$row = execute("select roomtype from roomtypes where id=$id");?>

	<!DOCTYPE html>

	<html lang="en">

		<head>

			<?php include("head.php");?>

		</head>

		<body style="padding-top:0px;">

			<div class="the-box" style="padding-bottom:0px;">

				<?php alertbox($clrclass,$msg);

				modal("Image","image")?>

				<form name="filter_form" method="POST">

					<input type="hidden" name="deleteid" id="deleteid">

					<input type="hidden" name="cnt" id="cnt" value="<?=col("cnt")?>">

				</form>

				<h4 class="small-text">ROOM PICTURES OF '<?=strtoupper($row['roomtype'])?>'</h4>

				<div class="row magnific-popup-wrap">

					<form method="post" action="?id=<?=$id?>&cntr=<?=$cntr?>" enctype="multipart/form-data">

						<?php $result = $conn->query("select id,title,path,approved from pictures where type='Room' and propertyid=$id");

						while($row = $result->fetch_assoc())

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

								<div style="margin: 5px 0px;">

									<a href="javascript:void(0)" class="redlink" onClick='modal_button_updater(<?=$row['id']?>)'>

										<span class="label label-danger" data-toggle="modal" data-target="#DangerModalColor2">Remove</span>

									</a>

									<?=$approvalflag?>

								</div>

							</div>

							<?php

						}?>

						<!-- <div class="col-xs-12"><br><br></div> -->

						<div class="col-xs-4">

							<div class="form-group">

								<input type="text" class="form-control" name="pictitles[]" style="margin-bottom:5px;" placeholder="Picture Title">

								<div class="input-group">

									<input type="text" class="form-control" readonly>

									<span class="input-group-btn">

										<span class="btn btn-default btn-file">

											Browse...<input type="file" name="roompics[]">

										</span>

									</span>

								</div>

							</div>

						</div>

						<div class="col-xs-4">

							<div class="form-group">

								<input type="text" class="form-control" name="pictitles[]" style="margin-bottom:5px;" placeholder="Picture Title">

								<div class="input-group">

									<input type="text" class="form-control" readonly>

									<span class="input-group-btn">

										<span class="btn btn-default btn-file">

											Browse...<input type="file" name="roompics[]">

										</span>

									</span>

								</div>

							</div>

						</div>

						<div class="col-xs-4">

							<div class="form-group">

								<input type="text" class="form-control" name="pictitles[]" style="margin-bottom:5px;" placeholder="Picture Title">

								<div class="input-group">

									<input type="text" class="form-control" readonly>

									<span class="input-group-btn">

										<span class="btn btn-default btn-file">

											Browse...<input type="file" name="roompics[]">

										</span>

									</span>

								</div>

							</div>

						</div>

						<div class="col-xs-12">

							<div class="form-group">

								<input type="submit" value="Upload" class="btn btn-primary">

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