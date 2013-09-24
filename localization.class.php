<?php
namespace u4u;

/**
 * Class that deals with localization settings
 *
 * @author unreal4u
 */
class localization extends \Locale {
    private static $_localeInfo = array();
    public $timezone;

    /**
     * Constructor, will call setLocale internally
     *
     * @param string $setLocale
     */
    public function __construct($setLocale='') {
        $this->setDefault($setLocale);
        $this->timezone = new \DateTimeZone('UTC');
    }

    /**
     * Sets the locale to the given locale
     *
     * This function will also set the "old" setlocale, mainly timezone support for PHP5.3 - PHP5.5
     *
     * @param string $locale Locale we want to set in RFC 4646 format
     * @return string Returns the setted locale
     */
    public static function setDefault($locale) {
        $result = '';
        if (!empty($locale)) {
            $locale = \Locale::parseLocale($locale);
            $locale = \Locale::composeLocale($locale);
            setlocale(LC_ALL, $locale);
            self::$_localeInfo = localeconv();
            parent::setDefault($locale);
            $result = \Locale::getDefault();
        }

        return $result;
    }

    /**
     * Sends header to browser containing charset and contentType (mandatory)
     *
     * @param string $charset Charset to send to browser. Defaults to UTF-8
     * @param string $contentType ContentType to send to browser. Defaults to text/html
     * @return boolean Returns true if headers could be sent, false otherwise
     */
    public function sendHeaders($charset='UTF-8', $contentType='text/html') {
        $return = false;

        if (!headers_sent()) {
            $headerString = sprintf('Content-type: %s', $contentType);
            if (!empty($charset)) {
                $headerString .= sprintf('; charset='.$charset);
            }
            header($headerString);
            $return = true;
        }

        return $return;
    }

    /**
     * Detects on basis of the browser settings which locale we should apply
     *
     * @return string Returns the setted locale
     */
    public function autodetectLocale() {
        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $this->setDefault(\Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']));
        }

        return \Locale::getDefault();
    }

    /**
     * Applies simple rules to print a number
     *
     * @param float $value
     * @return string Returns the given value formatted according to current locale
     */
    public function formatSimpleNumber($value=0) {
        $numberFormatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::DECIMAL);
        return $numberFormatter->format($value);
    }

    /**
     * Applies simple rules to print a currency
     *
     * This will also print the currency symbol
     *
     * @param string $value Returns the given value formatted according to current locale
     */
    public function formatSimpleCurrency($value=0) {
        $numberFormatter = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY);
        return $numberFormatter->format($value);
    }

    /**
     * Returns the 3-letter (ISO 4217) currency code of the current locale
     *
     * @return string
     */
    public function getCurrencyISOCode() {
        return self::$_localeInfo['int_curr_symbol'];
    }

    /**
     * Returns a intlDateFormatter object
     *
     * @param int $dateConstant
     * @param int $timeConstant
     * @return \IntlDateFormatter Returns a \IntlDateFormatter object with given options
     */
    private function _getDateObject($dateConstant=\IntlDateFormatter::MEDIUM, $timeConstant=\IntlDateFormatter::NONE) {
        return new \intlDateFormatter(\Locale::getDefault(), $dateConstant, $timeConstant);
    }

    /**
     * Returns a formatted medium-typed date
     *
     * @TODO Implement printing locale in another locale
     *
     * @param int $value The UNIX timestamp we want to print. Defaults to current time
     * @param int $type The type of date we want to print
     * @param string $locale The locale in which we want the date
     * @return string The formatted date according to current locale settings
     */
    public function formatSimpleDate($value=0, $type=\intlDateFormatter::MEDIUM, $locale='') {
        if (empty($value)) {
            $value = time();
        }

        $dateObject = $this->_getDateObject($type);
        return $dateObject->format($value);
    }

    /**
     * Returns a formatted medium-typed time
     *
     * @TODO Implement printing locale in another locale
     *
     * @param int $value The UNIX timestamp we want to print. Defaults to current time
     * @param int $type The type of time we want to print
     * @param string $locale The locale in which we want the time
     * @return string The formatted time according to current locale settings
     */
    public function formatSimpleTime($value=0, $type=\intlDateFormatter::MEDIUM, $locale='') {
        if (empty($value)) {
            $value = time();
        }

        $dateObject = $this->_getDateObject(\intlDateFormatter::NONE, $type);
        return $dateObject->format($value);
    }

    /**
     * @TODO Implement this
     *
     * @param unknown_type $locale
     * @return string
     */
    public function getTimezeoneOffset($locale='') {
        return '';
    }
}