const {average, rollingAverage} = require("./math/average")
const controlChart = require("./math/control-chart")
const histogram = require("./math/histogram")
const linearApproximation = require("./math/linear-approximation")
const percentile = require("./math/percentile")

module.exports = {average, rollingAverage, controlChart, histogram, linearApproximation, percentile}
