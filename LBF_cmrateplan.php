<?php include 'conn.php';

include 'udf.php';



if (!isset($_SESSION['groupid'])) { ?>

	<script type="text/javascript">
		window.parent.location = "login.php";
	</script>

	<?php

} else if (page_access("roomdetails") and content_access("roomtypes", $id)) {

	if (isset($_POST['oldroomnumbers'])) {

		$conn->query("update room_rate_plans set deleted=1 where roomtype=$id");



		foreach ($_POST['oldroomnumbersid'] as $key => $oldroomnumberid) {

			$oldroomnumber = $_POST['oldroomnumbers'][$key];

			if (content_access("room_rate_plan", $oldroomnumberid))

				$conn->query("update room_rate_plans set cm_rate_plan='$oldroomnumber',deleted=0 where id=$oldroomnumberid");
		}

		$msg = "Room numbers are set";

		$clrclass = "success";
	}



	if ($msg == "Room Rates are set") { ?>

		<script type="text/javascript">
			window.parent.location = "admin.php?Pg=roomdetails";
		</script>

	<?php

	}



	$row = execute("select roomtype from roomtypes where id=$id"); ?>

	<!DOCTYPE html>

	<html lang="en">

	<head>

		<?php include("head.php"); ?>

	</head>

	<body style="padding-top:0px;">

		<div class="the-box" style="margin: 30px;">

			<?php alertbox($clrclass, $msg); ?>

			<h1 class="page-heading">SET CM ROOM RATE PLAN '<?= strtoupper($row['roomtype']) ?>'</h1>

			<div class="row">

				<form method="post" action="?id=<?= $id ?>" id="rnform">


					<?php $result = $conn->query("select id,cm_rate_plan from room_rate_plans where roomtype=$id and deleted=0");

					if ($result->num_rows > 0) {

						$i = 0;

						while ($row = $result->fetch_assoc()) { ?>

							<div id="rn<?= $i ?>">

								<div class="col-xs-4">

									<div class="form-group">

										<input type="text" name="oldroomnumbers[]" class="form-control" placeholder="E.g Deluxe (with dinner)" value="<?= $row['cm_rate_plan'] ?>" required>

										<input type="hidden" name="oldroomnumbersid[]" value="<?= $row['id'] ?>">

									</div>
								</div>
							</div>

					<?php $i++;
						}
					} ?>



					<!-- <div id="newroomnumbers"></div>

						<div class="col-xs-12">

							<div class="form-group">

								<a href="javascript:void(0)" onClick="addroomnumber()">+Add more room number(s)</a>

							</div>

						</div> -->

					<div class="col-xs-12">

						<div class="form-group">

							<input type="submit" value="Save" class="btn btn-primary">

						</div>

					</div>

				</form>

			</div>

		</div>

	</body>

	<?php include("js.php"); ?>

	</html>

<?php

}

ob_end_flush(); ?>