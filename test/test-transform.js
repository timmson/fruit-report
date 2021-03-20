const transform = require("../src/transform");

describe("Transform should", () => {

	test("return data", () => {
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
		};

		const expected = [
			{
				key: "JIRA-1", status: "Closed",
				history: [
					{status: "New", date: new Date("2015-03-20")},
					{status: "In Progress", date: new Date("2015-03-20")},
					{status: "Closed", date: new Date("2015-03-25")}
				]
			},
			{
				key: "JIRA-2", status: "In Progress",
				history: [
					{status: "New", date: new Date("2015-03-22")},
					{status: "In Progress", date: new Date("2015-03-22")},
				]
			}
		];

		const result = transform(arrange, "New");

		expect(result).toEqual(expected);
	});

});