<?php include 'conn.php';

include 'udf.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'update') {

        // Continue with your database operations
        $id = $_POST['id'];
        $cmhotelname = $_POST['cmhotelname'];


        // Execute the query
        if ($conn->query("update users u set u.cm_company_name='$cmhotelname' where u.id='$id'") === TRUE) {
            echo "Data updated successfully";
        } else {
            echo "Error updating data: " . $conn->error;
        }
    } else if (isset($_POST['action']) && $_POST['action'] === 'update_cm_roomcode_and_roomrateplan') {

        $roomtypeid = $_POST['roomtypeid'];
        $cmRoomCode = $_POST['cmRoomCode'];
        // $cmRatePlanCode = $_POST['cmRatePlanCode'];


        // Execute the query
        if ($conn->query("update roomtypes set cmroomid='$cmRoomCode' where id ='$roomtypeid'") === TRUE) {
            echo "Data updated successfully";
        } else {
            echo "Error updating data: " . $conn->error;
        }
    }
} else {
    echo "Invalid request method.";
}
