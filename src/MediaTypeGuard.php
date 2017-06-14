<?php

namespace RealPage\JsonApi;

use Illuminate\Http\Request;

class MediaTypeGuard
{
    protected $contentType;

    public function __construct(string $contentType)
    {
        $this->contentType = $contentType;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function validateExistingContentType(Request $request): bool
    {
        return str_is($this->getContentType(), $request->header('Accept')) || str_is('', $request->header('Accept'));
    }

    public function clientRequestMustHaveContentTypeHeader(Request $request)
    {
        $method = $request->method();
        return $method === 'POST' || $method === 'PATCH';
    }

    public function contentTypeIsValid(string $contentType): bool
    {
        return str_is($this->getContentType(), $contentType);
    }

    public function hasCorrectHeadersForData(Request $request): bool
    {
        if ($this->clientRequestMustHaveContentTypeHeader($request)) {
            return $request->hasHeader('Content-Type') && $this->contentTypeIsValid($request->header('Content-Type'));
        }
        return true;
    }

    public function hasCorrectlySetAcceptHeader(Request $request): bool
    {
        $accept = $request->header('Accept');
        if ('*/*' !== $accept) {
            return substr_count($accept, $this->getContentType()) > substr_count($accept, $this->getContentType() . ';');
        }
        return true;
    }
}
