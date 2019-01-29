<?php

namespace App\Test\FormManager\Walker;

use App\Entity\Team;
use App\Entity\Walker;
use App\Form\Walker\WalkerType;
use App\FormManager\Walker\WalkerCreateFormManager;
use App\Service\WalkerReferenceCharacterService;
use App\Tests\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Mockery as m;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class WalkerCreateFormManagerTest extends TestCase
{
    public function testCreate_CallsFormFactory()
    {
        $em = $this->mockEntityManager();
        $formFactory = $this->mockFormFactory($this->mockForm());
        $walkerReferenceCharacterService = $this->mockWalkerReferenceCharacterService();
        $formManager = new WalkerCreateFormManager($em, $formFactory, $walkerReferenceCharacterService);
        $team = $this->mockTeam();

        $formManager->createForm($team);

        $formFactory->shouldHaveReceived('create')->withArgs(function($type, $walker) use ($team) {
            $this->assertEquals(WalkerType::class, $type);
            $this->assertInstanceOf(Walker::class, $walker);
            $this->assertEquals($team, $walker->getTeam());
            return true;
        })->once();
    }

    public function testCreate_ReturnsForm()
    {
        $em = $this->mockEntityManager();
        $form = $this->mockForm();
        $formFactory = $this->mockFormFactory($form);
        $walkerReferenceCharacterService = $this->mockWalkerReferenceCharacterService();
        $formManager = new WalkerCreateFormManager($em, $formFactory, $walkerReferenceCharacterService);

        $actual = $formManager->createForm($this->mockTeam());
        $this->assertEquals($form, $actual);
    }

    public function testProcess_SetsReferenceCharacterOnWalker()
    {
        $em = $this->mockEntityManager();
        $nextReferenceCharacter = 'B';

        $team = $this->mockTeam();
        $walker = $this->mockWalker($team);
        $form = $this->mockForm($walker);
        $formFactory = $this->mockFormFactory($form);

        $walkerReferenceCharacterService = $this->mockWalkerReferenceCharacterService($nextReferenceCharacter);
        $formManager = new WalkerCreateFormManager($em, $formFactory, $walkerReferenceCharacterService);

        $formManager->processForm($form);

        $walker->shouldHaveReceived('setReferenceCharacter')->with($nextReferenceCharacter)->once();
    }

    public function testProcess_PersistsWalker()
    {
        $em = $this->mockEntityManager();

        $team = $this->mockTeam();
        $walker = $this->mockWalker($team);
        $form = $this->mockForm($walker);
        $formFactory = $this->mockFormFactory($form);

        $walkerReferenceCharacterService = $this->mockWalkerReferenceCharacterService();
        $formManager = new WalkerCreateFormManager($em, $formFactory, $walkerReferenceCharacterService);

        $formManager->processForm($form);

        $em->shouldHaveReceived('persist')->with($walker)->once();
    }

    public function testProcess_CallsFlush()
    {
        $em = $this->mockEntityManager();

        $team = $this->mockTeam();
        $walker = $this->mockWalker($team);
        $form = $this->mockForm($walker);
        $formFactory = $this->mockFormFactory($form);

        $walkerReferenceCharacterService = $this->mockWalkerReferenceCharacterService();
        $formManager = new WalkerCreateFormManager($em, $formFactory, $walkerReferenceCharacterService);

        $formManager->processForm($form);

        $em->shouldHaveReceived('flush')->once();
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
    private function mockForm(?Walker $walker = null): FormInterface
    {
        $form = m::mock(FormInterface::class);
        $form->shouldReceive('getData')->andReturn($walker);
        return $form;
    }

    /**
     * @param Team $team
     * @return Walker
     */
    private function mockWalker(Team $team): Walker
    {
        $walker = m::mock(Walker::class);
        $walker->shouldReceive('setReferenceCharacter');
        $walker->shouldReceive('getTeam')->andReturn($team);
        return $walker;
    }

    /**
     * @return Team|m\Mock
     */
    private function mockTeam(): Team
    {
        $team = m::mock(Team::class);
        return $team;
    }

    /**
     * @param string $nextAvailableReferenceCharacter
     * @return WalkerReferenceCharacterService
     */
    private function mockWalkerReferenceCharacterService(
        string $nextAvailableReferenceCharacter = 'A'
    ): WalkerReferenceCharacterService {
        $walkerReferenceCharacterService = m::mock(WalkerReferenceCharacterService::class);
        $walkerReferenceCharacterService
            ->shouldReceive('getNextAvailable')
            ->andReturn($nextAvailableReferenceCharacter);

        return $walkerReferenceCharacterService;
    }
}
