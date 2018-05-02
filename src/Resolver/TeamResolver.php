<?php

namespace App\Resolver;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Service\CurrentUserService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Fetches a team from the database but also checks that the team exists and the current user is authorized to access
 * it. Throws appropriate exceptions if not.
 * @package App\Resolver
 */
class TeamResolver
{

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var CurrentUserService
     */
    private $currentUserService;

    /**
     * @param TeamRepository $teamRepository
     * @param CurrentUserService $currentUserService
     */
    public function __construct(TeamRepository $teamRepository, CurrentUserService $currentUserService)
    {
        $this->teamRepository = $teamRepository;
        $this->currentUserService = $currentUserService;
    }

    /**
     * @param int $teamId
     * @return Team
     * @throws NotFoundHttpException
     * @throws AccessDeniedHttpException
     */
    public function resolveById(int $teamId): Team
    {
        $team = $this->teamRepository->find($teamId);

        if (is_null($team)) {
            throw new NotFoundHttpException(sprintf("No team found with the id %d", $teamId));
        }

        if ($team->getUser() !== $this->currentUserService->getCurrentUser()) {
            throw new AccessDeniedHttpException(sprintf(
                "You do not have permission to access the team with the id %d",
                $teamId
            ));
        }

        return $team;
    }
}
