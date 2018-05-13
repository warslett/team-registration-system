<?php

namespace App\Tests\Resolver;

use App\Entity\UserGroup;
use App\Repository\UserGroupRepository;
use App\Resolver\UserGroupResolver;
use App\Tests\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Mockery as m;

class UserGroupResolverTest extends TestCase
{

    public function testResolveByRole_CallsUserGroupRepositoryFind()
    {
        $userGroupRepository = $this->mockUserGroupRepository($this->mockUserGroup());
        $userGroupResolver = new UserGroupResolver($userGroupRepository, $this->mockEntityManager());
        $role = 'ROLE_ADMIN';

        $userGroupResolver->resolveByRole($role);

        $userGroupRepository->shouldHaveReceived('find')->with($role)->once();
    }

    public function testResolveByRole_UserGroupExists_ReturnsUserGroup()
    {
        $userGroup = $this->mockUserGroup();
        $userGroupRepository = $this->mockUserGroupRepository($userGroup);
        $userGroupResolver = new UserGroupResolver($userGroupRepository, $this->mockEntityManager());
        $role = 'ROLE_ADMIN';

        $actual = $userGroupResolver->resolveByRole($role);

        $this->assertEquals($userGroup, $actual);
    }

    public function testResolveByRole_UserGroupDoesNotExist_ReturnsNewUserGroupForRole()
    {
        $userGroupRepository = $this->mockUserGroupRepository(null);
        $userGroupResolver = new UserGroupResolver($userGroupRepository, $this->mockEntityManager());
        $role = 'ROLE_ADMIN';

        $actual = $userGroupResolver->resolveByRole($role);

        $this->assertEquals($role, $actual->getRole());
    }

    public function testResolveByRole_UserGroupDoesNotExist_PersistsNewUserGroup()
    {
        $userGroupRepository = $this->mockUserGroupRepository(null);
        $em = $this->mockEntityManager();
        $userGroupResolver = new UserGroupResolver($userGroupRepository, $em);
        $role = 'ROLE_ADMIN';

        $userGroup = $userGroupResolver->resolveByRole($role);

        $em->shouldHaveReceived('persist')->with($userGroup)->once();
    }

    public function testResolveByRole_UserGroupDoesNotExist_CallsFlush()
    {
        $userGroupRepository = $this->mockUserGroupRepository(null);
        $em = $this->mockEntityManager();
        $userGroupResolver = new UserGroupResolver($userGroupRepository, $em);
        $role = 'ROLE_ADMIN';

        $userGroupResolver->resolveByRole($role);

        $em->shouldHaveReceived('flush')->once();
    }

    /**
     * @param UserGroup|null $userGroup
     * @return UserGroupRepository|m\Mock
     */
    private function mockUserGroupRepository(?UserGroup $userGroup = null): UserGroupRepository
    {
        $userGroupResolver = m::mock(UserGroupRepository::class);
        $userGroupResolver->shouldReceive('find')->andReturn($userGroup);
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
     * @return EntityManagerInterface|m\Mock
     */
    private function mockEntityManager(): EntityManagerInterface
    {
        $em = m::mock(EntityManagerInterface::class);
        $em->shouldReceive('persist');
        $em->shouldReceive('flush');
        return $em;
    }
}
