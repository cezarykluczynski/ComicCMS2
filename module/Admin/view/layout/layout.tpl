{$this->doctype()}

<html lang="en" class="ng-app">
<head>
    <meta charset="utf-8">
    {$this->headTitle()->setSeparator(' - ')->setAutoEscape(false)}

    {$basePath = $this->basePath()}

    {$this->headMeta()}

    <!-- styles -->
    {$this->headLink()
    ->appendStylesheet("`$basePath`/css/style.css")}

    <!-- scripts -->
    {$this->headScript()
    ->appendFile("`$basePath`/vendor/jquery/dist/jquery.js")
    ->appendFile("`$basePath`/vendor/angular/angular.js")
    ->appendFile("`$basePath`/js/admin/admin.js")}
</head>

<body>
    <a href="{$this->url('home')}">ComicCMS2</a>

    <div class="container">
        {$this->content}
    </div>

</body>
</html>