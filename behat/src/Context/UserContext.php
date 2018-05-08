<?php

namespace App\Behat\Context;

use App\Entity\User;
use App\Behat\FixtureFactory\UserFactory;
use App\Behat\Service\FixtureStorageService;
use Behat\Behat\Context\Context;

class UserContext implements Context
{

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var FixtureStorageService
     */
    private $fixtureStorage;

    /**
     * UserContext constructor.
     * @param UserFactory $userFactory
     * @param FixtureStorageService $fixtureStorage
     */
    public function __construct(UserFactory $userFactory, FixtureStorageService $fixtureStorage)
    {
        $this->userFactory = $userFactory;
        $this->fixtureStorage = $fixtureStorage;
    }

    /**
     * @Given /^that "([^"]*)" is a User with email "([^"]*)" and password "([^"]*)"$/
     * @throws \Exception
     */
    public function thatIsAUserWithEmailAndPassword($userReference, $email, $password)
    {
        $user = $this->userFactory->createUser($email, $password);
        $this->fixtureStorage->set(User::class, $userReference, $user);
    }
}
