<?php
namespace Test\sfMainPlugin\Unit\Validator;

require_once __DIR__.'/../../bootstrap/all.php';

use sfValidatorSlug;

/**
 * sfValidatorSlugTest
 */
class sfValidatorSlugTest extends \PHPUnit_Framework_TestCase
{
    private $validator;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->validator = new sfValidatorSlug();
    }


    /**
     * Validate
     */
    public function testValidate()
    {
        $plan = array(
            'кириллица' => false,
            'z.z'       => false,
            'z_z'       => false,
            '-zz'       => false,
            'zz-'       => false,
            '--'        => false,
            'z--z'      => false,
            'Zzz'       => false,

            'zz-zz'     => true,
            'zzz'       => true,
            '123'       => true,
        );

        foreach ($plan as $input => $result) {
            $message = sprintf('%s: %d', $input, $result);
            try {
                $this->validator->clean($input);
                $this->assertTrue($result, "Expected validation error for: `{$input}`");
            } catch (\sfValidatorError $e) {
                $this->assertFalse($result, "Expected validation PASSED for: `{$input}`");
            }
        }
    }


    /**
     * Auto trim
     */
    public function testAutoTrim()
    {
        $clean = $this->validator->clean('  some-value  ');
        $this->assertEquals(trim($clean), $clean);
    }

}
