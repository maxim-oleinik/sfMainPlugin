<?php
namespace sfMainPlugin\Mail;


/**
 * MessageTemplate: Базовый класс почтового шаблона
 */
abstract class MessageTemplate
{
    /**
     * Получить сообщение
     *
     * @return Swift_Message
     */
    abstract public function getMessage(\sfEvent $event);


    /**
     * Создать пустое шаблонное сообщение с дефолтной конфигурацией
     *
     * @return Swift_Message
     */
    public function createTemplateMessage()
    {
        $message = new \Swift_Message;
        $message->setEncoder(new \Swift_Mime_ContentEncoder_Base64ContentEncoder);
        return $message;
    }


    /**
     * Подключить партиал из указанного шаблона
     *
     * @param  string $templateName
     * @param  array  $vars
     * @return string
     */
    protected function getPartial($templateName, array $vars = null)
    {
        return get_partial($templateName, $vars);
    }

}
