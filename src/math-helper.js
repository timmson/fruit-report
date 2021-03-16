class MathHelper {

	static linearApproximation(data) {
		const size = data.length;

		const xData = [];
		for (let i = 0; i < size; i++) {
			xData.push(i);
		}

		const xSum = xData.reduce((c,a) => c + a, 0);
		const ySum = data.reduce((c,a) => c + a, 0);
		const x2Sum = xData.map((x) => x * x).reduce((c,a) => c + a, 0);
		const xySum = xData.map((x) => x * data[x]).reduce((c,a) => c + a, 0);

		const xsr = xSum / size;
		const ysr = ySum / size;

		const b = (xySum - size * xsr * ysr) / (x2Sum - size * xsr * xsr);
		const a = ysr - b * xsr;

		return [a, b];
	}

	static percentile(data, percentile) {
		const result = data.sort((a,b) => a == b ? 0 : (a > b ? 1 : -1));
		return result[Math.floor(data.length * percentile)];
	}

	static histogram(data, percentile) {
		const result = [];
		const length = Math.max.apply(null, data);

		for(let i = 0; i < length; i++) {
			result[i] = {id: i, count: data.filter((t) => t === i).length, percentile: i === percentile ? 1 : 0 };
		}
		return result;
	}

	static rollingAverage(data, index, window) {
		return MathHelper.average(data.slice(Math.max(0, index - Math.floor(window / 2)), Math.min(data.length, index + window)));
	}

	static average(data) {
		return Math.round(data.reduce((c,a) => c + a, 0) / data.length);
	}
}

module.exports = MathHelper;