<?php

namespace App\Behat\FixtureFactory;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory
{

    public const DEFAULT_PASSWORD = 'development';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * UserFactory constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Generator $faker
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        Generator $faker
    ) {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = $faker;
    }

    /**
     * @param array $properties
     * @return User
     */
    public function createUser(array $properties = []): User
    {
        $user = new User();
        $user->setEmail($properties['email'] ?? $this->faker->email);
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $properties['password'] ?? UserFactory::DEFAULT_PASSWORD
            )
        );
        $user->setFullName($properties['fullName'] ?? $this->faker->name());
        $user->setContactNumber($properties['contactNumber'] ?? $this->faker->phoneNumber);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}
