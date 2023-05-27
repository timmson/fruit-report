const linearApproximation = require("../../src/math/linear-approximation")

describe("Linear Approximation should", () => {

	test("return calculate b*x + a", () => {
		const arrange = [100, 110, 120, 130, 140, 150, 160, 170, 180, 190, 200]

		const result = linearApproximation(arrange)

		expect(result).toEqual([100, 10])
	})

})
 