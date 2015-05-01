define({
    suites: false,
    proxyPort: 9091,
    proxyUrl: "http://127.0.0.1:9091/",
    capabilities: {
        "selenium-version": "2.45.0"
    },
    tunnel: "SauceLabsTunnel",
    tunnelOptions: {
        port: 4444
    },
    environments: [
        // { browserName: "Firefox" },
        { browserName: "Chrome" }
    ],
    maxConcurrency: 3,
    functionalSuites: [
        "tests/functional/helloworld",
    ],
    excludeInstrumentation: /tests|node_modules/
});