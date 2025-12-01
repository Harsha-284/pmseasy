<?php include 'conn.php';
include 'udf.php';?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box">
				<h4 class="small-title">SET FROTEL RATES</h4>
				<?php alertbox($clrclass,$msg);?>
				<form method="post" action="?Pg=frotelnormalrates">
					<div class="row">
						<div class="col-xs-12">
							<div class="col-xs-3">
								<label>Room Type</label>
								<select class="form-control" name="roomtype" onChange="form.action='?Pg=frotelnormalrates&act=roomtype';form.submit()">
									<option value="">--- Select Room Type ---</option>
									<?php $result = $conn->query("select id,roomtype from roomtypes where hotel=$_SESSION[hotel]");
									while($row=$result->fetch_assoc())
									{?>
										<option value="<?=$row['id']?>" <?=selected($row['id'],col("roomtype",0))?>><?=$row['roomtype']?></option>
										<?php
									}?>
								</select>
							</div>
							<?php if(col("roomtype")!="")
							{
								$roomtype = col("roomtype");
								$row = execute("select fulldaytariff from roomtypes where id=$roomtype");
								$fdt = $row['fulldaytariff'];?>
								<div class="col-xs-3">
									<div class="form-group">
										<label>Full Day Tariff</label>
										<input type="number" id="fdt" class="form-control" onChange="applyfdt()" onKeyup="applyfdt()" value="<?=$fdt?>">
									</div>
								</div>
								<div style="width:50%; float:left; height:70px;"></div>
								<?php
							}
							else
							{?>
								<div style="width:75%; float:left; height:70px;"></div>
								<?php
							}?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-3">
							<b>Day Timing</b> 09:00 AM To 07:00 PM
						</div>
						<div class="col-xs-9">
							<b>Night Timing</b> 07:00 PM To 09:00 AM
						</div>
					</div>
					<br>
					<?php if(col("roomtype")!="")
					{
						if(content_access("roomtypes",$roomtype))
						{
							$row = execute("select count(*)cnt from roomtypes r join hotelrates hr on hr.roomtype=r.id where hr.hours<>24 and r.hotel=$_SESSION[hotel] and hr.roomtype=$roomtype");

							if($row['cnt']==0)
								$result = $conn->query("select 1 as active,h.hour,h.wdefiner as wdrd,5 as wdrdnl,(h.wdefiner+5)werd,5 as werdnl, coalesce(hr.mon,(h.wdefiner/100)*$fdt)mon, coalesce(hr.tue,(h.wdefiner/100)*$fdt)tue, coalesce(hr.wed,(h.wdefiner/100)*$fdt)wed, coalesce(hr.thu,(h.wdefiner/100)*$fdt)thu, coalesce(hr.fri,(h.wdefiner/100)*$fdt)fri, coalesce(hr.sat,((h.wdefiner+5)/100)*$fdt)sat, coalesce(hr.sun,((h.wdefiner+5)/100)*$fdt)sun,coalesce(hr.monn,((h.wdefiner+5)/100)*$fdt)monn, coalesce(hr.tuen,((h.wdefiner+5)/100)*$fdt)tuen,coalesce(hr.wedn,((h.wdefiner+5)/100)*$fdt)wedn, coalesce(hr.thun,((h.wdefiner+5)/100)*$fdt)thun,coalesce(hr.frin,((h.wdefiner+5)/100)*$fdt)frin, coalesce(hr.satn,((h.wdefiner+10)/100)*$fdt)satn,coalesce(hr.sunn,((h.wdefiner+10)/100)*$fdt)sunn from (select 2 as hour,$roomtype as roomtype,20 as wdefiner union all select 3,$roomtype,25 union all select 4,$roomtype,30 union all select 5,$roomtype,35 union all select 6,$roomtype,40 union all select 7,$roomtype,45 union all select 8,$roomtype,50 union all select 9,$roomtype,55 union all select 10,$roomtype,60)h left join hotelrates hr on (h.hour=hr.hours and h.roomtype=hr.roomtype) order by h.hour");
							else
								$result = $conn->query("select hr.hours as hour,hr.wdrd,hr.wdrdnl,hr.werd,hr.werdnl,hr.mon,hr.tue,hr.wed,hr.thu,hr.fri, hr.sat,hr.sun,hr.monn,hr.tuen,hr.wedn,hr.thun,hr.frin,hr.satn,hr.sunn,hr.active from hotelrates hr where hr.roomtype=$roomtype and hr.hours<>24 order by hr.hours");?>
							<div style="border-style:solid; border-color:#ACB5BE; border-width:1px 0px 0px 1px; overflow:hidden;">
								<div style="height:60px;font-size:12px; font-weight:bold;">
									<div class="head1">
										<span class="txtblock"><u>No of Hours</u><br>Amenities/hr</span>
									</div>
									<div class="head2">
										<div class="head2b">
											<span class="txtblock">Mon</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Day</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Night</span>
										</div>
									</div>
									<div class="head2">
										<div class="head2b">
											<span class="txtblock">Tue</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Day</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Night</span>
										</div>
									</div>
									<div class="head2">
										<div class="head2b">
											<span class="txtblock">Wed</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Day</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Night</span>
										</div>
									</div>
									<div class="head2">
										<div class="head2b">
											<span class="txtblock">Thu</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Day</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Night</span>
										</div>
									</div>
									<div class="head2">
										<div class="head2b">
											<span class="txtblock">Fri</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Day</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Night</span>
										</div>
									</div>
									<div class="head2">
										<div class="head2b">
											<span class="txtblock">Sat</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Day</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Night</span>
										</div>
									</div>
									<div class="head2">
										<div class="head2b">
											<span class="txtblock">Sun</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Day</span>
										</div>
										<div class="head2s">
											<span class="txtblock">Night</span>
										</div>
									</div>
								</div>
								<?php while($row = $result->fetch_assoc())
								{?>
									<div class="frotelraterow">
										<div class="data1" style="background-color:;">
											<input type="checkbox" class="normalfrotelhours" name="hour<?=$row['hour']?>" <?=checked($row['active'],1)?> value="1"><div class="normalfrotelhorstext"><?=$row['hour']?></div>
										</div>
										<div class="data2">
											<input type="text" class="ratefield" id="monday<?=$row['hour']?>" name="monday<?=$row['hour']?>" style="border-width:0px 1px 0px 0px;" onKeyup="validatehotelrates('monday<?=$row['hour']?>')" value="<?=$row['mon']?>" required>
											<input type="text" class="ratefield" id="monnight<?=$row['hour']?>" name="monnight<?=$row['hour']?>"  onKeyup="validatehotelrates('monnight<?=$row['hour']?>')" value="<?=$row['monn']?>" required>
										</div>
										<div class="data2">
											<input type="text" class="ratefield" id="tueday<?=$row['hour']?>" name="tueday<?=$row['hour']?>" style="border-width:0px 1px 0px 0px;" onKeyup="validatehotelrates('tueday<?=$row['hour']?>')" value="<?=$row['tue']?>" required>
											<input type="text" class="ratefield" id="tuenight<?=$row['hour']?>" name="tuenight<?=$row['hour']?>" onKeyup="validatehotelrates('tuenight<?=$row['hour']?>')" value="<?=$row['tuen']?>" required>
										</div>
										<div class="data2">
											<input type="text" class="ratefield" id="wedday<?=$row['hour']?>" name="wedday<?=$row['hour']?>" style="border-width:0px 1px 0px 0px;" onKeyup="validatehotelrates('wedday<?=$row['hour']?>')" value="<?=$row['wed']?>" required>
											<input type="text" class="ratefield" id="wednight<?=$row['hour']?>" name="wednight<?=$row['hour']?>" onKeyup="validatehotelrates('wednight<?=$row['hour']?>')" value="<?=$row['wedn']?>" required>
										</div>
										<div class="data2">
											<input type="text" class="ratefield" id="thuday<?=$row['hour']?>" name="thuday<?=$row['hour']?>" style="border-width:0px 1px 0px 0px;" onKeyup="validatehotelrates('thuday<?=$row['hour']?>')" value="<?=$row['thu']?>" required>
											<input type="text" class="ratefield" id="thunight<?=$row['hour']?>" name="thunight<?=$row['hour']?>" onKeyup="validatehotelrates('thunight<?=$row['hour']?>')" value="<?=$row['thun']?>" required>
										</div>
										<div class="data2">
											<input type="text" class="ratefield" id="friday<?=$row['hour']?>" name="friday<?=$row['hour']?>" style="border-width:0px 1px 0px 0px;" onKeyup="validatehotelrates('friday<?=$row['hour']?>')" value="<?=$row['fri']?>" required>
											<input type="text" class="ratefield" id="frinight<?=$row['hour']?>" name="frinight<?=$row['hour']?>" onKeyup="validatehotelrates('frinight<?=$row['hour']?>')" value="<?=$row['frin']?>" required>
										</div>
										<div class="data2">
											<input type="text" class="ratefield" id="satday<?=$row['hour']?>" name="satday<?=$row['hour']?>" style="border-width:0px 1px 0px 0px;" onKeyup="validatehotelrates('satday<?=$row['hour']?>')" value="<?=$row['sat']?>" required>
											<input type="text" class="ratefield" id="satnight<?=$row['hour']?>" name="satnight<?=$row['hour']?>" onKeyup="validatehotelrates('satnight<?=$row['hour']?>')" value="<?=$row['satn']?>" required>
										</div>
										<div class="data2">
											<input type="text" class="ratefield" id="sunday<?=$row['hour']?>" name="sunday<?=$row['hour']?>" style="border-width:0px 1px 0px 0px;" onKeyup="validatehotelrates('sunday<?=$row['hour']?>')" value="<?=$row['sun']?>" required>
											<input type="text" class="ratefield" id="sunnight<?=$row['hour']?>" name="sunnight<?=$row['hour']?>" onKeyup="validatehotelrates('sunnight<?=$row['hour']?>')" value="<?=$row['sunn']?>" required>
										</div>
									</div>
									<?php
								}?>
							</div>
							<div style="margin-top:15px; height:30px; overflow:hidden;">
								<input type="submit" class="btn btn-primary" value="Save">
							</div>
							<?php
						}
					}?>
				</form>
			</div>
		</body>
		<?php include("js.php");?>
	</html>
	<?php

ob_end_flush();?>