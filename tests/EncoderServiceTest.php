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

    public function testGetEncoderOptionsDefaults()
    {
        $method = $this->getMethod(EncoderService::class, 'getEncoderOptions');

        $service = new EncoderService([]);

        $encoder_options = $method->invokeArgs($service, [[]]);

        $this->assertEquals(0, $encoder_options->getOptions());
        $this->assertNull($encoder_options->getUrlPrefix());
        $this->assertEquals(512, $encoder_options->getDepth());
    }

    public function testGetEncoderOptions()
    {
        $method = $this->getMethod(EncoderService::class, 'getEncoderOptions');

        $service = new EncoderService([]);

        $configs = [
            [
                'options' => 0,
                'urlPrefix' => null,
                'depth' => 512
            ],
            [
                'options' => JSON_PRETTY_PRINT,
                'urlPrefix' => '/',
                'depth' => 1024
            ],
        ];

        foreach ($configs as $config) {
            $encoder_options = $method->invokeArgs($service, [$config]);

            $this->assertEquals($config['options'], $encoder_options->getOptions());
            $this->assertEquals($config['urlPrefix'], $encoder_options->getUrlPrefix());
            $this->assertEquals($config['depth'], $encoder_options->getDepth());
        }
    }

    public function testSetMetaAndJsonApiVersion()
    {
        $config = [
            'schemas' => [],
        ];
        $encoder_service = new EncoderService($config);
        $encoder = $encoder_service->getEncoder();
        $this->assertNull($this->getProperty($encoder, 'meta'));
        $this->assertFalse($this->getProperty($encoder, 'isAddJsonApiVersion'));
        $this->assertNull($this->getProperty($encoder, 'jsonApiVersionMeta'));

        $config = [
            'schemas' => [],
            'jsonapi' => true,
            'meta' => [
                'apiVersion' => '1.0',
            ],
        ];
        $encoder_service = new EncoderService($config);
        $encoder = $encoder_service->getEncoder();
        $this->assertEquals($config['meta'], $this->getProperty($encoder, 'meta'));
        $this->assertEquals($config['jsonapi'], $this->getProperty($encoder, 'isAddJsonApiVersion'));
        $this->assertNull($this->getProperty($encoder, 'jsonApiVersionMeta'));

        $config = [
            'schemas' => [],
            'jsonapi' => [
                'foo' => 'bar',
            ],
        ];
        $encoder_service = new EncoderService($config);
        $encoder = $encoder_service->getEncoder();
        $this->assertEquals($config['jsonapi'], $this->getProperty($encoder, 'jsonApiVersionMeta'));
        $this->assertTrue($this->getProperty($encoder, 'isAddJsonApiVersion'));
    }

    protected static function getProperty($object, $name)
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);
        return $property->getValue($object);
    }

    protected static function getMethod($class, $name)
    {
        $class  = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
