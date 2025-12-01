<?php
require 'conn.php'; // mysqli connection

$hotel_id   = (int)$_POST['hotel_id'];
$start_date = $_POST['start_date'];
$end_date   = $_POST['end_date'];

// ✅ Basic validation
if (!$hotel_id || !$start_date || !$end_date || $start_date > $end_date) {
    exit('Invalid data');
}

// ✅ Prepare INSERT (no ON DUPLICATE)
$sql = "INSERT INTO pms_subscriptions (hotel_id, start_date, end_date, created_at)
        VALUES (?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    exit('Prepare failed: ' . $conn->error);
}

// ✅ Bind parameters
$stmt->bind_param("iss", $hotel_id, $start_date, $end_date);

// ✅ Execute
if ($stmt->execute()) {
    echo 'OK'; // or return JSON if used via AJAX
} else {
    if (str_contains($stmt->error, 'Duplicate')) {
        echo 'Duplicate entry: subscription already exists';
    } else {
        echo 'DB error: ' . $stmt->error;
    }
}
