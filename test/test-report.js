const report = require("../src/report")

describe(" Report should", () => {

	test("return data", () => {
		const config = {
			protocol: "https",
			host: "jira.atlassian.com",
			query: "project = ALL",
			statuses: {
				start: ["Open"],
				end: ["Closed"]
			}
		}

		const arrange = {
			issues: [
				{
					key: "JIRA-1", changelog: {
						histories: [
							{created: new Date("2015-03-20"), items: [{field: "status", toString: "In Progress"}]},
							{created: new Date("2015-03-25"), items: [{field: "status", toString: "Closed"}]}
						]
					}
				},
				{
					key: "JIRA-2",
					changelog: {
						histories: [
							{created: new Date("2015-03-22"), items: [{field: "status", toString: "In Progress"}]},
						]
					}
				}
			]
		}

		const result = report(config, arrange)

		expect(result.cc).toHaveLength(1)
		expect(result).toBeDefined()
	})

})
