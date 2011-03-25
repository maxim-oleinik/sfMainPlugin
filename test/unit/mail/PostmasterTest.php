<?php
namespace Test\sfMainPlugin\Unit\Mail;

require_once __DIR__.'/../../bootstrap/all.php';

use sfEvent;
use sfMainPlugin\Mail\MessageTemplate;
use sfMainPlugin\Mail\Postmaster;


/**
 * Тестовые классы
 */
class PostmasterTest_MessageTemplate1 extends MessageTemplate
{
    public function getMessage(sfEvent $event) {}
}
class PostmasterTest_MessageTemplate1_Message extends \Swift_Message {}


class PostmasterTest_MessageTemplate2 extends MessageTemplate
{
    public function getMessage(sfEvent $event) {}
}
class PostmasterTest_MessageTemplate2_Message extends \Swift_Message {}


/**
 * PostmasterTest
 */
class PostmasterTest extends \PHPUnit_Framework_TestCase
{
    protected $preserveGlobalState = false;

    private
        $mailer,
        $handler;


    /**
     * SetUp
     */
    public function setUp()
    {
        $this->mailer = $this->getMock('Swift_Mailer', array('batchSend'), array(new \Swift_NullTransport));

        $this->handler = new Postmaster($this->mailer, array(
            'event1' => 'Test\sfMainPlugin\Unit\Mail\PostmasterTest_MessageTemplate1',
            'event2' => array(
                'Test\sfMainPlugin\Unit\Mail\PostmasterTest_MessageTemplate1',
                'Test\sfMainPlugin\Unit\Mail\PostmasterTest_MessageTemplate2',
                // Игнорировать дубликаты почтовых шаблонов
                'Test\sfMainPlugin\Unit\Mail\PostmasterTest_MessageTemplate1',
            ),
        ));
    }


    /**
     * Получить почтовое событие по имени
     */
    public function testGetMessageTemplateByName()
    {
        $events = $this->handler->getTemplates('event1');
        $this->assertEquals(array(new PostmasterTest_MessageTemplate1), $events);

        // Дважды не создавать
        $this->assertSame($events, $this->handler->getTemplates('event1'));
    }


    /**
     * Получить почтовых несколько событий для одного имени
     */
    public function testGetMoreMessageTemplatesBySameName()
    {
        $events = $this->handler->getTemplates('event2');
        $this->assertEquals(array(new PostmasterTest_MessageTemplate1, new PostmasterTest_MessageTemplate2), $events);

        // Дважды не создавать
        $this->assertSame($events, $this->handler->getTemplates('event2'));
    }


    /**
     * Почтовый шаблон может быть только наследником MessageTemplate
     */
    public function testMessageTemplateTypeHint()
    {
        $this->handler = new Postmaster($this->mailer, array(
            'event1' => 'StdClass',
        ));
        $this->setExpectedException('Exception', 'must be an instance of sfMainPlugin\Mail\MessageTemplate');
        $this->handler->getTemplates('event1');
    }


    /**
     * Событие не зарегистрировано
     */
    public function testMessageTemplateNotDefined()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->handler->getTemplates('unknown');
    }


    /**
     * Обработка события с одним шаблоном
     */
    public function testProcessEventWithOneTemplate()
    {
        $event   = new sfEvent('subject', 'event1');
        $message = new PostmasterTest_MessageTemplate1_Message;

        // Было создано нужное письмо
        $mail1 = $this->getMock('Test\sfMainPlugin\Unit\Mail\PostmasterTest_MessageTemplate1', array('getMessage'));
        $mail1->expects($this->once())
            ->method('getMessage')
            ->with($event)
            ->will($this->returnValue($message));

        //  Нужное письмо было отправлено
        $this->mailer->expects($this->once())
            ->method('batchSend')
            ->with($message);

        $this->handler->addTemplate('event1', $mail1);
        $this->handler->process($event);
    }


    /**
     * Обработка события с несколькими шаблонами
     */
    public function testProcessEventWithSomeTemplates()
    {
        $event    = new sfEvent('subject', 'event2');
        $message1 = new PostmasterTest_MessageTemplate1_Message;
        $message2 = new PostmasterTest_MessageTemplate2_Message;

        // Первое письмо
        $mail1 = $this->getMock('Test\sfMainPlugin\Unit\Mail\PostmasterTest_MessageTemplate1', array('getMessage'));
        $mail1->expects($this->once())
            ->method('getMessage')
            ->with($event)
            ->will($this->returnValue($message1));

        // Второе письмо
        $mail2 = $this->getMock('Test\sfMainPlugin\Unit\Mail\PostmasterTest_MessageTemplate2', array('getMessage'));
        $mail2->expects($this->once())
            ->method('getMessage')
            ->with($event)
            ->will($this->returnValue($message2));

        // Было отправлено 2 письма
        $this->mailer->expects($this->exactly(2))
            ->method('batchSend');

        $this->handler->addTemplate('event2', $mail1);
        $this->handler->addTemplate('event2', $mail2);
        $this->handler->process($event);
    }

}
