const JiraApi = require("jira-client");

function fetch(credentials, config) {

	const jira = new JiraApi({
		protocol: config.protocol,
		host: config.host,
		username: credentials.username,
		password: credentials.password,
		apiVersion: "2",
		strictSSL: false
	});
    
	const options = {
		maxResults: config.maxResults,
		fields: ["navigable"],
		expand: ["changelog"]
	};
    
	return jira.searchJira(config.query, options);
}

module.exports = fetch;