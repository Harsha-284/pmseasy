<?php include 'conn.php';
include 'udf.php';

if(!isset($_SESSION['groupid']))
{?>	
	<script type="text/javascript">
		window.parent.location="login.php";
	</script>
	<?php
}
else if($_SESSION['groupid']<=0 or page_access($_SESSION['modules'],"cities"))
{?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
			<link href="css/lightbox.css" rel="stylesheet">
		</head>
		<body style="padding-top:0px;">
			<div class="the-box">
				<?php if(isset($_POST['city']))
				{
					$latitude	= $_POST['latitude'];
					$longitude	= $_POST['longitude'];
					$city		= $_POST['city'];
					$row		= execute("select count(id)cnt from cities where city='$city' and id<>$_GET[id]");
					if($row['cnt'] == 0)
					{
						$conn->query("update cities set city='$city',latitude='$latitude',longitude='$longitude' where id=$id");

						alertbox("success","City name has been updated");?>
						
						<script type="text/javascript">
							window.parent.document.getElementById("td2id<?=$_GET['cntr']?>").innerHTML = "<?=$city?>";
						window.parent.document.getElementById("td5id<?=$_GET['cntr']?>").innerHTML = "<?=$latitude.', '.$longitude?>";
							setTimeout('parent.$.fancybox.close();', 1500);
						</script>
						<?php
					}
					else
						alertbox("danger","City name already exist");
				}
				$row = execute("select c.id,c.city,s.state,con.country,c.latitude,c.longitude from cities c left join states s on c.state=s.id left join countries con on con.id=s.country where c.id=$id");?>
				<h1 class="page-heading">Edit City</h1>
				<form method="post" action="?id=<?=$id?>&cntr=<?=$_GET['cntr']?>">
					<div class="row">
						<div class="col-xs-4">
							<div class="form-group">
								<label>Country</label>
								<input type="text" class="form-control" value="<?=$row['country']?>" disabled>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<label>State</label>
								<input type="text" class="form-control" value="<?=$row['state']?>" disabled>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<label>City</label>
								<input type="text" name="city" class="form-control" value="<?=$row['city']?>">
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label>Location</label>
								<input type="text" class="form-control" id="us3-address" value="" />
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group">
								<label>Latitude</label>
								<input type="text" class="form-control" name="latitude" value="<?=$row['latitude']?>" id="us3-lat" readonly/>
							</div>
						</div>
						<div class="col-xs-3">
							<div class="form-group">
								<label>Longitude</label>
								<input type="text" class="form-control" name="longitude" value="<?=$row['longitude']?>" id="us3-lon" readonly/>
							</div>
						</div>
						<div class="col-xs-12">
							<div id="us3" style="width:100%; height:400px;"></div>
						</div>
						<div class="col-xs-12" style="margin-top:15px;">
							<div class="form-group">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</div>
					<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
					<script type="text/javascript" src='http://maps.google.com/maps/api/js?key=AIzaSyBLZ9ZLnTdY6vAdJ9ty5OSYDeqvyfDXGgQ&sensor=false&libraries=places'></script>
					<script src="locationpicker/locationpicker.jquery.min.js"></script>
					<input type="hidden" class="form-control" id="us3-radius"/>
					<script>
						$('#us3').locationpicker({
							location: {
								latitude: <?=$row['latitude']?>,
								longitude: <?=$row['longitude']?>
							},
							radius: 0,
							zoom: 14,
							inputBinding: {
								latitudeInput: $('#us3-lat'),
								longitudeInput: $('#us3-lon'),
								radiusInput: $('#us3-radius'),
								locationNameInput: $('#us3-address')
							},
							enableAutocomplete: true,
							onchanged: function (currentLocation, radius, isMarkerDropped) {
								// Uncomment line below to show alert on each Location Changed event
								//alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
							}
						});
					</script>
				</form>
			</div>
			<js>
				<?php include("js.php");?>
			</js>
		</body>
	</html>
	<?php
}
ob_end_flush();?>