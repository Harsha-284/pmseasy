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
			<div class="the-box" style="padding-bottom:0px; margin-bottom:0px;">
				<h4 class="small-text">TRAVELER'S DETAILS</h4>
				<?php $row = execute("select u.fullname,u.email,u.contact,u.address1,u.address2,s.state,u.travelercity,u.dob,u.reg_date from users u left join states s on s.id=u.travelerstate where u.groupid=5 and u.id=$id");?>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label>Full Name</label>
							<div class="form-control">
								<?=$row['fullname']?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label>Email</label>
							<div class="form-control">
								<?=$row['email']?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label>Contact</label>
							<div class="form-control">
								<?=$row['contact']?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label>Date of Birth</label>
							<div class="form-control">
								<?php if($row['dob']=="0000-00-00")
									echo "-";
								else
									echo popdate("dob");?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label>Address</label>
							<div class="form-control">
								<?=$row['address1']." ".$row['address2']?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label>City</label>
							<div class="form-control">
								<?=$row['travelercity']?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label>State</label>
							<div class="form-control">
								<?=$row['state']?>
							</div>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label>Sign up Date</label>
							<div class="form-control">
								<?=popdate($row['reg_date'])?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</body>
		<?php include("js.php");?>
	</html>
	<?php
}
ob_end_flush();?>