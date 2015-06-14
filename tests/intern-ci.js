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
    maxConcurrency: 1,
    functionalSuites: [
        "tests/functional/mainpage",
        "tests/functional/admin/auth/signin",
        "tests/functional/admin/dashboard/dashboard",
        "tests/functional/admin/dashboard/comics/create",
        "tests/functional/admin/dashboard/comics/delete",
    ],
    excludeInstrumentation: /tests|node_modules/
});