<?php include 'conn.php';

include 'udf.php';



if (!isset($_SESSION['groupid'])) { ?>

	<script type="text/javascript">
		window.parent.location = "login.php";
	</script>

<?php

} else if (page_access("promocodes")) {
	$date = $_GET['date'];
	$roomname = $_GET['roomname'];

?>


	<!DOCTYPE html>

	<html lang="en">

	<head>
		<script src="js/bookings.js"></script>
		<?php include("head.php"); ?>


	</head>

	<style>
		label {
			margin-bottom: 0px;
			font-weight: 500;
			font-size: 14px;
			color: black;
			display: block;
		}

		/* Hide the default calendar icon in Chrome, Safari, and Edge */
		input[type="date"]::-webkit-calendar-picker-indicator {
			position: absolute;
			top: 15;
			left: -5;
			color: red;
			width: 18px;
			height: 18px;
		}

		/* Change color of the calendar icon */
		.input-group-text .fa-calendar {
			color: red;
			/* Change icon color to red */
		}

		/* Optional: Adjust size if needed */
		.input-group-text .fa-calendar {
			font-size: 18px;
			/* Adjust icon size */
		}

		.fa {
			width: 18px;
			height: 18px;
		}

		/* Hide arrows for Chrome, Safari, Edge, and Opera */
		input[type="number"]::-webkit-inner-spin-button,
		input[type="number"]::-webkit-outer-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}

		/* Hide arrows for Firefox */
		input[type="number"] {
			-moz-appearance: textfield;
		}

		.table-th-block>thead>tr>th,
		.table-th-block>tbody>tr>th,
		.table-th-block>tfoot>tr>th,
		.table-th-block>thead>tr>td,
		.table-th-block>tbody>tr>td,
		.table-th-block>tfoot>tr>td {
			vertical-align: text-top;
		}
	</style>


	<body style="padding-top:0px;">

		<!-- 1st modal for checking availability-->
		<div id="1stModaldata" class="modal-body" style="overflow-y: auto; width:100%; height: 100%">
			<div class="olddetails">
				<h5 style="font-weight:bold">Assign Rooms</h5>
			</div>

			<!-- BRN filter input -->
			<div class="row mb-3">
				<div class="col-md-4 mb-3">
					<form method="GET" action="bookmap.php">
						<div class="input-group mb-3" style="display:flex !important; margin-bottom:10px;">
							<input type="text" id="brnInput" name="brn" class="form-control" placeholder="Enter BRN"
								value="<?= isset($_GET['brn']) ? htmlspecialchars($_GET['brn']) : '' ?>" style="width:fit-content; margin-right:10px;">
							<button class="btn btn-primary" type="submit">Filter</button>
						</div>
					</form>
				</div>
			</div>


			<!-- Bookings Table -->
			<table class="table table-th-block table-hover">
				<thead>
					<tr style='font-size: 13px'>
						<th style="width: 45px;">BR#</th>
						<th style="width: 220px;">User details</th>
						<th style="width: 220px;">Room details</th>
						<th>Room assigned</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php

					if (isset($_GET['brn']) && trim($_GET['brn']) !== '') {
						$term = trim($_GET['brn']);
						$safe = $conn->real_escape_string($term);
						$brnId = null;

						if (preg_match('/^fr\s*(\d+)$/i', $term, $m)) {
							$brnId = (int)$m[1];
						} elseif (ctype_digit($term)) {
							$brnId = (int)$term;
						}

						$where = [];
						if ($brnId !== null) {
							$where[] = "b.banquet_booking_id = $brnId OR b.id = $brnId";
						}
						$where[] = "u.fullname LIKE '%$safe%'";
						$where[] = "u.contact LIKE '%$safe%'";

						$whereClause = implode(' OR ', $where);

						$result = $conn->query("
		SELECT b.id, u.fullname, u.contact, u.email, u.address1,b.banquet_booking_id,
		       b.checkindatetime, b.checkoutdatetime, b.usercheckedout, b.usercheckedin,
		       r.id AS room_id,
		       GROUP_CONCAT(rn.roomnumber) AS roomnumbers,
		       GROUP_CONCAT(rd.isRoomAssigned) AS isRoomAssigned
		FROM bookings b
		JOIN room_distribution rd ON rd.bookingid = b.id
		JOIN roomnumbers rn ON rn.id = rd.roomnumber
		JOIN roomtypes r ON r.id = rn.roomtype
		JOIN users u ON u.id = b.guestid
		WHERE $whereClause
		AND (b.status = 'Scheduled' OR b.status = 'Cancelled') 
		GROUP BY b.id
	");


						while ($row = $result->fetch_assoc()) {
							$checkindate = new DateTime($row['checkindatetime']);
							$checkoutdate = new DateTime($row['checkoutdatetime']);
							$roomtype = execute("SELECT roomtype FROM roomtypes WHERE id=" . (int)$row['room_id']);
							$no_of_room = execute("SELECT COUNT(*) AS cnt FROM room_distribution WHERE bookingid=" . $row['id']);
					?>
							<tr style='font-size: 13px'>
								<td><?= $row['id'] ?> <br>
									<p style='font-size: 10px; color:red;'>
										<?php if ($row['banquet_booking_id'] != null) {
											echo "(Banqueteasy)";
										} ?></p>
								</td>
								<td>
									Guest Name: <?= $row['fullname'] ?>,<br>
									Mobile No: <?= $row['contact'] ?>,<br>
									Email: <?= $row['email'] ?>,<br>
									Address: <?= $row['address1'] ?>
								</td>
								<td>
									Check-in: <?= $checkindate->format('d-m-Y') ?>,<br>
									Check-out: <?= $checkoutdate->format('d-m-Y') ?>,<br>
									Room Type: <?= $roomtype['roomtype'] ?>,<br>
									Rooms: <?= $no_of_room['cnt'] ?>
								</td>
								<td>
									<div style="display: flex; gap: 5px; flex-wrap: wrap;">
										<?php
										$roomNumbers = explode(',', $row['roomnumbers']);
										$isRoomAssigned = explode(',', $row['isRoomAssigned']);
										$noroomassigned = 0;
										foreach ($roomNumbers as $i => $roomNumber) {
											if ($isRoomAssigned[$i]) {
												echo "<div class='cube booked' style='width:45px;height:25px;text-align:center;line-height:25px;'>{$roomNumber}</div>";
											} else {
												$noroomassigned = 1;
											}
										}
										if ($noroomassigned) echo "<div>No Room Assigned yet</div>";
										?>
									</div>
								</td>
								<td style="vertical-align: sub; display: flex; gap: 15px;  ">
									<?php
									$todaysDate = date("d-m-Y");
									$checkindate = new DateTime($row['checkindatetime']);
									if ($noroomassigned && $todaysDate >= $checkindate->format("d-m-Y")) {
										echo '
									<a id="assign-check-in" class="btn available" style="" href="LBF_assignroom.php?id=' . $row['id'] . '&roomtype=' . $row['room_id'] . '">Check in</a>

									';
									} else if ($todaysDate >= $checkindate->format("d-m-Y")) {
										echo '<a id="edit-check-in" class="btn available" style="" href="LBF_assignroom.php?id=' . $row['id'] . '&roomtype=' . $row['room_id'] . '">Edit</a>';
									} else {
										// done
									}
									?>
									<?php
									// Check the user status and show appropriate buttons
									if ($row['usercheckedin'] == "0000-00-00 00:00:00" || $row['usercheckedin'] == "") {
										// User has not checked in yet, show 'Check in' button

										$todaysDate = date("d-m-Y");
									} else if ($row['usercheckedout'] == "0000-00-00 00:00:00" || $row['usercheckedout'] == "") {
										// User is checked in but has not checked out yet, show 'Check out' button and check-in time
										$checkInTime = date("d-m-Y, g:i a", strtotime($row['usercheckedin'])); // Format the check-in time
										$btnvalue = "
												<a id='edit-checkout' href='LBF_usercheckinout.php?id=" . $row['id'] . "' class='fancybox2 fancybox.iframe' style='color:#FFFFFF'>
												<button class='btn booked' style='' >Check out</button>
												</a>
												<small style='display: block' >Checked in at: " . $checkInTime . "</small> 
											";
									} else {
										// User has already checked out, show 'Checked out' status
										$checkInTime = date("d-m-Y, g:i a", strtotime($row['usercheckedin']));
										$checkOutTime = date("d-m-Y, g:i a", strtotime($row['usercheckedout']));
										$btnvalue = "
												<div>
												<a href='LBF_usercheckinout.php?id=" . $row['id'] . "' class='fancybox fancybox.iframe' style='color:#FFFFFF;'><button class='btn booked'>Checked out</button></a>
												</div>";
									}
									?>

									<?= $btnvalue ?>

									<!-- Only show 'No show' and 'Cancel Booking' buttons if the user has NOT checked in -->
									<?php
									$todaysDate = date("d-m-Y"); // Get today's date in the format "d-m-Y"
									$checkindate = new DateTime($row['checkindatetime']);

									// First condition for "No show"
									if (($row['usercheckedin'] == "0000-00-00 00:00:00" || $row['usercheckedin'] == "") && $todaysDate >= $checkindate->format("d-m-Y")): ?>
										<a id="booking-edit" class="btn booked" onclick="noshow('<?= $userInfo['cm_company_name'] ?>', '<?= $row['id'] ?>')">No show</a>
									<?php
									endif;

									// Additional condition for "Cancel Booking"
									if (($row['usercheckedin'] == "0000-00-00 00:00:00" || $row['usercheckedin'] == "") && $todaysDate < $checkindate->format('d-m-Y')): ?>
										<a href="">
											<button id="booking-cancel" onClick="handleClick('<?= $row['id'] ?>')" class="btn booked">Cancel Booking</button>
										</a>
									<?php
									endif; ?>
								</td>
							</tr>
					<?php
						}
					}
					?>
				</tbody>
			</table>
		</div>


		</div>
	</body>


	</html>
<?php

}

ob_end_flush(); ?>