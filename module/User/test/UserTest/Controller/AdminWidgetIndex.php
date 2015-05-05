<?php

namespace UserTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use User\Provider\Identity\UserIdentityProviderMockInterface;

class AdminWidgetIndexControllerTest extends AbstractHttpControllerTestCase
{
    use UserIdentityProviderMockInterface;

    public function setUp()
    {
        $this->setApplicationConfig(include 'config/application.config.php');
        parent::setUp();
    }

    public function testUserAdminWidgetIndexCanBeAccessed()
    {
        $this->reset();
        $this->grantAllRolesToUser();

        $this->dispatch('/admin/user/widget/index');
        $this->assertResponseStatusCode(200);
        $this->assertTemplateName('user/admin/widget/index');

        $this->setOriginalUserRoles();
    }
}