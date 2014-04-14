PHPSickBeard
============

Here is a wrapper class for Sick Beard to retrieve anything from the [Sick Beard API](http://sickbeard.com/api/)

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
    $apiKey, // Your BTN API key
    $returnArray // True for an array, false for an object
);
```


Make an API call:
```php
$shows = $sb->shows();
```


The functions in PHPSickBeard are the same as the functions in the Sick Beard API (except full stops are replaced with an underscore!).


Any extra parameters can be added:
```php
$shows = $sb->shows(array(
    'sort' => 'name',
    'paused' => 0
));
```
