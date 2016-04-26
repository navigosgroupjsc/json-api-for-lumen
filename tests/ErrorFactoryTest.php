<?php

namespace Tests\RealPage\JsonApi\Lumen;

use Neomerx\JsonApi\Document\Link;
use Neomerx\JsonApi\Document\Error;
use RealPage\JsonApi\Lumen\ErrorFactory;

class ErrorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testUnsupportedMediaTypeErrorGeneration()
    {
        $link                 = new Link('http://jsonapi.org/format/#content-negotiation-clients');
        $title                = 'Unsupported Media Type';
        $detail               = 'Content-Type of a request containing JSON data must be application/vnd.api+json';
        $defaultError         = ErrorFactory::buildUnsupportedMediaType();
        $expectedDefaultError = new Error(null, $link, '415', null, $title, $detail);
        $this->assertEquals($expectedDefaultError, $defaultError);

        $customLink      = new Link('http://docs.myapp.com/errors#specific-error');
        $idError         = ErrorFactory::buildUnsupportedMediaType(1, $customLink, '12', null, ['this' => 'is meta']);
        $expectedIdError = new Error(1, $customLink, '415', '12', $title, $detail, null, ['this' => 'is meta']);
        $this->assertEquals($expectedIdError, $idError);
    }

    public function testUnacceptableErrorGeneration()
    {
        $link                 = new Link('http://jsonapi.org/format/#content-negotiation-clients');
        $title                = 'Not Acceptable';
        $detail               = 'Accept header must accept application/vnd.api+json at least once without parameters';
        $defaultError         = ErrorFactory::buildUnacceptable();
        $expectedDefaultError = new Error(null, $link, '406', null, $title, $detail);
        $this->assertEquals($expectedDefaultError, $defaultError);

        $customLink      = new Link('http://docs.myapp.com/errors#specific-error');
        $idError         = ErrorFactory::buildUnacceptable(1, $customLink, '12', null, ['this' => 'is meta']);
        $expectedIdError = new Error(1, $customLink, '406', '12', $title, $detail, null, ['this' => 'is meta']);
        $this->assertEquals($expectedIdError, $idError);
    }
}

