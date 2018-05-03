<?php

namespace App\Tests\Resolver;

use App\Entity\Team;
use App\Entity\User;
use App\Repository\TeamRepository;
use App\Resolver\TeamResolver;
use App\Service\CurrentUserService;
use App\Tests\TestCase;
use Mockery as m;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamResolverTest extends TestCase
{

    public function testResolveById_TeamDoesNotExist_ThrowsNotFoundException()
    {
        $teamId = 1;
        $teamRepo = $this->mockTeamRepository(null);
        $currentUserService = $this->mockCurrentUserService(null);
        $teamResolver = new TeamResolver($teamRepo, $currentUserService);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage("No team found with the id 1");
        $teamResolver->resolveById($teamId);
    }

    public function testResolveById_BelongsToDifferentUser_ThrowsAccessDeniedException()
    {
        $teamId = 1;
        $user = $this->mockUser();

        $differentUser = $this->mockUser();
        $team = $this->mockTeam($differentUser);
        $teamRepo = $this->mockTeamRepository($team);

        $currentUserService = $this->mockCurrentUserService($user);

        $teamResolver = new TeamResolver($teamRepo, $currentUserService);

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage("You do not have permission to access the team with the id 1");
        $teamResolver->resolveById($teamId);
    }

    public function testResolveById_BelongsToCurrentUser_ReturnsTeam()
    {
        $teamId = 1;
        $user = $this->mockUser();

        $team = $this->mockTeam($user);
        $teamRepo = $this->mockTeamRepository($team);

        $currentUserService = $this->mockCurrentUserService($user);

        $teamResolver = new TeamResolver($teamRepo, $currentUserService);

        $actual = $teamResolver->resolveById($teamId);

        $this->assertEquals($team, $actual);
    }

    /**
     * @param $null
     * @return TeamRepository|m\Mock
     */
    private function mockTeamRepository(?Team $team): TeamRepository
    {
        $teamRepo = m::mock(TeamRepository::class);
        $teamRepo->shouldReceive('find')->andReturn($team);
        return $teamRepo;
    }

    /**
     * @param $null
     * @return CurrentUserService|m\Mock
     */
    private function mockCurrentUserService(?User $user): CurrentUserService
    {
        $currentUserService = m::mock(CurrentUserService::class);
        $currentUserService->shouldReceive('getCurrentUser')->andReturn($user);
        return $currentUserService;
    }

    /**
     * @return User|m\Mock
     */
    private function mockUser(): User
    {
        $user = m::mock(User::class);
        return $user;
    }

    /**
     * @param $user
     * @return Team|m\Mock
     */
    private function mockTeam(User $user): Team
    {
        $team = m::mock(Team::class);
        $team->shouldReceive('getUser')->andReturn($user);
        return $team;
    }
}
