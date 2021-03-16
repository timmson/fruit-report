function drawVelocity(demand, wip, throughput, averageCycleTime) {
    const data = google.visualization.arrayToDataTable([
      ["Label", "Value"],
      ["Backlog", 0],
      ["WIP", 0],
      ["Avg. TPT", 0],
      ["Avg. CT", 0]
    ]);

    const options = {
      width: 1800, height: 500,
      redFrom: 90, redTo: 100,
      yellowFrom:75, yellowTo: 90,
      minorTicks: 5
    };

    const chart = new google.visualization.Gauge(document.getElementById("velocity"));

    chart.draw(data, options);

    let demandVal = 0;
    let wipVal = 0;
    let throughputVal = 0;
    let averageCycleTimeVal = 0;

    setInterval(function() {
      demandVal = demandVal<demand ? demandVal + 1 : demandVal;
      data.setValue(0, 1, demandVal);
      chart.draw(data, options);
    }, 100);

    setInterval(function() {
      wipVal = wipVal<wip ? wipVal + 1 : wipVal;
      data.setValue(1, 1, wipVal);
      chart.draw(data, options);
    }, 100);

    setInterval(function() {
      throughputVal = throughputVal<throughput ? throughputVal+1 : throughputVal;
      data.setValue(2, 1, throughputVal);
      chart.draw(data, options);
    }, 100);

    setInterval(function() {
      averageCycleTimeVal = averageCycleTimeVal < averageCycleTime ? averageCycleTimeVal+1 : averageCycleTimeVal;
      data.setValue(3, 1, averageCycleTimeVal);
      chart.draw(data, options);
    }, 100);

}

function drawCFD(source) {
  const data = new google.visualization.DataTable();
  data.addColumn("string", "Period End Date");
  data.addColumn("number", "Created");
  data.addColumn("number", "Started");
  data.addColumn("number", "Closed");
  data.addColumn("number", "WIP");
  data.addColumn("number", "Throughput");

  $.each(source, function(index, row) {
      data.addRow([row.period,
                    parseInt(row.created),
                    parseInt(row.started),
                    parseInt(row.closed),
                    parseInt(row.wip),
                    parseInt(row.throughput)]);
  });


  const options = {
    chart: {
      title: "Cumulative flow diagram",
      subtitle: "in Tasks"
    },
    width: 1800,
    height: 800,
    axes: {
      x: {
        0: {side: "bottom"}
      }
    }
  };

  const chart = new google.charts.Line(document.getElementById("cfd"));

  chart.draw(data, options);
}


function drawCC(source) {
    const data = new google.visualization.DataTable();

    data.addColumn("string", "Tasks");
    data.addColumn("number", "Cycle time");
    data.addColumn("number", "Trend");
    data.addColumn("number", "Rolling Average");

    $.each(source, function(index, row) {
        data.addRow([row.key, parseInt(row.ct), parseInt(row.trend), parseInt(row.avg)]);
    });

    const options = {
      title : "CT control chart",
      vAxis: {title: "Days"},
      hAxis: {title: "Tasks"},
      seriesType: "bars",
      series: {1: {type: "line"}, 2: {type: "line"}},
      width: 1800,
      height: 800,
    };

    const chart = new google.visualization.ComboChart(document.getElementById("cc"));
    chart.draw(data, options);

}

function drawHistogram(source) {
    const data = new google.visualization.DataTable();
    
    data.addColumn("string", "Tasks");
    data.addColumn("number", "Number of tasks");
    data.addColumn("number", "85p");
  
    $.each(source, function(index, row) {
      data.addRow([row.id.toString(), parseInt(row.count), parseInt(row.percentile)]);
    });

    const options = {
      title : "CT histogram",
      vAxis: {title: "Number of tasks"},
      hAxis: {title: "Days"},
      seriesType: "bars",
      //series: {1: {type: "line"}},
      width: 1800,
      height: 800,
      isStacked: true
    };

    const chart = new google.visualization.ComboChart(document.getElementById("ht"));
    chart.draw(data, options);
}

function drawOut(source) {
    var data = new google.visualization.DataTable();

    data.addColumn("string", "Issue");
    data.addColumn("number", "Cycle time");
    data.addColumn("string", "Started");
    data.addColumn("string", "Closed");
    
    $.each(source.tasks, function(index, row) {
      data.addRow([
            `<a href="${source.baseUrl}/browse/${row.key}?page=com.googlecode.jira-suite-utilities:transitions-summary-tabpanel" target="blank">${row.key}</a>`,
            parseInt(row.ct),
            row.startDate,
            row.endDate
        ]);
    });
    
    var table = new google.visualization.Table(document.getElementById("longest"));
    table.draw(data, {allowHtml:true, showRowNumber: false, width: "100%", height: "100%", sortColumn: 1, sortAscending: false});
}

function draw() {
    drawCFD(data.cfd);
    drawVelocity(data.backlog, data.wip, data.throughput, data.averageCycleTime);
    drawCC(data.cc);
    drawHistogram(data.ht);
    drawOut(data.longest);
}