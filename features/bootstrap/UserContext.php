<?php

use App\Entity\User;
use App\Factory\UserFactory;
use Behat\Behat\Context\Context;

class UserContext implements Context
{

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var null|User
     */
    private $user = null;

    /**
     * UserContext constructor.
     * @param UserFactory $userFactory
     */
    public function __construct(UserFactory $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    /**
     * @Given /^there is a User with email "([^"]*)" and password "([^"]*)"$/
     */
    public function thereIsAUserWithEmailAndPassword($email, $password)
    {
        $this->user = $this->userFactory->createUser($email, $password);
    }
}
