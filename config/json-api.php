<?php
return [
    'media-type' => 'application/vnd.api+json',

    // schemas are shared by all encoder instances
    'schemas' => [],

    // If jsonapi is set to true, $encoder->withJsonApiVersion() will be called.
    // If jsonapi is an array, it will be passed as a parameter.
    'jsonapi' => true,

    // If meta is an array, it will be passed as $meta to $encoder->withMeta($meta).
    // 'meta' => [],

    // encoder-options are passed as parameters to Neomerx\JsonApi\Encoder\EncoderOptions.
    // 'encoder-options' => [
    //     'options' => JSON_PRETTY_PRINT,
    // ],

    // accept-header-policy configuration defines how to handle Accept header in requests.
    //
    // If accept-header-policy is set to 'default' or 'require', api will response with
    // 406 (Not Acceptable) if request has an Accept header, which does not match
    // configured media-type.
    //
    // If accept-header-policy is set to 'require', requests without an Accept header
    // will be responded by a 406 (Not Acceptable).
    //
    // If accept-header-policy is set to 'ignore', api will response all requests with a
    // json api document regardless of Accept header.
    'accept-header-policy' => 'require',
];
