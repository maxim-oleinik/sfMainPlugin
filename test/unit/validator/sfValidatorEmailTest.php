<?php
namespace Test\sfMainPlugin\Unit\Validator;

require_once __DIR__.'/../../bootstrap/all.php';

use sfMainPlugin\Validator\sfValidatorEmail;

/**
 * sfValidatorEmailTest
 */
class sfValidatorEmailTest extends \PHPUnit_Framework_TestCase
{
    protected $preserveGlobalState = false;
    private $validator;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->validator = new sfValidatorEmail();
    }


    /**
     * Validate
     */
    public function testValidate()
    {
        // http://www.linuxjournal.com/article/9585?page=2,2
        // http://fightingforalostcause.net/misc/2006/compare-email-regex.php

        $plan = array(
            // OK
            "dclo@us.ibm.com"                          => true,
            "\"abc\\@def\"@example.com"                => true,
            "\"abc\\\\\"@example.com"                  => true,
            "\"Fred\\ Bloggs\"@example.com"            => true,
            "\"Joe.\\\\Blow\"@example.com"             => true,
            "\"Abc@def\"@example.com"                  => true,
            "\"Fred Bloggs\"@example.com"              => true,
            "customer/department=shipping@example.com" => true,
            "\$A12345@example.com"                     => true,
            "!def!xyz%abc@example.com"                 => true,
            "_somename@example.com"                    => true,
            "user+mailbox@example.com"                 => true,
            "peter.piper@example.com"                  => true,
            "\"Doug\\ \\\"Ace\\\"\\ Lovell\"@example.com"  => true,
            "\"Doug \\\"Ace\\\" L.\"@example.com"      => true,
            'l3tt3rsAndNumb3rs@domain.com'             => true,
            'has-dash@domain.com'                      => true,
            'hasApostrophe.o\'leary@domain.org'        => true,
            'uncommonTLD@domain.museum'                => true,
            'uncommonTLD@domain.travel'                => true,
            'uncommonTLD@domain.mobi'                  => true,
            'countryCodeTLD@domain.uk'                 => true,
            'countryCodeTLD@domain.rw'                 => true,
            'lettersInDomain@911.com'                  => true,
            'underscore_inLocal@domain.net'            => true,
#            'IPInsteadOfDomain@127.0.0.1'              => true,
#            'IPAndPort@127.0.0.1:25'                   => true,
            'subdomain@sub.domain.com'                 => true,
            'local@dash-inDomain.com'                  => true,
            'dot.inLocal@foo.com'                      => true,
            'a@singleLetterLocal.org'                  => true,
            'singleLetterDomain@x.org'                 => true,
            '&*=?^+{}\'~@validCharsInLocal.net'        => true,
            'foor@bar.newTLD'                          => true,

            // Fail
            'not email'                                    => false,
            'не почта'                                     => false,
            'mail,box@gmail.com'                           => false,
            '<script>alert(\'t\');</script>@domain.com'    => false,
            'mail@тест.рф'                                 => false,
#            'mail@xn--e1aybc.xn--p1ai'                     => false,
            'mail@local'                                   => false,
#            'mail@domain.local'                            => false,
            "abc@def@example.com"                          => false,
            "abc\\\\@def@example.com"                      => false,
            "abc\\@example.com"                            => false,
            "@example.com"                                 => false,
            "doug@"                                        => false,
            "\"qu@example.com"                             => false,
            "ote\"@example.com"                            => false,
            ".dot@example.com"                             => false,
            "dot.@example.com"                             => false,
            "two..dot@example.com"                         => false,
            "\"Doug \"Ace\" L.\"@example.com"              => false,
            "Doug\\ \\\"Ace\\\"\\ L\\.@example.com"        => false,
            "hello world@example.com"                      => false,
            "gatsby@f.sc.ot.t.f.i.tzg.era.l.d."            => false,
            "missingDomain@.com"                           => false,
            "@missingLocal.org"                            => false,
            "missingatSign.net"                            => false,
            "missingDot@com"                               => false,
            "two@@signs.com"                               => false,
            "colonButNoPort@127.0.0.1:"                    => false,
            "someone-else@127.0.0.1.26"                    => false,
            ".localStartsWithDot@domain.com"               => false,
            "localEndsWithDot.@domain.com"                 => false,
            "two..consecutiveDots@domain.com"              => false,
            "domainStartsWithDash@-domain.com"             => false,
            "domainEndsWithDash@domain-.com"               => false,
#            "numbersInTLD@domain.c0m"                      => false,
            "missingTLD@domain."                           => false,
            "! \"#$%(),/;<>[]`|@invalidCharsInLocal.org"   => false,
            "invalidCharsInDomain@! \"#$%(),/;<>_[]`|.org" => false,
            "local@SecondLevelDomainNamesAreInvalidIfTheyAreLongerThan64Charactersss.org" => false,
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

}
