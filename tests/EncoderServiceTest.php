<?php

namespace RealPage\JsonApi;

use Neomerx\JsonApi\Encoder\Encoder;

class EncoderServiceTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $this->config = [
            'schemas' => [],
            'encoders' => [
                'test-1' => [
                    'jsonapi' => true,
                    'meta' => [
                        'apiVersion' => '1.0',
                    ],
                    'encoder-options' => [
                        'options' => JSON_PRETTY_PRINT,
                        'urlPrefix' => '/',
                        'depth' => 512
                    ],
                ],
                'test-2' => [
                    'jsonapi' => [
                        'extensions' => 'bulk',
                    ],
                    'meta' => [
                        'apiVersion' => '1.0',
                    ],
                ],
            ]
        ];
        $this->encoder_service = new EncoderService($this->config);
    }

    public function testGetDefaultEncoder()
    {
        $this->assertInstanceOf(Encoder::class, $this->encoder_service->getEncoder());
    }

    public function testGetNamedEncoder()
    {
        $this->assertInstanceOf(Encoder::class, $this->encoder_service->getEncoder('test-1'));
        $this->assertInstanceOf(Encoder::class, $this->encoder_service->getEncoder('test-2'));
    }

    public function testGetUnconfiguredEncoder()
    {
        $this->expectException(\Exception::class);
        $this->encoder_service->getEncoder('missing');
    }


    public function testEncoderIsSingleton()
    {
        $encoder = $this->encoder_service->getEncoder();
        $this->assertSame($encoder, $this->encoder_service->getEncoder());
    }
}
