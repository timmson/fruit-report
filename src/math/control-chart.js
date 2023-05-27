const {rollingAverage} = require("./average")

function controlChart(data, abLinear) {
	return data.map((t, i, a) => ({
		key: t.key,
		ct: t.ct,
		avg: rollingAverage(a.map((t) => t.ct), i, 5),
		trend: Math.floor(i * abLinear[1] + abLinear[0]),
		endDate: t.endDate
	}))
}

module.exports = controlChart