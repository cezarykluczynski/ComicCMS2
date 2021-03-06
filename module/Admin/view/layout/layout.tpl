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
        ->appendStylesheet("`$basePath`/css/admin.css")
    }

    <!-- scripts -->
    {$this->headScript()
        ->appendFile("`$basePath`/vendor/jquery/dist/jquery.js")
        ->appendFile("`$basePath`/vendor/bootswatch-dist/js/bootstrap.js")
        ->appendFile("`$basePath`/vendor/angular/angular.js")
        ->appendFile("`$basePath`/vendor/angular-utils-pagination/dirPagination.js")
        ->appendFile("`$basePath`/vendor/ngDialog/js/ngDialog.js")
        ->appendFile("`$basePath`/vendor/ng-file-upload/ng-file-upload.js")
        ->appendFile("`$basePath`/vendor/angular-translate/angular-translate.js")
        ->appendFile("`$basePath`/vendor/lodash/lodash.js")
        ->appendFile("`$basePath`/vendor/noty/js/noty/packaged/jquery.noty.packaged.js")
        ->appendFile("`$basePath`/js/admin/module/admin.js")
        ->appendFile("`$basePath`/js/admin/module/admin-signin.js")
        ->appendFile("`$basePath`/js/admin/i18n/en.js")
        ->appendFile("`$basePath`/js/admin/filter/admin.js")
        ->appendFile("`$basePath`/js/admin/directive/focusOnce.js")
        ->appendFile("`$basePath`/js/admin/Service/comics.js")
        ->appendFile("`$basePath`/js/admin/Service/settings.js")
        ->appendFile("`$basePath`/js/admin/Service/strips.js")
        ->appendFile("`$basePath`/js/admin/Service/users.js")
        ->appendFile("`$basePath`/js/admin/Controller/Dashboard.js")
        ->appendFile("`$basePath`/js/admin/Controller/Tab.js")
        ->appendFile("`$basePath`/js/admin/Controller/Comics.js")
        ->appendFile("`$basePath`/js/admin/Controller/ComicDelete.js")
        ->appendFile("`$basePath`/js/admin/Controller/ComicEdit.js")
        ->appendFile("`$basePath`/js/admin/Controller/Settings.js")
        ->appendFile("`$basePath`/js/admin/Controller/Strips.js")
        ->appendFile("`$basePath`/js/admin/Controller/StripDelete.js")
        ->appendFile("`$basePath`/js/admin/Controller/StripEdit.js")
        ->appendFile("`$basePath`/js/admin/Controller/StripUpload.js")
        ->appendFile("`$basePath`/js/admin/Controller/Signin.js")
        ->appendFile("`$basePath`/js/admin/Controller/Users.js")
    }
</head>

<body class="admin">
    {$this->content}
</body>
</html>