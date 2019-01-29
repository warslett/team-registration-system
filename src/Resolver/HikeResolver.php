<?php

namespace App\Resolver;

use App\Entity\Hike;
use App\Repository\HikeRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HikeResolver
{

    /**
     * @var HikeRepository
     */
    private $hikeRepository;

    /**
     * @param HikeRepository $hikeRepository
     */
    public function __construct(HikeRepository $hikeRepository)
    {
        $this->hikeRepository = $hikeRepository;
    }

    /**
     * @param int $hikeId
     * @return Hike
     */
    public function resolveById(int $hikeId): Hike
    {
        $hike = $this->hikeRepository->find($hikeId);
        if (is_null($hike)) {
            throw new NotFoundHttpException(sprintf("No hike found with the id %d", $hikeId));
        }
        return $hike;
    }
}
