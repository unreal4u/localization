<?php

include('../localization.class.php');
$locale = new \u4u\localization();

$testLocales = array('pt-BR', 'pt-PT', 'ko-KR', 'nl-NL', 'jp-JP', 'nl-BE', 'de-DE', 'en-CA', 'en-GB', '');
$testNumbers = array(0, 1, 3, 3.1415, -45.33, 20000);

$locale->sendHeaders();

foreach ($testLocales as $testLocale) {
    if (empty($testLocale)) {
        $locale->autodetectLocale();
    } else {
        $locale->setDefault($testLocale);
    }
    var_dump('Current locale: '.$locale->getDefault());

    printf('Timezone offset: <strong>%s</strong><br />', $locale->getTimezoneOffset());
    printf('Date (UTC): <strong>%s</strong><br />', $locale->formatSimpleDate('UTC'));
    printf('Date (local): <strong>%s</strong><br />', $locale->formatSimpleDate());
    printf('Time (UTC): <strong>%s</strong><br />', $locale->formatSimpleTime('UTC'));
    printf('Time (local): <strong>%s</strong><br />', $locale->formatSimpleTime());

    foreach ($testNumbers as $testNumber) {
        printf('Number: <strong>%s</strong><br />', $locale->formatSimpleNumber($testNumber));
        printf('Currency: <strong>%s</strong><br />', $locale->formatSimpleCurrency($testNumber));
        printf('Currency Symbol: <strong>%s</strong><br />', $locale->getCurrencyISOCode());
        printf('---<br />');
    }
}