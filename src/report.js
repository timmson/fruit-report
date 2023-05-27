const {average, controlChart, histogram, linearApproximation, percentile} = require("./math-helper")
const StatusHelper = require("./status-helper")
const transform = require("./transform")

function compareTasks(t1, t2) {
	return t1.endDate.getTime() > t2.endDate.getTime() ? 1 : -1
}

function report(config, data) {
	const statusHelper = new StatusHelper(config)
	const tasks = transform(data, config.statuses.start[0])

	const createdTasks = tasks.map((t) => {
		t.createDate = t.history[0].date

		if (!statusHelper.isBacklog(t)) {
			t.startDate = t.history[1].date
		}

		if (statusHelper.isDone(t)) {
			t.endDate = t.history[t.history.length - 1].date
			t.ct = Math.max(1, Math.ceil((t.endDate - t.startDate) / (1000 * 60 * 60 * 24)))
		}
		return t
	})

	const completedTasks = createdTasks.filter((t) => statusHelper.isDone(t)).sort(compareTasks)

	const currentDate = new Date()
	const averageCycleTime = average(completedTasks.map((t) => t.ct))
	const abLinear = linearApproximation(completedTasks.map((t) => t.ct))
	const percentile85 = percentile(completedTasks.map((t) => t.ct), 0.85)

	const cfd = []
	const threeMonthsAgoDate = new Date(currentDate.getTime())
	threeMonthsAgoDate.setMonth(threeMonthsAgoDate.getMonth() - 3)
	for (let d = threeMonthsAgoDate.getTime(); d <= currentDate.getTime(); d+= 1000 * 60 * 60 * 24) {
		const cfdEntity = {
			period: new Date(d).toISOString().slice(0, 10),
			created: createdTasks.filter((t) => new Date(t.createDate).getTime() <= d).length,
			started: createdTasks.filter((t) => new Date(t.startDate).getTime() <= d).length,
			closed: createdTasks.filter((t) => new Date(t.endDate).getTime() <= d).length
		}
		cfdEntity.wip = cfdEntity.started - cfdEntity.closed
		cfdEntity.throughput = cfd.length > 7 ? cfdEntity.closed - cfd[cfd.length - 7].closed : 0
		cfd.push(cfdEntity)
	}

	const longestTasks = completedTasks.filter((t) => t.ct >= percentile85)

	return {
		url:`${config.protocol}://${config.host}/issues/?jql=${encodeURI(config.query)}`,
		query: config.query,
		team: config.team,
		date: currentDate.toDateString(),
		total: tasks.length,
		backlog: cfd[cfd.length-1].created - cfd[cfd.length-1].started,
		wip: cfd[cfd.length-1].wip,
		done: cfd[cfd.length-1].closed,
		throughput: cfd[cfd.length-1].closed - cfd[cfd.length > 30 ? cfd.length - 31 : 0].closed,
		averageCycleTime: averageCycleTime,
		percentile85: percentile85,
		linearApproximation: abLinear,
		cfd: cfd,
		cc: controlChart(completedTasks, abLinear),
		ht: histogram(completedTasks.map((t) => t.ct), percentile85),
		longest: {
			tasks: longestTasks,
			url: `${config.protocol}://${config.host}/issues/?jql=issuekey in (${longestTasks.map((t) => t.key).join(",")})`,
			baseUrl: `${config.protocol}://${config.host}`
		}
	}
}

module.exports = report