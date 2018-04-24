<?php
/**
 * Created by PhpStorm.
 * User: william
 * Date: 09/04/18
 * Time: 17:30
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $name;

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
        $this->name = $role;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param User[]|Collection $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }
}
