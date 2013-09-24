<?php
require_once '../localization.class.php';
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * pid test case.
 */
class localizationTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var localization
     */
    private $localization;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp();
        $this->localization = new u4u\localization();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->localization = null;
        parent::tearDown();
    }

    public function test_sendHeaders() {
        $this->markTestIncomplete('Not completed yet');
    }

    /**
     * Important in this test: test several valid and invalid browser HTTP_ACCEPT_LANGUAGE tags
     */
    public function test_autodetectLocale() {
        $this->markTestIncomplete('Not completed yet');
    }

    /**
     * Data provider for formatSimpleNumber
     *
     * @return array
     */
    public function provider_formatSimpleNumber() {
        $mapValues[] = array('pt-BR', 0, -1, -1, '0');
        $mapValues[] = array('pt-BR', 0,  2, -1, '0,00');
        $mapValues[] = array('pt-BR', 0,  2,  6, '0,00');
        $mapValues[] = array('pt-BR', 1234.5678,  -1, -1, '1.234,568');
        $mapValues[] = array('pt-BR', 1234.5678,  -1, 4,  '1.234,5678');
        $mapValues[] = array('pt-BR', 1234.5678,  -1, 6,  '1.234,5678');
        $mapValues[] = array('pt-BR', -1234.5678, -1, 2,  '-1.234,57');

        $mapValues[] = array('pt-PT', 0, -1, -1, '0');
        $mapValues[] = array('pt-PT', 0,  2, -1, '0,00');
        $mapValues[] = array('pt-PT', 0,  2,  6, '0,00');
        $mapValues[] = array('pt-PT', 1234.5678,  -1, -1, '1 234,568');
        $mapValues[] = array('pt-PT', 1234.5678,  -1, 4,  '1 234,5678');
        $mapValues[] = array('pt-PT', 1234.5678,  -1, 6,  '1 234,5678');
        $mapValues[] = array('pt-PT', -1234.5678, -1, 2,  '-1 234,57');

        $mapValues[] = array('ko-KR', 0, -1, -1, '0');
        $mapValues[] = array('ko-KR', 0,  2, -1, '0.00');
        $mapValues[] = array('ko-KR', 0,  2,  6, '0.00');
        $mapValues[] = array('ko-KR', 1234.5678,  -1, -1, '1,234.568');
        $mapValues[] = array('ko-KR', 1234.5678,  -1, 4,  '1,234.5678');
        $mapValues[] = array('ko-KR', 1234.5678,  -1, 6,  '1,234.5678');
        $mapValues[] = array('ko-KR', -1234.5678, -1, 2,  '-1,234.57');

        $mapValues[] = array('nl-NL', 0, -1, -1, '0');
        $mapValues[] = array('nl-NL', 0,  2, -1, '0,00');
        $mapValues[] = array('nl-NL', 0,  2,  6, '0,00');
        $mapValues[] = array('nl-NL', 1234.5678,  -1, -1, '1.234,568');
        $mapValues[] = array('nl-NL', 1234.5678,  -1, 6,  '1.234,5678');
        $mapValues[] = array('nl-NL', -1234.5678, -1, 2,  '-1.234,57');

        $mapValues[] = array('de-DE', 0, -1, -1, '0');
        $mapValues[] = array('de-DE', 0,  2, -1, '0,00');
        $mapValues[] = array('de-DE', 0,  2,  6, '0,00');
        $mapValues[] = array('de-DE', 1234.5678,  -1, -1, '1.234,568');
        $mapValues[] = array('de-DE', 1234.5678,  -1, 6,  '1.234,5678');
        $mapValues[] = array('de-DE', -1234.5678, -1, 2,  '-1.234,57');

        $mapValues[] = array('en-CA', 0, -1, -1, '0');
        $mapValues[] = array('en-CA', 0,  2, -1, '0.00');
        $mapValues[] = array('en-CA', 0,  2,  6, '0.00');
        $mapValues[] = array('en-CA', 1234.5678,  -1, -1, '1,234.568');
        $mapValues[] = array('en-CA', 1234.5678,  -1, 6,  '1,234.5678');
        $mapValues[] = array('en-CA', -1234.5678, -1, 2,  '-1,234.57');

        $mapValues[] = array('en-GB', 0, -1, -1, '0');
        $mapValues[] = array('en-GB', 0,  2, -1, '0.00');
        $mapValues[] = array('en-GB', 0,  2,  6, '0.00');
        $mapValues[] = array('en-GB', 1234.5678,  -1, -1, '1,234.568');
        $mapValues[] = array('en-GB', 1234.5678,  -1, 6,  '1,234.5678');
        $mapValues[] = array('en-GB', -1234.5678, -1, 2,  '-1,234.57');

        $mapValues[] = array('en-US', 0, -1, -1, '0');
        $mapValues[] = array('en-US', 0,  2, -1, '0.00');
        $mapValues[] = array('en-US', 0,  2,  6, '0.00');
        $mapValues[] = array('en-US', 1234.5678,  -1, -1, '1,234.568');
        $mapValues[] = array('en-US', 1234.5678,  -1, 6,  '1,234.5678');
        $mapValues[] = array('en-US', -1234.5678, -1, 2,  '-1,234.57');

        return $mapValues;
    }

    /**
     * @dataProvider provider_formatSimpleNumber
     */
    public function test_formatSimpleNumber($locale, $value, $minimumDigits, $maximumDigits, $expected) {
        $this->localization->setDefault($locale);
        $result = $this->localization->formatSimpleNumber($value, $minimumDigits, $maximumDigits);

        $this->assertEquals($result, $expected);
    }

    /**
     * Data provider for all currency related functions
     */
    public function provider_formatSimpleCurrency() {
        $mapValues[] = array('pt-BR', 0, -1, -1, 'R$0,00');
        $mapValues[] = array('pt-BR', 0,  2, -1, 'R$0,00');
        $mapValues[] = array('pt-BR', 0,  2,  6, 'R$0,00');
        $mapValues[] = array('pt-BR', 1234.5678,  -1, -1, 'R$1.234,57');
        $mapValues[] = array('pt-BR', 1234.5678,  -1, 4,  'R$1.234,5678');
        $mapValues[] = array('pt-BR', 1234.5678,  -1, 6,  'R$1.234,5678');
        $mapValues[] = array('pt-BR', -1234.5678, -1, 2,  '(R$1.234,57)');
/*
        $mapValues[] = array('pt-PT', 0, -1, -1, '0');
        $mapValues[] = array('pt-PT', 0,  2, -1, '0,00');
        $mapValues[] = array('pt-PT', 0,  2,  6, '0,00');
        $mapValues[] = array('pt-PT', 1234.5678,  -1, -1, '1 234,568');
        $mapValues[] = array('pt-PT', 1234.5678,  -1, 4,  '1 234,5678');
        $mapValues[] = array('pt-PT', 1234.5678,  -1, 6,  '1 234,5678');
        $mapValues[] = array('pt-PT', -1234.5678, -1, 2,  '-1 234,57');

        $mapValues[] = array('ko-KR', 0, -1, -1, '0');
        $mapValues[] = array('ko-KR', 0,  2, -1, '0.00');
        $mapValues[] = array('ko-KR', 0,  2,  6, '0.00');
        $mapValues[] = array('ko-KR', 1234.5678,  -1, -1, '1,234.568');
        $mapValues[] = array('ko-KR', 1234.5678,  -1, 4,  '1,234.5678');
        $mapValues[] = array('ko-KR', 1234.5678,  -1, 6,  '1,234.5678');
        $mapValues[] = array('ko-KR', -1234.5678, -1, 2,  '-1,234.57');

        $mapValues[] = array('nl-NL', 0, -1, -1, '0');
        $mapValues[] = array('nl-NL', 0,  2, -1, '0,00');
        $mapValues[] = array('nl-NL', 0,  2,  6, '0,00');
        $mapValues[] = array('nl-NL', 1234.5678,  -1, -1, '1.234,568');
        $mapValues[] = array('nl-NL', 1234.5678,  -1, 6,  '1.234,5678');
        $mapValues[] = array('nl-NL', -1234.5678, -1, 2,  '-1.234,57');

        $mapValues[] = array('de-DE', 0, -1, -1, '0');
        $mapValues[] = array('de-DE', 0,  2, -1, '0,00');
        $mapValues[] = array('de-DE', 0,  2,  6, '0,00');
        $mapValues[] = array('de-DE', 1234.5678,  -1, -1, '1.234,568');
        $mapValues[] = array('de-DE', 1234.5678,  -1, 6,  '1.234,5678');
        $mapValues[] = array('de-DE', -1234.5678, -1, 2,  '-1.234,57');

        $mapValues[] = array('en-CA', 0, -1, -1, '0');
        $mapValues[] = array('en-CA', 0,  2, -1, '0.00');
        $mapValues[] = array('en-CA', 0,  2,  6, '0.00');
        $mapValues[] = array('en-CA', 1234.5678,  -1, -1, '1,234.568');
        $mapValues[] = array('en-CA', 1234.5678,  -1, 6,  '1,234.5678');
        $mapValues[] = array('en-CA', -1234.5678, -1, 2,  '-1,234.57');

        $mapValues[] = array('en-GB', 0, -1, -1, '0');
        $mapValues[] = array('en-GB', 0,  2, -1, '0.00');
        $mapValues[] = array('en-GB', 0,  2,  6, '0.00');
        $mapValues[] = array('en-GB', 1234.5678,  -1, -1, '1,234.568');
        $mapValues[] = array('en-GB', 1234.5678,  -1, 6,  '1,234.5678');
        $mapValues[] = array('en-GB', -1234.5678, -1, 2,  '-1,234.57');

        $mapValues[] = array('en-US', 0, -1, -1, '0');
        $mapValues[] = array('en-US', 0,  2, -1, '0.00');
        $mapValues[] = array('en-US', 0,  2,  6, '0.00');
        $mapValues[] = array('en-US', 1234.5678,  -1, -1, '1,234.568');
        $mapValues[] = array('en-US', 1234.5678,  -1, 6,  '1,234.5678');
        $mapValues[] = array('en-US', -1234.5678, -1, 2,  '-1,234.57');
        */

        return $mapValues;
    }

    /**
     * @dataProvider provider_formatSimpleCurrency
     */
    public function test_formatSimpleCurrency($locale, $value, $minimumDigits, $maximumDigits, $expected) {
        $this->localization->setDefault($locale);
        $result = $this->localization->formatSimpleCurrency($value, $minimumDigits, $maximumDigits);

        $this->assertEquals($result, $expected);
    }

    public function test_formatSimpleDate() {
        $this->markTestIncomplete('Not completed yet');
    }

    public function test_formatSimpleTime() {
        $this->markTestIncomplete('Not completed yet');
    }

    public function provider_getCurrencyISOCode() {
        $mapValues[] = array('pt-BR', 'BRL');
        $mapValues[] = array('pt-PT', 'EUR');
        $mapValues[] = array('ko-KR', 'KRW');
        $mapValues[] = array('nl-NL', 'EUR');
        $mapValues[] = array('en-CA', 'CAD');
        $mapValues[] = array('en-GB', 'GBP');
        $mapValues[] = array('en-US', 'USD');
        $mapValues[] = array('es-CL', 'CLP');

        return $mapValues;
    }

    /**
     * @dataProvider provider_getCurrencyISOCode
     */
    public function test_getCurrencyISOCode($locale, $expected) {
        $this->localization->setDefault($locale);
        $result = $this->localization->getCurrencyISOCode();

        $this->assertEquals($result, $expected);
    }

    public function test_getTimezeoneOffset() {
        $this->markTestIncomplete('Not completed yet');
    }
}