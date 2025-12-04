<!DOCTYPE html>
<!-- saved from url=(0070)https://www.banqueteasy.com/erp/lightboxes/create_invoice.php?id=62010 -->
<html lang="en" class="">
<?php
include '../conn.php';
include '../udf.php';
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Banquet Easy - Control Panel</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
	<link href="./Invoice Form_files/bootstrap.min.css" rel="stylesheet">

	<!-- PLUGINS CSS -->

	<link href="./Invoice Form_files/weather-icons.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/prettify.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/magnific-popup.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/owl.carousel.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/owl.theme.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/owl.transitions.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/chosen.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/all.css" rel="stylesheet">
	<link href="./Invoice Form_files/datepicker.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/bootstrap-timepicker.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/bootstrapValidator.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/summernote.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/bootstrap-markdown.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/bootstrap.datatable.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/morris.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/c3.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/slider.min.css" rel="stylesheet">
	<link href="./Invoice Form_files/salvattore.css" rel="stylesheet">
	<link href="./Invoice Form_files/toastr.css" rel="stylesheet">
	<link href="./Invoice Form_files/fullcalendar.css" rel="stylesheet">
	<link href="./Invoice Form_files/fullcalendar.print.css" rel="stylesheet" media="print">

	<!-- MAIN CSS (REQUIRED ALL PAGE)-->
	<!-- <link href="https://www.banqueteasy.com/erp/css/font-awesome.min.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="./Invoice Form_files/font-awesome.min.css">
	<link href="./Invoice Form_files/style-min.css" rel="stylesheet">
	<link href="./Invoice Form_files/style-responsive.css" rel="stylesheet">
	<link href="./Invoice Form_files/jquery-ui.css" rel="stylesheet">
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!----------------------------------- Autocomplete ------------------------------------>
	<!-- <link rel="stylesheet" href="https://www.banqueteasy.com/erp/third_party/autocomplete/jquery-ui.css">
	<link rel="stylesheet" href="https://www.banqueteasy.com/erp/third_party/autocomplete/style.css">
	<script src="https://www.banqueteasy.com/erp/third_party/autocomplete/jquery-1.12.4.js"></script> -->
	<!-- <script src="https://www.banqueteasy.com/third_party/jquery-ui.js"></script> -->
	<!----------------------------------- Autocomplete ------------------------------------>
	<link rel="stylesheet" type="text/css" href="./Invoice Form_files/jquery.datetimepicker.css">
	<style>
		.inv-desc {
			float: left;
			margin: 0px;
			width: 15%;
		}

		.inv-vat {
			float: left;
			width: 5%;
		}

		.inv-hsn {
			width: 7%;
			float: left;
			margin: 0px 4px;
		}

		.inv-rate {
			width: 6%;
			float: left;
			margin: 0px 0px;
		}

		.inv-qty {
			width: 7%;
			float: left;
			margin: 0px 3px;
		}

		.inv-disctype {
			width: 13%;
			float: left;
			margin: 0px 0px;
		}

		.inv-slab {
			width: 5%;
			float: left;
			margin: 0px 4px;
		}

		.inv-total {
			width: 8%;
			float: left;
			margin: 0px 0px 0px 0px;
		}

		.inv-gst {
			float: left;
			margin: 0px 3px;
			width: 6%;
		}

		.form-control {
			height: 34px;
			border-radius: 0;
		}

		.d-flex {
			display: flex;
			gap: 3px;
		}

		/* The snackbar - position it at the bottom and in the middle of the screen */
		#snackbar {
			visibility: hidden;
			/* Hidden by default. Visible on click */
			min-width: 250px;
			/* Set a default minimum width */
			margin-left: -125px;
			/* Divide value of min-width by 2 */
			/*background-color: #333; /* Black background color */
			color: #fff;
			/* White text color */
			text-align: center;
			/* Centered text */
			border-radius: 2px;
			/* Rounded borders */
			padding: 16px;
			/* Padding */
			position: fixed;
			/* Sit on top of the screen */
			z-index: 1;
			/* Add a z-index if needed */
			left: 50%;
			/* Center the snackbar */
			bottom: 30px;
			/* 30px from the bottom */
		}

		/* Show the snackbar when clicking on a button (class added with JavaScript) */
		#snackbar.show {
			visibility: visible;
			/* Show the snackbar */

			/* Add animation: Take 0.5 seconds to fade in and out the snackbar. 
	However, delay the fade out process for 2.5 seconds */
			-webkit-animation: fadein 0.5s, fadeout 0.5s 3.5s;
			animation: fadein 0.5s, fadeout 0.5s 3.5s;
		}

		/* Animations to fade the snackbar in and out */

		@-webkit-keyframes fadein {
			from {
				bottom: 0;
				opacity: 0;
			}

			to {
				bottom: 30px;
				opacity: 1;
			}
		}

		@keyframes fadein {
			from {
				bottom: 0;
				opacity: 0;
			}

			to {
				bottom: 30px;
				opacity: 1;
			}
		}

		@-webkit-keyframes fadeout {
			from {
				bottom: 30px;
				opacity: 1;
			}

			to {
				bottom: 0;
				opacity: 0;
			}
		}

		@keyframes fadeout {
			from {
				bottom: 30px;
				opacity: 1;
			}

			to {
				bottom: 0;
				opacity: 0;
			}
		}

		/* .same-createinvoice.same-cre-mr {
    width: 32%;
} */
	</style>

	<script language="javascript">
		function showMessage(msg, clr) {
			// Get the snackbar DIV
			var x = document.getElementById("snackbar");
			x.innerHTML = msg;
			x.style.backgroundColor = clr;
			// Add the "show" class to DIV
			x.className = "show";

			// After 3 seconds, remove the show class from DIV
			setTimeout(function() {
				x.className = x.className.replace("show", "");
			}, 4000);
		}
	</script>

	<style type="text/css">
		.btn {
			background-color: #37bc9b;
			color: white;
			border-radius: 2px;
			font-size: 12px !important;
			text-transform: uppercase !important;
		}

		label {
			font-weight: 500;
			font-size: 12px !important;
			text-transform: uppercase;
			padding-left: 5px;
		}

		h1.page-heading {
			padding: 10px;
			font-size: 16px;
			color: #e4514b;
			font-weight: 700;
			text-transform: uppercase;
			border-bottom: 1px solid #dcdcdc;
		}

		.fancybox-margin {
			margin-right: 0px;
		}

		.Reference-createinvo {
			padding-left: 10px;
		}
	</style>
</head>

<body style="padding-top: 0px;" class="scroll-d1">

	<?php
	$userInfo = execute("select u.email,u.company,u.cm_company_name,u.address1,r.roomtype,r.id,rn.roomnumber,c.city from roomnumbers rn join roomtypes r on r.id=rn.roomtype join hotels h on h.id=r.hotel join users u on u.id=h.user join locations l on l.id=h.location join cities c on c.id=l.city where rn.id=$_GET[id]");


	$bdate = date_create($_GET['date']);
	$now = date_create(date("Y-m-d H:i"));
	$a = date_create($bdate->format("Y-m-d") . " " . '12' . ":" . '00');
	$row = execute("select b.id,b.intialtariff,b.hours,b.reg_date,u.fullname,u.id_proof,(declaredtariff-pcdiscount+hotelst+hsbc+hkcc+frotelst+fsbc+fkcc+lt+sc+stonsc)total,u.fullname,u.contact,u.email,u.address1, b.trav_name,b.checkindatetime,b.checkoutdatetime,b.usercheckedin,b.usercheckedout from bookings b left join room_distribution rd on b.id=rd.bookingid left join users u on u.id=b.guestid where (b.paid=1 or ticker>'" . $now->format("Y-m-d H:i") . "') and (b.status='Scheduled' or b.status='Cancelled') and rd.roomnumber=$_GET[id] 
									and 
				(
				b.checkindatetime<='" . $a->format("Y-m-d H:i") . "' and b.checkoutdatetime>='" . $a->format("Y-m-d H:i") . "'
			)");
	?>

	<div id="snackbar"></div>

	<script type="text/javascript">
		function mymodal(act, id, clr, ttl, msg) {
			document.getElementById('act').value = act;
			document.getElementById('value').value = id;
			document.getElementById('modal-title').innerHTML = ttl;
			document.getElementById('modal-body').innerHTML = msg;
			document.getElementById('act_btn').innerHTML = act;
			document.getElementById('modal_header').setAttribute("class", "modal-header bg-" + clr + " no-border");
			document.getElementById('act_btn').setAttribute("class", "btn btn-" + clr);
		}
	</script>

	<div class="modal fade" id="Modal_id" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content modal-no-shadow modal-no-border">
				<div class="modal-header bg-danger no-border" id="modal_header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="modal-title"></h4>
				</div>
				<div class="modal-body" id="modal-body"></div>
				<div class="modal-footer">
					<a href="javascript:void(0)" id="modalbuttonlink" onclick="document.filter_form.action=&#39;?Pg=&amp;commit=true&#39;;document.filter_form.submit()">
						<button type="button" class="btn btn-danger" id="act_btn"></button>
					</a>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div id="sub_expired" class="white-popup mfp-with-anim mfp-hide ">
		<p>Your Subscription is expired!</p>
		<p>Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>
		<p><a href="https://www.banqueteasy.com/erp/lightboxes/create_invoice.php?id=62010">Click here</a> to renew your subscription</p>
	</div>

	<div id="no_more_halls" class="white-popup mfp-with-anim mfp-hide ">
		<p>You can not add more banquets with your current subscription plan!</p>
		<p>To add more banquets upgrade your plan <a href="javascript:void(0)">here</a></p>
	</div>
	<script src="./Invoice Form_files/jquery-1.10.2.min.js.download"></script>

	<!-- <div id="alter_demo" class="white-popup mfp-with-anim mfp-hide" style="width:180px">
	<div class="row">
		<form method="post" action="?Pg=">
			<div class="col-xs-12">
				<div class="form-group">
					<label>Expiry Date</label>
					<input type="text" class="form-control datepicker" id="expiry_date" name="expiry_date" readonly>
					<input type="hidden" id="sid" name="sid">
				</div>
			</div>
			<div class="col-xs-12">
				<input type="submit" class="btn btn-primary" value="Save">
			</div>
		</form>
	</div>
</div> -->

	<script type="text/javascript">
		function alter_expiry_date(sid, edate) {
			document.getElementById("expiry_date").value = edate;
			document.getElementById("sid").value = sid;
		}
	</script>

	<div id="text-popup-html" class="white-popup mfp-with-anim mfp-hide" style="width:270px">
		<form method="post" action="https://www.banqueteasy.com/erp/lightboxes/create_invoice.php?Pg=">
			<div class="form-group">
				<label>Demo Expiry Date</label>
				<input type="text" class="form-control datepicker hasDatepicker" name="dsdate" placeholder="Demo Start Date" required="" autocomplete="off" onkeyup="this.value=&#39;&#39;" id="dp1725615919696">
				<input type="text" class="form-control datepicker hasDatepicker" name="expiry_date" placeholder="Set Expiry Date" required="" autocomplete="off" onkeyup="this.value=&#39;&#39;" id="dp1725615919697">
				<select name="plan" id="plan" class="form-control" required="">
					<option value="">Select Plan</option>
					<option value="2">Double Banquet</option>
					<option value="3">Multiple Banquet</option>
					<option value="4">Chain of Banquets</option>
				</select>
				<input type="hidden" id="sid" name="sid">
			</div>
			<input type="submit" class="btn btn-primary" value="Set">
		</form>
	</div>


	<div class="the-box" style="padding: 12px 15px 0 15px; margin-bottom:0px">
		<h1 class="page-heading" style="margin:0px 0px 10px 0px;padding-left:0;">Create Invoice</h1>
		<div>
			<script>
				var monopolies = [{
					"monopoly": "Vallet Parking",
					"slab": "10",
					"hsn": "vallethsn",
					"rate": "100"
				}, {
					"monopoly": "Basic Lighting Decoration",
					"slab": "0",
					"hsn": "",
					"rate": "1"
				}, {
					"monopoly": "Walkway Carpet",
					"slab": "12",
					"hsn": "carpet12",
					"rate": "5000"
				}, {
					"monopoly": "Flower Decoration",
					"slab": "0",
					"hsn": "",
					"rate": "200"
				}, {
					"monopoly": "Addon Item Cost",
					"slab": "5",
					"hsn": "",
					"rate": "1"
				}, {
					"monopoly": "Cleaning Boys",
					"slab": "0",
					"hsn": "",
					"rate": "1000"
				}, {
					"monopoly": "Food",
					"slab": "5",
					"hsn": "",
					"rate": "1"
				}, {
					"monopoly": "Decoration",
					"slab": "18",
					"hsn": "",
					"rate": "40000"
				}, {
					"monopoly": "Corkage-Beer",
					"slab": "0",
					"hsn": "",
					"rate": "150"
				}, {
					"monopoly": "Fancy Decoration",
					"slab": "0",
					"hsn": "",
					"rate": "5000"
				}, {
					"monopoly": "DJ",
					"slab": "10",
					"hsn": "",
					"rate": "5000"
				}, {
					"monopoly": "AC Charges",
					"slab": "18",
					"hsn": "ACHSN",
					"rate": "1"
				}, {
					"monopoly": "DJ Royalty",
					"slab": "0",
					"hsn": "",
					"rate": "1000"
				}, {
					"monopoly": "Couple sofa",
					"slab": "0",
					"hsn": "",
					"rate": "1"
				}, {
					"monopoly": "DG set\/Power backup",
					"slab": "0",
					"hsn": "",
					"rate": "1500"
				}, {
					"monopoly": "Varmala & other accessories",
					"slab": "0",
					"hsn": "",
					"rate": "100"
				}, {
					"monopoly": "Room Charges",
					"slab": "0",
					"hsn": "",
					"rate": "998"
				}, {
					"monopoly": "Catering Charges",
					"slab": "0",
					"hsn": "",
					"rate": "100"
				}, {
					"monopoly": "Cleaning Charges",
					"slab": "0",
					"hsn": "",
					"rate": "1"
				}, {
					"monopoly": "Chair Covers",
					"slab": "18",
					"hsn": "",
					"rate": "17"
				}, {
					"monopoly": "Decoration Royalty",
					"slab": "0",
					"hsn": "",
					"rate": "1000"
				}, {
					"monopoly": "Cake Charges",
					"slab": "0",
					"hsn": "",
					"rate": "1"
				}, {
					"monopoly": "Service boys",
					"slab": "5",
					"hsn": "SBHSN",
					"rate": "1200"
				}, {
					"monopoly": "Breakfast",
					"slab": "18",
					"hsn": "BFHSN",
					"rate": "1"
				}, {
					"monopoly": "EMC",
					"slab": "0",
					"hsn": "",
					"rate": "10"
				}, {
					"monopoly": "Corkage-Hard Drink",
					"slab": "0",
					"hsn": "",
					"rate": "1"
				}, {
					"monopoly": "Anchor",
					"slab": "18",
					"hsn": "",
					"rate": "2000"
				}, {
					"monopoly": "Band",
					"slab": "10",
					"hsn": "",
					"rate": "1"
				}, {
					"monopoly": "Projector & screen",
					"slab": "18",
					"hsn": "",
					"rate": "3000"
				}, {
					"monopoly": "Security Deposit",
					"slab": "0",
					"hsn": "",
					"rate": "99998"
				}, {
					"monopoly": "Catering Royalty",
					"slab": "0",
					"hsn": "",
					"rate": "1"
				}, {
					"monopoly": "Extra Hour",
					"slab": "0",
					"hsn": "",
					"rate": "5000"
				}, {
					"monopoly": "Current\/Electricity",
					"slab": "0",
					"hsn": "",
					"rate": "13"
				}, {
					"monopoly": "LED Screen",
					"slab": "0",
					"hsn": "",
					"rate": "5000"
				}, {
					"monopoly": "Additional Decor Charges",
					"slab": "0",
					"hsn": "",
					"rate": "2000"
				}, {
					"monopoly": "Parking Charges",
					"slab": "12",
					"hsn": "PCHSN",
					"rate": "10"
				}];
				var monopolyNames = ["Vallet Parking", "Basic Lighting Decoration", "Walkway Carpet", "Flower Decoration", "Addon Item Cost", "Cleaning Boys", "Food", "Decoration", "Corkage-Beer", "Fancy Decoration", "DJ", "AC Charges", "DJ Royalty", "Couple sofa", "DG set/Power backup", "Varmala & other accessories", "Room Charges", "Catering Charges", "Cleaning Charges", "Chair Covers", "Decoration Royalty", "Cake Charges", "Service boys", "Breakfast", "EMC", "Corkage-Hard Drink", "Anchor", "Band", "Projector & screen", "Security Deposit", "Catering Royalty", "Extra Hour", "Current/Electricity", "LED Screen", "Additional Decor Charges", "Parking Charges"];
				var banq_state = 15;
			</script>

			<div class="row">
				<div class="hr-invoice">
					<p class="Reference-createinvo"><b>Invoice Reference No.: #62010</b></p>
                            <input type="hidden" id="invoice_type" value="<?= $_GET['type'] ?>">
                            <input type="hidden" id="action" value="<?= $_GET['action'] ?>">

				</div>
				<div class="col-xs-12 hr-invoice hr-invoice-padd ">
					<div class="form-group row">
						<div class="same-createinvoice same-cre-mr col-xs-4">
							<div style="margin-bottom: 10px;">
								<label>Customer Name</label>
								<input type="text" name="fullname" class="form-control" value="<?= $row['fullname'] ?>" placeholder="Client&#39;s name" required="">
							</div>
							<div style="margin-bottom: 10px;">
								<label>Address</label>
								<input type="text" name="address" class="form-control" value="<?= $row['address1'] ?>" placeholder="Address" required="">
							</div>
						</div>
						<div class="same-createinvoice same-cre-mr col-xs-4">
							<div style="margin-bottom: 10px;">
								<label>Set Invoice Date</label>
								<input type="text" class="datepicker form-control inp-creat-inv-date hasDatepicker" name="invoice_date" value="06-09-2024" style="margin-bottom: 0px;width: 100%;" readonly="" id="dp1725615919698">
							</div>
							<div style="margin-bottom: 10px;">
								<label>Email Id</label>
								<input type="text" name="email" class="form-control" value="<?= $row['email'] ?>">
							</div>
						</div>
						<div class="same-createinvoice col-xs-4">
							<div style="margin-bottom: 10px;">
								<label>Customer GSTIN</label>
								<input type="text" name="gstin" class="form-control" value="">
								<input type="text" name="hiddeninputonlyused" class="form-control" style="display: none;" value="">
							</div>
							<div style="margin-bottom: 10px;">
								<label>Customer State</label>
								<select name="state" id="state" class="form-control" onchange="invCalc()" required="">
									<option value="">State</option>
									<option value="1">Andhra Pradesh</option>
									<option value="2">Arunachal Pradesh</option>
									<option value="3">Assam</option>
									<option value="4">Bihar</option>
									<option value="5">Chhattisgarh</option>
									<option value="6">Goa</option>
									<option value="7">Gujarat</option>
									<option value="8">Haryana</option>
									<option value="9">Himachal Pradesh</option>
									<option value="10">Jammu and Kashmir</option>
									<option value="11">Jharkhand</option>
									<option value="12">Karnataka</option>
									<option value="13">Kerala</option>
									<option value="14">Madhya Pradesh</option>
									<option value="15" selected="">Maharashtra</option>
									<option value="16">Manipur</option>
									<option value="17">Meghalaya</option>
									<option value="18">Mizoram</option>
									<option value="19">Nagaland</option>
									<option value="20">Odisha</option>
									<option value="21">Punjab</option>
									<option value="22">Rajasthan</option>
									<option value="23">Sikkim</option>
									<option value="24">Tamil Nadu</option>
									<option value="25">Tripura</option>
									<option value="26">Uttar Pradesh</option>
									<option value="27">Uttarakhand</option>
									<option value="28">West Bengal</option>
									<option value="29">Andaman and Nicobar Islands</option>
									<option value="30">Chandigarh</option>
									<option value="31">Dadra and Nagar Haveli</option>
									<option value="32">Daman and Diu</option>
									<option value="33">Lakshadweep</option>
									<option value="34">National Capital Territory of Delhi</option>
									<option value="35">Puducherry</option>
									<option value="36">Telangana</option>
									<option value="37">New Delhi</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			$invoice = execute("select isgst,state,start_invoice_no,invoice_prefix,signature_label,bank_detail,account_no,branch_detail,ifsc,upi_id,gstin_no from invoice_setup where hotel='$_SESSION[hotel]'");

			$isgst = $invoice['isgst'];

			$display_gst = $isgst ? 'block' : 'none';
			?>
			<div class="row descparent">
				<div class="form-group row" style="margin-left: 0;">
					<div class="inv-desc float-left">
						<label>Description</label>
					</div>
					<!-- <div class="inv-vat float-left">
						<label>Vat</label>
					</div> -->
					<div class="inv-hsn float-left">
						<label>HSN</label>
					</div>
					<div class="inv-rate float-left">
						<label>Rate</label>
					</div>
					<div class="inv-qty float-left">
						<label>Qty</label>
					</div>
					<div class="inv-qty float-left">
						<label>Sub Total</label>
					</div>
					<div class="inv-disctype float-left">
						<label>Discount Type</label>
					</div>
					<div class="inv-qty float-left">
						<label>Discount</label>
					</div>

					<?php
					if ($isgst) {
					?>
						<div class="inv-qty float-left">
							<label>Taxable</label>
						</div>
						<div class="inv-slab float-left">
							<label>GST%</label>
						</div>
						<!-- <div class="inv-gst float-left">
							<label>VAT</label>
						</div> -->
						<div class="inv-gst cls-csgst float-left" style="display:block">
							<label>CGST</label>
						</div>
						<div class="inv-gst cls-csgst float-left" style="display:block">
							<label>SGST</label>
						</div>
						<div class="inv-gst cls-igst float-left" style="display:none">
							<label>IGST</label>
						</div>
					<?php
					} ?>
					<div class="inv-total float-left">
						<label>Total</label>
					</div>
				</div>
			</div>
			<div id="description" class="form-group">
				<input type="hidden" id="igst_flag" value="0">
				<?php
				$invoice_num = execute("select count(*)cnt,invoice_data from invoices where bookingid = $row[id]");
				// $data = execute("select * from invoices where bookingid = $row[id]");
				// print_r($data);
				if ($invoice_num['cnt'] == 0) {
				?>
					<?php
					$intitalroomamount = execute("select service,tax,vat,gst,hsn,id from additional_services where hotel=$_SESSION[hotel] and ishotelroomtax=1");
					$no_of_room = execute("select COUNT(*)cnt from room_distribution where bookingid=$row[id]");

					$discount = execute("select discount_flat,discount_percent from payment_mode where bookingid=$row[id] and payment_type='discount' and deleted=0");

					$discount_price = 0;
					$flat = 1; // Default to 'Flat'

					if ($discount) {
						if ($discount['discount_flat'] != '') {
							$discount_price = $discount['discount_flat'];
						} elseif ($discount['discount_percent'] != '') {
							$flat = 0; // Set to '%' if there's a discount percentage
							$discount_price = $discount['discount_percent'];
						}
					}

					$discount_type = $flat == 0 ? 'selected' : '';

					$vatChecked = $intitalroomamount['vat'] == 1 ? 'checked' : '';
					$charge = $intitalroomamount['charge'];

					$chargeWithoutDecimal = intval($charge);
					$checkindate = new DateTime($row['checkindatetime']);

					$sub_total = $row['intialtariff'] / $no_of_room['cnt'];
					$subtotal = $sub_total;

					//($subtotal);

					?>
					<div class="row" id="row1" style="margin-left: 0;">
						<div class="inv-desc float-left" style="padding-left:0;">

							<div class="form-group d-flex">
								<a href="javascript:void(0)" class="text-danger invoice_close_btn cross-icon-invocre" onclick="cancel_invoice_row(1)" title="Cancel" style="position: relative;top: 5px;">X</a>
								<input type="text" style="width:90%; float:left;margin-right: 10px;" class="form-control" id="desc1" name="description[]" value="<?= $intitalroomamount['service'] ?> (<?= $checkindate->format('d-m-Y') ?>)">
								<input type="text" style="display:none;" class="form-control" id="sourcetable1" value="maintable">
								<input type="text" style="display:none;" class="form-control" id="dataid1" value="<?= $intitalroomamount['id'] ?>">
							</div>
						</div>

						<input type="checkbox" <?= $vatChecked ?> class="form-control" id="isvat1" name="isvat1" value="1" disabled style="display:none;">
						<div class="inv-hsn float-left" style="padding-left:0;">

							<div class="form-group">
								<input type="text" class="form-control" id="hsn1" name="hsn[]" value="<?= $intitalroomamount['hsn'] ?>">
							</div>
						</div>
						<div class="inv-rate float-left" style="padding-left:0;">

							<div class="form-group">
									<input type="hidden" min="0" step="0.01" class="form-control nonnegative" id="rates1" name="rates[]" onkeyup="invCalc()" value="<?= $subtotal ?>">	
									

								<input type="number" min="0" step="0.01" class="form-control nonnegative" id="rate1" name="rate[]" onkeyup="invCalc()" value="<?= $subtotal ?>">
							</div>
						</div>
						<div class="inv-qty float-left" style="padding-left:0;">

							<div class="form-group">
								<input type="number" min="1" step="1" class="form-control nonnegative" id="qty1" name="qty[]" onkeyup="invCalc()" value="<?= $no_of_room['cnt'] ?>">
							</div>
						</div>
						<div class="inv-qty float-left" style="padding-left:0;">

							<div class="form-group">
								<input type="number" min="0" step="1" class="form-control" id="subtot1" name="subtot[]" value="<?= $subtotal * $no_of_room['cnt'] ?>" readonly="">
							</div>
						</div>
						<div class="inv-disctype float-left" style="padding-left:0;">
							<div class="form-group d-flex">
								<input type="number" min="0" step="1" class="form-control width-50 nonnegative" id="discper1" name="discper[]" onkeyup="invCalc()" value="<?= $discount_price ?>" style="margin-bottom:15px;">
								<select class="form-control width-50" id="dtype1" name="dtype[]">
									<option>Flat</option>
									<option <?= $discount_type ?>>%</option>
								</select>
							</div>
						</div>
						<div class="inv-qty float-left" style="padding-left:0;">

							<div class="form-group">
								<input type="number" class="form-control" id="discount1" name="discount[]" value="0" readonly="">
							</div>
						</div>
						<div class="inv-qty float-left">
							<div class="form-group" style="display: <?= $display_gst ?>;">
								<input type="number" class="form-control" id="taxable1" name="taxable[]" value="10000" readonly="">
							</div>
						</div>

						<div class="inv-slab float-left">
							<div class="form-group" style="display: <?= $display_gst ?>;">
								<input type="number" step="1" min="0" class="form-control nonnegative" name="slab[]" onkeyup="invCalc()" id="slab1" value="<?= $isgst ? $intitalroomamount['tax'] : 0 ?>">
							</div>
						</div>
						<div class="inv-gst cls-csgst float-left" style="display:block">
							<div class="form-group" style="display: <?= $display_gst ?>;">
								<input type="text" class="form-control" id="cgst1" name="cgst[]" value="900" readonly="">
							</div>
						</div>
						<div class="inv-gst cls-csgst float-left" style="display:block">
							<div class="form-group" style="display: <?= $display_gst ?>;">
								<input type="text" class="form-control" id="sgst1" name="sgst[]" value="900" readonly="">
							</div>
						</div>
						<div class="inv-gst cls-igst float-left">
							<div class="form-group" style="display: <?= $display_gst ?>;">
								<input type="text" class="form-control" id="igst1" name="igst[]" value="0" readonly="">
							</div>
						</div>
						<div class="inv-total float-left">
							<div class="form-group">
								<input type="text" class="form-control" id="total1" name="total[]" value="<?= $subtotal * $no_of_room['cnt'] ?>" readonly="">
							</div>
						</div>

					<?php
				} ?>
					</div>
			</div>
			<div class="row addmore-parent">
				<div class="col-xs-12">
					<div class="form-group">
						<a href="javascript:void(0)" id="addInvDescRow" style="text-decoration:none">+ Add more</a>
					</div>
				</div>
			</div>
			<?php 
			$invoice_num = execute("select count(*)cnt,invoice_data from invoices where bookingid = $row[id]");
			if ($invoice_num['cnt'] == 0) {
			?>
				<script>
					$(document).ready(function() {
						invCalc()
						let isgst = JSON.parse(<?= json_encode($isgst); ?>);
						let display_gst = isgst ? 'block' : 'none';

						<?php
						$alluserservices = $conn->query("SELECT 
					gas.id,
					a.service,
					a.charge,
					a.tax,
					a.hsn,
					a.vat,
					a.gst,
					gas.quantity,
					gas.created_at,
					'guest_additional_services' AS source_table
					FROM 
						guest_additional_services gas
					LEFT JOIN 
						additional_services a ON a.id = gas.additional_service_id
					LEFT JOIN 
						additional_services_receipt ar ON ar.guest_service_id = gas.id
					WHERE 
						gas.bookingid = $row[id] 
						AND gas.deleted = 0 AND a.ishotelroomtax=0
					;");

						while ($rs_row = $alluserservices->fetch_assoc()) {
							$vatChecked = $rs_row['vat'] == 1 ? 'checked' : '';
							$charge = $rs_row['charge']; // e.g., 100.00
							$chargeWithoutDecimal = intval($charge);
							$date = new DateTime($rs_row['created_at']);
						?>

							var i = 1;

							while (document.getElementById("desc" + i))
								i++;

							var str = `
										<div class="row" id="row${i}" style="margin-left:0;">
											<div class="inv-desc">
												<div class="form-group d-flex">
													<a href="javascript:void(0)" class="text-danger invoice_close_btn cross-icon-invocre" 
													onClick="cancel_invoice_row(${i});" title="Cancel">X</a>
													<input type="text" style="width:90%; float:left" class="form-control" id="desc${i}" name="description[]"
														value="<?= $rs_row['service'] ?> (<?= $date->format('d-m-Y') ?>)">
													<input type="text" style="display:none;" class="form-control" id="sourcetable${i}" 
														value="<?= $rs_row['source_table'] ?>">
													<input type="text" style="display:none;" class="form-control" id="dataid${i}" value="<?= $rs_row['id'] ?>">
												</div>
											</div>
											<input type="checkbox" <?= $vatChecked ?> class="form-control" id="isvat${i}"name="isvat${i}" value="1" disabled style="display:none;">

											<div class="inv-hsn">
												<div class="form-group">
													<input type="text" class="form-control" id="hsn${i}" name="hsn[]" value="<?= $rs_row['hsn'] ?>">
												</div>
											</div>

											<div class="inv-rate">
												<div class="form-group">
												<input type="hidden" min="0" step="1" class="form-control nonnegative" id="rates${i}" name="rates[]"
														onKeyup="invCalc()" value="<?= $chargeWithoutDecimal ?>">
														
													<input type="number" min="0" step="1" class="form-control nonnegative" id="rate${i}" name="rate[]"
														onKeyup="invCalc()" value="<?= $chargeWithoutDecimal ?>">
												</div>
											</div>

											<div class="inv-qty">
												<div class="form-group">
													<input type="number" min="1" step="1" class="form-control nonnegative" id="qty${i}" name="qty[]" 
														onKeyup="invCalc()" value="<?= $rs_row['quantity'] ?>">
												</div>
											</div>

											<div class="inv-qty">
												<div class="form-group">
													<input type="number" min="0" step="1" class="form-control" id="subtot${i}" name="subtot[]" 
														value="<?= $chargeWithoutDecimal * $rs_row['quantity'] ?>" readonly>
												</div>
											</div>

											<div class="inv-disctype">
												<div class="form-group d-flex">
													<input type="number" min="0" step="1" class="form-control width-50 nonnegative" id="discper${i}" 
														name="discper[]" onKeyup="invCalc()" value="0">
													<select class="form-control width-50" id="dtype${i}" name="dtype[]">
														<option>Flat</option>
														<option>%</option>
													</select>
												</div>
											</div>

											<div class="inv-qty">
												<div class="form-group">
													<input type="number" class="form-control" id="discount${i}" name="discount[]" value="0" readonly>
												</div>
											</div>

											<div class="inv-qty">
												<div class="form-group" style="display: ${display_gst};">
													<input type="number" class="form-control" id="taxable${i}" name="taxable[]" 
														value="<?= $chargeWithoutDecimal * $rs_row['quantity'] ?>" readonly>
												</div>
											</div>

											<div class="inv-slab">
												<div class="form-group" style="display: ${display_gst};">
													<input type="number" step="1" min="0" class="form-control nonnegative" name="slab[]" onKeyup="invCalc()"
														id="slab${i}" value="${isgst?<?= $rs_row['tax'] ?>:0}">
												</div>
											</div>
											<div class="inv-gst">
												<div class="form-group" style="display: ${display_gst};">
													<input type="text" class="form-control" id="cgst${i}" name="cgst[]" value="0" readonly>
												</div>
											</div>

											<div class="inv-gst cls-csgst">
												<div class="form-group" style="display: ${display_gst};">
													<input type="text" class="form-control" id="sgst${i}" name="sgst[]" value="0" readonly>
												</div>
											</div>

											<div class="inv-gst cls-igst">
												<div class="form-group" style="display: ${display_gst};">
													<input type="text" class="form-control" id="igst${i}" name="igst[]" value="0" readonly>
												</div>
											</div>

											<div class="inv-total">
												<div class="form-group">
													<input type="text" class="form-control" id="total${i}" name="total[]" value="0" readonly>
												</div>
											</div>
										</div>
									`;


							$("#description").append(str);
							document.querySelector('input[name="hiddeninputonlyused"]').value = i;


							var state = document.getElementById("state").value;

							if (state == banq_state) {
								$(".cls-csgst").show();
								$(".cls-igst").hide();
							} else {
								$(".cls-csgst").hide();
								$(".cls-igst").show();
							}
							invCalc();
						<?php
						} ?>
					});
				</script>
			<?php
			} else { ?>
				<?php
				$decoded_data = html_entity_decode($invoice_num['invoice_data']);
				$decoded_data = trim($decoded_data, '"');
				$decoded_data = stripslashes($decoded_data);
				?>
				<script>
					let data = JSON.parse(<?= json_encode($decoded_data); ?>); // Use json_encode to safely encode the data
					let isgst = JSON.parse(<?= json_encode($isgst); ?>);
					let display_gst = isgst ? 'block' : 'none';

					$(document).ready(function() {
						var i = 1;

						var str = ''

						data.rowData.map((dat, index) => {
							let flat = '';
							let percent = '';

							if (dat.dtype === "%") {
								percent = 'selected';
							} else {
								flat = 'selected';
							}
							let subtotal = dat.rate * dat.qty;
							let taxable = subtotal - dat.discount;
							total = taxable + dat.cgst + dat.igst +dat.sgst;
							let total_amount = dat.total / dat.qty;
							str += `
								<div class="row" id="row${i}" style="margin-left:0;">
									<div class="inv-desc">
									<div class="form-group d-flex">
										<a href="javascript:void(0)" class="text-danger invoice_close_btn cross-icon-invocre" 
										onClick="cancel_invoice_row(${i});" title="Cancel">X</a>
										<input type="text" style="width:90%; float:left" class="form-control" 
											id="desc${i}" name="description[]" value="${dat.description}">
									</div>
									</div>
									<div class="inv-hsn">
									<div class="form-group">
										<input type="text" class="form-control" id="hsn${i}" name="hsn[]" value="${dat.hsn}">
									</div>	
									</div>
									<div class="inv-rate">
									<div class="form-group">		
									<input type="hidden" min="0" step="1" class="form-control nonnegative" id="rates${i}" 
											name="rates[]" onKeyup="invCalc()" value="${total_amount}">					
										<input type="number" min="0" step="1" class="form-control nonnegative" id="rate${i}" 
											name="rate[]" onKeyup="invCalc()" value="${dat.rate}">
									</div>
									</div>
									<div class="inv-qty">
									<div class="form-group">
										<input type="number" min="1" step="1" class="form-control nonnegative" id="qty${i}" 
											name="qty[]" onKeyup="invCalc()" value="${dat.qty}">
									</div>
									</div>
									<div class="inv-qty">
									<div class="form-group">
										<input type="number" min="0" step="1" class="form-control" id="subtot${i}" 
											name="subtot[]" value="${subtotal}" readonly>
									</div>
									</div>
									<div class="inv-disctype">
									<div class="form-group d-flex">
										<input type="number" min="0" step="1" class="form-control width-50 nonnegative" 
											id="discper${i}" name="discper[]" onKeyup="invCalc()" value="${dat.discount}">
										<select class="form-control width-50" id="dtype${i}" name="dtype[]">
										<option ${flat}>Flat</option>
										<option ${percent}>%</option>
										</select>
									</div>
									</div>
									<div class="inv-qty">
									<div class="form-group">
										<input type="number" class="form-control" id="discount${i}" name="discount[]" value="0" readonly>
									</div>
									</div>
									<div class="inv-qty">
									<div class="form-group" style="display: ${display_gst};">
										<input type="number" class="form-control" id="taxable${i}" name="taxable[]" value="${taxable}" readonly>
									</div>
									</div>
									<div class="inv-slab">
									<div class="form-group" style="display: ${display_gst};">
										<input type="number" step="1" min="0" class="form-control nonnegative" name="slab[]" 
											onkeyup="invCalc()" id="slab${i}" value="${isgst? dat.slab:0}">
									</div>
									</div>
									<div class="inv-gst">
									<div class="form-group" style="display: ${display_gst};">
										<input type="text" class="form-control" id="cgst${i}" name="cgst[]" value="${dat.cgst}" readonly>
									</div>
									</div>
									<div class="inv-gst cls-csgst">
									<div class="form-group" style="display: ${display_gst};">
										<input type="text" class="form-control" id="sgst${i}" name="sgst[]" value="${dat.sgst}" readonly>
									</div>
									</div>
									<div class="inv-gst cls-igst">
									<div class="form-group" style="display: ${display_gst};">
										<input type="text" class="form-control" id="igst${i}" name="igst[]" value="${dat.igst}" readonly>
									</div>
									</div>
									<div class="inv-total">
									<div class="form-group" >
										<input type="text" class="form-control" id="total${i}" name="total[]" value="${total}" readonly>
									</div>
									</div>
								</div>`;


							i++;
						})

						$("#description").append(str);
						document.querySelector('input[name="hiddeninputonlyused"]').value = i;


						var state = document.getElementById("state").value;

						if (state == banq_state) {
							$(".cls-csgst").show();
							$(".cls-igst").hide();
						} else {
							$(".cls-csgst").hide();
							$(".cls-igst").show();
						}
						invCalc();
					});
				</script>
			<?php } ?>

			<div class="row form-groupmb">
				<div class="col-xs-2 pull-right">
					<div class="form-group">
						<input type="text" class="form-control" id="subtotal" value="10000" readonly="">
					</div>
				</div>
				<div class="col-xs-2 pull-right">
					<div class="form-group text-right">
						<label>Total Taxable Amount</label>
					</div>
				</div>
			</div>
			<div class="row form-groupmb">
				<div class="col-xs-2 pull-right">
					<div class="form-group">
						<input type="text" class="form-control" id="totdisc" name="totdisc" value="0" readonly="">
					</div>
				</div>
				<div class="col-xs-2 pull-right">
					<div class="form-group text-right">
						<label>Total Discount</label>
					</div>
				</div>
			</div>
			<div class="row form-groupmb" style="display: <?= $display_gst ?>;">
				<div class="col-xs-2 pull-right">
					<div class="form-group">
						<input type="text" class="form-control" id="gsttotal" value="1800" readonly="">
					</div>
				</div>
				<div class="col-xs-2 pull-right">
					<div class="form-group text-right">
						<label>Total GST</label>
					</div>
				</div>
			</div>
			<div class="row form-groupmb" style="display: <?= $display_gst ?>;">
				<div class="col-xs-2 pull-right">
					<div class="form-group">
						<input type="text" class="form-control" id="vattotal" value="0" readonly="">
					</div>
				</div>
				<div class="col-xs-2 pull-right">
					<div class="form-group text-right">
						<label>Total VAT</label>
					</div>
				</div>
			</div>
			<div class="row form-groupmb" style="display:none">
				<div class="col-xs-2 pull-right">
					<div class="form-group">
						<input type="text" class="form-control" id="total" name="total" value="10000" readonly="">
					</div>
				</div>
				<div class="col-xs-2 pull-right">
					<div class="form-group text-right">
						<label>Total</label>
					</div>
				</div>
			</div>
			<!-- <div class="row form-groupmb" style="display:none">
						<div class="col-xs-2 pull-right">
							<div class="form-group">
								<input type="number" class="form-control" id="discount" name="discount" value="0">
							</div>
						</div>
						<div class="col-xs-2 pull-right">
							<div class="form-group text-right">
								<label>Discount</label>
							</div>
						</div>
					</div> -->
			<div class="row form-groupmb">
				<div class="col-xs-2 pull-right">
					<div class="form-group">
						<input type="text" class="form-control" id="grand_total" name="grand_total" value="11800" readonly="">
					</div>
				</div>
				<div class="col-xs-2 pull-right">
					<div class="form-group text-right">
						<label style="font-size: 15px; color: #fb3c3c">Grand Total</label>
					</div>
				</div>
			</div>
			<div class="row form-groupmb">
				<div class="col-xs-2 pull-right">
					<div class="form-group">
						<input type="text" class="form-control" id="gt_round" name="gt_round" value="11800" readonly="">
					</div>
				</div>
				<div class="col-xs-2 pull-right">
					<div class="form-group text-right">
						<label style="font-size: 15px; color: #fb3c3c">Round off</label>
					</div>
				</div>
			</div>
			<?php
			$advancepayment = execute("SELECT amount 
                                FROM payment_mode 
                                WHERE bookingid = $row[id] 
                                AND payment_type NOT IN ('discount', 'payatcheckout', 'writeoff') 
                                AND deleted = 0;");

			// Check if advance payment is not found and set it to 0
			$advancepayment_amount = $advancepayment ? $advancepayment['amount'] : 0;
			?>
			<div class="row form-groupmb">
				<div class="col-xs-2 pull-right">
					<div class="form-group">
						<input type="text" class="form-control" id="total_paid" name="total_paid" value="<?= $advancepayment_amount ?>" readonly="">
					</div>
				</div>
				<div class="col-xs-2 pull-right">
					<div class="form-group text-right">
						<label>Total Advance Paid</label>
					</div>
				</div>
			</div>

			<div class="row form-groupmb">
				<div class="col-xs-2 pull-right">
					<div class="form-group">
						<input type="text" class="form-control" id="balance_amt" name="balance_amt" value="11800" readonly="">
					</div>
				</div>
				<div class="col-xs-2 pull-right">
					<div class="form-group text-right">
						<label>Balance Amount</label>
					</div>
				</div>
			</div>
			<div class="row create-sticky">
				<div class="col-xs-12">
					<div class="form-group text-center">
						<input type="hidden" id="part_cnt" value="1">
						<input type="button" onclick="handleClick()" class="btn" id="createinv" value="Create Invoice">
					</div>
				</div>
			</div>
		</div>

		<script>
			function updateData() {
				const rows = document.querySelectorAll('[id^="row"]');
				let invoiceData = []

				rows.forEach((row, index) => {
					const rowData = {
						service: row.querySelector(`#desc${index + 1}`).value,
						source_table: row.querySelector(`#sourcetable${index + 1}`).value,
						id: row.querySelector(`#dataid${index + 1}`).value,
						vat: row.querySelector(`#isvat${index + 1}`).checked ? 1 : 0,
						gst: row.querySelector(`#isvat${index + 1}`).checked ? 0 : 1,
						hsn: row.querySelector(`#hsn${index + 1}`).value,
						rate: parseFloat(row.querySelector(`#rate${index + 1}`).value),
						quantity: parseInt(row.querySelector(`#qty${index + 1}`).value),
						isflat: document.querySelector(`#dtype${i}`).value === 'Flat' ? 1 : 0,
						discount: parseFloat(document.querySelector(`#discount${i}`).value),
						tax: parseFloat(document.querySelector(`#slab${i}`).value),
					};
					invoiceData.push(rowData);
				});
				console.log(invoiceData);
			}


			function handleClick() {
				const rows = document.querySelectorAll('[id^="row"]');
				const invoiceData = [];
				const new_row_data = [];

				let index = 1;
				// updateData()

				while (document.getElementById("desc" + index))
					index++;

				let old_row = document.querySelector('input[name="hiddeninputonlyused"]').value;

				if ((index - 1) !== Number(old_row)) {
					for (let i = Number(old_row) + 1; i <= (index - 1); i++) {
						const rowData = {
							service: document.querySelector(`#desc${i}`).value,
							gst: document.querySelector(`#isvat${i}`).checked ? 0 : 1,
							vat: document.querySelector(`#isvat${i}`).checked ? 1 : 0,
							hsn: document.querySelector(`#hsn${i}`).value,
							charge: parseFloat(document.querySelector(`#rate${i}`).value),
							quantity: parseInt(document.querySelector(`#qty${i}`).value),
							isflat: document.querySelector(`#dtype${i}`).value === 'Flat' ? 1 : 0,
							discount: parseFloat(document.querySelector(`#discount${i}`).value),
							tax: parseFloat(document.querySelector(`#slab${i}`).value),
						};
						new_row_data.push(rowData);
					}
					console.log(new_row_data);
					$.ajax({
						type: 'POST',
						url: `../bookingajax.php`,
						data: {
							action: "add_additional_service_addons",
							bookingid: <?= $row['id'] ?>,
							hotel: <?= $_SESSION['hotel'] ?>,
							services: new_row_data
						},
						success: function(response) {
							res = JSON.parse(response)
							console.log(res);

							// location.reload()
						},
						error: function(err) {
							console.log('err', err);
						}
					});
				}

				rows.forEach((row, index) => {
					const rowData = {
						description: row.querySelector(`#desc${index + 1}`).value,
						isvat: row.querySelector(`#isvat${index + 1}`).checked ? 1 : 0,
						isigst: parseFloat(row.querySelector(`#igst${index + 1}`).value) > 0 ? 1 : 0,
						hsn: row.querySelector(`#hsn${index + 1}`).value,
						rate: parseFloat(row.querySelector(`#rate${index + 1}`).value),
						qty: parseInt(row.querySelector(`#qty${index + 1}`).value),
						subtot: parseFloat(row.querySelector(`#subtot${index + 1}`).value),
						dtype: row.querySelector(`#dtype${index + 1}`).value,
						discount: parseFloat(row.querySelector(`#discount${index + 1}`).value),
						taxable: parseFloat(row.querySelector(`#taxable${index + 1}`).value),
						slab: parseFloat(row.querySelector(`#slab${index + 1}`).value),
						vat: 0,
						cgst: parseFloat(row.querySelector(`#cgst${index + 1}`).value),
						sgst: parseFloat(row.querySelector(`#sgst${index + 1}`).value),
						igst: parseFloat(row.querySelector(`#igst${index + 1}`).value),
						total: parseFloat(row.querySelector(`#total${index + 1}`).value)
					};
					invoiceData.push(rowData);
					// console.log(invoiceData);
				});
				let additionalData = {
					subtotal: parseFloat(document.getElementById('subtotal').value),
					totalDiscount: parseFloat(document.getElementById('totdisc').value),
					totalGST: parseFloat(document.getElementById('gsttotal').value),
					totalVAT: parseFloat(document.getElementById('vattotal').value),
					total: parseFloat(document.getElementById('total').value),
					grandTotal: parseFloat(document.getElementById('grand_total').value),
					roundOff: parseFloat(document.getElementById('gt_round').value),
					totalPaid: parseFloat(document.getElementById('total_paid').value),
					balanceAmount: parseFloat(document.getElementById('balance_amt').value)
				};
				const alldata = {
					rowData: invoiceData,
					summatyData: additionalData
				}
				console.log(JSON.stringify(alldata));
				$.ajax({
					type: 'POST',
					url: `../bookingajax.php`,
					data: {
						action: "add_invoice",
						bookingid: <?= $row['id'] ?>,
						hotel: <?= $_SESSION['hotel'] ?>,
						dump_data: JSON.stringify(alldata),
						user: <?= $_SESSION['id'] ?>,
						customergstin: "27SGSHHDJDHSK"
					},
					success: function(response) {
						res = JSON.parse(response)
						console.log(res);
						window.parent.location = window.location.origin + "/pms/admin.php?Pg=invoices";
					},
					error: function(err) {
						console.log('err', err);
					}
				});
			}
		</script>
	</div>
	<script src="./Invoice Form_files/jquery.min.js.download"></script>

	<!------------------------------ Datepicker (Another part below) -------------------------------->
	<script type="text/javascript">
		$.noConflict();

		$(function() {
			$(".datepicker").datepicker({
				dateFormat: 'dd-mm-yy'
			});
			$(".datepicker_no_future").datepicker({
				dateFormat: 'dd-mm-yy',
				maxDate: new Date()
			});
			$(".datepicker_no_past").datepicker({
				dateFormat: 'dd-mm-yy',
				minDate: new Date()
			});

			var a = window.innerHeight;
			var b = a - 307;
			var c = b + 70;
			var d = c - 95;
			// var th=a-255;
			var th = a - 55;
			var tc = a - 118;
			$('.inner-content-div').slimScroll({
				height: d + 'px',
				color: '#d14244'
			});

			$('.table-2-scroll').css({
				"height": b + "px"
			});
			$('.onlyb').css({
				"height": c + "px"
			});
			$('.scroll-d').css({
				"height": d + "px"
			});
			$('.scroll-d-2').css({
				"height": th + "px"
			});
			$('.t-c-maind').css({
				"height": tc + "px"
			});


		});
		$(document).ready(function() {
			$(".srcoll-any-d").niceScroll({
				cursorborder: "",
				cursorcolor: "#d14244",
				boxzoom: false
			});
			$(".srcoll-any-d2").niceScroll({
				cursorborder: "",
				cursorcolor: "#d14244",
				boxzoom: false
			});
			$(".chosen-results").niceScroll({
				cursorborder: "",
				cursorcolor: "#d14244",
				boxzoom: false
			});
		});
		$(".add-h-click").click(function() {
			$(this).parent().toggleClass("add-hall-tog");
			// alert($(this).parent());
			$(this).children().toggleClass("fa-plus fa-minus");
		});
		$(".add-h-click-t").click(function() {
			$(".add-h-click").click();
		});
	</script>
	<!--------------------------------------- Datepicker -------------------------------------------->

	<!----------------------------------------- SENTIR Files ---------------------------------------->
	<script src="./Invoice Form_files/aws.js.download?v=<?= time() ?>"></script>
	<script src="./Invoice Form_files/jquery.min(1).js.download"></script>
	<script src="./Invoice Form_files/bootstrap.min.js.download"></script>
	<script src="./Invoice Form_files/retina.min.js.download"></script>
	<script src="./Invoice Form_files/jquery.nicescroll.js.download"></script>
	<script src="./Invoice Form_files/jquery.slimscroll.min.js.download"></script>
	<script src="./Invoice Form_files/jquery.backstretch.min.js.download"></script>

	<script src="./Invoice Form_files/jquery-ui.min.js.download"></script>
	<!-- PLUGINS -->
	<script src="./Invoice Form_files/skycons.js.download"></script>
	<script src="./Invoice Form_files/prettify.js.download"></script>
	<script src="./Invoice Form_files/jquery.magnific-popup.min.js.download"></script>
	<script src="./Invoice Form_files/owl.carousel.min.js.download"></script>
	<script src="./Invoice Form_files/chosen.jquery.min.js.download"></script>
	<!-- <script src="https://www.banqueteasy.com/erp/js/chosen.js"></script> -->
	<script src="./Invoice Form_files/icheck.min.js.download"></script>
	<!-- <script src="https://www.banqueteasy.com/erp/js/bootstrap-datepicker.js"></script> -->
	<script src="./Invoice Form_files/bootstrap-timepicker.js.download"></script>
	<script src="./Invoice Form_files/jquery.mask.min.js.download"></script>
	<script src="./Invoice Form_files/bootstrapValidator.min.js.download"></script>
	<script src="./Invoice Form_files/jquery.dataTables.min.js.download"></script>
	<script src="./Invoice Form_files/bootstrap.datatable.js.download"></script>
	<script src="./Invoice Form_files/summernote.min.js.download"></script>
	<script src="./Invoice Form_files/markdown.js.download"></script>
	<script src="./Invoice Form_files/to-markdown.js.download"></script>
	<script src="./Invoice Form_files/bootstrap-markdown.js.download"></script>
	<!-- <script src="js/salvattore.min.js"></script> THIS FILE NEED FOR MASONRY -->
	<script src="./Invoice Form_files/jquery.newsTicker.min.js.download"></script>
	<script src="./Invoice Form_files/toastr.js.download"></script>

	<script src="./Invoice Form_files/jquery.nicescroll.min.js.download"></script>
	<!-- FULL CALENDAR JS -->
	<script src="./Invoice Form_files/jquery-ui.custom.min.js.download"></script>
	<script src="./Invoice Form_files/fullcalendar.min.js.download"></script>
	<script src="./Invoice Form_files/full-calendar.js.download"></script>

	<!-- EASY PIE CHART JS -->
	<script src="./Invoice Form_files/easypiechart.min.js.download"></script>
	<script src="./Invoice Form_files/jquery.easypiechart.min.js.download"></script>

	<!-- KNOB JS -->
	<!--[if IE]>
		<script type="text/javascript" src="js/excanvas.js"></script>
		<![endif]-->
	<script src="./Invoice Form_files/jquery.knob.js.download"></script>
	<script src="./Invoice Form_files/knob.js.download"></script>

	<!-- FLOT CHART JS -->
	<script src="./Invoice Form_files/jquery.flot.js.download"></script>
	<!-- <script src="https://www.banqueteasy.com/erp/js/jquery.flot.tooltip.js"></script> -->
	<script src="./Invoice Form_files/jquery.flot.resize.js.download"></script>
	<script src="./Invoice Form_files/jquery.flot.selection.js.download"></script>
	<script src="./Invoice Form_files/jquery.flot.stack.js.download"></script>
	<script src="./Invoice Form_files/jquery.flot.time.js.download"></script>

	<!-- MORRIS JS -->
	<script src="./Invoice Form_files/raphael.min.js.download"></script>
	<script src="./Invoice Form_files/morris.min.js.download"></script>

	<!-- C3 JS -->
	<script src="./Invoice Form_files/d3.v3.min.js.download" charset="utf-8"></script>
	<script src="./Invoice Form_files/c3.min.js.download"></script>

	<!-- MAIN APPS JS -->
	<script src="./Invoice Form_files/apps.js.download"></script>
	<script src="./Invoice Form_files/example.js.download"></script>
	<!-- <script src="https://www.banqueteasy.com/erp/js/demo-panel.js"></script> -->
	<!---------------------------------------- //SENTIR Files --------------------------------------->

	<!------------------------------ Datepicker (Another part above) -------------------------------->
	<link href="./Invoice Form_files/jquery-ui(1).css" rel="stylesheet">
	<!-- <link href="https://www.banqueteasy.com/erp/third_party/datepicker/style.css" rel="stylesheet"> -->
	<!-- <script src="https://www.banqueteasy.com/erp/third_party/datepicker/jquery-1.12.4.js"></script> -->
	<script src="./Invoice Form_files/jquery-ui.js.download"></script>


	<!-- <script type="text/javascript" src="https://www.banqueteasy.com/erp/fancybox/jquery.fancybox-1.3.4.pack.js"></script> -->

	<!-- <script type="text/javascript" src="https://www.banqueteasy.com/erp/js/date-pic-new.js"></script>
		<script type="text/javascript">
			$(function () {

                 $('.date').datetimepicker({
                 	minDate:new Date(),
                  maxDate:new Date(),
                	format: 'DD/MM/YYYY'
                 });
                 
           });
		</script> -->
	<!------------------------------------- //Datepicker -------------------------------------------->

	<!--------------------------------------- Fancy Box Lightbox ------------------------------------>
	<!-- Add mousewheel plugin (this is optional) -->
	<!-- <script type="text/javascript" src="https://www.banqueteasy.com/erp/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script> -->
	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="./Invoice Form_files/jquery.fancybox.js.download"></script>
	<!-- <link rel="stylesheet" type="text/css" href="https://www.banqueteasy.com/erp/fancybox/source/jquery.fancybox.css?v=2.1.0" media="screen" /> -->

	<script type="text/javascript">
		// =====================Fast lightbox======================
		// $('.fancybox_250, .fancybox_350, .fancybox_450, .fancybox_550, .fancybox_650, .fancybox_750, .fancybox_850, .fancybox_950, .set_click_open_lb').click(function()
		$(document).on("click", ".fancybox_250, .fancybox_350, .fancybox_450, .fancybox_550, .fancybox_650, .fancybox_750, .fancybox_850, .fancybox_950, .set_click_open_lb", function() {
			if ($(this).hasClass("fancybox_250")) {
				$('.setNewLb_myd .setNewLb_myd_child').css('width', '290px');
			} else if ($(this).hasClass("fancybox_350")) {
				$('.setNewLb_myd .setNewLb_myd_child').css('width', '390px');
			} else if ($(this).hasClass("fancybox_450")) {
				$('.setNewLb_myd .setNewLb_myd_child').css('width', '490px');
			} else if ($(this).hasClass("fancybox_550")) {
				$('.setNewLb_myd .setNewLb_myd_child').css('width', '590px');
			} else if ($(this).hasClass("fancybox_650")) {
				$('.setNewLb_myd .setNewLb_myd_child').css('width', '690px');
			} else if ($(this).hasClass("fancybox_750")) {
				$('.setNewLb_myd .setNewLb_myd_child').css('width', '790px');
			} else if ($(this).hasClass("fancybox_850")) {
				$('.setNewLb_myd .setNewLb_myd_child').css('width', '890px');
			} else if ($(this).hasClass("fancybox_950")) {
				$('.setNewLb_myd .setNewLb_myd_child').css('width', '990px');
			}

			var set_new_href = $(this).attr("href");
			$('.setNewLb_myd .setNewLb_myd_child').append('<iframe id="iframe" src="' + set_new_href + '" width="" height=""></iframe><div class="close_cutom_lb"></div>');
			$('.setNewLb_myd').addClass('active');
			// console.log(set_new_href);
			return false;
		});
		$(document).on("click", ".close_cutom_lb", function() {
			$('.setNewLb_myd').removeClass('active');
			$('.setNewLb_myd_child .close_cutom_lb, .setNewLb_myd_child iframe').remove();
		});


		// $(document).ready(function() {
		// 		$('.fancybox').fancybox();
		// });
		// // var fbtitle="Swapnil2a";	

		// $(document).ready(function() {
		// 		$('.fancybox_250').fancybox({
		// 				'autoSize'  : false,
		// 				'autoResize': false,
		// 				'autoWidth' : true,
		// 				'width'     : 250,
		// 				'autoHeight': false,
		// 				'minHeight' : 100,
		// 				'height'        : 100,
		// 		});
		// });

		// $(document).ready(function() {
		// 		$('.fancybox_350').fancybox({
		// 				'autoSize'  : false,
		// 				'autoResize': false,
		// 				'autoWidth' : true,
		// 				'width'     : 350,
		// 				'autoHeight': false,
		// 				'minHeight' : 100,
		// 				'height'        : 100,
		// 		});
		// });

		// $(document).ready(function() {
		// 		$('.fancybox_450').fancybox({
		// 				'autoSize'  : false,
		// 				'autoResize': false,
		// 				'autoWidth' : true,
		// 				'width'     : 450,
		// 				'autoHeight': false,
		// 				'minHeight' : 100,
		// 				'height'        : 100,
		// 		});
		// });

		// $(document).ready(function() {
		// 		$('.fancybox_550').fancybox({
		// 				'autoSize'  : false,
		// 				'autoResize': false,
		// 				'autoWidth' : true,
		// 				'width'     : 550,
		// 				'autoHeight': false,
		// 				'minHeight' : 100,
		// 				'height'        : 100,
		// 		});
		// });

		// $(document).ready(function() {
		// 		$('.fancybox_650').fancybox({
		// 				'autoSize'  : false,
		// 				'autoResize': false,
		// 				'autoWidth' : true,
		// 				'width'     : 650,
		// 				'autoHeight': false,
		// 				'minHeight' : 100,
		// 				'height'        : 100,
		// 		});
		// });

		// $(document).ready(function() {
		// 		$('.fancybox_750').fancybox({
		// 				'autoSize'  : false,
		// 				'autoResize': false,
		// 				'autoWidth' : true,
		// 				'width'     : 750,
		// 				'autoHeight': false,
		// 				'minHeight' : 100,
		// 				'height'        : 100,
		// 		});
		// });

		// $(document).ready(function() {
		// 		$('.fancybox_850').fancybox({
		// 				'autoSize'  : false,
		// 				'autoResize': false,
		// 				'autoWidth' : true,
		// 				'width'     : 850,
		// 				'autoHeight': false,
		// 				'minHeight' : 100,
		// 				'height'        : 100,
		// 		});
		// });

		// $(document).ready(function() {
		// 		$('.fancybox_950').fancybox({
		// 				'autoSize'  : false,
		// 				'autoResize': false,
		// 				'autoWidth' : true,
		// 				'width'     : 950,
		// 				'autoHeight': false,
		// 				'minHeight' : 100,
		// 				'height'        : 100,
		// 		});
		// });
		// $(".fancybox").fancybox({
		// 				helpers: {
		// 						title: {
		// 								type: 'outside'
		// 						}
		// 				}
		// 		});
	</script>
	<script type="text/javascript">
		$(function() {
			// $(".datepicker").datepicker({ dateFormat: "dd-mm-yy" })
			$(".datepicker").datepicker({
				dateFormat: 'dd/mm/yy'
			});
		});
	</script>
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
	</style>
	<!------------------------------------- //Fancy Box Lightbox ------------------------------------>
	<script type="text/javascript">
		$(function() {
			// $(".datepicker").datepicker({ dateFormat: "dd-mm-yy" })
			$(".datepicker").datepicker({
				dateFormat: 'dd/mm/yy'
			});
		});
	</script>
	<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
	</style>

	<!------------------------------------ News Ticker Updown Arrows -------------------------------->
	<script type="text/javascript">
		$('.widget-newsticker').newsTicker({
			max_rows: 2,
			direction: 'down',
			autostart: 0
		});
		$('.Followup-h').newsTicker({
			prevButton: $('#d1-prev-button'),
			nextButton: $('#d1-next-button')
		});
	</script>
	<!-- --------------------------------- //News Ticker Updown Arrows ------------------------------>

	<!----------------------------------------- Mytimepicker ---------------------------------------->
	<link href="./Invoice Form_files/mytimepicker.css" rel="stylesheet">
	<script src="./Invoice Form_files/mytimepicker.js.download"></script>
	<!----------------------------------------- Mytimepicker ---------------------------------------->

	<!---------------------------------------- Food Formula ----------------------------------------->

	<script>
		function formulaAddItem() {
			var index = 0;
			while ($("div[index=" + index + "]").length)
				index++;

			var item = '<div index=' + index + '><div class="col-xs-6"><div class="form-group"><input type="text" class="form-control autocomp" name="ingredients[]" autocomplete="off" index=' + index + '></div></div><div class="col-xs-2"><div class="form-group"><input type="number" class="form-control" name="qtys[]" min="0" index=' + index + ' step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><select class="form-control" name="units[]" index=' + index + '><option value="" title="Select Unit">Unit</option><option value="kg" title="Kilograms">kg</option><option value="gm" title="Grams">gm</option><option value="l" title="Litre">l</option><option value="ml" title="Millilitre">ml</option><option value="m" title="Meter">m</option><option value="tbsps" title="Table Spoon">tbsps</option><option value="teasps" title="Tea Spoon">teasps</option><option value="cup" title="Cup">cup</option><option value="pcs." title="Pieces">pcs.</option></select></div></div><div class="col-xs-1"><div class="form-group"><a href="javascript:void(0)" onclick="formulaRemoveItem(' + index + ')"><span class="fa fa-trash-o text-danger abby_denger"></span></a></div></div></div>';

			$("ingredient").append(item);
		}

		function formulaRemoveItem(index) {
			$("div[index=" + index + "]").remove();
		}

		function formulaAddItemByFi(fi) {
			var index = 0;
			while ($("div[index=" + index + "]").length)
				index++;

			var item = '<div food=' + fi + ' index=' + index + '><div class="col-xs-6"><div class="form-group"><input type="text" class="form-control autocomp" name="ingredients' + fi + '[]" autocomplete="off" food=' + fi + ' index=' + index + '></div></div><div class="col-xs-2"><div class="form-group"><input type="number" class="form-control" name="qtys' + fi + '[]" min="0" food=' + fi + ' index=' + index + ' step="0.01"></div></div><div class="col-xs-3"><div class="form-group"><select class="form-control" name="units' + fi + '[]" food=' + fi + ' index=' + index + '><option value="" title="Select Unit">Unit</option><option value="kg" title="Kilograms">kg</option><option value="gm" title="Grams">gm</option><option value="l" title="Litre">l</option><option value="ml" title="Millilitre">ml</option><option value="m" title="Meter">m</option><option value="tbsps" title="Table Spoon">tbsps</option><option value="teasps" title="Tea Spoon">teasps</option><option value="cup" title="Cup">cup</option><option value="pcs." title="Pieces">pcs.</option></select></div></div><div class="col-xs-1"><div class="form-group"><a href="javascript:void(0)" onclick="formulaRemoveItemByFi(' + fi + ',' + index + ')"><span class="fa fa-trash-o text-danger abby_denger"></span></a></div></div></div>';

			$("ingredient[food=" + fi + "]").append(item);
		}

		function formulaRemoveItemByFi(fi, index) {
			$("div[index=" + index + "][food=" + fi + "]").remove();
		}
	</script>

	<script>
		$(document).on("keyup", ".autocomp", function() {
			var nameArr = ["Sunday oil ", "Sunflower oil", "Palmolien oil", "Coconut oil", "Kolam rice", "Basmati rice ", "Basmati tukda rice", "moong daal 5", "Chana dal 10", "Tuar dal ", "Masur dal", "chole", "black chana", "Cheese ", "butter ", "Paneer", "Milk ", "Onion", "Tomato", "Methi", "palak", "Spring onion", "Ginger", "Garlic", "", "Extra Cottage", "Electricity Consumption", "400 watt Metal halogen", "LED Patta ", "LED NEW ITME", "AC", "FAN", "CHAIR", "", "Bamboo Stick", "Tarpauline", "Rope", "", ""];
			var objArr = new Array();

			objArr["Sunday oil "] = "ml";
			objArr["Sunflower oil"] = "ml";
			objArr["Palmolien oil"] = "ml";
			objArr["Coconut oil"] = "ml";
			objArr["Kolam rice"] = "kg";
			objArr["Basmati rice "] = "kg";
			objArr["Basmati tukda rice"] = "kg";
			objArr["moong daal 5"] = "kg";
			objArr["Chana dal 10"] = "kg";
			objArr["Tuar dal "] = "kg";
			objArr["Masur dal"] = "kg";
			objArr["chole"] = "kg";
			objArr["black chana"] = "kg";
			objArr["Cheese "] = "";
			objArr["butter "] = "";
			objArr["Paneer"] = "";
			objArr["Milk "] = "";
			objArr["Onion"] = "kg";
			objArr["Tomato"] = "kg";
			objArr["Methi"] = "kg";
			objArr["palak"] = "kg";
			objArr["Spring onion"] = "kg";
			objArr["Ginger"] = "kg";
			objArr["Garlic"] = "kg";
			objArr[""] = "";
			objArr["Extra Cottage"] = "pcs";
			objArr["Electricity Consumption"] = "";
			objArr["400 watt Metal halogen"] = "pcs";
			objArr["LED Patta "] = "";
			objArr["LED NEW ITME"] = "";
			objArr["AC"] = "";
			objArr["FAN"] = "";
			objArr["CHAIR"] = "";
			objArr["Bamboo Stick"] = "pcs";
			objArr["Tarpauline"] = "pcs";
			objArr["Rope"] = "pcs";

			$(this).autocomplete({
				source: nameArr,
				close: function(event, ui) {
					var unit = objArr[$(this).val()];
					var index = $(this).attr("index");
					$("select[index=" + index + "]").val(unit);
				}
			});
		});
	</script>
	<!--------------------------------------// Food Formula ----------------------------------------->
	<script>
		// filter collaps js
		$('.morefilter').click(function() {
			$('.filter-collaps').toggleClass('filter-show');
			$(this).toggleClass('filter-more-less');
		});
	</script>

	<script src="./Invoice Form_files/jquery.datetimepicker.full.js.download"></script>
	<script>
		/*jslint browser:true*/
		/*global jQuery, document*/

		jQuery(document).ready(function() {
			'use strict';

			jQuery('.date-time-picker').datetimepicker({
				format: 'd-m-Y h:i A',
				formatTime: 'h:i A',
				formatDate: 'd-m-Y',
				minDate: new Date(),
				step: 15
			});
		});
	</script>


	<div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>
</body>

</html>