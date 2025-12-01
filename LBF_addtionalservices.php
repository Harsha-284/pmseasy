<?php include 'conn.php';
include 'udf.php';

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <?php include("head.php"); ?>

</head>

<body style="padding-top:0px;">

    <div class="modal-body" style="">
        <div class="olddetails">
            <h5 style="font-weight:bold">Additional Services Details</h5>
            <h5 class="modal-title" id="dataTo2ndModal" style="display: none;"></h5>
        </div>
        <hr>

        <form class="row g-3 needs-validation" novalidate style="display: flex; flex-wrap: wrap;">
            <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px; width: 100%">
                <label for="validationCustom01" class="form-label">Date</label>
                <input type="date" name="service-date" class="form-control" id="validationCustom01" style="padding: 5px" required>
            </div>
            <div class="col-md-4" style="margin-bottom: 10px; margin-right: 0px; width: 100%">
                <label for="validationCustom02" class="form-label">Quantity</label>
                <input type="number" class="form-control" value="1" id="validationCustom02" placeholder="Enter your last name" name="service-quantity" required>
            </div>

            <div class="submit-button" style="width: 100%;float: right;position: fixed;margin-left: 515px;bottom: 18" ;>
                <a class="btn btn-primary" style="margin: 0px 23px 0px auto;" onClick="handleSubmit()">Submit</a>
            </div>
        </form>
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

    function handleSubmit(params) {
        let service_date = document.querySelector('input[name="service-date"]').value;
        let service_quantity = document.querySelector('input[name="service-quantity"]').value;

        $.ajax({
            type: 'POST',
            url: 'bookingajax.php',
            data: {
                action: "add_guest_additional_service",
                date: service_date,
                quantity: service_quantity,
                bookingid: <?= $_GET['bookingid'] ?>,
                additional_service_id: <?= $_GET['serviceid'] ?>,
                user: <?= $_SESSION['id'] ?>,
                hotel: <?= $_SESSION['hotel'] ?>
            },
            success: function(response) {
                res = JSON.parse(response)
                location.reload()
            },
            error: function(err) {
                console.log('err', err);
            }
        });
    }
</script>

<?php include("js.php"); ?>


</html>

<?php



ob_end_flush(); ?>