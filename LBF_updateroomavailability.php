<?php include 'conn.php';
include 'udf.php';

if(!isset($_SESSION['groupid']))
{?>	
	<script type="text/javascript">
		window.parent.location="login.php";
	</script>
	<?php
}
else if(page_access("setroomtypes"))
{
	if(isset($_POST['act']))
	{
		$rdate		= $_GET['rdate'];
		$roomnumber	= $_GET['id'];
		$rpid		= $_GET['rpid'];

		if(isset($_POST['time_slots']))
		{
			if(sizeof($_POST['time_slots'])<48)
			{
				$time_slots			= $_POST['time_slots'];
				$clock				= Array();
				$blocked_ranges		= Array(Array("start","end"));
				$blocked_time_ranges= Array(Array("start","end"));
				
				for($i=0; $i<48; $i++)$clock[$i] = $i+1;
				
				$blocked_slots = array_diff($clock,$time_slots);
				//print_r($blocked_slots);
				$flag	= 0;
				$w		= 0;
				
				for($i=1; $i<=48; $i++)
				{
					if(in_array($i,$blocked_slots))
					{
						if(!$flag)
						{
							$blocked_ranges[$w]["start"]= $i;
							$blocked_ranges[$w]["end"]	= $i;
							$flag = 1;
						}
						else
						{
							if(!in_array($i+1,$blocked_slots))
							{
								$blocked_ranges[$w]["end"] = $i;
								$w++;
								$flag = 0;
							}
						}
					}
				}
				
				if($blocked_ranges)
				{
					foreach($blocked_ranges as $key => $blocked_range)
					{
						if(isset($blocked_range["start"]))
						{
							if(fmod($blocked_range["start"],2))
							{
								$s = floor(($blocked_range["start"]-1)/2).":00";
							}
							else
							{
								$s = floor(($blocked_range["start"]-1)/2).":30";
							}
							
							if(fmod($blocked_range["end"],2))
							{
								$e = floor(($blocked_range["end"]-1)/2).":30";
							}
							else
							{
								$e = (floor(($blocked_range["end"]-1)/2)+1==24?"0":floor(($blocked_range["end"]-1)/2)+1).":00";
							}
							
							$blocked_time_ranges[$key]["start"] = $s;
							$blocked_time_ranges[$key]["end"]	= $e;
						}
					}
				}
				
				$conn->query("delete from special_blocked_slots where rpid=$rpid and bdate='$rdate' and roomnumber=$id");
				
				foreach($blocked_time_ranges as $blocked_time_range)
				{
					if(isset($blocked_time_range["start"]))
						$conn->query("insert into special_blocked_slots (rpid,roomnumber,bdate,starttime,endtime) values ($rpid,$roomnumber,'$rdate', '".$blocked_time_range["start"]."','".$blocked_time_range["end"]."')");
				}
			}
			else
			{
				$conn->query("delete from special_blocked_slots where rpid=$rpid and bdate='$rdate' and roomnumber=$id");
				$conn->query("insert into special_blocked_slots (rpid,roomnumber,bdate,starttime,endtime) values ($rpid,$roomnumber,'$rdate', '00:00:00','00:00:00')");
			}?>
			<script type="text/javascript">
				parent.location.reload(true);
			</script>
			<?php
		}
		else
		{
			$conn->query("delete from special_blocked_slots where rpid=$rpid and bdate='$rdate' and roomnumber=$id");
			$conn->query("insert into blocked_roomnumbers (roomnumber,bdate) values ($roomnumber,'$rdate')");?>
			<script type="text/javascript">
				//window.parent.document.getElementById("toggle_status").value = 0;
				//window.parent.document.getElementById("d<?=$rdate?>").checked=0;
				//parent.location.reload(true);
				parent.location = "admin.php?Pg=specialrates";
			</script>
			<?php
		}
		
		$msg = "Room availability has been set";
		$clrclass = "success";?>
		<?php
	}?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;" onLoad="display_checked_slots()">
			<div class="the-box" style="padding-bottom:4px;margin-bottom:0px">
				<?php alertbox($clrclass,$msg);?>
				<h4 class="small-text">CHANGE ROOM AVAILABILITY FOR '<?=date_create($_GET['rdate'])->format("d-M-Y")?>'</h4>
				<form method="post" action="?rpid=<?=$_GET['rpid']?>&id=<?=$id?>&rdate=<?=$_GET['rdate']?>">
					<?php $sql = "select ts.slot,cast(ts.stime as time)stime,cast(ts.etime as time)etime,coalesce(sbs.id,bs.id,0)blocked, coalesce(sbs.starttime,bs.starttime,'00:00:00')starttime,coalesce(sbs.endtime,bs.endtime,'00:00:00')endtime from time_slots ts left join special_blocked_slots sbs on (sbs.starttime=cast(ts.stime as time) and sbs.roomnumber=$id and sbs.bdate='$_GET[rdate]' and sbs.rpid=$_GET[rpid]) left join blocked_slots bs on (bs.starttime=cast(ts.stime as time) and bs.rpid=$_GET[rpid] and bs.roomtype=(select roomtype from roomnumbers where id=$id)) left join rate_plans rp on (rp.id=bs.rpid and ('$_GET[rdate]' between rp.validfrom and rp.validto)) where not exists(select 1 from blocked_roomnumbers where roomnumber=$id and bdate='$_GET[rdate]')";
									
					$result = $conn->query($sql);
					if($result->num_rows > 0)
					{?>
						<div class="row">
							<div class="col-xs-12">
								<table class="table table-striped table-condensed" style="margin:auto">
									<tbody>
										<?php $flag = 0;
										while($row = $result->fetch_assoc())
										{
											$i = $row['slot'];
											if($row['blocked'] and !$flag)
											{
												$checked= "";
												$end	= $row['endtime'];
												$flag	= 1;
											}
											else if($flag and $row['etime']==$end)
											{
												$checked= "";
												$end	= "";
												$flag	= 0;
											}
											else if(!$flag)
											{
												$checked = "checked";
												$end	 = "";
											}
											else if($flag)
											{
												$checked= "";
											}
											else
											{
												$checked = "";
											}?>
											<tr class="cb_tr">
												<td class="cb_td" id="td1_<?=$i?>">
													<?php if(fmod($i,2))
													{?>
														<span class="time"><?=floor(($i-1)/2)?>:00</span>
														<?php
													}
													else
													{?>
														<span class="time">&nbsp;</span>
														<?php
													}?>
													<div class="checkboxOne">
														<input class="cb_cb" name="time_slots[]" type="checkbox" id="cb1_<?=$i?>" onChange="display_checked_slots()" title="" value="<?=$i?>" <?=$checked?> roomtype="1"/>
														<label for="cb1_<?=$i?>"></label>
													</div>
													<?php if(!fmod($i,2))
													{?>
														<span class="time"><?=floor(($i-1)/2)?>:30</span>
														<?php
													}
													else
													{?>
														<span class="time">&nbsp;</span>
														<?php
													}?>
												</td>
											</tr>
											<?php
										}?>
									</tbody>
								</table>
								<div class="row">
									<div class="col-xs-2">
										<div class="form-group">
											<label>Selected Slots</label>
											<div id="slots_list_1">
												<div>No slots are selected</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<input type="hidden" name="act" value="save">
								<input type="submit" class="btn btn-primary" value="Save">
							</div>
						</div>
						<?php
					}?>
				</form>
			</div>
		</body>
		<?php include("js.php");?>
	</html>
	<?php
}
ob_end_flush();?>