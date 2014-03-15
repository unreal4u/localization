<?php

require_once 'src/unreal4u/localization.php';

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
        $this->localization = new unreal4u\localization();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->localization = null;
        parent::tearDown();
    }

    /**
     * Tests magic toString method
     */
    public function test___toString() {
        $output = sprintf($this->localization);
        $this->assertStringStartsWith('localization', $output);
    }

    /**
     * Provider for test_sendHeaders
     *
     * @return array
     */
    public function provider_sendHeaders() {
        $mapValues[] = array('UTF-8', 'text/html', true);
        $mapValues[] = array('ISO-8859-1', 'text/xml', true);

        return $mapValues;
    }

    /**
     * Check that header gets sent correctly, if at all
     *
     * @dataProvider provider_sendHeaders
     */
    public function test_sendHeaders($charset, $contentType, $expected) {
        $result = $this->localization->sendHeaders($charset, $contentType);

        if ($result === true) {
            $headers = xdebug_get_headers();
            $this->assertNotEmpty($headers);
            $this->assertEquals('Content-type: '.$contentType.'; charset='.$charset, $headers[0]);
        } else {
            $this->assertFalse($result);
        }
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
        $mapValues[] = array('pt-BR', \Numberformatter::DECIMAL, 0, -1, -1, '0');
        $mapValues[] = array('pt-BR', \Numberformatter::DECIMAL, 0,  2, -1, '0,00');
        $mapValues[] = array('pt-BR', \Numberformatter::DECIMAL, 0,  2,  6, '0,00');
        $mapValues[] = array('pt-BR', \Numberformatter::DECIMAL, 1234.5678,  -1, -1, '1.234,568');
        $mapValues[] = array('pt-BR', \Numberformatter::DECIMAL, 1234.5678,  -1, 4,  '1.234,5678');
        $mapValues[] = array('pt-BR', \Numberformatter::DECIMAL, 1234.5678,  -1, 6,  '1.234,5678');
        $mapValues[] = array('pt-BR', \Numberformatter::DECIMAL, -1234.5678, -1, 2,  '-1.234,57');

        $mapValues[] = array('pt-PT', \Numberformatter::DECIMAL, 0, -1, -1, '0');
        $mapValues[] = array('pt-PT', \Numberformatter::DECIMAL, 0,  2, -1, '0,00');
        $mapValues[] = array('pt-PT', \Numberformatter::DECIMAL, 0,  2,  6, '0,00');
        $mapValues[] = array('pt-PT', \Numberformatter::DECIMAL, 1234.5678,  -1, -1, '1 234,568');
        $mapValues[] = array('pt-PT', \Numberformatter::DECIMAL, 1234.5678,  -1, 4,  '1 234,5678');
        $mapValues[] = array('pt-PT', \Numberformatter::DECIMAL, 1234.5678,  -1, 6,  '1 234,5678');
        $mapValues[] = array('pt-PT', \Numberformatter::DECIMAL, -1234.5678, -1, 2,  '-1 234,57');

        $mapValues[] = array('ko-KR', \Numberformatter::DECIMAL, 0, -1, -1, '0');
        $mapValues[] = array('ko-KR', \Numberformatter::DECIMAL, 0,  2, -1, '0.00');
        $mapValues[] = array('ko-KR', \Numberformatter::DECIMAL, 0,  2,  6, '0.00');
        $mapValues[] = array('ko-KR', \Numberformatter::DECIMAL, 1234.5678,  -1, -1, '1,234.568');
        $mapValues[] = array('ko-KR', \Numberformatter::DECIMAL, 1234.5678,  -1, 4,  '1,234.5678');
        $mapValues[] = array('ko-KR', \Numberformatter::DECIMAL, 1234.5678,  -1, 6,  '1,234.5678');
        $mapValues[] = array('ko-KR', \Numberformatter::DECIMAL, -1234.5678, -1, 2,  '-1,234.57');
        $mapValues[] = array('ko-KR', null, -1234.5678, -1, 2,  '-1,234.57');


        return $mapValues;
    }

    /**
     * @dataProvider provider_formatSimpleNumber
     */
    public function test_formatSimpleNumber($locale, $type, $value, $minimumDigits, $maximumDigits, $expected) {
        $this->localization->setDefault($locale);
        $result = $this->localization->formatNumber($value, $type, $minimumDigits, $maximumDigits);

        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider for all currency related functions
     */
    public function provider_formatSimpleCurrency() {
        $mapValues[] = array('pt-BR', \Numberformatter::CURRENCY, 0, -1, -1, 'R$0,00');
        $mapValues[] = array('pt-BR', \Numberformatter::CURRENCY, 0,  2, -1, 'R$0,00');
        $mapValues[] = array('pt-BR', \Numberformatter::CURRENCY, 0,  2,  6, 'R$0,00');
        $mapValues[] = array('pt-BR', \Numberformatter::CURRENCY, 1234.5678,  -1, -1, 'R$1.234,57');
        $mapValues[] = array('pt-BR', \Numberformatter::CURRENCY, 1234.5678,  -1, 4,  'R$1.234,5678');
        $mapValues[] = array('pt-BR', \Numberformatter::CURRENCY, 1234.5678,  -1, 6,  'R$1.234,5678');
        $mapValues[] = array('pt-BR', \Numberformatter::CURRENCY, -1234.5678, -1, 2,  '(R$1.234,57)');

        $mapValues[] = array('pt-PT', \Numberformatter::CURRENCY, 0, -1, -1, '0,00 €');
        $mapValues[] = array('pt-PT', \Numberformatter::CURRENCY, 0,  2, -1, '0,00 €');
        $mapValues[] = array('pt-PT', \Numberformatter::CURRENCY, 0,  2,  6, '0,00 €');
        $mapValues[] = array('pt-PT', \Numberformatter::CURRENCY, 1234.5678,  -1, -1, '1 234,57 €');
        $mapValues[] = array('pt-PT', \Numberformatter::CURRENCY, 1234.5678,  -1, 4,  '1 234,5678 €');
        $mapValues[] = array('pt-PT', \Numberformatter::CURRENCY, 1234.5678,  -1, 6,  '1 234,5678 €');
        $mapValues[] = array('pt-PT', \Numberformatter::CURRENCY, -1234.5678, -1, 2,  '-1 234,57 €');

        $mapValues[] = array('ko-KR', \Numberformatter::CURRENCY, 0, -1, -1, '₩0');
        $mapValues[] = array('ko-KR', \Numberformatter::CURRENCY, 0,  2, -1, '₩0.00');
        $mapValues[] = array('ko-KR', \Numberformatter::CURRENCY, 0,  2,  6, '₩0.00');
        $mapValues[] = array('ko-KR', \Numberformatter::CURRENCY, 1234.5678,  -1, -1, '₩1,235');
        $mapValues[] = array('ko-KR', \Numberformatter::CURRENCY, 1234.5678,  -1, 4,  '₩1,234.5678');
        $mapValues[] = array('ko-KR', \Numberformatter::CURRENCY, 1234.5678,  -1, 6,  '₩1,234.5678');
        $mapValues[] = array('ko-KR', \Numberformatter::CURRENCY, -1234.5678, -1, 2,  '-₩1,234.57');

        $mapValues[] = array('nl-NL', \Numberformatter::CURRENCY, 0, -1, -1, '€ 0,00');
        $mapValues[] = array('nl-NL', \Numberformatter::CURRENCY, 0,  2, -1, '€ 0,00');
        $mapValues[] = array('nl-NL', \Numberformatter::CURRENCY, 0,  2,  6, '€ 0,00');
        $mapValues[] = array('nl-NL', \Numberformatter::CURRENCY, 1234.5678,  -1, -1, '€ 1.234,57');
        $mapValues[] = array('nl-NL', \Numberformatter::CURRENCY, 1234.5678,  -1, 6,  '€ 1.234,5678');
        $mapValues[] = array('nl-NL', \Numberformatter::CURRENCY, -1234.5678, -1, 2,  '€ 1.234,57-');

        $mapValues[] = array('en-GB', \Numberformatter::CURRENCY, 0, -1, -1, '£0.00');
        $mapValues[] = array('en-GB', \Numberformatter::CURRENCY, 0,  2, -1, '£0.00');
        $mapValues[] = array('en-GB', \Numberformatter::CURRENCY, 0,  2,  6, '£0.00');
        $mapValues[] = array('en-GB', \Numberformatter::CURRENCY, 1234.5678,  -1, -1, '£1,234.57');
        $mapValues[] = array('en-GB', \Numberformatter::CURRENCY, 1234.5678,  -1, 6,  '£1,234.5678');
        $mapValues[] = array('en-GB', \Numberformatter::CURRENCY, -1234.5678, -1, 2,  '-£1,234.57');

        return $mapValues;
    }

    /**
     * @dataProvider provider_formatSimpleCurrency
     */
    public function test_formatSimpleCurrency($locale, $type, $value, $minimumDigits, $maximumDigits, $expected) {
        $this->localization->setDefault($locale);
        $result = $this->localization->formatNumber($value, $type, $minimumDigits, $maximumDigits);

        $this->assertEquals($expected, $result);
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

        $this->assertEquals($expected, $result);
    }

    public function provider_isValidTimeZone() {
        $mapValues[] = array('America/Santiago', true);
        $mapValues[] = array('Europe/Amsterdam', true);
        $mapValues[] = array('UTC', true);
        $mapValues[] = array('Invented/Invented', false);
        $mapValues[] = array('', false);
        $mapValues[] = array(false, false);
        $mapValues[] = array(true, false);
        $mapValues[] = array(null, false);
        $mapValues[] = array(array(), false);
        $mapValues[] = array(3, false);
        $mapValues[] = array(3.1415, false);

        return $mapValues;
    }

    /**
     * Tests isValidTimeZone
     *
     * @dataProvider provider_isValidTimeZone
     * @param unknown $timezone
     * @param unknown $expected
     */
    public function test_isValidTimeZone($timezone, $expected) {
        $result = $this->localization->isValidTimeZone($timezone);
        $this->assertEquals($expected, $result);
    }

    public function provider_setTimezone() {
        $mapValues[] = array('UTC', 'UTC');
        $mapValues[] = array('CET', 'Europe/Berlin');
        $mapValues[] = array('America/Santiago', 'America/Santiago');
        $mapValues[] = array('XXXXXX-invalid-time-zone', 'UTC');

        return $mapValues;
    }

    /**
     * Tests setTimezone
     *
     * @dataProvider provider_setTimezone
     * @param unknown $timezoneName
     * @param unknown $expected
     */
    public function test_setTimezone($timezoneName, $expected) {
        $result = $this->localization->setTimezone($timezoneName);
        $this->assertEquals($expected, $result);
    }

    public function provider_getTimezoneOffset() {
        $mapValues[] = array('hi_IN', 'hours', 5.5);
        $mapValues[] = array('hi_IN', 'z', '+0530');
        $mapValues[] = array('es_BO', 'hours', -4);
        $mapValues[] = array('es_BO', 'z', '-0400');
        $mapValues[] = array('en_GB', 'hours', 0);
        $mapValues[] = array('en_GB', 'z', '+0000');
        $mapValues[] = array('nl_NL', 'hours', 1);
        $mapValues[] = array('nl_NL', 'z', '+0100');
        $mapValues[] = array('jp_JP', 'minutes', 540);
        $mapValues[] = array('jp_JP', 'seconds', 32400);

        return $mapValues;
    }

    /**
     * Tests getTimezoneOffset
     *
     * @dataProvider provider_getTimezoneOffset
     * @param unknown $locale
     * @param unknown $unit
     * @param unknown $expected
     */
    public function test_getTimezoneOffset($locale, $unit, $expected) {
        $this->localization->setDefault($locale);
        $result = $this->localization->getTimezoneOffset($unit);

        $this->assertEquals($expected, $result);
    }
}
