<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserGroupRepository")
 */
class UserGroup
{

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @ORM\Id
     */
    private $role;

    /**
     * @var Collection|User[]
     * @ORM\ManyToMany(targetEntity="User", mappedBy="userGroups")
     */
    private $users;

    /**
     * @param string $role
     */
    public function __construct(string $role)
    {
        $this->users = new ArrayCollection();
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return User[]|Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param User[]|Collection $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }
}
