const percentile = require("../../src/math/percentile");

describe("Percentile should", () => {

	test("return calculate", () => {
		const arrange = new Array(100).fill(0).map((c, i) => i);
		const p = 0.85;

		const result = percentile(arrange, p);

		expect(result).toEqual(85);
	});

});
 