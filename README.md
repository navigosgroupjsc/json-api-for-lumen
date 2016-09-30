# Json-Api For Lumen
Lumen helpers to help ease implementation of json-api standards

[![Latest Stable Version](https://poser.pugx.org/realpage/json-api-for-lumen/v/stable)](https://packagist.org/packages/realpage/json-api-for-lumen) [![Total Downloads](https://poser.pugx.org/realpage/json-api-for-lumen/downloads)](https://packagist.org/packages/realpage/json-api-for-lumen) [![Latest Unstable Version](https://poser.pugx.org/realpage/json-api-for-lumen/v/unstable)](https://packagist.org/packages/realpage/json-api-for-lumen) [![License](https://poser.pugx.org/realpage/json-api-for-lumen/license)](https://packagist.org/packages/realpage/json-api-for-lumen)

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

### Middleware

#### Enforcing Media Types

Fulfills the [server responsibilities](http://jsonapi.org/format/#content-negotiation) section of the json-api docs.  This should wrap all endpoints that are json-api spec compliant.

You can register the middleware in `bootstrap/app.php`
``` php
$app->routeMiddleware([
    'json-api.enforce-media-type' => RealPage\JsonApi\Middleware\EnforceMediaType::class,
]);
```

You can then use the middleware within your `routes.php` file
``` php
$app->get('quotes', ['middleware' => 'json-api.enforce-media-type', function () {
  //
}]);
```

### Validation

Use Laravel's validation capabilities to validate your requests and this package will handle converting failed validation to valid json-api responses.  

Add this entry to your exception handler

```php
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Neomerx\JsonApi\Contracts\Http\Headers\MediaTypeInterface;

// automatically encode exceptions as valid json-api json responses
if ($e instanceof JsonApiException) {
    return response(Encoder::instance()->encodeErrors($e->getErrors()), $e->getHttpCode(), [
        'Content-Type' => MediaTypeInterface::JSON_API_MEDIA_TYPE,
    ]);
}
```

Use the trait `RealPage\JsonApi\Validation\ValidatesRequests` to the architecture of your liking.  One approach is to use this trait on a `Request`, which would allow you to do:

```php
    public function store(MyRequest $request)
    {
        $request->validate();
        
        // do stuff
    }
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
If you discover any security related issues, please email [ben.kuhl@realpage.com](mailto:ben.kuhl@realpage.com) instead of using the issue tracker.

## Credits
- Ben Kuhl
- John Laswell
- David Yurek

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
