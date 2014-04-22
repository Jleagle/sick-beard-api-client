PHPSickBeard
============

A wrapper wrapper class for the [Sick Beard API](http://sickbeard.com/api/)

## Usage

Add PHPSickBeard to your `composer.json`:

    {
        "require": {
            "jleagle/php-sick-beard": "dev-master"
        }
    }


Download the package

    $ php composer.phar update jleagle/php-sick-beard


Enable the bundle:
```php
$sb = new \Jleagle\PHPSickBeard\PHPSickBeard(
    $url, // The URL to your Sick Beard installation
    $apiKey, // Your Sick Beard API key
    $returnArray // True for an array, false for an object
);
```


Make an API call:
```php
$shows = $sb->shows();
```


The methods in PHPSickBeard are the same as the methods in the Sick Beard API (except full stops are replaced with an underscore!).


Any extra parameters can be added:
```php
$shows = $sb->shows(array(
    'sort' => 'name',
    'paused' => 0
));
```
