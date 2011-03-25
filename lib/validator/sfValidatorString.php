<?php
namespace sfMainPlugin\Validator;


/**
 * sfValidatorString
 */
class sfValidatorString extends \sfValidatorString
{
    /**
     * Config
     */
    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);

        $this->addOption('to_lower', false);
        $this->addOption('ucwords', false);

        $this->setOption('trim', isset($options['trim']) ? (bool)$options['trim'] : true);
    }


    /**
     * Validate
     *   - auto trim
     *   - to lower
     *   - to ucfirst each word
     *
     * @throws sfValidatorError
     *
     * @param  mixed $value
     * @return mixed $value
     */
    protected function doClean($value)
    {
        $clean = parent::doClean($value);

        if ($this->getOption('to_lower')) {
            $clean = mb_strtolower($clean);
        }

        if ($this->getOption('ucwords')) {
            $clean = mb_convert_case($clean, MB_CASE_TITLE, "UTF-8");
        }

        return $clean;
    }

}
