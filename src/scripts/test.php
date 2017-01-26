<?php
header("Content-type:application/pdf");
header("Content-Disposition:attachment;filename=downloaded.pdf");
$conn = oci_connect('capstone', 'caps', 'srvdb74/t01', 'CL8MSWIN1251');
$stid = oci_parse($conn, 'select OFERTA_DATA from WPS_CRB_OFERTA where RCLRCRDID = \'CRB2010210717480699\'');
$result = oci_execute($stid);
for ($data=array(); $row=oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS); $data[]=$row);
oci_free_statement($stid);
oci_close($conn);
print_r(($data[0]['OFERTA_DATA']->load()));
?>
