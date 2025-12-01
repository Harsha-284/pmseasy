<?php
include 'conn.php';
include 'functions.php';

function postResponse()
{
    global $conn;
    $input = json_decode(file_get_contents("php://input"), true);

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
        $checkindatetime = new DateTime($input['checkin']);
        $checkoutdatetime = new DateTime($input['checkout']);
        $specialRequests = $input['specialRequests'];
        $amountbeforetax = $input['amount']['amountBeforeTax'];
        $amountaftertax = $input['amount']['amountAfterTax'];
        $ticker = new \DateTime();
        $now = new \DateTime();
        $ticker->modify("+" . 12 . " minutes");

        $hotelid = execute("select h.id from hotels h JOIN users u ON h.user = u.id where u.cm_company_name = '$hotelCode';");

        $guestid = insert("insert into users (fullname,email,contact,address1) values ('$fullname','$email','$phone','$address');");

        $bookingid = insert("insert into bookings (checkindatetime, checkoutdatetime, hours, guestid, paid, ticker, reg_date, ip, source, hoteltariff, declaredtariff, specialrequest) 
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
                '" . $specialRequests . "'
            )");

        foreach ($input['rooms'] as $room) {
            $roomtype = $room['roomCode'];
            $nofadults = $room['occupancy']['adults'];
            $nofchildren = $room['occupancy']['children'];

            $roomtypeid = execute("select id from roomtypes where cmroomid='$roomtype' and hotel= '$hotelid[id]'");

            $roomnumbers = $conn->query("select id,roomnumber from roomnumbers where roomtype='$roomtypeid[id]' and active=1");
            $free_room_number = null;

            while ($roomnumbers_row = $roomnumbers->fetch_assoc()) {
                $x = execute("select count(*)bookedrooms from bookings b join room_distribution rd on rd.bookingid=b.id where rd.roomnumber=$roomnumbers_row[id] and 
						
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
                $result = $conn->query("insert into room_distribution (bookingid,roomnumber,adults,children,child1age,child2age) values ($bookingid,$free_room_number,$nofadults,$nofchildren,0,0)");
                if ($result === TRUE) {
                    $availble = search_booking($roomtypeid['id'], $checkindatetime, $checkoutdatetime, $nofadults, $nofchildren);
                    updateCmAvailability($checkindatetime->format("Y-m-d"), $checkoutdatetime->format("Y-m-d"), $availble, $roomtype, $hotelCode);
                }
            }
        }

        $response = array(
            "success" => true,
            "message" => "Reservation Booked Successfully",
        );
    } elseif ($action === 'check') {
        $checkindatetime = new DateTime($input['checkin']);
        $checkoutdatetime = new DateTime($input['checkout']);
        $avail = search_booking('2617', $checkindatetime, $checkoutdatetime, '1', "0");

        $response = [
            "success" => false,
            "message" => "Invalid action",
            "data" => $avail
        ];
    } else if ($action === 'modify') {
        $response = array(
            "success" => true,
            "message" => "Reservation Modified Successfully",
        );
    } elseif ($action === 'cancel') {
        $bookingid = $input['bookingId'];

        // Delete room distribution records
        $conn->query("delete from room_distribution where bookingid = '$bookingid'");

        // SOft Delete booking record
        $conn->query("update bookings set status='Cancelled' where id='$bookingid'");

        $response = [
            "success" => true,
            "message" => "Reservation Cancelled Successfully",
            "data" => $bookingid
        ];
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
}
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    getResponse();
}
else {
    echo "Invalid request method.";
}
