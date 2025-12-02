<?php include 'conn.php';

include 'udf.php';



if (!isset($_SESSION['groupid'])) { ?>

	<script type="text/javascript">
		window.parent.location = "login.php";
	</script>

	<?php

} else if ($_SESSION['groupid'] <= 2) {

	if ($msg == "Room numbers are set") { ?>

		<script type="text/javascript">
			window.parent.location = "admin.php?Pg=roomdetails";
		</script>

	<?php

	} ?>

	<!DOCTYPE html>

	<html lang="en">

	<head>

		<?php include("head.php"); ?>

	</head>

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
		<div class="the-box" style="padding-bottom:0px; margin-bottom: 0px;">

			<?php

			$hotelcode = execute("select u.cm_company_name, h.user from hotels h JOIN users u ON h.user = u.id where h.id = $_SESSION[hotel]");

			$row = execute("select TIMESTAMPDIFF(HOUR,b.cancellationrequestdate,b.checkindatetime)cancellationpriortocheckin,u.fullname,hu.company,b.reg_date,b.source, b.cancellationrequestdate,b.checkindatetime,(b.declaredtariff-b.pcdiscount+b.hotelst+ b.hsbc+b.hkcc+b.frotelst+ b.fsbc+b.fkcc+b.lt+b.sc+ b.stonsc)total from bookings b join room_distribution rd on rd.bookingid=b.id join roomnumbers rn on rn.id=rd.roomnumber join roomtypes r on rn.roomtype =r.id join hotels h on h.id=r.hotel join users hu on hu.id=h.user join users u on u.id=b.guestid where b.id=$id;");
			
			if($row['source'] === 'PMS' || $row['source']==='Direct'){
				$source="Offline";
			}else{
				$source ="CM -$row[source]";
			}

			?>

			<h4 class="small-text">CANCEL BOOKING</h4>

			<a href="cancellationpolicy.php?id=<?= $id ?>" target="_blank">View hotel cancellation policy</a>

			<div class="row">

				<form onSubmit="return confirm('Value entered once can not be reversed. Are you sure, you want to initiate refund?');">

					<div class="col-xs-12">

						<div class="form-group">

							<label>Cancelled By</label>

							<div class="form-control"><?= $row['fullname'] ?></div>

						</div>

					</div>

					<div class="col-xs-12">

						<div class="form-group">

							<label>Hotel</label>

							<div class="form-control"><?= $row['company'] ?></div>

						</div>

					</div>

					<div class="col-xs-6">

						<div class="form-group">

							<label>Booking Date</label>

							<div class="form-control"><?= date_format(date_create($row['reg_date']), "d-M-Y H:i") ?></div>

						</div>

					</div>

					<div class="col-xs-6">

						<div class="form-group">

							<label>Cancellation Request Date</label>

							<div class="form-control"><?= date_format(date_create($row['cancellationrequestdate']), "d-M-Y H:i") ?></div>

						</div>

					</div>

					<div class="col-xs-6">

						<div class="form-group">

							<label>Checkin Date Time</label>

							<div class="form-control"><?= date_format(date_create($row['checkindatetime']), "d-M-Y H:i") ?></div>

						</div>

					</div>

					<div class="col-xs-6">

						<div class="form-group">

							<label>Hours B4 Checkin</label>

							<div class="form-control"><?= $row['cancellationpriortocheckin'] ?></div>

						</div>

					</div>

					<div class="col-xs-12">

						<div class="form-group">

							<label>Booking Source</label>

							<div class="form-control"><?= $source ?></div>

						</div>

					</div>

					<div class="col-xs-12">

						<div class="form-group">

							<label>Booking Amount</label>

							<div class="form-control"><?= round($row['total'], 2) ?></div>

						</div>

					</div>

					<div class="col-xs-12">

						<div class="form-group">

							<label>Refunded Amount</label>

							<input type="number" name="refundamount" class="form-control" min=0 step="0.01" placeholder="Amount to be refunded" required>

						</div>

					</div>

					<div class="col-xs-12">

						<div class="form-group">

							<label>Reason of Cancellation</label>

							<textarea name="cancellationreason" class="form-control" placeholder="Reason of cancellation" rows=2 required></textarea>

						</div>

					</div>

					<div class="col-xs-12">

						<div class="form-group">

							<input type="button" onClick="handleClick('<?= $hotelcode['cm_company_name'] ?>','<?= $id ?>')" value="Cancel" class="btn btn-danger">

						</div>

					</div>

				</form>

			</div>

		</div>

	</body>
	<script>
		function handleClick(hotelCode, bookingId) {

			let refund_amount = document.querySelector('input[name="refundamount"]').value;
			let refund_reason = document.querySelector('textarea[name="cancellationreason"]').value;
			const body = {
				"action": "cancel",
				"hotelCode": hotelCode,
				"channel": "banqueteasy",
				"bookingId": bookingId,
				"refund_amount": refund_amount,
				"refund_reason": refund_reason
			}

			console.log(body);


			fetch(`update_reservation.php`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						// Include any additional headers your API requires here
					},
					body: JSON.stringify(body)
				})
				.then(response => response.json())
				.then(data => {
          showMessageBoxuserdetails("Room Cancelled Successfully", "success");

					// Handle the response from the API
					console.log('Success:', data);
				})
				.catch(error => {
          showMessageBoxuserdetails("Room Cancellation Failed", "error");

					// Handle any errors
					console.error('Error:', error);
				});

		}
	</script>

	<?php include("js.php"); ?>

	</html>

<?php

}

ob_end_flush(); ?>