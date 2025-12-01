<?php include 'conn.php';
include 'udf.php';

if(!isset($_SESSION['groupid']))
{?>	
	<script type="text/javascript">
		window.parent.location="login.php";
	</script>
	<?php
}
else if(page_access("promocodes"))
{?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php include("head.php");?>
		</head>
		<body style="padding-top:0px;">
			<div class="the-box" style="margin-bottom:0px;padding-bottom:0px;">
				<?php alertbox($clrclass,$msg);
				$row = execute("select * from promocodeids where id=$id");?>
				<h4 class="small-text">PROMOCODES</h4>
				<div class="row">
					<div class="col-xs-5">
						<div class="form-group">
							<label>Promocode Title</label>
							<div><?=$row['title']?></div>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<label>Valid From</label>
							<div><?=popdate($row['validfrom'])?></div>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
							<label>Valid Upto</label>
							<div><?=popdate($row['validto'])?></div>
						</div>
					</div>
					<div class="col-xs-1">
						<div class="form-group">
							<a href="csvexport.php?id=<?=$id?>"><img src="images/1475324306_application-vnd.ms-excel.png" width="32" height="32" border="0" alt=""></a>
						</div>
					</div>
				</div>
				<div class="table-responsive">	
					<table class="table table-th-block table-hover">
						<thead>
							<tr>
								<th>No.</th>
								<th>Promocode</th>
								<th>Type</th>
								<th>Value</th>
								<th>Usability</th>
								<th>Valid From</th>
								<th>Valid Upto</th>
								<th>Code For</th>
								<th><?php if($row['target']=="city")echo "City"; else if($row['target']=="hotel")echo "Hotel"; else if($row['target']=="user")echo "User"; else echo "General";?></th>
								<th>Used</th>
							</tr>
						</thead>
						<tbody>
							<?php if($row['target']=="city")
								$sql = "select pci.type,pci.validfrom,pci.validto,pci.value,pci.usability,pci.target,c.city,pc.promocode,(case when pct.used=1 then 'Yes' else 'No' end)used from promocodeids pci join promocodes pc on pc.promocodeid=pci.id join promocodetargets pct on pct.promocodeid=pc.id join cities c on c.id=pct.targetid where pci.id=$id order by pct.targetid,pc.promocode";
							else if($row['target']=="hotel")
								$sql = "select pc.promocode,pci.type,pci.value,pci.usability,pci.validfrom,pci.validto,pci.target,u.company,(case when pct.used=1 then 'Yes' else 'No' end)used from promocodeids pci join promocodes pc on pc.promocodeid=pci.id join promocodetargets pct on pct.promocodeid=pc.id join hotels h on h.id=pct.targetid join users u on u.id=h.user where pci.id=$id order by pct.targetid,pc.promocode";
							else if($row['target']=="user")
								$sql = "select pc.promocode,pci.type,pci.value,pci.usability,pci.validfrom,pci.validto,pci.target,u.fullname,(case when pct.used=1 then 'Yes' else 'No' end)used from promocodeids pci join promocodes pc on pc.promocodeid=pci.id join promocodetargets pct on pct.promocodeid=pc.id join users u on u.id=pct.targetid where pci.id=$id order by pct.targetid,pc.promocode";
							else
								$sql = "select pc.promocode,pci.type,pci.value,pci.usability,pci.validfrom,pci.validto,(case when pc.used=1 then 'Yes' else 'No' end)used,'General' as target from promocodeids pci join promocodes pc on pc.promocodeid=pci.id where pci.id=$id order by pc.promocode";
							
							//echo $sql;
							$result = $conn->query($sql);
							$i=1;
							while($row=$result->fetch_assoc())
							{?>
								<tr>
									<td><?=$i?></td>
									<td><?=$row['promocode']?></td>
									<td><?=$row['type']?></td>
									<td><?=$row['value']?></td>
									<td><?=$row['usability']?></td>
									<td><?=$row['validfrom']?></td>
									<td><?=$row['validto']?></td>
									<td><?=$row['target']?></td>
									<?php if($row['target']=="hotel")
									{?>
										<td><?=$row['company']?></td>
										<?php
									}
									else if($row['target']=="city")
									{?>
										<td><?=$row['city']?></td>
										<?php
									}
									else if($row['target']=="user")
									{?>
										<td><?=$row['fullname']?></td>
										<?php
									}
									else
									{?>
										<td>-</td>
										<?php
									}?>
									<td><?=$row['used']?></td>
								</tr>
								<?php $i++;
							}?>
						</tbody>
					</table>
				</div>
			</div>
		</body>
		<?php include("js.php");?>
	</html>
	<?php
}
ob_end_flush();?>