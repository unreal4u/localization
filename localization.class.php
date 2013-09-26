<?php
namespace u4u;

/**
 * Class that deals with localization settings
 *
 * @author unreal4u
 */
class localization {

    /**
     * Saves the current timezone settings
     * @var unknown_type
     */
    public $timezone;

    /**
     * Contains the most probable language associated with the selected locale (2 letter code)
     * @var string
     */
    public $language = '';

    /**
     * Which are the most probable timezone candidates for the current setted locale
     * @var array
     */
    protected $_timezoneCandidates = array();

    /**
     * Contains the value of the current setted locale
     * @var string
     */
    protected $_currentLocale = '';

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
    public function setDefault($locale) {
        $result = '';
        if (!empty($locale)) {
            $locale = \Locale::parseLocale($locale);
            $locale = \Locale::composeLocale($locale);
            \Locale::setDefault($locale);
            $result = $this->getDefault();
            $this->_setOptions();
        }

        return $result;
    }

    /**
     * Gets the default setted locale
     */
    public function getDefault() {
        $this->_currentLocale = \Locale::getDefault();
        return $this->_currentLocale;
    }

    /**
     * Sets some options
     *
     * @param string $locale
     * @return boolean
     */
    private function _setOptions() {
        $this->language = \Locale::getPrimaryLanguage($this->_currentLocale);
        $this->_setTimezoneCandidates(\Locale::getRegion($this->_currentLocale));
        return true;
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

        if (!headers_sent() && !empty($contentType)) {
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

        return $this->_currentLocale;
    }

    private function _setAttribute($object, $attribute, $value) {
        $object->setAttribute($attribute, $value);
        return true;
    }

    /**
     * Applies simple rules to print a number
     *
     * @param float $value
     * @param int $minimumDigits The minimum significant digits. Defaults to -1, which equals locale default
     * @param int $maximumDigits The maximum significant digits. Defaults to -1, which equals locale default
     * @return string Returns the given value formatted according to current locale
     */
    public function formatSimpleNumber($value=0, $minimumDigits=-1, $maximumDigits=-1) {
        $numberFormatter = new \NumberFormatter($this->_currentLocale, \NumberFormatter::DECIMAL);

        if ($minimumDigits > -1) {
            $this->_setAttribute($numberFormatter, \NumberFormatter::MIN_FRACTION_DIGITS, $minimumDigits);
        }

        if ($maximumDigits > -1) {
            $this->_setAttribute($numberFormatter, \NumberFormatter::MAX_FRACTION_DIGITS, $maximumDigits);
        }
        return $numberFormatter->format($value);
    }

    /**
     * Applies simple rules to print a currency
     *
     * This will also print the currency symbol
     *
     * @param string $value Returns the given value formatted according to current locale
     * @param int $minimumDigits The minimum significant digits. Defaults to -1, which equals locale default
     * @param int $maximumDigits The maximum significant digits. Defaults to -1, which equals locale default
     * @return string Returns the given value formatted according to current locale
     */
    public function formatSimpleCurrency($value=0, $minimumDigits=-1, $maximumDigits=-1) {
        $numberFormatter = new \NumberFormatter($this->_currentLocale, \NumberFormatter::CURRENCY);

        if ($minimumDigits > -1) {
            $this->_setAttribute($numberFormatter, \NumberFormatter::MIN_FRACTION_DIGITS, $minimumDigits);
        }

        if ($maximumDigits > -1) {
            $this->_setAttribute($numberFormatter, \NumberFormatter::MAX_FRACTION_DIGITS, $maximumDigits);
        }
        return $numberFormatter->format($value);
    }

    /**
     * Returns the 3-letter (ISO 4217) currency code of the current locale
     *
     * @return string
     */
    public function getCurrencyISOCode() {
        $numberFormatter = new \NumberFormatter($this->_currentLocale, \NumberFormatter::CURRENCY);
        return $numberFormatter->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL);
    }

    /**
     * Returns a intlDateFormatter object
     *
     * @param int $dateConstant
     * @param int $timeConstant
     * @return \IntlDateFormatter Returns a \IntlDateFormatter object with given options
     */
    private function _getDateObject($dateConstant=\IntlDateFormatter::MEDIUM, $timeConstant=\IntlDateFormatter::NONE) {
        return new \intlDateFormatter($this->_currentLocale, $dateConstant, $timeConstant);
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
     * Gets the possible timezone candidates and if only found one, instantiates a DateTimeZone object
     *
     * @param string $region
     */
    private function _setTimezoneCandidates($region='') {
        if (!empty($region)) {
            $this->_timezoneCandidates = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $region);
            if (!empty($this->_timezoneCandidates) && count($this->_timezoneCandidates) == 1) {
                $this->timezone = new \DateTimeZone($this->_timezoneCandidates[0]);
            } else {
                $this->timezone = new \DateTimeZone('UTC');
            }
        }
    }

    /**
     * @TODO Implement this
     *
     * @param unknown_type $locale
     * @return string
     */
    public function getTimezoneOffset($unit='seconds') {
        $dateTimeObject = new \DateTime("now", $this->timezone);
        $return = $dateTimeObject->getOffset();

        switch ($unit) {
            case 'minutes':
                $return = $return / 60;
                break;
            case 'hours':
                $return = $return / 60 / 60;
                break;
        }

        return $return;
    }
}
