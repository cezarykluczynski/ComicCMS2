{$this->doctype()}

<html lang="en" class="ng-app">
<head>
    <meta charset="utf-8">
    {$this->headTitle()->setSeparator(' - ')->setAutoEscape(false)}

    {$basePath = $this->basePath()}

    {$this->headMeta()}

    <!-- styles -->
    {$this->headLink()
        ->appendStylesheet("`$basePath`/vendor/bootswatch-dist/css/bootstrap.css")
        ->appendStylesheet("`$basePath`/vendor/ngDialog/css/ngDialog.css")
        ->appendStylesheet("`$basePath`/vendor/ngDialog/css/ngDialog-theme-default.css")
        ->appendStylesheet("`$basePath`/vendor/alertify.js/themes/alertify.core.css")
        ->appendStylesheet("`$basePath`/vendor/alertify.js/themes/alertify.default.css")
        ->appendStylesheet("`$basePath`/vendor/select2/select2.css")
        ->appendStylesheet("`$basePath`/vendor/angular-ui-select/dist/select.css")
        ->appendStylesheet("`$basePath`/css/admin.css")
    }

    <!-- scripts -->
    {$this->headScript()
        ->appendFile("`$basePath`/vendor/jquery/dist/jquery.js")
        ->appendFile("`$basePath`/vendor/bootswatch-dist/js/bootstrap.js")
        ->appendFile("`$basePath`/vendor/alertify.js/lib/alertify.js")
        ->appendFile("`$basePath`/vendor/angular/angular.js")
        ->appendFile("`$basePath`/vendor/lodash/lodash.js")
        ->appendFile("`$basePath`/vendor/select2/select2.js")
        ->appendFile("`$basePath`/vendor/angular-ui-select/dist/select.js")
        ->appendFile("`$basePath`/vendor/angular-utils-pagination/dirPagination.js")
        ->appendFile("`$basePath`/vendor/ng-alertify/ng-alertify.js")
        ->appendFile("`$basePath`/vendor/ngDialog/js/ngDialog.js")
        ->appendFile("`$basePath`/vendor/restangular/dist/restangular.js")
        ->appendFile("`$basePath`/js/admin/module/admin.js")
        ->appendFile("`$basePath`/js/admin/module/admin-signin.js")
        ->appendFile("`$basePath`/js/admin/filter/admin.js")
        ->appendFile("`$basePath`/js/admin/Service/comics.js")
        ->appendFile("`$basePath`/js/admin/Service/strips.js")
        ->appendFile("`$basePath`/js/admin/Service/users.js")
        ->appendFile("`$basePath`/js/admin/Controller/Dashboard.js")
        ->appendFile("`$basePath`/js/admin/Controller/Tab.js")
        ->appendFile("`$basePath`/js/admin/Controller/Comics.js")
        ->appendFile("`$basePath`/js/admin/Controller/ComicDelete.js")
        ->appendFile("`$basePath`/js/admin/Controller/ComicEdit.js")
        ->appendFile("`$basePath`/js/admin/Controller/Strip.js")
        ->appendFile("`$basePath`/js/admin/Controller/StripEdit.js")
        ->appendFile("`$basePath`/js/admin/Controller/Signin.js")
        ->appendFile("`$basePath`/js/admin/Controller/Users.js")
    }
</head>

<body class="admin">
    {$this->content}
</body>
</html>