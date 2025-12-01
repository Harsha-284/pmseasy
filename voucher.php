<?php include 'conn.php';
include 'functions.php'; ?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Reddit+Mono:wght@200..900&display=swap"
        rel="stylesheet">
 <style>
     *{
  font-family: Arial !important;
}

h5 {
  font-family: "Playfair Display", serif;
  font-size: 15px;
  color: #fff;
  text-transform: uppercase;

}
.branding-tag{
  font-size: 13px;
  position: relative;
  bottom: -50px;
  left: -10px;
}

.middle h6 {
  /* font-family: "Courier Prime"; */
  color: #133054;
  text-transform: uppercase;
}

#bg-color {
  background-color: #f4f4f4;
}

.head {
  background-color: #e35453;

}

.head h6 {
  color: white;
  /* font-family: The Youngest serif; */
}

.train {
  width: 340px;
  margin-left: -10px;
  height: 250px;
}

.bottom h4 {
  transform-origin: 0 0;
  transform: rotate(90deg);
  width: max-content;
  margin-top: 33px;
  margin-left: 30px;
  font-family: "Courier Prime";
  color: #000;
  text-transform: uppercase;
  font-weight: 700;
  font-size: 20px;
}

.bord {
  border-right: 3px dashed #133054;
}

.para {
  width: 150px;
}

.bottom-1 {
  margin-left: 50px;
}

.heading h6 {
  color: white;
  /* font-family: The Youngest serif; */
}

/* .heading{
  background-color:#133054;
} */
.right-content h6 {
  /* font-family: "Courier Prime"; */
  color: #133054;
  text-transform: uppercase;
  font-weight: 600;
  font-size: 15px;
}

.noted {
  text-align: justify;
}

.footer-left {
  width: 68% !important;
}

.footer-right {
  width: 32% !important;
}

.heading-content {
  font-size: 16px;
}

.root {

  background-color: #e35453;
  width: 100%;
  height: 49px;
  position: relative;
  overflow: hidden;
}

.slash {
  width: 48%;
    height: 163%;
    background-color: #f4f4f4;
    transform: rotate(45deg) translateY(100%);
    position: relative;
    top: -63px;
    right: 47px;
}


/* Print-specific styles */
@media print {
  .section-middle{
    padding-left: 0px !important;
  }
  .branding-tag{
    font-size: 12px;
  }
  .heading-content{
    font-size: 14px;
  }
  .heading-line{
    font-size: 15px;
  }
  .pass-section{
    margin-right: 0px;
    width: max-content;
  }
  .slash {
    width: 48%;
    height: 163%;
    background-color: #f4f4f4;
    transform: rotate(45deg) translateY(100%);
    position: relative;
    top: -68px;
    right: 48px;
  }
  .line img{
    width: 300px;
    padding-left: 20px;
    object-fit: cover;
  }
  .branding-tag{
    padding-left: 20px;
  }
  /* Ensure that the content fits within the print margins */
  .container {
    page-break-inside: avoid;
  }
  .qr-code-img{
    padding-bottom: 20px;
  }
  .name-head-text{
    font-size: 16px !important;
  }

}

 </style>
</head>
<?php 
$id = (int) ($_GET['id'] ?? 0);
$sql = "SELECT 
            b.*, 
            h.id AS hotel_id,
            h.location,
            h.user,
            h.extrapositioncontact,
            l.location AS hotel_location,
            rd.adults, 
            h.dining,h.wifi,h.traveldesk,h.gym,h.parking,h.tv,
            rd.children, 
            rt.roomtype,
            u.fullname AS guest_name, 
            u.email AS guest_email, 
            u.contact AS guest_phone, 
            u.address1 AS guest_address
        FROM bookings b
        JOIN users u ON b.guestid = u.id
        JOIN room_distribution rd ON rd.bookingid = b.id
        JOIN roomnumbers rn ON rn.id = rd.roomnumber
        JOIN roomtypes rt ON rt.id = rn.roomtype
        JOIN hotels h ON h.id = rt.hotel
        JOIN locations l ON h.location = l.id
        WHERE b.id = $id";
        
        
$result = $conn->query($sql);

$row = $result->fetch_assoc();

$base_query = "SELECT * FROM users WHERE id = " . $row['user'];
$data = $conn->query($base_query);
$row2 = $data->fetch_assoc();


$issuedDate = date("d/m/Y", strtotime($row['reg_date']));
$checkinDate = date("d/m/Y", strtotime($row['checkindatetime']));
$checkoutDate = date("d/m/Y", strtotime($row['checkoutdatetime']));

$result1 = $conn->query("
    SELECT GROUP_CONCAT(f.facility ORDER BY f.facility SEPARATOR ', ') AS facilities
    FROM facilities f
    LEFT JOIN hotel_facilities hf 
        ON (f.id = hf.facility AND hf.hotel = {$row['hotel_id']})
    WHERE f.type = 2
");

$row3 = $result1->fetch_assoc();

$included = [];

if (!empty($row['wifi']) && $row['wifi'] == 1) {
    $included[] = 'Free Wifi';
}
if (!empty($row['dining']) && $row['dining'] == 1) {
    $included[] = 'Dining';
}
if (!empty($row['parking']) && $row['parking'] == 1) {
    $included[] = 'Parking';
}
if (!empty($row['gym']) && $row['gym'] == 1) {
    $included[] = 'Gym';
}
if (!empty($row['tv']) && $row['tv'] == 1) {
    $included[] = 'TV';
}
if (!empty($row['traveldesk']) && $row['traveldesk'] == 1) {
    $included[] = 'Traveldesk';
}
?>
<body>
    <div class="container" id="bg-color" style="zoom:75%">
        <div class="row">
            <div class="col-8 pb-3 bord">
                <div class="row head">
                    <div class="col-sm-5 pt-3 pb-2">
                        <h5 class="ps-3">Booking Confirmation <span class="text-warning"></span></h5>
                    </div>
                    <div class="col-sm-7 pt-3 pb-2">
                        <h5 class="ps-3">Issued Date : <?php echo $issuedDate; ?></h5>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 pt-3">
                        <!-- <h6>Swiss/Eurail Pass:</h6> -->
                        <div class="col-sm-12 d-flex">
                            <h6 class="ms-3 mb-0 heading-line">Check In</h6>
                            <h6 class=" mb-0 fw-bold heading-content" style="margin-left: 53px;">: <?php echo $checkinDate; ?></h6>
                        </div>

                        <div class="line mt-3 w-50">
                            <img src="images/small-bedroom-ideas.png" alt="" class="train px-4"
                                style="object-fit: cover;">
                        </div>
                        <!--<div class="col-sm-12 mt-4 pb-3 d-flex">-->
                        <!--    <h6 class="ms-3 mb-0 heading-line">Client</h6>-->
                        <!--    <h6 class=" mb-0 fw-bold heading-content" style="margin-left: 53px;">: </h6>-->
                        <!--</div>-->

                        <div class="col-sm-12 pb-3 d-flex mt-4" style="margin-top: -10px;">
                            <h6 class="ms-3 mb-0 heading-line">Property</h6>
                            <h6 class=" mb-0 fw-bold heading-content" style="margin-left: 22px;">: <?php echo $row2['company']; ?> </h6>
                        </div>

                        <div class="col-sm-12  pb-3 d-flex" style="margin-top: -10px;">
                            <h6 class="ms-3  mb-0 heading-line">Property <br> Contact <br> Number  <span class="fw-bold heading-content" style="margin-left: 35px ;">
                                : <?php echo $row2['contact'];?>
                            </span>  </h6>
                            
                        </div>

                    </div>
                    <div class="col-sm-7 pt-3 section-middle">
                        <div class="col-sm-12 " style="display: flex; align-items: center;">
                            <h6 style="width: 220px; margin-left: 19px; margin-bottom: 0;">Check Out</h6>
                            <h6 style="font-weight: bold; margin-bottom: 0;">: <?php echo $checkoutDate; ?></h6>
                        </div>
                        <div class="row pt-3">
                            <div style="display: flex; align-items: center; padding-bottom: 8px;">
                                <h6 style="width: 220px; margin-left: 20px; margin-bottom: 0;">Name of Passenger</h6>
                                <h6 style="font-weight: bold; margin-bottom: 0;">: <?php echo $row['guest_name']?></h6>
                            </div>

                            <div style="display: flex; align-items: center; padding-bottom: 8px;">
                                <h6 style="width: 220px; margin-left: 20px; margin-bottom: 0;">Number of Rooms</h6>
                                <h6 style="font-weight: bold; margin-bottom: 0;">: 1</h6>
                            </div>

                            <div style="display: flex; align-items: center; padding-bottom: 8px;">
                                <h6 style="width: 220px; margin-left: 20px; margin-bottom: 0;">Number of Extra Beds</h6>
                                <h6 style="font-weight: bold; margin-bottom: 0;">: 0</h6>
                            </div>

                            <div style="display: flex; align-items: center; padding-bottom: 8px;">
                                <h6 style="width: 220px; margin-left: 20px; margin-bottom: 0;">Number of Adults</h6>
                                <h6 style="font-weight: bold; margin-bottom: 0;">: <?php echo $row['adults']; ?></h6>
                            </div>

                            <div style="display: flex; align-items: center; padding-bottom: 8px;">
                                <h6 style="width: 220px; margin-left: 20px; margin-bottom: 0;">Number of Children</h6>
                                <h6 style="font-weight: bold; margin-bottom: 0;">: <?php echo $row['children']; ?></h6>
                            </div>

                            <div style="display: flex; align-items: center; padding-bottom: 8px;">
                                <h6 style="width: 220px; margin-left: 20px; margin-bottom: 0;">Room Type</h6>
                                <h6 style="font-weight: bold; margin-bottom: 0;">: <?php echo $row['roomtype']; ?></h6>
                            </div>

                            <div style="display: flex; align-items: center; padding-bottom: 8px;">
                                <h6 style="width: 220px; margin-left: 20px; margin-bottom: 0;">Booking ID</h6>
                                <h6 style="font-weight: bold; margin-bottom: 0;">: <?php echo $id; ?></h6>
                            </div>

                            <!--<div style="display: flex; align-items: center; padding-bottom: 8px;">-->
                            <!--    <h6 style="width: 220px; margin-left: 20px; margin-bottom: 0;">Booking Reference No</h6>-->
                            <!--    <h6 style="font-weight: bold; margin-bottom: 0;">: </h6>-->
                            <!--</div>-->


                            <div class="col-sm-12 pb-3">
                                <h6 style="width: 220px; margin-left: 20px; margin-bottom: 0;">Property Address :</h6>
                                <h6 class=" mb-0 fw-bold heading-content" style="width: 62%; margin-left: 20px;">
                                   <?php echo $row2['address1']; ?> </h6>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="col-4 right-content">
                <div class="row heading">
                    <div class="root text-center">
                        <img src="/images/voucher/logo-white.png" alt="" class="mt-3">
                        <div class="slash"></div>
                    </div>
                </div>
                <div class="right-section px-3">
                    <h6 class="mt-3">Cancellation Policy :</h6>
                    <h6 class="text-secondary">Any cancellation received
                        within 3 days prior
                        to the arrival date will incur the first night's charge. Failure to arrive at your hotel or
                        property
                        will be treated as a No-Show and will incur a charge of 100% of the booking value (Hotel
                        policy).</h6>
                    <h6 class=" mt-2">Benefits : </h6>
                    <?php if (!empty($included)) {
    echo '<h6 class="text-secondary">Included ' . implode(', ', $included) . '</h6>';
}?>

                    <h6 class=" mt-2">Remarks :</h6>
                    <!--<h6 class="text-secondary">Included : Taxes and fees INR 232.34</h6>-->
                    <h6 class="text-secondary">NonSmoke,LargeBed, AdditionalNotes: Late check-in after 8pm
                    </h6>
                    <h6 class=" mt-2">All special requests are subject to availability upon arrival</h6>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        window.onbeforeprint = function () {
            var container = document.querySelector('.container');
            if (container) {
                container.classList.remove('container');
            }
        };

        window.onafterprint = function () {
            var container = document.querySelector('div');
            if (container) {
                container.classList.add('container');
            }
        };
    </script>
</body>


</html>