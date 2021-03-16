function parse(data) {
	const tasks = [];

	for (let i =0; i < data.issues.length; i++) {
		const issue = data.issues[i];
		const task = {"key": issue.key, "status": "Open", "history": []};

		if (issue.changelog.histories.length > 0) {
			task.history.push({"status" : "Open", "date" : new Date(issue.changelog.histories[0].created)});
			for (let j = 0; j < issue.changelog.histories.length; j++) {
				const history = issue.changelog.histories[j];
				for (let k = 0; k < history.items.length; k++) {
					const item = history.items[k];
					if (item.field === "status") {
						task.history.push({"status" : item.toString, "date" : new Date(history.created)});
						task.status = item.toString;
					}
				}
			}

			tasks.push(task);
		} else {
			//console.log(task.key);
		}

	}

	return tasks;
}

module.exports = parse;