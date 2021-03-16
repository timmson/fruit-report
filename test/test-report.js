const report = require("../src/report");

describe(" Report should", () => {

    test("return data", () => {
        const config = {
            protocol: "https",
            host: "jira.atlassian.com",
        };
        const data = {
            issues: [
                {
                    key: "JIRA-1",
                    changelog: {
                        histories: [
                            /*
                                created: new Date(),
                                items: [
                                    {
                                        field: "status",
                                        toString: "In Progress"
                                    }
                                ]
                            },
                            {

                                created: new Date(),
                                items: [
                                    {
                                        field: "status",
                                        toString: "Closed"
                                    }
                                ]

                                }*/
                        ]
                    }
                },
                {
                    key: "JIRA-2",
                    changelog: {
                        histories: []
                    }
                }
            ]
        };
        const result = report(config, data);

        expect(result).not.toBeNaN();

        //console.log(JSON.stringify(result, null, 2));
    })

});
