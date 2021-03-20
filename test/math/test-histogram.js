const histogram = require("../../src/math/histogram");

describe("Histogram should", () => {

	test("return data", () => {
		const arrange = [100, 110, 120, 130, 140, 150, 150, 160, 170, 180, 200];
		const percentile = 85;

		const result = histogram(arrange, percentile);

		expect(result.length).toEqual(200);
	});

});
 