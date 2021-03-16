const StatusHelper = require("../src/status-helper");

describe("StatusHelper should", () => {

	const config = {
		statuses: {
			start : ["Open"],
			end : ["Closed", "Resolved"]
		}
	};

	const statusHelper = new StatusHelper(config);

	test("return true if status is initial", () => {
		const arrange = {status: "Open"};

		expect(statusHelper.isBacklog(arrange)).toBeTruthy();
		expect(statusHelper.isWIP(arrange)).toBeFalsy();
		expect(statusHelper.isDone(arrange)).toBeFalsy();
	});

	test("return true if status is in progress", () => {
		const arrange = {status: "Development"};

		expect(statusHelper.isBacklog(arrange)).toBeFalsy();
		expect(statusHelper.isWIP(arrange)).toBeTruthy();
		expect(statusHelper.isDone(arrange)).toBeFalsy();
	});

	test("return true if status is final", () => {
		const arrange = {status: "Resolved"};

		expect(statusHelper.isBacklog(arrange)).toBeFalsy();
		expect(statusHelper.isWIP(arrange)).toBeFalsy();
		expect(statusHelper.isDone(arrange)).toBeTruthy();
	});

});