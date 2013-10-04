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
$locale->formatSimpleCurrency(3.1415);
</pre>

* Congratulations! You have just printed 3.1415 formatted according to your browser locale settings!
* **Please see documentation folder for more options and advanced usage**

Composer
----------

This class has support for Composer install. Just add the following section to your composer.json with:

<pre>
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/unreal4u/localization"
        }
    ],
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

* Check compatibility with PSR-0
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

Contact the author
-------

* Twitter: [@unreal4u](http://twitter.com/unreal4u)
* Website: [http://unreal4u.com/](http://unreal4u.com/)
* Github:  [http://www.github.com/unreal4u](http://www.github.com/unreal4u)
