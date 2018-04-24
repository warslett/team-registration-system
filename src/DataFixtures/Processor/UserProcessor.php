<?php


namespace App\DataFixtures\Processor;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserProcessor implements ProcessorInterface
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Processes an object before it is persisted to DB.
     *
     * @param string $id Fixture ID
     * @param User $object
     * @return void
     */
    public function preProcess(string $id, $object): void
    {
        if (($object instanceof User) === false) {
            return;
        }
        $object->setPassword($this->passwordEncoder->encodePassword($object, $object->getPlainPassword()));
    }

    /**
     * Processes an object after it is persisted to DB.
     *
     * @param string $id Fixture ID
     * @param object $object
     * @return void
     */
    public function postProcess(string $id, $object): void
    {
    }
}
