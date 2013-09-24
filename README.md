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

<pre>include('localization.class.php');
$locale = new \u4u\localization();
$locale->autodetectLocale();
$locale->formatSimpleCurrency(3.1415);
</pre>

* Congratulations! You have just printed 3.1515 formatted according to your browser locale settings!
* **Please see documentation folder for more options and advanced usage**

Version History
----------

* 0.1 :
    * Original class

Contact the author
-------

* Twitter: [@unreal4u](http://twitter.com/unreal4u)
* Website: [http://unreal4u.com/](http://unreal4u.com/)
* Github:  [http://www.github.com/unreal4u](http://www.github.com/unreal4u)
