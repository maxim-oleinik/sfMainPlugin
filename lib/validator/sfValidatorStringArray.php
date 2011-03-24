<?php
namespace sfMainPlugin\Validator;

/**
 * sfValidatorStringArray
 *
 * Разбивает строки в массив
  *
autoload hack
class sfMainPlugin\Validator\sfValidatorStringArray
 */
class sfValidatorStringArray extends \sfMainPlugin\Validator\sfValidatorString
{
    /**
     * Config
     *
     * - delimiter
     */
    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);

        $this->addOption('delimiter', '\n+|\r+');
        $this->setOption('empty_value', array());
    }


    /**
     * Validate
     *
     * @throws sfValidatorError
     *
     * @param  string $value
     * @return array $value
     */
    protected function doClean($value)
    {
        $value = parent::doClean($value);
        $value = array_filter(preg_split('/'.$this->getOption('delimiter').'/', $value));

        $result = array();
        foreach ($value as $str) {
            $str = trim($str);
            if (! empty($str)) {
                $result[] = $str;
            }
        }

        return $result;
    }
}
