<?php

namespace App\Resolver;

use App\Entity\Walker;
use App\Repository\WalkerRepository;
use App\Service\CurrentUserService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @todo write unit tests
 */
class WalkerResolver
{

    /**
     * @var WalkerRepository
     */
    private $walkerRepository;
    /**
     * @var CurrentUserService
     */
    private $currentUserService;

    public function __construct(WalkerRepository $walkerRepository, CurrentUserService $currentUserService)
    {
        $this->walkerRepository = $walkerRepository;
        $this->currentUserService = $currentUserService;
    }

    /**
     * @param int $walkerId
     * @return Walker
     * @throws NotFoundHttpException
     * @throws AccessDeniedHttpException
     */
    public function resolveById(int $walkerId): Walker
    {
        $walker = $this->walkerRepository->find($walkerId);

        if (is_null($walker)) {
            throw new NotFoundHttpException(sprintf("No walker found with the id %d", $walkerId));
        }



        if ($walker->getTeam()->getUser() !== $this->currentUserService->getCurrentUser()) {
            throw new AccessDeniedHttpException(sprintf(
                "You do not have permission to access the walker with the id %d",
                $walkerId
            ));
        }

        return $walker;
    }
}
