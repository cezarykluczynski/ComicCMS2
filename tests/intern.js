define({
    suites: false,
    proxyPort: 12345,
    proxyUrl: "http://comiccms.dev/",
    capabilities: {
        "selenium-version": "2.45.0"
    },
    /** "Chrome", nor "Firefox", won't work here. */
    environments: [
        { browserName: "chrome" }
    ],
    maxConcurrency: 1,
    functionalSuites: [
        "tests/functional/helloworld"
    ],
    excludeInstrumentation: /tests|node_modules/
});