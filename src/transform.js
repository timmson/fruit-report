function transform(data, startStatus) {
	return data.issues.filter((issue) => issue.changelog.histories.length > 0).map((issue) => {
		const task = {key: issue.key, status: startStatus, history: [{status: startStatus, date: new Date(issue.changelog.histories[0].created)}]}

		issue.changelog.histories.forEach((history) =>
			history.items.filter((item) => item.field === "status").forEach((item) => {
				task.history.push({status: item.toString, date: new Date(history.created)})
				task.status = item.toString
			})
		)

		return task
	})
}

module.exports = transform
