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
	if(isset($_POST['v360']))
	{
		$conn->query("update roomtypes set v360='$_POST[v360]' where id=$id");
		$msg = "360&deg; video link updated";
		$clrclass = "success";?>
		<script type="text/javascript">
			window.parent.document.getElementById("td4id<?=$cntr?>").innerHTML = "<a href='LBF_360degvideo.php?id=<?=$id?>&cntr=<?=$cntr?>' class='fancybox fancybox.iframe'><span class='label label-primary'><i class='fa fa-check'></i> 360&deg; video</span></a>";
		</script>
		<?php
	}

	if(col("deleteid")!="")
	{
		$deleteid = col("deleteid");
		if(content_access("roomtypes",$deleteid))
		{
			$conn->query("update roomtypes set v360='' where id=$deleteid");
			$clrclass	= "success";
			$msg		= "Video has been removed";
		}?>
		<script type="text/javascript">
			window.parent.document.getElementById("td2id<?=$cntr?>").innerHTML = "<a href='LBF_360degvideo.php?id=<?=$deleteid?>&cntr=<?=$cntr?>' class='fancybox fancybox.iframe'><span class='label label-orange'><i class='fa fa-spinner'></i> 360&deg; video</span></a>";
		</script>
		<?php
	}

	$row = execute("select id,v360,roomtype from roomtypes where id=$id");?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box" style="padding-bottom:0px;">
				<?php alertbox($clrclass,$msg);?>
				<h4 class="small-text">360&deg; VIDEO OF '<?=strtoupper($row['roomtype'])?>'</h4>
				<div class="row">
					<form name="filter_form" method="POST">
						<input type="hidden" name="deleteid" id="deleteid">
						<input type="hidden" name="cnt" id="cnt" value="<?=col("cnt")?>">
					</form>
					<?php modal("Video","video");?>
					<form method="post" action="?id=<?=$id?>&cntr=<?=$cntr?>">
						<div class="col-xs-12">
							<div class="form-group">
								<input type="text" name="v360" class="form-control" placeholder="Youtube 360&deg; video link e.g. https://www.youtube.com/watch?v=oNb3H9kICbA" value="<?=$row['v360']?>">
							</div>
						</div>
						<?php $temp = explode("v=",$row['v360']);
						if(isset($temp[1]))
							$v360 = $temp[1];
						else
							$v360 = "";
						if($v360!="")
						{?>
							<div class="col-xs-12">
								<iframe style="width:100%; min-height:300px" src="https://www.youtube.com/embed/<?=$v360?>"></iframe>
							</div>
							<div class="col-xs-12">
								<a href="javascript:void(0)" class="redlink" onClick='modal_button_updater(<?=$id?>)'>
									<span class="label label-danger" data-toggle="modal" data-target="#DangerModalColor2">Remove this video</span>
								</a>
							</div>
							<?php
						}?>
						<div class="col-xs-12">
							<div class="form-group">
								<br><input type="submit" value="Save" class="btn btn-primary">
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