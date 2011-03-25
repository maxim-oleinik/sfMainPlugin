<?php
namespace sfMainPlugin\Validator;


/**
 * sfValidatorSlug
 */
class sfValidatorSlug extends \sfValidatorString
{
    const REGEX = '/^[a-z0-9]+[a-z0-9\-]*[a-z0-9]+$/';

    /**
     * Validate
     *   - regex
     *   - auto trim
     *
     * @throws sfValidatorError
     *
     * @param  mixed $value
     * @return mixed $value
     */
    protected function doClean($value)
    {
        $clean = parent::doClean(trim($value));

        if (strpos($value, '--') || !preg_match(self::REGEX, $clean)) {
            throw new \sfValidatorError($this, 'invalid', array('value' => $value));
        }

        return $clean;
    }

}
