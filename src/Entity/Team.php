<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation as ApiPlatform;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter as ORMFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @UniqueEntity(fields={"name","hike"}, message="Team name already taken for this hike")
 * @ApiPlatform\ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     attributes={
 *         "normalization_context"={"groups"={"api"}}
 *     }
 * )
 * @ApiPlatform\ApiFilter(ORMFilter\SearchFilter::class, properties={"hike": "exact"})
 */
class Team
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $name;

    /**
     * @var Hike
     * @ORM\ManyToOne(targetEntity="Hike", inversedBy="teams")
     */
    private $hike;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="teams")
     */
    private $user;

    /**
     * @Groups({"api"})
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @Groups({"api"})
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @Groups({"api"})
     * @return Hike
     */
    public function getHike(): ?Hike
    {
        return $this->hike;
    }

    /**
     * @param Hike $hike
     */
    public function setHike(Hike $hike)
    {
        $this->hike = $hike;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
