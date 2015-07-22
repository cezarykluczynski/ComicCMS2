define({
    suites: false,
    proxyPort: 9090,
    proxyUrl: "http://localhost:9090/",
    capabilities: {
        "selenium-version": "2.45.0",
        "idle-timeout": 45
    },
    tunnel: "BrowserStackTunnel",
    environments: [
        { browserName: "Firefox" },
        { browserName: "Internet Explorer", platform: "WIN8", version: "11" },
        { browserName: "Chrome" }
    ],
    maxConcurrency: 1,
    functionalSuites: [
        "tests/functional/mainpage",
        "tests/functional/admin/auth/signin",
        "tests/functional/admin/dashboard/dashboard",
        "tests/functional/admin/dashboard/comics/create",
        "tests/functional/admin/dashboard/comics/delete",
        "tests/functional/admin/dashboard/comics/edit",
        "tests/functional/admin/dashboard/comics/select",
        "tests/functional/admin/dashboard/settings/edit",
        "tests/functional/admin/dashboard/strips/create",
        "tests/functional/admin/dashboard/strips/delete",
        "tests/functional/admin/dashboard/strips/edit",
        "tests/functional/admin/dashboard/strips/list",
    ],
    excludeInstrumentation: /tests|node_modules/
});