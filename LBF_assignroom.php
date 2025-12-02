<?php include 'conn.php';

include 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sentir, Responsive admin and dashboard UI kits template">
    <meta name="keywords" content="admin,bootstrap,template,responsive admin,dashboard template,web apps template">
    <meta name="author" content="Ari Rusmanto, Isoh Design Studio, Warung Themes">
    <title>Blank Page | AWS - Admin Panel</title>
    <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- PLUGINS CSS -->
    <link href="css/weather-icons.min.css" rel="stylesheet">
    <link href="css/prettify.min.css" rel="stylesheet">
    <link href="css/magnific-popup.min.css" rel="stylesheet">
    <link href="css/owl.carousel.min.css" rel="stylesheet">
    <link href="css/owl.theme.min.css" rel="stylesheet">
    <link href="css/owl.transitions.min.css" rel="stylesheet">
    <link href="css/chosen.min.css" rel="stylesheet">
    <link href="css/all.css" rel="stylesheet">
    <link href="css/datepicker.min.css" rel="stylesheet">
    <link href="css/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="css/bootstrapValidator.min.css" rel="stylesheet">
    <link href="css/summernote.min.css" rel="stylesheet">
    <link href="css/bootstrap-markdown.min.css" rel="stylesheet">
    <link href="css/bootstrap.datatable.min.css" rel="stylesheet">
    <link href="css/morris.min.css" rel="stylesheet">
    <link href="css/c3.min.css" rel="stylesheet">
    <link href="css/slider.min.css" rel="stylesheet">
    <link href="css/salvattore.css" rel="stylesheet">
    <link href="css/toastr.css" rel="stylesheet">
    <link href="css/fullcalendar.css" rel="stylesheet">
    <link href="css/fullcalendar.print.css" rel="stylesheet" media="print">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- MAIN CSS (REQUIRED ALL PAGE)-->
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/Lightbox.css">
    <link rel="stylesheet" type="text/css" href="css/style_map.css">
    <?php include("head.php"); ?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>
<style>
    h5 {
        margin-left: 0px;
    }
    .d-flex{
		display:flex;
        padding: 0 15px 15px;
        gap: 10px;
	}
	.w-40{
		width: 40%;
	}
	.w-60{
		width: 60%;
	}
	.h-155{
		height: 155px;
	}
	.bill-heading{
		    padding: 10px;
    background: #e8e9ee;
	margin-top:0;
	}
	.border{
		border:1px solid #e8e9ee;
	}
	.inner-content{
		padding:10px;
	}
	.inner-content p {
		margin:0 0 2px;
	}
	.details{
		display:flex;
		padding: 0 15px;
	}
	.table-section{
		padding:0 10px 10px;
		overflow-x:scroll;
	}
	.inner-container{
    border: 1px solid #dcdcdc;
	padding-bottom:10px;
	}
    .pt-15{
        padding-top: 15px;
    }

</style>

<body style="overflow-x: hidden;padding: 7px 13px;">
    <?php
    $row = execute("select b.id,u.fullname,u.contact,u.email,u.address1,u.id_proof_path,b.checkindatetime,b.checkoutdatetime,GROUP_CONCAT(rd.roomnumber) AS roomnumbers,count(rd.roomnumber)roomnumbercount,GROUP_CONCAT(rd.id) AS roomdistriid,GROUP_CONCAT(rd.isRoomAssigned) AS isRoomAssigned from bookings b join users u on b.guestid=u.id join room_distribution rd on rd.bookingid=b.id  where b.id=$_GET[id]");

    $roomtype = execute("select roomtype from roomtypes where id=$_GET[roomtype]");

    $no_of_room = execute("select COUNT(*)cnt from room_distribution where bookingid=$_GET[id]");

    $checkindate = new DateTime($row['checkindatetime']);
    $checkoutdate = new DateTime($row['checkoutdatetime']);

    $roomdisid = explode(',', $row['roomdistriid']);

    $availableRooms = execute("SELECT GROUP_CONCAT(rn.id) AS available_rooms
        FROM roomnumbers rn
        WHERE rn.roomtype = $_GET[roomtype]
        AND rn.id NOT IN (
            SELECT rd.roomnumber FROM bookings b
            JOIN room_distribution rd ON rd.bookingid = b.id
            WHERE b.status IN ('Scheduled', 'Cancelled') 
            AND rd.isRoomAssigned = 1
            AND (
                (
                    ('" . $checkindate->format("Y-m-d H:i") . "' >= b.checkindatetime AND '" . $checkoutdate->format("Y-m-d H:i") . "' <= b.checkoutdatetime) 
                    OR 
                    ('" . $checkindate->format("Y-m-d H:i") . "' <= b.checkindatetime AND '" . $checkoutdate->format("Y-m-d H:i") . "' >= b.checkoutdatetime) 
                )
            )
        )
        AND rn.id NOT IN (
            SELECT roomnumber FROM blocked_roomnumbers 
            WHERE bdate = '" . $checkindate->format("Y-m-d") . "'
        );
        ");
    ?>
    <div class="mainbody" style="width:100%; height: 100%;">
        <div id="messageBox-assignroom"></div>
        <script>
            function showMessageBoxassignroom(content, type) {
                const messageBox = document.getElementById('messageBox-assignroom');

                // Determine the alert class based on the type parameter
                let alertClass;
                switch (type) {
                    case 'success':
                        alertClass = 'alert-success';
                        break;
                    case 'error':
                        alertClass = 'alert-danger';
                        break;
                    case 'warning':
                        alertClass = 'alert-warning';
                        break;
                    default:
                        alertClass = 'alert-info';
                }

                // Populate the messageBox with the content and appropriate styling
                messageBox.innerHTML = `
                <div style="position:absolute; z-index:50; width:50%; padding: 5px 14px; height:30px; top:9px; right:37px; border-radius: 2px;" class="alert ${alertClass} alert-block square fade in alert-dismissable">
                <button style="width: 45px;" type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p style="width:90%;">${content}</p>
                </div>`;

                // Show the message box
                messageBox.style.display = "block";

                // Automatically hide the message box after 4 seconds
                setTimeout(() => {
                    messageBox.style.display = "none";
                }, 4000);
            }
        </script>
        <div class="inner-container">
            <div>
                <h6 style="margin-bottom: 15px; color: #fb3c3c;background: #dcdcdc;" class="bill-heading ">BRN Number: <?= $_GET['id'] ?></h6>
            </div>

            <div class="d-flex">
                <div class="left-section w-60">
                    <div class="border">
                        <div class="bill-heading">Client Details</div>
                    		<div class="inner-content h-155">
                                <p><strong>Guest Name:</strong> <?= $row['fullname'] ?></p>
                                <p><strong>Mobile No:</strong> <?= $row['contact'] ?></p>
                                <p><strong>Email:</strong> <?= $row['email'] ?></p>
                                <p><strong>Address:</strong> <?= $row['address1'] ?></p>
                                <p><strong>Id Proof: </strong><a href="uploads/<?= $row['id_proof_path'] ?>" target="_blank"><i class="fa fa-print" style="color: #3BAFDA; cursor: pointer"></i></a></p>
					        </div>
                    </div>
                </div>
                <div class="right-section w-40">
                    <div class="border">
                    <div class="bill-heading">Room Details</div>
                        <div class="inner-content h-155">
                            <p><strong>Check-in Date:</strong> <?= $checkindate->format('d-m-Y') ?></p>
                            <p><strong>Check-out Date:</strong> <?= $checkoutdate->format('d-m-Y') ?></p>
                            <p><strong>Room Type:</strong> <?= $roomtype['roomtype'] ?></p>
                             <p><strong>No. of Rooms:</strong> <?= $no_of_room['cnt'] ?></p>
					    </div>
                    </div>
                </div>
            </div>
            <div style="padding: 0 15px 15px;">
                <div class="left-section">
                    <div class="border">
                        <div class="bill-heading">Assign Room</div>
                        <div style="padding: 5px 0px 15px 10px;">
                            <div style="width: 40%; text-align: center; display: inline-block; margin-right: 10px;  ">
                                <h5 style="display: inline;">Available rooms <small id="total-available-room"></small></h5>
                            </div>

                            <div style="width: 40%; text-align: center; display: inline-block; ">
                                <h5 style="display: inline;">Assigned rooms <small id="total-assigned-room">(<?= $no_of_room['cnt'] ?>)</small></h5>
                            </div>


                                    <div class="card" id="156151" style="display: flex; flex-direction: row; gap: 15px; width: 100% ">

                                        <div class="available-rooms" style="width: 41%; display: flex; gap: 5px; flex-wrap: wrap; padding: 20px; box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px; border-radius: 3px;">

                                            <?php
                                            $length = 0;
                                            $assignedRooms = explode(',', $availableRooms['available_rooms']);

                                            if (!empty($assignedRooms)) {
                                                // Process assigned rooms
                                                foreach ($assignedRooms as $roomNumber) {
                                                    $length++;
                                                    $roomnum = execute("select roomnumber from roomnumbers where id=$roomNumber");
                                                    echo "<div class='cube available' id='$roomnum[roomnumber]' data-roomid='$roomNumber' style='border-radius: 2px; width:50px; text-align: center; cursor: pointer; height: 25px; line-height: 25px;'>$roomnum[roomnumber]</div>";
                                                }
                                            }
                                            ?>

                                        </div>
                                        <script>
                                            document.getElementById("total-available-room").innerText = "(<?= $length ?>)"
                                        </script>
                                        <div class="assign-rooms" style="display: flex; flex-direction: row; gap: 5px; width: 41%; flex-wrap: wrap; box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;  padding: 20px; border-radius: 3px;">
                                            <?php
                                            $assignedval = 0;
                                            $roomNumbers = explode(',', $row['roomnumbers']);
                                            $isRoomAssigned = explode(',', $row['isRoomAssigned']);

                                            $i = 0;
                                            foreach ($roomNumbers as $roomNumber) {
                                                $indiIsRoomAssigned = $isRoomAssigned[$i];
                                                $i++;
                                                if ($indiIsRoomAssigned) {
                                                    $assignedval++;

                                                    $roomnum = execute("select roomnumber from roomnumbers where id=$roomNumber");
                                                    echo "<div class='cube booked' id='$roomnum[roomnumber]' data-roomid='$roomNumber' style='border-radius: 2px; width:50px; text-align: center; cursor: pointer; height: 25px; line-height: 25px;'>$roomnum[roomnumber]</div>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="action" style="line-height: 7;">
                                            <button onclick="assignrooms()" style="width: 100px; height: 27px; border: none; border-radius: 3px; cursor: pointer; margin-left: -5px; " class="btn available" disabled>Assign room</button>
                                        </div>
                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        </div>
    </div>
</body>


<script>
    // Define the max limit of cubes that can be added to assign-rooms
    const maxAssignRooms = <?= $no_of_room['cnt'] ?>;
    let assignedCount = <?= $assignedval ?>;


    function assignrooms() {
        if (maxAssignRooms == assignedCount) {


            // console.log("Maximum rooms assigned:", assignedCount);
            const assignRooms = document.querySelector('.assign-rooms');
            const cubes = assignRooms.querySelectorAll('.cube');
            const cubeNumbers = Array.from(cubes).map(cube => cube.dataset.roomid);
            console.log(cubeNumbers);

            const roomdisid = <?= json_encode($roomdisid) ?>;
            console.log(cubeNumbers, roomdisid);

            $.ajax({
                type: 'POST',
                url: 'bookingajax.php',
                data: {
                    bookingid: <?= $_GET['id'] ?>,
                    roomnumberids: cubeNumbers,
                    roomdisids: roomdisid,
                    checkindate: <?= "'" . $row['checkindatetime'] . "'" ?>,
                    checkoutdate: <?= "'" . $row['checkoutdatetime'] . "'" ?>,
                    action: 'assign_roomnumbers',
                    user: <?= $_SESSION['id'] ?>,
                    hotel: <?= $_SESSION['hotel'] ?>
                },
                success: function(response) {
                    res = JSON.parse(response)
                    if (res.status === 'success') {
                        showMessageBoxassignroom("Room assigned successfully", "success")
                        setTimeout(() => {
                            window.history.back();
                        }, 1000);

                    } else {
                        console.log('err', res);

                    }
                }
            });

        } else {
            alert("Please assign all rooms!");
        }
    }


    // Function to move cube between divs and toggle classes
    function moveCube(cube, fromDiv, toDiv) {

        fromDiv.removeChild(cube); // Remove cube from current div
        toDiv.appendChild(cube); // Append cube to the target div

        // Toggle classes based on the div the cube is moved to
        if (toDiv.classList.contains('assign-rooms')) {
            cube.classList.remove('available');
            cube.classList.add('booked');
            assignedCount++; // Increment the assigned room count
        } else {
            cube.classList.remove('booked');
            cube.classList.add('available');
            assignedCount--; // Decrement the assigned room count
        }

        // Disable cubes in available-rooms if limit is reached
        if (assignedCount >= maxAssignRooms) {
            document.querySelectorAll('.available-rooms .cube').forEach(cube => {
                cube.style.pointerEvents = 'none'; // Disable further clicks
            });
        } else {
            document.querySelectorAll('.available-rooms .cube').forEach(cube => {
                cube.style.pointerEvents = ''; // Enable clicks when below the limit
            });
        }

        if (maxAssignRooms == assignedCount) {
            document.querySelector('.btn.available').disabled = false;
        }
        if (maxAssignRooms != assignedCount) {
            document.querySelector('.btn.available').disabled = true;
        }
    }

    // Event delegation to handle cube clicks
    document.querySelectorAll('.card').forEach(card => {
        const availableRooms = card.querySelector('.available-rooms');
        const assignRooms = card.querySelector('.assign-rooms');

        card.addEventListener('click', function(event) {
            const cube = event.target.closest('.cube');
            if (!cube) return; // Exit if clicked element is not a cube

            if (availableRooms.contains(cube) && assignedCount < maxAssignRooms) {
                // If cube is in available-rooms and limit not reached, move to assign-rooms
                moveCube(cube, availableRooms, assignRooms);
            } else if (assignRooms.contains(cube)) {
                // If cube is in assign-rooms, move back to available-rooms
                moveCube(cube, assignRooms, availableRooms);
            }
        });
    });
</script>



</html>