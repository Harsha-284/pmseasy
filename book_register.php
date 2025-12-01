<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Include your DB connection and helper functions
require_once 'conn.php';
require_once 'functions.php';
require_once 'udf.php';

// Decode the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);
// print_r($data);
// Check if action is set and valid
// if (!isset($data['action']) || $data['action'] !== 'register_hotel') {
//     echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
//     exit;
// }
if (isset($data['action']) && $data['action'] == 'register_hotel') {

    // Extract and sanitize input
    $usernamep  = str_replace("&quot;", "", $data['usernamep'] ?? '');
    $mobilep    = str_replace("&quot;", "", $data['mobilep'] ?? '');
    $agencyp    = str_replace("&quot;", "", $data['agencyp'] ?? '');
    $passwordp  = str_replace("&quot;", "", $data['passwordp'] ?? '');
    $emailp     = str_replace("&quot;", "", $data['emailp'] ?? '');
    $hotelp     = str_replace("&quot;", "", $data['hotelp'] ?? '');
    $addressp   = str_replace("&quot;", "", $data['addressp'] ?? '');
    $cityp      = str_replace("&quot;", "", $data['cityp'] ?? '');
    $sendmail   = str_replace("&quot;", "", $data['sendmail'] ?? '');

    // Your API logic here (existing code) ...

    // Check if user already exists
    $result = $conn->query("SELECT COUNT(*) AS cnt FROM users WHERE contact = '$mobilep'");
    $row = $result->fetch_assoc();

    if ($row['cnt'] > 0) {
        echo json_encode(['status' => 'fail', 'message' => 'Hotel already exists']);
        exit;
    }
    if (stripos($agencyp, 'chain') !== false) {
    $agencyp = 'chain';
} else {
    $agencyp = 'hotel';
}
    // Insert data based on agency type
    if ($agencyp === 'hotel') {
        $uid = insert("INSERT INTO users (groupid, fullname, password, company, email, contact, city, reg_date, address1, emailverified, mobileverified)
        VALUES (2, '$usernamep', '$passwordp', '$hotelp', '$emailp', '$mobilep', 35, NOW(), '$addressp', 1, 1)");
        $hotelid = insert("INSERT INTO hotels (admin, user) VALUES (0, $uid)");
    } elseif ($agencyp === 'chain') {
        $uid = insert("INSERT INTO users (groupid, fullname, password, company, email, contact, city, reg_date, address1, emailverified, mobileverified)
        VALUES (1, '$usernamep', '$passwordp', '$hotelp', '$emailp', '$mobilep', 35, NOW(), '$addressp', 1, 1)");
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid agency type']);
        exit;
    }

    // Send email if requested
    if (!empty($sendmail)) {
        $subject = "Signup Requested - PMSEASY";
        $message = "
        <html>
        <body>
            <p>Dear {$usernamep},</p>
            <p>Thank you for signing up with PMSEasy! You've made an excellent choice. Please allow us some time to verify and activate your account. We will reach out to you as soon as the process is complete.</p>
            <p>We have received the following details for your registration:</p>
            <h2>New " . ucfirst($agencyp) . " Signup</h2>
            <p><strong>Name:</strong> {$usernamep}</p>
            <p><strong>Email:</strong> {$emailp}</p>
            <p><strong>Mobile:</strong> {$mobilep}</p>
            <p><strong>Company:</strong> {$hotelp}</p>
            <p><strong>Address:</strong> {$addressp}</p>
            <p><strong>City:</strong> {$cityp}</p>
            <p><strong>Registered On:</strong> " . date("d-m-Y H:i:s") . "</p>
        </body>
        </html>
    ";

        $to = $emailp;
        $cc = "";
        $bcc = "nikhildk@aspiringwebsolutions.com";
        $from = "pmseasy";
        $mobile_number = "9552016400";
        $wa_message = "Dear {$usernamep},\n\n"
            . "Thank you for signing up with PMSEasy! You've made an excellent choice.\n\n"
            . "Please allow us some time to verify and activate your account. We will reach out to you as soon as the process is complete.\n\n"
            . "We have received the following details for your registration:\n\n"
            . "New " . ucfirst($agencyp) . " Signup\n"
            . "Name: {$usernamep}\n"
            . "Email: {$emailp}\n"
            . "Mobile: {$mobilep}\n"
            . "Company: {$hotelp}\n"
            . "Address: {$addressp}\n"
            . "City: {$cityp}\n"
            . "Registered On: " . date("d-m-Y H:i:s") . "\n\n"
            . "Warm regards,\n"
            . "Team PMSEasy";

        $urlEncodedMessage = urlencode($wa_message);

        sendwhatsapp($mobile_number, $urlEncodedMessage);

        if (myemail($to, $subject, $message, $cc, $bcc, $from)) {
            // echo "Mail sent successfully!";
        } else {
            // echo "Failed to send mail.";
        }
    }
    echo json_encode(['status' => 'success', 'message' => 'Hotel registered successfully']);
} else if (isset($data['action']) && $data['action'] == 'demo_request') {
    $company_name = $data['company_name'];
    $property_type = $data['property_type'];
    $concern_person = $data['concern_person'];
    $city = $data['city'];
    $locality = $data['locality'];
    $email = $data['email'];
    $mobile = $data['mobile'];

    $result = $conn->query("SELECT COUNT(*) AS cnt FROM demo_request WHERE mobile = '$mobile'");
    $row = $result->fetch_assoc();

    if ($row['cnt'] > 0) {
        echo json_encode(['status' => 'fail', 'message' => 'Request already exists']);
        exit;
    }

    $uid = insert("INSERT INTO demo_request (company_name, property_type, concern_person, city, locality, email, mobile) VALUES ('$company_name', '$property_type', '$concern_person', '$city', '$locality', '$email', '$mobile')");
    $subject = "Demo Requested - PMSEASY";
    $message = "
        <html>
        <body>
            <p>Hey,</p>
            <p>Thank you for requesting a demo with PMSEasy! You've made an excellent choice. Our team will get in touch with you shortly to complete the process.</p>
            <p>We have received the following details for your demo:</p>
            <h2>New Demo Requested</h2>
            <p><strong>Company Name:</strong> {$company_name}</p>
            <p><strong>Property Type:</strong> {$property_type}</p>
            <p><strong>Concern person:</strong> {$concern_person}</p>
            <p><strong>City:</strong> {$city}</p>
            <p><strong>Locality:</strong> {$locality}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Mobile:</strong> {$mobile}</p>
            <p><strong>Demo Request On:</strong> " . date("d-m-Y H:i:s") . "</p>
        </body>
        </html>
    	";

    $to = $email;
    $cc = "";
    $bcc = "nikhildk@aspiringwebsolutions.com";
    $from = "pmseasy";
    $mobile_number = "9552016400";
    $wa_message = "Hey,\n\n"
        . "Thank you for requesting a demo with PMSEasy! You've made an excellent choice.\n\n"
        . "Our team will get in touch with you shortly to complete the process.\n\n"
        . "We have received the following details for your registration:\n\n"
        . "New Demo Requested\n"
        . "Company Name: {$company_name}\n"
        . "Property Type: {$property_type}\n"
        . "Concern Person: {$concern_person}\n"
        . "City: {$city}\n"
        . "Locality: {$locality}\n"
        . "Email: {$email}\n"
        . "Mobile: {$mobile}\n"
        . "Demo Request On: " . date("d-m-Y H:i:s") . "\n\n"
        . "Warm regards,\n"
        . "Team PMSEasy";

    $urlEncodedMessage = urlencode($wa_message);

    sendwhatsapp($mobile_number, $urlEncodedMessage);

    if (myemail($to, $subject, $message, $cc, $bcc, $from)) {
        echo "Demo request successfull";
    } else {
        echo "Failed to send mail.";
    }
} else if (isset($data['action']) && $data['action'] == 'contact_request') {
    $website = $data['website'];
    $address = $data['address'];
    $messages = $data['message'];
    $email = $data['email'];
    $mobile = $data['mobile'];

    $uid = insert("INSERT INTO contact_request (email, mobile, message, address, website) VALUES ('$email', '$mobile', '$messages', '$address', '$website')");
    $subject = "Enquiry From Contact Form - PMSEASY";
    $message = "
        <html>
        <body>
            <p>Hey,</p>
            <p>Thank you for getting in touch with PMSEasy! You've made an excellent choice.Our team will get in touch with you shortly to complete the process.</p>
            <p>We have received the following details for your demo:</p>
            <h2>Contact details</h2>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Mobile:</strong> {$mobile}</p>
            <p><strong>Website:</strong> {$website}</p>
            <p><strong>Address:</strong> {$address}</p>
            <p><strong>Message:</strong> {$messages}</p>
            <p><strong>Contact Request On:</strong> " . date("d-m-Y H:i:s") . "</p>
        </body>
        </html>
    	";

    $to = $email;
    $cc = "";
    $bcc = "nikhildk@aspiringwebsolutions.com";
    $from = "pmseasy";
    $mobile_number = "9552016400";
    $wa_message = "Hey,\n\n"
        . "Thank you for getting in touch with PMSEasy! You've made an excellent choice.\n\n"
        . "Our team will get in touch with you shortly to complete the process.\n\n"
        . "We have received the following details for your registration:\n\n"
        . "New Demo Requested\n"
        . "Email: {$email}\n"
        . "Mobile: {$mobile}\n"
        . "Website: {$website}\n"
        . "Address: {$address}\n"
        . "Message: {$messages}\n"
        . "Contact Request On: " . date("d-m-Y H:i:s") . "\n\n"
        . "Warm regards,\n"
        . "Team PMSEasy";

    $urlEncodedMessage = urlencode($wa_message);

    sendwhatsapp($mobile_number, $urlEncodedMessage);

    if (myemail($to, $subject, $message, $cc, $bcc, $from)) {
        echo "Contact request successfull";
    } else {
        echo "Failed to send mail.";
    }
} else {
    echo json_encode(['status' => 'fail', 'message' => 'Invalid action']);
}


// Return success response

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
