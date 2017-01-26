<?
$user = array("akrotov", "abobrikov", "nchugunov");
$conn = oci_connect(
    'dm_jira',
    'dev0jira',
    'srvdb64/MISC',
    'CL8MSWIN1251');
	$query="select PKEY, REPORTER, SUMMARY, UPDATED from JIRAISSUE where PROJECT in (10160, 10201, 10250) and ISSUESTATUS = (10002) order by UPDATED";
	$stid = oci_parse($conn, $query);
	$result = oci_execute($stid);
	for ($data=array(); $row=oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS); $data[]=$row);
	oci_free_statement($stid);
	print_r($data);
oci_close($conn);
?>
