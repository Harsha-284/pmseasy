<?php include 'conn.php';
include 'udf.php';

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

    .col-md-4 {
        width: 196px;
    }
</style>

<body style="padding-top:0px;">
    <div class="modal-body" style="">
        <div class="olddetails">
            <h1 class="page-heading" style="font-weight:bold">Payment Details</h1>
            <div id="messageBox-editpayment"></div>
            <script>
                function showMessageBoxeditpayment(content, type) {
                    const messageBox = document.getElementById('messageBox-editpayment');

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
                    <div style="position:absolute; z-index:50; width:35%; padding: 5px 14px; height:30px; top:17px; right:15px; border-radius: 2px;" class="alert ${alertClass} alert-block square fade in alert-dismissable">
                        <button style="width: 45px;" type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <p style="width:90%;">${content}</p>
                    </div>`;

                    messageBox.style.display = "block";

                    setTimeout(() => {
                        messageBox.style.display = "none";
                    }, 4000);
                }
            </script>


            <h5 class="modal-title" id="dataTo2ndModal" style="display: none;"></h5>
        </div>
        <!-- <hr> -->
        <?php
        if ($_GET['isUpdate'] == 0) {
        ?>
            <form class="row g-3 needs-validation" novalidate style="display: flex; flex-wrap: wrap; margin-bottom: 15px;margin-right: 0px;">

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px; display: grid">
                    <label for="paymentModeDropdown" class="form-label">Mode of payment</label>
                    <select name="payment_mode" id="paymentModeDropdown" style="width:185px; height: 30px; border-color: gainsboro">
                         <option value="" disabled selected>Please select</option>
                        <option value="online">Online</option>
                        <option value="upi">UPI</option>
                        <option value="cash">Cash</option>
                        <option value="credit/debit card">Credit/Debit Card</option>
                        <option value="discount">Discount</option>
                        <option value="writeoff">Write off</option>
                        <option value="neft/rtgs/imps">NEFT/RTGS/IMPS</option>
                        <option value="cheque/dd">Cheque/DD</option>
                    </select>
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                    <label for="checkinDateBooking" class="form-label">Date of Payment</label>
                    <input type="date" name="date_of_payment" class="form-control" id="validationCustom01" style="width:185px" required>
                </div>


                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="amount">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" name="amount_inp" style="width:185px">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px; display: grid" id="discounttype">
                    <label for="discountChoiceDropdown" class="form-label" style="display: block">Discount type</label>
                    <select name="discount_type" id="discountChoiceDropdown" style="width:185px; height: 30px; border-color: gainsboro">
                        <option value="flat">Flat</option>
                        <option value="percent">%</option>
                    </select>
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="discount">
                    <label for="discountInput" class="form-label">Discount</label>
                    <input type="text" class="form-control" id="discountInput" name="discount" style="width:185px">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="txnId">
                    <label for="txnIdInput" class="form-label">Txn id</label>
                    <input type="text" class="form-control" id="txnIdInput" name="txn_id" style="width:185px">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="chequeBank">
                    <label for="chequeBankInput" class="form-label">Cheque bank</label>
                    <input type="text" class="form-control" id="chequeBankInput" name="cheque_bank" style="width:185px">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="chequeNo">
                    <label for="chequeNoInput" class="form-label">Cheque No</label>
                    <input type="number" class="form-control" id="chequeNoInput" name="cheque_no" style="width:185px">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="chequeDate">
                    <label for="chequeDateInput" class="form-label">Cheque date</label>
                    <input type="date" class="form-control" id="chequeDateInput" name="cheque_date" style="width:185px">
                </div>

                <div class="col-md-12" style="margin-bottom: 10px; margin-right: 0px;">
                    <label for="commentTextarea" class="form-label">Comment</label>
                    <textarea class="form-control" placeholder="Add comment..." name="comment" id="commentTextarea" style="min-width: 773px; max-width: 767px; min-height: 48px;"></textarea>
                </div>
                <div class="submit-button" style="width: 787px; margin-left: 10px; " ;>
                    <a class="btn btn-primary" style="float: right; " onClick="handleSubmit()">Submit</a>
                </div>
            </form>

            <script>
                // tableBody.insertBefore(newRow, tableBody.firstChild);
                // const today = new Date();
                // const yyyy = today.getFullYear();
                // const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                // const dd = String(today.getDate()).padStart(2, '0');
                // const formattedDate = `${yyyy}-${mm}-${dd}`;
                // console.log(document.getElementById('chequeDateInput'))

                // document.getElementById('chequeDateInput').value = formattedDate;

                function updateFormFields() {
                    const paymentMode = document.getElementById('paymentModeDropdown').value;

                    // Disable all fields by default
                    document.getElementById('discount').style.display = 'none';
                    document.getElementById('discounttype').style.display = 'none'; // Correct id
                    document.getElementById('txnId').style.display = 'none';
                    document.getElementById('chequeBank').style.display = 'none';
                    document.getElementById('chequeNo').style.display = 'none';
                    document.getElementById('chequeDate').style.display = 'none';

                    // Clear field values
                    document.getElementById('discountInput').value = '';
                    document.getElementById('txnIdInput').value = '';
                    document.getElementById('chequeBankInput').value = '';
                    document.getElementById('chequeNoInput').value = '';
                    document.getElementById('chequeDateInput').value = '';
                    document.getElementById('amount').querySelector('input').value = '';

                    // Show fields based on payment mode
                    if (paymentMode === 'discount') {
                        document.getElementById('discounttype').style.display = 'block';
                        document.getElementById('discount').style.display = 'block'; // Correct id
                        document.getElementById('amount').style.display = 'none';
                    } else if (paymentMode === 'neft/rtgs/imps' || paymentMode === 'upi') {
                        document.getElementById('txnId').style.display = 'block';
                    } else if (paymentMode === 'cash') {
                        document.getElementById('amount').style.display = 'block';
                    } else if (paymentMode === 'writeoff') {
                        document.getElementById('amount').style.display = 'block';
                        document.getElementById('amount').style.display = 'none';
                    } else if (paymentMode === 'credit/debit card') {
                        document.getElementById('amount').style.display = 'block';
                        document.getElementById('txnId').style.display = 'block';
                    } else if (paymentMode === 'cheque/dd') {
                        document.getElementById('chequeBank').style.display = 'block';
                        document.getElementById('chequeNo').style.display = 'block';
                        document.getElementById('chequeDate').style.display = 'block';
                    } else if (paymentMode === 'online') {
                        document.getElementById('amount').style.display = 'block';
                        document.getElementById('txnId').style.display = 'block';
                    }
                }

                // Add event listener for dropdown change
                document.getElementById('paymentModeDropdown').addEventListener('change', updateFormFields);

                // Initialize form fields on page load
                window.onload = updateFormFields;

                function handleSubmit(params) {
                    let date_of_payment = document.querySelector('input[name="date_of_payment"]').value;
                    let txn_id = document.querySelector('input[name="txn_id"]').value;
                    let cheque_bank = document.querySelector('input[name="cheque_bank"]').value;
                    let cheque_no = document.querySelector('input[name="cheque_no"]').value;
                    let cheque_date = document.querySelector('input[name="cheque_date"]').value;
                    let amount_inp = document.querySelector('input[name="amount_inp"]').value;
                    let comment = document.getElementById('commentTextarea').value;
                    let mode_of_payment = document.getElementById('paymentModeDropdown').value;

                    let discount_type = mode_of_payment === "discount" ? document.getElementById('discountChoiceDropdown').value : "";
                    let flat_discount = "";
                    let percent_discount = "";
                    let type_of_discount = ""




                    if (discount_type === "flat") {
                        flat_discount = document.querySelector('input[name="discount"]').value;
                        type_of_discount = "flat";
                    } else if (discount_type === "percent") {
                        percent_discount = document.querySelector('input[name="discount"]').value;
                        type_of_discount = "percentage";
                    }


                    // Validation based on payment mode
                    if (mode_of_payment === 'discount' && (!flat_discount && !percent_discount)) {
                        showMessageBoxeditpayment("Please provide a discount value.", "warning");
                        return;
                    }

                    if ((mode_of_payment === 'neft/rtgs/imps' || mode_of_payment === 'upi' || mode_of_payment === 'credit/debit card' || mode_of_payment === 'online') && txn_id === "") {
                        showMessageBoxeditpayment("Transaction ID is required for this payment mode.", "warning");
                        return;
                    }

                    if (mode_of_payment === 'cheque/dd') {
                        if (cheque_bank === "") {
                            showMessageBoxeditpayment("Bank name is required for cheque payment.", "warning");
                            return;
                        }
                        if (cheque_no === "") {
                            showMessageBoxeditpayment("Cheque number is required.", "warning");
                            return;
                        }
                        if (cheque_date === "") {
                            showMessageBoxeditpayment("Cheque date is required.", "warning");
                            return;
                        }
                    }

                    if ((mode_of_payment === 'cash' || mode_of_payment === 'neft/rtgs/imps' || mode_of_payment === 'upi' || mode_of_payment === 'credit/debit card' || mode_of_payment === 'online') && (amount_inp === "")) {
                        showMessageBoxeditpayment("Amount is required for cash payment.", "warning");
                        return;
                    }

                    $.ajax({
                        type: 'POST',
                        url: 'bookingajax.php',
                        data: {
                            payment_type: mode_of_payment,
                            date_of_payment: date_of_payment,
                            txnid: txn_id,
                            cheque_bank: cheque_bank,
                            cheque_no: cheque_no,
                            cheque_date: cheque_date,
                            comment: comment,
                            amount_inp: amount_inp,
                            discount_type: type_of_discount,
                            flat_discount: flat_discount,
                            percent_discount: percent_discount,
                            bookingid: <?= $_GET['id'] ?>,
                            action: 'add_payment_mode',
                            user:<?=$_SESSION['id']?>, 
                            hotel:<?= $_SESSION['hotel']?>
                        },
                        success: function(response) {
                            let res = JSON.parse(response);
                            console.log(res);
                            window.history.back();
                        },
                        error: function(err) {
                            console.log('err', err);
                        }
                    });

                }
            </script>

        <?php
        } else { ?>
            <?php
            $data = $_GET['data'];

            $decodedData = urldecode($data);

            // Decode HTML entities
            $decodedData = html_entity_decode($decodedData);
            $decodedData = json_decode($decodedData, true);

            ?>

            <form class="row g-3 needs-validation" novalidate style="display: flex; flex-wrap: wrap; margin-bottom: 15px">

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px; display: grid">
                    <label for="paymentModeDropdown" class="form-label">Mode of payment</label>
                    <select disabled name="payment_mode" id="paymentModeDropdown" style="width:120px; height: 30px; border-color: gainsboro">
                         <option value="" disabled selected>Please select</option>
                        <option value="online" <?= $decodedData['payment_type'] === 'online' ? 'selected' : '' ?>>Online</option>
                        <option value="upi" <?= $decodedData['payment_type'] === 'upi' ? 'selected' : '' ?>>UPI</option>
                        <option value="cash" <?= $decodedData['payment_type'] === 'cash' ? 'selected' : '' ?>>Cash</option>
                        <option value="credit/debit card" <?= $decodedData['payment_type'] === 'credit/debit card' ? 'selected' : '' ?>>Credit/Debit Card</option>
                        <option value="discount" <?= $decodedData['payment_type'] === 'discount' ? 'selected' : '' ?>>Discount</option>
                        <option value="writeoff" <?= $decodedData['payment_type'] === 'writeoff' ? 'selected' : '' ?>>Write off</option>
                        <option value="neft/rtgs/imps" <?= $decodedData['payment_type'] === 'neft/rtgs/imps' ? 'selected' : '' ?>>NEFT/RTGS/IMPS</option>
                        <option value="cheque/dd" <?= $decodedData['payment_type'] === 'cheque/dd' ? 'selected' : '' ?>>Cheque/DD</option>
                    </select>
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;">
                    <label for="checkinDateBooking" class="form-label">Date of Payment</label>
                    <input type="date" name="date_of_payment" class="form-control" id="validationCustom01" style="width:120px"
                        value="<?= htmlspecialchars($decodedData['date_of_payment']) ?>" required>
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="amount">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" name="amount_inp" style="width:120px"
                        value="<?= htmlspecialchars($decodedData['amount']) ?>">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px; display: grid" id="discounttype">
                    <label for="discountChoiceDropdown" class="form-label" style="display: block">Discount type</label>
                    <select name="discount_type" id="discountChoiceDropdown" style="width:120px; height: 30px; border-color: gainsboro">
                        <option value="flat" <?= $decodedData['discount_type'] === 'flat' ? 'selected' : '' ?>>Flat</option>
                        <option value="percent" <?= $decodedData['discount_type'] === 'percentage' ? 'selected' : '' ?>>%</option>
                    </select>
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="discount">
                    <label for="discountInput" class="form-label">Discount</label>
                    <input type="text" class="form-control" id="discountInput" name="discount" style="width:120px"
                        value="<?= $decodedData['discount_type'] === 'flat' ? htmlspecialchars($decodedData['discount_flat']) : htmlspecialchars($decodedData['discount_percent']) ?>">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="txnId">
                    <label for="txnIdInput" class="form-label">Txn id</label>
                    <input type="text" class="form-control" id="txnIdInput" name="txn_id" style="width:120px"
                        value="<?= htmlspecialchars($decodedData['txnid']) ?>">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="chequeBank">
                    <label for="chequeBankInput" class="form-label">Cheque bank</label>
                    <input type="text" class="form-control" id="chequeBankInput" name="cheque_bank" style="width:120px"
                        value="<?= htmlspecialchars($decodedData['cheque_bank']) ?>">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="chequeNo">
                    <label for="chequeNoInput" class="form-label">Cheque No</label>
                    <input type="number" class="form-control" id="chequeNoInput" name="cheque_no" style="width:120px"
                        value="<?= htmlspecialchars($decodedData['cheque_no']) ?>">
                </div>

                <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px;" id="chequeDate">
                    <label for="chequeDateInput" class="form-label">Cheque date</label>
                    <input type="date" class="form-control" id="chequeDateInput" name="cheque_date" style="width:120px"
                        value="<?= $decodedData['cheque_date'] !== '0000-00-00' ? htmlspecialchars($decodedData['cheque_date']) : '' ?>">
                </div>

                <div class="col-md-12" style="margin-bottom: 10px; margin-right: 0px;">
                    <label for="commentTextarea" class="form-label">Comment</label>
                    <textarea class="form-control" placeholder="Add comment..." name="comment" id="commentTextarea"
                        style="min-width: 785px; max-width: 767px; min-height: 48px;"><?= htmlspecialchars($decodedData['comment']) ?></textarea>
                </div>

                <div class="submit-button" style="width: 787px; margin-left: 10px;">
                    <a class="btn btn-primary" style="float: right;" onClick="update_mode()">Submit</a>
                </div>
            </form>
            <script>
                function update_mode(params) {
                    let date_of_payment = document.querySelector('input[name="date_of_payment"]').value;
                    let txn_id = document.querySelector('input[name="txn_id"]').value;
                    let cheque_bank = document.querySelector('input[name="cheque_bank"]').value;
                    let cheque_no = document.querySelector('input[name="cheque_no"]').value;
                    let cheque_date = document.querySelector('input[name="cheque_date"]').value;
                    let amount_inp = document.querySelector('input[name="amount_inp"]').value;
                    let comment = document.getElementById('commentTextarea').value;
                    let mode_of_payment = document.getElementById('paymentModeDropdown').value;

                    let discount_type = mode_of_payment === "discount" ? document.getElementById('discountChoiceDropdown').value : "";
                    let flat_discount = "";
                    let percent_discount = "";
                    let type_of_discount = ""

                    
                    if (discount_type === "flat") {
                        flat_discount = document.querySelector('input[name="discount"]').value;
                        type_of_discount = "flat";
                    } else if (discount_type === "percent") {
                        percent_discount = document.querySelector('input[name="discount"]').value;
                        type_of_discount = "percentage";
                    }


                    // Validation based on payment mode
                    if (mode_of_payment === 'discount' && (!flat_discount && !percent_discount)) {
                        showMessageBoxeditpayment("Please provide a discount value.", "warning");
                        return;
                    }

                    if ((mode_of_payment === 'neft/rtgs/imps' || mode_of_payment === 'upi' || mode_of_payment === 'credit/debit card' || mode_of_payment === 'online') && txn_id === "") {
                        showMessageBoxeditpayment("Transaction ID is required for this payment mode.", "warning");
                        return;
                    }

                    if (mode_of_payment === 'cheque/dd') {
                        if (cheque_bank === "") {
                            showMessageBoxeditpayment("Bank name is required for cheque payment.", "warning");
                            return;
                        }
                        if (cheque_no === "") {
                            showMessageBoxeditpayment("Cheque number is required.", "warning");
                            return;
                        }
                        if (cheque_date === "") {
                            showMessageBoxeditpayment("Cheque date is required.", "warning");
                            return;
                        }
                    }

                    if ((mode_of_payment === 'cash' || mode_of_payment === 'neft/rtgs/imps' || mode_of_payment === 'upi' || mode_of_payment === 'credit/debit card' || mode_of_payment === 'online') && (amount_inp === "")) {
                        showMessageBoxeditpayment("Amount is required for cash payment.", "warning");
                        return;
                    }

                    $.ajax({
                        type: 'POST',
                        url: 'bookingajax.php',
                        data: {
                            payment_type: mode_of_payment,
                            date_of_payment: date_of_payment,
                            txnid: txn_id,
                            cheque_bank: cheque_bank,
                            cheque_no: cheque_no,
                            cheque_date: cheque_date,
                            comment: comment,
                            amount_inp: amount_inp,
                            discount_type: type_of_discount,
                            flat_discount: flat_discount,
                            percent_discount: percent_discount,
                            paymentmodeid: <?= $decodedData['id'] ?>,
                            bookingid: <?= $_GET['id'] ?>,
                            action: 'update_payment_mode',
                            user:<?=$_SESSION['id']?>,
                            hotel:<?= $_SESSION['hotel']?>
                        },
                        success: function(response) {
                            let res = JSON.parse(response);
                            console.log(res);
                            window.history.back();
                        },
                        error: function(err) {
                            console.log('err', err);
                        }
                    });
                }
            </script>
            <script>
                function updateFormFields() {
                    const paymentMode = document.getElementById('paymentModeDropdown').value;

                    // Disable all fields by default
                    document.getElementById('discount').style.display = 'none';
                    document.getElementById('discounttype').style.display = 'none'; // Correct id
                    document.getElementById('txnId').style.display = 'none';
                    document.getElementById('chequeBank').style.display = 'none';
                    document.getElementById('chequeNo').style.display = 'none';
                    document.getElementById('chequeDate').style.display = 'none';

                    // Show fields based on payment mode
                    if (paymentMode === 'discount') {
                        document.getElementById('discounttype').style.display = 'block';
                        document.getElementById('discount').style.display = 'block'; // Correct id
                        document.getElementById('amount').style.display = 'none';
                    } else if (paymentMode === 'neft/rtgs/imps' || paymentMode === 'upi') {
                        document.getElementById('txnId').style.display = 'block';
                    } else if (paymentMode === 'cash') {
                        document.getElementById('amount').style.display = 'block';
                    } else if (paymentMode === 'writeoff') {
                        document.getElementById('amount').style.display = 'block';
                        document.getElementById('amount').style.display = 'none';
                    } else if (paymentMode === 'credit/debit card') {
                        document.getElementById('amount').style.display = 'block';
                        document.getElementById('txnId').style.display = 'block';
                    } else if (paymentMode === 'cheque/dd') {
                        document.getElementById('chequeBank').style.display = 'block';
                        document.getElementById('chequeNo').style.display = 'block';
                        document.getElementById('chequeDate').style.display = 'block';
                    } else if (paymentMode === 'online') {
                        document.getElementById('amount').style.display = 'block';
                        document.getElementById('txnId').style.display = 'block';
                    }
                }

                // Add event listener for dropdown change
                document.getElementById('paymentModeDropdown').addEventListener('change', updateFormFields);

                // Initialize form fields on page load
                window.onload = updateFormFields;
            </script>
        <?php
        } ?>

</body>

<script>
    // Get today's date
    var today = new Date();
    var day = ('0' + today.getDate()).slice(-2);
    var month = ('0' + (today.getMonth() + 1)).slice(-2);
    var year = today.getFullYear();

    // Set the value of the input field to today's date
    var todayDate = year + '-' + month + '-' + day;
    document.getElementById('validationCustom01').value = todayDate;
    document.getElementById('chequeDateInput').value = todayDate;
</script>

<?php include("js.php"); ?>


</html>

<?php



ob_end_flush(); ?>