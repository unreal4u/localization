[![Latest Stable Version](https://poser.pugx.org/unreal4u/localization/v/stable.png)](https://packagist.org/packages/unreal4u/localization)
[![Build Status](https://travis-ci.org/unreal4u/localization.png?branch=master)](https://travis-ci.org/unreal4u/localization)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/unreal4u/localization/badges/quality-score.png?s=5f6fa877aaa981d9a4e43aec068334c667c32815)](https://scrutinizer-ci.com/g/unreal4u/localization/)
[![License](https://poser.pugx.org/unreal4u/localization/license.png)](https://packagist.org/packages/unreal4u/localization)

localization.class.php
======

Credits
--------

This class is made by unreal4u (Camilo Sperberg). [http://unreal4u.com/](unreal4u.com).

About this class
--------

* This class will interact with the server to set locale settings
* It is able to autodetect from the browser which locale you can setup
* You can also set it to whatever locale you want
* It will also format numbers and currencies for you
* It will also format dates and times
* It also is able to work with timezones

Basic usage
----------

<pre>include('src/unreal4u/localization.class.php');
$locale = new unreal4u\localization();
$locale->autodetectLocale();
$locale->formatNumber(3.1415);
</pre>

* Congratulations! You have just printed 3.1415 formatted according to your browser locale settings!
* **Please see documentation folder for more options and advanced usage**

Composer
----------

This class has support for Composer install. Just add the following section to your composer.json with:

<pre>
{
    "require": {
        "unreal4u/localization": "0.3.*@dev"
    }
}
</pre>

Now you can instantiate a new localization class by executing:

<pre>
require('vendor/autoload.php');

$localization = new unreal4u\localization();
</pre>

TODO list
----------

* Pass PHP_CodeSniffer
* Implement more tests
* Print percentage
* Print other stuff, make it easy to do so

Version History
----------

* 0.1 :
    * Original class
* 0.3 :
    * Composer and PSR-0 compatibility
* 0.4.0:
    * Deleted check for inline PHP >= 5.3
    * Class is now tested with Travis-CI
    * Updated PHPUnit to v4.0
    * Deleted method formatSimpleNumber and formatSimpleCurrency in favor of formatNumber

Contact the author
-------

* Twitter: [@unreal4u](http://twitter.com/unreal4u)
* Website: [http://unreal4u.com/](http://unreal4u.com/)
* Github:  [http://www.github.com/unreal4u](http://www.github.com/unreal4u)
