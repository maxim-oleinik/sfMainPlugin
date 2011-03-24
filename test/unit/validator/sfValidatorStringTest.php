<?php
namespace Test\sfMainPlugin\Unit\Validator;

require_once __DIR__.'/../../bootstrap/all.php';

use sfMainPlugin\Validator\sfValidatorString;

/**
 * sfValidatorStringTest
 */
class sfValidatorStringTest extends \PHPUnit_Framework_TestCase
{
    protected $preserveGlobalState = false;
    private $validator;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->validator = new sfValidatorString();
    }


    /**
     * Auto trim
     */
    public function testAutoTrim()
    {
        $clean = $this->validator->clean('  Some-Value  ');
        $this->assertEquals(trim($clean), $clean);
    }


    /**
     * To lower
     */
    public function testToLower()
    {
        $this->validator = new sfValidatorString(array('to_lower' => true));
        $clean = $this->validator->clean('ЯЯЯ');
        $this->assertEquals('яяя', $clean);
    }


    /**
     * Каждое слово с заглавной буквы
     */
    public function testToUpperCaseEachWord()
    {
        $this->validator = new sfValidatorString(array('trim' => false, 'ucwords' => true));
        $clean = $this->validator->clean('вася  пУПкин');
        $this->assertEquals('Вася  Пупкин', $clean);
    }

}
