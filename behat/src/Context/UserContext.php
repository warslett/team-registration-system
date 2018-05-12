<?php

namespace App\Behat\Context;

use App\Entity\User;
use App\Behat\FixtureFactory\UserFactory;
use App\Behat\Service\FixtureStorageService;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;

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
     * @Given that :userReference is a User with the following properties:
     * @Given that :userReference is a User
     * @param $userReference
     * @param TableNode|null $table
     */
    public function thatIsAUserWithEmailAndPassword($userReference, TableNode $table = null)
    {
        $properties = is_null($table)?[]:$table->getRowsHash();
        $user = $this->userFactory->createUser($properties);
        $this->fixtureStorage->set(User::class, $userReference, $user);
    }
}
