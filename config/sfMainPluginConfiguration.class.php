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
    }

}
