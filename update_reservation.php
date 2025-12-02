<?php
include 'conn.php';
include 'functions.php';
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
function postResponse()
{
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);
    //echo '<pre>'.print_r($input,true).'</pre>';
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
        $address2 = $input['address2'];
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

        $guestid = insert("insert into users (fullname,email,contact,address1,address2,groupid) values ('$fullname','$email','$phone','$address','$address2',5);");

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
        $res = updateCmAvailability($checkindatetime->format("Y-m-d"), $modifiedCheckOutDate->format("Y-m-d"), $availble, $roomtype, $hotelCode);
        if ($res === true) {
            // ------------------------------
            // SEND EMAIL + WHATSAPP
            // ------------------------------
            $hotelInfo = execute("SELECT h.id, u.company
                      FROM hotels h 
                      JOIN users u ON h.user = u.id 
                      WHERE u.cm_company_name = '$hotelCode'");

            $hotelid = $hotelInfo['id'];
            $hotelName = $hotelInfo['company'];
            // echo $email;
            // var_dump(empty($email));
            // if (!empty($email)) {
            // try {
            $subject = "Your Booking Confirmation – $hotelName";

            $message = "Dear $fullname,<br><br>"
                . "Thank you for choosing <strong>$hotelName</strong>.<br>"
                . "Your booking has been confirmed.<br><br>"
                . "<strong>Hotel:</strong> $hotelName<br>"
                . "Check-in: " . $checkindatetime->format('d-m-Y H:i') . "<br>"
                . "Check-out: " . $checkoutdatetime->format('d-m-Y H:i') . "<br><br>"
                . "Warm regards,<br>"
                . "$hotelName Team";

            $voucher = generateAndDownloadVoucher($bookingid);
            $voucherContent = $voucher['content'];

            $myemail = myemail5($email, $subject, $message, "", "", "PMSEasy", $voucherContent);
            // echo "<pre>";
            // print_r($myemail);
            // echo "</pre>";
            // print_r($myemail);
            // } catch (Exception $e) {
            //     error_log("Email sending failed: " . $e->getMessage());
            // }
            // }
            // ------------------------------
            // WhatsApp Message
            // ------------------------------
            $wa_message = "Hello $fullname,\n"
                . "Thank you for booking your stay at *$hotelName*.\n"
                . "Your reservation is confirmed!\n\n"
                . "Hotel: $hotelName\n"
                . "Check-in: " . $checkindatetime->format('d-m-Y H:i') . "\n"
                . "Check-out: " . $checkoutdatetime->format('d-m-Y H:i') . "\n\n"
                . "We look forward to hosting you.\n"
                . "- $hotelName Team";


            sendwhatsapp($phone, $wa_message);

            $response = array(
                "success" => true,
                "message" => "Reservation Booked Successfully",
            );
        }

        if (!isset($response)) {
            $response = [
                "success" => false,
                "message" => "Unknown error – no response created",
            ];
        }

        echo json_encode($response);
        exit;
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

        $conn->query("update bookings set status='Refunded',refund_amount='$refundAmount',cancellationreason='$refundReason',refund_date=now() where id=$bookingid");
        $conn->query("update room_distribution set deleted=1 where bookingid=$bookingid");

        $checkindatetime = new DateTime($bookingInfo['checkindatetime']);
        $checkoutdatetime = new DateTime($bookingInfo['checkoutdatetime']);

        $availble = search_booking($room_number_info['roomtype'], $checkindatetime, $checkoutdatetime, '0', '0');
        $res = updateCmAvailability($checkindatetime->format("Y-m-d"), $checkoutdatetime->format("Y-m-d"), $availble, $roomtypename['cmroomid'], $hotelCode);


        $hotelInfo = execute("SELECT h.id, u.company
                      FROM hotels h 
                      JOIN users u ON h.user = u.id 
                      WHERE u.cm_company_name = '$hotelCode'");

            $hotelid = $hotelInfo['id'];
            $hotelName = $hotelInfo['company'];
        // Fetch guest details
$guest = execute("SELECT fullname, contact FROM users WHERE id = (SELECT guestid FROM bookings WHERE id='$bookingid')");
$fullname = $guest['fullname'];
$phone = $guest['contact'];  // use same name as in your sendwhatsapp()

// Build WA message
$wa_message = "Hello $fullname,\n"
            . "Your reservation at *$hotelName* has been cancelled.\n\n"
            . "*Cancellation Details:*\n"
            . "Check-in: " . $checkindatetime->format('d-m-Y H:i') . "\n"
            . "Check-out: " . $checkoutdatetime->format('d-m-Y H:i') . "\n"
            . "Refund Amount: ₹$refundAmount\n"
            . "Reason: $refundReason\n\n"
            . "We hope to serve you in the future.\n"
            . "- $hotelName Team";

// Send WhatsApp message
sendwhatsapp($phone, $wa_message);

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
    } elseif ($action === 'update_cancel_request') {
        $bookingid = $input['bookingId'];
        $result = $conn->query("update bookings set status='Cancelled' where id=$bookingid");
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
            return;
        }
    } else {
        $response = [
            "success" => false,
            "message" => "Invalid action"
        ];
    }

    echo json_encode($response);
}

function getResponse()
{
    $response = [
        "success" => true,
        "message" => "Hello"
    ];
    echo json_encode($response);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    postResponse();
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    getResponse();
} else {
    echo "Invalid request method.";
}

function sendwhatsapp($cellno, $smstext)
{
    // $varsms = "authentic-key=31334e696b68696c73697240676d61696c2e636f6d3130301726726008&route=1&number=" . $cellno . "&message=" . $smstext;
    // $url = "http://wapp.powerstext.in/http-tokenkeyapi.php?" . $varsms;

    $url = "https://wapp.powerstext.in/http-tokenkeyapi.php?authentic-key=31334e696b68696c73697240676d61696c2e636f6d3130301726726008&route=1&number=" . $cellno . "&message=" . urlencode($smstext);
    // echo $url;
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPGET => true
    ]);

    $response = curl_exec($curl);
    // print_r($response);
    $error = curl_error($curl);
    // dd($error);
    curl_close($curl);

    if ($error) {
        return "cURL Error: " . $error;
    } else {
        return $response;
    }
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
