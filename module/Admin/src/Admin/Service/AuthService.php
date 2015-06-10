<?php

namespace Admin\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;

class AuthService implements ServiceLocatorAwareInterface
{
    protected $email;
    protected $password;
    protected $user;
    protected $valid = false;
    protected $authenticated = false;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function validate()
    {
        $em = $this->serviceLocator->get('doctrine.entitymanager.orm_default');

        $this->user = $em
            ->getRepository('User\Entity\User')
            ->findOneBy(array(
                'email' => $this->email,
            ));

        if (!is_null($this->user) && $this->validatePassword($this->password, $this->user->password))
        {
            $this->valid = true;
        }

        return $this;
    }

    /**
     * Stores user data in session, is user is validated.
     *
     * @return $this
     */
    public function authenticate()
    {
        if ($this->isValid())
        {
            $userContainer = new Container('user');
            $userContainer->id = $this->user->id;
        }

        return $this;
    }

    /**
     * Removes authenticated user from session.
     *
     * @return $this
     */
    public function removeUserFromSession()
    {
        $userContainer = new Container('user');
        $userContainer->id = null;
        $userContainer->authenticatedUser = null;

        return $this;
    }

    /**
     * Getter for $valid.
     *
     * @return True is user is validated, false otherwise.
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Credential setter.
     *
     * @param string $email    User e-mail.
     * @param string $password User password.
     * @return $this
     */
    public function setCredentials($email, $password)
    {
        $this->email = $email;
        $this->password = $password;

        return $this;
    }

    /**
     * Validates password, given the password nad hash.
     *
     * @param string $password Password to validate.
     * @param string $hash     bcrypt hash.
     * @return boolean         True if password matches, false otherwise.
     */
    protected function validatePassword($password, $hash)
    {
        $bcrypt = new Bcrypt();
        return $bcrypt->verify($password, $hash);
    }
}