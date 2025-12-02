<?php include 'conn.php';
include 'udf.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Sentir, Responsive admin and dashboard UI kits template">
	<meta name="keywords" content="admin,bootstrap,template,responsive admin,dashboard template,web apps template">
	<meta name="author" content="Ari Rusmanto, Isoh Design Studio, Warung Themes">
	<title>Blank Page | AWS - Admin Panel</title>
	<!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- PLUGINS CSS -->
	<link href="css/weather-icons.min.css" rel="stylesheet">
	<link href="css/prettify.min.css" rel="stylesheet">
	<link href="css/magnific-popup.min.css" rel="stylesheet">
	<link href="css/owl.carousel.min.css" rel="stylesheet">
	<link href="css/owl.theme.min.css" rel="stylesheet">
	<link href="css/owl.transitions.min.css" rel="stylesheet">
	<link href="css/chosen.min.css" rel="stylesheet">
	<link href="css/all.css" rel="stylesheet">
	<link href="css/datepicker.min.css" rel="stylesheet">
	<link href="css/bootstrap-timepicker.min.css" rel="stylesheet">
	<link href="css/bootstrapValidator.min.css" rel="stylesheet">
	<link href="css/summernote.min.css" rel="stylesheet">
	<link href="css/bootstrap-markdown.min.css" rel="stylesheet">
	<link href="css/bootstrap.datatable.min.css" rel="stylesheet">
	<link href="css/morris.min.css" rel="stylesheet">
	<link href="css/c3.min.css" rel="stylesheet">
	<link href="css/slider.min.css" rel="stylesheet">
	<link href="css/salvattore.css" rel="stylesheet">
	<link href="css/toastr.css" rel="stylesheet">
	<link href="css/fullcalendar.css" rel="stylesheet">
	<link href="css/fullcalendar.print.css" rel="stylesheet" media="print">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- MAIN CSS (REQUIRED ALL PAGE)-->
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/style-responsive.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/Lightbox.css">
	<link rel="stylesheet" type="text/css" href="css/style_map.css">
	<?php include("head.php"); ?>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>
<style>
	/* For Webkit browsers (Chrome, Safari, Edge) */
	input[type=number]::-webkit-inner-spin-button,
	input[type=number]::-webkit-outer-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	/* For Firefox */
	input[type=number] {
		-moz-appearance: textfield;
	}

	label {
		margin-bottom: 0px;
		font-weight: 500
	}

	h5 {
		margin: 3px 0px 0px 0px;
		font-size: 15px
	}

	hr {
		margin: 5px 5px;
		border-top: 2px solid #eee;
	}

	.d-flex {
		display: flex;
		/* padding: 0 10px; */
		justify-content: space-between;
	}

	.w-40 {
		width: 40%;
	}

	.w-60 {
		width: 60%;
	}

	.w-50 {
		width: 50%;
	}

	.h-155 {
		height: 155px;
	}

	.bill-heading {
		padding: 10px;
		background: #e8e9ee;
		margin-top: 0;
	}

	.border {
		border: 1px solid #e8e9ee;
	}

	.inner-content {
		padding: 10px;
	}

	.inner-content p {
		margin: 0 0 2px;
	}

	.details {
		display: flex;
		padding: 0 15px;
	}

	.table-section {
		padding: 0 10px 10px;
		overflow-x: scroll;
	}

	.inner-container {
		border: 1px solid #dcdcdc;
		padding-bottom: 10px;
	}

	.box-inp {
		width: 50px;
	}
</style>
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
				console.log(res);
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

	async function deletePaymentMode(id) {
		$.ajax({
			type: 'POST',
			url: 'bookingajax.php',
			data: {
				action: "delete_payment_mode",
				pamentmodeid: id,
			},
			success: function(response) {
				res = JSON.parse(response)
				location.reload()
			}
		});
	}

	async function deleteadditionalservices(id, bookingid) {
		$.ajax({
			type: 'POST',
			url: 'bookingajax.php',
			data: {
				action: "delete_guest_additional_service",
				guest_service_id: id,
				bookingid: bookingid,
				user: <?= $_SESSION['id'] ?>,
				hotel: <?= $_SESSION['hotel'] ?>
			},
			success: function(response) {
				res = JSON.parse(response)
				location.reload()
			},
			error: function(err) {
				console.log('err', err);
			}
		});
	}

	function getDatesBetween(startDate, endDate) {
		let dates = [];
		let currentDate = new Date(startDate);
		endDate = new Date(endDate);

		// Ensure endDate is greater than startDate
		if (endDate <= currentDate) {
			return dates; // Return an empty array if the range is invalid
		}

		while (currentDate < endDate) {
			dates.push(new Date(currentDate).toISOString().split('T')[0]);
			currentDate.setDate(currentDate.getDate() + 1);
		}

		return dates.length;
	}
</script>

<body style="padding:0px; ">
	<script>
		console.log(<?= $_GET['id'] ?>);
	</script>
	<div class="main-card">
		<?php
		$userInfo = execute("select u.company,u.cm_company_name,r.roomtype,r.id,rn.roomnumber,c.city from roomnumbers rn left join roomtypes r on r.id=rn.roomtype join hotels h on h.id=r.hotel left join users u on u.id=h.user left join locations l on l.id=h.location left join cities c on c.id=l.city where rn.id=$_GET[id]");
		$bdate = date_create($_GET['date']);
		$now = date_create(date("Y-m-d H:i"));
		$a = date_create($bdate->format("Y-m-d") . " " . '12' . ":" . '00');

		$row = execute("select b.id,b.status,b.intialtariff,b.hours,b.reg_date,u.id_proof_path,u.id_proof,(declaredtariff-pcdiscount+hotelst+hsbc+hkcc+frotelst+fsbc+fkcc+lt+sc+stonsc)total,u.fullname,u.contact,u.email,u.address1, b.trav_name,b.checkindatetime,b.checkoutdatetime,b.usercheckedin,b.usercheckedout from bookings b left join room_distribution rd on b.id=rd.bookingid left join users u on u.id=b.guestid where (b.paid=1 or ticker>'" . $now->format("Y-m-d H:i") . "') and (b.status='Scheduled' or b.status='Cancelled' or b.status='Refunded') and rd.roomnumber=$_GET[id] 
								and 
			(
			b.checkindatetime<='" . $a->format("Y-m-d H:i") . "' and b.checkoutdatetime>='" . $a->format("Y-m-d H:i") . "'
		)");
		// print_r($row);
		if ($row && isset($row['id'])) {

			$bookingId = intval($row['id']);

			$no_of_room = execute("
        SELECT COUNT(*) AS cnt 
        FROM room_distribution 
        WHERE bookingid = $bookingId
    ");

			$checkindate = new DateTime($row['checkindatetime']);
			$checkoutdate = new DateTime($row['checkoutdatetime']);
		} else {
			// No booking found for this room at this time
			$bookingId = 0;
			$no_of_room = ['cnt' => 0];
		}
		$checkindate = new DateTime($row['checkindatetime']);
		$checkoutdate = new DateTime($row['checkoutdatetime']);
		?>
		<div class="inner-container">
			<div class="brn-card" style="display: flex;justify-content: space-between;align-items: center; padding:10px 25px; background: #dcdcdc; margin-bottom:10px;">
				<div>
					<h6 style="  font-size:17px;font-weight: 700;line-height: 100%;" class="">BRN Number: <span style=" color: #fb3c3c;"><?= $row['id'] ?></span></h6>
				</div>
				<div>
					<div>
						<?php
						// Check the user status and show appropriate buttons
						if ($row['usercheckedin'] == "0000-00-00 00:00:00" || $row['usercheckedin'] == "") {
							// User has not checked in yet, show 'Check in' button

							$todaysDate = date("d-m-Y"); // Get today's date
							if ($todaysDate === $checkindate->format("d-m-Y")) {
								$btnvalue = "<a href='LBF_usercheckinout.php?id=" . $row['id'] . "&date=" . $checkindate->format("d-m-Y") . "' class='fancybox2 fancybox.iframe' style='color:#FFFFFF'><button class='btn available'>Check in</button></a>";
							}
						} else if ($row['usercheckedout'] == "0000-00-00 00:00:00" || $row['usercheckedout'] == "") {
							// User is checked in but has not checked out yet, show 'Check out' button and check-in time
							$checkInTime = date("d-m-Y, g:i a", strtotime($row['usercheckedin'])); // Format the check-in time
							$btnvalue = "
									<div>
									<a href='LBF_usercheckinout.php?id=" . $row['id'] . "' class='fancybox2 fancybox.iframe' style='color:#FFFFFF'>
									<button class='filter-action-btn2' style='margin-left: 108px;' >Check out</button>
									</a>
							<small style='display: flex; justify-content: end;' >Checked in at: " . $checkInTime . "</small> 
									</div>
								";
						} else {
							// User has already checked out, show 'Checked out' status
							$checkInTime = date("d-m-Y, g:i a", strtotime($row['usercheckedin']));
							$checkOutTime = date("d-m-Y, g:i a", strtotime($row['usercheckedout']));
							$btnvalue = "
						<div>
						<a href='LBF_usercheckinout.php?id=" . $row['id'] . "' class='fancybox2 fancybox.iframe' style='color:#FFFFFF; margin-left: 108px;'><button class='btn booked'>Checked out</button></a>
							<small style='display: flex; justify-content: end;' >Checked in at: " . $checkInTime . "</small> 
							<small style='display: flex; justify-content: end;' >Checked out at: " . $checkOutTime . "</small> 
						</div>";
						}
						?>

						<?= $btnvalue ?>

						<!-- Only show 'No show' and 'Cancel Booking' buttons if the user has NOT checked in -->
						<?php
						$todaysDate = date("d-m-Y"); // Get today's date in the format "d-m-Y"
						$checkindate = new DateTime($row['checkindatetime']); // Assuming $row['checkin'] is the check-in date

						// First condition for "No show"
						if (($row['usercheckedin'] == "0000-00-00 00:00:00" || $row['usercheckedin'] == "") && $todaysDate == $checkindate->format("d-m-Y")): ?>
							<a class="btn booked" onclick="noshow('<?= $userInfo['cm_company_name'] ?>', '<?= $row['id'] ?>')">No show</a>
						<?php
						endif;

						// Additional condition for "Cancel Booking"
						if (($row['usercheckedin'] == "0000-00-00 00:00:00" || $row['usercheckedin'] == "") && $todaysDate < $checkindate->format('d-m-Y')): ?>

							<button onClick="handleClick('<?= $row['id'] ?>')" class="btn booked">Cancel Booking</button>
						<?php
						endif; ?>

					</div>
				</div>
			</div>
			<!-- <hr> -->

			<div class="brn-card" style="padding:5px 0px">
				<div style="padding: 0 15px;">
					<div class="border">
						<div class="bill-heading d-flex justify-content-between">Client Details 
						</div>

						<div class="inner-content">
							<div class="d-flex">
								<div class="w-60">
									<h5><strong>Guest Name:</strong> <?= $row['fullname'] ?></h5>
								</div>
								<div class="w-40">
									<h5><strong>Mobile No:</strong> +91 <?= $row['contact'] ?></h5>
								</div>
							</div>
							<div class="d-flex">
								<div class="w-60">
									<h5><strong>Email:</strong> <?= $row['email'] ?></h5>
								</div>
								<div class="w-40">
									<h5><strong>Address:</strong> <?= $row['address1'] ?></h5>
								</div>
							</div>
							<div>
								<div>
									<h5><strong>Id Proof:</strong> <?= $row['id_proof'] ?> <a href="uploads/medium/<?= $row['id_proof_path'] ?>" target="_blank"><i class="fa fa-print" style="color: #3BAFDA; cursor: pointer"></i></a></h5>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- <div>
				<button class="btn btn-primary">done</button>
				<button class="btn btn-primary">done</button>
				<button class="btn btn-primary">done</button>
			</div> -->
			</div>
			<div class="details">
				<div class="brn-card w-50" style="padding:5px 0px">
					<div>
						<div class="border">
							<h6 class="bill-heading">Room Details</h6>
							<div class="inner-content h-155">
								<p><strong>Check-in Date:</strong> (<?= $checkindate->format("d-m-Y") ?>)</p>
								<p><strong>Check-out Date:</strong> (<?= $checkoutdate->format("d-m-Y") ?>)</p>
								<p><strong>Room Type:</strong> <?= $userInfo['roomtype'] ?></p>
								<p><strong>No. of Rooms:</strong> <?= $no_of_room['cnt'] ?></p>
								<p><strong>No. of Nights: </strong><span style="display: inline;" id="number-of-nights"></span></p>
							</div>
						</div>
					</div>
					<!-- <div>
				<button class="btn btn-primary">done</button>
				<button class="btn btn-primary">done</button>
				<button class="btn btn-primary">done</button>
			</div> -->
				</div>
				<div class="brn-card w-50" style="padding: 5px 0px;">
					<div>
						<div class="border">
							<h6 class="bill-heading">Payment Details</h6>
							<div class="inner-content h-155">
								<p style="display: flex;justify-content: space-between;"><strong>Total deal amount</strong> <span>₹ <span class="box-inp"><?= $row['intialtariff'] ?></span></span></p>
								<p style="display: flex;justify-content: space-between;"><strong>Total amount of only services</strong><span>₹ <span class="box-inp" id="total-amount-of-only-services">0</span></span></p>
								<p style="display: flex;justify-content: space-between;"><strong>Total amount with services</strong><span>₹ <span class="box-inp" id="total-amount-with-services">0</span></span></p>
								<p style="display: flex;justify-content: space-between;"><strong>Discount</strong><span>₹ <span class="box-inp" id="total-discount">0</span></span></p>
								<p style="display: flex;justify-content: space-between;"><strong>Total paid amount</strong><span>₹ <span class="box-inp" id="total-paid-amount">0</span></span></p>
								<p style="display: flex;justify-content: space-between;"><strong>Total outstand</strong><span>₹ <span class="box-inp" id="outstand-amount">0</span></span></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="brn-card" style="padding: 5px 0px;">
				<div style="padding: 0 15px;">
					<div class="border">
						<h6 class="bill-heading">Payment Trail</h6>
						<div class="table-section">
							<?php
							$reg_date = new DateTime($row['reg_date']);
							$payment = $conn->query("select id,payment_type, date_of_payment, txnid, cheque_bank, cheque_no, cheque_date, comment,amount,discount_type,discount_percent,discount_flat from payment_mode where bookingid=$row[id] and deleted=0 order by id desc;");
							$payment_count = $payment->num_rows;
							if($payment_count > 0){ ?>
							<table id="payment-table" class="table table-bordered br-none mg-top" style="border-collapse: collapse; width: 100%; border: none; margin: 5px 0px">
								<thead style="border-bottom: 1px solid #ddd;">
									<tr class="table-color" style="border-bottom: 1px solid #ddd;border-top: 1px solid #ddd; font-size: 15px; ">
										<th class="border-none" style="font-size: 11px; border: none; ">Sr#</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Mode</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Date</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Txn id</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Cheque Bank</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Cheque no.</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Cheque Date</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Amount</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Discount</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Discount type</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Due Balance</th>
										<th class="border-none" style="font-size: 11px; border: none; text-align: center ">Note</th>
										<th class="border-none" style="font-size: 11px; border: none; text-align: center ">Edit</th>
										<th class="border-none" style="font-size: 11px; border: none; text-align: center">Receipt</th>
										<th class="border-none" style="font-size: 11px; border: none; text-align: center">Delete</th>
									</tr>
								</thead>
								<script>
									document.getElementById('number-of-nights').innerText = getDatesBetween('<?= $checkindate->format('Y-m-d') ?>', '<?= $checkoutdate->format('Y-m-d') ?>');
								</script>
								<tbody id="room-availability-body">
									<?php
									$reg_date = new DateTime($row['reg_date']);
									$payment = $conn->query("select id,payment_type, date_of_payment, txnid, cheque_bank, cheque_no, cheque_date, comment,amount,discount_type,discount_percent,discount_flat from payment_mode where bookingid=$row[id] and deleted=0 order by id desc;");
									$i = 0;
									$total_paid_amount = 0;
									$discount = 0;
									$total_outstand = $row['total'];
									while ($rs_row = $payment->fetch_assoc()) {
										// Use ternary operator to set default value if data is missing
										$payment_type = !empty($rs_row['payment_type']) ? $rs_row['payment_type'] : '-';
										$date_of_payment = !empty($rs_row['date_of_payment']) ? new DateTime($rs_row['date_of_payment']) : '-';
										$txnid = !empty($rs_row['txnid']) ? $rs_row['txnid'] : '-';
										$cheque_bank = !empty($rs_row['cheque_bank']) ? $rs_row['cheque_bank'] : '-';
										$cheque_date = $rs_row['cheque_date'] !== "0000-00-00" ? $rs_row['cheque_date'] : "-";
										$cheque_no = !empty($rs_row['cheque_no']) ? $rs_row['cheque_no'] : '-';
										$comment = !empty($rs_row['comment']) ? $rs_row['comment'] : '-';
										if (!empty($rs_row['amount'])) {
											$amount = $rs_row['amount'];
											$total_paid_amount = $total_paid_amount + $amount;
											$total_outstand = $total_outstand - $amount;
											$due_balence = $total_outstand;
										} else {
											$amount = "-";
											$total_outstand = $total_outstand - '0';
											$due_balence = $total_outstand;
										}
										if ($rs_row['discount_type'] === "flat") {
											$discount = $discount + $rs_row['discount_flat'];
											$amount = $discount;
											$total_outstand = $total_outstand - $amount;
											$due_balence = $total_outstand;
										} elseif ($rs_row['discount_type'] === "percentage") {
											$percent = $rs_row['discount_percent'];
											$discount = $discount + ($percent / 100) * $row['total'];
											$amount = $discount;
											$total_outstand = $total_outstand - $amount;
											$due_balence = $total_outstand;
										}
									?>
										<?php
										if ($payment_type !== 'payatcheckout') {
											$i++;
										?>
											<tr style="border-bottom: 1px solid #ddd;font-size: 15px">
												<th class="border-none" style="font-size: 11px; border: none; "><?= $i ?></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"><?= $payment_type ?></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"><?= $date_of_payment->format("d-m-Y") ?></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"><?= $txnid ?></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"><input type="text" value=<?= $cheque_bank ?> style="width: 50px; border: none; background-color: white; " disabled></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"><input type="text" value=<?= $cheque_no ?> style="width: 50px; border: none; background-color: white; " disabled></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"><?= $cheque_date ?></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"><input id="amount-<?= $i ?>" type="number" value=<?= $amount ?> style="width: 50px; border: none; background-color: white; " disabled></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"></th>
												<th class="border-none" style="font-size: 11px; border: none; ;"><?= $due_balence ?></th>
												<th class="border-none" style="font-size: 11px; border: none; text-align: center;"><input style="width: 55px; border: none; background-color: white; text-align: center; " type="text" value=<?= $comment ?> disabled></th>
												<th class="border-none" style="border: none; text-align: center"><a href="LBF_editpaymentdetails.php" id="edit-payment-<?= $i ?>"><i class="fa fa-edit" style="color: #3BAFDA; cursor: pointer"></i></a></th>

												<th class="border-none" style="border: none; text-align: center"><a href="LBF_paymentreceipt.php?id=<?= $_GET['id']; ?>&paymentid=<?= $rs_row['id']; ?>&date=<?= $_GET['date']; ?>&amount=<?= $amount; ?>" target="_blank" id="receipt-payment-<?= $i ?>"><i class="fa fa-print" style="color: #3BAFDA; cursor: pointer"></i></a></th>

												<th class="border-none" style="border: none;text-align: center"><a id="delete-payment-<?= $i ?>" onClick="deletePaymentMode('<?= $rs_row['id'] ?>')"><i class="fa fa-trash-o" style="color: #fb3c3c; cursor: pointer"></i></a></th>
												<script>
													document.getElementById('outstand-amount').innerText = <?= $total_outstand ?>;
													document.getElementById('total-paid-amount').innerText = <?= $total_paid_amount ?>;
													document.getElementById('total-discount').innerText = <?= $discount ?>;
													let data_to_send = JSON.stringify(<?= json_encode($rs_row) ?>)

													let editUrl = document.getElementById('edit-payment-<?= $i ?>')
													editUrl.href = `LBF_editpaymentdetails.php?data=${encodeURIComponent(data_to_send)}&isUpdate=1&id=<?= $row['id']; ?>`
												</script>
											</tr>
										<?php
										} ?>
									<?php
									} ?>
								</tbody>
							</table>
							<?php } ?>
							<div>
								<a id="booking-edit-add" href="LBF_editpaymentdetails.php?id=<?= $row['id']; ?>&isUpdate=0" class="btn available">Add Payment details </a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="brn-card" style="padding: 5px 0px;">
				<div style="padding:0 15px">
					<div class="border">
						<h6 class="bill-heading">Additional services</h6>
						<div style="padding:0 10px 10px">
							<div class="services-checkbox" style="display: flex; flex-wrap: wrap; margin-top: 10px; gap: 0px">
								<?php

								$invoice = execute("select isgst from invoice_setup where hotel='$_SESSION[hotel]'");

								$isgst = $invoice['isgst'];

								$display_gst = $isgst ? '' : 'display: none;';

								$additional_services = $conn->query("SELECT id, service,charge,gst,vat,tax FROM additional_services WHERE hotel=$_SESSION[hotel] and ishotelroomtax=0 and deleted=0");
								$i = 0;
								while ($rs_row = $additional_services->fetch_assoc()) {
									$gst = "-";
									$vat = "-";
									if ($rs_row['gst'] === "1") {
										$gst = $isgst ? $rs_row['tax'] : 0;
									} else {
										$vat = $isgst ? $rs_row['tax'] : 0;
									}
									$total = ($rs_row['charge'] + (($isgst ? $rs_row['tax'] : 0) / 100) * $rs_row['charge']);
									$i++;
								?>
									<div id="create-additional-services-<?= $i ?>" class="form-check" style="margin: 0px 15px 0px 0px">
										<input id="add-row-btn-<?= $i ?>" class="form-check-input" style="width: 16px; height: 12px; float: left; margin-right: 0px"
											type="checkbox" value="<?= $rs_row['id'] ?>"
											data-service-id="<?= $rs_row['id'] ?>"
											data-service-name="<?= $rs_row['service'] ?>"
											data-service-charge="<?= $rs_row['charge'] ?>"
											data-service-gst="<?= $gst ?>"
											data-service-vat="<?= $vat ?>"
											data-service-total="<?= $total ?>"
											onclick="toggleServiceRow(this)">

										<a id="add-row-btn-<?= $i ?>" style='color: #656D78'>
											<label class="form-check-label" for="service-<?= $rs_row['id'] ?>" style="display: inline-block; max-width: 100%; margin-bottom: 0px; font-weight: 700; font-size: 13px; cursor: pointer">
												<?= $rs_row['service'] ?> - ₹ <?= $rs_row['charge'] ?>
											</label>
										</a>
									</div>
								<?php
								} ?>
							</div>
							<table id="additional-table" class="table table-bordered br-none mg-top" style="border-collapse: collapse; width: 100%; border: none; margin: 5px 0px; display: none">
								<thead style="border-bottom: 1px solid #ddd;">
									<tr class="table-color" style="border-bottom: 1px solid #ddd;border-top: 1px solid #ddd;font-size: 15px">
										<th class="border-none" style="font-size: 11px; border: none; ">Sr#</th>
										<th class="border-none" style="font-size: 11px; border: none; ">services</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Date</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Quantity</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Charge</th>
										<th class="border-none" style="font-size: 11px; border: none; <?= $display_gst ?>">% GST</th>
										<th class="border-none" style="font-size: 11px; border: none; <?= $display_gst ?>">% VAT</th>
										<th class="border-none" style="font-size: 11px; border: none; ">Total</th>
										<th class="border-none" style="font-size: 11px; border: none; text-align: center">Edit</th>
										<th class="border-none" style="font-size: 11px; border: none; text-align: center">Receipt</th>
										<th class="border-none" style="font-size: 11px; border: none; text-align: center">Delete</th>
									</tr>
									<script>
										document.getElementById('total-amount-with-services').innerText = <?= $row['total'] ?>;
										document.getElementById('total-amount-of-only-services').innerText = <?= $row['total'] - $row['intialtariff'] ?>;
									</script>
								</thead>
								<tbody id="room-additional-service">
									<?php
									$i = 0;
									$additional_service = $conn->query("SELECT gas.additional_service_id, gas.created_at, gas.quantity, gas.id, a.service, a.charge, a.gst, a.vat, a.tax,asr.amount 
								FROM guest_additional_services gas 
								JOIN additional_services_receipt asr on asr.guest_service_id=gas.id
								JOIN additional_services a ON a.id = gas.additional_service_id 
								WHERE gas.bookingid = $row[id] AND gas.deleted = 0 
								ORDER BY gas.id DESC");
									$total_quantity = $total_charge = $total_row = 0;

									while ($rs_row = $additional_service->fetch_assoc()) {
										$gst = "-";
										$vat = "-";
										if ($rs_row['gst'] === "1") {
											$gst = $isgst ? $rs_row['tax'] : 0;
										} else {
											$vat = $isgst ? $rs_row['tax'] : 0;
										}
										$total_quantity += $rs_row['quantity'];
										$total_charge += $rs_row['charge'];

										$total = $rs_row['amount'];
										$total_row += $total;

										$i++;

										// Convert created_at date to dd-m-yy format
										$created_at_formatted = date("d-m-y", strtotime($rs_row['created_at']));
										$created_at_formatted_temp = date("Y-m-d", strtotime($rs_row['created_at']));
									?>
										<tr style="border-bottom: 1px solid #ddd;font-size: 15px">
											<th class="border-none" style="font-size: 11px;border: none;"><?= $i ?></th>
											<td class="border-none" style="font-size: 11px;border: none;"><?= $rs_row['service'] ?></td>
											<td class="border-none" style="font-size: 11px;border: none;">
												<input id="dateadd-<?= $i ?>" type="date" value="<?= $created_at_formatted_temp ?>" style="border: none; height: 19px; background-color: white;" disabled>
											</td>
											<td class="border-none" style="font-size: 11px;border: none; width:0px ">
												<input id="addquantity-<?= $i ?>" style="border: none; background-color: white; width: 50px;" type="number" aria-label="<?= $rs_row['id'] ?>" value=<?= $rs_row['quantity'] ?> oninput="changeaddedit(<?= $i ?>)" disabled>
											</td>
											<td class="border-none" style="font-size: 11px;border: none;" id="charge-<?= $i ?>"><?= $rs_row['charge'] ?></td>
											<td class="border-none" style="font-size: 11px;border: none; <?= $display_gst ?>"><?= $gst ?></td>
											<td class="border-none" style="font-size: 11px;border: none; <?= $display_gst ?>"><?= $vat ?></td>
											<td class="border-none" style="font-size: 11px;border: none;" id="total-<?= $i ?>"><?= $total ?></td>
											<td id="edit-additional-services-<?= $i ?>" class="border-none" style="border: none; text-align: center">
												<a style="text-decoration: none; " onclick="editadd(<?= $i ?>)"><i id="editaddbut-<?= $i ?>" class="fa fa-edit" style="color: #3BAFDA; cursor: pointer; display: block;"></i></a>
												<a style="text-decoration: none; " onclick="saveadd(<?= $i ?>)"><i id="editsavebut-<?= $i ?>" class="fa fa-save" style="color: #3BAFDA; cursor: pointer; display: none;"></i></a>
											</td>
											<td id="create-receipt-additional-services-<?= $i ?>" class="border-none" style="border: none; text-align: center"><a href="LBF_bill.php?id=<?= $_GET['id']; ?>&serviceid=<?= $rs_row['additional_service_id']; ?>&date=<?= $_GET['date']; ?>&guest_service_id=<?= $rs_row['id']; ?>" target="_blank"><i class="fa fa-print" style="color: #3BAFDA; cursor: pointer"></i></a></td>
											<td id="delete-additional-services-<?= $i ?>" class="border-none" style="border: none; text-align: center"><a onclick="deleteadditionalservices('<?= $rs_row['id'] ?>','<?= $row['id'] ?>')"><i class="fa fa-trash-o" style="color: #fb3c3c; cursor: pointer"></i></a></td>
										</tr>
									<?php
									} ?>
									<tr id="total-row" style="border-bottom: 1px solid #ddd;font-size: 15px; background-color: black; color: white;  ">
										<th class="border-none" style="font-size: 11px;border: none;">Total</th>
										<td class="border-none" style="font-size: 11px;border: none;">-</td>
										<td class="border-none" style="font-size: 11px;border: none;">-</td>
										<td class="border-none" style="font-size: 11px;border: none;"><b><?= $total_quantity ?></b></td>
										<td class="border-none" style="font-size: 11px;border: none;"><b><?= $total_charge ?></b></td>
										<td class="border-none" style="font-size: 11px;border: none;<?= $display_gst ?>">-</td>
										<td class="border-none" style="font-size: 11px;border: none;<?= $display_gst ?>">-</td>
										<td class="border-none" style="font-size: 11px;border: none;" id="total-row-total"><b><?= $total_row ?></b></td>
										<td class="border-none" style="font-size: 11px;border: none;text-align: center">-</td>
										<td class="border-none" style="font-size: 11px;border: none;text-align: center">-</td>
										<td class="border-none" style="font-size: 11px;border: none;text-align: center">-</td>
									</tr>
								</tbody>

							</table>
							<script>
								function changeaddedit(index) {
									// Reference the quantity input field and retrieve its value
									const quantityInput = document.getElementById(`addquantity-${index}`);
									const quantity = parseFloat(quantityInput.value) || 0;

									// Retrieve the charge value for this row and parse it to a float
									const chargeCell = document.querySelector(`#charge-${index}`);
									const charge = parseFloat(chargeCell ? chargeCell.innerText : 0);

									// Calculate the new total for this specific row
									const newTotal = (quantity * charge).toFixed(2);

									// Update the total cell for this row
									const totalCell = document.querySelector(`#total-${index}`);
									if (totalCell) {
										totalCell.innerText = newTotal;
									}

									// Update the grand total for all rows
								}





								function editadd(rowId) {
									document.getElementById('addquantity-' + rowId).disabled = false;
									document.getElementById('dateadd-' + rowId).disabled = false;
									document.getElementById('addquantity-' + rowId).style.border = "1px solid #beb5b5";
									document.getElementById('dateadd-' + rowId).style.border = "1px solid #beb5b5";

									document.getElementById('editaddbut-' + rowId).style.display = "none";
									document.getElementById('editsavebut-' + rowId).style.display = "block";
								}

								function saveadd(rowId) {
									document.getElementById('addquantity-' + rowId).disabled = true;
									document.getElementById('dateadd-' + rowId).disabled = true;
									document.getElementById('addquantity-' + rowId).style.border = "none";
									document.getElementById('dateadd-' + rowId).style.border = "none";

									document.getElementById('editaddbut-' + rowId).style.display = "block";
									document.getElementById('editsavebut-' + rowId).style.display = "none";

									let quantity = document.getElementById('addquantity-' + rowId).value;
									let date = document.getElementById('dateadd-' + rowId).value;
									let id = document.getElementById('addquantity-' + rowId).ariaLabel;

									updateService(id, quantity, date)


								}

								if (<?= $i ?> > 0) {
									document.getElementById('additional-table').style.display = 'table'
								}
							</script>
							<div style="width: 100%">
								<button id="submit-btn" class="btn btn-primary" style="margin-top: 10px; float:right; visibility: hidden " onclick="addServices()">Submit</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			<?php
			$additional_services = $conn->query("SELECT id, service, charge, gst, vat, tax FROM additional_services WHERE hotel=$_SESSION[hotel] AND deleted=0");
			$i = 0;
			$now = new DateTime();
			while ($rs_row = $additional_services->fetch_assoc()) {
				$i++;
				$gst = ($rs_row['gst'] === "1") ? $rs_row['tax'] : "-";
				$vat = ($rs_row['gst'] !== "1") ? $rs_row['tax'] : "-";
				$total = ($rs_row['tax'] / 100) * $rs_row['charge'] + $rs_row['charge'];
			?>

				function toggleServiceRow(checkbox) {
					var tableBody = document.getElementById('room-additional-service');
					var serviceId = checkbox.getAttribute('data-service-id');
					var serviceName = checkbox.getAttribute('data-service-name');
					var serviceCharge = checkbox.getAttribute('data-service-charge');
					var gst = checkbox.getAttribute('data-service-gst');
					var vat = checkbox.getAttribute('data-service-vat');
					var total = checkbox.getAttribute('data-service-total');

					if (checkbox.checked) {
						// Add row
						document.getElementById('additional-table').style.display = 'table'
						var newRow = document.createElement('tr');
						newRow.setAttribute('id', 'service-row-' + serviceId);
						newRow.style.borderBottom = '1px solid #ddd';
						newRow.style.fontSize = '15px';

						// Create the cells for the new row
						var newCells = [
							`<th class="border-none" style="font-size: 11px; border: none;">${tableBody.rows.length + 1}</th>`, // Sr#
							`<td class="border-none" style="font-size: 11px; border: none;">${serviceName}</td>`, // Service
							`<td class="border-none" style="font-size: 11px; border: none;"><input type="date" id="add-date-${serviceId}" name="service-date" class="form-control" style="width: 86px; padding: 2px; height: 20px; font-size: 11px" required></td>`, // Date
							`<td class="border-none" style="font-size: 11px; border: none;"><input type="number" class="form-control quantity-input" min="1" value="1" style="appearance: textfield; margin: 0; height: 20px; width: 50px;" name="service-quantity-${serviceId}" required></td>`, // Quantity
							`<td class="border-none" style="font-size: 11px; border: none;">₹ ${serviceCharge}</td>`, // Charge
							`<td class="border-none" style="font-size: 11px; border: none; <?= $display_gst ?>">${gst}</td>`, // GST
							`<td class="border-none" style="font-size: 11px; border: none; <?= $display_gst ?>">${vat}</td>`, // VAT
							`<td class="border-none" style="font-size: 11px; border: none;" id="add-total-${serviceId}">${total}</td>`, // Total
							`<td style="display:none;" class="border-none" style="font-size: 11px; border: none;">${serviceId}</td>`, // Hidden Service ID
							`<td class="border-none" style="border: none; text-align: center"></td>`, // Receipt
							`<td class="border-none" style="border: none; text-align: center"></td>`, // Delete button
						];

						// Add cells to the row
						newRow.innerHTML = newCells.join('');
						// Add today's date by default
						tableBody.insertBefore(newRow, tableBody.firstChild);
						document.getElementById(`add-date-${serviceId}`).value = (new Date()).toISOString().slice(0, 10);

						// Quantity update event
						var quantityInput = newRow.querySelector('.quantity-input');
						quantityInput.addEventListener('input', function() {
							var quantity = parseFloat(quantityInput.value);
							document.getElementById(`add-total-${serviceId}`).innerText = (quantity * total).toFixed(2);
						});

					} else {
						// Remove row
						// document.getElementById('additional-table').style.display = 'none'
						var rowToDelete = document.getElementById('service-row-' + serviceId);
						if (rowToDelete) {
							rowToDelete.remove();
						}
					}

					const selectedServices = document.querySelectorAll('.services-checkbox input:checked').length;
					document.getElementById('submit-btn').style.visibility = selectedServices > 0 ? 'visible' : 'hidden';

					// Update row numbers after adding or removing rows
					updateSrNumbers();
				}

				function updateSrNumbers() {
					var rows = document.querySelectorAll('#room-additional-service tr');
					rows.forEach(function(row, index) {
						row.querySelector('th').innerText = index + 1;
					});
				}

				function deleteRow(deleteIcon) {
					// Find and remove the row containing the clicked delete icon
					var row = deleteIcon.closest('tr');
					if (row) row.remove();
					updateSrNumbers();
				}

			<?php
			}
			?>

			function addServices() {
				var tableBody = document.getElementById('room-additional-service');
				var newValues = [];

				// Loop through each row in the table
				for (var i = 0; i < tableBody.rows.length; i++) {
					var row = tableBody.rows[i];
					var serviceId = row.cells[8].innerText; // Hidden service ID
					var dateInput = row.cells[2].querySelector('input');
					var quantityInput = row.cells[3].querySelector('input');
					if (dateInput && quantityInput && serviceId != '') {
						newValues.push({
							date: dateInput.value,
							quantity: quantityInput.value,
							additional_service_id: serviceId
						});
					}
				}

				$.ajax({
					type: 'POST',
					url: 'bookingajax.php',
					data: {
						action: "add_guest_additional_service",
						bookingid: <?= $row['id'] ?>,
						services: newValues,
						user: <?= $_SESSION['id'] ?>,
						hotel: <?= $_SESSION['hotel'] ?>
					},
					success: function(response) {
						location.reload();
					},
					error: function(err) {
						console.log('Error:', err);
					}
				});
			}

			function updateService(id, quantity, date) {
				$.ajax({
					type: 'POST',
					url: 'bookingajax.php',
					data: {
						action: "update_user_additional_service",
						serviceid: id,
						quantity: quantity,
						date: date,
						bookingid: <?= $row['id'] ?>,
						user: <?= $_SESSION['id'] ?>,
						hotel: <?= $_SESSION['hotel'] ?>
					},
					success: function(response) {
						location.reload();
					},
					error: function(err) {
						console.log('Error:', err);
					}
				});
			}
		</script>




		<script>
			let permissions = <?= decode_permissions($_SESSION['permission']) ?>;
			let group_id = <?= $_SESSION['groupid'] ?>;


			let initialRowCount = document.getElementById('room-availability-body').rows.length;

			const totalCheckboxes = document.querySelectorAll('.services-checkbox input[type="checkbox"]').length;

			let serviceRowCount = document.getElementById('room-additional-service').rows.length;

			if (group_id >= 3 && group_id <= 4) {
				if (!permissions.booking.Edit) {
					document.getElementById("booking-edit-add").style.display = 'none';
					for (let i = 1; i <= initialRowCount; i++) {
						document.getElementById(`edit-payment-${i}`).style.pointerEvents = "none";
						document.getElementById(`edit-payment-${i}`).style.cursor = "default";

						document.getElementById(`receipt-payment-${i}`).style.pointerEvents = "none";
						document.getElementById(`receipt-payment-${i}`).style.cursor = "default";

						document.getElementById(`delete-payment-${i}`).style.pointerEvents = "none";
						document.getElementById(`delete-payment-${i}`).style.cursor = "default";
					}
				}
				// if (!permissions.additional_services.Create) {
				// 	for (let i = 1; i <= totalCheckboxes; i++) {
				// 		document.getElementById(`create-additional-services-${i}`).style.pointerEvents = "none";
				// 		document.getElementById(`create-additional-services-${i}`).style.cursor = "default";

				// 	}
				// }
				// if (!permissions.additional_services.Edit) {
				// 	for (let i = 1; i <= serviceRowCount; i++) {
				// 		document.getElementById(`edit-additional-services-${i}`).style.pointerEvents = "none";
				// 		document.getElementById(`edit-additional-services-${i}`).style.cursor = "default";

				// 		document.getElementById(`create-receipt-additional-services-${i}`).style.pointerEvents = "none";
				// 		document.getElementById(`create-receipt-additional-services-${i}`).style.cursor = "default";

				// 		document.getElementById(`delete-additional-services-${i}`).style.pointerEvents = "none";
				// 		document.getElementById(`delete-additional-services-${i}`).style.cursor = "default";
				// 	}
				// }
			}
		</script>

	</div>
</body>

<script>
	// Get today's date in YYYY-MM-DD format
	var today = new Date().toISOString().split('T')[0];

	// Set the default value of the input field to today's date
	document.getElementById('validationCustom01').value = today;

	func
</script>

</html>