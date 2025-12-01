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
			<title>Blank Page | AWS - Admin Panel</title>
			
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
	 
		<body style="padding-top:10px">
			<?php $row = execute("select company from users where id=$id");?>
			<div class="col-xs-12">
				<h4>HOTELS IN <?=strtoupper($row['company'])?></h4>
				<div class="row">
					<?php $result = $conn->query("select u.company,coalesce(p.path,'')path,c.city,l.location,u.address1,u.address2,h.stars,u.email,u.contact, h.fulldescription from hotels h left join users u on u.id=h.user left join pictures p on (p.propertyid=h.id and p.type='Hotel' and title='Hotel Profile Picture') left join locations l on h.location=l.id left join cities c on c.id=l.city where h.admin=$id");
					
					if($result->num_rows > 0)
					{
						while($row = $result->fetch_assoc())
						{
							if($row['path']=="")
								$path = "images/NOIMAGE568555985.png";
							else
								$path = "images/propertypics/hotel/medium/".$row['path'];?>
							
							<div class="the-box no-border property-list">
								<div class="media">
									<a class="pull-left" href="javascript:void(0)"><img alt="image" class="property-image img-responsive" src="<?=$path?>"></a>
									<div class="clearfix visible-xs"></div>
									<div class="media-body">
										<h4 class="media-heading">
											<a href="javascript:void(0)">
												<strong><?=$row['company']?></strong>
												<small class="pull-right"><?=$row['city']?></small>
											</a>
										</h4>
										<ul class="list-inline">
											<li>
												<?php for($i=1; $i<=$row['stars']; $i++)
												{?>
													<i class="fa fa-star text-warning"></i>
													<?php
												}
												for($i=($row['stars']+1); $i<=5; $i++)
												{?>
													<i class="fa fa-star text-default"></i>
													<?php
												}?>
											</li>
											<li style="list-style: none">|</li>
											<li><?=$row['contact']?></li>
											<li style="list-style: none">|</li>
											<li><?=$row['email']?></li>
										</ul>
										<p class="hidden-xs"><?=$row['fulldescription']?></p>
										<span class="small text-muted"><?=$row['address1'].", ".$row['address2']?></span>
									</div>
								</div>
							</div>
							<?php
						}
					}
					else
						echo "<div class='col-xs-12'>No hotels in the chain</div>";?>
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