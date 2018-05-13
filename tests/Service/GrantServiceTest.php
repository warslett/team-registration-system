<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\UserGroup;
use App\Exception\RoleAlreadyGrantedException;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use App\Resolver\UserGroupResolver;
use App\Service\GrantService;
use App\Tests\TestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Mockery as m;

class GrantServiceTest extends TestCase
{

    public function testGrant_CallsUserRepoWithEmail()
    {
        $userRepo = $this->mockUserRepo($this->mockUser($this->mockArrayCollection()));
        $email = "john@acme.co";
        $grantService = new GrantService(
            $userRepo,
            $this->mockUserGroupResolver($this->mockUserGroup()),
            $this->mockEntityManager()
        );

        $grantService->grant($email, "ROLE_ADMIN");

        $userRepo->shouldHaveReceived('findOneByEmail')->with($email)->once();
    }

    public function testGrant_emailDoesNotExist_ThrowsException()
    {
        $userRepo = $this->mockUserRepo(null);
        $email = "john@acme.co";
        $grantService = new GrantService(
            $userRepo,
            $this->mockUserGroupResolver($this->mockUserGroup()),
            $this->mockEntityManager()
        );

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage("No User found with email john@acme.co");

        $grantService->grant($email, "ROLE_ADMIN");
    }

    public function testGrant_CallsUserGroupResolverWithRole()
    {
        $userRepo = $this->mockUserRepo($this->mockUser($this->mockArrayCollection()));
        $userGroupResolver = $this->mockUserGroupResolver($this->mockUserGroup());
        $role = "ROLE_ADMIN";
        $grantService = new GrantService($userRepo, $userGroupResolver, $this->mockEntityManager());

        $grantService->grant("john@acme.co", $role);

        $userGroupResolver->shouldHaveReceived('resolveByRole')->with($role)->once();
    }

    public function testGrant_ChecksWhetherUserHasUserGroup()
    {
        $userGroupCollection = $this->mockArrayCollection();
        $user = $this->mockUser($userGroupCollection);
        $userRepo = $this->mockUserRepo($user);
        $userGroup = $this->mockUserGroup();
        $userGroupResolver = $this->mockUserGroupResolver($userGroup);
        $grantService = new GrantService($userRepo, $userGroupResolver, $this->mockEntityManager());

        $grantService->grant("john@acme.co", "ROLE_ADMIN");

        $userGroupCollection->shouldHaveReceived('contains')->with($userGroup)->once();
    }

    public function testGrant_UserDoesNotHaveUserGroup_AddsUserToGroup()
    {
        $userGroupCollection = $this->mockArrayCollection(false);
        $user = $this->mockUser($userGroupCollection);
        $userRepo = $this->mockUserRepo($user);
        $userGroup = $this->mockUserGroup();
        $userGroupResolver = $this->mockUserGroupResolver($userGroup);
        $grantService = new GrantService($userRepo, $userGroupResolver, $this->mockEntityManager());

        $grantService->grant("john@acme.co", "ROLE_ADMIN");

        $userGroupCollection->shouldHaveReceived('add')->with($userGroup)->once();
    }

    public function testGrant_UserAlreadyHasUserGroup_ThrowsException()
    {
        $userGroupCollection = $this->mockArrayCollection(true);
        $user = $this->mockUser($userGroupCollection);
        $userRepo = $this->mockUserRepo($user);
        $userGroup = $this->mockUserGroup();
        $userGroupResolver = $this->mockUserGroupResolver($userGroup);
        $grantService = new GrantService($userRepo, $userGroupResolver, $this->mockEntityManager());

        $this->expectException(RoleAlreadyGrantedException::class);
        $this->expectExceptionMessage("The role ROLE_ADMIN has already been granted to user john@acme.co");

        $grantService->grant("john@acme.co", "ROLE_ADMIN");
    }

    public function testGrant_UserDoesNotHaveUserGroup_CallsFlush()
    {
        $user = $this->mockUser($this->mockArrayCollection(false));
        $userRepo = $this->mockUserRepo($user);
        $userGroupResolver = $this->mockUserGroupResolver($this->mockUserGroup());
        $em = $this->mockEntityManager();
        $grantService = new GrantService($userRepo, $userGroupResolver, $em);

        $grantService->grant("john@acme.co", "ROLE_ADMIN");

        $em->shouldHaveReceived('flush')->once();
    }

    /**
     * @param User|null $user
     * @return UserRepository|m\Mock
     */
    private function mockUserRepo(?User $user = null): UserRepository
    {
        $userRepo = m::mock(UserRepository::class);
        $userRepo->shouldReceive('findOneByEmail')->andReturn($user);
        return $userRepo;
    }

    /**
     * @param ArrayCollection $userGroupCollection
     * @return User m\Mock
     */
    private function mockUser(ArrayCollection $userGroupCollection): User
    {
        $user = m::mock(User::class);
        $user->shouldReceive('getUserGroups')->andReturn($userGroupCollection);
        return $user;
    }

    /**
     * @param UserGroup $userGroup
     * @return UserGroupResolver|m\Mock
     */
    private function mockUserGroupResolver(UserGroup $userGroup): UserGroupResolver
    {
        $userGroupResolver = m::mock(UserGroupResolver::class);
        $userGroupResolver->shouldReceive('resolveByRole')->andReturn($userGroup);
        return $userGroupResolver;
    }

    /**
     * @return UserGroup|m\Mock
     */
    private function mockUserGroup(): UserGroup
    {
        $userGroup = m::mock(UserGroup::class);
        return $userGroup;
    }

    /**
     * @param bool $hasItem
     * @return ArrayCollection|m\Mock
     */
    private function mockArrayCollection(bool $hasItem = false): ArrayCollection
    {
        $arrayCollection = m::mock(ArrayCollection::class);
        $arrayCollection->shouldReceive('add');
        $arrayCollection->shouldReceive('contains')->andReturn($hasItem);
        return $arrayCollection;
    }

    /**
     * @return EntityManagerInterface|m\Mock
     */
    private function mockEntityManager(): EntityManagerInterface
    {
        $em = m::mock(EntityManagerInterface::class);
        $em->shouldReceive('flush');
        return $em;
    }
}
