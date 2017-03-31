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
];
