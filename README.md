# laravel-sso-package
The package for my <a href="https://auth.cyrexag.com">SSO application</a> that is under development.

## Installation
1. Run `composer require cyrex/sso`.
2. Add to the `providers` in `auth.php` the following:
```php
    'sso' => [
        'driver' => 'sso',
    ],
```
2. Add to the `guards` in `auth.php` the following:
```php
    'sso' => [
        'driver' => 'sso',
        'provider' => 'sso'
    ],
```
3. Change the `defaults.guard` section of `auth.php` to `sso`.

4. Add the following variables to your environment file.
```
AUTHORIZATION_SERVER_CLIENT_ID=
AUTHORIZATION_SERVER_CLIENT_SECRET=""
AUTHORIZATION_SERVER_URL=""
```
