<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<!-- saved from url=(0066)https://www.banqueteasy.com/erp/print/receipt/receipt.php?id=54124 -->

<?php
include 'conn.php';
include 'udf.php'; ?>

<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <title>Payment Receipt</title>
  <meta name="Generator" content="EditPlus">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <!-- <link rel="stylesheet" href="./Payment Receipt_files/style.css"> -->
  <style type="text/css">
    .buttons {
      width: 80px;
      height: 40px;
      font-size: 18px;
      color: #FFFFFF;
      background-color: #2c2a1e;
      border-radius: 5px;
      border-width: 0px;
      margin-right: 25px;
      cursor: pointer;
    }
  </style>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Calibri, arial;
    }

    * {
      padding: 0;
      margin: 0;
      font-family: Calibri, arial;
    }

    .wrapper {
      width: 1034px;
      margin: 0 auto;
      height: auto;
      /* border: 2px solid black; */
      position: relative;
      background-image: url("../images/bg.png");
      background-size: cover;
      background-position: center -25px;
      background-repeat: no-repeat;
    }

    .top-image-section {}

    .mid-section {
      overflow: hidden;
      width: 100%;
    }

    .innerwrapper {
      width: 984px;
      margin: 0 auto;
      border: 2px solid black;
      padding: 25px;
    }

    .receipt {
      float: left;
    }

    .receipt-no,
    .receipt-no2,
    .spand2dot {
      font-size: 22px;
      font-family: cursive;
      font-family: Calibri, arial;
    }

    p.add,
    addbloack {
      border-bottom: 2px solid black;
      margin-bottom: 0px;
      font-family: "roboto";
      font-weight: 400;
      font-size: 15px;
      font-family: Arial, Helvetica, sans-serif;
      font-family: Calibri, arial;
      /* font-family: Arial, Helvetica, sans-serif; */
    }

    .address {
      position: absolute;
      top: 18%;
      right: 0;
      max-width: 302px;
    }

    p.batch {
      font-weight: bold;
      font-size: 22px;
    }

    .content {}

    .date {
      margin-left: 145px;
      text-align: right;
      margin-top: 16px;
      float: right;
    }

    p.date-no {
      float: left;
      margin-right: 4px;
    }

    .date-section {
      margin-top: -8px;
      border-bottom: 1px dashed black;
      border-top: none;
      border-right: none;
      border-left: none;
      width: 230px;
      outline: none;
      background: transparent;
      font-weight: 300;
      font-size: 22px;
      font-family: raleway;
      padding-left: 10px;
    }

    .client-name {
      border-bottom: 1px dashed black;
      border-top: none;
      border-right: none;
      border-left: none;
      width: 67%;
      outline: none;
      background: transparent;
      /* height: 30px; */
      font-size: 28px;
      margin-left: 10px;
      margin-left: 10px;
      padding-left: 8px;
      font-family: raleway;
      font-weight: 300;
    }

    .cheque {
      margin-top: 14px;
      border-bottom: 1px dashed black;
      border-top: none;
      border-right: none;
      border-left: none;
      width: 34%;
      margin-left: 8px;
      outline: none;
      background: transparent;
      padding-left: 6px;
      font-size: 23px;
      font-family: raleway;
      font-weight: 300;
      text-align: center;
    }

    .rupee {
      margin-top: 14px;
      border-bottom: 1px dashed black;
      border-top: none;
      border-right: none;
      border-left: none;
      width: 78%;
      margin-left: 15px;
      outline: none;
      background: transparent;
      font-size: 23px;
      font-family: raleway;
      font-weight: 300;
      padding-left: 10px;
    }


    .dated {
      border-bottom: 1px dashed black;
      border-top: none;
      border-right: none;
      border-left: none;
      width: 33%;
      margin-left: 2px;
      outline: none;
      background: transparent;
      padding-left: 6px;
      font-size: 23px;
      font-family: raleway;
      font-weight: 300;
      text-align: center;
    }

    span.spacing {
      margin-left: 6px;
      margin-right: 4px;
    }

    .pay {
      margin-top: 14px;
      border-bottom: 1px dashed black;
      border-top: none;
      border-right: none;
      border-left: none;
      width: 55%;
      margin-left: 4px;
      outline: none;
      background: transparent;
      padding-left: 6px;
      font-size: 23px;
      font-family: raleway;
      font-weight: 300;
    }

    .batch-month {
      margin-top: 14px;
      border-bottom: 1px dashed black;
      border-top: none;
      border-right: none;
      border-left: none;
      width: 30%;
      margin-left: 4px;
      outline: none;
      background: transparent;
      padding-left: 6px;
      font-size: 23px;
      font-family: raleway;
      font-weight: 300;
      text-align: center
    }

    .balance {
      margin-top: 12px;
      border-bottom: 1px dashed black;
      border-top: none;
      border-right: none;
      border-left: none;
      width: 32%;
      margin-left: 4px;
      outline: none;
      background: transparent;
      padding-left: 6px;
      font-size: 23px;
      font-family: raleway;
      font-weight: 300;
      text-align: center
    }

    span.rec {
      font-size: 22px
    }

    .last-content {
      overflow: hidden;
    }

    .signature {
      float: right;
    }

    .box-size {
      margin-top: 62px;
      float: left;
    }

    .rupee-box {
      width: 270px;
      height: 48px;
      border: 2px solid black;
      overflow: hidden;
    }

    .claim {
      width: 307px;
      margin-top: 0px;
    }

    .claim-sent {
      margin-top: 5px;
      font-family: roboto;
      font-size: 14px;
    }


    .words {
      margin-right: 25px;
    }


    .amount {
      margin-top: -46px;
      text-align: center;
      font-size: 26px;
      font-family: raleway;
      font-weight: 300;
    }



    /*******************/
    .font-bold {
      font-weight: bold;
    }

    .calibri {
      font-family: Calibri, arial;
      /* font-family: Arial, Helvetica, sans-serif; */
    }

    .font-size-22px {
      font-size: 22px;
    }

    .width-45 {
      width: 45%;
    }

    .width-58 {
      width: 58%;
    }

    .width-25 {
      width: 25%;
    }

    .width-38 {
      width: 38%;
    }

    .width-57 {
      width: 57%;
    }

    .width-100 {
      width: 100%;
    }

    .float-left {
      float: left;
    }

    .clear-left {
      clear: left;
    }

    .oneline {
      margin-left: 90px;
      width: 67%;
    }

    .overflow-hidden {
      overflow: hidden;
    }

    .margin-left {
      margin-left: 205px;
    }

    .margin-left-95 {
      margin-left: 95px;
    }

    .margin-top {
      margin-top: 5px;
    }

    .margin-left-120 {
      margin-left: 120px;
    }

    .margin-left-138 {
      margin-left: 138px;
    }

    .margin-left-140 {
      margin-left: 140px;
    }

    .margin-left-150 {
      margin-left: 150px;
    }

    .margin-left-210 {
      margin-left: 210px;
    }

    .margin-left-170 {
      margin-left: 210px;
    }

    .margin-right-29 {
      margin-right: 29%;
    }

    .receipt-no {
      overflow: hidden;
      width: 100%;
    }

    .margin-left-0 {
      margin-left: 0px;
    }

    .receipt-no input {
      width: 70% !important;
      float: right !important;
      margin-top: 0px;
      margin-left: 0px !important;
      padding-left: 10px;
    }

    .check-dn {
      width: 70%;
      float: right;
      display: flex;
    }

    .check-dn>p {
      /* margin-top: 8px; */
      width: 59% !important;
    }

    .check-dn>p>input {
      width: 39% !important;
      /*margin-right: 7%;*/
    }

    .check-dn>p:nth-of-type(1)>input {
      /*float: left;*/
      margin-right: 29%;
    }

    .check-dn>p:nth-of-type(2) {
      width: 50% !important;
      text-align: left;
    }

    .check-dn>p:nth-of-type(2)>input {
      /* margin-left: 8% !important; */
      float: unset !important;
      min-width: 39% !important;
      max-width: 77% !important;
      width: auto !important;
    }

    .autho {
      font-size: 19px;
      margin-top: 91px;
      font-weight: 400;
    }

    .wrapper {
      padding-bottom: 20px;
    }

    .totalamt {
      margin: 0px;
      font-size: 26px;
    }

    .all-center {
      justify-content: center;
      align-items: center;
      display: flex;
    }

    .colon-d {
      float: right;
    }

    .text-tup {
      text-transform: uppercase;
    }

    .nameofbanq {
      position: absolute;
      top: 5px;
      color: #fff;
      font-size: 30px;
      left: 26px;
    }

    .receipt-no2 {
      max-width: 40%;
      float: left;
    }

    .spand2dot {
      margin-right: 10px;
    }

    .divnm .check-dn .vnpm {
      width: 39% !important;
    }

    .mvp60 {
      margin: 0px !important;
      float: left;
      width: 69.8%;
      padding-left: 3%;
      padding-left: 11px;
    }

    .vmc40 {
      width: 29%;
      float: left;
    }
  </style>
</head>

<body>

  <?php
  $userInfo = execute("select u.email,u.company,u.cm_company_name,u.address1,r.roomtype,r.id,rn.roomnumber,c.city from roomnumbers rn join roomtypes r on r.id=rn.roomtype join hotels h on h.id=r.hotel join users u on u.id=h.user join locations l on l.id=h.location join cities c on c.id=l.city where rn.id=$_GET[id]");


  $bdate = date_create($_GET['date']);
  $now = date_create(date("Y-m-d H:i"));
  $a = date_create($bdate->format("Y-m-d") . " " . '12' . ":" . '00');
  $row = execute("select b.id,b.hours,b.reg_date,u.fullname,u.id_proof,(declaredtariff-pcdiscount+hotelst+hsbc+hkcc+frotelst+fsbc+fkcc+lt+sc+stonsc)total,u.fullname,u.contact,u.email,u.address1, b.trav_name,b.checkindatetime,b.checkoutdatetime,b.usercheckedin,b.usercheckedout from bookings b left join room_distribution rd on b.id=rd.bookingid left join users u on u.id=b.guestid where (b.paid=1 or ticker>'" . $now->format("Y-m-d H:i") . "') and (b.status='Scheduled' or b.status='Cancelled') and rd.roomnumber=$_GET[id] 
								and 
			(
			b.checkindatetime<='" . $a->format("Y-m-d H:i") . "' and b.checkoutdatetime>='" . $a->format("Y-m-d H:i") . "'
		)");


  $additional_services = execute("select asp.amount,a.service,asp.id from additional_services_receipt asp join additional_services a on a.id=asp.additional_service_id where asp.additional_service_id='$_GET[serviceid]' and asp.bookingid='$row[id]' and guest_service_id=$_GET[guest_service_id]");

  $checkindate = new DateTime($row['checkindatetime']);
  $checkoutdate = new DateTime($row['checkoutdatetime']);

  ?>

  <div class="wrapper" id="wrapper">
    <div style="height:100px">
      <div style="width:25%; float:left; margin:20px 0px 20px 25px; font-weight:bold; font-size:23px; padding:10px; background-color:#EEEEEE; height:100px">
        <img src="./images/1474040IH.png" border="0" alt="1474040IH.png" style="max-width:100%; max-height:100%">
      </div>
      <div style="width:66%; float:left; margin:20px 25px 20px 0px; font-weight:bold; font-size:23px; padding:10px; background-color:#EEEEEE; height:100px">
        <?= $userInfo['company'] ?><div style="font-weight:normal; font-size:16px;">
        <?= $userInfo['address1'] ?> <br /><?= $userInfo['city'] ?><br />E-mail: <?= $userInfo['email'] ?>
        </div>
      </div>
      <div class="innerwrapper">
        <div class="mid-section">
          <div class="receipt">
            <p class="receipt-no font-bold calibri text-tup">Receipt no&nbsp;<?=$additional_services['id']?></p>
          </div>

          <div class="date calibri font-bold">
            <p class="date-no ">Date</p><input type="text" class="date-section font-bold font-size-22px calibri" value="<?= $checkindate->format("d-m-Y") ?>" readonly="">
          </div>
        </div>
        <div class="content divnm">
          <div class="first-line calibri">
            <p class="receipt-no">Received With thanks from <input class="client-name font-bold calibri font-size-22px margin-top" type="text" value="<?= $row['fullname'] ?>" readonly=""><span class="colon-d">:</span></p>
            <p class="receipt-no2">Additional Service
            </p>
            <div class="overflow-hidden check-dn ">
              <p class="receipt-no float-left width-38">Date<input class="rupee font-bold calibri font-size-22px oneline width-38 margin-top float-left width-" type="text" value="30-Sep-2024" readonly=""><span class="colon-d">:</span></p>

            </div>
            <span class="colon-d spand2dot">:</span>
            <div class="overflow-hidden check-dn">
              <p class="receipt-no float-left width-38" style="width:100% !important">Service<input class="rupee font-bold calibri font-size-22px oneline width-38 margin-top float-left width-" type="text" value="<?= $additional_services['service'] ?>" readonly="" style="margin-right:22px; width:538px !important; overflow:hidden"><span class="colon-d">:</span></p>
            </div>

            <p class="receipt-no">Mode of Payment<input class="rupee font-bold calibri font-size-22px oneline width-58 margin-top margin-left-138" type="text" value="Cash" readonly=""><span class="colon-d">:</span></p>
            <p class="receipt-no">The sum of rupees <input class="rupee font-bold calibri font-size-22px oneline margin-top" type="text" name="number-to-word" readonly=""><span class="colon-d">:</span></p>
            <div class="width-100 float-left">
              <!-- <p class="receipt-no vmc40">Note <span class="colon-d">:</span></p> -->
              <!-- <div class="rupee font-bold calibri font-size-22px oneline margin-left margin-top mvp60" style="height:28px"></div> -->
              <p></p>
            </div>
          </div>
        </div>
        <div class="last-content" style="width:100%">
          <div class="box-size">
            <div class="rupee-box all-center">
              <!-- <img src="receipt/images/indian-currency-389006_960_720.jpg" width="65" height="50"> -->
              <p class="amount calibri font-bold font-size-22px totalamt">₹ <?=$additional_services['amount']?> </p>
            </div>
            <div class="claim">
            </div>
          </div>
          <div class="signature calibri">
            <p class="autho calibri">Authorised Signatory</p>
          </div>
        </div>
      </div>
    </div>
    <div id="bttns" style="width:1034px; margin:auto; margin-top:30px;">
    </div>

    <script>
      let word = numberToWords('<?= $additional_services['amount'] ?>')
      document.querySelector('input[name="number-to-word"]').value = word;

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

</body>

</html>