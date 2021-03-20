const MathHelper = require("../src/math-helper");

describe("MathHelper should", () => {

	test("return calculate Linear Approximation", () => {
		const arrange = [100, 110, 120, 130, 140, 150, 160, 170, 180, 190, 200];

		const result = MathHelper.linearApproximation(arrange);

		expect(result).toEqual([100, 10]);
	});

	test("return calculate Percentile", () => {
		const arrange = new Array(100).fill(0).map((c, i) => i);
		const percentile = 0.85;

		const result = MathHelper.percentile(arrange, percentile);

		expect(result).toEqual(85);
	});

	test("return Histogram", () => {
		const arrange = [100, 110, 120, 130, 140, 150, 150, 160, 170, 180, 200];
		const percentile = 85;

		const result = MathHelper.histogram(arrange, percentile);

		expect(result.length).toEqual(200);
	});

	test("return calculate Rolling Average", () => {
		const arrange = [100, 110, 120, 130, 140, 150, 160, 170, 180, 190, 200];
		const p = arrange.length / 2;

		const result = MathHelper.rollingAverage(arrange, 10, p);

		expect(result).toEqual(190);
	});

	test(" return ControlChart Data", () => {
		const arrange = [
			{key: "JIRA-1", ct: 10, endDate: "2010-10-25"},
			{key: "JIRA-2", ct: 14, endDate: "2010-10-25"},
			{key: "JIRA-3", ct: 11, endDate: "2014-10-25"},
			{key: "JIRA-3", ct: 12, endDate: "2011-10-25"},
			{key: "JIRA-4", ct: 10, endDate: "2012-10-25"},
		];

		const expected = [
			{key: "JIRA-1", ct: 10, avg: 11,trend: 1, endDate: "2010-10-25"},
			{key: "JIRA-2", ct: 14, avg: 11,trend: 2, endDate: "2010-10-25"},
			{key: "JIRA-3", ct: 11, avg: 11,trend: 3, endDate: "2014-10-25"},
			{key: "JIRA-3", ct: 12, avg: 12,trend: 4, endDate: "2011-10-25"},
			{key: "JIRA-4", ct: 10, avg: 11,trend: 5, endDate: "2012-10-25"}
		];

		const result = MathHelper.controlChart(arrange, [1, 1]);

		expect(result).toEqual(expected);
	});

});
 