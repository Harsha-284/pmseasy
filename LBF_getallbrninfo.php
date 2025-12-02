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

		.header {
			border: 1px solid #e8e9ee;
			padding-bottom: 15px;
		}

		.card {
			margin-bottom: 20px;
		}

		.br {
			display: flex;
			align-items: center;
		}

		.header-content {
			display: flex;
			justify-content: space-between;
			padding: 5px 10px;
			padding-right: 30px;
			background: #ede8e8;
			margin-bottom: 15px;
		}

		.inner-content {
			display: flex;
			gap: 15px;
			padding: 0 15px;
		}

		.modal-body {
			padding: 0;
		}

		.client-details {
			width: 60%;
		}

		.check-in-details {
			width: 40%;
		}

		.client-content {
			padding: 10px 15px;
			border: 1px solid #e8e9ee;
		}

		.content-header {
			padding: 5px;
			color: #656D78;
			background-color: #e8e9ee;
			border-color: #e8e9ee;
			text-transform: uppercase;
		}

		.client-content p {
			margin: 0 0 2px;
		}

		.assign-head {
			padding: 10px;
			font-size: 16px;
			font-weight: 700;
			line-height: 100%;
			text-transform: uppercase;
			color: #e45452;
			margin: 0 10px !important;
			/* background-color:#dcdcdc; */
			border-bottom: 1px solid #dcdcdc;
			margin-bottom: 15px !important;
		}

		.card-body-inner-section {
			padding: 0 10px;
		}

		.border {
			border: 1px solid #dcdcdc;
		}

		.available {
			padding: 4px;
			/* font-size: 12px; */
		}

		.booked {
			padding: 4px;
			font-size: 12px;
		}
	</style>


	<body style="padding-top:0px;">

		<?php
		$bdate = date_create($_GET['date']);
		$a = date_create($bdate->format("Y-m-d") . " " . '12' . ":" . '00');
		$now = date_create(date("Y-m-d H:i"));
		$roomTypeFilter = '';

		?>

		<!-- 1st modal for checking availability-->
		<div id="1stModaldata" class="modal-body" style=" width:100%; height: 100%">
			<div class="olddetails border">
				<h5 class="assign-head" style="font-weight:bold;margin:0;">Assign Rooms</h5>


				<?php
				$i = 0;
				$nowFormatted = $now->format("Y-m-d H:i");
				$aFormatted = $a->format("Y-m-d H:i");
				$roomTypeId = intval($_GET['id']); // Ensure this is an integer for security
				$roomNumberId = intval($_GET['room_id']); // Ensure this is an integer for security
				if ($_GET['id'] != "all") {
					$roomtype = execute("select roomtype from roomtypes where id=$_GET[id]");
					$roomTypeId     = (int)$_GET['id'];
					$roomTypeFilter = " AND r.id = $roomTypeId";
				} else {
					$roomTypeFilter = '';
				}
				$result = $conn->query("SELECT DISTINCT b.id,u.fullname,u.contact,u.email,u.address1,b.checkindatetime,b.checkoutdatetime ,b.usercheckedout,b.usercheckedin,r.id as room_id, GROUP_CONCAT(rn.roomnumber) AS roomnumbers, GROUP_CONCAT(rd.isRoomAssigned) AS isRoomAssigned
                         FROM bookings b 
                         JOIN room_distribution rd ON rd.bookingid = b.id 
                         JOIN roomnumbers rn ON rn.id = rd.roomnumber 
                         JOIN roomtypes r ON r.id = rn.roomtype 
						 JOIN users u ON u.id = b.guestid 
                         WHERE (b.paid = 1 OR b.ticker > '$nowFormatted') 
                         AND (b.status = 'Scheduled' OR b.status = 'Cancelled') 
                         AND (b.checkindatetime <= '$aFormatted' AND b.checkoutdatetime >= '$aFormatted') 
						 AND rn.id = $roomNumberId
                         $roomTypeFilter GROUP BY b.id");
				//  print_r($result);

				while ($row = $result->fetch_assoc()) {
					if ($_GET['id'] == "all") {
						$roomtype = execute('SELECT roomtype FROM roomtypes WHERE id=' . (int)$row['room_id']);
					}

					$no_of_room = execute("select COUNT(*)cnt from room_distribution where bookingid=$row[id]");
					$checkindate = new DateTime($row['checkindatetime']);
					$checkoutdate = new DateTime($row['checkoutdatetime']);
					$i++; ?>
					<div class="available-rooms" style="width: 82%; display: flex; gap: 5px; flex-wrap: wrap; padding: 0px;position: absolute;top: 24%;right: 9%;">
						<?php
						$roomNumbers = explode(',', $row['roomnumbers']); // Split room numbers into an array
						$isRoomAssigned = explode(',', $row['isRoomAssigned']);
						$i = 0;
						$noroomassigned = 0;
						foreach ($roomNumbers as $roomNumber) {
							$indiIsRoomAssigned = $isRoomAssigned[$i];
							$i++;
							if ($indiIsRoomAssigned) {
								echo "<div class='cube booked' id='$roomNumber' style='border-radius: 2px; width:45px; text-align: center; cursor: pointer; height: 25px;'>$roomNumber</div>";
							} else {
								$noroomassigned = 1;
							}
						}

						?>
					</div>
					<div class="card-body-inner-section">
						<div class="card">
							<div class="card-body">
								<div class="header">
									<div class="header-content">
										<div class="br" style="    position: relative; right: -6px;">
											<span style="font-weight: 600;">BRN#</span> &nbsp;&nbsp;&nbsp; <span><?= $row['id'] ?></span>
										</div>
										<div class="btns" style="position: relative; top: 8px; right: -58.5px;">
											<?php
											$todaysDate = date("d-m-Y");
											$checkindate = new DateTime($row['checkindatetime']);
											if ($noroomassigned && $todaysDate >= $checkindate->format("d-m-Y")) {
												echo '
										<a id="assign-check-in" class="btn available" style="" href="LBF_assignroom.php?id=' . $row['id'] . '&roomtype=' . $row['room_id'] . '">Check in</a>

										';
											} else if ($todaysDate >= $checkindate->format("d-m-Y")) {
												echo '<a id="edit-check-in" class="btn available" style="width: 67px; height: 32px;  padding: 7px 17px 5px 21px;" href="LBF_assignroom.php?id=' . $row['id'] . '&roomtype=' . $row['room_id'] . '">Edit</a>';
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
													<button class='filter-action-btn2' style='' >Check out</button>
													</a> 
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
												<a id="booking-edit" class="btn filter-action-btn1 mr-2" onclick="noshow('<?= $userInfo['cm_company_name'] ?>', '<?= $row['id'] ?>')">No show</a>
											<?php
											endif;

											// Additional condition for "Cancel Booking"
											if (($row['usercheckedin'] == "0000-00-00 00:00:00" || $row['usercheckedin'] == "") && $todaysDate < $checkindate->format('d-m-Y')): ?>

												<button id="booking-cancel" onClick="handleClick('<?= $row['id'] ?>')" class="btn booked">Cancel Booking</button>
											<?php
											endif;

											if ($row['usercheckedout'] == "0000-00-00 00:00:00" || $row['usercheckedout'] == "") {
												$checkInTime = date("d-m-Y, g:i a", strtotime($row['usercheckedin']));
												echo "<small style='display: block; position: relative;left: -220%;top:-49%;'>Checked in at: " . $checkInTime . "</small>";
											}
											?>

										</div>
									</div>
									<div class="inner-content">

										<div class="client-details">
											<div class="content-header">
												Client Details
											</div>
											<div class="client-content">
												<p><b>Guest Name:</b> <?= $row['fullname'] ?></p>
												<p><b>Mobile No:</b> <?= $row['contact'] ?></p>
												<p><b>Email:</b> <?= $row['email'] ?></p>
												<p><b>Address:</b> <?= $row['address1'] ?></p>
											</div>
										</div>
										<div class="check-in-details">
											<div class="content-header">
												Check-in Detailes
											</div>
											<div class="client-content">
												<p><b>Check-in Date:</b> (<?= $checkindate->format('d-m-Y') ?>)</p>
												<p><b>Check-out Date:</b> (<?= $checkoutdate->format('d-m-Y') ?>)</p>
												<p><b>Room Type:</b> <?= $roomtype['roomtype'] ?></p>
												<p><b>No. of Rooms:</b> <?= $no_of_room['cnt'] ?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php include("js.php"); ?>


	</body>

	<!-- <script>
		let permissions = <?= decode_permissions($_SESSION['permission']) ?>;
		let group_id = <?= $_SESSION['groupid'] ?>;

		if (group_id >= 3 && group_id <= 4) {
			let bookingCancel = document.getElementById('booking-cancel');
			let bookingEdit = document.getElementById('booking-edit');
			let checkout=document.getElementById('edit-checkout');
			let assignRoom=document.getElementById('assign-check-in');
			let editRoom=document.getElementById('edit-check-in');

			if (bookingCancel && !permissions.booking.Delete) {
				bookingCancel.style.display = 'none';
			}

			if (bookingEdit && !permissions.booking.Edit) {
				bookingEdit.style.display = 'none';
			}

			if (checkout && !permissions.assign_room.Assign) {
				checkout.style.display = 'none';
			}

			if(assignRoom && !permissions.assign_room.Assign){
				assignRoom.style.display='none'
			}

			if(editRoom && !permissions.assign_room.Edit){
				editRoom.style.display='none'
			}


		}
	</script> -->

	<script>
		async function handleClick(bookingid) {
			console.log(bookingid);
			$.ajax({
				type: 'POST',
				url: 'bookingajax.php',
				data: {
					action: "update_cancel_request",
					bookingid: bookingid,
				},
				success: function(response) {
					res = JSON.parse(response)
					if (res.status === 'success') {
						window.parent.location = "admin.php?Pg=cancellationrequests"
					}
				}
			});
		}

		function noshow(hotelcode, bookingid) {
			const body = {
				action: "cancel",
				hotelCode: hotelcode,
				channel: "Goingo",
				bookingId: bookingid
			}
			fetch(`update_reservation.php`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						// Include any additional headers your API requires here
					},
					body: JSON.stringify(body)
				})
				.then(response => response.json())
				.then(data => {
					// Handle the response from the API
					window.parent.location = "admin.php?Pg=bookingmap"
				})
				.catch(error => {
					// Handle any errors
					console.error('Error:', error);
				});
		}
	</script>

	<?php include("js.php"); ?>

	</html>
<?php

}

ob_end_flush(); ?>