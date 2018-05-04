<?php

namespace App\Context;

use App\Entity\User;
use App\Factory\Entity\UserGroupFactory;
use App\Repository\UserRepository;
use App\Service\FixtureStorageService;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;

class UserGroupContext implements Context
{

    /**
     * @var UserGroupFactory
     */
    private $userGroupFactory;

    /**
     * @var FixtureStorageService
     */
    private $fixtureStorage;

    /**
     * @param UserGroupFactory $userGroupFactory
     * @param FixtureStorageService $fixtureStorage
     */
    public function __construct(
        UserGroupFactory $userGroupFactory,
        FixtureStorageService $fixtureStorage
    ) {
        $this->userGroupFactory = $userGroupFactory;
        $this->fixtureStorage = $fixtureStorage;
    }

    /**
     * @Given /^that there is a User Group with the role "([^"]*)" with the following members:$/
     * @param $role
     * @param TableNode $table
     * @throws \Exception
     */
    public function thatThereIsAUserGroupCalledWithTheRoleWithTheFollowingMembers($role, TableNode $table)
    {
        $users = [];
        foreach ($table->getColumnsHash() as $row) {
            $user = $this->fixtureStorage->get(User::class, $row['Reference']);
            $users[] = $user;
        }
        $this->userGroupFactory->createUserGroup($role, $users);
    }
}
