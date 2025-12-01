<?php include("conn.php");

include("udf.php");



$output = "";



//$row = execute("select target,title from promocodeids where id=$id");



$target = $row['target'];

$title = $row['title'];



$sql= "SELECT * FROM `facilities` where id < 10";

	

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