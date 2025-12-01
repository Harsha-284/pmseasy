<?php include("conn.php");
include("udf.php");

$output = "";
// $report = $_GET['report'];

// if($report == "enquiries")
// 	$sql = "select e.id,er.fullname,er.contact,(case when cast(er.dob as char)='0000-00-00' then '' else cast(er.dob as char) end)dob,(case when cast(er.doa as char)='0000-00-00' then '' else cast(er.doa as char) end)doa,er.address1,er.address2,er.city,er.pincode,er.state,m.mediatype,group_concat(p.project)projects, group_concat(c.configuration)configuration,group_concat(pc.area)area,(case when cast(e.followup1 as char)='0000-00-00 00:00:00' then '' else cast(e.followup1 as char) end)followup1,(case when cast(e.followup2 as char)='0000-00-00 00:00:00' then '' else cast(e.followup2 as char) end)followup2,(case when cast(e.followup3 as char)='0000-00-00 00:00:00' then '' else cast(e.followup3 as char) end)followup3,e.dnd,e.fbq1,e.fbq2,e.fbq3,e.fbq4,e.fbq5,e.review,l.fullname as representative from enquiries e join enquiry_details ed on e.id=ed.enquiryid left join enquirers er on e.enquirerid=er.id left join project_configurations pc on pc.id=ed.pcid left join projects p on p.id=pc.projectid left join configurations c on c.id=pc.configurationid left join logins l on l.id=e.salespersonid left join mediatypes m on m.id=er.mediatype group by e.id,er.fullname,er.contact,e.office,e.reg_date, e.followup1,e.followup2,e.followup3,e.dnd";

// if($report == "purchases")
// 	$sql = "select coalesce(e1.id,e2.id,0)enquiryid,coalesce(err1.fullname,err2.fullname)fullname, coalesce(pc1.area,pc2.area)area,coalesce(c1.configuration,c2.configuration)configuration,coalesce(p1.project,p2.project)project,pur.flatno,pur.purchasedate,(case when cast(pur.possessiondate as char)='0000-00-00' then 'No Possession' else cast(pur.possessiondate as char) end)possessiondate  from purchases pur left join enquirers err1 on err1.id=pur.enquirerid left join enquiry_details ed on ed.id=pur.edid left join enquiries e1 on e1.id=ed.enquiryid left join enquirers err2 on err2.id=e1.enquirerid left join enquiries e2 on e2.enquirerid=err2.id left join project_configurations pc1 on ed.pcid=pc1.id left join project_configurations pc2 on pc2.id=pur.pcid left join configurations c1 on pc1.configurationid=c1.id left join configurations c2 on pc2.configurationid=c2.id left join projects p1 on p1.id=pc1.projectid left join projects p2 on p2.id=pc2.projectid";

//change query here///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//$sql = "SELECT h.id,u.company,u.address1,u.address2,c.city,s.state,ct.country,u.contact,u.email,h.minidescription,h.fulldescription,h.latitude,h.longitude FROM hotels h left join users u on h.user = u.id left join cities c on c.id= u.city left join states s on c.state = s.id left join countries ct on s.country = ct.id where 1";

//$sql = "SELECT vr.hotel as hotelname,vr.roomtype as roomname,'' as cancellation_description,'dont know' as room_images,vr.id as room_code,'dont know' max_accomodtion,vr.adults as max_adults,vr.children as max_children,vr.fulldaytariff as room_price_per_day,'dont know' as discount,'dont know' as room_total_price,'dont know' as room_amenities_id,vr.hotelid FROM view_roomtypes vr ";

 $sql = "SELECT h.id,u.company,hf.facility as amenityid,f.facility as amenity FROM  hotels h left join users u on h.user = u.id left join hotel_facilities hf on h.id = hf.hotel left join facilities f on f.id = hf.facility WHERE 1 ";

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

$filename = "hotel_amenities.csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);

echo $output;
exit;?>