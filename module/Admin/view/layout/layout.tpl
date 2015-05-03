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
        ->appendStylesheet("`$basePath`/css/admin.css")
    }

    <!-- scripts -->
    {$this->headScript()
        ->appendFile("`$basePath`/vendor/jquery/dist/jquery.js")
        ->appendFile("`$basePath`/vendor/bootswatch-dist/js/bootstrap.js")
        ->appendFile("`$basePath`/vendor/angular/angular.js")
        ->appendFile("`$basePath`/js/admin/admin-signin.js")
        ->appendFile("`$basePath`/js/admin/admin.js")
    }
</head>

<body class="admin">
    {$this->content}
</body>
</html>