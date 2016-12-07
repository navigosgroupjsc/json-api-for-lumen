<?php

namespace RealPage\JsonApi;

use Neomerx\JsonApi\Document\Link;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Contracts\Document\LinkInterface;

class ErrorFactory
{
    public static function buildUnsupportedMediaType(
        $id = null,
        LinkInterface $aboutLink = null,
        $code = null,
        array $source = null,
        $meta = null
    ): Error {
        return new Error(
            $id ?? null,
            $aboutLink ?? new Link('http://jsonapi.org/format/#content-negotiation-clients'),
            '415',
            $code ?? null,
            'Unsupported Media Type',
            'Content-Type of a request containing JSON data must be application/vnd.api+json',
            $source,
            $meta
        );
    }

    public static function buildUnacceptable(
        $id = null,
        LinkInterface $aboutLink = null,
        $code = null,
        array $source = null,
        $meta = null
    ): Error {
        return new Error(
            $id ?? null,
            $aboutLink ?? new Link('http://jsonapi.org/format/#content-negotiation-clients'),
            '406',
            $code ?? null,
            'Not Acceptable',
            'Accept header must accept application/vnd.api+json at least once without parameters',
            $source,
            $meta
        );
    }

    public static function buildUnauthorized(
        $id = null,
        LinkInterface $aboutLink = null,
        $code = null,
        array $source = null,
        $meta = null
    ): Error {
        return new Error(
            $id ?? null,
            $aboutLink ?? new Link('https://tools.ietf.org/html/rfc7231#section-6.5.3'),
            '403',
            $code ?? null,
            'Forbidden',
            'Access is denied for one or more of the specified resources',
            $source,
            $meta
        );
    }
}
