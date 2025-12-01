<!DOCTYPE html>
<?php
include 'conn.php';
include 'udf.php'; ?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>GST Invoice</title>
	<link rel="stylesheet" type="text/css" href="./css/gst_invoice_style.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

	<?php
	$invoice = execute("select isgst,state,start_invoice_no,invoice_prefix,signature_label,bank_detail,account_no,branch_detail,ifsc,upi_id,gstin_no from invoice_setup where hotel='$_SESSION[hotel]'");

	$isgst = $invoice['isgst'];
	$display_gst = $isgst ? 'block' : 'none';
	$display_gst_table = $isgst ? 'revert' : 'none';


	$userDetails = execute("select u.email,u.company,u.cm_company_name,u.address1,r.roomtype,r.id,rn.roomnumber,c.city,u.contact from roomnumbers rn join roomtypes r on r.id=rn.roomtype join hotels h on h.id=r.hotel join users u on u.id=h.user join locations l on l.id=h.location join cities c on c.id=l.city where rn.id=$_GET[id]");
	$bdate = date_create($_GET['date']);
	$now = date_create(date("Y-m-d H:i"));
	$a = date_create($bdate->format("Y-m-d") . " " . '12' . ":" . '00');
	$row = execute("select b.id,b.hours,b.reg_date,u.fullname,u.id_proof,(declaredtariff-pcdiscount+hotelst+hsbc+hkcc+frotelst+fsbc+fkcc+lt+sc+stonsc)total,u.fullname,u.contact,u.email,u.address1,u.contact, b.trav_name,b.checkindatetime,b.checkoutdatetime,b.usercheckedin,b.usercheckedout from bookings b left join room_distribution rd on b.id=rd.bookingid left join users u on u.id=b.guestid where (b.paid=1 or ticker>'" . $now->format("Y-m-d H:i") . "') and (b.status='Scheduled' or b.status='Cancelled') and rd.roomnumber=$_GET[id] 
								and 
			(
			b.checkindatetime<='" . $a->format("Y-m-d H:i") . "' and b.checkoutdatetime>='" . $a->format("Y-m-d H:i") . "'
		)");
	$hotel_id = isset($_SESSION['hotel']) ? $_SESSION['hotel'] : (isset($_GET['hotel']) ? $_GET['hotel'] : null);
	$hotel_prefix = execute("SELECT invoice_prefix from invoice_setup where hotel=$hotel_id");
	$invoice_num = execute("select invoice_number,count(*)cnt from invoices where bookingid = $row[id]");

	$checkindate = new DateTime($row['checkindatetime']);
	$checkoutdate = new DateTime($row['checkoutdatetime']);
	$usercheckedin = new DateTime($row['usercheckedout']);
	?>

	<div class="main invoicev2">
		<div class="width-100 fl">
			<div class="d50 fl pad-lr-10 setvbarcd1">
				<p class="invoicetext">
					<?= $isgst ? 'Tax Invoice' : 'Bill of supply' ?> </p>
				<div class="width-100 fl">
					<p class="dated-vdt1 dated-vdt2"><?= $isgst ? 'Tax Invoice' : 'Bill of supply' ?> </p>
					<p class="dated-vdt1 dated-vdt2">: <?= '' . $hotel_prefix['invoice_prefix'] . '' . $invoice_num['invoice_number'] . ''; ?> </p>
				</div>
				<div class="width-100 fl">
					<p class="dated-vdt1 dated-vdt2">Date</p>
					<p class="dated-vdt1 dated-vdt2">: <?= $now->format('d-M-Y') ?></p>
				</div>
				<div class="width-100 fl">
					<p class="dated-vdt1 dated-vdt2">Booking Ref#</p>
					<p class="dated-vdt1 dated-vdt2">: <?= "$row[id]" ?></p>
				</div>
				<div class="width-100 fl">
					<p class="dated-vdt1 dated-vdt2">Check in Date</p>
					<p class="dated-vdt1 dated-vdt2">: <?= $checkindate->format('d-M-Y') ?></p>
				</div>
				<div class="width-100 fl">
					<p class="dated-vdt1 dated-vdt2">Check out Date</p>
					<p class="dated-vdt1 dated-vdt2">: <?= $usercheckedin->format('d-M-Y') ?></p>
				</div>
				<div class="width-100 fl">
					<p class="dated-vdt1 dated-vdt2">No of Nights</p>
					<p class="dated-vdt1 dated-vdt2">: <?= $checkindate->diff($usercheckedin)->days ?></p>
				</div>
				<div class="width-100 fl">
					<p class="dated-vdt1 dated-vdt2">Room Type</p>
					<p class="dated-vdt1 dated-vdt2">: <?= $userDetails['roomtype'] ?></p>
				</div>
			</div>
			<div class="d50 fl pad-lr-10 setvbarcd2">
				<div class="logod">
					<img src="./images/1474040IH.png" class="/*img-asc*/" style="max-width:180px; max-height:80px; float:right">
				</div>
			</div>
		</div>
		<div class="width-100 fl inv-md2 inv-pt_2 ">
			<div class="d50 fl pad-lr-10">
				<p class="ptv1small mar-bottom-6">Bill To:</p>
				<p class="ptv1big mar-bottom-6 mar-bottom-1"><b><?= $row['fullname'] ?></b></p>
				<p class="ptv1small mar-bottom-3 mar-bottom-1"><?= $row['email'] ?></p>
				<p class="ptv1small mar-bottom-3 mar-bottom-1"><?= 'Mumbai' ?></p>
				<p class="ptv1small mar-bottom-6 mar-bottom-2">Phone: <?= $row['contact'] ?></p>
			</div>
		</div>

		<div class="width-100 fl product-table2 pad-lr-10">
			<table border="1" cellpadding="5" cellspacing="0" width="100%">
				<thead>
					<tr>
						<td rowspan="2" width="6%" class="t-a-c bor_left_no bor_right_no bor_bottom_no">HSN /<br>SAC
						</td>
						<td rowspan="2" width="13%" class="t-a-c bor_right_no bor_bottom_no">Name of the Service
						</td>
						<td rowspan="2" width="8%" class="t-a-c bor_right_no bor_bottom_no">Rate
						</td>
						<td rowspan="2" width="6%" class="t-a-c bor_right_no bor_bottom_no">Qty
						</td>
						<td rowspan="2" width="8%" class="t-a-c bor_right_no bor_bottom_no">Subtotal
						</td>
						<td rowspan="2" width="12%" class="bor_right_no bor_bottom_no ">Discount
						</td>
						<?php
						if ($isgst) {
						?>
							<td rowspan="2" width="9%" class="t-a-c bor_right_no bor_bottom_no">Taxable Value
							</td>
							<td colspan="2" width="12%" class="t-a-c bor_right_no bor_bottom_no">VAT
							</td>
							<td colspan="2" width="12%" class="t-a-c bor_right_no bor_bottom_no">CGST
							</td>
							<td colspan="2" width="12%" class="t-a-c bor_right_no bor_bottom_no">SGST
							</td>
							<td colspan="2" width="12%" class="t-a-c bor_right_no bor_bottom_no">IGST
							</td>
					</tr>
					<tr>
						<td class="bor_right_no bor_bottom_no" style="width: 6%; text-align:center;">Tax %
						</td>
						<td class="bor_right_no bor_bottom_no" style="width: 6%; text-align:center;">Amt.
						</td>
						<td class="bor_right_no bor_bottom_no" style="width: 6%; text-align:center;">Tax %
						</td>
						<td class="bor_right_no bor_bottom_no" style="width: 6%; text-align:center;">Amt.
						</td>
						<td class="bor_right_no bor_bottom_no" style="width: 6%; text-align:center;">Tax %
						</td>
						<td class="bor_right_no bor_bottom_no" style="width: 6%; text-align:center;">Amt.
						</td>
						<td class="bor_right_no bor_bottom_no" style="width: 6%; text-align:center;">Tax %
						</td>
						<td class="bor_right_no bor_bottom_no" style="width: 6%; text-align:center;">Amt.
						</td>
					</tr>
				<?php
						} else {
							echo "<td rowspan='2' width='9%' class='t-a-c bor_right_no bor_bottom_no'>Total</td>";
						}
				?>

				</thead>
				<tbody>
					<tr>
						<td class="bor_left_no bor_right_no bor_bottom_no">hsn</td>
						<td class="bor_right_no bor_bottom_no">tax</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">2000</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">1</td>

						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">50,000</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">
							1,000 </td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">49,000</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">-</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">-</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">-</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">-</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">-</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">-</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">18%</td>
						<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">8,820</td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="width-100 fl pad-lr-10 set-all-vdmanagw">
			<table border="1" cellpadding="5" cellspacing="0" width="100%" style="border-top:none !important;">
				<tbody>
					<tr>
						<td width="63.5%" class="bor_top_no bor_bottom_no bor_left_no" style="padding-left: 5px !important;">Address Of Hotel :</td>

						<td class="bor_top_no bor_left_no"><b>Subtotal</b></td>
						<td class="t-a-r bor_top_no bor_left_no bor_right_no"><b id="subtotal-price">₹ 1,17,750</b>&nbsp;</td>
					</tr>
					<tr>
						<td width="63.5%" class="bor_top_no bor_bottom_no bor_left_no" style="padding-left: 5px !important;">
							<div><b><?= $userDetails['address1'] ?></b></div>
						</td>
						<td class="bor_top_no bor_left_no"><b>Discount</b></td>
						<td class="t-a-r bor_top_no bor_left_no bor_right_no"><b id="discount-price">₹ 2,000</b>&nbsp;</td>
					</tr>
					<tr>
						<td width="63.5%" class="bor_top_no bor_bottom_no bor_left_no">&nbsp;</td>
						<td class="bor_top_no  bor_left_no"><b>Taxable</b></td>
						<td class="t-a-r bor_top_no  bor_left_no bor_right_no"><b id="taxable-price">₹ 1,15,750</b>&nbsp;</td>
					</tr>
					<tr>
						<td width="63.5%" class="bor_top_no bor_bottom_no bor_left_no">&nbsp;</td>
						<td class="bor_top_no bor_left_no"><b>Total GST in Rs.</b></td>
						<td class="t-a-r bor_top_no bor_left_no bor_right_no"><b id="total-gst-price">₹ 16,507.5</b>&nbsp;</td>
					</tr>
					<!-- <tr>
						<td width="63.5%" class="bor_top_no bor_bottom_no bor_left_no">&nbsp;</td>
						<td class="bor_top_no bor_left_no"><b>Total VAT in Rs.</b></td>
						<td class="t-a-r bor_top_no bor_left_no bor_right_no"><b id="total-vat-price">₹ 0</b>&nbsp;</td>
					</tr> -->
					<tr>
						<td width="63.5%" rowspan="2" class="bor_top_no bor_bottom_no bor_left_no">&nbsp; </td>
						<td class="bor_top_no bor_left_no" style="background-color: #ebebeb;"><b>Invoice Total</b></td>
						<td class="t-a-r bor_top_no bor_left_no bor_right_no" style="background-color: #ebebeb;"><b id="total-invoice-price">₹ 1,32,257.50</b>&nbsp;</td>
					</tr>
					<tr>
						<td class="bor_top_no bor_left_no"><b>Round off</b></td>
						<td class="t-a-r bor_top_no bor_left_no bor_right_no"><b id="roundoff-price">₹ 1,32,258</b>&nbsp;</td>
					</tr>
					<tr>
						<td width="63.5%" class="bor_top_no bor_bottom_no bor_left_no" style="padding-left: 5px !important;">Invoice Value in Words :</td>
						<td class="bor_top_no bor_left_no"><b>Total Adavance Paid</b></td>
						<td class="t-a-r bor_top_no bor_left_no bor_right_no"><b id="total-advance-price">₹ 1,17,750</b>&nbsp;</td>
					</tr>
					<tr>
						<td width="63.5%" class="bor_top_no bor_bottom_no bor_left_no" style="padding-left: 5px !important;">
							<div><b id="total-paid-in-words"></b></div>
						</td>
						<td class="bor_top_no bor_left_no" style="border-bottom:none; background-color: #ebebeb;"><b>Balance Amount</b></td>
						<td class="t-a-r bor_top_no bor_left_no bor_right_no" style="border-bottom:none; background-color: #ebebeb;"><b id="balance-amount-price">₹ 14,508</b>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="14" width="63.5%" class="t-a-c bor_bottom_no" style="border-left:none; border-right:none;"><b>Certified that the particulars given above are true and correct</b></td>

					</tr>
				</tbody>
			</table>
			<div class="width-100 fl amount-boxv1 bor-bottom" style="display:none">
				<div class="am-side-vd1 border-left h-40p pad-left-2p align-vc" style="height:80px"></div>
				<div>
					<div class="am-side-vd2 border-left h-20p">
						<div class="align-vc sideblockv1 pad-left-2p h-20p"></div>
						<div class="align-vc sideblockv2 pad-left-2p h-20p">
							<p><b>Subtotal</b></p>
						</div>
						<div class="align-ac sideblockv3 pad-left-2p h-20p t-a-c">
							<p><b>₹ 1,17,750</b></p>
						</div>
					</div>
				</div>
				<div>
					<div class="am-side-vd2 border-left h-20p" style="border-top:#000000 solid 1px">
						<div class="align-vc sideblockv1 pad-left-2p h-20p"></div>
						<div class="align-vc sideblockv2 pad-left-2p h-20p">
							<p><b>Discount</b></p>
						</div>
						<div class="align-ac sideblockv3 pad-left-2p h-20p t-a-c">
							<p><b>₹ 2,000</b></p>
						</div>
					</div>
				</div>
				<div>
					<div class="am-side-vd2 border-left h-20p" style="border-top:#000000 solid 1px">
						<div class="align-vc sideblockv1 pad-left-2p h-20p"></div>
						<div class="align-vc sideblockv2 pad-left-2p h-20p">
							<p><b>Taxable</b></p>
						</div>
						<div class="align-ac sideblockv3 pad-left-2p h-20p t-a-c">
							<p><b>₹ 1,15,750</b></p>
						</div>
					</div>
				</div>

				<div>
					<div class="am-side-vd2 border-left h-20p bor-top" style="border-bottom:1px solid #000000">
						<div class="align-vc sideblockv1 pad-left-2p h-20p"></div>
						<div class="align-vc sideblockv2 pad-left-2p h-20p">
							<p><b>Total IGST in Rs.</b></p>
						</div>
						<div class="align-ac sideblockv3 pad-left-2p h-20p t-a-c">
							<p><b>₹ 16,507.5</b></p>
						</div>
					</div>
				</div>

				<div class="am-side-vd1 border-left h-40p pad-left-2p align-vc">
					<p>Invoice Value in Words :<b> <br>Rupees One Lakh Thirty Two Thousand Two Hundred Fifty Eight only</b></p>
				</div>
				<div>
					<div class="am-side-vd2 border-left h-20p" style="border-top:1px solid #000000">
						<div class="align-vc sideblockv1 pad-left-2p h-20p"></div>
						<div class="align-vc sideblockv2 pad-left-2p h-20p">
							<p><b>Invoice Total</b></p>
						</div>
						<div class="align-ac sideblockv3 pad-left-2p h-20p t-a-c">
							<p><b>₹ 1,32,257.50</b></p>
						</div>
					</div>
				</div>
				<div>
					<div class="am-side-vd2 border-left h-20p bor-top">
						<div class="align-vc sideblockv1 pad-left-2p h-20p"></div>
						<div class="align-vc sideblockv2 pad-left-2p h-20p">
							<p><b>Round off</b></p>
						</div>
						<div class="align-ac sideblockv3 pad-left-2p h-20p t-a-c">
							<p><b>₹ 1,32,258</b></p>
						</div>
					</div>
				</div>
				<div>
					<div class="am-side-vd2 border-left h-20p bor-top">
						<div class="align-vc sideblockv1 pad-left-2p h-20p"></div>
						<div class="align-vc sideblockv2 pad-left-2p h-20p">
							<p><b>Total Adavance Paid</b></p>
						</div>
						<div class="align-ac sideblockv3 pad-left-2p h-20p t-a-c">
							<p><b>₹ 1,32,258</b></p>
						</div>
					</div>
				</div>
				<div>
					<div class="am-side-vd2 border-left h-20p bor-top">
						<div class="align-vc sideblockv1 pad-left-2p h-20p"></div>
						<div class="align-vc sideblockv2 pad-left-2p h-20p">
							<p><b>Balance Amount</b></p>
						</div>
						<div class="align-ac sideblockv3 pad-left-2p h-20p t-a-c">
							<p><b>₹ 1,32,258</b></p>
						</div>
					</div>
				</div>
			</div>
		</div>


		<script>
			$(document).ready(function() {
				let apiUrl = `bookingajax.php?bookingid=<?= $row['id'] ?>`;

				$.getJSON(apiUrl, function(data) {

					let rowData = data.rowData;
					console.log(rowData);

					let subtotal = 0

					let tableBody = $('.product-table2 tbody');

					tableBody.empty();

					rowData.forEach(function(row) {
						let tableRow = `
							<tr>
								<td class="bor_left_no bor_right_no bor_bottom_no">${row.hsn}</td>
								<td class="bor_right_no bor_bottom_no">${row.description}</td>
								<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">${row.rate}</td>
								<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">${row.qty}</td>
								<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">${row.subtot}</td>
								<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">${row.discount}</td>
								<td class="t-a-c t-a-r space bor_right_no bor_bottom_no">${row.taxable}</td>
								<td style="display: <?= $display_gst_table ?>;" class="t-a-c t-a-r space bor_right_no bor_bottom_no">${row.slab}</td>
								<td style="display: <?= $display_gst_table ?>;" class="t-a-c t-a-r space bor_right_no bor_bottom_no">${row.isvat?row.vat:'-'}</td>
								<td style="display: <?= $display_gst_table ?>;" class="t-a-c t-a-r space bor_right_no bor_bottom_no">${(row.isvat || row.isigst) ?'-':`${row.slab/2}%`}</td>
								<td style="display: <?= $display_gst_table ?>;" class="t-a-c t-a-r space bor_right_no bor_bottom_no">${(row.vat || row.isigst)?'-':row.cgst}</td>
								<td style="display: <?= $display_gst_table ?>;" class="t-a-c t-a-r space bor_right_no bor_bottom_no">${(row.isvat || row.isigst) ?'-':`${row.slab/2}%`}</td>
								<td style="display: <?= $display_gst_table ?>;" class="t-a-c t-a-r space bor_right_no bor_bottom_no">${(row.vat || row.isigst)?'-':row.sgst}</td>
								<td style="display: <?= $display_gst_table ?>;" class="t-a-c t-a-r space bor_right_no bor_bottom_no">${(row.isvat || !row.isigst)?'-':`${row.slab}%`}</td>
								<td  style="display: <?= $display_gst_table ?>;" class="t-a-c t-a-r space bor_right_no bor_bottom_no">${(row.isvat || !row.isigst)?'-':row.igst}</td>
							</tr>
						`;
						subtotal = subtotal + Number(row.subtot)
						tableBody.append(tableRow);
					});

					let summarydata = data.summatyData;
					console.log(summarydata);

					document.getElementById('subtotal-price').innerText = `₹ ${subtotal}`;
					document.getElementById('discount-price').innerText = `₹ ${summarydata.totalDiscount}`;
					document.getElementById('taxable-price').innerText = `₹ ${summarydata.subtotal }`;
					if(summarydata.totalGST){
					document.getElementById('total-gst-price').innerText = `₹ ${summarydata.totalGST}`;
					}
					if(summarydata.totalVAT){
					document.getElementById('total-vat-price').innerText = `₹ ${summarydata.totalVAT}`;
					}
					document.getElementById('total-invoice-price').innerText = `₹ ${summarydata.grandTotal}`;
					document.getElementById('roundoff-price').innerText = `₹ ${summarydata.roundOff}`;
					document.getElementById('total-advance-price').innerText = `₹ ${summarydata.totalPaid}`;
					document.getElementById('balance-amount-price').innerText = `₹ ${summarydata.balanceAmount}`;
					document.getElementById('total-paid-in-words').innerText = `${word} Only`;


				});
			});

			function numberToWords(numStr) {
				const ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
				const teens = ["", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
				const tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
				const hundreds = "Hundred";
				const bigUnits = ["", "Thousand", "Million", "Billion"];

				let num = parseInt(numStr);

				if (isNaN(num) || num <= 0 || num > 999999999) {
					return "";
				}

				function convertHundreds(n) {
					let result = "";

					// Hundreds place
					if (Math.floor(n / 100) > 0) {
						result += ones[Math.floor(n / 100)] + " " + hundreds;
						n %= 100;
						if (n > 0) result += " ";
					}

					// Tens place
					if (n >= 11 && n <= 19) {
						result += teens[n - 10];
					} else {
						if (Math.floor(n / 10) > 0) {
							result += tens[Math.floor(n / 10)];
							n %= 10;
							if (n > 0) result += "-";
						}

						// Ones place
						if (n > 0) {
							result += ones[n];
						}
					}

					return result.trim();
				}

				function convertBigNumber(num) {
					let result = "";
					let partCount = 0;

					while (num > 0) {
						const part = num % 1000;

						if (part > 0) {
							const partWords = convertHundreds(part) + (bigUnits[partCount] ? " " + bigUnits[partCount] : "");
							result = partWords + (result ? " " + result : "");
						}

						num = Math.floor(num / 1000);
						partCount++;
					}

					return result;
				}

				return convertBigNumber(num);
			}
		</script>

		<div class="pad-lr-10 pease-note bor-top-0">
			<div class="width-100 fl signature-md bor-top-0">
				<div class="footersing before_footersing border-right pad-lr-10 align-vc">
					<!-- <div class="barcode-imgd">
						<img src="https://www.banqueteasy.com//erp/templates/b2c_gst_invoices/default_files/barcode.png" class="img-asc">
					</div> -->

					<?php
					$invoiesetup = execute("select invs.start_invoice_no,invs.invoice_prefix,invs.signature_label,invs.bank_detail,invs.account_no,invs.branch_detail,invs.ifsc,invs.upi_id,invs.gstin_no from invoice_setup invs where hotel='$_SESSION[hotel]'");
					?>
					<div class="width-100 fl">
						<div class="width-100 fl">
							<p class="dated-vdt1 dated-vdt2 v2">COMPANY: <?= $userDetails['company'] ?></p>
						</div>
						<div class="width-100 fl" style="display: <?= $display_gst ?>;">
							<p class="dated-vdt1 dated-vdt2 v2">GST: <?= $invoiesetup['gstin_no'] ?></p>
						</div>
						<div class="width-100 fl">
							<p class="dated-vdt1 dated-vdt2 v2">State : Maharashtra</p>
						</div>
						<div class="width-100 fl">
							<p class="dated-vdt1 dated-vdt2 v2">State code : 27</p>
						</div>
						<div class="width-100 fl">
							<p class="dated-vdt1 dated-vdt2 v2">Bank: <?= $invoiesetup['bank_detail'] ?></p>
						</div>
						<div class="width-100 fl">
							<p class="dated-vdt1 dated-vdt2 v2">A/C No.: <?= $invoiesetup['account_no'] ?></p>
						</div>
						<div class="width-100 fl">
							<p class="dated-vdt1 dated-vdt2 v2">Branch: <?= $invoiesetup['branch_detail'] ?></p>
						</div>
						<div class="width-100 fl">
							<p class="dated-vdt1 dated-vdt2 v2">IFSC <?= $invoiesetup['ifsc'] ?></p>
						</div>
						<div class="width-100 fl">
							<p class="dated-vdt1 dated-vdt2 v2">UPI: <?= $invoiesetup['upi_id'] ?></p>
						</div>
					</div>
				</div>
				<div class="footersing before_footersing pad-lr-10 t-align-right">
					<p><?= $userDetails['company'] ?></p>
					<p class="signt1 signt2"><?= $invoiesetup['signature_label'] ?></p>
				</div>
			</div>
		</div>
		<div class="width-100 fl pad-lr-10 lastlinev1" style="font-size:10px; text-align:left">
			<ul>
				<li style="text-align:justify">
					<p>Please contact the Hotel at least one month before your event to review and confirm the details for your event, including <span class="mytool"><a href="https://setupmyhotel.com/homepage/hotel-management-glossary/menu.html" title="The list of dishes on offer in a restaurant; those ready prepared are on the table d’h6te side, those cooked to order, on the a la carte side.">menus</a></span>, <span class="mytool"><a href="https://setupmyhotel.com/homepage/hotel-management-glossary/decoration.html" title="The Decoration department is responsible for decorating the place on various occasions, on special days, and holidays; including the decoration on the state, and conference room. Some hotels may combine this section to the Florist or the Banquet.">decorations</a></span>, entertainment and beverage service. Upon review of your event requirements, <span class="mytool"><a href="https://setupmyhotel.com/homepage/hotel-management-glossary/banquet.html" title="Formal meal for a number of persons, all seated and served with the same meal at the same time.">Banquet</a></span>
						Event Orders - BEO will be sent to you to confirm all final
						arrangements and prices. These BEO’s must be signed and returned prior
						to the event and will serve as a part of this agreement.</p>
				</li>
				<li style="text-align:justify">
					<p>The
						emergency exits/fire exits of the hall should not be blocked for safety
						reasons. However, anybody/anything obstructing the exits; the hotel
						management will have the authority to forcibly clear these obstructions
						for the safety. At least two emergency exits with signboard are to be
						kept.</p>
				</li>
			</ul>
		</div>
		<!-- <p class="text-center vnc-md">RTGS to Artisans Rose, Axis Bank, Ashok Nagar Branch Mumbai, A/c No. 915020040690189, IFSC code: UTIB0001532, Swift Code: AXISINBB018</p> -->
		<p class="text-center vnc-md"><b>Thank you for your business!</b></p>
		<div class="pad-lr-10 width-100 fl">
			<div class="lasyd"></div>
			<p class="text-center vnc-md"><?= $userDetails['city'] ?></p>
			<p class="text-center vnc-md pad-top0">Tel: <?= $userDetails['contact'] ?> Email: <?= $userDetails['email'] ?></p>
		</div>
	</div>

	<!-- <div style="width:800px; float:left;">
		<button type="button" onClick="this.style.display=&#39;none&#39;; print(); this.style.display=&#39;block&#39;">Print</button>
	</div> -->
	<style type="text/css">
		button {
			width: 80px;
			height: 35px;
			float: left;
			background: #3BAFDA;
			color: #FFFFFF;
			border: 0px;
			border-radius: 3px;
			cursor: pointer;
			margin: 10px auto;
			outline-style: none
		}
	</style>
</body>
<script>
	window.onload = function() {
		window.print();
	};
</script>

</html>