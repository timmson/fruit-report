const fs = require("fs")

const config = require("./config")
const credentials = require("./credentials")
const fetch = require("./src/fetch")
const report = require("./src/report")

if (!fs.existsSync("tmp")){
	fs.mkdirSync("tmp")
}

const cacheFile = "tmp/1.json"

if (config.isFetch) {
	fetch(credentials, config).then((data) => {
		fs.writeFileSync(cacheFile, JSON.stringify(data))
		const result = report(config, data)
		const resultJson =  JSON.stringify(result, null, 2)
		fs.writeFileSync("assets/data.js", "const data = " + resultJson + ";")
	})
} else {
	const json = fs.readFileSync(cacheFile, "utf8")
	const data = JSON.parse(json)
	const result = report(config, data)
	const resultJson =  JSON.stringify(result, null, 2)
	fs.writeFileSync("assets/data.js", "const data = " + resultJson + ";")
}




