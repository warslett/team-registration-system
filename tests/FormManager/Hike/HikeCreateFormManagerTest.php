<?php

namespace App\Text\FormManager\Hike;

use App\Entity\Event;
use App\Entity\Hike;
use App\Form\Hike\HikeCreateType;
use App\FormManager\Hike\HikeCreateFormManager;
use App\Tests\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Mockery as m;
use Symfony\Component\Form\FormInterface;

class HikeCreateFormManagerTest extends TestCase
{

    public function testCreate_CallsFormFactory()
    {
        $em = $this->mockEntityManager();
        $formFactory = $this->mockFormFactory($this->mockForm());
        $formManager = new HikeCreateFormManager($em, $formFactory);
        $event = $this->mockEvent();

        $formManager->createForm($event);

        $formFactory->shouldHaveReceived('create')->withArgs(function($type, $hike) use ($event) {
            $this->assertEquals(HikeCreateType::class, $type);
            $this->assertInstanceOf(Hike::class, $hike);
            $this->assertEquals($event, $hike->getEvent());
            return true;
        })->once();
    }

    public function testCreate_ReturnsForm()
    {
        $em = $this->mockEntityManager();
        $form = $this->mockForm();
        $formFactory = $this->mockFormFactory($form);
        $formManager = new HikeCreateFormManager($em, $formFactory);

        $actual = $formManager->createForm($this->mockEvent());
        $this->assertEquals($form, $actual);
    }


    public function testProcess_PersistsHike()
    {
        $em = $this->mockEntityManager();

        $hike = $this->mockHike();
        $form = $this->mockForm($hike);
        $formFactory = $this->mockFormFactory($form);

        $formManager = new HikeCreateFormManager($em, $formFactory);

        $formManager->processForm($form);

        $em->shouldHaveReceived('persist')->with($hike);
    }

    public function testProcess_CallsFlush()
    {
        $em = $this->mockEntityManager();

        $hike = $this->mockHike();
        $form = $this->mockForm($hike);
        $formFactory = $this->mockFormFactory($form);

        $formManager = new HikeCreateFormManager($em, $formFactory);

        $formManager->processForm($form);

        $em->shouldHaveReceived('flush');
    }

    public function testProcess_ReturnsHike()
    {
        $em = $this->mockEntityManager();

        $hike = $this->mockHike();
        $form = $this->mockForm($hike);
        $formFactory = $this->mockFormFactory($form);

        $formManager = new HikeCreateFormManager($em, $formFactory);

        $formManager->processForm($form);


        $actual = $formManager->processForm($form);

        $this->assertEquals($hike, $actual);
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
    private function mockForm(?Hike $hike = null): FormInterface
    {
        $form = m::mock(FormInterface::class);
        $form->shouldReceive('getData')->andReturn($hike);
        return $form;
    }

    /**
     * @return Event|m\Mock
     */
    private function mockEvent(): Event
    {
        $event = m::mock(Event::class);
        return $event;
    }

    /**
     * @return Hike|m\Mock
     */
    private function mockHike(): Hike
    {
        $hike = m::mock(Hike::class);
        return $hike;
    }
}
