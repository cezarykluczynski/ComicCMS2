define({
    suites: false,
    proxyPort: 9090,
    proxyUrl: "http://localhost:9090/",
    capabilities: {
        "selenium-version": "2.45.0"
    },
    tunnel: "SauceLabsTunnel",
    tunnelOptions: {
        port: 4444
    },
    environments: [
        { browserName: "Firefox" },
        { browserName: "Internet Explorer", platform: "Windows 8.1", version: "11" },
        { browserName: "Chrome" }
    ],
    maxConcurrency: 3,
    functionalSuites: [
        "tests/functional/helloworld",
    ],
    excludeInstrumentation: /tests|node_modules/
});