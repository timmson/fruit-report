$(() => {
    $("#team").val(data.team);
    $("#query").val(data.query);
    $("#title").html(`Метрики на ${data.date}. Открыть выборку в <a target="blank" href="${data.url}">Jira</a>`);
    $("#description1").html(`Команда завершит все ${data.wip} задач(у) в течение ~${Math.ceil(data.wip/data.throughput)} месяца(ев) при текущей производительности в ${data.throughput} шт./мес.`);
    $("#description2").html(`Линейная аппроксимация: y = ${data.linearApproximation[1]}*x + ${data.linearApproximation[0]}, т.е. время цикла ${data.linearApproximation[1] > 0 ? 'растёт':'падает'}.`);
    $("#description3").html(`Среднее время выполнения 1 задачи ~ ${data.averageCycleTime} дней(я), а ${data.percentile85} дней(я) - с вероятностью 85%`);
    $("#description4").html(`Задачи, над которыми работали дольше 85% всей выборки. Открыть выборку в <a target="blank" href="${data.longest.url}">Jira</a>`);

    google.load("visualization", "1.1", {packages: ["line", "corechart", "gauge", "table"]});
    
    google.setOnLoadCallback(draw);
});

$("#submit").click(() => {
    const team = $("#team").val();
    const query = $("#query").val();
    window.location.href = `/fetch?team=${team}&query=${query}`;
});
