<?php

namespace RealPage\JsonApi\Lumen;

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

    public function validateExistingContentType(string $contentType): bool
    {
        return str_is($this->getContentType(), $contentType) || str_is('', $contentType);
    }

    public function clientRequestHasJsonApiData(Request $request)
    {
        return !empty($request->all());
    }

    public function contentTypeIsValid(string $contentType): bool
    {
        return str_is($this->getContentType(), $contentType);
    }

    public function hasCorrectHeadersForData(Request $request): bool
    {
        if ($this->clientRequestHasJsonApiData($request)) {
            return $this->contentTypeIsValid($request->header('Content-Type'));
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
