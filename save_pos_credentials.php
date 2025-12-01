<?php
require 'conn.php';

$action = $_POST['action'] ?? '';

if ($action === 'update_status') {
    $hotel_id = $_POST['hotel_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE hotels SET pos_status = ? WHERE id = ?");
    $stmt->bind_param('ii', $status, $hotel_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'POS status updated']);
    exit;
}

if ($action === 'save_credentials') {
    $hotel_id = $_POST['hotel_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("UPDATE hotels SET pos_username = ?, pos_password = ? WHERE id = ?");
    $stmt->bind_param('ssi', $username, $password, $hotel_id);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Credentials saved']);
    exit;
}

// If no action matched
http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Invalid action']);
