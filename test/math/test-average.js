const {average, rollingAverage} = require("../../src/math/average");

describe("Average should", () => {

	test("return calculate Average", () => {
		const arrange = [100, 110, 120, 130, 140, 150, 160, 170, 180, 190, 200];

		const result = average(arrange);

		expect(result).toEqual(150);
	});

	test("return calculate Rolling Average", () => {
		const arrange = [100, 110, 120, 130, 140, 150, 160, 170, 180, 190, 200];
		const p = arrange.length / 2;

		const result = rollingAverage(arrange, 10, p);

		expect(result).toEqual(190);
	});

});
 