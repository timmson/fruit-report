const fs = require("fs")
const express = require("express")

const port = 8080
const app = express()

const config = require("./config")
const credentials = require("./credentials")
const fetch = require("./src/fetch")
const report = require("./src/report")


app.use("/*.html", express.static("."))
app.use("/assets", express.static("assets"))

app.get("/", (request, response) => {
	response.redirect("/index.html")
})

app.get("/fetch", (request, response) => {
	config.team = request.query.team || config.team
	config.query = request.query.query || config.query
	fetch(credentials, config).then((data) => {
		const result = report(config, data)
		const resultJson =  JSON.stringify(result, null, 2)
		fs.writeFileSync("assets/data.js", "const data = " + resultJson + ";")
		response.redirect("/index.html")
	})
})

app.listen(port, () => console.log(`Server started at http://localhost:${port}. Press Ctl + C to stop.`))

