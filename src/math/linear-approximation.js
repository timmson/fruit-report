function linearApproximation(data) {
	const size = data.length;

	const xData = [];
	for (let i = 0; i < size; i++) {
		xData.push(i);
	}

	const xSum = xData.reduce((c, a) => c + a, 0);
	const ySum = data.reduce((c, a) => c + a, 0);
	const x2Sum = xData.map((x) => x * x).reduce((c, a) => c + a, 0);
	const xySum = xData.map((x) => x * data[x]).reduce((c, a) => c + a, 0);

	const xsr = xSum / size;
	const ysr = ySum / size;

	const b = (xySum - size * xsr * ysr) / (x2Sum - size * xsr * xsr);
	const a = ysr - b * xsr;

	return [a, b];
}

module.exports = linearApproximation;