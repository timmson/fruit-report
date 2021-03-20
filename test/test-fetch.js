const fetch = require("../src/fetch");
const config = {
	query: "project = ALL and updated < -6d",
	maxResults: 200
};

jest.mock("jira-client", () =>
	jest.fn().mockImplementation(() => ({
		searchJira: (query, option) => {
			expect(query).toEqual(config.query);
			expect(option.maxResults).toEqual(config.maxResults);
			return {};
		}
	}))
);

describe(" Fetch should ", () => {

	test("return data", () => {
		expect.assertions(3);
		expect(fetch({}, config)).toBeDefined();
	});

});