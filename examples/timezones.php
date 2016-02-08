<?php

include('../vendor/autoload.php');
include('../src/unreal4u/localization.php');
$locale = new \unreal4u\localization();

$testLocales = array('pt-BR', 'hi_IN', 'es-CL', 'es-BO', 'pt-PT', 'ko-KR', 'nl-NL', 'jp-JP', 'nl-BE', 'de-DE', 'en-CA', 'en-GB', '');
$locale->sendHeaders();

foreach ($testLocales as $testLocale) {
    if (empty($testLocale)) {
        $locale->autodetectLocale();
    } else {
        $locale->setDefault($testLocale);
    }

    $theDate = new \DateTime('23-05-2015 21:34:09', new \DateTimeZone('UTC'));

    if ($locale->timezoneId != 'UTC') {
        var_dump('Current locale: '.$locale->getDefault());
        var_dump('Time zone id: '.$locale->timezoneId);
        var_dump('Offset is: '.$locale->getTimezoneOffset('hours').' hours (or '.$locale->getTimezoneOffset('z').')');
        printf(
            'UTC 23-05-2015 21:34:09 is %s %s in %s'.PHP_EOL,
            $locale->formatSimpleDate($theDate),
            $locale->formatSimpleTime($theDate),
            $locale->getDefault()
        );
    } else {
        var_dump('Could not determine automatically the timezone for '.$locale->getDefault());
        var_dump('Please select one from the following list: ');
        var_dump($locale->getTimeZoneCandidates());
    }

    var_dump(str_repeat('-', 80));
}
