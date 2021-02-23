<?php

use ru\timmson\FruitReport\Math;

if ($_REQUEST['mode'] == 'async') {
    $conn = $CORE->getConnection($currentdep['props']);

    $team = 'TEAM-2';

    $output = "";
    switch ($_REQUEST['data_type']) {
        case 'cfd':
            $date_from = "date('2016-06-01')";
            $date_till = "date('now')";
            $data = $CORE->executeQuery($conn, "
					select date(d.x_date) x, 
						(select count(*) from tasks where team = t.team and date(create_date) <= d.x_date) created,
						(select count(*) from tasks where team = t.team and date(start_date) <= d.x_date) started,
						(select count(*) from tasks where team = t.team and date(uat_date) <= d.x_date) uat,
						(select count(*) from tasks where team = t.team and date(close_date) <= d.x_date) closed
					from date_list d, (select '" . $team . "' team) t 
						where d.x_date between " . $date_from . " and " . $date_till . " order by d.x_date
					");

            $cfd_data = array();
            for ($i = 0; $i < count($data); $i++) {
                $cfd_data[$i] = array('id' => $i,
                    'period' => $data[$i][0],
                    'created' => $data[$i][1],
                    'started' => $data[$i][2],
                    'throughput' => $data[$i][4] - $data[$i - 30][4],
                    'demand' => $data[$i][1] - $data[$i][2],
                    'wip' => $data[$i][2] - $data[$i][3],
                    'uat' => $data[$i][3],
                    'closed' => $data[$i][4]
                );
            }
            $output = json_encode($cfd_data);
            break;
        case 'cc':
            $data = $CORE->executeQuery($conn, "select key, round(julianday(uat_date) - julianday(start_date)) lead_time, 
					start_date, close_date from tasks where start_date is not null and close_date is not null 
					and team = '" . $team . "' order by close_date");

            $cc_data = array();
            $cc_data_linear = array();
            $max_lead = 0;
            for ($i = 0; $i < count($data); $i++) {
                $cc_data_linear[] = $data[$i][1];
            }

            $cc_data_avg = Math::rollingAverage($cc_data_linear, round(count($cc_data_linear) / 20));
            $percentil = Math::percentile($cc_data_linear, 0.85);
            $cc_data_linear = Math::trend($cc_data_linear);


            $period = 10;
            $out_data = array();
            for ($i = 0; $i < count($data); $i++) {
                $cc_data[] = array(
                    'id' => $i,
                    'key' => $data[$i][0] . ' [' . $data[$i][3] . ']',
                    'count' => $data[$i][1],
                    'trend' => $cc_data_linear[$i],
                    'avg' => $cc_data_avg[$i],
                );
                $max_lead = ($data[$i][1] > $max_lead) ? $data[$i][1] : $max_lead;

                if ($data[$i][1] > $percentil) {
                    $out_data[] = array(
                        'key' => $data[$i][0],
                        'count' => $data[$i][1],
                        'started' => $data[$i][2],
                        'closed' => $data[$i][3]
                    );
                }
            }

            //hist
            $hist_data = array();
            $step = 2;
            $above_percentil = false;
            for ($j = 1; $j <= ($max_lead + $step) / $step; $j++) {
                $hist_data[$j] = array('id' => $j * $step, 'count' => 0, 'percentil' => 0);
                for ($i = 0; $i < count($cc_data); $i++) {
                    if ($cc_data[$i]['count'] <= $j * $step && $cc_data[$i]['count'] > ($j - 1) * $step) {
                        $hist_data[$j]['count']++;
                    }
                }
                if (!$above_percentil && $j * $step >= $percentil) {
                    $hist_data[$j]['percentil'] = $hist_data[$j]['count'];
                    $above_percentil = true;
                }
            }
            $output = json_encode(array('cc_data' => $cc_data, 'hist_data' => $hist_data, 'out_data' => $out_data));
            break;

    }


    $CORE->closeConnection($conn);

    $VIEW->assign('data', $output);
}
