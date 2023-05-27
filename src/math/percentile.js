function percentile(data, percentile) {
	const result = data.sort((a, b) => a === b ? 0 : (a > b ? 1 : -1))
	return result[Math.floor(data.length * percentile)]
}

module.exports = percentile
