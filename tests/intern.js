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
        "tests/functional/mainpage",
        "tests/functional/admin/auth/signin",
        "tests/functional/admin/dashboard/dashboard",
        "tests/functional/admin/dashboard/comics/create",
        "tests/functional/admin/dashboard/comics/delete",
        "tests/functional/admin/dashboard/comics/edit",
        "tests/functional/admin/dashboard/comics/select",
        "tests/functional/admin/dashboard/strips/create",
        "tests/functional/admin/dashboard/strips/delete",
        "tests/functional/admin/dashboard/strips/edit",
        "tests/functional/admin/dashboard/strips/list",
    ],
    excludeInstrumentation: /tests|node_modules/
});