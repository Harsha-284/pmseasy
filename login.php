<?php include 'conn.php';

include 'udf.php';

$msg = "";

if (col('email') != "" and col('pwd') != "") {

	$pass	= new Crypter('awssecret', '26457381');

	$email	= col("email");

	$pwd	= col("pwd"); //$pass->encrypt(col("pwd"));

	$result = $conn->query("select id,groupid,email,fullname from users where contact='$email' and password='$pwd' and emailverified=1 and mobileverified=1 and active=1");
	// print_r($result);
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();

		$_SESSION['username']	= $row['fullname'];

		$_SESSION['email']		= $row['email']; // Username of to be displayed on top right corner

		$_SESSION['groupid']	= $row['groupid']; // Tells user belongs to which user group

		$_SESSION['id']			= $row['id']; // Unique numerical id of the user

		$_SESSION['modules']	= "";

		$user_id = $row['id'];

		// Step 2: Find hotel for this user
		$hotel_query = "SELECT id FROM hotels WHERE user = '$user_id'";
		$hotel_result = $conn->query($hotel_query);

		$hotel_staff_query = "SELECT hotel_id FROM hotel_staff WHERE user_id = '$user_id'";
		$hotel_staff_result = $conn->query($hotel_staff_query);
		if ($hotel_result->num_rows > 0) {
			$hotel = $hotel_result->fetch_assoc();
			$hotel_id = $hotel['id'];

			// Step 3: Get latest subscription for this hotel
			$subscription_query = "SELECT * FROM pms_subscriptions 
                               WHERE hotel_id = '$hotel_id' 
                               ORDER BY id DESC LIMIT 1";
			$subscription_result = $conn->query($subscription_query);
			if ($subscription_result->num_rows > 0) {
				$subscription = $subscription_result->fetch_assoc();
				$start_date = $subscription['start_date'];
				$end_date = $subscription['end_date'];
				$today = date("Y-m-d");
				// Step 4: Check if subscription is active
				if ($today >= $start_date && $today <= $end_date) {
					if ($_SESSION['groupid'] == 2) {

						$row = execute("select id from hotels where user=$_SESSION[id]");

						$_SESSION['hotel'] = $row['id'];
					}

					if ($_SESSION['groupid'] == 3 || $_SESSION['groupid'] == 4) {

						$row = execute("select hotel_id,permission from hotel_staff where user_id=$_SESSION[id]");

						$_SESSION['hotel'] = $row['hotel_id'];

						$_SESSION['permission'] = $row['permission'];
					}
					// $conn->query("update users set lastlogin=now() where id=".$row['id']);
					if ($_SESSION['groupid'] == 1)

						header('Location:admin.php?Pg=myhotels');

					else if ($_SESSION['groupid'] == 2)

						header('Location:admin.php?Pg=bookingmap');

					else if ($_SESSION['groupid'] == 3 || $_SESSION['groupid'] == 4)

						header('Location:admin.php');
				} else {
					$msg = "Your subscription has expired.";
				}
			} else {
				$msg = "No subscription found for the hotel.";
			}
		} else if ($hotel_staff_result->num_rows > 0) {
			$hotel = $hotel_staff_result->fetch_assoc();
			$hotel_id = $hotel['hotel_id'];

			// Step 3: Get latest subscription for this hotel
			$subscription_query = "SELECT * FROM pms_subscriptions 
                               WHERE hotel_id = '$hotel_id' 
                               ORDER BY id DESC LIMIT 1";
			$subscription_result = $conn->query($subscription_query);
			if ($subscription_result->num_rows > 0) {
				$subscription = $subscription_result->fetch_assoc();
				$start_date = $subscription['start_date'];
				$end_date = $subscription['end_date'];
				$today = date("Y-m-d");
				// Step 4: Check if subscription is active
				if ($today >= $start_date && $today <= $end_date) {
					if ($_SESSION['groupid'] == 2) {

						$row = execute("select id from hotels where user=$_SESSION[id]");

						$_SESSION['hotel'] = $row['id'];
					}

					if ($_SESSION['groupid'] == 3 || $_SESSION['groupid'] == 4) {

						$row = execute("select hotel_id,permission from hotel_staff where user_id=$_SESSION[id]");

						$_SESSION['hotel'] = $row['hotel_id'];

						$_SESSION['permission'] = $row['permission'];
					}
					// $conn->query("update users set lastlogin=now() where id=".$row['id']);
					if ($_SESSION['groupid'] == 1)

						header('Location:admin.php?Pg=myhotels');

					else if ($_SESSION['groupid'] == 2)

						header('Location:admin.php?Pg=bookingmap');

					else if ($_SESSION['groupid'] == 3 || $_SESSION['groupid'] == 4)

						header('Location:admin.php');
				} else {
					$msg = "Your subscription has expired.";
				}
			}
		} else {
			$msg = "No hotel found for this user.";
		}

		if ($_SESSION['groupid'] == 0)
			header('Location:admin.php?Pg=froteldash');
	} else
		$msg = "Login failed";
}



if (col('Pg') == "Logout") {

	session_destroy();

	$msg = "You are now logged out.";
} ?>

<html>

<head>

	<title>Login</title>

	<meta name="Generator" content="EditPlus">

	<meta name="description" content="">

	<meta name="keywords" content="">

	<meta name="author" content="Aspiring Web Solutions, Hrushikesh">

	<link rel='stylesheet' href='css/prop.css' type='text/css'>

	<link rel="stylesheet" href="css/bootstrap.css" type="text/css">

	<link rel='stylesheet' href='css/reset.css' type='text/css'>

	<link rel='stylesheet' href='css/demo.css' type='text/css'>

	<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

	<script type="text/javascript" src="js/bootstrap.js"></script>

	<script type="text/javascript" src="js/bootstrap.min.js"></script>



	<style type="text/css">
		.chkbx {
			width: 16px;
			height: 16px;
		}
	</style>

</head>



<body>

	<div id="wrapper">

		<div id="">

			<div id="header" class="container">

				<div class="row col-md-12">



				</div>

			</div>

		</div>

		<div id="secondary-wrapper">

			<div id="main-content" style="padding-top:200px" class="container">
				<div id="logo" class="col-md-5">

					<img src="images/logo.png" width="225" height="auto" style="margin-top: -60px; margin-left: 110px;" alt="">

				</div>
				<div id="login-bg">

					<div class="row col-md-12 f-sm">

						<div class="logged-in">

							<div class="col-md-12 f-sm">

								<div class="col-md-5 log-name padd-zero">LOG IN</div>

								<div class="col-md-7  padd-zero f-right">

									<p class="r-b padd-top-30 f-right">Recommended Browser <img class="v-bottom" src="images/chrome.png"></p>

								</div>

							</div>

							<form method="post" action="login.php">

								<div class="col-md-12 f-sm">

									<input type="text" name="email" id="email" onblur="if(this.value=='Mobile' || this.value=='') this.value='Mobile';" onfocus="if(this.value=='Mobile' || this.value=='') this.value='';" value="Mobile" class="log-field">

								</div>

								<div class="col-md-12 f-sm">

									<input type="text" name="pwd" id="pwd" onblur="if(this.value=='Password' || this.value==''){this.type='text';this.value='Password';}" onfocus="if(this.value=='Password' || this.value==''){this.type='password';this.value=''}" value="Password" class="log-field1" autocomplete="off">

								</div>

								<div class="col-md-12 f-sm">

									<input type="submit" value="Log in" class="b-log" style="margin-top: 11px;">

								</div>

							</form>

							<div class="col-md-12 create f-sm" style="color: rgb(241, 196, 5);">

								<?php echo $msg; ?>

							</div>

							<div class="col-md-12">

								<p class="r-b f-left padd-top-10">support@pmseasy.in</p>

								<p class="r-b f-right padd-top-10">+91-9540648648</p>


							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<!-- jQuery -->

	<script src="js/jquery-drop.min.js"></script>

	<script>
		$(function() {

			// Clickable Dropdown

			$('.click-nav > ul').toggleClass('no-js js');

			$('.click-nav .js ul').hide();

			$('.click-nav .js').click(function(e) {

				$('.click-nav .js ul').slideToggle(200);

				$('.clicker').toggleClass('active');

				e.stopPropagation();

			});

			$(document).click(function() {

				if ($('.click-nav .js ul').is(':visible')) {

					$('.click-nav .js ul', this).slideUp();

					$('.clicker').removeClass('active');

				}

			});

		});
	</script>

</body>

</html>