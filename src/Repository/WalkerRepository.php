<?php

namespace App\Repository;

use App\Entity\Walker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class WalkerRepository extends EntityRepository
{

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Walker::class));
    }

    /**
     * @param int $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return Walker|null
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?Walker
    {
        return parent::find($id, $lockMode, $lockVersion);
    }
}
