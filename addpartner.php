<?php include 'conn.php';
include 'udf.php';

$msg = "";
if(isset($_POST['email']))
{
	$email = $_POST['email'];
	$result = $conn->query("select count(*)cnt from users where email='$email'");
	$row = $result->fetch_assoc();
	
	if($row['cnt'] == 0)
	{
		$pwd		= new Crypter('awssecret','26457381');
		$partnertype= $_POST['partnertype'];
		$fullname	= $_POST['fullname'];
		$password	= $_POST['password'];
		$company	= $_POST['company'];
		$contact	= $_POST['contact'];
		$city		= $_POST['city'];
		$hotel		= $_POST['hotel'];
		
		$emailkey	= mt_rand(100000,10000000);
		$otp		= mt_rand(100000,999999);
		
		if($partnertype == "Hotel")
		{
			$uid = insert("insert into users (groupid,fullname,password,company,email,contact,emailkey,otp,city,reg_date) values (2,'$fullname','$password','$company', '$email','$contact','$emailkey','$otp',$city,now())");
			$hotelid = insert("insert into hotels (admin,user) values (0,$uid)");
		}
		else
		{
			$uid = insert("insert into users (groupid,fullname,password,company,email,contact,emailkey,otp,city,reg_date) values (1,'$fullname','$password','$company', '$email','$contact','$emailkey','$otp',$city,now())");
		}
				
		$msg = "Thanks for registering as a partner with us, in order to add your hotel details please verify your email ID.<br><br>We have sent the activation link to your registered email address.";
		
		//$validationlink = "http://localhost:81/frotels/login.php?id=".$hotelid."&key=".$emailkey;
		$data = '';
		
		//myemail($email,"Justfreshenup Email verifivation",$data);
		//mysms($contact,"Dear Partner, Welcome aboard justfreshenup.com ! In order to add your hotel details please verify your email ID. We have sent the activation link to your registered email address.");
	}
	else
	{
		$msg = "Email id already exist";
	}
}?>
<html>
<head>
<meta charset="utf-8">
<link href="signinup/css/style.css" rel='stylesheet' type='text/css'>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="application/x-javascript">
	addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
	function hideURLbar(){ window.scrollTo(0,1); }
</script> 
<!--webfonts-->
<link href='http://fonts.googleapis.com/css?family=Oxygen:400,300,700' rel='stylesheet' type='text/css'>
<!--//webfonts-->
</head>
<body >
<div >
	<div id="wrapper">
		<div class="main" id="">
			<span style="color:#FFFFFF; font-size:20px;">Partner Sign up</span>
			<?php
			if(isset($_POST['email']))
				{?>
					<div style="color:#FFEF3E; padding-top:30px;"><?=$msg?></div>
				<?php
				}
			else{ ?>
				<form method="post">
					<div class="form-content" id="contentscroll2">
						<!-- <div style="color:yellow;"><?=$msg?></div> -->
						<div class="col_1_of_2 span_1_of_2 form-margin">
							<input class="full-name-box" type="radio" name="partnertype" required style="width: 100% !important;" value="Hotel">Hotel
							<input class="full-name-box" type="radio" name="partnertype" required style="width: 100% !important;" value="Partner">Chain of Hotels
						</div>
						
						<div class="col_1_of_2 span_1_of_2 form-margin">
							<input class="full-name-box" type="text" placeholder="Hotel name" name="hotel" title="Please enter hotel name" required style="width: 100% !important;">
						</div>
						<div class="col_1_of_2 span_1_of_2 form-margin">
							<input class="full-name-box" type="text" placeholder="Your Full Name" name="fullname" title="Please enter your full name" required style="width: 100% !important;">
						</div>
						<div class="col_1_of_2 span_1_of_2 form-margin">
							<input class="full-name-box" type="email" placeholder="Email" name="email" autocomplete="off" required style="width: 100% !important;">
						</div>
						<div class="col_1_of_2 span_1_of_2 form-margin">
							<input class="full-name-box" type="text" placeholder="Contact No." name="contact" autocomplete="off" title="Please enter contact no." required style="width: 100% !important;">
						</div>
						<div class="col_1_of_2 span_1_of_2 form-margin">
							<input class="full-name-box" type="text" placeholder="Company" name="company" autocomplete="off" title="Please enter contact no." required style="width: 100% !important;">
						</div>
						<div style="display:none;" id="newcityblock">
							<div class="col_1_of_2 span_1_of_2 form-margin">
								<input class="full-name-box" type="text" placeholder="Enter City Name" name="newcity" autocomplete="off" style="width: 100% !important;">
							</div>
							<div class="col_1_of_2 span_1_of_2 form-margin">
								<select name="state" class="full-name-box" style="width:100% !important;">
									<option value="1">Goa</option>
								</select>
							</div>
						</div>
						<div class="col_1_of_2 span_1_of_2 form-margin">
							<select name="city" class="full-name-box" style="width:100% !important;" onChange="if(this.value==0)document.getElementById('newcityblock').style.display='block'; else document.getElementById('newcityblock').style.display='none';" title="Please select frotel city" required> 
								<option value="1">Vasai</option>
							</select>
						</div>
						<div class="col_1_of_2 span_1_of_2 form-margin">
							<select name="location" class="full-name-box" style="width:100% !important;" onChange="if(this.value==0)document.getElementById('newcityblock').style.display='block'; else document.getElementById('newcityblock').style.display='none';" title="Please select frotel city" required> 
								<option value="1">Navghar</option>
							</select>
						</div>
						<div class="col_1_of_2 span_1_of_2 form-margin">
							<input class="full-name-box pass-box" type="password" placeholder="Set Password" name="password" autocomplete="off" title="Please enter your coice of password" required style="width: 100% !important;">
						</div>
						<style type="text/css">
							.inner_content_text{overflow:hidden;}
							.inner_content_text h4{text-align:center;/*font-family: 'gotham';*/font-size:14px;}
							.inner_content_text p{padding:22px;text-align: justify;/*font-family: 'gotham';*/font-size:12px;}
							.inner_content_text span{padding:22px;text-align: justify;/*font-family: 'gotham';*/font-size: 12px;}
							.partners_block123{overflow:hidden;}
							.partners_block123 ul{overflow:hidden;}
							.partners_block123 ul li{    padding:22px;margin: 0 0 0 29px;text-align: justify;/* font-family: 'ProximaNova'; */font-size:12px;list-style-type: circle;}
							.partner_heading_block{list-style-type: none !important;margin:0px !important;}
							.obligations_block{padding:22px;text-align: justify;font-family: 'gotham';font-size:12px;}
							.obligations_block h2{padding:22px;text-align: justify;font-family: 'gotham';font-size: 12px;font-weight:bold;}
							.obligations_block p{padding:22px;text-align: justify;font-family: 'gotham';font-size:12px;}
						</style>
						<div class="col_1_of_2 span_1_of_2 form-margin inner_content_text" style="height:130px; overflow-y:scroll; background-color:#FFFFFF;padding:5px;width:99%;" id="boxscroll1">
							<h4>PARTNER TIE-UP AGREEMENT</h4>
							<p>This Service Agreement (hereinafter referred to as "Agreement") is the definitive agreement between Just Freshen-Up, a company incorporated under Ruosh International and having its Registered Office at 2/B/208, Queens Park, Parnaka, Vasai - W-401201(hereinafter referred to as "JFU"); and the "Frotel", Freshen-Up Hotel, subscribing electronically to this service with his/its details captured separately. (JFU and the Frotel are hereinafter individually called "Party" and collectively, "the Parties") </p>
							<span>WHEREAS </span>
							<p>1. JFU is the owner of the Website www.justfreshenup.com (hereinafter referred to as "the Website"), and engaged in the business of providing hotel accommodation services on hourly basis; </p>
							<p>2. The Frotel is engaged in the business of providing stay/accommodation services to its customers intends to avail the facilities offered by JFU through the Website(www.justfreshenup.com) for carrying out its business operations; and </p>
							<p>NOW, THEREFORE, in consideration of the payments and other covenants, obligations and representations contained herein, the sufficiency whereof is hereby acknowledged, the Parties agree as follows: </p>
							<div class="partners_block123">
								<ul>
									<li class="partner_heading_block">Interpretation In this Agreement, unless otherwise provided or if the subject or context otherwise requires: </li>
									<li>Words denoting the singular include the plural and vice versa, and words denoting the whole include a reference to any part thereof. </li>
									<li>Clause and Paragraph headings are inserted for ease of reference only and shall not affect the interpretation of this Agreement.</li>
									<li>References to this Agreement or any document include references to such document or agreement as amended, supplemented, varied or replaced from time to time. </li>
									<li>The words "including", "include" and "in particular" shall be construed as being by way of illustration only and shall not be construed as limiting the generality of any preceding words. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li class="partner_heading_block">Obligations of the Parties </li>
									<li style="font-weight:bold;   list-style-type: none !important;">Obligations of the Frotel: </li>
									<li>JFU grants to the Frotel a limited, non-transferable right to use the Website in accordance with the terms and conditions of this Agreement. The Frotel shall use the JFU Website to make legitimate reservations and shall not use the Website for any other purpose, including without limitation, to make any speculative, false or fraudulent reservation or any reservation in anticipation of demand. </li>
									<li>The Frotel agrees that the Website and the content provided on the Website, including the text, graphics, button icons, audio and video clips, digital downloads, data compilations and software, may not be copied, reproduced, republished, uploaded, posted, transmitted or distributed without the prior written permission of JFU, and/or its third party providers and distributors, except that the Frotel may download, display and print the materials presented on the Website for business and commercial use only. </li>
									<li>The Frotel shall be solely responsible for maintaining the confidentiality of its password and account information. The Frotel may not authorize any third parties to use the services on its behalf, and shall be responsible for all actual or purported use by the Frotel and those allowed by the Frotel to use the services. The Frotel may not divulge, sublicense, transfer, sell or assign its password and account information under any circumstances. Any attempt to do so shall be null and void and shall be considered a material breach of this Agreement. </li>
									<li>The Frotel shall be solely responsible for all usage or activity on its account including, but not limited to, use of the account by any person who uses its password and account information, with or without authorization by the Frotel. If the Frotel has reason to believe that its account is no longer secure (for example, in the event of a loss, theft or unauthorized disclosure or use of its password and account information), it must promptly change the affected password and account information and notify JFU of the problem by emailing it. It is however clarified that the Frotel shall be fully liable for all use of its account, including any unauthorized use of its account by any third-party. </li>
									<li>Without prejudice to the foregoing core method of making bookings by utilizing the license to access the Website through the login and password, the Frotels may from time to time, at JFU's discretional written permission, make bookings by: </li>
									<li>Either sending an offline request for booking through email/phone to JFU, with the Frotel booked through this mode being reflected/uploaded on to the Website only once booking is complete; </li>
									<li>Accessing JFU's account through sub-logins, with such bookings may never get posted/displayed/uploaded on the Website. Accordingly, it is expressly agreed that the Frotel shall be fully liable for payments for the booking through these non-core methods as well. </li>
									<li>The Frotel shall not use the services provided by JFU through the Website for any purpose that is unlawful or prohibited. </li>
									<li>The Frotel shall inform JFU immediately by telephone and additionally confirm in writing any matters coming to the its knowledge which indicate a suspected problem (including incorrect pricing) with or misuse of the JFU automated electronic booking system by any person. </li>
									<li>The Frotel shall use the services provided by JFU at the prices advertised by JFU on the Website (the latest price advised being applicable) provided that the Frotel shall be entitled to offer discounts and incentive schemes to its customers at its sole cost and responsibility without affecting its liability to account to JFU in accordance with this Agreement.</li>
									<li>The contents of the Website (including information, communications, images and sounds contained on or available through www.justfreshenup.com) are provided by JFU, its affiliates, independent content providers and third parties. The contents of the Website are copyright &copy; JFU, its affiliates, independent content providers or third parties and cannot be reproduced, modified, transferred, distributed, republished, downloaded, posted or transmitted in any form or by any means including but not limited to electronic, mechanical photocopying or recording without the prior written permission of JFU </li>
									<li>The Frotel acknowledges that JFU has not reviewed and does not endorse the content of all sites linked to from the Website and is not responsible for the content or actions of any other sites linked to from the Website. The Frotels linking to any service or site shall be at its sole risk. </li>
									<li>Frotel shall be responsible for generating booking invoice directly to its customers accompanied with the required delivery invoicing and all other relevant documents as required under the applicable statutory and governmental regulations. Any improper travel documentation, the issuing Frotel will be responsible to bear the deputation charges.</li>
									<li>Frotel and/or its Partner shall have no authority to bind JFU to any third party commitments of any nature and Frotel shall not hold out as an authorized representative of JFU in any manner whatsoever to any third party.</li>
									<li>It is between the Frotel and the Guest for any loss or damage caused by the Guest during his stay at the Frotel.</li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;   list-style-type: none !important;">Obligations of JFU</li>
									<li>JFU and its affiliates undertake not to disclose or divulge the Frotel's personal information to any third party.  </li>
									<li>JFU shall use all reasonable endeavors to check the accuracy of the Information published on Website. </li>
									<li>JFU shall provide a login and password to Frotel, which will allow the Frotel a limited access to Website. The access to the website is limited in nature, and JFU will not provide any payment gateways to the Frotel directly. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;   list-style-type: none !important;">3. Terms of Payment and Invoicing</li>
									<li>The fees and payment for JFU's Services shall be as specified </li>
									<li>The Frotel shall make payment of 15% per booking towards the commission against every booking. These charges will be deducted from the Frotel's ledger balance as and when they occur. However, JFU reserves the right to amend the rates and schedules thereto from time to time through written communication including electronic mode. </li>
									<li>JThe refund credit will be given back to the Frotel after receipt thereof by JFU </li>
									<li>Non-payment/delayed payment will be considered as the breach of the obligations of the Frotel, and shall further attract a late payment interest at the rate of 18% per annum. Provided that, JFU's receipt of the late interest amount shall not be deemed to be any waiver of the primary breach.</li>
									<li>All account statements shall be available online. In some cases offline statements (not invoices) will be mailed to the Frotels. The Frotel shall make payments as per online statements, unless they are receiving offline statements, which will then override and supersede the online statements. Further, offline statements may be revised to adjust unbilled invoices, refunds, incorrect commissions and the revised statements will be payable in full subject to reconciliation by the Frotel.</li>
									<li>All the Frotels are on Weekly credit, the payment for bookings done between Sundays to Saturday (both days included) should be made on every Monday for the previous week if the payment irrespective of the mode of the payment.</li>
									<li>The payments shall be subject to deduction of tax at source (TDS) as per the provisions of the Income Tax Act. Any other applicable taxes will be over and above the agreed rates. </li>
									<li>Any cancellation of bookings shall be adjusted in next payment cycle and credit note shall be issued of the cancelled tickets. Retention and cancellation charges on hotel and/or air products would be on actual. 
									Any payments due and payable under this Agreement that remain unpaid on the relevant due date of payment shall accrue interest at the rate of 18% per annum from the due date of payment until full payment is made</li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;   list-style-type: none !important;">4. Taxes </li>
									<li>JFU shall be entitled to deduct tax from all commissions/ incentives payable to the Frotel and applicable TDS certificate(s) will be issued in accordance with applicable legal provisions. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;   list-style-type: none !important;">5. Representations and Warranties </li>
									<li>JFU does not warrant nor makes any representations regarding the accuracy or completeness of any data or information contained on the Website. JFU disclaims any liability, responsibility or any other claim, whatsoever, in respect of any loss, whether direct or consequential, to any person, arising out of or from the use of the information contained in the Website. </li>
									<li>Although JFU makes every effort to ensure that the description and content on each page of the Website is correct, it does not, however, take responsibility for any changes occurred due to human, data entry errors or for any loss or damages suffered by any person due to any information contained herein. Also, JFU does not own a Frotel and cannot therefore control or prevent changes in the published descriptions. </li>
									<li style="font-weight:bold;   list-style-type: none !important;">The Frotel represents and warrants that: </li>
									<li>It has full power and authority to enter into this Agreement as at the date of execution of this Agreement. </li>
									<li>It is not aware of any charges, actions, suits, and proceedings etc., actual or threatened, which would restrict or prohibit him from performing any of your obligations under this Agreement. </li>
									<li>there are no current, pending or threatened actions or proceedings before any court, arbitrator, administrative tribunal or government authority which might materially and adversely affect its business, assets or conditions (financial or otherwise) or operations or the ability to perform obligations under this Agreement. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold; list-style-type: none !important;">6. Indemnity  </li>
									<li>The Frotel agrees and undertakes to protect, defend, indemnify and hold harmless JFU its employees, officers, directors, Frotels or representatives from and against any and all liabilities, damages, fines, penalties and costs (including reasonable legal fees and disbursements in connection therewith and interest chargeable thereon) arising from or relating to:</li>
									<li>Any breach of any statute, regulation, direction, orders or standards from any governmental body, agency, or regulator; </li>
									<li>Any breach of the terms and conditions in this Agreement by the Frotel or its employees, officers, directors, Frotels or representatives; </li>
									<li>Any claim of any infringement of any intellectual property right or any other right of any third party or of law. </li>
									<li>Any claim made by any third party/user arising out of the use of the services and/or arising in connections with services offered by the Frotel under this Agreement. </li>
									<li>The Frotel also agrees to indemnify, defend and hold harmless JFU and/or its affiliates, partner websites and their respective lawful successors and assigns from and against any and all losses, liabilities, claims, damages, costs and expenses asserted against or incurred by the JFU and/or its affiliates, partner websites and their respective lawful successors and assigns that arise out of, result from, or may be payable by virtue of, any breach or nonperformance of any representation, warranty, covenant or agreement made or obligation to be performed by the Frotel pursuant to this Agreement. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">7. Liability  </li>
									<li>The Frotel acknowledges that JFU acts only as a website provider commonly known as portal and therefore it has no liability whatsoever for any aspect of the arrangements between the Frotel and the customer as regard to the services provided by the Frotel. In no circumstances shall JFU be liable for any activities/ services provided by the Frotel. </li>
									<li>The Website may contain links to other websites ("Linked Sites"). The Linked Sites are not under the control of JFU and it is not responsible for the contents of any Linked Site, including without limitation any link contained in a Linked Site, or any changes or updates to a Linked Site. JFU is not responsible for any form of transmission, whatsoever, received from any Linked Site. JFU is providing these links to the Frotel only as a convenience, and the inclusion of any link does not imply endorsement by JFU of the site or any association with its operators or owners including the legal heirs or assigns thereof. </li>
									<li>JFU does not accept responsibility for any defects that may exist or for any costs, loss of profits, loss of data or consequential losses arising from the Frotel's use of, or inability to use or access or a failure, suspension or withdrawal of all or part of the service at any time. The Frotel acknowledges that JFU has no control over and JFU excludes all liability for any material on the World Wide Web, which can be accessed by using the Website. </li>
									<li>JFU shall not be liable to the Frotel or any other party claiming for the Frotel by virtue of termination of this Agreement for any reason whatsoever for any claim for loss or profit or on account for any expenditure, investment, leases, capital improvements or any other commitments made by the Frotel or any other party in connection with their business made in reliance upon or by virtue of this Agreement</li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">8. Term and Termination of Contract </li>
									<li>This Agreement shall enter into effect on and from the date first mentioned hereinabove and shall continue to be in operation unless otherwise terminated by either Party in accordance with the provisions of this Agreement. </li>
									<li>Either Party may terminate this Agreement with immediate effect by giving a notice to the other Party. </li>
									<li>JFU may also terminate this Agreement and/or discontinue provision of any of the services at any time for any reason, including any improper use of the Website or the Frotel's failure to comply with the terms and conditions of this Agreement. Such termination shall not affect any right to relief to which JFU and its third party providers and distributors may be entitled, at law or in equity. Upon termination of this Agreement and these terms and conditions, all rights granted to the Frotel will terminate and revert to JFU and its third party providers or distributors, as applicable. </li>
									<li>The Frotel agrees and understands that in case of breach of clause 3: Term of Payment, JFU reserves rights to cancel all the unutilized bookings for the future dates wherein the Frotel will fully indemnify and hold harmless JFU against any claims of its customer in relation to such bookings. </li>
									<li>The Frotel agrees that in case of non-payment on due dates JFU reserves the right to block further issuance and reissuance of invoice till the old payment is made in full. </li>
									<li>With immediate effect from the date of termination, the Frotel shall cease to use the services offered by JFU and shall immediately deliver up to JFU in accordance with the directions of JFU all documents and other property (including without limitation financial and statutory records) belonging to JFU (insofar as such property and information was obtained in pursuance of the performance of services under this Agreement).</li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">9. Confidentiality </li>
									<li>The Frotel acknowledges that all documents and any other material containing or referring to confidential information which at any time are or become within or under its control, power or possession are, shall become and shall at all times remain the property of JFU, to which the confidential information relates. </li>
									<li style="font-weight:bold;list-style-type: none !important;">The Frotel undertakes (both during the term and after the termination date): </li>
									<li>On request made at any time by JFU, to deliver all the confidential information (including copies thereof) or delete the confidential information from any re-usable material in accordance with the directions of JFU; </li>
									<li>not to use or disclose any confidential information except as is necessary to perform its obligations under this Agreement or except as required by law or any regulatory body, provided that this clause shall not apply to confidential information which comes into the public domain other than through the default of any member of the Frotel; </li>
									<li>Not to copy or reproduce any confidential information in any form or on any media or device except as is necessary to perform its obligations under this Agreement; </li>
									<li>To ensure that the password and account information provided to it by the JFU for accessing the Website is secured by all means. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">10. Intellectual Property Rights </li>
									<li>The contents of the Website (including information, communications, images and sounds contained) are provided by JFU, its affiliates, independent content providers and third parties. The contents of this site are copyright &copy; of JFU, its affiliates, independent content providers or third parties and cannot be reproduced, modified, transferred, distributed, republished, downloaded, posted or transmitted in any form or by any means including but not limited to electronic, mechanical photocopying or recording without the prior written permission of JFU </li>
									<li>JFU has and shall retain all ownership rights in the Website, including all patent rights, copyrights, trade secrets, trademarks, service marks, related goodwill and confidential and proprietary information. The Frotel will have no rights in the Website except as explicitly stated in this Agreement. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">11. Governing Law</li>
									<li>This Agreement shall be governed by and construed and in accordance with the Laws of India, and subject to Arbitration provisions below, with the exclusive forum being courts at Mumbai. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">12. Arbitration </li>
									<li>Any and all disputes or controversies arising out of or in connection with the interpretation, performance or non-performance, or termination of this Agreement, shall, to the extent possible, be settled in the first instance by prompt and good faith negotiations between the Parties. </li>
									<li>If the dispute cannot be settled within seven (7) days by mutual discussions, the dispute shall finally be settled by arbitration under the Arbitration and Conciliation Act, 1996 by a sole arbitrator to be appointed by JFU. The venue of arbitration shall be New Delhi and the arbitration proceedings shall be conducted in English language. </li>
									<li>Each Party shall bear its own costs (including legal costs) for participating in the arbitration proceedings. The arbitrator's fees and expenses and other incidental expenses shall be paid by the Parties as determined by the arbitral tribunal. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">13. Assignment  </li>
									<li>The Frotel shall not assign or transfer all or any of its rights or obligations under this Agreement without the prior written consent of JFU. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">14. Force Majeure </li>
									<li>Neither Party to this Agreement shall be liable for failure to perform any of its obligations hereunder during any period in which such performance is delayed by Force Majeure event including but not limited to fire, flood, war, riot, embargo, organized labor stoppage, earthquake, hurricane, acts of civil or military authorities, acts of terrorism, acts of god etc. beyond the reasonable control of the Parties, provided that the Party whose performance is affected by the event of Force Majeure gives notice in writing to the other Party of such event and provided further that the Party whose performance is so affected did not act in a reckless manner or did not willfully misconduct itself.</li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">15. Severability </li>
									<li>The various provisions of the Agreement are severable and if any provision is found by the Parties hereto or is held to be invalid or unenforceable by any court of competent jurisdiction such invalidity or unenforceability shall not affect the validity or enforceability of any of its other provisions unless it goes to the root of the Agreement or radically affects it. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">16. Entire Agreement and Amendments </li>
									<li>This Agreement sets forth the entire agreement and understanding between the Parties as to the subject matter hereof and merges all prior discussions and negotiations between them. No modification to or amendment of this Agreement shall be valid or binding unless made in writing by means of a side-letter and signed on behalf of the Parties by their duly authorized officers or representatives. </li>
								</ul>
							</div>
							<div class="partners_block123">
								<ul>
									<li style="font-weight:bold;list-style-type: none !important;">17 GENERAL PROVISIONS </li>
									<li style="font-weight:bold;list-style-type: none !important;">Binding Effect; Benefit. </li>
									<li>This Agreement shall insure to the benefit of and are binding upon the parties hereto and their respective successors and permitted assignees. Nothing in this Agreement, expressed or implied, is intended to confer on any person other than the parties hereto and the JFU persons, any rights, remedies, obligations or liabilities under or by reason of this Agreement. </li>
									<li style="font-weight:bold;list-style-type: none !important;">Notices</li>
									<li>Any notice or other communication that is or can be important to JFU should be very well communicated verbally and written way so as to avoid further miscommunications and misunderstandings.</li>
								</ul>
							</div>
						</div>
						<div class="submit">
							<input type="checkbox" onChange="if(this.checked)document.getElementById('submit').disabled=false; else document.getElementById('submit').disabled=true;">I agree to the terms and conditions
						</div>
						<div class="submit" style="width:100%;">
							<input type="submit" onClick="myFunction()" value="Submit" id="submit" disabled>
						</div>
						<div class="clear"></div><br>
						<style>
							.full-name-box{width:99% !important;box-sizing: border-box; margin-left:0 !important; padding:19px 15px !important; font-size:1em; font-family:'Oxygen', sans-serif; color:#666666 !important; font-weight:700 !important; border:none !important; outline:none !important;}
							.pass-box {margin:0 !important;}
							.form-margin {width:100% !important; padding: 18px 0 !important;}
						</style>
					</div>
				</form>
				<?php 
				}
				?>
		</div>
	</div>
</div>
</body>
<!-- scroll -->
	 <!-- <link href="css/scroll/nicescroll.css" rel="stylesheet"> -->
	<!-- <script src="js/scroll/jquery.min.js"></script> -->
	<!-- <script src="js/scroll/jquery.nicescroll.min.js"></script>
	<script src="js/scroll/jquery.min.js"></script>
	<script type="text/javascript">
	  $(document).ready(function() {
		var nicesx = $("#lightboxscroll").niceScroll({railpadding:{top:0,right:0,left:200,bottom:0},autohidemode:false,cursorminheight:25,touchbehavior:false,cursorcolor:"#FFF113",cursorborder:"#FFF113",cursorwidth:8,cursorminheight:20});

		var nicesx = $("#boxscroll12").niceScroll({railpadding:{top:0,right:0,left:15,bottom:0},autohidemode:false,cursorminheight:25,touchbehavior:false,cursorcolor:"#FFF113",cursorborder:"#FFF113",cursorwidth:8,cursorminheight:20});
	  });
	</script> -->
	<!-- <style type="text/css">
		#ascrail2000{left:1098px !important;}
	</style> -->
  <!-- scroll -->
<!-----------nicescroll------------------->
<script src="js/addpartnerscroll/jquery-1.11.3.min.js"></script>
<script src="js/addpartnerscroll/jquery.nicescroll.min.js"></script>

<script>
  $(document).ready(function() {
  
	var nice = $("").niceScroll();  // The document page (body)
    
    $("#boxscroll1").niceScroll({cursorborder:"",cursorcolor:"#FFF113",boxzoom:true}); // First scrollable DIV

	$("#boxscroll2").niceScroll({cursorborder:"",cursorcolor:"#FFF113",boxzoom:true}); // First scrollable DIV
    
  });
</script>
<!-----------nicescroll------------------->
</html>