<?
$user = array('akrotov', 'nchugunov', 'ext_nvasileva');
//$user = array('ext_nvasileva');
$conn = oci_connect(
    'dm_jira',
    'dev0jira',
    'srvdb64/MISC',
    'CL8MSWIN1251');
for ($j=0; $j<count($user); $j++) {
	$query="select i.PKEY, i.SUMMARY, s.PNAME
	from JIRAISSUE i, ISSUESTATUS s
	where (i.ASSIGNEE = '".$user[$j]."' and (trunc(i.UPDATED) > trunc(sysdate-5) or i.ISSUESTATUS in (1,3,4,9)) or PKEY in ('REL-95', 'REL-1976')) and i.ISSUESTATUS = s.ID 
	order by i.PRIORITY desc";
	$stid = oci_parse($conn, $query);
	$result = oci_execute($stid);
	for ($data=array(); $row=oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS); $data[]=$row);
	oci_free_statement($stid);
	$body ="<html><h3>Текущие задачи на ".date("d.m.Y")."</h3>";
	$body.= "<h4>Шаблоны [Date: ".date('d.m.Y')." 01:00]";
	$body.= "&nbsp;[Date: ".date('d.m.Y')." 02:00]";
	$body.= "&nbsp;[Date: ".date('d.m.Y')." 04:00]";
	$body.= "&nbsp;[Date: ".date('d.m.Y')." 08:00]</h4>";
	$body.="<table border=\"1\" cellpadding=\"5\">";
	for ($i=0; $i<count($data); $i++) {
		$body .= "<tr><td>";
		$body .= "<a href=\"http://it.rccf.ru/jira/browse/".$data[$i]['PKEY']."\">".$data[$i]['PKEY']."</a>";
		$body .= "</td><td>".$data[$i]['SUMMARY']."</td><td>".$data[$i]['PNAME'];
		$body .= "</td></tr>";
	}
	$body .="</table></html>";
	$sender = "tasknotify@recnredit.ru";
	$email = $user[$j]."@rencredit.ru";
	$str = "Отметится в Jire";
	$headers = "From: ".$sender . "\n".
	            "Content-Type: text/html; charset=windows-1251\n".
	            "X-Mailer: PHP/" . phpversion();
	mail($email, "[jira]Отметиться в Jira ".date("d.m.Y"), $body, $headers);
	echo $body;
}
oci_close($conn);
?>
