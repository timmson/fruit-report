function histogram(data, percentile) {
	const result = []
	const length = Math.max.apply(null, data)

	for (let i = 0; i < length; i++) {
		result[i] = {id: i, count: data.filter((t) => t === i).length, percentile: i === percentile ? 1 : 0}
	}
	return result
}

module.exports = histogram