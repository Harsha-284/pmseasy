<?php include 'conn.php';
include 'udf.php';

if (!isset($_SESSION['groupid'])) { ?>

    <script type="text/javascript">
        window.parent.location = "login.php";
    </script>

<?php
} elseif (isset($_GET['data'])) {
    $data = $_GET['data'];
    $decodedData = urldecode($data);

    // Decode HTML entities
    $decodedData = html_entity_decode($decodedData);

    // Now decode the JSON
    $roomData = json_decode($decodedData, true);

?>

    <!DOCTYPE html>

    <html lang="en">

    <head>

        <?php include("head.php"); ?>

    </head>
    <style>
        label {
            margin-bottom: 0px;
            font-weight: 500
        }
    </style>

    <body style="padding-top:0px;">

        <div id="messageBox-userdetails"></div>
        <script>
            function showMessageBoxuserdetails(content, type) {
                const messageBox = document.getElementById('messageBox-userdetails');

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
                <div style="position:absolute; z-index:50; width:75%; padding: 5px 14px; height:30px; top:9px; right:37px; border-radius: 2px;" class="alert ${alertClass} alert-block square fade in alert-dismissable">
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
        <!-- 2nd modal for checking availability-->

        <div class="modal-body user-details" style="overflow-y: auto; height:89%">
            <div class="olddetails">
                <h1 style="font-weight:bold" class="page-heading">User Details</h1>
                <h5 class="modal-title" id="dataTo2ndModal" style="display: none;"></h5>
            </div>
            <!-- <hr> -->
            <div class="information">
                <form class="row g-3 needs-validation" novalidate style="display: flex; flex-wrap: wrap; margin: 0px -1px 0px -10px; ">
                    <div class="col-md-2" style="flex: 1; min-width: 50px; margin-right: 0px; padding-right: 0px; padding-left: 7px">
                        <label for="validationCustom01" class="form-label">Check in date</label>
                        <input type="date" name="checkinDateBooking" class="form-control" id="validationCustom01" style="padding: 5px" disabled>
                    </div>
                    <div class="col-md-2" style="flex: 1; min-width: 50px; margin-right: 0px; padding-right: 0px; padding-left: 7px">
                        <label for="validationCustom02" class="form-label">Check out date</label>
                        <input type="date" name="checkoutDateBooking" class="form-control" id="validationCustom02" style="padding: 5px" disabled>
                    </div>
                    <div class="col-md-1" style="flex: 1; min-width: 50px; margin-right: 0px; padding-right: 0px; padding-left: 7px">
                        <label for="validationCustom05" class="form-label">Adult</label>
                        <input type="number" name="adultBooking" class="form-control" id="validationCustom05" value="1" disabled>
                    </div>
                    <div class="col-md-1" style="flex: 1; min-width: 50px; margin-right: 0px; padding-right: 0px; padding-left: 7px">
                        <label for="validationCustom04" class="form-label">Child</label>
                        <input type="number" name="childBooking" class="form-control" id="validationCustom04" value="0" disabled>
                    </div>
                    <div class="col-md-1" style="flex: 1; min-width: 50px; margin-right: 0px; padding-right: 0px; padding-left: 7px">
                        <label for="validationCustom05" class="form-label">No. of rooms</label>
                        <input type="number" name="noOfRooms" class="form-control" id="validationCustom05" value="1" disabled>
                    </div>
                </form>

                <hr>

                <form class="row g-3 needs-validation" novalidate style="display: flex; flex-wrap: wrap;">
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom01" class="form-label">First Name </label>
                        <input type="text" class="form-control" id="validationCustom01" onkeypress="return blockNumbers(event)" placeholder="Enter your first name" name="first_name" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="validationCustom02" onkeypress="return blockNumbers(event)" placeholder="Enter your last name" name="last_name" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Contact Details</label>
                        <input type="number" class="form-control" id="validationCustom02" placeholder="Enter your contact details" name="contact" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Email</label>
                        <input type="email" class="form-control" id="validationCustom02" placeholder="abc@gmail.com" name="email" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">Country</label>
                        <input type="text" class="form-control" id="validationCustom02" onkeypress="return blockNumbers(event)" placeholder="Enter Country Name" name="country" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">State</label>
                        <!-- <input type="text" class="form-control" id="validationCustom02" onkeypress="return blockNumbers(event)" placeholder="Enter State name" name="state" style="width:176px" required> -->
                        <select name="state" id="stateDropDown" class="form-control" required="" style="width: 176px;">
                            <option value="">State</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra" selected="">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                            <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                            <option value="Chandigarh">Chandigarh</option>
                            <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                            <option value="Daman and Diu">Daman and Diu</option>
                            <option value="Lakshadweep">Lakshadweep</option>
                            <option value="National Capital Territory of Delhi">National Capital Territory of Delhi</option>
                            <option value="Puducherry">Puducherry</option>
                            <option value="Telangana">Telangana</option>
                            <option value="New Delhi">New Delhi</option>
                        </select>

                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">City</label>
                        <input type="text" class="form-control" id="validationCustom02" placeholder="Enter city name" onkeypress="return blockNumbers(event)" name="city" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom02" class="form-label">ZipCode</label>
                        <input type="text" class="form-control" id="validationCustom02" placeholder="Enter ZipCode" name="zip_code" style="width:176px" required>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom03" class="form-label">Identity proof</label>
                        <select name="payment_mode" id="identityproof" style="display: block;width:176px; height: 30px; border-color: gainsboro">
                            <option value="Aadhar Card">Aadhar Card</option>
                            <option value="Pan Card">Pan Card</option>
                            <option value="Passport">Passport</option>
                        </select>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                        <label for="validationCustom03" class="form-label">Identity proof</label>
                        <input type="file" class="form-control" id="fileinput-user-details-modal" style="width:176px" required>
                    </div>
                    <?php
                    $where = "";
                    if (isset($_SESSION['hotel']) && $_SESSION['hotel'] > 0) {
                        $hotelId = (int)$_SESSION['hotel'];
                        $where = "WHERE status = 1 AND banquet = $hotelId";
                    } else {
                        $where = "WHERE status = 1";
                    }

                    $agents = $conn->query("SELECT * FROM agents $where");
                    ?>
                    <?php if ($agents && $agents->num_rows > 0) { ?>
                        <label style="display:flex; align-items:center; margin-top:15px;margin-left:15px;">
                            <input type="checkbox" id="showAgentCheckbox"> Assign to Agent
                        </label>

                        <div class="col-md-4" id="agentSelectWrapper" style="display: none;">
                            <label for="agentSelect" class="form-label">Select Agent:</label>
                            <select name="agent_id" id="agentSelect" class="form-control">
                                <option value="">-- Select Agent --</option>
                                <?php foreach ($agents as $agent): ?>
                                    <option value="<?= $agent['id'] ?>"><?= htmlspecialchars($agent['agent']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php } ?>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                    <script>
                        $(document).ready(function() {
                            $('#showAgentCheckbox').on('change', function() {
                                if ($(this).is(':checked')) {
                                    $('#agentSelectWrapper').show();
                                } else {
                                    $('#agentSelectWrapper').hide();
                                    $('#agentSelect').val(''); // Clear selection if hidden
                                }
                            });
                        });
                    </script>

                    <label for="validationCustom04" style="width: 100%;margin-left: 12px" class="form-label">Room Rate Selection</label>

                    <div class="ratecards" id="ratecards" style="display: flex; width: 100%; flex-wrap: wrap; justify-content: space-between">
                    </div>
                    <form class="row g-3 needs-validation" novalidate style="display: flex; flex-wrap: wrap; margin-bottom: 15px">
                        <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px; display: grid">
                            <label for="validationCustom03" class="form-label">Mode of payment</label>
                            <select name="payment_mode" id="paymentModeDropdown" style="width:176px; height: 30px; border-color: gainsboro">
                                <option value="" disabled selected>Please select</option>
                                <option value="online">Online</option>
                                <option value="upi">UPI</option>
                                <option value="cash">Cash</option>
                                <option value="credit/debit card">Credit/Debit Card</option>
                                <option value="payatcheckout">Pay at checkout</option>
                                <option value="discount">Discount</option>
                                <option value="writeoff">Write off</option>
                                <option value="neft/rtgs/imps">NEFT/RTGS/IMPS</option>
                                <option value="cheque/dd">Cheque/DD</option>
                            </select>
                        </div>

                        <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="dateOfPayment">
                            <label for="dateOfPaymentInput" class="form-label">Date of payment</label>
                            <input type="date" class="form-control" id="dateOfPaymentInput" name="date_of_payment" style="width:176px">
                        </div>
                        <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="amount">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" name="amount_inp" style="width:176px">
                        </div>

                        <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px; display: grid" id="discountChoiceDropdown">
                            <label for="discountChoiceDropdown" class="form-label" style="display: block">Discount (Flat or %)</label>
                            <select name="discount_type" id="discountChoiceDropdown" style="width:176px; height: 30px; border-color: gainsboro">
                                <option value="flat">Flat</option>
                                <option value="percent">%</option>
                            </select>
                        </div>

                        <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="discount">
                            <label for="discountInput" class="form-label">Discount</label>
                            <input type="text" class="form-control" id="discountInput" name="discount" style="width:176px">
                        </div>

                        <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="txnId">
                            <label for="txnIdInput" class="form-label">Txn id</label>
                            <input type="text" class="form-control" id="txnIdInput" name="txn_id" style="width:176px">
                        </div>

                        <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="chequeBank">
                            <label for="chequeBankInput" class="form-label">Cheque bank</label>
                            <input type="text" class="form-control" id="chequeBankInput" name="cheque_bank" style="width:176px">
                        </div>

                        <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="chequeNo">
                            <label for="chequeNoInput" class="form-label">Cheque No</label>
                            <input type="number" class="form-control" id="chequeNoInput" name="cheque_no" style="width:176px">
                        </div>

                        <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="chequeDate">
                            <label for="chequeDateInput" class="form-label">Cheque date</label>
                            <input type="date" class="form-control" id="chequeDateInput" name="cheque_date" style="width:176px">
                        </div>
                    </form>

                    <script>
                        function updatePrices(adults, children, roomtypeid, no_of_room) {
                            return new Promise((resolve, reject) => {
                                $.ajax({
                                    type: 'POST',
                                    url: 'bookingajax.php',
                                    data: {
                                        action: "check_extra_person_allowed",
                                        adult: adults,
                                        id: roomtypeid,
                                        child: children,
                                        rooms: no_of_room
                                    },
                                    success: function(response) {
                                        let data = JSON.parse(response)
                                        if (data.status === "success") {
                                            document.querySelector('input[name="fixed-rate-input"]').value = data.totalPrice;
                                            resolve(Number(data.totalPrice));
                                        }
                                        if (data.status === "error") {
                                            document.querySelector('input[name="fixed-rate-input"]').value = 0;
                                            resolve(0);
                                        }
                                    },
                                    error: function(err) {
                                        console.log('Error:', err);
                                    }
                                });
                            });
                        }

                        function updateFormFields() {
                            const paymentMode = document.getElementById('paymentModeDropdown').value;

                            // Hide all fields by default
                            // document.getElementById('dateOfPayment').style.display = 'none';
                            document.getElementById('discount').style.display = 'none';
                            document.getElementById('txnId').style.display = 'none';
                            document.getElementById('chequeBank').style.display = 'none';
                            document.getElementById('chequeNo').style.display = 'none';
                            document.getElementById('chequeDate').style.display = 'none';
                            document.getElementById('amount').style.display = 'none';
                            document.getElementById('discountChoiceDropdown').style.display = 'none';

                            document.querySelector('input[name="date_of_payment"]').value = '';
                            document.querySelector('input[name="discount"]').value = '';
                            document.querySelector('input[name="txn_id"]').value = '';
                            document.querySelector('input[name="cheque_bank"]').value = '';
                            document.querySelector('input[name="cheque_no"]').value = '';
                            document.querySelector('input[name="cheque_date"]').value = '';
                            document.querySelector('input[name="amount_inp"]').value = '';

                            // Show fields based on payment mode
                            if (paymentMode === 'writeoff') {
                                // No fields to show
                            } else if (paymentMode === 'neft/rtgs/imps' || paymentMode === 'upi') {
                                document.getElementById('dateOfPayment').style.display = 'block';
                                document.getElementById('txnId').style.display = 'block';
                                document.getElementById('amount').style.display = 'block';
                            } else if (paymentMode === 'credit/debit card') {
                                document.getElementById('dateOfPayment').style.display = 'block';
                                document.getElementById('amount').style.display = 'block';
                                document.getElementById('txnId').style.display = 'block';
                            } else if (paymentMode === 'cash') {
                                document.getElementById('dateOfPayment').style.display = 'block';
                                document.getElementById('amount').style.display = 'block';
                            } else if (paymentMode === 'cheque/dd') {
                                document.getElementById('chequeBank').style.display = 'block';
                                document.getElementById('chequeNo').style.display = 'block';
                                document.getElementById('chequeDate').style.display = 'block';
                                document.getElementById('amount').style.display = 'block';
                            } else if (paymentMode === 'discount') {
                                document.getElementById('discount').style.display = 'block';
                                document.getElementById('discountChoiceDropdown').style.display = 'block';
                            } else if (paymentMode === 'payatcheckout') {
                                document.getElementById('amount').style.display = 'none';
                            } else if (paymentMode === 'online') {
                                document.getElementById('amount').style.display = 'block';
                                document.getElementById('txnId').style.display = 'block';
                            }
                        }

                        document.getElementById('paymentModeDropdown').addEventListener('change', updateFormFields);

                        // Initialize form fields on page load
                        window.onload = updateFormFields;
                    </script>


                    <form class="row g-3 needs-validation" novalidate style="display: flex; flex-wrap: wrap; margin-top: 10px">
                        <div class="col-md-12" style="margin-bottom: 10px; margin-right: 0px;">
                            <label for="commentTextarea" class="form-label">Comment</label>
                            <textarea class="form-control" placeholder="Add comment..." name="comment" id="commentTextarea" style="min-width: 775px; max-width: 775px; min-height: 48px;"></textarea>
                        </div>
                    </form>

                    <?php
                    $hotelcode = execute("select u.cm_company_name, h.user from hotels h JOIN users u ON h.user = u.id where h.id = $_SESSION[hotel]");
                    ?>
                    <div class="submit-button" style="width: 100%;float: right; text-align: end;">
                        <label for="commentTextarea " class="form-label " style="display: block; font-weight: 700;">Deal Amount</label>
                        <input class="fw-bold" type="text" style=" text-align: end;  border: none; 
    background-color: #fff;" name="fixed-rate-input" disabled>
                        
                    </div>
                    <div class="deal-submint-btn" style="text-align: end; margin-top: 6%;" >
                    <a id="submitAnchor" class="btn btn-primary" style="margin: 0px 0px 0px auto;" onClick="bookRoom('<?= $hotelcode['cm_company_name'] ?>')">
                            Submit
                        </a>
                    </div>

                </form>
            </div>
        </div>


        </div>
        </div>
        </div>
        <script src="js/bookings.js?v=<?= time() ?>"></script>

        <script>
            function toggleSubmitAndImage(anchorId, imageSrc) {
                const anchor = document.getElementById(anchorId);
                if (anchor) {
                    const img = anchor.querySelector('img');
                    if (img) {
                        anchor.innerHTML = 'Submit';
                    } else {
                        anchor.innerHTML = '';
                        const newImg = document.createElement('img');
                        newImg.src = imageSrc;
                        newImg.style.width = '20px';
                        newImg.style.height = '20px';
                        newImg.alt = 'loading...';
                        anchor.appendChild(newImg);
                    }
                }
            }




            let roomData = <?php echo json_encode($roomData); ?>;

            let roomDataString = JSON.stringify(roomData);

            document.addEventListener('DOMContentLoaded', function() {
                let modalTitleElement = document.getElementById('dataTo2ndModal');
                if (modalTitleElement) {
                    modalTitleElement.innerText = roomDataString;
                }

                let rateCardHtml = '';

                rateCardHtml += `
                                    <div id="rate-card-${0}" style="width: 778px; margin: 0 11px 15px; text-align: center; box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                                        <div style="background-color: #3BAFDA; padding: 5px; border-bottom: 1px solid #ddd; font-size:11px; ">
                                            <h3 id="roomRatePlanTitle-${0}" style="margin: 0; font-size: 1.2em; color:#fff;">${roomData.roomtypename}</h3>
                                        </div>
                                        <div style="padding: 0px 10px; font-size: 13px;">
                                            <div id="radio-container-${0}" style="display: flex; flex-direction: column; align-items: flex-start; margin-top: 2px;">
                                                Loading...
                                            </div>
                                        </div>
                                    </div>`;


                let total_day = getDatesBetween(roomData.checkinDateBooking, roomData.checkoutDateBooking).length

                updatePrices(roomData.old_adult, roomData.old_child, roomData.roomtypeid, roomData.no_of_rooms)
                    .then((res) => {
                        let extra_charge = res;
                        getRoomRatePlan(roomData.roomtypeid, roomData.no_of_rooms, 0, total_day, extra_charge);
                    })
                    .catch((error) => {
                        console.log('Error:', error); // Handle any errors from the AJAX request
                    });

                document.getElementById("ratecards").innerHTML = rateCardHtml;

            });


            var checkinInput = document.getElementById('validationCustom01');
            checkinInput.value = roomData.checkinDateBooking;

            // Set check-out date and time
            var checkoutInput = document.getElementById('validationCustom02');
            checkoutInput.value = roomData.checkoutDateBooking;
            document.querySelector('input[name="childBooking"]').value = roomData.childBooking
            document.querySelector('input[name="adultBooking"]').value = roomData.adultBooking
            document.querySelector('input[name="noOfRooms"]').value = roomData.no_of_rooms

            document.addEventListener('DOMContentLoaded', function() {
                let now = new Date();

                var year = now.getFullYear();
                var month = ('0' + (now.getMonth() + 1)).slice(-2);
                var day = ('0' + now.getDate()).slice(-2);
                var formattedDateTime = `${year}-${month}-${day}`;

                document.getElementById('dateOfPaymentInput').value = formattedDateTime;
                console.log("date value", document.getElementById('dateOfPaymentInput').value)
            });
        </script>
        <script>
            function blockNumbers(e) {
                var char = String.fromCharCode(e.which);
                if (/[0-9]/.test(char)) {
                    return false; // Prevent typing number
                }
            }
        </script>

    </body>

    <?php include("js.php"); ?>


    </html>

<?php

}

ob_end_flush(); ?>