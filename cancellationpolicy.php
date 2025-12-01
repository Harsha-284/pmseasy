<?php include 'conn.php';
include 'udf.php';

if(!isset($_SESSION['groupid']))
{?>	
	<script type="text/javascript">
		window.parent.location="login.php";
	</script>
	<?php
}
else if($_SESSION['groupid'] == 0)
{?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="description" content="">
			<meta name="keywords" content="">
			<meta name="author" content="">
			<title>Booking Cancellation Policy</title>
			
			<!-- bootstrap css (required all page)-->
			<link href="css/bootstrap.min.css" rel="stylesheet">
			
			<!-- plugins css -->
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
			
			<!-- main css (required all page)-->
			<link href="css/font-awesome.min.css" rel="stylesheet">
			<link href="css/style.css" rel="stylesheet">
			<link href="css/style-responsive.css" rel="stylesheet">
			
			<!-- html5 shim and respond.js ie8 support of html5 elements and media queries -->
			<!--[if lt ie 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
			<![endif]-->
		</head>
	 
		<body style="padding-top:0px;">
			<div class="the-box">
				<?php alertbox($clrclass,$msg);$msg="";
				
				$row = execute("select u.company,l.location,h.hotelcheckintime,h.hotelcheckouttime,h.marriedcouplesallowed,h.unmarriedcouplesallowed, h.posercancellationrefund,h.cancellationpriorto24hours,h.cancellationlessthan24hours,h.p821a,h.p821b,h.p822a,h.p822b,h.guestsofguest, h.petsallowed from hotels h join users u on u.id=h.user join locations l on l.id=h.location where h.id=(select hotelid from bookings b join view_roomtypes vr on vr.id=b.roomtype where b.id=$_GET[id])");?>
				<h4 class="small-title">BOOKING CANCELLATION POLICY</h4>
				<div class="row">
					<div class="col-xs-12">
						<table border=0 cellpadding=0 cellspacing=0 width=100% style='border-collapse:
						 collapse;table-layout:fixed;width:635pt'>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td colspan=11 class=xl88>Policies &amp; Understandings Agreement between Frotels Pvt. Ltd. &amp; the Hotel</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=19 style='mso-height-source:userset;height:14.45pt'>
								<td class=xl70>&nbsp;</td>
								<td colspan=11 rowspan=4 class=xl85 width=685 style='width:519pt'>This Policy Understanding Agreement (hereinafter referred to as 'Agreement') is between <font class="font8">Frotels.com</font><font class="font0">, For
								Frotels Private Limited, incorporate under the Companies Act, 2013 having its Registered Office at 2/B/208, Queens Park, Parnaka, Vasai (West): 401 201 (hereinafter referred to as 'Portal', which expression shall unless
								repugnant to the context herein, include its successors and permitted assigns);<br>
								<br>
								</font></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>& you i.e.</td>
								<td colspan=9 class=xl73 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td height=21 style='height:15.75pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td colspan=2 class=xl86>Hotel Name</td>
								<td class=xl81></td>
								<td class=xl71><input type="text" name="hotelname" style="width:270px" value="<?=$row['company']?>" disabled></td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td colspan=2>Located At</td>
								<td class=xl81></td>
								<td class=xl71><input type="text" name="locationname" style="width:270px" value="<?=$row['location']?>" disabled></td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=23 style='mso-height-source:userset;height:17.45pt'>
								<td class=xl70>&nbsp;</td>
								<td colspan=11 rowspan=2 class=xl91 width=685 style='width:519pt'>Subscribing electronically to this service with his/its details captured separately. (Portal and the Hotel are hereinafter individually called 'Party' and collectively, 'the Parties')</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=19 style='mso-height-source:userset;height:14.45pt'>
								<td class=xl70>&nbsp;</td>
								<td colspan=11 rowspan=4 class=xl85 width=685 style='width:519pt'>The following terms and conditions constitute definitive agreement between Hotel and the Portal. By clicking the 'Accept' or similar option, the Hotel agrees
								to terms of this Agreement along with the terms of use and privacy policy available on the Website. This Agreement shall be read along with the aforesaid and in case of any inconsistency; the terms and conditions of this
								Agreement shall prevail.</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td colspan=11 class=xl92>Hotel &/ Frotel acknowledges, understands and accepts</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=19 style='mso-height-source:userset;height:14.45pt'>
								<td class=xl70>&nbsp;</td>
								<td rowspan=2 class=xl94>1</td>
								<td colspan=10 rowspan=2 class=xl93 width=623 style='width:472pt'>That Portal has given them two options/sections under which their property will get listed namely <font class="font7">Frotels &amp;/ Hotels. And Hotel is free to opt either or both the options, to maximize their inventory utilization.</font></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 class=xl74 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>2</td>
								<td colspan=10 class=xl80>Check in & Check out Time:</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>2.1</td>
								<td colspan=10 class=xl92>For Frotels Section (Hotels by Hours):</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 rowspan=3 class=xl85 width=623 style='width:472pt'>That Portal will sell hotel room/ inventory listed with them on hourly basis under its Frotels section on their website/App. And also that Hotel agrees to allow Flexible Check-In & Check out Timing for the bookings captured through Frotels.com to their guests under Frotels section.</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>2.2</td>
								<td colspan=10 class=xl92>For Hotels Section: For regular Hotel bookings</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=2 class=xl86>Check In Time</td>
								<td colspan=2 class=xl81 style='border-right:1.0pt solid black'>
									<input type="text" name="checkintime" class="timepicker" value="<?=$row['hotelcheckintime']?>" style="width:70px">
								</td>
								<td class=xl70 style='border-left:none'>&nbsp;</td>
								<td colspan=2 class=xl86>Check Out Time</td>
								<td colspan=2 class=xl81>
									<input type="text" name="checkouttime" class="timepicker" value="<?=$row['hotelcheckouttime']?>" style="width:70px">
								</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>3</td>
								<td colspan=10 rowspan=2 class=xl93 width=623 style='width:472pt'>That portal has defined <font class="font7">Day hours as 9 am to 7 pm & Night hours as 7 pm to 9 am</font><font class="font6"> so that hotel may set different
								rates for day use & night use for booking purpose in Extranet.</font></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>4</td>
								<td colspan=6 class=xl86>Does Hotel allow Married Couples with valid ID proof?</td>
								<td>
									<select disabled name="marriedcouplesallowed">
										<option value="">Select</option>
										<option value="1" <?=selected($row['marriedcouplesallowed'],"1")?>>Yes</option>
										<option value="0" <?=selected($row['marriedcouplesallowed'],"0")?>>No</option>
									</select>
								</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td height=21 style='height:15.75pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>5</td>
								<td colspan=6 class=xl86>Does Hotel allow Unmarried Couples with valid ID proof ?</td>
								<td></td>
								<td>
									<select disabled name="unmarriedcouplesallowed">
										<option value="">Select</option>
										<option value="1" <?=selected($row['unmarriedcouplesallowed'],"1")?>>Yes</option>
										<option value="0" <?=selected($row['unmarriedcouplesallowed'],"0")?>>No</option>
									</select>
								</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td height=21 style='height:15.75pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=8 style='mso-ignore:colspan'></td>
								<td align=left valign=top>
									<span style='mso-ignore:vglayout2'>
										<table cellpadding=0 cellspacing=0>
											<tr>
												<td height=21 width=62 style='height:15.75pt;width:47pt'></td>
											</tr>
										</table>
									</span>
								</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>6</td>
								<td colspan=7 class=xl86>Does hotel allow the guest/s from the same city as the hotel itself ?</td>
								<td>
									<select disabled name="guestsofguestallowed">
										<option value="">Select</option>
										<option value="1" <?=selected($row['guestsofguest'],"1")?>>Yes</option>
										<option value="0" <?=selected($row['guestsofguest'],"0")?>>No</option>
									</select>
								</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=19 style='mso-height-source:userset;height:14.45pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>7</td>
								<td colspan=10 height=19 width=623 style='height:14.45pt;width:472pt' align=left valign=top>
									<table cellpadding=0 cellspacing=0>
										<tr>
											<td colspan=10 height=19 class=xl85 width=623 style='height:14.45pt;
											width:472pt'>For the guests posing as a 'Married Couple' but fail to produce valid id proof during check-in</td>
										</tr>
									</table>
								</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=6 style='mso-ignore:colspan'>Booking will get cancelled and guest will get refund of</td>
								<td></td>
								<td>
									<select disabled name="posercancellationrefund">
										<option value="">Select</option>
										<option value="0" <?=selected($row['posercancellationrefund'],"0")?>>0%</option>
										<option value="10" <?=selected($row['posercancellationrefund'],"10")?>>10%</option>
										<option value="20" <?=selected($row['posercancellationrefund'],"20")?>>20%</option>
										<option value="30" <?=selected($row['posercancellationrefund'],"30")?>>30%</option>
										<option value="40" <?=selected($row['posercancellationrefund'],"40")?>>40%</option>
										<option value="50" <?=selected($row['posercancellationrefund'],"50")?>>50%</option>
										<option value="60" <?=selected($row['posercancellationrefund'],"60")?>>60%</option>
										<option value="70" <?=selected($row['posercancellationrefund'],"70")?>>70%</option>
										<option value="80" <?=selected($row['posercancellationrefund'],"80")?>>80%</option>
										<option value="90" <?=selected($row['posercancellationrefund'],"90")?>>90%</option>
										<option value="100" <?=selected($row['posercancellationrefund'],"100")?>>100%</option>
									</select>
								</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>8</td>
								<td colspan=10 class=xl96>Cancellation Policy</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>8.1</td>
								<td class=xl80>Day Use</td>
								<td colspan=7 class=xl80 style='mso-ignore:colspan'></td>
								<td align=left valign=top>
									<table cellpadding=0 cellspacing=0>
										<tr>
											<td height=21 class=xl80 width=62 style='height:15.75pt;width:47pt'></td>
										</tr>
									</table>
								</td>
								<td class=xl80></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=5 style='mso-ignore:colspan'>Cancellations prior to 24 hours will get refund of</td>
								<td colspan=2 style='mso-ignore:colspan'></td>
								<td>
									<select disabled name="cancellationpriorto24hours">
										<option value="">Select</option>
										<option value="0" <?=selected($row['cancellationpriorto24hours'],"0")?>>0%</option>
										<option value="10" <?=selected($row['cancellationpriorto24hours'],"10")?>>10%</option>
										<option value="20" <?=selected($row['cancellationpriorto24hours'],"20")?>>20%</option>
										<option value="30" <?=selected($row['cancellationpriorto24hours'],"30")?>>30%</option>
										<option value="40" <?=selected($row['cancellationpriorto24hours'],"40")?>>40%</option>
										<option value="50" <?=selected($row['cancellationpriorto24hours'],"50")?>>50%</option>
										<option value="60" <?=selected($row['cancellationpriorto24hours'],"60")?>>60%</option>
										<option value="70" <?=selected($row['cancellationpriorto24hours'],"70")?>>70%</option>
										<option value="80" <?=selected($row['cancellationpriorto24hours'],"80")?>>80%</option>
										<option value="90" <?=selected($row['cancellationpriorto24hours'],"90")?>>90%</option>
										<option value="100" <?=selected($row['cancellationpriorto24hours'],"100")?>>100%</option>
									</select>
								</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=7 style='mso-ignore:colspan'>Cancellations less than 24 hours
								until check- In time, will get refund of</td>
								<td>
									<select disabled name="cancellationlessthan24hours">
										<option value="">Select</option>
										<option value="0" <?=selected($row['cancellationlessthan24hours'],"0")?>>0%</option>
										<option value="10" <?=selected($row['cancellationlessthan24hours'],"10")?>>10%</option>
										<option value="20" <?=selected($row['cancellationlessthan24hours'],"20")?>>20%</option>
										<option value="30" <?=selected($row['cancellationlessthan24hours'],"30")?>>30%</option>
										<option value="40" <?=selected($row['cancellationlessthan24hours'],"40")?>>40%</option>
										<option value="50" <?=selected($row['cancellationlessthan24hours'],"50")?>>50%</option>
										<option value="60" <?=selected($row['cancellationlessthan24hours'],"60")?>>60%</option>
										<option value="70" <?=selected($row['cancellationlessthan24hours'],"70")?>>70%</option>
										<option value="80" <?=selected($row['cancellationlessthan24hours'],"80")?>>80%</option>
										<option value="90" <?=selected($row['cancellationlessthan24hours'],"90")?>>90%</option>
										<option value="100" <?=selected($row['cancellationlessthan24hours'],"100")?>>100%</option>
									</select>
								</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>8.2</td>
								<td colspan=2 style='mso-ignore:colspan'>Multiple Day Stay</td>
								<td colspan=8 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>8.2.1</td>
								<td colspan=4 style='mso-ignore:colspan'>Cancellations after check- in / no show</td>
								<td colspan=4 style='mso-ignore:colspan'></td>
								<td align=left valign=top>
									<table cellpadding=0 cellspacing=0>
										<tr>
											<td height=21 width=62 style='height:15.75pt;width:47pt'></td>
										</tr>
									</table>
								</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl75>a.</td>
								<td colspan=5 style='mso-ignore:colspan'>Booking will get cancelled & will get refund of</td>
								<td></td>
								<td>
									<select disabled name="p821a">
										<option value="">Select</option>
										<option value="0" <?=selected($row['p821a'],"0")?>>0%</option>
										<option value="10" <?=selected($row['p821a'],"10")?>>10%</option>
										<option value="20" <?=selected($row['p821a'],"20")?>>20%</option>
										<option value="30" <?=selected($row['p821a'],"30")?>>30%</option>
										<option value="40" <?=selected($row['p821a'],"40")?>>40%</option>
										<option value="50" <?=selected($row['p821a'],"50")?>>50%</option>
										<option value="60" <?=selected($row['p821a'],"60")?>>60%</option>
										<option value="70" <?=selected($row['p821a'],"70")?>>70%</option>
										<option value="80" <?=selected($row['p821a'],"80")?>>80%</option>
										<option value="90" <?=selected($row['p821a'],"90")?>>90%</option>
										<option value="100" <?=selected($row['p821a'],"100")?>>100%</option>
									</select>
								</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl75></td>
								<td colspan=2 style='mso-ignore:colspan'></td>
								<td>OR</td>
								<td colspan=3 style='mso-ignore:colspan'></td>
								<td class=xl81 style='border-top:none'>&nbsp;</td>
								<td align=left valign=top>
									<table cellpadding=0 cellspacing=0>
										<tr>
											<td height=21 class=xl82 width=62 style='height:15.75pt;border-top:none; width:47pt'>&nbsp;</td>
										</tr>
									</table>
								</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl75>b.</td>
								<td colspan=6 style='mso-ignore:colspan'>Booking will get cancelled, will get refund of (days)</td>
								<td>
									<select disabled name="p821b">
										<option value="">Select</option>
										<option value="0" <?=selected($row['p821b'],"0")?>>0%</option>
										<option value="10" <?=selected($row['p821b'],"10")?>>10%</option>
										<option value="20" <?=selected($row['p821b'],"20")?>>20%</option>
										<option value="30" <?=selected($row['p821b'],"30")?>>30%</option>
										<option value="40" <?=selected($row['p821b'],"40")?>>40%</option>
										<option value="50" <?=selected($row['p821b'],"50")?>>50%</option>
										<option value="60" <?=selected($row['p821b'],"60")?>>60%</option>
										<option value="70" <?=selected($row['p821b'],"70")?>>70%</option>
										<option value="80" <?=selected($row['p821b'],"80")?>>80%</option>
										<option value="90" <?=selected($row['p821b'],"90")?>>90%</option>
										<option value="100" <?=selected($row['p821b'],"100")?>>100%</option>
									</select>
								</td>
								<td colspan=2>Day / Days</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl75></td>
								<td colspan=9 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>8.2.2</td>
								<td colspan=4 class=xl80>To shorten the stay</td>
								<td colspan=6 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl75>a.</td>
								<td colspan=5 style='mso-ignore:colspan'>Booking will get cancelled & will get refund of</td>
								<td></td>
								<td>
									<select disabled name="p822a">
										<option value="">Select</option>
										<option value="0" <?=selected($row['p822a'],"0")?>>0%</option>
										<option value="10" <?=selected($row['p822a'],"10")?>>10%</option>
										<option value="20" <?=selected($row['p822a'],"20")?>>20%</option>
										<option value="30" <?=selected($row['p822a'],"30")?>>30%</option>
										<option value="40" <?=selected($row['p822a'],"40")?>>40%</option>
										<option value="50" <?=selected($row['p822a'],"50")?>>50%</option>
										<option value="60" <?=selected($row['p822a'],"60")?>>60%</option>
										<option value="70" <?=selected($row['p822a'],"70")?>>70%</option>
										<option value="80" <?=selected($row['p822a'],"80")?>>80%</option>
										<option value="90" <?=selected($row['p822a'],"90")?>>90%</option>
										<option value="100" <?=selected($row['p822a'],"100")?>>100%</option>
									</select>
								</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl75></td>
								<td colspan=2 style='mso-ignore:colspan'></td>
								<td>OR</td>
								<td colspan=3 style='mso-ignore:colspan'></td>
								<td class=xl81 style='border-top:none'>&nbsp;</td>
								<td align=left valign=top>
									<table cellpadding=0 cellspacing=0>
										<tr>
											<td height=21 class=xl82 width=62 style='height:15.75pt;border-top:none;width:47pt'>&nbsp;</td>
										</tr>
									</table>
								</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td class=xl75>b.</td>
								<td colspan=6 style='mso-ignore:colspan'>Booking will get cancelled, will get refund of (days)</td>
								<td>
									<select disabled name="p822b">
										<option value="">Select</option>
										<option value="0" <?=selected($row['p822b'],"0")?>>0%</option>
										<option value="10" <?=selected($row['p822b'],"10")?>>10%</option>
										<option value="20" <?=selected($row['p822b'],"20")?>>20%</option>
										<option value="30" <?=selected($row['p822b'],"30")?>>30%</option>
										<option value="40" <?=selected($row['p822b'],"40")?>>40%</option>
										<option value="50" <?=selected($row['p822b'],"50")?>>50%</option>
										<option value="60" <?=selected($row['p822b'],"60")?>>60%</option>
										<option value="70" <?=selected($row['p822b'],"70")?>>70%</option>
										<option value="80" <?=selected($row['p822b'],"80")?>>80%</option>
										<option value="90" <?=selected($row['p822b'],"90")?>>90%</option>
										<option value="100" <?=selected($row['p822b'],"100")?>>100%</option>
									</select>
								</td>
								<td colspan=2>Day /	Days</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td height=21 style='height:15.75pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=8 style='mso-ignore:colspan'></td>
								<td align=left valign=top>
									<table cellpadding=0 cellspacing=0>
										<tr>
											<td height=21 width=62 style='height:15.75pt;width:47pt'></td>
										</tr>
									</table>
								</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>9</td>
								<td colspan=5 style='mso-ignore:colspan'>Guest of the guest allowed in the room?</td>
								<td colspan=2 style='mso-ignore:colspan'></td>
								<td>
									<select disabled name="guestsofguest">
										<option value="">Select</option>
										<option value="1" <?=selected($row['guestsofguest'],"1")?>>Yes</option>
										<option value="0" <?=selected($row['guestsofguest'],"0")?>>No</option>
									</select>
								</td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td height=21 style='height:15.75pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=21 style='height:15.75pt'>
								<td class=xl70>&nbsp;</td>
								<td class=xl65>10</td>
								<td colspan=3 style='mso-ignore:colspan'>Pets allowed in the Hotel?</td>
								<td colspan=4 style='mso-ignore:colspan'></td>
								<td>
									<select disabled name="petsallowed">
										<option value="">Select</option>
										<option value="1" <?=selected($row['petsallowed'],"1")?>>Yes</option>
										<option value="0" <?=selected($row['petsallowed'],"0")?>>No</option>
									</select>
								</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td rowspan=4 class=xl94>11</td>
								<td colspan=9 rowspan=4 class=xl85 width=561 style='width:425pt'>That portal is not responsible for any unacceptable behavior of the guest/s at the hotel & vice versa. As portal is just a medium for online bookings & holds	no responsibility towards any disputes between the guests & hotels caused due to quality of the services / facilities / amenities or for any other reason.</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td height=20 style='height:15.0pt'></td>
								<td class=xl70>&nbsp;</td>
								<td class=xl65></td>
								<td colspan=10 style='mso-ignore:colspan'></td>
								<td class=xl71>&nbsp;</td>
							</tr>
							<tr height=20 style='height:15.0pt'>
								<td class=xl70>&nbsp;</td>
								<td rowspan=3 class=xl94>12</td>
								<td colspan=9 rowspan=3 class=xl85 width=561 style='width:425pt'>It is understood by Hotel that Portal has given them the option of not reflecting their inventory on the portal if Hotel inventory is 100% booked. It means if
								Hotel's inventory is reflecting on Frotels.com, Hotel needs to honor the booking sent by the portal.</td>
								<td></td>
								<td class=xl71>&nbsp;</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
				
			<js>
				<script src="js/jquery.min.js"></script>
				<script src="js/bootstrap.min.js"></script>
				<script src="js/retina.min.js"></script>
				<script src="js/jquery.nicescroll.js"></script>
				<script src="js/jquery.slimscroll.min.js"></script>
				<script src="js/jquery.backstretch.min.js"></script>
				
				<!-- plugins -->
				<script src="js/skycons.js"></script>
				<script src="js/prettify.js"></script>
				<script src="js/jquery.magnific-popup.min.js"></script>
				<script src="js/owl.carousel.min.js"></script>
				<script src="js/chosen.jquery.min.js"></script>
				<script src="js/icheck.min.js"></script>
				<script src="js/bootstrap-datepicker.js"></script>
				<script src="js/bootstrap-timepicker.js"></script>
				<script src="js/jquery.mask.min.js"></script>
				<script src="js/bootstrapValidator.min.js"></script>
				<script src="js/jquery.dataTables.min.js"></script>
				<script src="js/bootstrap.datatable.js"></script>
				<script src="js/summernote.min.js"></script>
				<script src="js/markdown.js"></script>
				<script src="js/to-markdown.js"></script>
				<script src="js/bootstrap-markdown.js"></script>
				<script src="js/bootstrap-slider.js"></script>
				<script src="js/salvattore.min.js"></script>
				<script src="js/toastr.js"></script>
				
				<!-- full calendar js -->
				<script src="js/jquery-ui.custom.min.js"></script>
				<script src="js/fullcalendar.min.js"></script>
				<script src="js/full-calendar.js"></script>
				
				<!-- easy pie chart js -->
				<script src="js/easypiechart.min.js"></script>
				<script src="js/jquery.easypiechart.min.js"></script>
				
				<!-- knob js -->
				<!--[if ie]>
				<script type="text/javascript" src="js/excanvas.js"></script>
				<![endif]-->
				<script src="js/jquery.knob.js"></script>
				<script src="js/knob.js"></script>
				
				<!-- flot chart js -->
				<script src="js/jquery.flot.js"></script>
				<script src="js/jquery.flot.tooltip.js"></script>
				<script src="js/jquery.flot.resize.js"></script>
				<script src="js/jquery.flot.selection.js"></script>
				<script src="js/jquery.flot.stack.js"></script>
				<script src="js/jquery.flot.time.js"></script>
				
				<!-- morris js -->
				<script src="js/raphael.min.js"></script>
				<script src="js/morris.min.js"></script>
				
				<!-- c3 js -->
				<script src="js/d3.v3.min.js" charset="utf-8"></script>
				<script src="js/c3.min.js"></script>
				
				<!-- main apps js -->
				<script src="js/apps.js"></script>
				<script src="js/demo-panel.js"></script>
			</js>
		</body>
	</html>
	<?php
}
ob_end_flush();?>