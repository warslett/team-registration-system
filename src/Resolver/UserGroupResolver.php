<?php


namespace App\Resolver;

use App\Entity\UserGroup;
use App\Repository\UserGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserGroupResolver
{

    /**
     * @var UserGroupRepository
     */
    private $userGroupRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param UserGroupRepository $userGroupRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(UserGroupRepository $userGroupRepository, EntityManagerInterface $entityManager)
    {
        $this->userGroupRepository = $userGroupRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $role
     * @return UserGroup
     */
    public function resolveByRole(string $role): UserGroup
    {
        $userGroup = $this->userGroupRepository->find($role);

        if (is_null($userGroup)) {
            $userGroup = new UserGroup($role);
            $this->entityManager->persist($userGroup);
            $this->entityManager->flush();
        }

        return $userGroup;
    }
}
