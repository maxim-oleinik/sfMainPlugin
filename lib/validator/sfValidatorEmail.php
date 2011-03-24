<?php
namespace sfMainPlugin\Validator;

require_once __DIR__.'/../vendor/iamcal_email/rfc822.php';

/**
 * ValidatorEmail
 *
 * aautoload хак
class sfMainPlugin\Validator\sfValidatorEmail
 */
class sfValidatorEmail extends \sfValidatorBase
{
    /**
     * Validate
     *   - regex
     *   - auto trim
     *   - auto strtolower
     *
     * @throws sfValidatorError
     *
     * @param  mixed $value
     * @return mixed $value
     */
    protected function doClean($value)
    {
        $clean = strtolower(trim($value));

        if (!is_valid_email_address($clean)) {
            throw new \sfValidatorError($this, 'invalid', array('value' => $value));
        }

        return $clean;
    }

}
