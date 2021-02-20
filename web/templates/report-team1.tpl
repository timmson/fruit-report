<table class="container" style="font-size:8pt; widht:100%">
	<tr>
		<td style="text-aligin:center">
			<div id="result" style="font-size:18pt; align: 10px;">
				&nbsp;
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div style="width: 1800px; height: 500px; text-align:center;" id="velocity">
				<img src="{$factory->img_admin_dir}ajax-loader.gif"/>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div style="width: 1800px; height: 800px; text-align:center;" id="cumulative_flow">
				<img src="{$factory->img_admin_dir}ajax-loader.gif"/>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div style="width: 1800px; height: 800px; text-align:center;" id="control_chart">
				<img src="{$factory->img_admin_dir}ajax-loader.gif"/>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div style="width: 1800px; height: 800px; text-align:center;" id="histogram">
				<img src="{$factory->img_admin_dir}ajax-loader.gif"/>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<h4>Issues are above 85p</h4>
			<div id="out"></div>
		</td>
	</tr>
</table>
{literal}
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <script type="text/javascript">
    google.load('visualization', '1.1', {packages: ['line', 'corechart', 'gauge', 'table']});

    google.setOnLoadCallback(draw);
    
    function draw() {
		$.getJSON('.?dep={/literal}{$smarty.request.dep}{literal}&mode=async&data_type=cfd', function(source) {
			drawVelocity(source[source.length-1].demand, source[source.length-1].wip, source[source.length-1].throughput);
			drawCFD(source);
		});
		$.getJSON('.?dep={/literal}{$smarty.request.dep}{literal}&mode=async&data_type=cc', function(source) {
			drawCC(source.cc_data);
			drawHistogram(source.hist_data);
			drawOut(source.out_data);
		});
	}

	function drawVelocity(demand, wip, throughput) {
		var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Demand', 0],
          ['WIP', 0],
          ['Throughput', 0]
        ]);

        var options = {
          width: 1200, height: 500,
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('velocity'));

        chart.draw(data, options);

		var demandVal = 0;
		var wipVal = 0;
		var throughputVal = 0;
        setInterval(function() {
		  demandVal = demandVal<demand ? demandVal+1 : demandVal;
          data.setValue(0, 1, demandVal);
          chart.draw(data, options);
        }, 100);
        setInterval(function() {
		  wipVal = wipVal<wip ? wipVal+1 : wipVal;
          data.setValue(1, 1, wipVal);
          chart.draw(data, options);
        }, 100);
        setInterval(function() {
		  throughputVal = throughputVal<throughput ? throughputVal+1 : throughputVal;
          data.setValue(2, 1, throughputVal);
          chart.draw(data, options);
        }, 100);

	}


    function drawCFD(source) {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Period End Date');
      data.addColumn('number', 'Created');
      data.addColumn('number', 'Started');
      data.addColumn('number', 'UAT');
      data.addColumn('number', 'Closed');
      data.addColumn('number', 'WIP');
      data.addColumn('number', 'Throughput');

	  $.each(source, function(index, row) {
		  data.addRow([row.period, 
						parseInt(row.created),
						parseInt(row.started), 
						parseInt(row.uat), 
						parseInt(row.closed), 
						parseInt(row.wip), 
						parseInt(row.throughput)]);
	  });
      
      
      var options = {
        chart: {
          title: 'Cumulative flow diagram',
          subtitle: 'in BRs'
        },
        width: 1800,
        height: 800,
        axes: {
          x: {
            0: {side: 'bottom'}
          }
        }
      };

      var chart = new google.charts.Line(document.getElementById('cumulative_flow'));

      chart.draw(data, options);
    }
    
    function drawCC(source) {
		var data = new google.visualization.DataTable();
		
		data.addColumn('string', 'Tasks');
		data.addColumn('number', 'Cycle time');
		data.addColumn('number', 'Trend');
		data.addColumn('number', 'Rolling Average');
		
		$.each(source, function(index, row) {
		data.addRow([row.key, 
						parseInt(row.count),
						parseInt(row.trend), 
						parseInt(row.avg)
						]);
		});

		var options = {
		  title : 'CT control chart',
		  vAxis: {title: 'Days'},
		  hAxis: {title: 'Tasks'},
		  seriesType: 'bars',
		  series: {1: {type: 'line'}, 2: {type: 'line'}},
		  width: 1800,
          height: 800,
		};

		var chart = new google.visualization.ComboChart(document.getElementById('control_chart'));
		chart.draw(data, options);

	}
	
	function drawHistogram(source) {
		var data = new google.visualization.DataTable();
		
		data.addColumn('string', 'Tasks');
		data.addColumn('number', 'Number of tasks');
		data.addColumn('number', '85p');
      
        $.each(source, function(index, row) {
		  data.addRow([row.id.toString(), 
						parseInt(row.count),
						parseInt(row.percentil)
						]);
		});

		var options = {
		  title : 'CT histogram',
		  vAxis: {title: 'Number of tasks'},
		  hAxis: {title: 'Days'},
		  seriesType: 'bars',
		  //series: {1: {type: 'line'}},
		  width: 1800,
          height: 800,
          isStacked: true
		};

		var chart = new google.visualization.ComboChart(document.getElementById('histogram'));
		chart.draw(data, options);

	}
	
	function drawOut(source) {
		var data = new google.visualization.DataTable();
	
		data.addColumn('string', 'Issue');
		data.addColumn('number', 'Cycle time');
		data.addColumn('string', 'Started');
		data.addColumn('string', 'Closed');
		
		$.each(source, function(index, row) {
		  data.addRow([
			'<a href="http://jira.yourcompany.com/browse/'+row.key+'?page=com.googlecode.jira-suite-utilities:transitions-summary-tabpanel">'+row.key+'</a>',
			parseInt(row.count),
			row.started,
			row.closed
			]);
		});
		
        var table = new google.visualization.Table(document.getElementById('out'));
        table.draw(data, {allowHtml:true, showRowNumber: false, width: '100%', height: '100%', sortColumn: 1, sortAscending: false});

	}
    
  </script>
{/literal} 

