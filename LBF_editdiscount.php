<?php include 'conn.php';
include 'udf.php';

if(!isset($_SESSION['groupid']))
{?>	
	<script type="text/javascript">
		window.parent.location="login.php";
	</script>
	<?php
}
else if(page_access("discounts") and content_access("discounts",$id))
{
	if(isset($_POST['title']))
	{
		$conn->query("update discounts set title='$_POST[title]',validfrom='".dateindia($_POST['validfrom'])."',validto='".dateindia($_POST['validto'])."', value=$_POST[value] where id=$id");

		$conn->query("delete from discountedrooms where discount=$id");
		foreach($_POST['roomtypes'] as $roomtype)
		{
			$row = execute("select count(*)cnt from discounts d join discountedrooms dr on dr.discount=d.id and (('".dateindia($_POST['validfrom'])."' between d.validfrom and d.validto) or ('".dateindia($_POST['validto'])."' between d.validfrom and d.validto)) and dr.roomtype=$roomtype");
			if(!$row['cnt'])
				$conn->query("insert into discountedrooms (discount,roomtype) values ($id,".nosql($roomtype).")");
		}

		$row = execute("select d.type,group_concat(r.roomtype SEPARATOR ', ')roomtypes from discounts d join discountedrooms dr on d.id=dr.discount join roomtypes r on r.id=dr.roomtype where dr.discount=$id group by dr.discount,d.type");
		
		$clrclass = "success";
		$msg = "Discount has been updated";?>

		<script type="text/javascript">
			window.parent.document.getElementById("td1id<?=$cntr?>").innerHTML = "<?=$_POST['title']?>";
			window.parent.document.getElementById("td2id<?=$cntr?>").innerHTML = "<?=popdate(dateindia($_POST['validfrom']))?>";
			window.parent.document.getElementById("td3id<?=$cntr?>").innerHTML = "<?=popdate(dateindia($_POST['validto']))?>";
			window.parent.document.getElementById("td4id<?=$cntr?>").innerHTML = "<?=$_POST['value']?><?=($row['type']=='Percent')?'%':'/-';?>";
			window.parent.document.getElementById("td5id<?=$cntr?>").innerHTML = "<?=$row['roomtypes']?>";
			setTimeout('parent.$.fancybox.close();', 1200);
		</script>
		<?php
	}
	$row = execute("select * from discounts where id=$id");?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box" style="padding-bottom:4px;margin-bottom:0px;">
				<?php alertbox($clrclass,$msg);?>
				<h4 class="small-text">EDIT DISCOUNT</h4>
				<div class="row">
					<form method="post" action="?id=<?=$id?>&cntr=<?=$cntr?>">
						<div class="col-xs-12">
							<div class="form-group">
								<label>Discount Title</label>
								<input type="text" name="title" class="form-control" value="<?=$row['title']?>">
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label>Discount Type</label>
								<input type="text" name="type" class="form-control" value="<?=$row['type']?>" disabled>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label>Discount Value</label>
								<input type="text" name="value" class="form-control" value="<?=$row['value']?>">
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label>Valid From</label>
								<input type="text" name="validfrom" data-date-format="dd-mm-yyyy" id="datepicker1" class="form-control" value="<?=popdate($row['validfrom'],"dmY")?>" readonly>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label>Valid To</label>
								<input type="text" name="validto" data-date-format="dd-mm-yyyy" id="datepicker2" class="form-control" value="<?=popdate($row['validto'],"dmY")?>" readonly>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label>Room Types</label>
								<div style="margin-left:-8px;">
									<?php $result = $conn->query("select r.id,r.roomtype,coalesce(dr.roomtype,0)matched from roomtypes r left join discountedrooms dr on (r.id=dr.roomtype and dr.discount=$row[id]) where r.hotel=$_SESSION[hotel]");
									while($row=$result->fetch_assoc())
									{?>
										<div class="radio-6">
											<input type="checkbox" name="roomtypes[]" value="<?=$row['id']?>" <?=checked($row['id'],$row['matched'])?>><?=$row['roomtype']?>
										</div>
										<?php
									}?>
								</div>
							</div>
						</div>
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