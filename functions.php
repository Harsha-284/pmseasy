<?php
include 'conn.php';
include 'udf.php';

function search_booking($rt, $cidt, $codt, $adults, $children)
{

    $rs_row = execute("select count(*)cnt from roomtypes where id=$rt and active=1");
    if ($rs_row['cnt'] == 1) {
        $now = new \DateTime();

        $row = execute("select rp.id from rate_plans rp where rp.hotel=(select hotel from roomtypes where id=$rt) and ('" . $cidt->format("Y-m-d") . "' between rp.validfrom and rp.validto)");

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

        $rs_row1 = execute("select count(*)totalrooms from roomtypes r left join roomnumbers rn on rn.roomtype=r.id where r.id=$rt and r.active=1 and (r.adults+r.extraallowed)>=$adults and r.children>=$children");
        $totalrooms = $rs_row1['totalrooms'];

        if ($totalrooms - $previouslybookedrooms < 0)
            return 0;
        else {
            $total_room = ($totalrooms - $previouslybookedrooms);
            return  $total_room;
        }
    } else
        return 0;
}

function updateCmAvailability($startdate, $enddate, $available, $roomtype, $hotelCode)
{
    // Construct the request body
    $body = array(
        "hotelCode" => $hotelCode,
        "updates" => array(
            array(
                "startDate" => $startdate,
                "endDate" => $enddate,
                "rooms" => array(
                    array(
                        "available" => $available,
                        "roomCode" => $roomtype
                    )
                )
            )
        )
    );

    // Convert the body to JSON format
    $jsonData = json_encode($body);

    // Initialize cURL session
    $ch = curl_init('https://live.aiosell.com/api/v2/cm/update/banqueteasy');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        echo "Error: $error";
        return false;
    }

    // Close cURL session
    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Check if the response data indicates success
    if (is_array($responseData) && isset($responseData['success']) && $responseData['success'] === true) {
        return true;
    }

    // Optionally log the response for debugging
    // echo json_encode($responseData);

    return false;
}

function updateSeasonalCmRate($hotelCode, $startDate, $endDate, $rate, $roomType, $ratePlan)
{
    // Construct the request body
    $body = array(
        "hotelCode" => $hotelCode,
        "updates" => array(
            array(
                "startDate" => $startDate,
                "endDate" => $endDate,
                "rates" => array(
                    array(
                        "roomCode" => $roomType,
                        "rate" => (float)$rate,
                        "rateplanCode" => $ratePlan
                    )
                )
            )
        )
    );

    // Convert the body to JSON format
    $jsonData = json_encode($body);

    // Initialize cURL session
    $ch = curl_init('https://live.aiosell.com/api/v2/cm/update-rates/banqueteasy');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        echo "Error: $error";
        return false;
    }

    // Close cURL session
    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Check if the response data indicates success
    if (is_array($responseData) && isset($responseData['success']) && $responseData['success'] === true) {
        return true;
    }

    // Optionally log the response for debugging
    // echo json_encode($responseData);

    return false;
}

function sendSampleRateUpdate()
{
    // Construct the request body with sample data
    $body = array(
        "hotelCode" => "empire-royale-hotel",
        "updates" => array(
            array(
                "startDate" => "2024-09-11",
                "endDate" => "2024-09-24",
                "rates" => array(
                    array(
                        "roomCode" => "non-ac-dorm-bed",
                        "rate" => 1749.0,
                        "rateplanCode" => "non-ac-dorm-bed-s-ep"
                    )
                )
            )
        )
    );

    // Convert the body to JSON format
    $jsonData = json_encode($body);

    // Initialize cURL session
    $ch = curl_init('https://live.aiosell.com/api/v2/cm/update-rates/banqueteasy');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        echo "Error: $error";
        return false;
    }

    // Close cURL session
    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Check if the response data indicates success
    if (is_array($responseData) && isset($responseData['success']) && $responseData['success'] === true) {
        return true;
    }

    // Optionally log the response for debugging
    // echo json_encode($responseData);

    return false;
}


function total_room_assigned($rt, $cidt, $codt, $adults, $children)
{
    global $conn;
    $rs_row = execute("SELECT COUNT(*) AS cnt FROM roomtypes WHERE id = $rt AND active = 1");
    if ($rs_row['cnt'] == 1) {
        $now = new \DateTime();

        // Fetch booked room numbers
        $rs_row = execute("
            SELECT GROUP_CONCAT(rd.roomnumber) AS roomnumbers FROM bookings b
            JOIN room_distribution rd ON rd.bookingid = b.id
            LEFT JOIN roomnumbers rn ON rn.id = rd.roomnumber
            WHERE b.status IN ('Scheduled', 'Cancelled') AND rn.roomtype = $rt 
            AND (
                (
                    ('" . $cidt->format("Y-m-d H:i") . "' >= b.checkindatetime AND '" . $codt->format("Y-m-d H:i") . "' <= b.checkoutdatetime) 
                    OR 
                    ('" . $cidt->format("Y-m-d H:i") . "' <= b.checkindatetime AND '" . $codt->format("Y-m-d H:i") . "' >= b.checkoutdatetime) 
                )
                AND b.paid = 1 AND rd.isRoomAssigned=0
            )
            UNION ALL
            SELECT roomnumber FROM blocked_roomnumbers WHERE bdate = '" . $cidt->format("Y-m-d") . "' AND roomnumber IN (SELECT id FROM roomnumbers WHERE roomtype = $rt)
        ");

        // Get list of booked and blocked room numbers
        $bookedRoomNumbers = !empty($rs_row['roomnumbers']) ? explode(',', $rs_row['roomnumbers']) : [];

        // Get total number of rooms for this room type
        $rs_row1 = execute("SELECT COUNT(*) AS totalrooms FROM roomnumbers WHERE roomtype = $rt");
        $totalRooms = $rs_row1['totalrooms'];

        // Get all room numbers for this room type
        $allRoomNumbers = $conn->query("SELECT id FROM roomnumbers WHERE roomtype = $rt");
        $allRoomNumbersArray = [];
        while ($room = $allRoomNumbers->fetch_assoc()) {
            $allRoomNumbersArray[] = $room['id'];
        }

        // Filter out booked room numbers
        $availableRoomNumbers = array_diff($allRoomNumbersArray, $bookedRoomNumbers);

        return $availableRoomNumbers; // Return array of available room numbers
    } else {
        return []; // Return an empty array if room type is not active
    }
}


