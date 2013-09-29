<?php

include('../localization.class.php');
$locale = new \u4u\localization();

$testLocales = array('pt-BR', 'hi_IN', 'es-CL', 'es-BO', 'pt-PT', 'ko-KR', 'nl-NL', 'jp-JP', 'nl-BE', 'de-DE', 'en-CA', 'en-GB', '');
$locale->sendHeaders();

foreach ($testLocales as $testLocale) {
    if (empty($testLocale)) {
        $locale->autodetectLocale();
    } else {
        $locale->setDefault($testLocale);
    }

    if ($locale->timezoneId != 'UTC') {
        var_dump('Current locale: '.$locale->getDefault());
        var_dump('Time zone id: '.$locale->timezoneId);
        var_dump('Offset is: '.$locale->getTimezoneOffset('hours').' hours');
    } else {
        var_dump('Could not determine automatically the timezone for '.$locale->getDefault());
        var_dump('Please select one from the following list: ');
        var_dump($locale->getTimeZoneCandidates());
    }

    var_dump(str_repeat('-', 80));
}