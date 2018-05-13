<?php


namespace App\Repository;

use App\Entity\UserGroup;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserGroupRepository extends EntityRepository
{

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(UserGroup::class));
    }

    /**
     * @param string $role
     * @param $lockMode
     * @param $lockVersion
     * @return UserGroup|null
     */
    public function find($role, $lockMode = null, $lockVersion = null): ?UserGroup
    {
        return parent::find($role, $lockMode, $lockVersion);
    }
}
