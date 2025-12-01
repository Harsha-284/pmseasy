<?php include("conn.php");
include("udf.php");

$output = "";

$row = execute("select target,title from promocodeids where id=$id");

$target = $row['target'];
$title = $row['title'];

if($target=="city")
	$sql = "select pci.type,pci.validfrom,pci.validto,pci.value,pci.usability,pci.target,c.city,pc.promocode,(case when pct.used=1 then 'Yes' else 'No' end)used from promocodeids pci join promocodes pc on pc.promocodeid=pci.id join promocodetargets pct on pct.promocodeid=pc.id join cities c on c.id=pct.targetid where pci.id=$id order by pct.targetid,pc.promocode";
else if($target=="hotel")
	$sql = "select pc.promocode,pci.type,pci.value,pci.usability,pci.validfrom,pci.validto,pci.target,u.company,(case when pct.used=1 then 'Yes' else 'No' end)used from promocodeids pci join promocodes pc on pc.promocodeid=pci.id join promocodetargets pct on pct.promocodeid=pc.id join hotels h on h.id=pct.targetid join users u on u.id=h.user where pci.id=$id order by pct.targetid,pc.promocode";
else if($target=="user")
	$sql = "select pc.promocode,pci.type,pci.value,pci.usability,pci.validfrom,pci.validto,pci.target,u.fullname,(case when pct.used=1 then 'Yes' else 'No' end)used from promocodeids pci join promocodes pc on pc.promocodeid=pci.id join promocodetargets pct on pct.promocodeid=pc.id join users u on u.id=pct.targetid where pci.id=$id order by pct.targetid,pc.promocode";
	
//echo $sql;

$result = $conn->query($sql);

$columns_total	= mysqli_num_fields($result);
$colnames		= array();

$x = 0;
while($fld = $result->fetch_field())
{
	$colnames[$x] = $fld->name;
	$output .= '"'.$fld->name.'",';
	$x++;
}
$output .="\n";

while ($row = $result->fetch_assoc())
{
	for ($i = 0; $i < $columns_total; $i++)
	{
		$output .='"'.$row[$colnames[$i]].'",';
	}
	$output .="\n";
}

$filename = $title.".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;?>