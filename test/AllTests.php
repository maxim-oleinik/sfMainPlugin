<?php
namespace Test\sfMainPlugin;

require_once dirname(__FILE__).'/bootstrap/all.php';


/**
 * AllTests
 */
class AllTests extends \PHPUnit_Framework_TestSuite
{
    /**
     * TestSuite
     */
    public static function suite()
    {
        $suite = new AllTests('sfMainPlugin');

        $base  = dirname(__FILE__);
        $files = \sfFinder::type('file')->name('*Test.php')->in(array(
            $base.'/unit',
        ));

        foreach ($files as $file) {
            $suite->addTestFile($file);
        }

        return $suite;
    }

}
