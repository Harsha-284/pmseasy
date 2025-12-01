<?php include 'conn.php';
include 'udf.php';

if(!isset($_SESSION['groupid']))
{?>	
	<script type="text/javascript">
		window.parent.location="login.php";
	</script>
	<?php
}
else if(page_access("cities"))
{
	if(isset($_FILES['cityimage']))
	{
		$filename = saveimage("cityimage","../images/cityimages/",1920);

		if($filename != "")
		{
			$row = execute("select path from cities where id=$id");
			deletefile("../images/cityimages/".$row['path']);
			$conn->query("update cities set path='$filename' where id=$id");
			$msg = "City image banner updated";
			$clrclass = "success";?>
			<script type="text/javascript">
				window.parent.document.getElementById("td6id<?=$cntr?>").innerHTML = "<a href='LBF_cityimage.php?id=<?=$id?>&cntr=<?=$cntr?>' class='fancybox fancybox.iframe'><img src='../images/cityimages/<?=$filename?>' width='140' border='0' alt=''></a>";
			</script>
			<?php
		}
	}

	$row = execute("select path from cities where id=$id");
	if($row['path']=="")
		$path = "booking-confirm-bannar.png";
	else
		$path = $row['path'];?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box" style="padding-bottom:0px;">
				<?php alertbox($clrclass,$msg);?>
				<h4 class="small-text">CITY IMAGE BANNER</h4>
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<img src="../images/cityimages/<?=$path?>" width="775" border="0" alt="">
						</div>
					</div>
				</div>
				<form method="post" action="?id=<?=$id?>&cntr=<?=$cntr?>" enctype="multipart/form-data">
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<label>City Image file</label>
								<div class="input-group">
									<input type="text" class="form-control" readonly="">
									<span class="input-group-btn">
										<span class="btn btn-default btn-file">
											Browse...<input type="file" name="cityimage" required>
										</span>
									</span>
								</div>
							</div>
							<p class="help-block">Image Dimensions : 1920 x 275 Pixels</p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<input type="submit" value="Upload" class="btn btn-primary">
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