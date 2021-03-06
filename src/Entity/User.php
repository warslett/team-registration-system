<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=254, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=254)
     * @Assert\NotBlank()
     * @var string|null
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=254)
     * @Assert\NotBlank()
     * @var string|null
     */
    private $contactNumber;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="user")
     * @var Collection|Team[]
     */
    private $teams;

    /**
     * @var int $resetPasswordCount
     * @ORM\Column(type="integer")
     */
    private $resetPasswordCount = 0;

    /**
     * @var \DateTime|null $refreshPasswordCountDate
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $refreshPasswordCountDate;

    /**
     * @var string|null $resetPasswordToken
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $resetPasswordToken;

    /**
     * @var \DateTime|null $resetPasswordTokenExpiry
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $resetPasswordTokenExpiry;

    /**
     * @var Collection|UserGroup[]
     * @ORM\ManyToMany(targetEntity="UserGroup", inversedBy="users")
     * @ORM\JoinTable(
     *     name="users_usergroups",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role", referencedColumnName="role")}
     * )
     */
    private $userGroups;

    /**
     * @var string
     */
    private $plainPassword;

    public function __construct()
    {
        $this->isActive = true;
        $this->teams = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        foreach ($this->userGroups as $userGroup) {
            $roles[] = $userGroup->getRole();
        }
        return $roles;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @see \Serializable::serialize()
     * @return string
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password
            ) = unserialize($serialized);
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * @return UserGroup[]|Collection
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }

    /**
     * @param UserGroup[]|Collection $userGroups
     */
    public function setUserGroups($userGroups): void
    {
        $this->userGroups = $userGroups;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string|null
     */
    public function getContactNumber(): ?string
    {
        return $this->contactNumber;
    }

    /**
     * @param string $contactNumber
     */
    public function setContactNumber(string $contactNumber): void
    {
        $this->contactNumber = $contactNumber;
    }

    /**
     * @return null|string
     */
    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    /**
     * @param null|string $resetPasswordToken
     */
    public function setResetPasswordToken(?string $resetPasswordToken): void
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    /**
     * @return \DateTime|null
     */
    public function getResetPasswordTokenExpiry(): ?\DateTime
    {
        return $this->resetPasswordTokenExpiry;
    }

    /**
     * @param \DateTime|null $resetPasswordTokenExpiry
     */
    public function setResetPasswordTokenExpiry(?\DateTime $resetPasswordTokenExpiry): void
    {
        $this->resetPasswordTokenExpiry = $resetPasswordTokenExpiry;
    }

    /**
     * @return int
     */
    public function getResetPasswordCount(): int
    {
        return $this->resetPasswordCount;
    }

    /**
     * @param int $resetPasswordCount
     */
    public function setResetPasswordCount(int $resetPasswordCount): void
    {
        $this->resetPasswordCount = $resetPasswordCount;
    }

    /**
     * @return \DateTime|null
     */
    public function getRefreshPasswordCountDate(): ?\DateTime
    {
        return $this->refreshPasswordCountDate;
    }

    /**
     * @param \DateTime|null $refreshPasswordCountDate
     */
    public function setRefreshPasswordCountDate(?\DateTime $refreshPasswordCountDate): void
    {
        $this->refreshPasswordCountDate = $refreshPasswordCountDate;
    }

    public function incrementResetPasswordCount(): void
    {
        $this->resetPasswordCount++;
    }
}
