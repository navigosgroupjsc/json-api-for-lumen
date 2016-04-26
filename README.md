# Json-Api For Lumen
Lumen helpers to help ease implementation of json-api standards

## Install
Via Composer
``` bash
$ composer require realpage/json-api-for-lumen
```

### Lumen
You can register the service provider in `bootstrap/app.php`
``` php
$app->register(\RealPage\JsonApi\Lumen::class);
```

## Usage
You can register the middleware in `bootstrap/app.php`
``` php
$app->middleware([
    'json-api' => RealPage\JsonApi\Lumen\EnforceMediaType::class,
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