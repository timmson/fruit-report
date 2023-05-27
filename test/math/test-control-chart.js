const controlChart = require("../../src/math/control-chart")

describe("ControlChart should", () => {

	test(" return data", () => {
		const arrange = [
			{key: "JIRA-1", ct: 10, endDate: "2010-10-25"},
			{key: "JIRA-2", ct: 14, endDate: "2010-10-25"},
			{key: "JIRA-3", ct: 11, endDate: "2014-10-25"},
			{key: "JIRA-3", ct: 12, endDate: "2011-10-25"},
			{key: "JIRA-4", ct: 10, endDate: "2012-10-25"},
		]

		const expected = [
			{key: "JIRA-1", ct: 10, avg: 11,trend: 1, endDate: "2010-10-25"},
			{key: "JIRA-2", ct: 14, avg: 11,trend: 2, endDate: "2010-10-25"},
			{key: "JIRA-3", ct: 11, avg: 11,trend: 3, endDate: "2014-10-25"},
			{key: "JIRA-3", ct: 12, avg: 12,trend: 4, endDate: "2011-10-25"},
			{key: "JIRA-4", ct: 10, avg: 11,trend: 5, endDate: "2012-10-25"}
		]

		const result = controlChart(arrange, [1, 1])

		expect(result).toEqual(expected)
	})

})
 