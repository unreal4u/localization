<?php

include('../localization.class.php');
$locale = new \u4u\localization();

$testLocales = array('pt-BR', 'es-CL', 'pt-PT', 'ko-KR', 'nl-NL', 'jp-JP', 'nl-BE', 'de-DE', 'en-CA', 'en-GB', '');
$locale->sendHeaders();

foreach ($testLocales as $testLocale) {
    if (empty($testLocale)) {
        $locale->autodetectLocale();
    } else {
        $locale->setDefault($testLocale);
    }
    var_dump('Current locale: '.$locale->getDefault());
    echo $locale->getTimezoneOffset();
}