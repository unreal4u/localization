<?php

include('../localization.class.php');
$locale = new \u4u\localization();

$testLocales = array('pt-BR', 'pt-PT', 'ko-KO', 'nl-NL', 'nl-BE', 'de-DE', 'en-CA', 'en-GB', '');
$testNumbers = array(0, 1, 3, 3.1415, -45.33, 20000);

$locale->sendHeaders();

var_dump(timezone_abbreviations_list());

foreach ($testLocales as $testLocale) {
    if (empty($testLocale)) {
        $locale->autodetectLocale();
    } else {
        $locale->setDefault($testLocale);
    }
    var_dump('Current locale: '.$locale->getDefault());

    foreach ($testNumbers as $testNumber) {
        printf('Number: <strong>%s</strong><br />', $locale->formatSimpleNumber($testNumber));
        printf('Currency: <strong>%s</strong>', $locale->formatSimpleCurrency($testNumber));
        printf('. Currency Symbol: %s<br />', $locale->getCurrencyISOCode());
        printf('Date: <strong>%s</strong><br />', $locale->formatSimpleDate());
        printf('Time: <strong>%s</strong><br />', $locale->formatSimpleTime());
        printf('Offset: <strong>%s</strong>', $locale->getTimezeoneOffset());
        printf('---<br />');
    }
}