<?php
/*$conn = $CORE->getConnection($currentdep['props']);

//cfd
$data = $CORE->executeQuery($conn, "
select 
    to_char(create_date, 'yyyy-mm') x,
    (select count(*) from tasks_ers where to_char(create_date,'yyyy-mm') <= to_char(t.create_date, 'yyyy-mm')) created,
    (select count(*) from tasks_ers where to_char(start_date,'yyyy-mm') <= to_char(t.create_date, 'yyyy-mm')) started,
    (select count(*) from tasks_ers where to_char(uat_date,'yyyy-mm') <= to_char(t.create_date, 'yyyy-mm')) uat,
    (select count(*) from tasks_ers where to_char(close_date,'yyyy-mm') <= to_char(t.create_date, 'yyyy-mm')) closed
from 
     tasks_ers t 
group by to_char(create_date, 'yyyy-mm') order by to_char(create_date, 'yyyy-mm')
");
$cfd_data = array();
for ($i=0; $i < count($data); $i++) {
	$cfd_data[$i] = array('id' => $i, 
						  'created' => $data[$i]['CREATED'], 
						  'started' => $data[$i]['STARTED'], 
						  'throughtput' => $data[$i]['CLOSED'] - $data[$i-1]['CLOSED'],
						  'demand' => $data[$i]['CREATED'] - $data[$i]['STARTED'],
						  'wip' => $data[$i]['STARTED']-$data[$i]['UAT'], 
						  'uat' => $data[$i]['UAT'], 
						  'closed' => $data[$i]['CLOSED']
					);
}	

//cc
$data = $CORE->executeQuery($conn, "select issuekey, round(close_date - start_date) LEAD_TIME from tasks_ers where create_date > to_date('2014-01-01', 'yyyy-mm-dd') and start_date is not null and close_date is not null  order by close_date");
$cc_data = array();
$max_lead = 0;
for ($i=0; $i < count($data); $i++) {
	$cc_data[$i] = array('id' => $i, 'count' => $data[$i]['LEAD_TIME']);
	$max_lead = ($data[$i]['LEAD_TIME'] > $max_lead) ? $data[$i]['LEAD_TIME'] : $max_lead;
}

//hist
$hist_data = array();
$step = 5;
for ($j=1; $j<=$max_lead/$step; $j++ ){
	$hist_data[$j] = array('id' => $j*$step, 'count' => 0);
	for ($i=0; $i<count($cc_data); $i++) {
		if ($cc_data[$i]['count'] <= $j*$step &&$cc_data[$i]['count'] > ($j-1)*$step) {
			$hist_data[$j]['count']++;
		}
	}
}

$CORE->closeConnection($conn);

$VIEW->assign('cc_data', $cc_data);
$VIEW->assign('hist_data', $hist_data);
$VIEW->assign('cfd_data', $cfd_data);*/

