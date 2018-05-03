<?php

namespace App\Tests\FormManager\User;

use App\Entity\User;
use App\Form\User\UserRegisterType;
use App\FormManager\User\UserRegisterFormManager;
use App\Tests\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Mockery as m;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegisterFormManagerTest extends TestCase
{

    public function testCreate_CallsFormFactory()
    {
        $em = $this->mockEntityManager();
        $formFactory = $this->mockFormFactory($this->mockForm());
        $passwordEncoder = $this->mockPasswordEncoder();
        $formManager = new UserRegisterFormManager($em, $formFactory, $passwordEncoder);

        $formManager->createForm();

        $formFactory->shouldHaveReceived('create')->with(
            UserRegisterType::class,
            m::type(User::class)
        )->once();
    }

    public function testCreate_ReturnsForm()
    {
        $em = $this->mockEntityManager();
        $form = $this->mockForm();
        $formFactory = $this->mockFormFactory($form);
        $passwordEncoder = $this->mockPasswordEncoder();
        $formManager = new UserRegisterFormManager($em, $formFactory, $passwordEncoder);

        $actual = $formManager->createForm();
        $this->assertEquals($form, $actual);
    }

    public function testProcess_SetsEncodedPasswordOnUser()
    {
        $plainTextPassword = "plaintext";
        $cipherTextPassword = "cipherText";
        $em = $this->mockEntityManager();

        $user = $this->mockUser($plainTextPassword);
        $form = $this->mockForm($user);
        $formFactory = $this->mockFormFactory($form);

        $passwordEncoder = $this->mockPasswordEncoder($cipherTextPassword);

        $formManager = new UserRegisterFormManager($em, $formFactory, $passwordEncoder);

        $formManager->processForm($form);

        $passwordEncoder->shouldHaveReceived('encodePassword')->with($user, $plainTextPassword)->once();
        $user->shouldHaveReceived('setPassword')->with($cipherTextPassword);
    }

    public function testProcess_PersistsUser()
    {
        $em = $this->mockEntityManager();

        $user = $this->mockUser();
        $form = $this->mockForm($user);
        $formFactory = $this->mockFormFactory($form);

        $passwordEncoder = $this->mockPasswordEncoder();

        $formManager = new UserRegisterFormManager($em, $formFactory, $passwordEncoder);

        $formManager->processForm($form);

        $em->shouldHaveReceived('persist')->with($user)->once();
    }

    public function testProcess_CallsFlush()
    {
        $em = $this->mockEntityManager();

        $user = $this->mockUser();
        $form = $this->mockForm($user);
        $formFactory = $this->mockFormFactory($form);

        $passwordEncoder = $this->mockPasswordEncoder();

        $formManager = new UserRegisterFormManager($em, $formFactory, $passwordEncoder);

        $formManager->processForm($form);

        $em->shouldHaveReceived('flush')->once();
    }

    public function testProcess_ReturnsUser()
    {
        $em = $this->mockEntityManager();

        $user = $this->mockUser();
        $form = $this->mockForm($user);
        $formFactory = $this->mockFormFactory($form);

        $passwordEncoder = $this->mockPasswordEncoder();

        $formManager = new UserRegisterFormManager($em, $formFactory, $passwordEncoder);

        $actual = $formManager->processForm($form);

        $this->assertEquals($user, $actual);
    }

    /**
     * @return EntityManagerInterface|m\Mock
     */
    private function mockEntityManager(): EntityManagerInterface
    {
        $em = m::mock(EntityManagerInterface::class);
        $em->shouldReceive('persist');
        $em->shouldReceive('flush');
        return $em;
    }

    /**
     * @return FormFactoryInterface|m\Mock
     */
    private function mockFormFactory(?FormInterface $form = null): FormFactoryInterface
    {
        $formFactory = m::mock(FormFactoryInterface::class);
        $formFactory->shouldReceive('create')->andReturn($form);
        return $formFactory;
    }

    /**
     * @return FormInterface|m\Mock
     */
    private function mockForm(?User $user = null): FormInterface
    {
        $form = m::mock(FormInterface::class);
        $form->shouldReceive('getData')->andReturn($user);
        return $form;
    }

    /**
     * @param string $plainTextPassword
     * @return User|m\Mock
     */
    private function mockUser(string $plainTextPassword = ''): User
    {
        $user = m::mock(User::class);
        $user->shouldReceive('getPlainPassword')->andReturn($plainTextPassword);
        $user->shouldReceive('setPassword');
        return $user;
    }

    /**
     * @return UserPasswordEncoderInterface|m\Mock
     */
    private function mockPasswordEncoder(string $cipherText = ''): UserPasswordEncoderInterface
    {
        $passwordEncoder = m::mock(UserPasswordEncoderInterface::class);
        $passwordEncoder->shouldReceive('encodePassword')->andReturn($cipherText);
        return $passwordEncoder;
    }
}
