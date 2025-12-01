<?php
include 'conn.php';
include 'functions.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['action']) && $_POST['action'] === 'search_booking') {
		$rt = $_POST['rt'];
		$cidt = new DateTime($_POST['cidt'] . "T12:00:00");
		$codt = new DateTime($_POST['codt'] . "T11:00:00");
		$adults = $_POST['adults'];
		$children = $_POST['children'];
		$no_of_rooms = $_POST['no_of_rooms'];


		$rs_row = execute("select count(*)cnt from roomtypes where id=$rt and active=1");
		if ($rs_row['cnt'] == 1) {
			$now = new \DateTime();
			//$ticker->modify("+15 minutes");


			$row = execute("select rp.id from rate_plans rp where rp.hotel=(select hotel from roomtypes where id=$rt) and ('" . $cidt->format("Y-m-d") . "' between rp.validfrom and rp.validto)");

			$rate_pan_validity = execute("select rpv.id  from room_rate_plans_validity rpv where rpv.rateplanid in (select id from room_rate_plans where roomtype = $rt) and '" . $cidt->format("Y-m-d") . "' between rpv.validfrom and rpv.validto;");

			$rate_plan = $rate_pan_validity['id'];


			$rpid = $row['id'];

			$rs_row = execute("
			select count(*)cnt from
			(
				select rd.roomnumber from bookings b join room_distribution rd on rd.bookingid=b.id left join roomnumbers rn on rn.id=rd.roomnumber where b.status in ('Scheduled', 'Cancelled') and rn.roomtype=$rt 
				and
				(
					(
						(
							('" . $cidt->format("Y-m-d H:i") . "'>=b.checkindatetime and '" . $codt->format("Y-m-d H:i") . "'<=b.checkoutdatetime) 
							or 
							('" . $cidt->format("Y-m-d H:i") . "'<=b.checkindatetime and '" . $codt->format("Y-m-d H:i") . "'>=b.checkoutdatetime) 
							or
							('" . $cidt->format("Y-m-d H:i") . "'<=b.checkindatetime and '" . $codt->format("Y-m-d H:i") . "'>=b.checkindatetime)
							or
							('" . $cidt->format("Y-m-d H:i") . "'<=b.checkoutdatetime and '" . $codt->format("Y-m-d H:i") . "'>=b.checkoutdatetime)
						)
						and 
						b.paid=1
					)
					
					or
					
					(
						(
							('" . $cidt->format("Y-m-d H:i") . "'>=b.checkindatetime and '" . $codt->format("Y-m-d H:i") . "'<=b.checkoutdatetime) 
							or 
							('" . $cidt->format("Y-m-d H:i") . "'<=b.checkindatetime and '" . $codt->format("Y-m-d H:i") . "'>=b.checkoutdatetime) 
							or
							('" . $cidt->format("Y-m-d H:i") . "'<=b.checkindatetime and '" . $codt->format("Y-m-d H:i") . "'>=b.checkindatetime)
							or
							('" . $cidt->format("Y-m-d H:i") . "'<=b.checkoutdatetime and '" . $codt->format("Y-m-d H:i") . "'>=b.checkoutdatetime)
						)
						and 
						b.ticker>='" . $now->format('Y-m-d H:i:s') . "'
					)
				)
				
				union all
				
				select roomnumber from blocked_roomnumbers where bdate='" . $cidt->format("Y-m-d") . "' and roomnumber in (select id from roomnumbers where roomtype=$rt)
			)f");
			$previouslybookedrooms = $rs_row['cnt'];

			$rs_row = execute("select count(*)totalrooms,r.chargeperextra from roomtypes r left join roomnumbers rn on rn.roomtype=r.id where r.id=$rt and r.active=1 and (r.adults+r.extraallowed)>=$adults and r.children>=$children");
			$totalrooms = $rs_row['totalrooms'];
			
			if ($rpid && $rate_plan) {
				if ($totalrooms - $previouslybookedrooms < $no_of_rooms) {
					echo json_encode(['total_rooms' => 0, 'extra_charge' => $rs_row['chargeperextra']]);
				} else {
					$total_room = ($totalrooms - $previouslybookedrooms);
					echo json_encode(['total_rooms' => $total_room, 'extra_charge' => $rs_row['chargeperextra']]);
				}
			} else {
				echo json_encode(['total_rooms' => 0, 'extra_charge' => $rs_row['chargeperextra']]);
			}
		} else
			echo json_encode(['total_rooms' => 0, 'extra_charge' => $rs_row['chargeperextra']]);
	} else if (isset($_POST['post_action']) && $_POST['post_action'] == 'change_password') {
		$hotelId = (int) $_POST['user_id'];
		$newPassword = trim($_POST['new_password']);
		$confirmPassword = trim($_POST['confirm_password']);
		if ($newPassword !== $confirmPassword) {
			die("Passwords do not match.");
		}
		// 1. Fetch the user_id from the hotels table
		$userQuery = $conn->prepare("SELECT user FROM hotels WHERE id = ?");
		$userQuery->bind_param("i", $hotelId);
		$userQuery->execute();
		$userQuery->bind_result($userId);
		$userQuery->fetch();
		$userQuery->close();

		if (!$userId) {
			die("Invalid hotel ID or user not found.");
		}
		// 2. Hash the password and update users table
		// $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
		$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
		$stmt->bind_param("si", $newPassword, $userId);

		if ($stmt->execute()) {
			echo "Password updated successfully.";
		} else {
			echo "Error updating password.";
		}
		exit;
	} elseif (isset($_POST['action']) && $_POST['action'] === 'register_hotel') {
		$usernamep	= str_replace("&quot;", "", $_POST['usernamep']);
		$mobilep	= str_replace("&quot;", "", $_POST['mobilep']);
		$agencyp	= str_replace("&quot;", "", $_POST['agencyp']);
		$passwordp	= str_replace("&quot;", "", $_POST['passwordp']);
		$emailp		= str_replace("&quot;", "", $_POST['emailp']);
		$hotelp		= str_replace("&quot;", "", $_POST['hotelp']);
		$addressp	= str_replace("&quot;", "", $_POST['addressp']);
		$cityp		= str_replace("&quot;", "", $_POST['cityp']);

		$result = $conn->query("select count(*)cnt from users where contact='$mobilep'");
		$row = $result->fetch_assoc();

		if ($row['cnt'] == 0) {
			if ($agencyp == "hotel") {

				$uid = insert("insert into users (groupid,fullname,password,company,email,contact,city,reg_date,address1,emailverified,mobileverified) values (2,'$usernamep','$passwordp','$hotelp','$emailp','$mobilep',35,now(),'$addressp',1,1)");

				$hotelid = insert("insert into hotels (admin,user) values (0,$uid)");

				echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
			} else if ($agencyp == "chain") {

				$uid = insert("insert into users (groupid,fullname,password,company,email,contact,city,reg_date,address1,emailverified,mobileverified) values (1,'$usernamep','$passwordp','$hotelp', '$emailp','$mobilep',35,now(),'$addressp',1,1)");

				echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
			}
		} else {
			echo json_encode(['status' => 'fail', 'message' => 'Hotel Already Exist']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'add_additional_service') {

		$services = $_POST['services'];
		$gst = $_POST['gst'];
		$charges = $_POST['charges'];
		$hsn = $_POST['hsn'];
		$vat = $_POST['vat'];
		$hotel = $_POST['hotel'];
		$tax = $_POST['tax'];

		$flag = 0;

		foreach ($services as $index => $service) {
			$serviceName = $service;
			$gstValue = $gst[$index];
			$chargeValue = $charges[$index];
			$hsnValue = $hsn[$index];
			$vatValue = $vat[$index];
			$taxValue = $tax[$index];

			$result = $conn->query("SELECT COUNT(*) AS cnt FROM additional_services WHERE service = '$serviceName' AND deleted=0");

			$row = $result->fetch_assoc();

			if ($row['cnt'] > 0) {
				$flag = 1;
				break;
			} else {
				$conn->query("INSERT INTO additional_services (service, gst, charge, hsn,hotel,vat,tax) 
							  VALUES ('$serviceName', '$gstValue', '$chargeValue', '$hsnValue','$hotel','$vatValue','$taxValue')");
			}
		}

		if ($flag == 0) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Added new additional services", 'HOTEL SERVICE');
			echo json_encode(['status' => 'success', 'message' => "Services added successfully"]);
		} else {
			echo json_encode(['status' => 'fail', 'message' => 'Duplicate service found']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'room_plan_rate_set') {
		$rates = $_POST['rate'];
		$validfrom = $_POST['validfrom'];
		$validto = $_POST['validto'];
		$ids = $_POST['id'];
		$flag = 0;

		foreach ($rates as $index => $rate) {
			$id = $ids[$index];
			$result = execute("select count(*)cnt,id from room_rate_plans_validity where rateplanid = '$id' and validfrom <= '$validfrom' AND validto >= '$validto'");

			if ($result['cnt'] > 0) {
				$flag = 1;
				break;
			} else {
				$conn->query("insert into room_rate_plans_validity (rateplanid,fulldaytariff,validfrom,validto) values ('$id','$rate','$validfrom','$validto')");
			}
		}
		if ($flag == 0) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Added room rate plan", 'HOTEL SERVICE');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'fail', 'message' => 'Rate Already Exsist']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'seasonal_plan_rate_set') {
		$rate = $_POST['rate'];
		$validfrom = $_POST['validfrom'];
		$validto = $_POST['validto'];
		$id = $_POST['id'];
		$flag_season = 0;

		$hotelcode = execute("select u.cm_company_name, h.user from hotels h JOIN users u ON h.user = u.id where h.id = $_SESSION[hotel]");

		$cmquery = execute("select r.cmroomid,rp.cm_rate_plan from room_rate_plans rp join roomtypes r on r.id=rp.roomtype where rp.id='$id'");

		$result = execute("select count(*)cnt,id from seasonal_rate_plans_validity where rateplanid = '$id' and validfrom <= '$validfrom' AND validto >= '$validto'");

		if ($result['cnt'] > 0) {
			$flag_season = 1;
		} else {
			$conn->query("insert into seasonal_rate_plans_validity (rateplanid,fulldaytariff,validfrom,validto) values ('$id','$rate','$validfrom','$validto')");
			$result = updateSeasonalCmRate($hotelcode['cm_company_name'], $validfrom, $validto, $rate, $cmquery['cmroomid'], $cmquery['cm_rate_plan']);
		}
		if ($flag_season == 0 && $result == 1) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Added seasonal rate plan", 'HOTEL SERVICE');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'fail', 'message' => 'Rate Already Exist']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'update_cancel_request') {
		$bookingid = $_POST['bookingid'];
		$result = $conn->query("update bookings set status='Cancelled' where id=$bookingid");

		if ($result) {
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'block_room_number') {
		$bdate = $_POST['bdate'];
		$roomtypeid = $_POST['roomtypeid'];
		$limit = $_POST['limit'];
		$available = $_POST['available'];

		// Fetch hotel code and room type name
		$hotelcode = execute("SELECT u.cm_company_name, h.user FROM hotels h JOIN users u ON h.user = u.id WHERE h.id = $_SESSION[hotel]");
		$roomtypename = execute("SELECT cmroomid FROM roomtypes WHERE id=$roomtypeid");

		// Get the current number of blocked rooms
		$currentBlockedCount = $conn->query("
			SELECT COUNT(br.roomnumber) AS blockedCount 
			FROM blocked_roomnumbers br 
			JOIN roomnumbers rn ON br.roomnumber = rn.id 
			WHERE br.bdate = '$bdate' AND rn.roomtype = $roomtypeid
		")->fetch_assoc();

		$currentBlocked = (int)$currentBlockedCount['blockedCount'];
		$newBlockedCount = $limit; // The new limit provided from the input

		// If we need to block more rooms
		if ($newBlockedCount > $currentBlocked) {
			$difference = $newBlockedCount - $currentBlocked;

			$result = $conn->query("INSERT INTO blocked_roomnumbers (bdate, roomnumber) 
									SELECT '$bdate', id 
									FROM roomnumbers 
									WHERE roomtype = $roomtypeid
									AND id NOT IN (
										SELECT roomnumber FROM blocked_roomnumbers WHERE bdate = '$bdate'
									)
									LIMIT $difference;");
		}
		// If we need to unblock rooms
		elseif ($newBlockedCount < $currentBlocked) {
			$difference = $currentBlocked - $newBlockedCount;

			// Fetch room numbers to delete
			$roomNumbersToDelete = $conn->query("
				SELECT br.roomnumber 
				FROM blocked_roomnumbers br
				JOIN roomnumbers rn ON br.roomnumber = rn.id
				WHERE br.bdate = '$bdate' AND rn.roomtype = $roomtypeid
				LIMIT $difference;
			");

			// Fetch the room numbers into an array
			$roomNumbersArray = [];
			while ($row = $roomNumbersToDelete->fetch_assoc()) {
				$roomNumbersArray[] = $row['roomnumber'];
			}

			if (!empty($roomNumbersArray)) {
				// Convert the array into a comma-separated string for deletion
				$roomNumbers = implode(',', $roomNumbersArray);

				// Delete the corresponding rows
				$result = $conn->query("
					DELETE FROM blocked_roomnumbers 
					WHERE roomnumber IN ($roomNumbers)
					AND bdate = '$bdate'
				");
			}
		}

		// Check if the operation was successful and send a response
		if ($result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Blocked hotel room", 'HOTEL SERVICE');
			$res = updateCmAvailability($bdate, $bdate, $available, $roomtypename['cmroomid'], $hotelcode['cm_company_name']);
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'update_additonal_services') {
		$serviceid = $_POST['serviceid'];
		$charges = $_POST['charges'];
		$service = $_POST['service'];
		$gst = $_POST['gst'];
		$vat = $_POST['vat'];
		$tax = $_POST['tax'];
		$hsn = $_POST['hsn'];

		$result = $conn->query("UPDATE additional_services SET service = '$service', charge = $charges, updated_at =CURRENT_TIMESTAMP, tax = $tax, gst = '$gst', vat = '$vat', hsn = '$hsn' WHERE id = $serviceid");

		if ($result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Updated hotel additonal services", 'HOTEL SERVICE');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	}
	if (isset($_POST['action']) && $_POST['action'] === 'add_hotel_room_setup') {
		$hotel = $_POST['hotel'];
		$charge = $_POST['charge'];
		$service = $_POST['service'];
		$gst = $_POST['gst'];
		$vat = $_POST['vat'];
		$tax = $_POST['tax'];
		$hsn = $_POST['hsn'];

		$stmt = $conn->prepare("SELECT count(*) as cnt, id FROM additional_services WHERE hotel = ? AND ishotelroomtax = 1");
		$stmt->bind_param('s', $hotel);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();

		if ($row['cnt'] > 0) {
			$stmt = $conn->prepare("UPDATE additional_services SET service = ?, charge = ?, updated_at = CURRENT_TIMESTAMP, tax = ?, gst = ?, vat = ?, hsn = ? WHERE id = ?");
			$stmt->bind_param('ssssssi', $service, $charge, $tax, $gst, $vat, $hsn, $row['id']);
		} else {
			$stmt = $conn->prepare("INSERT INTO additional_services (service, gst, charge, hsn, hotel, vat, tax, ishotelroomtax) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
			$stmt->bind_param('sssssss', $service, $gst, $charge, $hsn, $hotel, $vat, $tax);
		}

		$execute_result = $stmt->execute();

		if ($execute_result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Added hotel room setup", 'HOTEL SERVICE');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data', 'error' => $conn->error]);
		}

		$stmt->close();
	} elseif (isset($_POST['action']) && $_POST['action'] === 'add_payment_mode') {
		$payment_type = $_POST['payment_type'];
		$date_of_payment = $_POST['date_of_payment'];
		$txnid = $_POST['txnid'];
		$cheque_bank = $_POST['cheque_bank'];
		$cheque_no = $_POST['cheque_no'];
		$cheque_date = $_POST['cheque_date'];
		$comment = $_POST['comment'];
		$amount_inp = $_POST['amount_inp'];
		$discount_type = $_POST['discount_type'];
		$flat_discount = $_POST['flat_discount'];
		$percent_discount = $_POST['percent_discount'];
		$bookingid = $_POST['bookingid'];

		$paymentmodeid = insert("insert into payment_mode (payment_type, date_of_payment, txnid, cheque_bank, cheque_no, cheque_date, comment,bookingid,amount,discount_flat,discount_percent,discount_type)
                values ('$payment_type', '$date_of_payment', '$txnid', '$cheque_bank', '$cheque_no', '$cheque_date', '$comment','$bookingid','$amount_inp','$flat_discount','$percent_discount','$discount_type')");

		$result = $conn->query("insert into payment_mode_receipt (paymentmodeid,bookingid,amount)
                values ('$paymentmodeid', '$bookingid','$amount_inp')");

		if ($result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Added new payment details for FR$bookingid", 'BOOKINGS');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'update_payment_mode') {
		// Collect data from POST request
		$payment_type = $_POST['payment_type'];
		$date_of_payment = $_POST['date_of_payment'];
		$txnid = $_POST['txnid'];
		$cheque_bank = $_POST['cheque_bank'];
		$cheque_no = $_POST['cheque_no'];
		$cheque_date = $_POST['cheque_date'];
		$comment = $_POST['comment'];
		$amount_inp = $_POST['amount_inp'];
		$discount_type = $_POST['discount_type'];
		$flat_discount = $_POST['flat_discount'];
		$percent_discount = $_POST['percent_discount'];
		$bookingid = $_POST['bookingid'];
		$paymentmodeid = $_POST['paymentmodeid']; // Ensure you get the payment mode ID for updating

		// Check if the record exists
		$checkQuery = $conn->query("SELECT * FROM payment_mode WHERE id = '$paymentmodeid'");

		if ($checkQuery && $checkQuery->num_rows > 0) {
			// Update existing record
			$updateQuery = $conn->query("
				UPDATE payment_mode
				SET 
					payment_type = '$payment_type',
					date_of_payment = '$date_of_payment',
					txnid = '$txnid',
					cheque_bank = '$cheque_bank',
					cheque_no = '$cheque_no',
					cheque_date = '$cheque_date',
					comment = '$comment',
					bookingid = '$bookingid',
					amount = '$amount_inp',
					discount_flat = '$flat_discount',
					discount_percent = '$percent_discount',
					discount_type = '$discount_type'
				WHERE id = '$paymentmodeid'
			");

			// Update related receipt record
			$updateReceiptQuery = $conn->query("
				UPDATE payment_mode_receipt
				SET 
					amount = '$amount_inp'
				WHERE paymentmodeid = '$paymentmodeid' AND bookingid = '$bookingid'
			");

			// Check if updates were successful
			if ($updateQuery && $updateReceiptQuery) {
				add_hotel_task_log($_POST['user'], $_POST['hotel'], "Updated payment details for FR$bookingid", 'BOOKINGS');
				echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Record not found']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'delete_hotel_staff') {
		$hotel_staff_id = $_POST['id'];
		$result = $conn->query("update hotel_staff set deleted=1 where id='$hotel_staff_id'");

		if ($result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], 'Deleted existing hotel staff', 'HOTEL STAFF');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'update_hotel_staff') {
		$hotel_staff_id = $_POST['id'];
		$hotel_staff_permission = $_POST['permission'];
		$result = $conn->query("update hotel_staff set permission='$hotel_staff_permission' where id='$hotel_staff_id'");

		if ($result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], 'Updated existing hotel staff', 'HOTEL STAFF');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'add_hotel_staff') {
		$fullname = $_POST['fullname'];
		$contact = $_POST['contact'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$address = $_POST['address'];
		$user_status = $_POST['user_status'];
		$permission = $_POST['permission'];
		$hotel = $_POST['hotel'];
		$user = $_POST['user'];

		// Insert into users table and get the user_id
		$user_id = insert("INSERT INTO users (groupid, fullname, password, email, contact, address1,emailverified,mobileverified)
		  VALUES ('$user_status', '$fullname', '$password', '$email', '$contact', '$address','1','1')");

		if ($user_id) {
			// Prepare insert statement for hotel_staff table
			$stmt = $conn->prepare("INSERT INTO hotel_staff (user_id, hotel_id, permission) VALUES (?, ?, ?)");
			$stmt->bind_param("iis", $user_id, $hotel, $permission);  // "i" for integer, "s" for string (permission)

			// Execute the statement and check for success
			if ($stmt->execute()) {
				add_hotel_task_log($user, $hotel, 'added new hotel staff', 'HOTEL STAFF');
				echo json_encode(['status' => 'success', 'message' => 'Data added successfully']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Failed to insert data into hotel_staff']);
			}

			$stmt->close();
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to insert user data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'delete_payment_mode') {
		$paymentmodeid = $_POST['pamentmodeid'];
		$result = $conn->query("update payment_mode set deleted=1 where id='$paymentmodeid'");

		if ($result) {
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'assign_roomnumbers') {
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);
		$bookingid = $_POST['bookingid'];
		$checkindate = new DateTime($_POST['checkindate']);
		$checkoutdate = new DateTime($_POST['checkoutdate']);
		$roomnumberids = $_POST['roomnumberids']; //roomnumber id which need to swap 
		$roomdisids = $_POST['roomdisids']; //room_distribution id which will swap

		$booking_count = execute("SELECT COUNT(*) AS count 
        FROM room_distribution 
        WHERE bookingid = '$bookingid' AND isroomassigned = 1");

		if ($booking_count['count'] == 0) {
			$chkid = $checkindate->format('Y-m-d');  // '0000-00-00' format
			$chkit = date("H:i:s");
			$res = $conn->query("update bookings set usercheckedin='$chkid $chkit' where id=$bookingid");
		}

		foreach ($roomnumberids as $index => $roomnumberid) {
			$roomdisid = $roomdisids[$index];

			$booking_exsist = execute("SELECT rd.id 
								FROM bookings b 
								JOIN room_distribution rd ON rd.bookingid = b.id 
								WHERE rd.roomnumber = $roomnumberid
								AND (
									('" . $checkindate->format("Y-m-d H:i") . "' < b.checkoutdatetime AND '" . $checkoutdate->format("Y-m-d H:i") . "' > b.checkindatetime)
								);");

			if ($booking_exsist != NULL) {
				$curr_roomnumberid = execute("select roomnumber from room_distribution where id='$roomdisid'");

				$conn->query("update room_distribution set roomnumber='$curr_roomnumberid[roomnumber]' where id='$booking_exsist[id]'");

				$conn->query("update room_distribution set isRoomAssigned=1,roomnumber='$roomnumberid' where id='$roomdisid'");
			} else {
				$conn->query("update room_distribution set isRoomAssigned=1,roomnumber='$roomnumberid' where id='$roomdisid'");
			}
		}

		if (1) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Assigned Room Number for FR$bookingid", 'BOOKINGS');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'add_invoice') {
		$customergstin = $_POST['customergstin'];
		$dump_data = json_encode($_POST['dump_data']); // Ensure this is valid JSON
		$bookingid = $_POST['bookingid'];
		$hotel = $_POST['hotel'];

		$invoice_info = execute("SELECT start_invoice_no,invoice_prefix from invoice_setup where hotel=$hotel");

		$maxinvoicenumber = execute("SELECT MAX(invoice_number) AS max_invoice_number FROM invoices WHERE hotel = $hotel");

		$nextInvoiceNumber = $maxinvoicenumber['max_invoice_number'] ? $maxinvoicenumber['max_invoice_number'] + 1 : $invoice_info['start_invoice_no'];

		// Prepared statement to check if record exists
		$stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM invoices WHERE bookingid = ?");
		$stmt->bind_param("i", $bookingid);
		$stmt->execute();
		$stmt->bind_result($cnt);
		$stmt->fetch();
		$stmt->close();

		if ($cnt) {
			// Update existing record
			$stmt = $conn->prepare("UPDATE invoices SET customer_gstin = ?, invoice_data = ? WHERE bookingid = ?");
			$stmt->bind_param("ssi", $customergstin, $dump_data, $bookingid);
		} else {
			// Insert new record
			$stmt = $conn->prepare("INSERT INTO invoices (bookingid, reg_date, customer_gstin, invoice_data, hotel, invoice_number) VALUES (?, NOW(), ?, ?, ?, ?)");
			$stmt->bind_param("issii", $bookingid, $customergstin, $dump_data, $hotel, $nextInvoiceNumber);
		}

		$result = $stmt->execute();

		if ($result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Created invoices for FR$bookingid", 'INVOICES');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}

		$stmt->close();
		$conn->close();
	} elseif (isset($_POST['action']) && $_POST['action'] === 'check_extra_person_allowed') {
		$roomTypeId = $_POST['id'];
		$adults = $_POST['adult'];
		$children = $_POST['child'];
		$roomQuantity = $_POST['rooms']; // Number of rooms selected

		// Execute the query to get the room type details
		$roomtype = execute("SELECT adults, children, extraallowed, chargeperextra FROM roomtypes WHERE id='$roomTypeId'");

		if ($roomtype) {
			$maxAdultsPerRoom = $roomtype['adults'];
			$maxChildrenPerRoom = $roomtype['children'];
			$maxExtraPersonsPerRoom = $roomtype['extraallowed'];
			$extraChargePerPerson = $roomtype['chargeperextra'];

			// Calculate the total allowed limits based on the number of rooms
			$totalAllowedAdults = $maxAdultsPerRoom * $roomQuantity;
			$totalAllowedChildren = $maxChildrenPerRoom * $roomQuantity;
			$totalAllowedExtraPersons = $maxExtraPersonsPerRoom * $roomQuantity;

			// Calculate base adults and children (within allowed limits)
			$baseAdults = min($adults, $totalAllowedAdults);
			$baseChildren = min($children, $totalAllowedChildren);

			// Calculate extra adults and children
			$extraAdults = max(0, $adults - $baseAdults);
			$extraChildren = max(0, $children - $baseChildren);
			$extraPersons = $extraAdults + $extraChildren;

			// Check if extra persons exceed the allowed limit
			if ($extraPersons > $totalAllowedExtraPersons) {
				echo json_encode([
					"status" => "error",
					"message" => "Total room occupancy exceeds the allowed limit."
				]);
			} else {
				// Calculate the total extra charge
				$totalExtraCharge = $extraPersons * $extraChargePerPerson;

				// Return success response with total extra charge if applicable
				echo json_encode([
					"status" => "success",
					"message" => "Occupancy is within limits.",
					"baseAdults" => $baseAdults,
					"baseChildren" => $baseChildren,
					"extraAdults" => $extraAdults,
					"extraChildren" => $extraChildren,
					"extraPersons" => $extraPersons,
					"extraCharge" => $totalExtraCharge,
					"totalPrice" => $totalExtraCharge // You can calculate total price here if necessary
				]);
			}
		} else {
			// Handle case where no roomtype is found
			echo json_encode([
				"status" => "error",
				"message" => "Room type not found or invalid room ID."
			]);
		}
	} else if (isset($_POST['action']) && $_POST['action'] === 'add_guest_additional_service') {
		$services = $_POST['services']; // Expecting 'services' to be an array
		$bookingid = $_POST['bookingid'];

		$invoice = execute("select isgst from invoice_setup where hotel='$_POST[hotel]'");

		$isgst = $invoice['isgst'];

		foreach ($services as $service) {
			$date = new DateTime($service['date']);
			$quantity = $service['quantity'];
			$additional_service_id = $service['additional_service_id'];

			$guest_service_id = insert("INSERT INTO guest_additional_services (additional_service_id, bookingid, quantity, created_at)
					VALUES ('$additional_service_id', '$bookingid', '$quantity', '" . $date->format("Y-m-d") . "')");

			$service_quantity = execute("SELECT quantity FROM guest_additional_services WHERE id='$guest_service_id'");

			$service_charge = execute("SELECT charge, tax FROM additional_services WHERE id='$additional_service_id'");

			$oldtariff = execute("SELECT declaredtariff FROM bookings WHERE id='$bookingid'");

			$service_tax = $isgst ? $service_charge['tax'] : 0;

			$newprice = $oldtariff['declaredtariff'] +
				($service_charge['charge'] * $service_quantity['quantity']) +
				((($service_tax / 100) * $service_charge['charge']) * $service_quantity['quantity']);

			$amount_inp = ($service_charge['charge'] * $service_quantity['quantity']) +
				((($service_tax / 100) * $service_charge['charge']) * $service_quantity['quantity']);

			$conn->query("INSERT INTO additional_services_receipt (additional_service_id, bookingid, amount, guest_service_id)
				VALUES ('$additional_service_id', '$bookingid', '$amount_inp','$guest_service_id')");

			$result = $conn->query("UPDATE bookings SET declaredtariff='$newprice' WHERE id='$bookingid'");
		}

		if ($result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Created new additional services for FR$bookingid", 'BOOKINGS');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'update_user_additional_service') {
		$serviceid = $_POST['serviceid'];
		$quantity = $_POST['quantity'];
		$date = new DateTime($_POST['date']);  // Ensure DateTime object for proper formatting
		$bookingid = $_POST['bookingid'];

		$invoice = execute("select isgst from invoice_setup where hotel='$_SESSION[hotel]'");
		$isgst = $invoice['isgst'];

		// Fetch existing quantity and additional service id
		$guest_service = execute("SELECT quantity, additional_service_id FROM guest_additional_services WHERE id='$serviceid'");
		$old_quantity = $guest_service['quantity'];
		$add_service_id = $guest_service['additional_service_id'];

		// Fetch service receipt id
		$service_receipt = execute("SELECT id FROM additional_services_receipt WHERE guest_service_id='$serviceid'");

		// Fetch service charge and tax
		$service_charge = execute("SELECT charge, tax FROM additional_services WHERE id='$add_service_id'");

		// Fetch old tariff
		$oldtariff = execute("SELECT declaredtariff FROM bookings WHERE id='$bookingid'");

		// Calculate the difference in quantity
		$quantity_diff = $quantity - $old_quantity;

		$service_tax = $isgst ? $service_charge['tax'] : 0;

		// Calculate new price based on the difference in quantity
		$additional_amount = ($service_charge['charge'] * $quantity_diff) +
			((($service_tax / 100) * $service_charge['charge']) * $quantity_diff);

		$newprice = $oldtariff['declaredtariff'] + $additional_amount;

		// Calculate the new total amount for this additional service
		$amount_inp = ($service_charge['charge'] * $quantity) +
			((($service_tax / 100) * $service_charge['charge']) * $quantity);

		// Begin transaction
		$conn->begin_transaction();
		try {
			// Update additional_services_receipt table
			$res = $conn->query("UPDATE additional_services_receipt SET amount='$amount_inp' WHERE id='$service_receipt[id]'");

			if (!$res) {
				throw new Exception("Failed to update additional_services_receipt: " . $conn->error);
			}

			// Update guest_additional_services table
			$result = $conn->query("UPDATE guest_additional_services SET created_at='" . $date->format('Y-m-d H:i:s') . "', quantity='$quantity' WHERE id='$serviceid'");

			if (!$result) {
				throw new Exception("Failed to update guest_additional_services: " . $conn->error);
			}

			// Update bookings table with new declared tariff
			$result2 = $conn->query("UPDATE bookings SET declaredtariff='$newprice' WHERE id='$bookingid'");

			if (!$result2) {
				throw new Exception("Failed to update bookings: " . $conn->error);
			}

			// Commit transaction
			$conn->commit();

			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Updated additional services for FR$bookingid", 'BOOKINGS');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} catch (Exception $e) {
			// Rollback on error
			$conn->rollback();
			echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'delete_guest_additional_service') {
		$guest_service_id = $_POST['guest_service_id'];
		$bookingid = $_POST['bookingid'];

		$invoice = execute("select isgst from invoice_setup where hotel='$_SESSION[hotel]'");
		$isgst = $invoice['isgst'];

		$service_details = execute("select quantity, additional_service_id from guest_additional_services where id='$guest_service_id'");
		$additional_service_id = $service_details['additional_service_id'];
		$service_quantity = $service_details['quantity'];

		$service_charge = execute("select charge, tax from additional_services where id='$additional_service_id'");

		$oldtariff = execute("select declaredtariff, intialtariff from bookings where id='$bookingid'");

		$conn->query("update additional_services_receipt set deleted=1 where guest_service_id='$guest_service_id'");

		$service_tax = $isgst ? $service_charge['tax'] : 0;

		$price_to_subtract = ($service_charge['charge'] * $service_quantity) +
			((($service_tax / 100) * $service_charge['charge']) * $service_quantity);

		$newprice = $oldtariff['declaredtariff'] - $price_to_subtract;

		$delete_result = $conn->query("update guest_additional_services set deleted=1 where id='$guest_service_id'");


		if ($delete_result) {
			$update_result = $conn->query("update bookings set declaredtariff='$newprice' where id='$bookingid'");

			if ($update_result) {
				add_hotel_task_log($_POST['user'], $_POST['hotel'], "Deleted additional services for FR$bookingid", 'BOOKINGS');
				echo json_encode(['status' => 'success', 'message' => 'Service deleted and tariff updated successfully']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Failed to update tariff after deletion']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to delete the service']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'delete_demo') {
		$id = intval($_POST['id']);

		// Check if entry exists
		$check = $conn->query("SELECT id FROM demo_request WHERE id = $id");
		if ($check && $check->num_rows > 0) {
			// Delete
			$delete = $conn->query("DELETE FROM demo_request WHERE id = $id");
			if ($delete) {
				echo json_encode(['status' => 'success']);
			} else {
				echo json_encode(['status' => 'error', 'message' => $conn->error]);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Record not found']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'delete_pendingsubscription') {
		$hotel_id = (int) $_POST['id'];

		// Check if hotel exists
		$check = $conn->query("SELECT user FROM hotels WHERE id = $hotel_id");
		if ($check && $check->num_rows > 0) {
			$row = $check->fetch_assoc();
			$user_id = (int)$row['user'];

			// Begin transaction for safety
			$conn->begin_transaction();

			try {
				// Delete from pms_subscriptions
				$conn->query("DELETE FROM pms_subscriptions WHERE hotel_id = $hotel_id");

				// Delete from hotels
				$conn->query("DELETE FROM hotels WHERE id = $hotel_id");

				// Delete user (optional: only if user is linked to only this hotel)
				$conn->query("DELETE FROM users WHERE id = $user_id");

				// Commit the transaction
				$conn->commit();

				echo json_encode(['status' => 'success']);
			} catch (Exception $e) {
				$conn->rollback();
				echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Hotel not found']);
		}
	}
	if (isset($_POST['action']) && $_POST['action'] === 'setup_invoice') {
		$start_invoice_no = $_POST['start_invoice_no'];
		$invoice_prefix = $_POST['invoice_prefix'];
		$bank_details = $_POST['bank_details'];
		$account_no = $_POST['account_no'];
		$branch_details = $_POST['branch_details'];
		$ifsc = $_POST['ifsc'];
		$upi_id = $_POST['upi_id'];
		$gstin_no = $_POST['gstin_no'];
		$signatory_label = $_POST['signatory_label'];
		$hotel = $_POST['hotel'];
		$state = $_POST['state'];
		$isgst = $_POST['isgst'];


		$already = $conn->query("SELECT COUNT(*) AS cnt, id FROM invoice_setup WHERE hotel='$hotel'");
		$row = $already->fetch_assoc();

		if ($row['cnt'] > 0) {
			$result = $conn->query("UPDATE invoice_setup 
				SET start_invoice_no='$start_invoice_no', 
					invoice_prefix='$invoice_prefix', 
					signature_label='$signatory_label', 
					bank_detail='$bank_details', 
					account_no='$account_no', 
					branch_detail='$branch_details', 
					ifsc='$ifsc', 
					upi_id='$upi_id', 
					gstin_no='$gstin_no' ,
					state='$state',
					isgst=$isgst
				WHERE id='{$row['id']}'");
		} else {
			$result = $conn->query("INSERT INTO invoice_setup 
				(hotel, start_invoice_no, invoice_prefix, signature_label, bank_detail, account_no, branch_detail, ifsc, upi_id, gstin_no, state,isgst) 
				VALUES 
				('$hotel', '$start_invoice_no', '$invoice_prefix', '$signatory_label', '$bank_details', '$account_no', '$branch_details', '$ifsc', '$upi_id', '$gstin_no','$state',$isgst)");
		}
		if ($result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Invoice setup", 'INVOICES');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'delete_additional_services') {
		$serviceid = $_POST['serviceid'];
		$result = $conn->query("update additional_services set deleted=1 where id='$serviceid'");

		if ($result) {
			add_hotel_task_log($_POST['user'], $_POST['hotel'], "Deleted additional services", 'HOTEL SERVICES');
			echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
		}
	} elseif (isset($_POST['action']) && $_POST['action'] === 'upload_document') {
		$upload_directory = "uploads/";
		$cidt = new DateTime($_POST['cidt'] . "T12:00:00");
		$codt = new DateTime($_POST['codt'] . "T11:00:00");
		$cmroomtype = $_POST['cmroomtype'];
		$reg_date = $_POST['reg_date'];
		$id_proof = $_POST['id_proof'];

		$userid = execute("select u.id from bookings b join users u on b.guestid=u.id join room_distribution rd on rd.bookingid=b.id join roomnumbers rn on rn.id=rd.roomnumber join roomtypes rt on rt.id=rn.roomtype where b.checkindatetime=' " . $cidt->format("Y-m-d H:i:s") . "' and checkoutdatetime=' " . $codt->format("Y-m-d H:i:s") . "' and rt.cmroomid='$cmroomtype' and b.reg_date='$reg_date';");

		// If a file is uploaded, save it
		if (isset($_FILES['file'])) {
			$saved_filename = savefile('file', $upload_directory);

			$updateuser = $conn->query("update users set id_proof_path='$saved_filename',id_proof='$id_proof' where id='$userid[id]'");

			if ($saved_filename) {
				echo json_encode([
					'success' => true,
					'message' => 'File uploaded successfully.',
					'file_name' => "$saved_filename $userid[id]",
				]);
			} else {
				echo json_encode([
					'success' => false,
					'message' => 'File upload failed.',
					'file_name' => "$saved_filename $userid[id]",
				]);
			}
		}
	} elseif (isset($_POST['checkin']) && isset($_POST['checkout']) && isset($_POST['hotel']) && $_POST['action'] === 'fetch_room_data') {
		$checkin = $_POST['checkin'];
		$checkout = $_POST['checkout'];
		$hotelId = $_POST['hotel'];
		ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
		// Validate input
		if (!$checkin || !$checkout || !$hotelId) {
			echo json_encode(['error' => 'Missing parameters']);
			exit;
		}

		// Ensure that check-in date is before the checkout date
		if (strtotime($checkin) >= strtotime($checkout)) {
			echo json_encode(['error' => 'Check-in date must be before checkout date']);
			exit;
		}

		// Initialize an empty array for room availability
		$availability = [];

		// Fetch hotel checkout time
		$hotelCheckinQuery = $conn->query("SELECT hotelcheckintime, hotelcheckouttime FROM hotels WHERE id='$hotelId'");
		$hotelCheckinTimeRow = $hotelCheckinQuery->fetch_assoc();
		$hotelCheckinTime = $hotelCheckinTimeRow['hotelcheckintime']; // e.g., '14:00:00' for 2:00 PM
		$hotelCheckoutTime = $hotelCheckinTimeRow['hotelcheckouttime']; // e.g., '11:00:00' for 11:00 AM

		// Fetch room types for the hotel
		$result = $conn->query("SELECT r.id, r.roomtype, r.cmroomid FROM roomtypes r WHERE r.hotel = '$hotelId' and r.cmroomid<>''");
		$roomTypes = [];
		$roomCodes = [];

		while ($roomType = $result->fetch_assoc()) {
			$roomTypes[$roomType['id']] = $roomType['roomtype'];
			$roomCodes[$roomType['id']] = $roomType['cmroomid'];
		}
		// Fetch room numbers
		$roomNumbersQuery = $conn->query("
			SELECT rn.id, rn.roomnumber, rn.roomtype 
			FROM roomnumbers rn 
			WHERE rn.roomtype IN (" . implode(',', array_keys($roomTypes)) . ")
		");

		$roomNumbers = [];
		while ($roomNumber = $roomNumbersQuery->fetch_assoc()) {
			$roomType = $roomTypes[$roomNumber['roomtype']] ?? 'Unknown';
			$roomNumbers[$roomType][$roomNumber['roomnumber']] = [
				'id' => $roomNumber['id'], // room number ID
				'roomnumber' => $roomNumber['roomnumber'],
				'roomtypeid' => $roomNumber['roomtype'] // room type ID
			];
		}

		// Fetch rate plans for the room types
		$ratePlansQuery = $conn->query("
			SELECT rrp.id AS rate_plan_id, rrp.roomtype, rrp.cm_rate_plan, rrp.room_rate_plan 
			FROM room_rate_plans rrp 
			WHERE rrp.roomtype IN (" . implode(',', array_keys($roomTypes)) . ") AND deleted=0
		");

		$ratePlans = [];
		while ($ratePlan = $ratePlansQuery->fetch_assoc()) {
			$ratePlans[$ratePlan['roomtype']][] = [
				'rate_plan_id' => $ratePlan['rate_plan_id'],
				'cm_rate_plan' => $ratePlan['cm_rate_plan'],
				'room_rate_plan' => $ratePlan['room_rate_plan'],
			];
		}

		// Initialize an empty array for available room numbers
		$availableRooms = [];

		foreach ($roomTypes as $roomTypeId => $roomType) {
			$availableRooms[$roomType] = [
				'roomtypeid' => $roomTypeId, // Include room type ID only once
				'rooms' => [], // Initialize an array for the rooms
				'rateplans' => [], // Initialize an empty array for the rate plans
				'roomtypecode' => $roomCodes[$roomTypeId]
			];

			if (isset($roomNumbers[$roomType])) {
				foreach ($roomNumbers[$roomType] as $roomNumber => $roomData) {
					$isRoomAvailable = true; // Assume the room is available

					// Fetch bookings for this room within the selected date range
					$roomIdsList = $roomData['id'];
					$bookingQuery = $conn->query("
						SELECT rd.roomnumber, b.status, 
							   CAST(b.checkindatetime AS DATE) AS checkindate, 
							   CAST(b.checkoutdatetime AS DATE) AS checkoutdate, 
							   TIME(b.checkindatetime) AS checkintime,
							   TIME(b.checkoutdatetime) AS checkouttime
						FROM bookings b 
						JOIN room_distribution rd ON rd.bookingid = b.id 
						WHERE b.status IN ('Scheduled', 'Cancelled') 
						  AND rd.roomnumber = $roomIdsList
						  AND (CAST(b.checkoutdatetime AS DATE) > '$checkin' 
							   AND CAST(b.checkindatetime AS DATE) < '$checkout')
					");

					while ($booking = $bookingQuery->fetch_assoc()) {
						$checkinDate = $booking['checkindate'];
						$checkoutDate = $booking['checkoutdate'];
						$bookingCheckinTime = $booking['checkintime'];
						$bookingCheckoutTime = $booking['checkouttime'];

						// If the booking overlaps with the requested dates
						if (($checkinDate < $checkout && $checkoutDate > $checkin) ||
							($checkinDate == $checkoutDate && strtotime($bookingCheckoutTime) > strtotime($hotelCheckoutTime))
						) {
							$isRoomAvailable = false; // Mark room as unavailable
							break; // No need to check other bookings
						}
					}

					// Add the room to the available rooms list if it has no bookings between the given dates
					if ($isRoomAvailable) {
						$availableRooms[$roomType]['rooms'][] = [
							'roomnumber' => $roomData['roomnumber'], // room number
							'roomId' => $roomData['id'] // room number ID
						];
					}
				}
			}

			// Include pricing information for each rate plan in the rateplans array
			if (isset($ratePlans[$roomTypeId])) {
				foreach ($ratePlans[$roomTypeId] as $ratePlan) {
					// First, check for pricing in seasonal_rate_plans_validity
					$seasonalPriceQuery = $conn->query("
						SELECT fulldaytariff 
						FROM seasonal_rate_plans_validity 
						WHERE rateplanid = " . $ratePlan['rate_plan_id'] . "
						AND validfrom <= '$checkin' 
						AND validto >= '$checkout'
					");

					if ($seasonalPriceRow = $seasonalPriceQuery->fetch_assoc()) {
						// If a price is found in seasonal rates, use it
						$availableRooms[$roomType]['rateplans'][] = [
							'rate_plan_id' => $ratePlan['rate_plan_id'],
							'cm_rate_plan' => $ratePlan['cm_rate_plan'],
							'room_rate_plan' => $ratePlan['room_rate_plan'],
							'fulldaytariff' => $seasonalPriceRow['fulldaytariff']
						];
					} else {
						// If no price in seasonal rates, check room_rate_plans_validity
						$priceQuery = $conn->query("
							SELECT fulldaytariff 
							FROM room_rate_plans_validity 
							WHERE rateplanid = " . $ratePlan['rate_plan_id'] . "
							AND validfrom <= '$checkin' 
							AND validto >= '$checkout'
						");

						if ($priceRow = $priceQuery->fetch_assoc()) {
							$availableRooms[$roomType]['rateplans'][] = [
								'rate_plan_id' => $ratePlan['rate_plan_id'],
								'cm_rate_plan' => $ratePlan['cm_rate_plan'],
								'room_rate_plan' => $ratePlan['room_rate_plan'],
								'fulldaytariff' => $priceRow['fulldaytariff']
							];
						}
					}
				}
			}
		}

		// Output only available room numbers along with IDs
		echo json_encode($availableRooms);
	}

	if (isset($_POST['post_action']) && $_POST['post_action'] == 'unblock_many') {
		if (!empty($_POST['block_ids'])) {
			$deleted = 0;
			foreach ($_POST['block_ids'] as $group) {
				$ids = explode(',', $group); // break group into IDs
				foreach ($ids as $id) {
					$id = (int)$id;
					$conn->query("DELETE FROM blocked_roomnumbers WHERE id = $id");
					if ($conn->affected_rows > 0) $deleted++;
				}
			}
			echo json_encode(['status' => 'success', 'deleted' => $deleted]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'No blocks selected']);
		}
		exit;
	} else if (isset($_POST['post_action']) && $_POST['post_action'] == 'unblock_single') {
		$ids = explode(',', $_POST['block_ids']);
		$deleted = 0;
		foreach ($ids as $id) {
			$id = (int)$id;
			$conn->query("DELETE FROM blocked_roomnumbers WHERE id = $id");
			if ($conn->affected_rows > 0) $deleted++;
		}
		echo json_encode(['status' => 'success', 'deleted' => $deleted]);
		exit;
	}
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (isset($_GET['bookingid'])) {
		$bookingid = $_GET['bookingid'];

		$stmt = $conn->prepare("SELECT invoice_data FROM invoices WHERE bookingid = ?");
		$stmt->bind_param("i", $bookingid);
		$stmt->execute();
		$stmt->bind_result($invoice_data);
		$stmt->fetch();
		$stmt->close();
		$conn->close();

		$decoded_data = html_entity_decode($invoice_data);

		$decoded_data = trim($decoded_data, '"');

		$decoded_data = stripslashes($decoded_data);

		$data = json_decode($decoded_data, true);

		if (json_last_error() === JSON_ERROR_NONE) {
			echo json_encode($data);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data', 'error' => json_last_error_msg()]);
		}
	}

	if (isset($_GET['stateid'])) {
		$stateid = $_GET['stateid'];

		$stmt = $conn->prepare("SELECT city, id FROM cities WHERE state = ?");
		$stmt->bind_param("i", $stateid);
		$stmt->execute();
		$result = $stmt->get_result();

		$data = [];
		while ($row = $result->fetch_assoc()) {
			$data[] = [
				'id' => $row['id'],
				'city' => $row['city']
			];
		}

		echo json_encode(["data" => $data]);
	}
	if (isset($_GET['roomtypeid'])) {
		$id = intval($_GET['roomtypeid']);
		$date = $_GET['date'];

		$qry = "SELECT rrp.id, 
					rrp.room_rate_plan, 
					rrp.cm_rate_plan, 
					COALESCE(srv.fulldaytariff, rrpv.fulldaytariff) AS fulldaytariff 
				FROM room_rate_plans rrp 
				LEFT JOIN seasonal_rate_plans_validity srv 
					ON srv.rateplanid = rrp.id 
					AND srv.validfrom <= '$date' 
					AND srv.validto >= '$date'
				JOIN room_rate_plans_validity rrpv 
					ON rrpv.rateplanid = rrp.id 
					AND rrpv.validfrom <= '$date'
					AND rrpv.validto >= '$date'
				WHERE rrp.roomtype = $id 
				AND rrp.deleted = 0;";
		$result = $conn->query($qry);

		$roomtype = execute("SELECT r.adults,r.children,r.extraallowed,r.chargeperextra from roomtypes r where r.id=$id;");
		if ($result->num_rows > 0) {
			$data = array();
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}

			echo json_encode(["data" => $data, "room_type_info" => "$roomtype[adults] + $roomtype[children] + $roomtype[extraallowed]", "extra_charge" => "$roomtype[chargeperextra]", "adults" => "$roomtype[adults]", "children" => "$roomtype[children]", "extraallowed" => "$roomtype[extraallowed]"]);
		} else {
			echo json_encode(["error" => "No data found for the given ID $id"]);
		}
	}

	if (isset($_GET['rateplanids']) && isset($_GET['validfrom']) && isset($_GET['validto'])) {
		$ids = $_GET['rateplanids']; // This can have duplicates, e.g., 1,6,1,6
		$validfrom = $_GET['validfrom'];
		$validto = $_GET['validto'];

		$idsArray = array_map('intval', explode(',', $ids));
		$idsListUnique = implode(',', array_unique($idsArray));

		// First, try seasonal_rate_plans_validity
		$query = "SELECT rateplanid, fulldaytariff 
				  FROM seasonal_rate_plans_validity 
				  WHERE rateplanid IN ($idsListUnique) 
				  AND validfrom <= ? 
				  AND validto >= ?";

		if ($stmt = $conn->prepare($query)) {
			$stmt->bind_param("ss", $validfrom, $validto);

			$stmt->execute();
			$result = $stmt->get_result();

			$dataMap = [];
			$foundIds = [];
			while ($row = $result->fetch_assoc()) {
				$dataMap[$row['rateplanid']] = $row['fulldaytariff'];
				$foundIds[] = $row['rateplanid'];  // Track found IDs
			}
			$stmt->close();

			// Find the IDs that were not found in seasonal_rate_plans_validity
			$missingIds = array_diff($idsArray, $foundIds);

			// If there are any missing IDs, try room_rate_plans_validity
			if (!empty($missingIds)) {
				$missingIdsList = implode(',', array_unique($missingIds)); // Create list of missing IDs
				$query = "SELECT rateplanid, fulldaytariff 
						  FROM room_rate_plans_validity 
						  WHERE rateplanid IN ($missingIdsList) 
						  AND validfrom <= ? 
						  AND validto >= ?";

				if ($stmt = $conn->prepare($query)) {
					$stmt->bind_param("ss", $validfrom, $validto);

					$stmt->execute();
					$result = $stmt->get_result();

					// Add the results from room_rate_plans_validity to the dataMap
					while ($row = $result->fetch_assoc()) {
						$dataMap[$row['rateplanid']] = $row['fulldaytariff'];
					}
					$stmt->close();
				}
			}

			// Build the final result based on combined data from both queries
			$data = [];
			foreach ($idsArray as $id) {
				if (isset($dataMap[$id])) {
					$data[] = [
						"rateplanid" => $id,
						"fulldaytariff" => $dataMap[$id]
					];
				}
			}

			// Return the data as JSON
			echo json_encode(["data" => $data]);
		}
	}

	if (isset($_GET['month']) && isset($_GET['hotel'])) {

		$month = $_GET['month'];

		$hotelId = $_GET['hotel'];

		if (!$month || !$hotelId) {
			echo json_encode(['error' => 'Missing parameters']);
			exit;
		}

		// $startDate = date('Y-m-01', strtotime("$month 12:00:00"));
		$currentDate = date('Y-m-d');
		$currentMonth = date('Y-m');
		$selectedMonth = date('Y-m', strtotime("$month"));

		if ($selectedMonth === $currentMonth) {
			$startDate = $currentDate; // Start from today if current month
		} else {
			$startDate = date('Y-m-01', strtotime("$month")); // Else 1st of selected month
		}
		$endDate = date('Y-m-t', strtotime("$month 11:00:00"));
		// 		echo $startDate;
		// 		echo $endDate;
		$missing = [];
		$availability = [];
		$ratePlanCheck = $conn->query("
		    SELECT validfrom, validto
		    FROM rate_plans
		    WHERE hotel = '$hotelId'
		");

		$roomtypesSQL = "
    SELECT r.id, r.roomtype
    FROM roomtypes r
    JOIN room_rate_plans rp ON rp.roomtype = r.id
    JOIN room_rate_plans_validity v ON v.rateplanid = rp.id
    WHERE r.hotel = $hotelId
    AND (
        v.validfrom <= '$endDate' AND v.validto >= '$startDate'
    )
";


		$roomtypes = $conn->query($roomtypesSQL);

		// Check if any records exist
		if ($roomtypes && $roomtypes->num_rows > 0) {
			// At least one roomtype has valid rate plan + validity
			$roomTypesAvailable = true;
		} else {
			// None found — add to missing array or show error
			$missing[] = 'room rate plan';
		}

		$ratePlanDates = [];

		while ($rp = $ratePlanCheck->fetch_assoc()) {
			$from = strtotime($rp['validfrom']);
			$to = strtotime($rp['validto']);
			for ($d = $from; $d <= $to; $d = strtotime('+1 day', $d)) {
				$ratePlanDates[date('Y-m-d', $d)] = true;
			}
		}

		// Step 2: Check if each date in the selected month is covered
		$missingRatePlanDates = [];

		for ($d = strtotime($startDate); $d <= strtotime($endDate); $d = strtotime('+1 day', $d)) {
			$date = date('Y-m-d', $d);
			if (!isset($ratePlanDates[$date])) {
				$missingRatePlanDates[] = $date;
			}
		}
		if (!empty($missingRatePlanDates)) {
			// echo json_encode([
			//     'status' => 'error',
			//     'message' => 'Rate plan is missing for the following dates: ' . implode(', ', $missingRatePlanDates)
			// ]);
			//     echo json_encode([
			//     'status' => 'error',
			//     'message' => 'Please set the rate plan to access the bookmap'
			// ]);
			// exit;
			$missing[] = 'rate plan';
		}
		// Fetch room types
		$result = $conn->query("
			SELECT r.id, r.roomtype 
			FROM roomtypes r 
			WHERE r.hotel = '$hotelId'
		");

		$roomTypes = [];
		while ($roomType = $result->fetch_assoc()) {
			$roomTypes[$roomType['id']] = $roomType['roomtype'];
		}

		if (empty($roomTypes)) {
			// echo json_encode(['status' => 'error', 'message' => 'Please set the room type to access the bookmap']);
			// exit;
			$missing[] = 'room type';
		}

		// Fetch room numbers
		$roomNumbers = [];
		if (!empty($roomTypes)) {

			$roomNumbersQuery = $conn->query("
			SELECT rn.id, rn.roomnumber, rn.roomtype 
			FROM roomnumbers rn 
			WHERE rn.roomtype IN (" . implode(',', array_keys($roomTypes)) . ")
		");


			while ($roomNumber = $roomNumbersQuery->fetch_assoc()) {
				$roomType = $roomTypes[$roomNumber['roomtype']] ?? 'Unknown';
				$roomNumbers[$roomType][$roomNumber['roomnumber']] = [
					'id' => $roomNumber['id'],
					'roomnumber' => $roomNumber['roomnumber'],
					'roomtypeid' => $roomNumber['roomtype'] // Add roomtypeid here
				];
			}
		}

		if (empty($roomNumbers)) {
			// echo json_encode(['status' => 'error', 'message' => 'Please set the room numbers to access the bookmap']);
			// exit;
			$missing[] = 'room number';
		}

		if (!empty($missing)) {
			// Format the message like: "Rate plan and room type are not set"
			$last = array_pop($missing);
			$message = !empty($missing) ? ucfirst(implode(', ', $missing)) . ' and ' . $last . ' are not set' : ucfirst($last) . ' is not set. Please complete the required setup to access the bookmap.';

			echo json_encode([
				'status' => 'error',
				'message' => $message
			]);
			exit;
		}
		$startDate = date('Y-m-01', strtotime("$month")); // Else 1st of selected month

		// Populate availability for each room type
		foreach ($roomTypes as $roomTypeId => $roomType) {
			$availability[$roomType] = [];
			$bookingNumbers = []; // Initialize booking numbers for each room type
			$currentNumber = 1;    // Reinitialize unique number for each room type

			// Get room numbers for this room type
			$roomIds = array_column($roomNumbers[$roomType], 'id');

			if (empty($roomIds)) {
				continue; // Skip if no rooms are found for this room type
			}

			$roomIdsList = implode(',', $roomIds);

			// Fetch bookings for the current room type
			$bookingQuery = $conn->query("
				SELECT rd.roomnumber, b.status, u.fullname, 
					   CAST(b.checkindatetime AS DATE) AS checkindate, 
					   CAST(b.checkoutdatetime AS DATE) AS checkoutdate, 
					   b.checkoutdatetime, b.ticker,rd.isRoomAssigned, b.paid, b.id as bookingid 
				FROM bookings b 
				JOIN room_distribution rd ON rd.bookingid = b.id 
				JOIN users u ON b.guestid = u.id
				WHERE b.status IN ('Scheduled', 'Cancelled') 
				  AND CAST(b.checkindatetime AS DATE) <= '$endDate' 
				  AND CAST(b.checkoutdatetime AS DATE) >= '$startDate'
				  AND rd.roomnumber IN ($roomIdsList)
			");

			$bookings = [];
			while ($booking = $bookingQuery->fetch_assoc()) {
				$roomNumber = $booking['roomnumber'];
				$checkinDate = $booking['checkindate'];
				$checkoutDate = $booking['checkoutdate'];
				$checkoutDateTime = $booking['checkoutdatetime'];
				$bookingId = $booking['bookingid'];

				// Assign unique number if not already assigned for the room type
				if (!isset($bookingNumbers[$bookingId])) {
					$bookingNumbers[$bookingId] = $currentNumber;
					$currentNumber++; // Increment for the next unique booking
				}

				$uniqueBookingNumber = $bookingNumbers[$bookingId];

				$currentDate = $checkinDate;
				while ($currentDate <= $checkoutDate) {
					// Determine if the current booking impacts availability
					if ($currentDate == $checkoutDate) {
						// If checking out today, set to unavailable only if the checkout time is before noon
						$bookingStatus = (strtotime($checkoutDateTime) < strtotime($currentDate . ' 12:00:00')) ? 0 : ($booking['ticker'] > 0 || $booking['paid'] == 1 ? 1 : 0);
					} else {
						// For other days, set to available based on ticker or paid status
						$bookingStatus = ($booking['ticker'] > 0 || $booking['paid'] == 1) ? 1 : 0;
					}

					// Check if a booking for this room already exists on the same date
					if (!isset($bookings[$roomNumber][$currentDate])) {
						// No existing booking for this room on this date, assign the booking
						$bookings[$roomNumber][$currentDate] = [
							'status' => $bookingStatus,
							'fullname' => $booking['fullname'],
							'checkin' => $checkinDate,
							'checkout' => $checkoutDate,
							'unique_booking_number' => $uniqueBookingNumber,
							'bookingid' => $bookingId,
							'isRoomAssigned' => $booking['isRoomAssigned']
						];
					} else {
						// There is an existing booking, compare statuses
						$existingBooking = $bookings[$roomNumber][$currentDate];

						// Check if current booking is still valid based on status and unique number
						if (
							$bookingStatus > $existingBooking['status'] ||
							($bookingStatus == $existingBooking['status'] && $uniqueBookingNumber > $existingBooking['unique_booking_number'])
						) {
							// Replace the existing booking if the current one is more relevant
							$bookings[$roomNumber][$currentDate] = [
								'status' => $bookingStatus,
								'fullname' => $booking['fullname'],
								'checkin' => $checkinDate,
								'checkout' => $checkoutDate,
								'unique_booking_number' => $uniqueBookingNumber,
								'bookingid' => $bookingId,
								'isRoomAssigned' => $booking['isRoomAssigned']
							];
						}
					}

					// Move to the next day
					$currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
				}
			}

			// Populate availability
			if (isset($roomNumbers[$roomType])) {
				foreach ($roomNumbers[$roomType] as $roomNumber => $roomData) {
					$availability[$roomType][$roomNumber] = [
						'id' => $roomData['id'],
						'roomtypeid' => $roomData['roomtypeid'], // Include roomtypeid here
						'availability' => []
					];
					for ($date = strtotime($startDate); $date <= strtotime($endDate); $date = strtotime("+1 day", $date)) {
						$currentDate = date('Y-m-d', $date);
						$availability[$roomType][$roomNumber]['availability'][$currentDate] = isset($bookings[$roomData['id']][$currentDate]) ? $bookings[$roomData['id']][$currentDate] : [
							'status' => 0,
							'fullname' => '',
							'checkin' => '',
							'checkout' => '',
							'unique_booking_number' => null,
							'bookingid' => null,
							'isRoomAssigned' => null
						];
					}
				}
			}
		}

		echo json_encode($availability);
	}


	if (isset($_GET['month']) && isset($_GET['hotelID'])) {
		$month = $_GET['month'];
		$hotelId = $_GET['hotelID'];

		if (empty($month) || empty($hotelId)) {
			echo json_encode(['error' => 'Missing parameters']);
			exit;
		}

		// Define start and end dates for the month
		$startDate = date('Y-m-01', strtotime($month));
		$endDate = date('Y-m-t', strtotime($month));

		// Fetch total rooms by room type
		$roomCountQuery = $conn->query("
			SELECT r.id AS roomtype_id, r.roomtype, COUNT(rn.id) AS total_rooms
			FROM roomnumbers rn
			JOIN roomtypes r ON rn.roomtype = r.id
			WHERE r.hotel = '$hotelId'
			GROUP BY r.id, r.roomtype
		");

		if (!$roomCountQuery) {
			echo json_encode(['error' => 'Failed to fetch room counts']);
			exit;
		}

		$totalRooms = [];
		while ($room = $roomCountQuery->fetch_assoc()) {
			$totalRooms[$room['roomtype_id']] = [
				'roomtype' => $room['roomtype'],
				'total_rooms' => (int)$room['total_rooms']
			];
		}

		// Fetch blocked rooms information
		$blockedRoomsQuery = $conn->query("
			SELECT roomnumber, bdate
			FROM blocked_roomnumbers
			WHERE bdate BETWEEN '$startDate' AND '$endDate'
		");

		if (!$blockedRoomsQuery) {
			echo json_encode(['error' => 'Failed to fetch blocked rooms']);
			exit;
		}

		$blockedRooms = [];
		while ($blocked = $blockedRoomsQuery->fetch_assoc()) {
			$roomnumber = $blocked['roomnumber'];
			$blockedDate = $blocked['bdate'];

			if (!isset($blockedRooms[$blockedDate])) {
				$blockedRooms[$blockedDate] = [];
			}
			$blockedRooms[$blockedDate][] = $roomnumber;
		}

		// Fetch bookings with full checkout datetime
		$bookingQuery = $conn->query("
			SELECT rn.roomtype, rd.roomnumber, b.status, 
				b.checkindatetime, 
				b.checkoutdatetime 
			FROM bookings b 
			JOIN room_distribution rd ON rd.bookingid = b.id 
			JOIN roomnumbers rn ON rn.id = rd.roomnumber 
			WHERE b.status IN ('Scheduled', 'Cancelled') 
			AND CAST(b.checkindatetime AS DATE) <= '$endDate' 
			AND CAST(b.checkoutdatetime AS DATE) >= '$startDate'
		");

		if (!$bookingQuery) {
			echo json_encode(['error' => 'Failed to fetch bookings']);
			exit;
		}

		$bookings = [];
		while ($booking = $bookingQuery->fetch_assoc()) {
			$roomTypeId = $booking['roomtype'];
			$checkinDate = date('Y-m-d', strtotime($booking['checkindatetime']));
			$checkoutDate = date('Y-m-d', strtotime($booking['checkoutdatetime']));
			$checkoutTime = date('H:i:s', strtotime($booking['checkoutdatetime']));

			$currentDate = $checkinDate;
			while ($currentDate <= $checkoutDate) {
				// Initialize the count if not set
				if (!isset($bookings[$roomTypeId][$currentDate])) {
					$bookings[$roomTypeId][$currentDate] = 0;
				}

				if ($currentDate < $checkoutDate) {
					// The room is booked on this date
					$bookings[$roomTypeId][$currentDate]++;
				} elseif ($currentDate == $checkoutDate) {
					// On checkout date, check the checkout time
					// Assuming '12:00:00' is the cutoff time
					if ($checkoutTime >= '12:00:00') {
						// If checkout time is 12:00:00 or later, count as booked on this date
						$bookings[$roomTypeId][$currentDate]++;
					}
				}

				// Move to the next day
				$currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
			}
		}

		// Calculate availability for each room type and each date
		$availability = [];
		foreach ($totalRooms as $roomTypeId => $roomData) {
			$roomType = $roomData['roomtype'];
			$availability[$roomType] = [
				'roomtype_id' => $roomTypeId,
				'total_rooms' => $roomData['total_rooms'],
				'dates' => []
			];

			for ($date = strtotime($startDate); $date <= strtotime($endDate); $date = strtotime("+1 day", $date)) {
				$currentDate = date('Y-m-d', $date);

				// Calculate how many rooms are booked and available
				$bookedRooms = isset($bookings[$roomTypeId][$currentDate]) ? (int)$bookings[$roomTypeId][$currentDate] : 0;
				$availableRooms = $roomData['total_rooms'] - $bookedRooms;

				// Adjust for blocked rooms
				if (isset($blockedRooms[$currentDate])) {
					$blockedCount = count($blockedRooms[$currentDate]);
					$availableRooms -= $blockedCount;

					// Ensure available rooms don't go negative
					if ($availableRooms < 0) {
						$availableRooms = 0;
					}
				}

				$availability[$roomType]['dates'][$currentDate] = [
					'booked_rooms' => $bookedRooms,
					'available_rooms' => $availableRooms
				];
			}
		}

		// Return the availability as JSON
		echo json_encode($availability);
	}

	if (isset($_GET['month']) && isset($_GET['assign_hotel'])) {

		$month = $_GET['month'];
		$hotelId = $_GET['assign_hotel'];

		if (!$month || !$hotelId) {
			echo json_encode(['error' => 'Missing parameters']);
			exit;
		}

		$startDate = date('Y-m-01', strtotime("$month 12:00:00"));
		$endDate = date('Y-m-t', strtotime("$month 11:00:00"));
		$availability = [];

		// Fetch room types
		$result = $conn->query("
			SELECT r.id, r.roomtype 
			FROM roomtypes r 
			WHERE r.hotel = '$hotelId'
		");

		$roomTypes = [];
		while ($roomType = $result->fetch_assoc()) {
			$roomTypes[$roomType['id']] = $roomType['roomtype'];
		}

		// Fetch room numbers
		$roomNumbersQuery = $conn->query("
			SELECT rn.id, rn.roomnumber, rn.roomtype 
			FROM roomnumbers rn 
			WHERE rn.roomtype IN (" . implode(',', array_keys($roomTypes)) . ")
		");

		$roomNumbers = [];
		foreach ($roomTypes as $roomTypeId => $roomType) {
			$roomNumbers[$roomType] = []; // Initialize room numbers for each room type
		}

		while ($roomNumber = $roomNumbersQuery->fetch_assoc()) {
			$roomType = $roomTypes[$roomNumber['roomtype']] ?? 'Unknown';
			$roomNumbers[$roomType][$roomNumber['roomnumber']] = [
				'id' => $roomNumber['id'],
				'roomnumber' => $roomNumber['roomnumber']
			];
		}

		// Initialize total assigned count, total room count, and bookings for each room type and day
		$assignedRoomsCount = [];
		foreach ($roomTypes as $roomTypeId => $roomType) {
			$totalRoomsInType = count($roomNumbers[$roomType]);
			$assignedRoomsCount[$roomType] = [
				'roomtype_id' => $roomTypeId,
				'total_rooms' => $totalRoomsInType,
				'dates' => []
			];
			for ($date = strtotime($startDate); $date <= strtotime($endDate); $date = strtotime("+1 day", $date)) {
				$currentDate = date('Y-m-d', $date);
				$assignedRoomsCount[$roomType]['dates'][$currentDate] = [
					'assigned_count' => 0,
					'booking_ids' => [] // Initialize booking IDs array for each date
				];
			}
		}

		// Populate availability and booking details for each room type
		foreach ($roomTypes as $roomTypeId => $roomType) {

			// Get room numbers for this room type
			$roomIds = array_column($roomNumbers[$roomType], 'id');

			if (empty($roomIds)) {
				continue; // Skip if no rooms are found for this room type
			}

			$roomIdsList = implode(',', $roomIds);

			// Fetch bookings for the current room type where isRoomAssigned is 1
			$bookingQuery = $conn->query("
				SELECT rd.roomnumber, 
					   CAST(b.checkindatetime AS DATE) AS checkindate, 
					   CAST(b.checkoutdatetime AS DATE) AS checkoutdate, 
					   b.checkoutdatetime, rd.isRoomAssigned, b.id as bookingid 
				FROM bookings b 
				JOIN room_distribution rd ON rd.bookingid = b.id 
				WHERE 
				   CAST(b.checkindatetime AS DATE) <= '$endDate' 
				  AND CAST(b.checkoutdatetime AS DATE) >= '$startDate'
				  AND rd.roomnumber IN ($roomIdsList)
			");

			while ($booking = $bookingQuery->fetch_assoc()) {
				$checkinDate = $booking['checkindate'];
				$checkoutDate = $booking['checkoutdate'];
				$checkoutDateTime = $booking['checkoutdatetime'];
				$bookingId = $booking['bookingid'];

				$currentDate = $checkinDate;
				while ($currentDate <= $checkoutDate) {
					if ($currentDate != $checkoutDate || strtotime($checkoutDateTime) >= strtotime($currentDate . ' 12:00:00')) {
						// Increment the assigned rooms count and add booking ID for this room type and date
						$assignedRoomsCount[$roomType]['dates'][$currentDate]['assigned_count']++;
						$assignedRoomsCount[$roomType]['dates'][$currentDate]['booking_ids'][] = $bookingId;
					}

					// Move to the next day
					$currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
				}
			}
		}

		// Return the room type ID, total rooms, assigned rooms count, and booking IDs for each day
		echo json_encode($assignedRoomsCount);
	}
} else {
	echo "Invalid request method.";
}

function sendwhatsapp($cellno, $smstext)
{
	// $varsms = "authentic-key=31334e696b68696c73697240676d61696c2e636f6d3130301726726008&route=1&number=" . $cellno . "&message=" . $smstext;
	// $url = "http://wapp.powerstext.in/http-tokenkeyapi.php?" . $varsms;

	$url = "https://wapp.powerstext.in/http-tokenkeyapi.php?authentic-key=31334e696b68696c73697240676d61696c2e636f6d3130301726726008&route=1&number=" . $cellno . "&message=" . $smstext;
	// echo $url;
	$curl = curl_init();

	curl_setopt_array($curl, [
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTPGET => true
	]);

	$response = curl_exec($curl);
	$error = curl_error($curl);
	// dd($error);
	curl_close($curl);

	if ($error) {
		return "cURL Error: " . $error;
	} else {
		return $response;
	}
}
