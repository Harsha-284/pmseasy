<?php
include 'conn.php';
include 'functions.php';

// error_reporting(E_ALL); // Report all errors
// ini_set('display_errors', 1); // Display errors on the page
// ini_set('log_errors', 1); // Log errors to a file
// ini_set('error_log', '/pms/error.log');


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
    if (is_jwt_valid($jwt)) {
        if (isset($_GET['action']) && $_GET['action'] === 'invoice') {

            $current_page = isset($_GET['cnt']) ? (int)$_GET['cnt'] : 1;

            $hotel_prefix = execute("SELECT invoice_prefix from invoice_setup where hotel=$_GET[hotel]");

            $base_query = "SELECT 
            b.id, 
            b.reg_date,
            b.checkindatetime,
            b.checkoutdatetime,
            r.roomtype,
            b.source,
            (b.declaredtariff-b.pcdiscount+b.hotelst+ b.hsbc+b.hkcc+b.frotelst+ b.fsbc+b.fkcc+b.lt+b.sc+ b.stonsc)total,
            (b.hoteltariff+b.hotelst+b.hsbc+b.hkcc+b.lt+b.sc+ b.stonsc)payabletohotel,
            b.pcdiscount AS discount,
            rn.id AS roomnumberid, 
            u_gst.fullname,  
            u_gst.contact,   
            b.declaredtariff,           
            COUNT(rd.roomnumber) AS cnt 
            FROM bookings b
            JOIN room_distribution rd ON rd.bookingid = b.id 
            JOIN roomnumbers rn ON rn.id = rd.roomnumber 
            JOIN roomtypes r ON r.id = rn.roomtype 
            JOIN hotels h ON h.id = r.hotel 
            JOIN users u ON u.id = h.user 
            JOIN users u_gst ON u_gst.id = b.guestid 
            JOIN cities c ON c.id = u.city";

            $filt = "";

            if ($_GET["bookingid"] != "") {
                $bookingid = str_replace("FR", "", $_GET["bookingid"]);
                $filt .= " AND b.id = " . $bookingid;
            }
            if ($_GET["global_search"] != "") {
                $bookingid = str_replace("FR", "", $_GET["global_search"]);
                $filt .= " AND id = " . $bookingid;
            }
            // if ($_SESSION['groupid'] == 2) {
            // 	$filt .= " AND hotelid = $_SESSION[hotel]";
            // } elseif ($_SESSION['groupid'] == 1) {
            // 	$filt .= " AND hotelid IN (SELECT id FROM hotels WHERE admin = $_SESSION[id])";
            // } elseif ($_GET["hotel") != "") {
            // 	$filt .= " AND hotelid = " . $_GET["hotel");
            // }

            if (!empty($_GET["fromdate"])) {
                $filt .= " AND DATE(b.checkindatetime) >= '" . dateindia($_GET["fromdate"]) . "'";
            }
            if (!empty($_GET["todate"])) {
                $filt .= " AND DATE(b.checkoutdatetime) >='" . dateindia($_GET["todate"]) . "'";
            }
            if ($_GET["roomtype"] != "") {
                $filt .= " AND r.roomtype = '" . $_GET["roomtype"] . "'";
            }
            // if ($_GET["source"] != "") {
            // 	$filt .= " AND source = '" . $_GET["source"] . "'";
            // }

            if ($_GET["guestname"] != "") {
                $filt .= " AND (SELECT u.fullname FROM users u WHERE u.id = b.guestid) = '" . $_GET["guestname"] . "'";
            }
            if ($_GET["guestcontact"] != "") {
                $filt .= " AND (SELECT u.contact FROM users u WHERE u.id = b.guestid) = '" . $_GET["guestcontact"] . "'";
            }
            if (!empty($_GET["source"])) {
                $filt .= " AND b.source = '" . $_GET["source"] . "'";
            }
            if (!empty($_GET["booking_reference"])) {
                $bookingid = str_replace("FR", "", $_GET["booking_reference"]);
                $filt .= " AND b.id = " . $bookingid;
            }

            $qry = $base_query . $filt . " GROUP BY b.id ORDER BY b.id DESC";
            $result = $conn->query($qry);

            if ($result->num_rows > 0) {
                $entries = [];
                while ($row = $result->fetch_assoc()) {
                    $invoice_num = execute("select invoice_number,count(*)cnt from invoices where bookingid = $row[id]");
                    $invoicestr = "-";
                    $isinvoiceCreated = 0;
                    $date = new DateTime($row['checkindatetime']);

                    if ($invoice_num['cnt'] > 0) {
                        $isinvoiceCreated = 1;
                        $invoicestr = '' . $hotel_prefix['invoice_prefix'] . '' . $invoice_num['invoice_number'] . '';
                    }
                    if ($isinvoiceCreated == 1) {
                        $invoiceUrl = "https://www.pmseasy.in/pms/LBF_finalinvoice.php?id={$row['roomnumberid']}&date=" . $date->format("Y-m-d");
                        $row['invoice_url'] = $invoiceUrl;
                    }
                    $row['invoicestr'] = $invoicestr;
                    $row['booking_reference'] = "FR" . $row['id'];
                    $entries[] = $row;
                }

                echo json_encode([
                    'status' => 'success',
                    'data' => $entries
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No entries found'
                ]);
            }
        } else if (isset($_GET['action']) && $_GET['action'] === 'bookings') {
            $base_query = "SELECT b.id, b.reg_date, b.status, u.company AS hotel, c.city AS city, b.source, u_gst.fullname, b.checkindatetime, b.checkoutdatetime, b.hours, r.roomtype, b.declaredtariff,rn.id as roomid, b.ip, COUNT(rd.roomnumber) AS cnt 
                   FROM bookings b 
                   JOIN room_distribution rd ON rd.bookingid = b.id 
                   JOIN roomnumbers rn ON rn.id = rd.roomnumber 
                   JOIN roomtypes r ON r.id = rn.roomtype 
                   JOIN hotels h ON r.hotel = h.id 
                   JOIN users u ON u.id = h.user 
                   JOIN cities c ON c.id = u.city 
                   JOIN users u_gst ON u_gst.id = b.guestid";

            $filt = [];

            if (!empty($_GET['bookingid'])) {
                $bookingid = str_replace("FR", "", $_GET['bookingid']);
                $filt[] = "b.id = " . intval($bookingid);
            }

            if (!empty($_GET['global_search'])) {
                $bookingid = str_replace("FR", "", $_GET['global_search']);
                $filt[] = "b.id = " . intval($bookingid);
            }

            if (!empty($_GET['groupid']) && $_GET['groupid'] == 2) {
                $filt[] = "h.id = " . intval($_GET['hotel']);
            } elseif (!empty($_GET['groupid']) && $_GET['groupid'] == 1) {
                $filt[] = "hotelid IN (SELECT id FROM hotels WHERE admin = " . intval($_GET['id']) . ")";
            } elseif (!empty($_GET['hotel'])) {
                $filt[] = "h.id = " . intval($_GET['hotel']);
            }

            if (!empty($_GET['fromdate'])) {
                $filt[] = "CAST(b.reg_date AS DATE) >= '" . date('Y-m-d', strtotime($_GET['fromdate'])) . "'";
            }

            if (!empty($_GET['todate'])) {
                $filt[] = "CAST(b.reg_date AS DATE) <= '" . date('Y-m-d', strtotime($_GET['todate'])) . "'";
            }

            if (!empty($_GET['fullname'])) {
                $filt[] = "u_gst.fullname = '" . $conn->real_escape_string($_GET['fullname']) . "'";
            }

            if (!empty($_GET['source'])) {
                $filt[] = "b.source = '" . $conn->real_escape_string($_GET['source']) . "'";
            }

            if (!empty($_GET['status'])) {
                $filt[] = "b.status = '" . $conn->real_escape_string($_GET['status']) . "'";
            }

            if (!empty($_GET["booking_reference"])) {
                $bookingid = str_replace("FR", "", $_GET["booking_reference"]);
                $filt[] = "b.id = " . $bookingid;
            }

            if (!empty($filt)) {
                $base_query .= " WHERE " . implode(" AND ", $filt);
            }

            $today = date("Y-m-d");

            $base_query .= " AND DATE(b.checkindatetime) >= '$today'";

            $qry = $base_query . " GROUP BY b.id ORDER BY b.checkindatetime ASC";

            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                $entries = [];
                while ($row = $result->fetch_assoc()) {
                    $row['booking_reference'] = "FR" . $row['id'];
                    $entries[] = $row;
                }
                echo json_encode(['status' => 'success', 'data' => $entries]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No entries found']);
            }
        } else if (isset($_GET['action']) && $_GET['action'] === 'gst') {
            $hotel_prefix = execute("SELECT invoice_prefix from invoice_setup where hotel=$_GET[hotel]");

            $base_query = "SELECT iv.bookingid, iv.invoice_number, iv.reg_date, r.roomtype ,iv.invoice_data,rn.id AS roomnumberid
            FROM invoices iv 
            JOIN room_distribution rd ON rd.bookingid = iv.bookingid 
            JOIN roomnumbers rn ON rn.id = rd.roomnumber 
            JOIN roomtypes r ON r.id = rn.roomtype 
            WHERE iv.invoice_number IS NOT NULL";

            $filt = "";

            if ($_GET['bookingid'] != "") {
                $bookingid = str_replace("FR", "", $_GET['bookingid']);
                $filt .= " AND iv.bookingid = " . $bookingid;
            }
            if ($_GET['global_search'] != "") {
                $bookingid = str_replace("FR", "", $_GET['global_search']);
                $filt .= " AND id = " . $bookingid;
            }
            // if ($_SESSION['groupid'] == 2) {
            // 	$filt .= " AND hotelid = $_SESSION[hotel]";
            // } elseif ($_SESSION['groupid'] == 1) {
            // 	$filt .= " AND hotelid IN (SELECT id FROM hotels WHERE admin = $_SESSION[id])";
            // } elseif ($_GET['hotel'] != "") {
            // 	$filt .= " AND hotelid = " . $_GET['hotel'];
            // }
            if ($_GET['fromdate'] != "") {
                $filt .= " AND b.checkindatetime >= '" . dateindia($_GET['fromdate']) . " 12:00:00'";
            }
            if ($_GET['todate'] != "") {
                $filt .= " AND b.checkoutdatetime <= '" . dateindia($_GET['todate']) . " 11:00:00'";
            }
            if ($_GET['roomtype'] != "") {
                $filt .= " AND r.roomtype = '" . $_GET['roomtype'] . "'";
            }
            // if ($_GET['source'] != "") {
            // 	$filt .= " AND source = '" . $_GET['source'] . "'";
            // }


            if ($_GET['booking_reference'] != "") {
                $bookingid = str_replace("FR", "", $_GET['booking_reference']);
                $filt .= " AND iv.bookingid = " . $bookingid;
            }
            $qry = $base_query  . $filt . " GROUP BY iv.id ORDER BY iv.id DESC";

            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                $entries = [];
                while ($row = $result->fetch_assoc()) {
                    $decoded_data = html_entity_decode($row['invoice_data']);
                    $decoded_data = trim($decoded_data, '"');
                    $decoded_data = stripslashes($decoded_data);

                    $data = json_decode($decoded_data, true);

                    $discount = $data['summatyData']['totalDiscount'] ?? 0;
                    $subtotal = $data['summatyData']['subtotal'] + $discount ?? 0;
                    $total_gst = $data['summatyData']['totalGST'] ?? 0;
                    $sgst = $total_gst / 2;
                    $cgst = $total_gst / 2;
                    $igst = 0;
                    $totalTax = $sgst + $cgst + $igst;
                    $totalAmount = ($subtotal + $totalTax) - $discount ?? 0;

                    $userinfo_query = "SELECT u.fullname, b.checkindatetime 
                                   FROM users u 
                                   JOIN bookings b ON b.guestid = u.id 
                                   WHERE b.id = $row[bookingid]";

                    if ($_GET['guestname'] != "") {
                        $filt .= " AND (SELECT u.fullname FROM users u WHERE u.id = b.guestid) = '" . $_GET['guestname'] . "'";
                    }
                    if ($_GET['guestcontact'] != "") {
                        $filt .= " AND (SELECT u.contact FROM users u WHERE u.id = b.guestid) = '" . $_GET['guestcontact'] . "'";
                    }

                    $qry = $base_query  . $filt ;

                    $userinfo_result = $conn->query($qry);
                    $userinfo = $userinfo_result->fetch_assoc();

                    $entries[] = [
                        'booking_id' => $row['bookingid'],
                        'room_type' => $row['roomtype'],
                        'invoice_number' => $hotel_prefix['invoice_prefix'] . $row['invoice_number'],
                        'registration_date' => $row['reg_date'],
                        'check_in_date' => $userinfo['checkindatetime'] ?? null,
                        'guest_name' => $userinfo['fullname'] ?? null,
                        'discount' => $discount,
                        'subtotal' => $subtotal,
                        'total_gst' => $total_gst,
                        'sgst' => $sgst,
                        'cgst' => $cgst,
                        'igst' => $igst,
                        'total_tax' => $totalTax,
                        'total_amount' => $totalAmount,
                        'booking_reference' => "FR" . $row['bookingid'],
                    ];
                }
                echo json_encode(['status' => 'success', 'data' => $entries]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No entries found']);
            }
        } else if (isset($_GET['action']) && $_GET['action'] === 'cancellation') {
            $base_query = "select x.* from (select b.id,b.status,hu.company,l.location, b.source,b.reg_date,(case when b.hours>0 and b.hours<24 then 'Frotel' else 'Hotel' end)hf,(sum(rd.adults)+sum(rd.children))guests, b.checkindatetime,b.checkoutdatetime,b.hours,r.roomtype,b.hoteltariff,b.declaredtariff, (b.declaredtariff-b.hoteltariff)frotelcommission,b.pcdiscount,b.hotelst,b.hsbc,b.hkcc,b.frotelst,b.fsbc,b.fkcc, b.lt,b.sc,b.stonsc,(b.declaredtariff-b.pcdiscount+b.hotelst+ b.hsbc+b.hkcc+b.frotelst+ b.fsbc+b.fkcc+b.lt+b.sc+ b.stonsc)total,(b.hoteltariff+b.hotelst+b.hsbc+b.hkcc+b.lt+b.sc+ b.stonsc)payabletohotel,h.accountant, h.accountnumber,h.bank,h.branch,h.ifsc,(case when b.paid_to_hotel=1 then 'Paid' else 'Unpaid' end)paid_to_hotel, b.promocode,h.id as hotelid,u.fullname,u.contact,b.cancellationrequestdate,b.cancellationreason, TIMESTAMPDIFF(HOUR,b.cancellationrequestdate,b.checkindatetime)cancellationpriortocheckin,b.refund_amount from bookings b join room_distribution rd on rd.bookingid=b.id join roomnumbers rn on rn.id=rd.roomnumber join roomtypes r on r.id=rn.roomtype join users u on u.id=b.guestid join hotels h on h.id=r.hotel join users hu on hu.id=h.user join locations l on l.id=h.location  group by b.id)x where x.status='Refunded'";
            $filt = "";
            if ($_GET['bookingid'] != "") {
                $bookingid = str_replace("FR", "", $_GET['bookingid']);
                $filt .= " AND x.id=" . $bookingid;
            }
            if (!empty($_GET['fullname'])) {
                $fullname = $conn->real_escape_string($_GET['fullname']); // Escape to prevent SQL injection
                $filt .= " AND x.fullname LIKE '%$fullname%'";
            }

            if (!empty($_GET['contact'])) {
                $contact = $conn->real_escape_string($_GET['contact']); // Escape to prevent SQL injection
                $filt .= " AND x.contact LIKE '%$contact%'";
            }
            if ($_GET['booking_reference'] != "") {
                $bookingid = str_replace("FR", "", $_GET['booking_reference']);
                $filt .= " AND x.id=" . $bookingid;
            }
            if (!empty($_GET['source'])) {
                $source = $conn->real_escape_string($_GET['source']); // Escape to prevent SQL injection
                $filt .= " AND x.source LIKE '%$source%'";
            }

            if (!empty($_GET["from_date"])) {
                $from_date = $_GET["from_date"];
                $filt .= " AND DATE(x.checkindatetime) >= '$from_date'";
            }

            // To Date Filter
            if (!empty($_GET["to_date"])) {
                $to_date = $_GET["to_date"];
                $filt .= " AND DATE(x.checkoutdatetime) <= '$to_date'";
            }
            if ($_GET['groupid'] == 2)
                $filt .= " and x.hotelid=$_GET[hotel]";
            else if ($_GET['groupid'] == 1)
                $filt .= " and x.hotelid in (select id from hotels where admin=$_GET[id])";
            else if ($_GET['hotel'] != "")
                $filt .= " and x.hotelid=" . $_GET['hotel'];
            $qry = $base_query . $filt . " order by x.id ";
            // echo "<a>$qry</a>";
            $result = $conn->query($qry);
            // echo $result->num_rows;
            if ($result->num_rows > 0) {
                $entries = [];
                while ($row = $result->fetch_assoc()) {
                    $row['booking_reference'] = "FR" . $row['id'];
                    $entries[] = $row;
                }
                echo json_encode(['status' => 'success', 'data' => $entries]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No entries found']);
            }
        } else if (isset($_GET['action']) && $_GET['action'] === 'customerdetails') {
            $sql = "SELECT * FROM users 
            JOIN bookings ON bookings.guestid = users.id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $data = [];

                while ($row = $result->fetch_assoc()) {
                    unset($row['password'], $row['emailkey'], $row['otp']);

                    $data[] = $row;
                }

                echo json_encode(["status" => "success", "data" => $data]);
            } else {
                echo json_encode(["status" => "error", "message" => "No records found"]);
            }
        } else if (isset($_GET['action']) && $_GET['action'] === 'bookingmap_payment_details') {
            if (!isset($_GET['id']) || !isset($_GET['date'])) {
                echo json_encode(["error" => "Missing required parameters"]);
                exit;
            }

            $id = intval($_GET['id']);
            $date = $_GET['date'];

            // Fetch User and Room Details
            $userInfoQuery = "SELECT 
                u.company, u.cm_company_name, r.roomtype, r.id, rn.roomnumber, c.city 
            FROM roomnumbers rn 
            JOIN roomtypes r ON r.id = rn.roomtype 
            JOIN hotels h ON h.id = r.hotel 
            JOIN users u ON u.id = h.user 
            JOIN locations l ON l.id = h.location 
            JOIN cities c ON c.id = l.city 
            WHERE rn.id = $id";

            $userInfo = execute($userInfoQuery);

            // Check if room exists
            if (!$userInfo) {
                echo json_encode(["error" => "Room not found"]);
                exit;
            }

            // Fetch Booking Details
            $bdate = date_create($date);
            $now = date_create(date("Y-m-d H:i"));
            $checkinTime = date_create($bdate->format("Y-m-d") . " 12:00");

            $bookingQuery = "SELECT 
                b.id, b.intialtariff, b.hours, b.reg_date, u.id_proof_path, u.id_proof, 
                (declaredtariff - pcdiscount + hotelst + hsbc + hkcc + frotelst + fsbc + fkcc + lt + sc + stonsc) AS total,
                u.fullname, u.contact, u.email, u.address1, b.trav_name, 
                b.checkindatetime, b.checkoutdatetime, b.usercheckedin, b.usercheckedout 
            FROM bookings b 
            LEFT JOIN room_distribution rd ON b.id = rd.bookingid 
            LEFT JOIN users u ON u.id = b.guestid 
            WHERE (b.paid = 1 OR ticker > '" . $now->format("Y-m-d H:i") . "') 
            AND (b.status = 'Scheduled' OR b.status = 'Cancelled') 
            AND rd.roomnumber = $id 
            AND (b.checkindatetime <= '" . $checkinTime->format("Y-m-d H:i") . "' 
            AND b.checkoutdatetime >= '" . $checkinTime->format("Y-m-d H:i") . "')";

            $row = execute($bookingQuery);

            // Check if booking exists
            if (!$row) {
                echo json_encode(["error" => "No booking found for this room"]);
                exit;
            }

            // Get Number of Rooms in Booking
            $roomCountQuery = "SELECT COUNT(*) AS cnt FROM room_distribution WHERE bookingid = {$row['id']}";
            $no_of_room = execute($roomCountQuery);

            $checkindate = new DateTime($row['checkindatetime']);
            $checkoutdate = new DateTime($row['checkoutdatetime']);
            $reg_date = new DateTime($row['reg_date']);

            // Fetch Payment Details
            $paymentQuery = "SELECT id, payment_type, date_of_payment, txnid, cheque_bank, cheque_no, cheque_date, 
                comment, amount, discount_type, discount_percent, discount_flat 
            FROM payment_mode 
            WHERE bookingid = {$row['id']} AND deleted = 0 
            ORDER BY id DESC";
            $paymentResult = $conn->query($paymentQuery);

            $payments = [];
            $total_paid_amount = 0;
            $total_outstand = $row['total'];
            $discount = 0;

            while ($paymentRow = $paymentResult->fetch_assoc()) {
                $amount = !empty($paymentRow['amount']) ? $paymentRow['amount'] : 0;
                $total_paid_amount += $amount;
                $total_outstand -= $amount;

                if ($paymentRow['discount_type'] === "flat") {
                    $discount += $paymentRow['discount_flat'];
                    $total_outstand -= $discount;
                } elseif ($paymentRow['discount_type'] === "percentage") {
                    $discount += ($paymentRow['discount_percent'] / 100) * $row['total'];
                    $total_outstand -= $discount;
                }

                $payments[] = [
                    "payment_type" => $paymentRow['payment_type'] ?: "-",
                    "date_of_payment" => $paymentRow['date_of_payment'] ?: "-",
                    "txnid" => $paymentRow['txnid'] ?: "-",
                    "cheque_bank" => $paymentRow['cheque_bank'] ?: "-",
                    "cheque_no" => $paymentRow['cheque_no'] ?: "-",
                    "cheque_date" => $paymentRow['cheque_date'] !== "0000-00-00" ? $paymentRow['cheque_date'] : "-",
                    "amount" => $amount,
                    "discount" => $discount,
                    "due_balance" => $total_outstand,
                ];
            }

            $response = [
                "client_details" => [
                    "booking_reference" => 'FR' . $row['id'],
                    "fullname" => $row['fullname'],
                    "contact" => "+91 " . $row['contact'],
                    "email" => $row['email'],
                    "address" => $row['address1'],
                    "id_proof" => $row['id_proof'],
                    "id_proof_path" => "uploads/" . $row['id_proof_path'],
                ],
                "room_details" => [
                    "checkin_date" => $checkindate->format("d-m-Y"),
                    "checkout_date" => $checkoutdate->format("d-m-Y"),
                    "room_type" => $userInfo['roomtype'],
                    "no_of_rooms" => $no_of_room['cnt'],
                    "no_of_nights" => $checkindate->diff($checkoutdate)->days,
                ],
                "payment_details" => [
                    "total_deal_amount" => $row['intialtariff'],
                    "total_paid_amount" => $total_paid_amount,
                    "total_outstand" => $total_outstand,
                    "payments" => $payments
                ],

            ];

            $hotelId = $_GET['hotel'];
            $invoice = execute("select isgst from invoice_setup where hotel='$hotelId'");
            $invoice = $conn->query("SELECT isgst FROM invoice_setup WHERE hotel = $hotelId LIMIT 1");

            $invoiceData = $invoice->fetch_assoc();
            $isgst = isset($invoiceData['isgst']) ? $invoiceData['isgst'] : 0;

            $additionalServices = $conn->query("SELECT id, service, charge, gst, vat, tax FROM additional_services WHERE hotel = $hotelId AND ishotelroomtax = 0 AND deleted = 0");
            $services = [];
            while ($row = $additionalServices->fetch_assoc()) {
                $services[] = $row;
            }

            $response['additional_services'] = $services;

            echo json_encode($response, JSON_PRETTY_PRINT);
        } else if (isset($_GET['action']) && $_GET['action'] === 'assigned_room') {
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
        } else if (isset($_GET['action']) && $_GET['action'] === 'check_availability') {
            $hotel = $_GET['hotel'];
            $result = $conn->query("SELECT fulldaytariff, cmroomid, roomtype, id FROM roomtypes WHERE hotel='$hotel'");

            $rooms = [];
            while ($row = $result->fetch_assoc()) {
                $roomId = $row['id'];

                // Get the available room count
                $roomQuery = "SELECT COUNT(*) AS totalrooms FROM roomtypes r 
                  LEFT JOIN roomnumbers rn ON rn.roomtype = r.id 
                  WHERE r.id = $roomId AND r.active = 1 
                  AND (r.adults + r.extraallowed) >= 1 AND r.children >= 0";

                $roomResult = $conn->query($roomQuery);
                $roomData = $roomResult->fetch_assoc();

                $row['totalrooms'] = $roomData['totalrooms'] ?? 0;
                $rooms[] = $row;
            }

            echo json_encode(["rooms" => $rooms]);
        } else if (isset($_GET['action']) && $_GET['action'] === 'booking_report') {

            $dateToday = date('Y-m-d');
            $monthStart = date('Y-m-01');
            $dailyThreshold = 5;
            $monthlyThreshold = 30;

            // Get total bookings for today
            $totalQuery = "SELECT COUNT(*) AS total FROM bookings WHERE DATE(reg_date) = '$dateToday'";
            $totalResult = $conn->query($totalQuery);
            $totalRow = $totalResult->fetch_assoc();
            $totalBookings = $totalRow['total'] ?? 0;

            // Get total bookings for the current month
            $totalMonthlyQuery = "SELECT COUNT(*) AS total FROM bookings WHERE DATE(reg_date) BETWEEN '$monthStart' AND '$dateToday'";
            $totalMonthlyResult = $conn->query($totalMonthlyQuery);
            $totalMonthlyRow = $totalMonthlyResult->fetch_assoc();
            $totalMonthlyBookings = $totalMonthlyRow['total'] ?? 0;

            $sources = [
                'OTA' => 'OTA',
                'Direct' => 'OFFLINE',
                'PMS' => 'ONLINE'
            ];

            $bookingData = [];

            foreach ($sources as $source => $label) {
                // Count today's bookings for each source
                $query = "SELECT COUNT(*) AS count FROM bookings WHERE DATE(reg_date) = '$dateToday' AND source = '$source'";
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                $countToday = $row['count'] ?? 0;

                // Calculate today's percentage based on the daily threshold
                $percentageToday = ($dailyThreshold > 0) ? round(($countToday / $dailyThreshold) * 100, 2) : 0;

                // Count monthly bookings for each source
                $queryMonthly = "SELECT COUNT(*) AS count FROM bookings WHERE DATE(reg_date) BETWEEN '$monthStart' AND '$dateToday' AND source = '$source'";
                $resultMonthly = $conn->query($queryMonthly);
                $rowMonthly = $resultMonthly->fetch_assoc();
                $countMonthly = $rowMonthly['count'] ?? 0;

                // Calculate monthly percentage based on the monthly threshold
                $percentageMonthly = ($monthlyThreshold > 0) ? round(($countMonthly / $monthlyThreshold) * 100, 2) : 0;

                $bookingData[$label] = [
                    'today' => [
                        'count' => $countToday,
                        'percentage' => $percentageToday
                    ],
                    'monthly' => [
                        'count' => $countMonthly,
                        'percentage' => $percentageMonthly
                    ]
                ];
            }

            // Get count of today's check-ins
            $checkinQuery = "SELECT COUNT(*) AS checkin_count FROM bookings WHERE DATE(usercheckedin) = '$dateToday'";
            $checkinResult = $conn->query($checkinQuery);
            $checkinRow = $checkinResult->fetch_assoc();
            $checkinCount = $checkinRow['checkin_count'] ?? 0;

            // Calculate today's check-in percentage
            $checkinPercentageToday = ($dailyThreshold > 0) ? round(($checkinCount / $dailyThreshold) * 100, 2) : 0;

            // Get count of monthly check-ins
            $monthlyCheckinQuery = "SELECT COUNT(*) AS checkin_count FROM bookings WHERE DATE(usercheckedin) BETWEEN '$monthStart' AND '$dateToday'";
            $monthlyCheckinResult = $conn->query($monthlyCheckinQuery);
            $monthlyCheckinRow = $monthlyCheckinResult->fetch_assoc();
            $monthlyCheckinCount = $monthlyCheckinRow['checkin_count'] ?? 0;

            // Calculate monthly check-in percentage
            $checkinPercentageMonthly = ($monthlyThreshold > 0) ? round(($monthlyCheckinCount / $monthlyThreshold) * 100, 2) : 0;

            // Add check-in counts and percentages to response
            $bookingData["Today's Check-ins"] = [
                'count' => $checkinCount,
                'percentage' => $checkinPercentageToday
            ];
            $bookingData["Monthly Check-ins"] = [
                'count' => $monthlyCheckinCount,
                'percentage' => $checkinPercentageMonthly
            ];

            echo json_encode(["bookings" => $bookingData]);
        } else {
            echo json_encode(['error' => 'Invalid action']);
        }
    } else {
        echo json_encode(['error' => 'Invalid or expired token']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (isset($input['post_action']) && $input['post_action'] == 'booking') {
        $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        if (is_jwt_valid($jwt)) {

            // Validate required fields in the input payload
            $requiredFields = ['action', 'hotelCode', 'channel', 'bookingId'];
            foreach ($requiredFields as $field) {
                if (!isset($input[$field])) {
                    http_response_code(400);
                    echo json_encode(array("status" => "error", "message" => "Missing required field: $field"));
                    return;
                }
            }

            $action = $input['action'];
            $hotelCode = $input['hotelCode'];
            $channel = $input['channel'];

            if ($action === 'book') {
                $segment = $input['segment'];
                $fullname = $input['guest']['firstName'] . " " . $input['guest']['lastName'];
                $phone = $input['guest']['phone'];
                $email = $input['guest']['email'];
                $address = $input['guest']['address']['line1'] . ", " . $input['guest']['address']['city'] . ", " . $input['guest']['address']['state'] . ", " . $input['guest']['address']['country'] . " - " . $input['guest']['address']['zipCode'];
                $bookedOn = $input['bookedOn'];
                $checkindatetime = new DateTime($input['checkin'] . "T12:00:00");
                $checkoutdatetime = new DateTime($input['checkout']  . "T11:00:00");
                $specialRequests = $input['specialRequests'];
                $amountbeforetax = $input['amount']['amountBeforeTax'];
                $amountaftertax = $input['amount']['amountAfterTax'];
                $ticker = new \DateTime();
                $now = new \DateTime();
                $ticker->modify("+" . 12 . " minutes");

                $hotelid = execute("select h.id from hotels h JOIN users u ON h.user = u.id where u.cm_company_name = '$hotelCode';");

                $guestid = insert("insert into users (fullname,email,contact,address1,groupid) values ('$fullname','$email','$phone','$address',5);");

                $bookingid = insert("insert into bookings (checkindatetime, checkoutdatetime, hours, guestid, paid, ticker, reg_date, ip, source, hoteltariff, declaredtariff,intialtariff, specialrequest) 
            values (
                '" . $checkindatetime->format("Y-m-d H:i") . "', 
                '" . $checkoutdatetime->format("Y-m-d H:i") . "', 
                0, 
                '" . $guestid . "', 
                1, 
                '" . $ticker->format("Y-m-d H:i:s") . "', 
                '" . $bookedOn . "', 
                '192.168.1.15', 
                '" . $segment . "',  
                '" . $amountbeforetax . "', 
                '" . $amountaftertax . "',
                '" . $amountaftertax . "',
                '" . $specialRequests . "'
            )");

                if ($segment === "Direct") {
                    $payment_type = $input['payment_type'];
                    $date_of_payment = $input['date_of_payment'];
                    $txnid = $input['txnid'];
                    $cheque_bank = $input['cheque_bank'];
                    $cheque_no = $input['cheque_no'];
                    $cheque_date = $input['cheque_date'];
                    $comment = $input['comment'];
                    $amount_inp = $input['amount_inp'];
                    $discount_type = $input['discount_type'];
                    $flat_discount = $input['flat_discount'];
                    $percent_discount = $input['percent_discount'];


                    $paymentmodeid = insert("insert into payment_mode (payment_type, date_of_payment, txnid, cheque_bank, cheque_no, cheque_date, comment,bookingid,amount,discount_flat,discount_percent,discount_type)
                values ('$payment_type', '$date_of_payment', '$txnid', '$cheque_bank', '$cheque_no', '$cheque_date', '$comment','$bookingid','$amount_inp','$flat_discount','$percent_discount','$discount_type')");

                    insert("insert into payment_mode_receipt (paymentmodeid	,bookingid,amount)
                values ('$paymentmodeid', '$bookingid','$amount_inp')");
                }

                foreach ($input['rooms'] as $room) {
                    $roomtype = $room['roomCode'];
                    $nofadults = $room['occupancy']['adults'];
                    $nofchildren = $room['occupancy']['children'];

                    $roomtypeid = execute("select id from roomtypes where cmroomid='$roomtype' and hotel= '$hotelid[id]'");

                    $roomnumbers = $conn->query("select id,roomnumber from roomnumbers where roomtype='$roomtypeid[id]' and active=1");
                    $free_room_number = null;

                    while ($roomnumbers_row = $roomnumbers->fetch_assoc()) {
                        $x = execute("select count(*)bookedrooms from bookings b join room_distribution rd on rd.bookingid=b.id where b.status IN ('Scheduled', 'Cancelled') and rd.roomnumber=$roomnumbers_row[id] and 
						
						(
							(
								(
									('" . $checkindatetime->format("Y-m-d H:i") . "'>=b.checkindatetime and '" . $checkoutdatetime->format("Y-m-d H:i") . "'<=b.checkoutdatetime) 
									or 
									('" . $checkindatetime->format("Y-m-d H:i") . "'<=b.checkindatetime and '" . $checkoutdatetime->format("Y-m-d H:i") . "'>=b.checkoutdatetime) 
									or
									('" . $checkindatetime->format("Y-m-d H:i") . "'<=b.checkindatetime and '" . $checkoutdatetime->format("Y-m-d H:i") . "'>=b.checkindatetime)
									or
									('" . $checkindatetime->format("Y-m-d H:i") . "'<=b.checkoutdatetime and '" . $checkoutdatetime->format("Y-m-d H:i") . "'>=b.checkoutdatetime)
								)
								and 
								b.paid=1
							)

							or

							(
								(
									('" . $checkindatetime->format("Y-m-d H:i") . "'>=b.checkindatetime and '" . $checkoutdatetime->format("Y-m-d H:i") . "'<=b.checkoutdatetime) 
									or 
									('" . $checkindatetime->format("Y-m-d H:i") . "'<=b.checkindatetime and '" . $checkoutdatetime->format("Y-m-d H:i") . "'>=b.checkoutdatetime) 
									or
									('" . $checkindatetime->format("Y-m-d H:i") . "'<=b.checkindatetime and '" . $checkoutdatetime->format("Y-m-d H:i") . "'>=b.checkindatetime)
									or
									('" . $checkindatetime->format("Y-m-d H:i") . "'<=b.checkoutdatetime and '" . $checkoutdatetime->format("Y-m-d H:i") . "'>=b.checkoutdatetime)
								)
								and 
								b.ticker>='" . $now->format('Y-m-d H:i:s') . "'
							)
						)
						");

                        if ($x['bookedrooms'] == 0) {
                            $free_room_number = $roomnumbers_row['id'];
                            break;
                        }
                    }

                    if ($free_room_number !== null) {
                        $conn->query("insert into room_distribution (bookingid,roomnumber,adults,children,child1age,child2age) values ($bookingid,$free_room_number,$nofadults,$nofchildren,0,0)");
                    }
                }
                $availble = search_booking($roomtypeid['id'], $checkindatetime, $checkoutdatetime, $nofadults, $nofchildren);
                $modifiedCheckOutDate = clone $checkoutdatetime;
                $modifiedCheckOutDate->modify('-1 day');
                $upload_directory = "uploads/";
                $cidt = new DateTime($input['cidt'] . "T12:00:00");
                $codt = new DateTime($input['codt'] . "T11:00:00");
                $cmroomtype = $input['cmroomtype'];
                $reg_date = $input['reg_date'];
                $id_proof = $input['id_proof'];

                $userid = execute("select u.id from bookings b join users u on b.guestid=u.id join room_distribution rd on rd.bookingid=b.id join roomnumbers rn on rn.id=rd.roomnumber join roomtypes rt on rt.id=rn.roomtype where b.checkindatetime=' " . $cidt->format("Y-m-d H:i:s") . "' and checkoutdatetime=' " . $codt->format("Y-m-d H:i:s") . "' and rt.cmroomid='$cmroomtype' and b.reg_date='$reg_date';");

                if (isset($_FILES['file'])) {
                    $saved_filename = savefile('file', $upload_directory);

                    $updateuser = $conn->query("update users set id_proof_path='$saved_filename',id_proof='$id_proof' where id='$userid[id]'");
                }
                $res = updateCmAvailability($checkindatetime->format("Y-m-d"), $modifiedCheckOutDate->format("Y-m-d"), $availble, $roomtype, $hotelCode);
                if ($res === true) {
                    $response = array(
                        "success" => true,
                        "message" => "Reservation Booked Successfully",
                        "bookingId" => $bookingid
                    );
                }
            } elseif ($action === 'check') {
                $checkindatetime = new DateTime($input['checkin']);
                $checkoutdatetime = new DateTime($input['checkout']);
                $avail = search_booking('2618', $checkindatetime, $checkoutdatetime, '0', "0");

                $response = [
                    "success" => true,
                    "message" => "Invalid hdhdhddh",
                    "data" => $avail
                ];
            } else if ($action === 'modify') {
                $response = array(
                    "success" => true,
                    "message" => "Reservation Modified Successfully",
                );
            } elseif ($action === 'cancel') {
                $bookingid = $input['bookingId'];
                $hotelCode = $input['hotelCode'];
                $refundAmount = '0';
                $refundReason = '';

                if ($input["channel"] === "banqueteasy") {
                    $refundAmount = $input["refund_amount"];
                    $refundReason = $input["refund_reason"];
                }

                $bookingInfo = execute("select checkindatetime,checkoutdatetime from bookings where id='$bookingid'");

                $room_distri_info = execute("select roomnumber,adults,children from room_distribution where bookingid='$bookingid'");

                $room_number_info = execute("select roomtype from roomnumbers where id='$room_distri_info[roomnumber]'");

                $roomtypename = execute("select cmroomid from roomtypes where id='$room_number_info[roomtype]'");

                $conn->query("update bookings set status='Refunded',refund_amount=$refundAmount,cancellationreason='$refundReason',refund_date=now() where id=$bookingid");
                $conn->query("update room_distribution set deleted=1 where bookingid=$bookingid");

                $checkindatetime = new DateTime($bookingInfo['checkindatetime']);
                $checkoutdatetime = new DateTime($bookingInfo['checkoutdatetime']);

                $availble = search_booking($room_number_info['roomtype'], $checkindatetime, $checkoutdatetime, '0', '0');
                $res = updateCmAvailability($checkindatetime->format("Y-m-d"), $checkoutdatetime->format("Y-m-d"), $availble, $roomtypename['cmroomid'], $hotelCode);

                if ($res === true) {
                    $response = [
                        "success" => true,
                        "message" => "Reservation Cancelled Successfully",
                        "data" => $availble,
                        "data1" => $checkindatetime,
                        "data2" => $refundAmount,
                        "data3" => $refundReason,
                    ];
                }
            } else {
                $response = [
                    "success" => false,
                    "message" => "Invalid action"
                ];
            }

            echo json_encode($response);
        } else {
            echo json_encode(['error' => 'Invalid or expired token']);
        }
    } else if (isset($input['post_action']) && $input['post_action'] == 'user_login') {
        $username = $input['username'];
        $password = $input['password'];
        $device_token = $input['device_token'];

        // Query the database to fetch the user
        $stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->bind_param('s', $username); // 's' means the parameter is a string
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];

            // Verify the password (assuming it is hashed in the database)
            if ($user['password'] === $password) {
                // If password is correct, generate JWT
                $headers = ['alg' => 'HS256', 'typ' => 'JWT'];
                $payload = ['user' => $username, 'exp' => time() + 3600];
                $jwt = generate_jwt($headers, $payload);

                $existingTokens = json_decode($user['device_token'], true) ?? [];

                if (!in_array($device_token, $existingTokens)) {
                    $existingTokens[] = $device_token;
                }
                $stmtUpdate = $conn->prepare('UPDATE users SET device_token = ? WHERE id = ?');
                $jsonTokens = json_encode($existingTokens);
                $stmtUpdate->bind_param('si', $jsonTokens, $user_id);
                $stmtUpdate->execute();
                $stmtUpdate->close();

                $stmtHotel = $conn->prepare('SELECT id FROM hotels WHERE user = ?');
                $stmtHotel->bind_param('i', $user_id);
                $stmtHotel->execute();
                $hotelResult = $stmtHotel->get_result();
                $hotel_id = ($hotelResult->num_rows > 0) ? $hotelResult->fetch_assoc()['id'] : null;
                $stmtHotel->close();
                echo json_encode(['token' => $jwt, 'hotel_id' => $hotel_id]);
            } else {
                // Invalid password
                http_response_code(401);
                echo json_encode(['error' => 'Invalid credentials']);
            }
        } else {
            // User not found
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }

        $stmt->close(); // Close the statement
        $conn->close(); // Close the connection
        exit;
    } else if (isset($input['post_action']) && $input['post_action'] == 'check_room_availability') {
        $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        if (is_jwt_valid($jwt)) {

            $rt = $input['rt'];
            $cidt = new DateTime($input['cidt'] . "T12:00:00");
            $codt = new DateTime($input['codt'] . "T11:00:00");
            $adults = $input['adults'];
            $children = $input['children'];
            $no_of_rooms = $input['no_of_rooms'];

            // Check if room type is active
            $stmt = $conn->prepare("SELECT COUNT(*) AS cnt FROM roomtypes WHERE id = ? AND active = 1");
            $stmt->bind_param('i', $rt);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['cnt'] == 1) {
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
				
				union all
				
				select roomnumber from special_blocked_slots where bdate='" . $cidt->format("Y-m-d") . "' and rpid=$rpid and roomnumber in (select id from roomnumbers where roomtype=$rt) and (('" . $cidt->format("H:i") . "' between starttime and endtime) or ('" . $codt->format("H:i") . "' between starttime and endtime))
				
				union all
				
				select rn.id as roomnumber from blocked_slots bs join roomnumbers rn on rn.roomtype=bs.roomtype where bs.rpid=$rpid and bs.roomtype=$rt and (('" . $cidt->format("H:i") . "' between starttime and endtime) or ('" . $codt->format("H:i") . "' between starttime and endtime)) and rn.id not in
				(select rn2.id from special_blocked_slots sbs2 join roomnumbers rn2 on rn2.id=sbs2.roomnumber where sbs2.bdate='" . $cidt->format("Y-m-d") . "' and rn2.roomtype=$rt)
			)f");
                $previouslybookedrooms = $rs_row['cnt'];

                $rs_row = execute("select count(*)totalrooms,r.chargeperextra from roomtypes r left join roomnumbers rn on rn.roomtype=r.id where r.id=$rt and r.active=1 and (r.adults+r.extraallowed)>=$adults and r.children>=$children");
                $totalrooms = $rs_row['totalrooms'];

                $hotel = $input['hotel'];
                $result = $conn->query("SELECT fulldaytariff, cmroomid, roomtype, id FROM roomtypes WHERE hotel='$hotel'");

                $rooms = [];
                while ($row = $result->fetch_assoc()) {
                    $roomId = $row['id'];

                    // Get the available room count
                    $roomQuery = "SELECT COUNT(*) AS totalrooms FROM roomtypes r 
                  LEFT JOIN roomnumbers rn ON rn.roomtype = r.id 
                  WHERE r.id = $roomId AND r.active = 1 
                  AND (r.adults + r.extraallowed) >= 1 AND r.children >= 0";

                    $roomResult = $conn->query($roomQuery);
                    $roomData = $roomResult->fetch_assoc();

                    $row['totalrooms'] = $roomData['totalrooms'] ?? 0;
                    $extra_charge = $rs_row['chargeperextra'];
                    $rooms[] = $row;
                }
                if ($rpid && $rate_plan) {
                    if ($totalrooms - $previouslybookedrooms < $no_of_rooms) {
                        echo json_encode(['total_rooms' => 0, 'extra_charge' => $extra_charge, "rooms" => $rooms]);
                    } else {
                        $total_room = ($totalrooms - $previouslybookedrooms);
                        echo json_encode(['total_rooms' => $total_room, 'extra_charge' => $extra_charge, "rooms" => $rooms]);
                    }
                } else {
                    echo json_encode(['total_rooms' => 0, 'extra_charge' => $extra_charge]);
                }
            } else {
                echo json_encode(['error' => 'Invalid or expired token']);
            }
        } else {
            echo json_encode(['total_rooms' => 0, 'extra_charge' => 0]);
        }
    } else {
        echo json_encode(['error' => 'Invalid request action']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

function generate_jwt($headers, $payload, $secret = 'pms-website-jwt-token-for-authentication')
{
    $headers_encoded = base64_url_encode(json_encode($headers));
    $payload_encoded = base64_url_encode(json_encode($payload));

    // Signature
    $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
    $signature_encoded = base64_url_encode($signature);

    return "$headers_encoded.$payload_encoded.$signature_encoded";
}
function base64_url_encode($data)
{
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

function base64_url_decode($data)
{
    return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
}

function is_jwt_valid($jwt, $secret = 'pms-website-jwt-token-for-authentication')
{
    $token_parts = explode('.', $jwt);
    if (count($token_parts) !== 3) {
        return false;
    }

    list($headers_encoded, $payload_encoded, $signature_encoded) = $token_parts;
    $signature = base64_url_decode($signature_encoded);
    $valid_signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);

    return hash_equals($signature, $valid_signature);
}
