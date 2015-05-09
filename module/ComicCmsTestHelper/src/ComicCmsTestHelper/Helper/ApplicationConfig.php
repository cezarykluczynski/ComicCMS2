<?php

namespace ComicCmsTestHelper\Helper;

trait ApplicationConfig
{
    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }
}
