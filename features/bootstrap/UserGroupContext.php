<?php

namespace App\Context;

use App\Factory\UserGroupFactory;
use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

class UserGroupContext implements Context
{

    /**
     * @var UserGroupFactory
     */
    private $userGroupFactory;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserGroupFactory $userGroupFactory, UserRepository $userRepository)
    {
        $this->userGroupFactory = $userGroupFactory;
        $this->userRepository = $userRepository;
    }

    /**
     * @Given /^that there is a User Group with the role "([^"]*)" with the following members:$/
     * @param $role
     * @param TableNode $table
     */
    public function thatThereIsAUserGroupCalledWithTheRoleWithTheFollowingMembers($role, TableNode $table)
    {
        $users = [];
        foreach ($table->getColumnsHash() as $row) {
            $user = $this->userRepository->findOneByEmail($row['Email']);
            Assert::assertNotNull($user, sprintf("User with email %s not found", $row['Email']));
            $users[] = $user;
        }
        $this->userGroupFactory->createUserGroup($role, $users);
    }
}