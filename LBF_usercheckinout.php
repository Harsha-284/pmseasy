<?php include 'conn.php';

include 'functions.php';



if (!isset($_SESSION['groupid'])) { ?>

	<script type="text/javascript">
		window.parent.location = "login.php";
	</script>

<?php

} else if (1) { ?>

	<!DOCTYPE html>

	<html lang="en">

	<head>

		<?php include("head.php"); ?>

	</head>
	<script>
		function handleClick(hotelCode, bookingId) {

			let refund_amount = document.querySelector('input[name="refundamount"]').value;
			let refund_reason = document.querySelector('textarea[name="cancellationreason"]').value;
			const body = {
				"action": "cancel",
				"hotelCode": hotelCode,
				"channel": "cancel",
				"bookingId": bookingId,
			}

			console.log(body);


			fetch(`${base_url}/update_reservation.php`, {
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
					console.log('Success:', data);
				})
				.catch(error => {
					// Handle any errors
					console.error('Error:', error);
				});

		}
	</script>

	<body style="padding-top:0px;">

		<div class="the-box" style="padding-bottom:0px; margin-bottom:0px;">

			<form method="post" action="?id=<?= $id ?>">

				<h4 class="small-text">Check in/ Check Out</h4>

				<?php if (isset($_POST['chkid'])) {

					$chkid = dateindia($_POST['chkid']);

					$chkit = date_create_from_format("Y-m-d h:i a", $chkid . " " . $_POST['chkit'])->format("H:i");



					$row = execute("select b.id,hu.company,u.fullname,u.email,u.contact,b.checkindatetime,cast(b.checkindatetime as date)chkdtin,checkoutdatetime, cast(b.checkoutdatetime as date)chkdtout,b.hours,(b.declaredtariff-b.pcdiscount+ b.hotelst+b.hsbc+b.hkcc+b.frotelst+b.fsbc+b.fkcc+b.lt+b.sc+ b.stonsc)total,b.usercheckedin,b.usercheckedout from bookings b JOIN room_distribution rd ON rd.bookingid = b.id JOIN roomnumbers rn ON rn.id = rd.roomnumber JOIN roomtypes r ON r.id = rn.roomtype join hotels h on h.id=r.hotel join users hu on hu.id=h.user join users u on u.id=b.guestid where b.id=$id");



					if ($row['hours'] > 0 and $row['hours'] < 24)

						$stay_period = $row['hours'] . " hours";

					else {

						$num_o_nights	= date_diff(date_create($row['chkdtin']), date_create($row['chkdtout']));

						$nights = $num_o_nights->format("%a");



						$stay_period = $nights . " nights";
					}



					if ($_POST['chkod'] != "") {

						$chkod = dateindia($_POST['chkod']);

						$chkot = date_create_from_format("Y-m-d h:i a", $chkod . " " . $_POST['chkot'])->format("H:i");



						$emailbody = "Guest has just checked out from hotel. Here are his booking details<br><br>

							

							<b>#BRN :</b> " . $id . "<br><br>



							<b>Hotel Name :</b> " . $row['company'] . "<br><br>



							<b>Guest Name :</b> " . $row['fullname'] . "<br><br>

							

							<b>Email :</b> " . $row['email'] . "<br><br>

							

							<b>Contact :</b> " . $row['contact'] . "<br><br>

							

							<b>Booked Check in DateTime :</b> " . date_create($row['checkindatetime'])->format("d-M-Y h:i A") . "<br><br>



							<b>Booked Check out DateTime :</b> " . date_create($row['checkoutdatetime'])->format("d-M-Y h:i A") . "<br><br>

							

							<b>Guest Checkedin At :</b> " . date_create($row['usercheckedin'])->format("d-M-Y h:i A") . "<br><br>

							

							<b>Guest Checkedout At :</b> " . date_create_from_format("d-m-Y", $_POST['chkod'])->format("d-M-Y") . " " . $_POST['chkot'] . "<br><br>

							

							<b>Stay Period :</b> " . $stay_period . "<br><br>

							

							<b>Total Amount :</b> " . round($row['total'], 2);
					} else {

						$chkod = "0000-00-00";

						$chkot = "";



						$emailbody = "Guest has just checked in the hotel. Here are his booking details<br><br>

							

							<b>#BRN :</b> " . $id . "<br><br>



							<b>Hotel Name :</b> " . $row['company'] . "<br><br>



							<b>Guest Name :</b> " . $row['fullname'] . "<br><br>

							

							<b>Email :</b> " . $row['email'] . "<br><br>

							

							<b>Contact :</b> " . $row['contact'] . "<br><br>

							

							<b>Booked Check in DateTime :</b> " . date_create($row['checkindatetime'])->format("d-M-Y h:i A") . "<br><br>



							<b>Booked Check out DateTime :</b> " . date_create($row['checkoutdatetime'])->format("d-M-Y h:i A") . "<br><br>

							

							<b>Guest Checkedin At :</b> " . date_create_from_format("Y-m-d h:i a", $chkid . " " . $_POST['chkit'])->format("d-M-Y h:i A") . "<br><br>



							<b>Stay Period :</b> " . $stay_period . "<br><br>

							

							<b>Total Amount :</b> " . round($row['total'], 2);
					}



					$conn->query("update bookings set usercheckedin='$chkid $chkit',usercheckedout='$chkod $chkot' where id=$id");

					if ($chkod !== "0000-00-00" && $chkot !== "") {
						$checkouttime = $chkot;
					
						// Retrieve room type and hotel information
						$roomtype = execute("SELECT r.id, b.checkindatetime, b.checkoutdatetime, r.cmroomid, COUNT(rd.bookingid) AS booking_count 
											 FROM bookings b 
											 JOIN room_distribution rd ON rd.bookingid = b.id 
											 JOIN roomnumbers rn ON rd.roomnumber = rn.id 
											 JOIN roomtypes r ON rn.roomtype = r.id 
											 WHERE b.id = $id");
					
						$hotelCode = execute("SELECT u.cm_company_name 
											  FROM hotels h 
											  JOIN users u ON h.user = u.id 
											  WHERE h.id = $_SESSION[hotel]");
					
						// Create DateTime objects
						$checkindatetime = new DateTime("$chkod $checkouttime");
						$checkoutdatetime = new DateTime($roomtype['checkoutdatetime']);
						$referenceTime = new DateTime("$chkod 11:00:00");
					
						// Determine the start of the availability period
						if ($checkindatetime < $referenceTime) {
							// Use the actual checkout date for availability if checkout time is before 11:00:00
							$availabilityStartDate = $checkindatetime;
						} else {
							// Use the day after the checkout date if checkout time is at or after 11:00:00
							$availabilityStartDate = $checkindatetime->modify('+1 day');
						}
					
						// Format the dates for availability update
						$availabilityStartDateFormatted = $availabilityStartDate->format("Y-m-d");
						$checkoutdatetimeFormatted = $checkoutdatetime->format("Y-m-d");
					
						// Update availability
						$availble = search_booking($roomtype['id'], $availabilityStartDate, $checkoutdatetime, '0', '0');
						$availble += $roomtype['booking_count'];
					
						$res = updateCmAvailability(
							$availabilityStartDateFormatted,
							$checkoutdatetimeFormatted,
							$availble,
							$roomtype['cmroomid'],
							$hotelCode['cm_company_name']
						);
					
						// Update the booking record if availability update was successful
						if ($res === true) {
							$conn->query("UPDATE bookings SET checkoutdatetime = '$chkod $checkouttime' WHERE id = $id");
						}
					}
					

					alertbox("success", "Check in/out data updated.");



					myemail("booking@frotels.com", "Guest Checkedin/out", $emailbody, "", "", "booking@frotels.com");
				}



				$row = execute("select usercheckedin,usercheckedout from bookings where id=$id");

				$checkindate=new DateTime($_GET['date']);

				if ($row['usercheckedin'] == "0000-00-00 00:00:00" or $row['usercheckedin'] == "") {

					$chkid = $checkindate->format('d-m-Y');
					$chkit = date("h:i a"); 
					$chkoutdisplay = "none";
				} else {

					$chkid = date_create($row['usercheckedin'])->format("d-m-Y");

					$chkit = date_create($row['usercheckedin'])->format("h:i a");

					$chkoutdisplay = "block";
				}



				if ($row['usercheckedout'] == "0000-00-00 00:00:00" or $row['usercheckedout'] == "") {

					$chkod = "";

					$chkot = "";
				} else {

					$chkod = date_create($row['usercheckedout'])->format("d-m-Y");

					$chkot = date_create($row['usercheckedout'])->format("h:i a");
				} ?>

				<?php


				?>

				<div class="row">

					<div class="col-xs-7">


						<div class="form-group">

							<label>Check in Date</label>

							<input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" name="chkid" value="<?= $chkid ?>" required>

						</div>

					</div>

					<div class="col-xs-5">

						<div class="form-group">

							<label>Check in Time</label>

							<input type="text" class="form-control timepicker" name="chkit" value="<?= $chkit ?>" required>

						</div>

					</div>

					<div id="chkotblock" style="display:<?= $chkoutdisplay ?>">

						<div class="col-xs-7">

							<div class="form-group">

								<label>Check out Date</label>

								<input type="text" class="form-control datepicker" data-date-format="dd-mm-yyyy" name="chkod" value="<?= $chkod ?>">

							</div>

						</div>

						<div class="col-xs-5">

							<div class="form-group">

								<label>Check out Time</label>

								<input type="text" class="form-control timepicker" name="chkot" value="<?= $chkot ?>">

							</div>

						</div>

					</div>

					<div class="col-xs-12">

						<div class="form-group">

							<input type="submit" class="btn btn-primary" value="OK">

						</div>

					</div>

				</div>

			</form>

		</div>

	</body>

	<?php include("js.php"); ?>

	</html>

<?php

}

ob_end_flush(); ?>