{$this->doctype()}

<html lang="en">
<head>
    <meta charset="utf-8">
    {$this->headTitle()->setSeparator(' - ')->setAutoEscape(false)}

    {$basePath = $this->basePath()}

    {$this->headMeta()}

    <!-- styles -->
    {$this->headLink()
        ->appendStylesheet("`$basePath`/css/application.css")
    }

    <!-- scripts -->
    {$this->headScript()}

</head>

<body class="application">
    <a href="{$this->url('home')}">ComicCMS2</a>
    {$this->content}
</body>
</html>