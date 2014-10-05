Sick Beard API Client
=====================

[![Build Status (Scrutinizer)](https://scrutinizer-ci.com/g/jleagle/sick-beard-api-client/badges/build.png)](https://scrutinizer-ci.com/g/jleagle/sick-beard-api-client)
[![Code Quality (scrutinizer)](https://scrutinizer-ci.com/g/jleagle/sick-beard-api-client/badges/quality-score.png)](https://scrutinizer-ci.com/g/jleagle/sick-beard-api-client)
[![Dependency Status (versioneye.com)](https://www.versioneye.com/php/jleagle:sick-beard-api-client/badge.svg)](https://www.versioneye.com/php/jleagle:sick-beard-api-client)
[![Downloads Total](https://poser.pugx.org/jleagle/sick-beard-api-client/downloads.svg)](https://packagist.org/packages/jleagle/sick-beard-api-client)
[![Issues Resolution (isitmaintained.com)](http://isitmaintained.com/badge/resolution/jleagle/sick-beard-api-client.svg)](https://github.com/jleagle/sick-beard-api-client/issues)
[![Issues Open (isitmaintained.com)](http://isitmaintained.com/badge/open/jleagle/sick-beard-api-client.svg)](https://github.com/jleagle/sick-beard-api-client/issues)
[![Latest Stable Version](https://poser.pugx.org/jleagle/sick-beard-api-client/v/stable.png)](https://packagist.org/packages/jleagle/sick-beard-api-client)
[![Latest Unstable Version](https://poser.pugx.org/jleagle/sick-beard-api-client/v/unstable.png)](https://packagist.org/packages/jleagle/sick-beard-api-client)
[![License](https://poser.pugx.org/jleagle/sick-beard-api-client/license.svg)](https://packagist.org/packages/jleagle/sick-beard-api-client)

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
