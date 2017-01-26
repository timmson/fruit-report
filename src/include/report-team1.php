<?
if ($_REQUEST['mode'] == 'async') {
	$conn = $CORE->getConnection($currentdep['props']);

	$team = 'TEAM-1';

	$output = "";
	switch($_REQUEST['data_type']) {
			case 'cfd':
					$date_from = "to_date('2015-01-01', 'yyyy-mm-dd')";
					$date_till = "trunc(sysdate)";
					$data = $CORE->executeQuery($conn, "
					select to_char(d.x_date, 'yyyy-mm-dd') x, 
						(select count(*) from tasks where team = t.team and trunc(create_date) <= d.x_date) created,
						(select count(*) from tasks where team = t.team and trunc(start_date) <= d.x_date) started,
						(select count(*) from tasks where team = t.team and trunc(uat_date) <= d.x_date) uat,
						(select count(*) from tasks where team = t.team and trunc(close_date) <= d.x_date) closed
					from date_list d, (select '".$team."' team from dual) t 
						where d.x_date between ".$date_from." and ".$date_till." order by d.x_date
					");

					$cfd_data = array();
					for ($i=0; $i < count($data); $i++) {
						$cfd_data[$i] = array('id' => $i, 
											  'period' => $data[$i]['X'],
											  'created' => $data[$i]['CREATED'], 
											  'started' => $data[$i]['STARTED'], 
											  'throughput' => $data[$i]['CLOSED'] - $data[$i-30]['CLOSED'],
											  'demand' => $data[$i]['CREATED'] - $data[$i]['STARTED'],
											  'wip' => $data[$i]['STARTED']-$data[$i]['UAT'], 
											  'uat' => $data[$i]['UAT'], 
											  'closed' => $data[$i]['CLOSED']
										);
					}
					$output = json_encode($cfd_data);
					break; 
		  case 'cc':
					$data = $CORE->executeQuery($conn, "select issuekey, round(uat_date - start_date) LEAD_TIME, 
					start_date, close_date from tasks where start_date is not null and close_date is not null 
					and team = '".$team."' order by close_date");

					$cc_data = array();
					$cc_data_linear = array();
					$max_lead = 0;
					for ($i = 0; $i < count($data); $i++) {
						$cc_data_linear[] = $data[$i]['LEAD_TIME'];
					}

					$cc_data_avg = Math::rollingAvg($cc_data_linear, round(count($cc_data_linear)/20));
					$percentil = Math::percentil($cc_data_linear, 0.85);
					$cc_data_linear = Math::trend($cc_data_linear);


					$period = 10;
					$out_data = array();
					for ($i = 0; $i < count($data); $i++) {
						$cc_data[] = array(
							'id' => $i, 
							'key' => $data[$i]['ISSUEKEY'].' ['.$data[$i]['CLOSE_DATE'].']',
							'count' => $data[$i]['LEAD_TIME'], 
							'trend' => $cc_data_linear[$i], 
							'avg' => $cc_data_avg[$i]
						);
						$max_lead = ($data[$i]['LEAD_TIME'] > $max_lead) ? $data[$i]['LEAD_TIME'] : $max_lead;
						
						if ($data[$i]['LEAD_TIME'] > $percentil) {
							$out_data[] = array(
								'key' => $data[$i]['ISSUEKEY'], 
								'count' => $data[$i]['LEAD_TIME'], 
								'started' => $data[$i]['START_DATE'], 
								'closed' => $data[$i]['CLOSE_DATE']
							);
						}
					}

					//hist
					$hist_data = array();
					$step = 2;
					$above_percentil = false;
					for ($j=1; $j<=($max_lead+$step)/$step; $j++ ){
						$hist_data[$j] = array('id' => $j*$step, 'count' => 0, 'percentil' => 0);
						for ($i=0; $i<count($cc_data); $i++) {
							if ($cc_data[$i]['count'] <= $j*$step && $cc_data[$i]['count'] > ($j-1)*$step) {
								$hist_data[$j]['count']++;
							}
						}
						if (!$above_percentil && $j*$step>=$percentil) {
							$hist_data[$j]['percentil'] = $hist_data[$j]['count'];
							$above_percentil = true;
						}
					}
					$output = json_encode(array('cc_data' => $cc_data, 'hist_data' => $hist_data, 'out_data' => $out_data));
					break;
         case 'delta' :
                    $data = $CORE->executeQuery($conn, "select round(avg(uat_date - start_date), 2) ct, count(*) count, to_char(close_date,'yyyy') y 
						from tasks where start_date is not null and 
						to_char(close_date,'yyyy') >= to_char(sysdate,'yyyy')-1 and team = '".$team."' 
						group by to_char(close_date,'yyyy') order by to_char(close_date,'yyyy')");
					$output = json_encode($data);
					break;					
					
	}
	

	$CORE->closeConnection($conn);

	$VIEW->assign('data', $output);
}
?>
