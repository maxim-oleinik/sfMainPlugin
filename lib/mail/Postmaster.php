<?php
namespace sfMainPlugin\Mail;


/**
 * Postmaster: Обработчик почтовых событий
 *   - содержит реестр всех событий
 *   - создает классы почтовых шаблонов по именам
 *   - готовит и отправляет почтовые шаблоны
 */
class Postmaster
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var array Названия классов почтовых шаблонов
     */
    protected $names = array();

    /**
     * @var array Созданные почтовые шаблоны
     */
    protected $templates = array();


    /**
     * Конструктор
     *
     * @param Swift_Mailer $mailer
     * @param array        $names  - array('eventName' => array('MessageTemplate1ClassName', 'MessageTemplate2ClassName'))
     */
    public function __construct(\Swift_Mailer $mailer, array $names = array())
    {
        $this->mailer = $mailer;

        foreach ($names as $name => $classes) {
            $this->names[$name] = (array)$classes;
        }
    }


    /**
     * Получить все почтовые шаблоны для указанного события
     *
     * @param  string $name
     * @return array  sfMainPlugin\Mail\MessageTemplate
     */
    public function getTemplates($name)
    {
        if (!isset($this->names[$name]) && !isset($this->templates[$name])) {
            throw new \InvalidArgumentException(__METHOD__.": Unknown event `{$name}`");
        }

        if (!isset($this->templates[$name])) {
            $mailClasses = $this->names[$name];
            foreach ($mailClasses as $class) {
                $this->addTemplate($name, new $class);
            }
        }

        return array_values($this->templates[$name]);
    }


    /**
     * Добавить почтовый шаблон
     *
     * @param  string $name
     * @param  sfMainPlugin\Mail\MessageTemplate $template
     * @return void
     */
    public function addTemplate($name, MessageTemplate $template)
    {
        if (!isset($this->templates[$name])) {
            $this->templates[$name] = array();
        }
        $this->templates[$name][get_class($template)] = $template;
        // Игнорирует дубликаты
    }


    /**
     * Обработать все события
     *
     * @param  sfEvent $event
     * @return void
     */
    public function process(\sfEvent $event)
    {
        $mails = $this->getTemplates($event->getName());
        foreach ($mails as $template) {
            $message = $template->getMessage($event);
            $this->mailer->batchSend($message);
        }
    }

}
