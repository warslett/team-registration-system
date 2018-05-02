<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\CurrentUserService;
use App\Tests\TestCase;
use Mockery as m;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CurrentUserServiceTest extends TestCase
{

    public function testGetCurrentUser_UserExists_ReturnsUser()
    {
        $user = $this->mockUser();
        $token = $this->mockToken($user);
        $tokenStorage = $this->mockTokenStorage($token);
        $currentUserService = new CurrentUserService($tokenStorage);

        $actual = $currentUserService->getCurrentUser();

        $this->assertEquals($user, $actual);
    }

    public function testGetCurrentUser_UserAnon_ReturnsNull()
    {
        $token = $this->mockToken('anon.');
        $tokenStorage = $this->mockTokenStorage($token);
        $currentUserService = new CurrentUserService($tokenStorage);

        $actual = $currentUserService->getCurrentUser();

        $this->assertNull($actual);
    }

    public function testGetCurrentUser_TokenNull_ReturnsNull()
    {
        $tokenStorage = $this->mockTokenStorage(null);
        $currentUserService = new CurrentUserService($tokenStorage);

        $actual = $currentUserService->getCurrentUser();

        $this->assertNull($actual);
    }

    /**
     * @return m\MockInterface|User
     */
    private function mockUser(): User
    {
        $user = m::mock(User::class);
        return $user;
    }

    /**
     * @param mixed|User $user
     * @return m\MockInterface|TokenInterface
     */
    private function mockToken($user): TokenInterface
    {
        $token = m::mock(TokenInterface::class);
        $token->shouldReceive('getUser')->andReturn($user);
        return $token;
    }

    /**
     * @param TokenInterface|null $token
     * @return m\MockInterface|TokenStorageInterface
     */
    private function mockTokenStorage(?TokenInterface $token): TokenStorageInterface
    {
        $tokenStorage = m::mock(TokenStorageInterface::class);
        $tokenStorage->shouldReceive('getToken')->andReturn($token);
        return $tokenStorage;
    }
}
