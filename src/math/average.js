function rollingAverage(data, index, window) {
	return average(data.slice(Math.max(0, index - Math.floor(window / 2)), Math.min(data.length, index + window)));
}

function average(data) {
	return Math.round(data.reduce((c, a) => c + a, 0) / data.length);
}

module.exports = {average, rollingAverage};