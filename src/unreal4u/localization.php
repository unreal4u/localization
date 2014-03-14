<?php
/**
 * Main class that defines the Localization class
 *
 * @author unreal4u
 * @link https://github.com/unreal4u/localization
 * @package localization
 * @category mainClass
 */

namespace unreal4u;

/**
 * Class that deals with localization settings
 *
 * This class will handle everything related to locales for you: from number formatting 'till timezones. One particular
 * caveat of this class however is that it doesn't know how to handle with countries that have more than 1 timezone. In
 * this case, it will setup a list with candidates for you to choose from
 *
 * @author "unreal4u / Camilo Sperberg" <me@unreal4u.com>
 * @copyright 2010 - 2014 Camilo Sperberg
 * @version 0.3.5
 * @license BSD License
 */
class localization {

    /**
     * The version of this class
     * @var string
     */
    private $classVersion = '0.3.5';

    /**
     * Saves the current timezone settings
     * @var \DateTimeZone
     */
    public $timezone = null;

    /**
     * The timezoneId we are in
     * @var string
     */
    public $timezoneId = 'UTC';

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
     * Contains a list of valid timezones
     * @var array
     */
    protected $_validTimeZones = array();

    /**
     * Constructor, will call setLocale internally
     *
     * @param string $setLocale
     */
    public function __construct($setLocale='') {
        $this->setDefault($setLocale);
    }

    /**
     * Returns a string with basic information of this class
     *
     * @return string
     */
    public function __toString() {
        return basename(__FILE__).' v'.$this->classVersion.' by Camilo Sperberg - http://unreal4u.com/';
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

    /**
     * Can set special attributes to the given numberFormatter
     *
     * @param \NumberFormatter $object
     * @param int $attribute The attribute to set
     * @param mixed $value
     * @return boolean
     */
    private function _setAttribute(\NumberFormatter $object, $attribute, $value) {
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
    private function _getDateObject($dateConstant=\IntlDateFormatter::MEDIUM, $timeConstant=\IntlDateFormatter::NONE, $timezone='') {
        if (!empty($timezone) && $this->isValidTimeZone($timezone)) {
            $timezoneId = $timezone;
        } else {
            $timezoneId = $this->timezoneId;
        }
        return \intlDateFormatter::create($this->_currentLocale, $dateConstant, $timeConstant, $timezoneId);
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
    public function formatSimpleDate($value=0, $timezone='', $type=\IntlDateFormatter::MEDIUM, $locale='') {
        if (empty($value)) {
            $value = time();
        }

        $dateObject = $this->_getDateObject($type, \IntlDateFormatter::NONE, $timezone);
        return $dateObject->format($value);
    }

    /**
     * Returns a formatted medium-typed time
     *
     * @TODO Implement printing locale in another locale
     *
     * @param string $timezone The timezone in which we want to print the time
     * @param int $value The UNIX timestamp we want to print. Defaults to current time
     * @param int $type The type of time we want to print
     * @param string $locale The locale in which we want the time
     * @return string The formatted time according to current locale settings
     */
    public function formatSimpleTime($value=0, $timezone='', $type=\intlDateFormatter::MEDIUM) {
        if (empty($value)) {
            $value = time();
        }

        $dateObject = $this->_getDateObject(\intlDateFormatter::NONE, $type, $timezone);
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
            $this->setTimeZone();
            if (!empty($this->_timezoneCandidates) && count($this->_timezoneCandidates) == 1) {
                $this->setTimezone($this->_timezoneCandidates[0]);
            }
        }
    }

    /**
     * Gets timezone candidates for the current locale
     *
     * @TODO Improve this
     *
     * @return array
     */
    public function getTimeZoneCandidates() {
        return $this->_timezoneCandidates;
    }

    /**
     * Verifies that the given timezone exists and sets the timezone to the selected timezone
     *
     * @TODO Verify that the given timezone exists
     *
     * @param string $timezoneName
     * @return string
     */
    public function setTimezone($timeZoneName='UTC') {
        if (!$this->isValidTimeZone($timeZoneName)) {
            $timeZoneName = 'UTC';
        }
        $this->timezone = new \DateTimeZone($timeZoneName);
        $this->timezoneId = $this->timezone->getName();

        return $this->timezoneId;
    }

    /**
     * Checks whether a timezonename is valid or not
     *
     * Original idea from StackOverflow
     * Modified a bit to adjust it to this class and PHPUnit tested it
     *
     * @link http://stackoverflow.com/a/5878722/396006
     * @author http://stackoverflow.com/users/59120/cal
     *
     * @param string $timeZoneName
     * @return boolean Returns true if timezone name is valid, false otherwise
     */
    public function isValidTimeZone($timeZoneName='') {
        if (!is_string($timeZoneName)) {
            $timeZoneName = '';
        }

        if (empty($this->_validTimeZones)) {
            $tza = \DateTimeZone::listAbbreviations();
            foreach ($tza as $zone) {
                foreach ($zone as $item) {
                    $this->_validTimeZones[$item['timezone_id']] = true;
                }
            }
            unset($this->_validTimeZones[''], $tza, $zone, $item);
        }
        return isset($this->_validTimeZones[$timeZoneName]);
    }

    /**
     * Gets the offset for the current timezone
     *
     * @param string $unit
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
            case 'z':
                $sign = '+';
                if ($return < 0) {
                    $sign = '-';
                }
                $return = $sign.str_pad(gmdate("Hi", abs($return)), 4, '0', STR_PAD_LEFT);
                break;
        }

        return (string)$return;
    }
}
