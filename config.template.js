module.exports = {
	"protocol" : "https",
	"host" : "<jira server>",
	"maxResults" : 200,
	"team" : "<team name>",
	"query" : "<jql query>",
	"statuses" : {
		"start" : ["Open"],
		"end" : ["Closed"]
	},
	"isFetch" : true
}
