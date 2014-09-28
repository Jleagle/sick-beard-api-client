Sick Beard API Client
=====================

An API wrapper class for the [Sick Beard API](http://sickbeard.com/api/)

## Usage

Add the package to your `composer.json`:

```
{
  "require": {
    "jleagle/sick-beard-api-client": "dev-master"
  }
}
```

Download the package

```php
$ php composer.phar update jleagle/sick-beard-api-client
```

Enable the package:

```php
$sb = new \Jleagle\SickBeard\SickBeard(
  $url, // The URL to your Sick Beard installation
  $apiKey, // Your Sick Beard API key
);
```

Make an API call:
```php
$shows = $sb->shows();
```
