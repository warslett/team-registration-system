<?php


namespace App\Service;

use App\Exception\RoleAlreadyGrantedException;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use App\Resolver\UserGroupResolver;
use Doctrine\ORM\EntityManagerInterface;

class GrantService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserGroupResolver
     */
    private $userGroupResolver;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param UserRepository $userRepository
     * @param UserGroupResolver $userGroupResolver
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UserRepository $userRepository,
        UserGroupResolver $userGroupResolver,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->userGroupResolver = $userGroupResolver;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $email
     * @param string $role
     * @throws RoleAlreadyGrantedException
     * @throws UserNotFoundException
     */
    public function grant(string $email, string $role): void
    {
        $user = $this->userRepository->findOneByEmail($email);

        if (is_null($user)) {
            throw new UserNotFoundException(sprintf("No User found with email %s", $email));
        }

        $userGroup = $this->userGroupResolver->resolveByRole($role);

        if ($user->getUserGroups()->contains($userGroup)) {
            throw new RoleAlreadyGrantedException(sprintf(
                "The role %s has already been granted to user %s",
                $role,
                $email
            ));
        } else {
            $user->getUserGroups()->add($userGroup);
            $this->entityManager->flush();
        }
    }
}
