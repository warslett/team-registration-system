<?php

namespace App\Factory\Fixture;

use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\ORM\EntityManagerInterface;

class UserGroupFactory
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $role
     * @param array|User[] $users
     * @return UserGroup
     */
    public function createUserGroup(string $role, array $users = []): UserGroup
    {
        $userGroup = new UserGroup($role);
        foreach ($users as $user) {
            $user->getUserGroups()->add($userGroup);
        }
        $this->entityManager->persist($userGroup);
        $this->entityManager->flush();
        return $userGroup;
    }
}
