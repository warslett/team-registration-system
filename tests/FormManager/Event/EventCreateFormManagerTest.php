<?php

use App\Entity\Event;
use App\Form\Event\EventType;
use App\FormManager\Event\EventCreateFormManager;
use App\Tests\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Mockery as m;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class EventCreateFormManagerTest extends TestCase
{

    public function testCreate_CallsFormFactory()
    {
        $em = $this->mockEntityManager();
        $formFactory = $this->mockFormFactory($this->mockForm());
        $formManager = new EventCreateFormManager($em, $formFactory);

        $formManager->createForm();

        $formFactory->shouldHaveReceived('create')->with(
            EventType::class,
            m::type(Event::class)
        )->once();
    }

    public function testCreate_ReturnsForm()
    {
        $em = $this->mockEntityManager();
        $form = $this->mockForm();
        $formFactory = $this->mockFormFactory($form);
        $formManager = new EventCreateFormManager($em, $formFactory);

        $actual = $formManager->createForm();
        $this->assertEquals($form, $actual);
    }


    public function testProcess_PersistsEvent()
    {
        $em = $this->mockEntityManager();

        $event = $this->mockEvent();
        $form = $this->mockForm($event);
        $formFactory = $this->mockFormFactory($form);

        $formManager = new EventCreateFormManager($em, $formFactory);

        $formManager->processForm($form);

        $em->shouldHaveReceived('persist')->with($event);
    }

    public function testProcess_CallsFlush()
    {
        $em = $this->mockEntityManager();

        $event = $this->mockEvent();
        $form = $this->mockForm($event);
        $formFactory = $this->mockFormFactory($form);

        $formManager = new EventCreateFormManager($em, $formFactory);

        $formManager->processForm($form);

        $em->shouldHaveReceived('flush');
    }

    public function testProcess_ReturnsTeam()
    {
        $em = $this->mockEntityManager();

        $event = $this->mockEvent();
        $form = $this->mockForm($event);
        $formFactory = $this->mockFormFactory($form);

        $formManager = new EventCreateFormManager($em, $formFactory);

        $formManager->processForm($form);

        $actual = $formManager->processForm($form);

        $this->assertEquals($event, $actual);
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
     * @param Event|null $event
     * @return FormInterface|m\Mock
     */
    private function mockForm(?Event $event = null): FormInterface
    {
        $form = m::mock(FormInterface::class);
        $form->shouldReceive('getData')->andReturn($event);
        return $form;
    }

    private function mockEvent(): Event
    {
        $event = m::mock(Event::class);
        return $event;
    }

}
