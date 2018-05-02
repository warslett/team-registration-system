<?php

namespace App\Test\FormManager\Team\TeamCreateFormManager;

use App\Entity\Team;
use App\Entity\User;
use App\Form\Team\TeamCreateType;
use App\FormManager\Team\TeamCreateFormManager;
use App\Service\CurrentUserService;
use App\Tests\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Mockery as m;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class TeamCreateFormManagerTest extends TestCase
{

    public function testCreate_CallsFormFactory()
    {
        $em = $this->mockEntityManager();
        $formFactory = $this->mockFormFactory($this->mockForm());
        $currentUserService = $this->mockCurrentUserService();
        $formManager = new TeamCreateFormManager($em, $formFactory, $currentUserService);

        $formManager->createForm();

        $formFactory->shouldHaveReceived('create')->with(
            TeamCreateType::class,
            m::type(Team::class)
        );
    }

    public function testCreate_ReturnsForm()
    {
        $em = $this->mockEntityManager();
        $form = $this->mockForm();
        $formFactory = $this->mockFormFactory($form);
        $currentUserService = $this->mockCurrentUserService();
        $formManager = new TeamCreateFormManager($em, $formFactory, $currentUserService);

        $actual = $formManager->createForm();
        $this->assertEquals($form, $actual);
    }

    public function testProcess_SetsUserOnTeam()
    {
        $em = $this->mockEntityManager();

        $team = $this->mockTeam();
        $form = $this->mockForm($team);
        $formFactory = $this->mockFormFactory($form);

        $user = $this->mockUser();
        $currentUserService = $this->mockCurrentUserService($user);

        $formManager = new TeamCreateFormManager($em, $formFactory, $currentUserService);

        $formManager->processForm($form);

        $team->shouldHaveReceived('setUser')->with($user);
    }

    public function testProcess_PersistsTeam()
    {
        $em = $this->mockEntityManager();

        $team = $this->mockTeam();
        $form = $this->mockForm($team);
        $formFactory = $this->mockFormFactory($form);

        $user = $this->mockUser();
        $currentUserService = $this->mockCurrentUserService($user);

        $formManager = new TeamCreateFormManager($em, $formFactory, $currentUserService);

        $formManager->processForm($form);

        $em->shouldHaveReceived('persist')->with($team);
    }

    public function testProcess_CallsFlush()
    {
        $em = $this->mockEntityManager();

        $team = $this->mockTeam();
        $form = $this->mockForm($team);
        $formFactory = $this->mockFormFactory($form);

        $user = $this->mockUser();
        $currentUserService = $this->mockCurrentUserService($user);

        $formManager = new TeamCreateFormManager($em, $formFactory, $currentUserService);

        $formManager->processForm($form);

        $em->shouldHaveReceived('flush');
    }

    public function testProcess_ReturnsTeam()
    {
        $em = $this->mockEntityManager();

        $team = $this->mockTeam();
        $form = $this->mockForm($team);
        $formFactory = $this->mockFormFactory($form);

        $user = $this->mockUser();
        $currentUserService = $this->mockCurrentUserService($user);

        $formManager = new TeamCreateFormManager($em, $formFactory, $currentUserService);

        $actual = $formManager->processForm($form);

        $this->assertEquals($team, $actual);
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
     * @param User|null $user
     * @return CurrentUserService
     */
    private function mockCurrentUserService(?User $user = null): CurrentUserService
    {
        $currentUserService = m::mock(CurrentUserService::class);
        $currentUserService->shouldReceive('getCurrentUser')->andReturn($user);
        return $currentUserService;
    }

    /**
     * @return FormInterface|m\Mock
     */
    private function mockForm(?Team $team = null): FormInterface
    {
        $form = m::mock(FormInterface::class);
        $form->shouldReceive('getData')->andReturn($team);
        return $form;
    }

    /**
     * @return Team|m\Mock
     */
    private function mockTeam(): Team
    {
        $team = m::mock(Team::class);
        $team->shouldReceive('setUser');
        return $team;
    }

    private function mockUser(): User
    {
        $user = m::mock(User::class);
        return $user;
    }
}
