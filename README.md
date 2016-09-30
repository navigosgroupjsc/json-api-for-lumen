# Json-Api For Lumen
Lumen helpers to help ease implementation of json-api standards

[![Latest Stable Version](https://poser.pugx.org/realpage/json-api-for-lumen/v/stable)](https://packagist.org/packages/realpage/json-api-for-lumen) [![Total Downloads](https://poser.pugx.org/realpage/json-api-for-lumen/downloads)](https://packagist.org/packages/realpage/json-api-for-lumen) [![Latest Unstable Version](https://poser.pugx.org/realpage/json-api-for-lumen/v/unstable)](https://packagist.org/packages/realpage/json-api-for-lumen) [![License](https://poser.pugx.org/realpage/json-api-for-lumen/license)](https://packagist.org/packages/realpage/json-api-for-lumen) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/realpage/json-api-for-lumen/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/realpage/json-api-for-lumen/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/realpage/json-api-for-lumen/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/realpage/json-api-for-lumen/?branch=master)

## Install
Via Composer
``` bash
$ composer require realpage/json-api-for-lumen
```

### Lumen
You can register the service provider in `bootstrap/app.php`
``` php
$app->register(RealPage\JsonApi\Lumen\ServiceProvider::class);
```

## Usage
You can register the middleware in `bootstrap/app.php`
``` php
$app->routeMiddleware([
    'jsonApi.enforceMediaType' => RealPage\JsonApi\Lumen\EnforceMediaType::class,
]);
```
You can then use the middleware within your `routes.php` file
``` php
$app->get('quotes', ['middleware' => 'json-api', function () {
  //
}]);
```

## Change log
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing
``` bash
$ composer test
```

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
If you discover any security related issues, please email [john.laswell@realpage.com](mailto:john.laswell@realpage.com) instead of using the issue tracker.

## Credits
- Ben Kuhl
- John Laswell
- David Yurek

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
