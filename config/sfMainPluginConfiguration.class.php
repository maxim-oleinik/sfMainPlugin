<?php

/**
 * Plugin config
 */
class sfMainPluginConfiguration extends sfPluginConfiguration
{
    /**
     * Init
     */
    public function initialize()
    {
        // Merge helpers
        $helpers = array_merge(sfConfig::get('sf_standard_helpers', array()), array('sfMainCommon'));
        sfConfig::set('sf_standard_helpers', $helpers);

        // Form formatter
        sfWidgetFormSchema::setDefaultFormFormatterName('ListExt');

        // Register mail events
        if ($mailEvents = sfConfig::get('app_mail_events')) {
            foreach (array_keys($mailEvents) as $name) {
                $this->dispatcher->connect($name, array($this, 'listenToPostmasterEvents'));
            }
        }
    }


    /**
     * Обработчик почтовых событий
     */
    public function listenToPostmasterEvents(sfEvent $event)
    {
        // Load Partial helper for mail templates
        $this->configuration->loadHelpers(array('Partial'));

        if (!isset($this->postmaster)) {
            $this->postmaster = new \sfMainPlugin\Mail\Postmaster(
                sfContext::getInstance()->getMailer(),
                sfConfig::get('app_mail_events', array())
            );
        }
        $this->postmaster->process($event);
    }

}
