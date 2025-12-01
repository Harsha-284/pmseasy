<?php
include 'conn.php';
include 'functions.php';

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
    if (is_jwt_valid($jwt))
    // REPLACE THIS FOR PRODUCTION WITH THE LINE ABOVE THE COMMENT
    // if (true) 
    {
        if (isset($_GET['action']) && $_GET['action'] === 'invoice') {

            $current_page = isset($_GET['cnt']) ? (int) $_GET['cnt'] : 1;

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
                        $invoiceUrl = "https://www.pmseasy.in/pms/LBF_finalinvoice.php?id={$row['roomnumberid']}&date=" . $date->format("Y-m-d") . "&hotel=" . $_GET['hotel'];
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
        } else if (isset($_GET['action']) && $_GET['action'] === 'getinvoice') {
            $id   = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
            $hotel = $_GET['hotel'];

            if ($id <= 0) {
                echo json_encode(["error" => "Invalid room id"]);
                exit;
            }

            // Booking info
            $bdate = date_create($date);
            $now   = date_create(date("Y-m-d H:i"));
            $a     = date_create($bdate->format("Y-m-d") . " 12:00");

            $row = execute("SELECT b.id,b.intialtariff,b.hours,b.reg_date,u.fullname,u.id_proof,
                           (declaredtariff-pcdiscount+hotelst+hsbc+hkcc+frotelst+fsbc+fkcc+lt+sc+stonsc) total,
                           u.fullname,u.contact,u.email,u.address1, 
                           b.trav_name,b.checkindatetime,b.checkoutdatetime,
                           b.usercheckedin,b.usercheckedout 
                    FROM bookings b 
                    LEFT JOIN room_distribution rd ON b.id=rd.bookingid 
                    LEFT JOIN users u ON u.id=b.guestid 
                    WHERE (b.paid=1 OR ticker>'" . $now->format("Y-m-d H:i") . "') 
                      AND (b.status='Scheduled' OR b.status='Cancelled') 
                      AND b.id=$id
                      AND (b.checkindatetime<='" . $a->format("Y-m-d H:i") . "' 
                      AND b.checkoutdatetime>='" . $a->format("Y-m-d H:i") . "')");

            $user_details = [
                "full_name"    => $row['fullname'],
                "address"      => $row['address1'],
                "invoice_date" => date("d-m-Y"),
                "email"        => $row['email'],
                "state_id"     => "15",
            ];

            $invoice = execute("SELECT isgst,state,start_invoice_no,invoice_prefix,
                               signature_label,bank_detail,account_no,branch_detail,
                               ifsc,upi_id,gstin_no 
                        FROM invoice_setup 
                        WHERE hotel='{$hotel}'");

            $isgst = $invoice['isgst'];
            $gst = [
                "isgst"       => $isgst,
                "display_gst" => $isgst ? 'block' : 'none'
            ];

            $services = [];

            $invoice_num = execute("SELECT count(*) cnt,invoice_data 
                            FROM invoices 
                            WHERE bookingid = {$id}");

            if ($invoice_num['cnt'] == 0) {
                $intitalroomamount = execute("SELECT service, charge, tax, vat, gst, hsn, id 
                                      FROM additional_services 
                                      WHERE hotel={$hotel} 
                                        AND ishotelroomtax=1");

                $no_of_room = execute("select COUNT(*)cnt from room_distribution where bookingid=$id");

                $discount = execute("SELECT discount_flat,discount_percent 
                             FROM payment_mode 
                             WHERE bookingid={$id} 
                               AND payment_type='discount' 
                               AND deleted=0");

                $discount_price = 0;
                $discount_type  = '';
                if (!empty($discount)) {
                    if ($discount['discount_flat'] != '') {
                        $discount_price = $discount['discount_flat'];
                        $discount_type  = 'flat';
                    } elseif ($discount['discount_percent'] != '') {
                        $discount_price = $discount['discount_percent'];
                        $discount_type  = '%';
                    }
                }

                $vatChecked = $intitalroomamount['vat'] == 1 ? 'checked' : '';
                $charge     = (float)$intitalroomamount['charge'];
                $subtotal   = $row['intialtariff'] / $no_of_room['cnt'];
                // print_r($row['intialtariff']);
                $services[] = [
                    'description'   => $intitalroomamount['service'],
                    'id'            => $intitalroomamount['id'],
                    'isvat'         => $vatChecked,
                    'hsn'           => $intitalroomamount['hsn'],
                    'rate'          =>  $subtotal,
                    'quantity'      => $no_of_room['cnt'],
                    'subtotal'      => $subtotal,
                    'taxable'       => $row['intialtariff'], // total cost
                    'discount'      => $discount_price,
                    'discount_type' => $discount_type,
                    'tax'           => $intitalroomamount['tax'] ?? 0,
                ];
            }

            $other_services = $conn->query("SELECT 
					gas.id,
					a.service,
					a.charge,
					a.tax,
					a.hsn,
					a.vat,
					a.gst,
					gas.quantity,
					gas.created_at,
					'guest_additional_services' AS source_table
					FROM 
						guest_additional_services gas
					LEFT JOIN 
						additional_services a ON a.id = gas.additional_service_id
					LEFT JOIN 
						additional_services_receipt ar ON ar.guest_service_id = gas.id
					WHERE 
						gas.bookingid = $id 
						AND gas.deleted = 0 AND a.ishotelroomtax=0
					;");

            while ($rs_row = $other_services->fetch_assoc()) {
                // print_r($rs_row);
                $date = new DateTime($rs_row['created_at']);
                $services[] = [
                    'description'   => $rs_row['service'],
                    'id'            => $rs_row['id'],
                    'isvat'         => $rs_row['vat'] == 1 ? 'checked' : '',
                    'hsn'           => $rs_row['hsn'],
                    'rate'          => intval($rs_row['charge']),
                    'quantity'      => $rs_row['quantity'],
                    'subtotal'      => (float)$rs_row['charge'],
                    'taxable'       => (float)$rs_row['charge'],
                    'discount'      => 0,
                    'discount_type' => '',
                    'tax'           => $rs_row['tax'] ?? 0,
                    'date' => $date->format('d-m-Y')
                ];
            }

            $advancepayment = execute("SELECT amount 
                                FROM payment_mode 
                                WHERE bookingid = $id 
                                AND payment_type NOT IN ('discount', 'payatcheckout', 'writeoff') 
                                AND deleted = 0;");

            // Check if advance payment is not found and set it to 0
            $advancepayment_amount = $advancepayment ? $advancepayment['amount'] : 0;

            $response = [
                "user_details" => $user_details,
                "services"     => $services,
                "gst"          => $gst,
                "advance_payment" => $$advancepayment_amount
            ];

            echo json_encode($response, JSON_PRETTY_PRINT);
        } else if (isset($_GET['action']) && $_GET['action'] === 'bookings') {
            $page  = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 10;
            $offset = ($page - 1) * $limit;
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

            $qry = $base_query . " GROUP BY b.id ORDER BY b.id DESC LIMIT $limit OFFSET $offset";

            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                $entries = [];
                while ($row = $result->fetch_assoc()) {
                    $row['booking_reference'] = $row['id'];
                    $voucher = generateAndDownloadVoucher($row['id']);
                    $row['voucher'] = $voucher['download'];
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
            $qry = $base_query . $filt . " GROUP BY iv.id ORDER BY iv.id DESC";

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

                    $qry = $base_query . $filt;

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
                b.id, b.intialtariff, b.hours, b.reg_date, u.id_proof_path, u.id_proof, b.usercheckedin, b.usercheckedout,
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
            $usercheckedout = new DateTime($row['usercheckedout']);
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

            $hotelId = $_GET['hotel'];
            $roomtypeId = $userInfo['id'];
            // Second query: fetch all roomnumbers for this hotel + roomtype

            $availableRooms = execute("
    SELECT GROUP_CONCAT(rn.id) AS available_rooms
    FROM roomnumbers rn
    WHERE rn.roomtype = $roomtypeId
    AND rn.id NOT IN (
        SELECT rd.roomnumber 
        FROM bookings b
        JOIN room_distribution rd ON rd.bookingid = b.id
        WHERE b.status IN ('Scheduled', 'Cancelled') 
        AND rd.isRoomAssigned = 1
        AND (
            (
                ('" . $checkindate->format("Y-m-d H:i") . "' >= b.checkindatetime 
                AND '" . $checkoutdate->format("Y-m-d H:i") . "' <= b.checkoutdatetime) 
                OR 
                ('" . $checkindate->format("Y-m-d H:i") . "' <= b.checkindatetime 
                AND '" . $checkoutdate->format("Y-m-d H:i") . "' >= b.checkoutdatetime) 
            )
        )
    )
    AND rn.id NOT IN (
        SELECT roomnumber FROM blocked_roomnumbers 
        WHERE bdate = '" . $checkindate->format("Y-m-d") . "'
    )
");

            $roomNumbers = [];
            $length = 0;

            if (!empty($availableRooms['available_rooms'])) {
                $assignedRooms = explode(',', $availableRooms['available_rooms']);

                foreach ($assignedRooms as $roomNumber) {
                    $length++;
                    $roomNumber = (int) $roomNumber; // sanitize
                    $roomnum = execute("SELECT roomnumber FROM roomnumbers WHERE id = $roomNumber");

                    $roomNumbers[] = [   // append instead of overwrite
                        'roomnumber' => $roomnum['roomnumber'],
                        'id'         => $roomNumber,
                    ];
                }
            }

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

            $filt .= " AND b.id = " . $row['id'];
            $qry = $base_query . $filt . " GROUP BY b.id ORDER BY b.id DESC";
            $resultdata = $conn->query($qry);
            $invoiceUrl = null;
            if ($resultdata->num_rows > 0) {
                $rowt = $resultdata->fetch_assoc();
                $invoice_num = execute("SELECT invoice_number, COUNT(*) cnt FROM invoices WHERE bookingid = {$rowt['id']}");
                $isinvoiceCreated = 0;
                $date = new DateTime($row['checkindatetime']);
                if ($invoice_num['cnt'] > 0) {
                    $isinvoiceCreated = 1;
                }
                if ($isinvoiceCreated == 1) {
                    $invoiceUrl = "https://www.pmseasy.in/pms/LBF_finalinvoice.php?id={$rowt['roomnumberid']}&date=" . $date->format("Y-m-d") . "&hotel=" . $hotelId;
                    // print_r($invoiceUrl);
                }
            }
            $usercheckindate = new DateTime($row['usercheckedin']);
            $room_distribution = execute("select b.id,u.fullname,u.contact,u.email,u.address1,u.id_proof_path,b.checkindatetime,b.checkoutdatetime,GROUP_CONCAT(rd.roomnumber) AS roomnumbers,count(rd.roomnumber)roomnumbercount,GROUP_CONCAT(rd.id) AS roomdistriid,GROUP_CONCAT(rd.isRoomAssigned) AS isRoomAssigned from bookings b join users u on b.guestid=u.id join room_distribution rd on rd.bookingid=b.id  where b.id=$row[id]");
            $roomdisid = explode(',', $room_distribution['roomdistriid']);

            $assigned_room = execute("
    SELECT rd.*, rn.roomnumber as roomno
    FROM room_distribution rd
    JOIN roomnumbers rn ON rd.roomnumber = rn.id
    WHERE rd.bookingid = {$row['id']}
");
            $assigned_room_no = null;
            if ($assigned_room['isRoomAssigned'] == 1) {
                $assigned_room_no = $assigned_room['roomno'];
            }

            $response = [
                "invoiceUrl" => $invoiceUrl,
                "roomNumbers" => $roomNumbers,
                "roomdisid" => $roomdisid,
                "assigned_room_no" => $assigned_room_no,
                "client_details" => [
                    "booking_reference" =>  $row['id'],
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
                    "usercheckindate" => $usercheckindate->format("d-m-Y"),
                    "usercheckoutdate" => $usercheckedout ? $usercheckedout->format("d-m-Y") : null,
                    "room_type" => $userInfo['roomtype'],
                    "no_of_rooms" => $no_of_room['cnt'],
                    "no_of_nights" => ($row['checkindate'] || $row['usercheckedin'])
                        ? max(
                            0,
                            (new DateTime($row['usercheckedin'] ?: $row['checkindate']))->setTime(0, 0, 0)
                                ->diff(
                                    (new DateTime($row['usercheckedout'] ?: $row['checkoutdate']))->setTime(0, 0, 0)
                                )->days
                        )
                        : 0,
                    "usercheckedin" => $row['usercheckedin'],
                    "usercheckedout" => $row['usercheckedout']
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
            while ($rows = $additionalServices->fetch_assoc()) {
                $services[] = $rows;
            }

            $response['additional_services'] = $services;

            $additional_service = $conn->query("SELECT gas.additional_service_id, gas.created_at, gas.quantity, gas.id, a.service, a.charge, a.gst, a.vat, a.tax,asr.amount 
								FROM guest_additional_services gas 
								JOIN additional_services_receipt asr on asr.guest_service_id=gas.id
								JOIN additional_services a ON a.id = gas.additional_service_id 
								WHERE gas.bookingid = $row[id] AND gas.deleted = 0 
								ORDER BY gas.id DESC");

            $booked_service = [];
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
                $booked_service[] = [
                    'service' => $rs_row['service'],
                    'created_at_formatted_temp' => $created_at_formatted_temp,
                    'quantity' => $rs_row['quantity'],
                    'charge' => $rs_row['charge'],
                    'gst' => $gst,
                    'vat' => $vat,
                    'total' => $total,
                    'id' => $rs_row['id'],
                    'additional_service_id' => $rs_row['additional_service_id']
                ];
            }

            $response['booked_service'] = $booked_service;
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
                    'total_rooms' => (int) $room['total_rooms']
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
                    $bookedRooms = isset($bookings[$roomTypeId][$currentDate]) ? (int) $bookings[$roomTypeId][$currentDate] : 0;
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
        } else if (isset($_GET['action']) && $_GET['action'] === 'today_assigned_rooms') {
            $hotelId = $_GET['hotelID'] ?? null;

            if (empty($hotelId)) {
                echo json_encode(['error' => 'Missing hotelID']);
                exit;
            }

            $today = date('Y-m-d');

            $query = $conn->query("
        SELECT 
            rn.id AS roomid,
            rn.roomnumber,
            rt.id AS roomtype_id,
            rt.roomtype
        FROM roomnumbers rn
        JOIN roomtypes rt ON rt.id = rn.roomtype
        WHERE rt.hotel = '$hotelId'
    ");

            if (!$query) {
                echo json_encode(['error' => 'Failed to fetch assigned rooms']);
                exit;
            }

            $rooms = [];
            $roomTypesMap = [];

            while ($row = $query->fetch_assoc()) {
                $bookingDetails = getBookingDetailsForRoom($conn, $row['roomid'], $today, $hotelId);

                // Populate room list
                $rooms[] = [
                    'roomnumber'    => $row['roomnumber'],
                    'roomid'        => $row['roomid'],
                    'roomtype_id'   => $row['roomtype_id'],
                    'roomtype'      => $row['roomtype'],
                    'booking'       => $bookingDetails
                ];

                // Group by roomtype_id
                $roomTypeKey = $row['roomtype_id'];
                if (!isset($roomTypesMap[$roomTypeKey])) {
                    $roomTypesMap[$roomTypeKey] = [
                        'roomtype_id' => $row['roomtype_id'],
                        'roomtype'    => $row['roomtype'],
                        'rooms'       => []
                    ];
                }

                $roomTypesMap[$roomTypeKey]['rooms'][] = [
                    'roomid'     => $row['roomid'],
                    'roomnumber' => $row['roomnumber']
                ];
            }

            // Convert associative map to indexed array
            $roomtypes = array_values($roomTypesMap);

            echo json_encode([
                'roomtypes' => $roomtypes,
                'rooms'     => $rooms
            ]);
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
        // print_r(is_jwt_valid($jwt));
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
                $checkoutdatetime = new DateTime($input['checkout'] . "T11:00:00");
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
                // if ($res === true) {

                $base_query = "SELECT b.id, b.reg_date, b.status, u.company AS hotel, c.city AS city, b.source, u_gst.fullname, b.checkindatetime, b.checkoutdatetime, b.hours, r.roomtype, b.declaredtariff,rn.id as roomid, b.ip, COUNT(rd.roomnumber) AS cnt 
                   FROM bookings b 
                   JOIN room_distribution rd ON rd.bookingid = b.id 
                   JOIN roomnumbers rn ON rn.id = rd.roomnumber 
                   JOIN roomtypes r ON r.id = rn.roomtype 
                   JOIN hotels h ON r.hotel = h.id 
                   JOIN users u ON u.id = h.user 
                   JOIN cities c ON c.id = u.city 
                   JOIN users u_gst ON u_gst.id = b.guestid where b.id = $bookingid";

                $result = $conn->query($base_query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $roomid = $row['roomid'];
                    }
                }
                // print_r($roomid);
                $voucher = generateAndDownloadVoucher($bookingid);

                if ($email) {

                    $subject = "PMSEASY";
                    $message = "
                        <html>
                        <body>
                            <p>Hey,</p>
                            <p>Thank you for booking hotel with pmseasy. Please find the attached voucher.</p>
                           
                        </body>
                        </html>
    	                ";
                    $cc = "";
                    $bcc = "";
                    $from = "pmseasy";
                    // myemail5($email, $subject, $message, $cc, $bcc, $from,$voucher['content']);

                    try {
                        myemail5($email, $subject, $message, $cc, $bcc, $from, $voucher['content']);
                    } catch (Exception $e) {
                        // Log the error but don't stop execution
                        error_log("Email sending failed: " . $e->getMessage());
                    }

                    $wa_message = "Hey,\n\n"
                        . "Thank you for booking hotel with pmseasy. Please find the attached voucher.\n\n"
                        . "Team PMSEasy";

                    $urlEncodedMessage = urlencode($wa_message);

                    sendwhatsapp($mobile_number, $urlEncodedMessage);
                }

                echo json_encode([
                    "success" => true,
                    "message" => "Reservation Booked Successfully",
                    "bookingId" => $bookingid,
                    "roomid" => $roomid,
                    "voucher" => $voucher['download']
                ]);


                // }
            } else if ($action === 'book_banqueteasy') {
                $banquetBookingId = null; // Start with null instead of 1
                $segment = $input['segment'];
                $fullname = $input['guest']['firstName'] . " " . $input['guest']['lastName'];
                $phone = $input['guest']['phone'];
                $email = $input['guest']['email'];
                $address = $input['guest']['address']['line1'] . ", " . $input['guest']['address']['city'] . ", " . $input['guest']['address']['state'] . ", " . $input['guest']['address']['country'] . " - " . $input['guest']['address']['zipCode'];
                $specialRequests = $input['specialRequests'];
                $amountbeforetax = $input['amount']['amountBeforeTax'];
                $amountaftertax = $input['amount']['amountAfterTax'];
                $bookedOn = $input['bookedOn'];
                $now = new DateTime();
                $ticker = new DateTime();
                $ticker->modify("+12 minutes");

                $hotelid = execute("select h.id from hotels h JOIN users u ON h.user = u.id where u.cm_company_name = '$hotelCode'");
                $guestid = insert("insert into users (fullname, email, contact, address1, groupid) values ('$fullname', '$email', '$phone', '$address', 5)");

                $payment_inserted = false;

                foreach ($input['rooms'] as $room) {
                    $roomtype = $room['roomCode'];
                    $nofadults = $room['occupancy']['adults'];
                    $nofchildren = $room['occupancy']['children'];
                    $roomtypeid = execute("select id from roomtypes where cmroomid='$roomtype' and hotel= '$hotelid[id]'");

                    foreach ($room['prices'] as $price) {

                        $checkin = $price['checkin'] . " 12:00:00";
                        $checkout = $price['checkout'] . " 11:00:00";

                        $bookingid = insert("insert into bookings (checkindatetime, checkoutdatetime, hours, guestid, paid, ticker, reg_date, ip, source, hoteltariff, declaredtariff, intialtariff, specialrequest, banquet_booking_id) 
                    values ('$checkin', '$checkout', 0, '$guestid', 1, '" . $ticker->format("Y-m-d H:i:s") . "', '$bookedOn', '192.168.1.15', '$segment', '$amountbeforetax', '$amountaftertax', '$amountaftertax', '$specialRequests', " . ($banquetBookingId ?? 'NULL') . ")");

                        // Only set banquetBookingId ONCE - for the first booking
                        if ($banquetBookingId === null) { // Changed condition
                            $banquetBookingId = $bookingid; // Set it once and never change
                        }

                        // insert payment only once for the first booking
                        if (!$payment_inserted) {
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

                            $paymentmodeid = insert("insert into payment_mode (payment_type, date_of_payment, txnid, cheque_bank, cheque_no, cheque_date, comment, bookingid, amount, discount_flat, discount_percent, discount_type)
                        values ('$payment_type', '$date_of_payment', '$txnid', '$cheque_bank', '$cheque_no', '$cheque_date', '$comment', '$bookingid', '$amount_inp', '$flat_discount', '$percent_discount', '$discount_type')");

                            insert("insert into payment_mode_receipt (paymentmodeid, bookingid, amount) values ('$paymentmodeid', '$bookingid', '$amount_inp')");

                            $payment_inserted = true;
                        }

                        $roomnumbers = $conn->query("select id from roomnumbers where roomtype='$roomtypeid[id]' and active=1");
                        $free_room_number = null;

                        while ($roomnumbers_row = $roomnumbers->fetch_assoc()) {
                            $rid = $roomnumbers_row['id'];

                            $x = execute("select count(*) as bookedrooms from bookings b 
                        join room_distribution rd on rd.bookingid = b.id 
                        where b.status IN ('Scheduled', 'Cancelled') 
                        and rd.roomnumber = $rid 
                        and (
                            (
                                ('$checkin' >= b.checkindatetime and '$checkout' <= b.checkoutdatetime) 
                                or ('$checkin' <= b.checkindatetime and '$checkout' >= b.checkoutdatetime)
                                or ('$checkin' <= b.checkindatetime and '$checkout' >= b.checkindatetime)
                                or ('$checkin' <= b.checkoutdatetime and '$checkout' >= b.checkoutdatetime)
                            )
                            and b.paid = 1
                            or (
                                (
                                    ('$checkin' >= b.checkindatetime and '$checkout' <= b.checkoutdatetime) 
                                    or ('$checkin' <= b.checkindatetime and '$checkout' >= b.checkoutdatetime)
                                    or ('$checkin' <= b.checkindatetime and '$checkout' >= b.checkindatetime)
                                    or ('$checkin' <= b.checkoutdatetime and '$checkout' >= b.checkoutdatetime)
                                )
                                and b.ticker >= '" . $now->format("Y-m-d H:i:s") . "'
                            )
                        )");

                            if ($x['bookedrooms'] == 0) {
                                $free_room_number = $rid;
                                break;
                            }
                        }

                        if ($free_room_number !== null) {
                            insert("insert into room_distribution (bookingid, roomnumber, adults, children, child1age, child2age) 
                        values ($bookingid, $free_room_number, $nofadults, $nofchildren, 0, 0)");
                        } else {
                            http_response_code(400);
                            echo json_encode([
                                "success" => false,
                                "message" => "No available rooms for booking from $checkin to $checkout"
                            ]);
                            return;
                        }
                    }
                }
                $conn->query("UPDATE bookings SET banquet_booking_id = '$banquetBookingId' WHERE id = '$banquetBookingId' AND banquet_booking_id IS NULL");

                echo json_encode([
                    "success" => true,
                    "message" => "All blocks booked successfully",
                    "bookingId" => $banquetBookingId
                ]);
            } else if ($action === 'check') {
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
            } else if ($action === 'cancel') {
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
                echo json_encode($response);
            }
        } else {
            echo json_encode(['error' => 'Invalid or expired token']);
        }
    } else if (isset($input['post_action']) && $input['post_action'] == 'user_login') {
        if (isset($input['username'])) {
            $username = $input['username'];
            $password = $input['password'];

            $stmt = $conn->prepare('SELECT * FROM users WHERE contact = ?');
            $stmt->bind_param('s', $username); // 's' means the parameter is a string
            $stmt->execute();
            $result = $stmt->get_result();
        } else if (isset($input['banquet_token'])) {
            $banquet_token = $input['banquet_token'];
            $result = $conn->query("select u.id,u.device_token,u.password from hotels h join users u on u.id=h.user where h.be_api_token='$banquet_token'");

            $tempresult = $conn->query("select u.email,u.password from hotels h join users u on u.id=h.user where h.be_api_token='$banquet_token'");
            $tr = $tempresult->fetch_assoc();
            $username = $tr['email'];
            $password = $tr['password'];
        }

        $device_token = $input['device_token'];
        // Check if the user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];
            // Verify the password (assuming it is hashed in the database)
            if ($user['password'] === $password) { // If password is correct, check subscription status
                $hotel_query = "SELECT id FROM hotels WHERE user = '$user_id'";
                $hotel_result = $conn->query($hotel_query);
                if ($user['groupid'] == 0) {
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
                    echo json_encode(['token' => $jwt, 'hotel_id' => $hotel_id, 'user_id' => $user_id]);
                    exit;
                }
                if ($hotel_result->num_rows > 0) {
                    $hotel = $hotel_result->fetch_assoc();
                    $hotel_id = $hotel['id'];
                    $subscription_query = "SELECT * FROM pms_subscriptions 
                               WHERE hotel_id = '$hotel_id' 
                               ORDER BY id DESC LIMIT 1";
                    $subscription_result = $conn->query($subscription_query);
                    if ($subscription_result->num_rows > 0) { // If subscription is active, generate JWT
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
                        echo json_encode(['token' => $jwt, 'hotel_id' => $hotel_id, 'user_id' => $user_id]);
                    } else {
                        http_response_code(403);
                        echo json_encode(['error' => 'Subscription is expired']);
                    }
                } else {
                    echo json_encode(['error' => 'No hotel found for this user.']);
                }
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

        if (isset($stmt))
            $stmt->close(); // Close the statement
        $conn->close(); // Close the connection
        exit;
    } else if (isset($input['post_action']) && $input['post_action'] == 'pos_login') {
        if (!isset($input['username'], $input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
            exit;
        }

        $username = $input['username'];
        $password = $input['password'];
        $stmt = $conn->prepare('SELECT * FROM hotels WHERE pos_username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];

            if ($user['pos_password'] === $password) { // If password is correct, check subscription status
                $subscription_query = "SELECT * FROM pms_subscriptions WHERE hotel_id = '$user_id' ORDER BY id DESC LIMIT 1";
                $subscription_result = $conn->query($subscription_query);

                if ($subscription_result->num_rows > 0) {
                    $headers = ['alg' => 'HS256', 'typ' => 'JWT'];
                    $payload = ['user_id' => $user_id, 'user' => $username, 'exp' => time() + 3600];
                    $jwt = generate_jwt($headers, $payload);
                    echo json_encode(['token' => $jwt, 'hotel_id' => $user_id]);
                } else {
                    http_response_code(403);
                    echo json_encode(['error' => 'Subscription is expired']);
                }
            } else {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid credentials']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }

        if (isset($stmt)) $stmt->close();
        $conn->close();
        exit;
    } else if (isset($input['post_action']) && $input['post_action'] === 'add_guest_additional_service') {
        $services = $input['services']; // Expecting array of services
        $bookingid = $input['bookingid'];
        $hotelId = $npinput['hotel'];     // New: hotel ID from request
        $user = $iut['user'] ?? 'user'; // Fallback for task log
        // echo $services;

        $invoice = execute("SELECT isgst FROM invoice_setup WHERE hotel='$hotelId'");
        $isgst = $invoice['isgst'];
        foreach ($services as $service) {
            $date = new DateTime($service['date']);
            $quantity = $service['quantity'];
            $order_type = $conn->real_escape_string($service['order_type'] ?? 'room service');
            $charge = $conn->real_escape_string($service['charge'] ?? 0);
            $tax = $conn->real_escape_string($service['tax'] ?? 0);

            // Step 1: Insert service into additional_services
            $conn->query("
            INSERT INTO additional_services (service, charge, hotel, created_at, tax, deleted, ishotelroomtax)
            VALUES ('$order_type', '$charge', '$hotelId', '" . date("Y-m-d H:i:s") . "', '$tax', 1, 0)
            ");
            $additional_service_id = $conn->insert_id;

            // Step 2: Prepare other optional data
            $kot = $conn->real_escape_string($service['kot'] ?? '');
            $order_number = $conn->real_escape_string($service['order_number'] ?? '');
            $table_number = $conn->real_escape_string($service['table_number'] ?? '');
            $order_datetime = $conn->real_escape_string($service['order_datetime'] ?? null);
            $payment_status = $conn->real_escape_string($service['payment_status'] ?? 'unpaid');
            $payment_mode = $conn->real_escape_string($service['payment_mode'] ?? '');
            $payment_details = $conn->real_escape_string($service['payment_details'] ?? '');
            $ordered_menu_items = $conn->real_escape_string(json_encode($service['ordered_menu_items'] ?? []));
            $customer_name = $conn->real_escape_string($service['customer_name'] ?? '');
            $customer_mobile = $conn->real_escape_string($service['customer_mobile'] ?? '');

            // Step 3: Insert into guest_additional_services
            $guest_service_id = insert("
            INSERT INTO guest_additional_services (
                additional_service_id, bookingid, quantity, created_at,
                kot, order_number, table_number, order_datetime,
                payment_status, payment_mode, payment_details, ordered_menu_items,
                customer_name, customer_mobile
            ) VALUES (
                '$additional_service_id', '$bookingid', '$quantity', '" . $date->format("Y-m-d") . "',
                '$kot', '$order_number', '$table_number', '$order_datetime',
                '$payment_status', '$payment_mode', '$payment_details', '$ordered_menu_items',
                '$customer_name', '$customer_mobile'
            )
            ");

            // Step 4: Pricing logic
            $service_quantity = execute("SELECT quantity FROM guest_additional_services WHERE id='$guest_service_id'");
            $oldtariff = execute("SELECT declaredtariff FROM bookings WHERE id='$bookingid'");
            $service_tax = $isgst ? $tax : 0;

            $unit_price = $charge + (($service_tax / 100) * $charge);
            $total_amount = $unit_price * $service_quantity['quantity'];
            $newprice = $oldtariff['declaredtariff'] + $total_amount;

            // Step 5: Store in additional_services_receipt
            $conn->query("
            INSERT INTO additional_services_receipt (
                additional_service_id, bookingid, amount, guest_service_id
            ) VALUES (
                '$additional_service_id', '$bookingid', '$total_amount', '$guest_service_id'
            )
            ");

            // Step 6: Update declared tariff in booking
            $result = $conn->query("UPDATE bookings SET declaredtariff='$newprice' WHERE id='$bookingid'");
        }

        if ($result) {
            add_hotel_task_log($user, $hotelId, "Created new additional services for FR$bookingid", 'BOOKINGS');
            echo json_encode(['status' => 'success', 'message' => 'Room service recorded successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update booking']);
        }
    } else if (isset($input['post_action']) && $input['post_action'] == 'check_room_availability') {
        $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        if (is_jwt_valid($jwt))
        // REPLACE THIS FOR PRODUCTION WITH THE LINE ABOVE THE COMMENT

        // if (true) 
        {

            $rt = $input['rt'];
            $date = $input['cidt'];
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
			)f");
                $previouslybookedrooms = $rs_row['cnt'];

                $rs_row = execute("select count(*)totalrooms,r.chargeperextra from roomtypes r left join roomnumbers rn on rn.roomtype=r.id where r.id=$rt and r.active=1 and (r.adults+r.extraallowed)>=$adults and r.children>=$children");
                $totalrooms = $rs_row['totalrooms'];

                $hotel = $input['hotel'];
                $result = $conn->query("SELECT fulldaytariff, cmroomid, roomtype, id FROM roomtypes WHERE hotel='$hotel' AND id=$rt");

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
				WHERE rrp.roomtype = $rt 
				AND rrp.deleted = 0;";
                $result = $conn->query($qry);

                $roomtype = execute("SELECT r.adults,r.children,r.extraallowed,r.chargeperextra from roomtypes r where r.id=$rt;");
                if ($result->num_rows > 0) {
                    $data = array();
                    $first = true;

                    while ($row = $result->fetch_assoc()) {

                        // Rename fulldaytariff to minvalue in first row
                        $row['minvalue'] = $row['fulldaytariff'];
                        unset($row['fulldaytariff']);
                        $first = false;

                        $data[] = $row;
                    }
                }
                if ($rpid && $rate_plan) {
                    if ($totalrooms - $previouslybookedrooms < $no_of_rooms) {
                        echo json_encode(['total_rooms' => 0, 'extra_charge' => $extra_charge, "rooms" => $rooms, "data" => $data]);
                    } else {
                        $total_room = ($totalrooms - $previouslybookedrooms);
                        echo json_encode(['total_rooms' => $total_room, 'extra_charge' => $extra_charge, "rooms" => $rooms, "data" => $data]);
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
    } else if (isset($input['post_action']) && $input['post_action'] == 'checkout') {
        $id      = intval($input['booking_id']);
        $chkid   = isset($input['checkin_date']) ? $input['checkin_date'] : null; // d-m-Y
        $chkit   = isset($input['checkin_time']) ? $input['checkin_time'] : null; // h:i a
        $chkod   = isset($input['checkout_date']) ? $input['checkout_date'] : null; // d-m-Y
        $chkot   = isset($input['checkout_time']) ? $input['checkout_time'] : null; // h:i a
        // echo "Hello";
        // convert check-in datetime
        if ($chkid && $chkit) {
            $checkin = DateTime::createFromFormat("d-m-Y h:i a", "$chkid $chkit");
            $checkinFormatted = $checkin ? $checkin->format("Y-m-d H:i:s") : null;
        }

        // convert check-out datetime
        if ($chkod && $chkot) {
            $checkout = DateTime::createFromFormat("d-m-Y h:i a", "$chkod $chkot");
            $checkoutFormatted = $checkout ? $checkout->format("Y-m-d H:i:s") : null;
        } else {
            $checkoutFormatted = "0000-00-00 00:00:00";
        }

        // fetch booking details
        $sql = "SELECT b.id, hu.company, u.fullname, u.email, u.contact, 
                   b.checkindatetime, b.checkoutdatetime, b.hours,
                   (b.declaredtariff-b.pcdiscount+b.hotelst+b.hsbc+b.hkcc+b.frotelst+b.fsbc+b.fkcc+b.lt+b.sc+b.stonsc) AS total,
                   b.usercheckedin, b.usercheckedout
            FROM bookings b
            JOIN room_distribution rd ON rd.bookingid = b.id 
                         JOIN roomnumbers rn ON rn.id = rd.roomnumber 
                         JOIN roomtypes r ON r.id = rn.roomtype
            JOIN hotels h ON h.id = r.hotel
            JOIN users hu ON hu.id = h.user
            JOIN users u ON u.id = b.guestid
            WHERE b.id = $id";
        $row = execute($sql);

        if (!$row) {
            echo json_encode(["status" => "error", "message" => "Booking not found"]);
            exit;
        }

        // stay period
        if ($row['hours'] > 0 && $row['hours'] < 24) {
            $stay_period = $row['hours'] . " hours";
        } else {
            $nights = date_diff(date_create($row['checkindatetime']), date_create($row['checkoutdatetime']))->format("%a");
            $stay_period = $nights . " nights";
        }

        // build email body
        if ($chkod && $chkot) {
            $emailbody = "Guest has just checked out.<br><br>
            <b>#BRN:</b> FR$id<br><br>
            <b>Hotel Name:</b> {$row['company']}<br><br>
            <b>Guest Name:</b> {$row['fullname']}<br><br>
            <b>Email:</b> {$row['email']}<br><br>
            <b>Contact:</b> {$row['contact']}<br><br>
            <b>Booked Check in:</b> " . date("d-M-Y h:i A", strtotime($row['checkindatetime'])) . "<br><br>
            <b>Booked Check out:</b> " . date("d-M-Y h:i A", strtotime($row['checkoutdatetime'])) . "<br><br>
            <b>Guest Checked in:</b> " . date("d-M-Y h:i A", strtotime($row['usercheckedin'])) . "<br><br>
            <b>Guest Checked out:</b> $chkod $chkot<br><br>
            <b>Stay Period:</b> $stay_period<br><br>
            <b>Total Amount:</b> " . round($row['total'], 2);
        } else {
            $emailbody = "Guest has just checked in.<br><br>
            <b>#BRN:</b> FR$id<br><br>
            <b>Hotel Name:</b> {$row['company']}<br><br>
            <b>Guest Name:</b> {$row['fullname']}<br><br>
            <b>Email:</b> {$row['email']}<br><br>
            <b>Contact:</b> {$row['contact']}<br><br>
            <b>Booked Check in:</b> " . date("d-M-Y h:i A", strtotime($row['checkindatetime'])) . "<br><br>
            <b>Booked Check out:</b> " . date("d-M-Y h:i A", strtotime($row['checkoutdatetime'])) . "<br><br>
            <b>Guest Checked in:</b> " . date("d-M-Y h:i A", strtotime($checkinFormatted)) . "<br><br>
            <b>Stay Period:</b> $stay_period<br><br>
            <b>Total Amount:</b> " . round($row['total'], 2);
        }

        // update DB
        $q = "UPDATE bookings SET usercheckedin='$checkinFormatted', usercheckedout='$checkoutFormatted' WHERE id=$id";
        $res = $conn->query($q);

        if ($res) {
            // send email
            myemail("booking@frotels.com", "Guest Checkin/out", $emailbody, "", "", "booking@frotels.com");

            $response = [
                "status" => "success",
                "message" => "Checkin/Checkout updated",
                "data" => [
                    "booking_id" => $id,
                    "checkin" => $checkinFormatted,
                    "checkout" => $checkoutFormatted,
                    "stay_period" => $stay_period,
                    "total" => round($row['total'], 2)
                ]
            ];
        }
        echo json_encode($response);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

function getBookingDetailsForRoom($conn, $roomId, $date, $hotelId)
{
    $response = [];

    // Fetch Booking Details
    $bdate = date_create($date);
    $now = date_create(date("Y-m-d H:i"));
    $checkinTime = date_create($bdate->format("Y-m-d") . " 12:00");

    $roomId = intval($roomId); // ensure it's numeric
    $checkinTime = $checkinTime->format("Y-m-d H:i");

    if (!$roomId || !$checkinTime) {
        return []; // or log error and return empty if invalid inputs
    }

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
AND rd.roomnumber = $roomId 
AND (b.checkindatetime <= '$checkinTime' 
AND b.checkoutdatetime >= '$checkinTime') 
LIMIT 1";

    $booking = execute($bookingQuery);
    if (!$booking) return [];

    $checkindate = new DateTime($booking['checkindatetime']);
    $checkoutdate = new DateTime($booking['checkoutdatetime']);

    // Room count
    $roomCount = execute("SELECT COUNT(*) AS cnt FROM room_distribution WHERE bookingid = {$booking['id']}");

    // Additional services
    $additionalServices = $conn->query("SELECT id, service, charge, gst, vat, tax FROM additional_services WHERE hotel = $hotelId AND ishotelroomtax = 0 AND deleted = 0");
    $services = [];
    while ($row = $additionalServices->fetch_assoc()) {
        $services[] = $row;
    }

    return [
        "client_details" => [
            "booking_reference" => 'FR' . $booking['id'],
            "fullname" => $booking['fullname'],
            "contact" => "+91 " . $booking['contact'],
            "email" => $booking['email'],
            "address" => $booking['address1'],
            "id_proof" => $booking['id_proof'],
            "id_proof_path" => "uploads/" . $booking['id_proof_path'],
        ],
        "room_details" => [
            "checkin_date" => $checkindate->format("d-m-Y"),
            "checkout_date" => $checkoutdate->format("d-m-Y"),
            "no_of_rooms" => $roomCount['cnt'],
            "no_of_nights" => $checkindate->diff($checkoutdate)->days,
        ]
    ];
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

function generateAndDownloadVoucher($booking)
{
    $filename = "Booking Confirmation";
    $url = "https://pmseasy.in/pms/voucher.php?id=" . $booking;
    $label = " Booking Confirmation";
    $data = array(
        "url"                => $url,
        "filename"            => $filename,
        "uniqueFilename"    => true,
        "label"                => $label,
        "paper"                => "A4",
        "orientation"        => "portrait",
        "printBackground"    => true
    );

    $jsonData = json_encode($data);
    $url = "https://okpdf.banqueteasy.com/api";
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));

    $server_response = curl_exec($ch);

    if (curl_errno($ch))
        $error = curl_error($ch);
    else
        $error = [];

    curl_close($ch);
    // dd($server_response);
    //printr(http_build_query($data));
    $server_response = json_decode($server_response, true);

    if ($server_response['success']) {
        $return['preview'] = $server_response['previewUrl'];
        $return['download'] = $server_response['downloadUrl'];
        $return['content'] = $server_response['content'];
        $return['error'] = '';
    } else {
        $return['preview'] = "";
        $return['download'] = "";
        $return['error'] = $error;
    }

    return $return;
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
