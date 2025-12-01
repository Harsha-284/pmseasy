<?php
session_start();
include "conn.php"; // your DB connection
include 'udf.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
	die("Invalid ID");
}

$id = (int) $_GET['id'];

// Fetch existing data
$query = $conn->query("SELECT * FROM room_rate_plans_validity WHERE id = $id");
$data = $query->fetch_assoc();

if (!$data) {
	die("Record not found");
}

// Update if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$tariff = $_POST['fulldaytariff'];
 $record = $conn->query("
        SELECT v.*, rp.room_rate_plan, rp.roomtype, rp.id as rpid
        FROM room_rate_plans_validity v
        JOIN room_rate_plans rp ON rp.id = v.rateplanid
        WHERE v.id = $id
    ")->fetch_assoc();
	$update = $conn->query("
        UPDATE room_rate_plans_validity
        SET fulldaytariff = '$tariff'
        WHERE id = $id
    ");
    

	if ($update) {
	    $rateplanid   = $record['rpid'];
        $validfrom    = $record['validfrom'];
        $validto      = $record['validto'];
        $oldTariff    = $record['fulldaytariff'];
        $newTariff    = $tariff;
        $plan_type   = "room_rate";
        $updatedAt    = date('Y-m-d H:i:s');

        $conn->query("
            INSERT INTO rate_room_plan_logs 
            (rateplanid, validfrom, validto, old_tariff, new_tariff, plan_type, updated_at)
            VALUES 
            ('$rateplanid', '$validfrom', '$validto', '$oldTariff', '$newTariff', '$plan_type' , '$updatedAt')
        ");
        
		$msg = "Room rate updated successfully.";

		$clrclass = "success";
	} else {
		echo "Error: " . $conn->error;
	}
}
	if ($msg == "Room rate updated successfully.") { alertbox($clrclass, $msg); ?>

		<script type="text/javascript">
			window.parent.location = "admin.php?Pg=setroomplanrateview";
		</script>

	<?php

	}
?>


<html lang="en">

<head>

	<?php include("head.php"); ?>

</head>

<body style="padding-top:0px;">
	<div class="the-box" style="padding-bottom:0px; height: 400px;">

		<form method="post">
			<div class="col-xs-10">
				<div class="form-group">
					<label class="form-label">Full Day Tariff:</label>
					<input type="number" step="0.01" name="fulldaytariff" value="<?= htmlspecialchars($data['fulldaytariff']) ?>" class="form-control" required>
				</div>
			</div>

			<div class="col-xs-10">
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		</form>
	</div>
</body>

</html>