<?php include 'conn.php';
include 'udf.php'; ?>

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

	<!-- MAIN CSS (REQUIRED ALL PAGE)-->
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/style-responsive.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/Lightbox.css">
	<link rel="stylesheet" type="text/css" href="css/style_map.css">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>
<script>
	async function handleClick(bookingid) {
		$.ajax({
			type: 'POST',
			url: 'bookingajax.php',
			data: {
				action: "update_cancel_request",
				bookingid: bookingid,
			},
			success: function(response) {
				res = JSON.parse(response)
				console.log(res);
			}
		});
	}
</script>

<body style="padding-top:0px">
	<div class="container-fluid">
		<div class="row margin">
			<div class="col-md-12 padd-zero bg-color">
				<div class=" info float width-70 mar">
					<div class="back col-1 mg-left-5 top-mg-10"></div>
					<div class="text mg-left-5 top-mg-7 padd-btt">
						<p> Only Frotel Booking i.e Hourly Booking present</p>
					</div>
					<div class="back col-2 mg-left-5 top-mg-10"></div>
					<div class="text mg-left-5 top-mg-7 padd-btt">
						<p>Only Hotel Booking i.e Full Day Booking present</p>
					</div>
					<div class="back col-3 mg-left-5 top-mg-10"></div>
					<div class="text mg-left-5 top-mg-7 padd-btt">
						<p>Both Frotal & Hotel Booking present</p>
					</div>
				</div>
				<div class=" info float width-70 mar">
					<div class="back col-1 mg-left-5 top-mg-10" style="background-color:#7160ff"></div>
					<div class="text mg-left-5 top-mg-7 padd-btt">
						<p> Checked In</p>
					</div>
					<div class="back col-2 mg-left-5 top-mg-10" style="background-color: rgb(250, 0, 242);"></div>
					<div class="text mg-left-5 top-mg-7 padd-btt">
						<p>Checked Out</p>
					</div>
					<div class="back col-3 mg-left-5 top-mg-10" style="background-color: rgb(0, 0, 0);"></div>
					<div class="text mg-left-5 top-mg-7 padd-btt">
						<p>Blocked</p>
					</div>
				</div>
				<?php $bdate = date_create($_GET['date']);
				$now = date_create(date("Y-m-d H:i"));
				$row = execute("select u.company,r.roomtype,rn.roomnumber,c.city from roomnumbers rn join roomtypes r on r.id=rn.roomtype join hotels h on h.id=r.hotel join users u on u.id=h.user join locations l on l.id=h.location join cities c on c.id=l.city where rn.id=$_GET[id]"); ?>
				
				<table class="table table-bordered br-none mg-top">
					<thead>
						<tr>
							<th class="size tb-color border-none width-80"></th>
							<th class="tb-color-1 width-80"><?= $bdate->format("M") ?></th>
							<th class="tb-color-1" align=left colspan=2><?= $row['company'] ?><br>Room Type:<?= $row['roomtype'] ?><br>Room No:<?= $row['roomnumber'] ?><br>City:<?= $row['city'] ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th class="size tb-color border-none width-80"></th>
							<td class="tb-color-2" size tb-color><?= $bdate->format("d") ?></td>
							<td class="tb-color-2" colspan=2></td>
						</tr>
						<tr>
							<th class="size tb-color border-none width-80"></th>
							<td class="tb-color-2"><?= weekdayname($bdate->format("w"), "short") ?></td>
							<td class="tb-color-2" colspan=2></td>
						</tr>
						<?php $flag = 0;
						for ($i = 0; $i < 48; $i++) {
							$hrs = floor($i / 2);
							if ($hrs < 10) $hrs = "0" . $hrs;
							if (fmod($i, 2) == 0)
								$mins = "00";
							else
								$mins = "30";

							$a = date_create($bdate->format("Y-m-d") . " " . $hrs . ":" . $mins);

							$row = execute("select b.id,b.hours,(declaredtariff-pcdiscount+hotelst+hsbc+hkcc+frotelst+fsbc+fkcc+lt+sc+stonsc)total,u.fullname, b.trav_name,b.checkindatetime,b.checkoutdatetime,b.usercheckedin,b.usercheckedout from bookings b left join room_distribution rd on b.id=rd.bookingid left join users u on u.id=b.guestid where (b.paid=1 or ticker>'" . $now->format("Y-m-d H:i") . "') and (b.status='Scheduled' or b.status='Cancelled') and rd.roomnumber=$_GET[id] 
								and 
								(
									b.checkindatetime<='" . $a->format("Y-m-d H:i") . "' and b.checkoutdatetime>='" . $a->format("Y-m-d H:i") . "'
								)");

							if ($row == "") {
								$class = "";
								$flag = 0;
								$info = "";
								$btnvalue = "";
							} else {
								if ($row['hours'] > 0 and $row['hours'] < 24) {
									$class = "col-1";
									$stay_period = $row['hours'] . " hours";
								} else {
									$class = "col-2";

									$ci = date_create(date_create($row['checkindatetime'])->format("Y-m-d"));
									$co = date_create(date_create($row['checkoutdatetime'])->format("Y-m-d"));

									$nights = date_diff($ci, $co)->format("%a");
									$stay_period = $nights . " night(s)";
								}

								if ($row['usercheckedin'] == "0000-00-00 00:00:00" or $row['usercheckedin'] == "")
									$btnvalue = "<a href='LBF_usercheckinout.php?id=" . $row['id'] . "' class='fancybox2 fancybox.iframe' style='color:#FFFFFF'><button class='btn' style='background-color:#7160ff;'>Check in</button></a>";
								else if ($row['usercheckedout'] == "0000-00-00 00:00:00" or $row['usercheckedout'] == "")
									$btnvalue = "<a href='LBF_usercheckinout.php?id=" . $row['id'] . "' class='fancybox2 fancybox.iframe' style='color:#FFFFFF'><button class='btn' style='background-color:rgb(250,0,242);'>Check out</button></a>";
								else
									$btnvalue = "<a href='LBF_usercheckinout.php?id=" . $row['id'] . "' class='fancybox2 fancybox.iframe' style='color:#FFFFFF'><button class='btn' style='background-color:rgb(250,0,242);'>Checked out</button></a>";

								if ($flag == 0) {
									$flag = 1;
									if ($row['usercheckedin'] == "0000-00-00 00:00:00" or $row['usercheckedin'] == "")
										$usercheckin = "-";
									else
										$usercheckin = date_create($row['usercheckedin'])->format("d-M-Y H:i");

									if ($row['usercheckedout'] == "0000-00-00 00:00:00" or $row['usercheckedout'] == "")
										$usercheckout = "-";
									else
										$usercheckout = date_create($row['usercheckedout'])->format("d-M-Y H:i");

									$traveler = $row['trav_name'] == "" ? $row['fullname'] : $row['trav_name'];

									$info = "<b>BRN:</b> " . $row['id'] . " | <b>Rs :</b>" . round($row['total'], 2) . " | <b>User:</b>" . $row['fullname'] . " | <b>Traveler:</b> " . $traveler . " | <b>Checkin :</b> " . date_create($row['checkindatetime'])->format("d-M-Y H:i") . " | <b>Checkout :</b>  " . date_create($row['checkoutdatetime'])->format("d-M-Y H:i") . "<br><b>Stay Period :</b> " . $stay_period . "<br><b>Checked in at:</b> " . $usercheckin . " | <b>Checked out at:</b>" . $usercheckout;
								} else {
									$info = "";
									$btnvalue = "";
								}
							} ?>

							<tr>
								<td class="tb-color-2"><?= $hrs ?>:<?= $mins ?></td>
								<td class="<?= $class ?>"></td>
								<td class=""><?= $info ?></td>
								<td class=""><?= $btnvalue ?></td>
							</tr>
						<?php
						} ?>
					</tbody>
				</table>
				<button onclick="handleClick('<?= $row['id'] ?>')">Cancel Booking</button>
			</div>
		</div>
	</div>
	<js>
		<?php include("js.php"); ?>
	</js>
</body>

</html>