<?php

namespace RealPage\JsonApi\Lumen;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Neomerx\JsonApi\Encoder\Encoder;
use RealPage\JsonApi\Lumen\MediaTypeGuard;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Exceptions\ErrorCollection;

class EnforceMediaType
{
    public function handle(Request $request, Closure $next, MediaTypeGuard $guard = null)
    {
        $guard = $guard ?? app(MediaTypeGuard::class);
        // http://jsonapi.org/format/#content-negotiation
        if (!$guard->validateExistingContentType($request) || !$guard->hasCorrectHeadersForData($request)) {
            $errors = (new ErrorCollection())->add(ErrorFactory::buildUnsupportedMediaType());
            $encoder = Encoder::instance([], new EncoderOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return new Response($encoder->encodeErrors($errors), 415, ['Content-Type' => $guard->getContentType()]);
        }

        if (!$guard->hasCorrectlySetAcceptHeader($request)) {
            $errors = (new ErrorCollection())->add(ErrorFactory::buildUnacceptable());
            $encoder = Encoder::instance([], new EncoderOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return new Response($encoder->encodeErrors($errors), 406, ['Content-Type' => $guard->getContentType()]);
        }

        $response = $next($request);
        $response->header('Content-Type', $guard->getContentType());

        return $response;
    }
}
