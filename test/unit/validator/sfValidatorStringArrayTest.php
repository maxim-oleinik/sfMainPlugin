<?php
namespace Test\sfMainPlugin\Unit\Validator;

require_once __DIR__.'/../../bootstrap/all.php';

use sfMainPlugin\Validator\sfValidatorStringArray;


/**
 * sfValidatorSlugTest
 */
class sfValidatorStringArrayTest extends \PHPUnit_Framework_TestCase
{
    protected $preserveGlobalState = false;

    /**
     * Validate
     */
    public function testValidate()
    {
        $validator = new sfValidatorStringArray(array('required' => false));

        $plan = array(
            "123\n456\r789\r\r"           => array('123', '456', '789'),
            "1\n 2 \n\r 3  \r\n4 \r\r\r5" => array('1', '2', '3', '4', '5'),
            "\n\r\n"                      => array(),
            "123"                         => array('123'),
        );

        foreach ($plan as $input => $expected) {
            $clean = $validator->clean($input);
            $this->assertEquals($expected, $clean);
        }
    }


    /**
     * Custom delmitter
     */
    public function testCustomDelmitter()
    {
        $validator = new sfValidatorStringArray(array('delimiter' => ";"));

        $clean = $validator->clean('aaa;bbb');
        $this->assertEquals(array('aaa', 'bbb'), $clean);
    }
}
