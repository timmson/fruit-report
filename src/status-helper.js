class StatusHelper {

	constructor(config) {
		this.statuses = config.statuses;
	}

	isBacklog(task) {
		return this.statuses.start.includes(task.status);
	}

	isWIP(task) {
		return !(this.isBacklog(task) || this.isDone(task));
	}

	isDone(task) {
		return this.statuses.end.includes(task.status);
	}

}

module.exports = StatusHelper;