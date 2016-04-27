<?php

namespace Tests\RealPage\JsonApi\Lumen;

use Illuminate\Http\Request;
use RealPage\JsonApi\Lumen\MediaTypeGuard;

class MediaTypeGuardTest extends \PHPUnit_Framework_TestCase
{
    protected $contentType;

    protected $guard;

    public function setUp()
    {
        $this->contentType = 'application/vnd.api+json';
        $this->guard       = new MediaTypeGuard($this->contentType);
    }

    public function testIsBuiltWithDependencies()
    {
        $this->assertEquals($this->contentType, $this->guard->getContentType());
    }

    public function testExistingContentTypeValidation()
    {
        $noContentTypeRequest    = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods(['header'])->getMock();
        $badContentTypeRequest   = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods(['header'])->getMock();
        $validContentTypeRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods(['header'])->getMock();
        $noContentTypeRequest->expects($this->any())->method('header')->with('Accept')->willReturn('');
        $badContentTypeRequest->expects($this->any())->method('header')->with('Accept')->willReturn('application/json');
        $validContentTypeRequest->expects($this->any())->method('header')->with('Accept')->willReturn('application/vnd.api+json');

        $this->assertTrue($this->guard->validateExistingContentType($noContentTypeRequest));
        $this->assertFalse($this->guard->validateExistingContentType($badContentTypeRequest));
        $this->assertTrue($this->guard->validateExistingContentType($validContentTypeRequest));
    }

    public function testRecognizesIfJsonDataIsPresent()
    {
        $emptyRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods(['all'])->getMock();
        $fullRequest  = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods(['all'])->getMock();
        $emptyRequest->expects($this->any())->method('all')->willReturn([]);
        $fullRequest->expects($this->any())->method('all')->willReturn(['data' => 'exists']);

        $this->assertFalse($this->guard->clientRequestHasJsonApiData($emptyRequest));
        $this->assertTrue($this->guard->clientRequestHasJsonApiData($fullRequest));
    }

    public function testContentTypeIsValid()
    {
        $validContentType                 = 'application/vnd.api+json';
        $invalidContentType               = 'application/json';
        $invalidContentTypeWithParameters = 'application/vnd.api+json; extras=bad';

        $this->assertTrue($this->guard->contentTypeIsValid($validContentType));
        $this->assertFalse($this->guard->contentTypeIsValid($invalidContentType));
        $this->assertFalse($this->guard->contentTypeIsValid($invalidContentTypeWithParameters));
    }

    public function testCanTellCorrectContentTypeWithData()
    {
        $validContentType    = 'application/vnd.api+json';
        $invalidContentType  = 'application/json';
        $emptyValidRequest   = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods([
            'all',
            'header',
        ])->getMock();
        $emptyInvalidRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods([
            'all',
            'header',
        ])->getMock();
        $fullValidRequest    = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods([
            'all',
            'header',
        ])->getMock();
        $fullInvalidRequest  = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods([
            'all',
            'header',
        ])->getMock();
        $emptyValidRequest->expects($this->any())->method('all')->willReturn([]);
        $emptyValidRequest->expects($this->any())->method('header')->willReturn($validContentType);
        $emptyInvalidRequest->expects($this->any())->method('all')->willReturn([]);
        $emptyInvalidRequest->expects($this->any())->method('header')->willReturn($invalidContentType);
        $fullValidRequest->expects($this->any())->method('all')->willReturn(['data' => 'exists']);
        $fullValidRequest->expects($this->any())->method('header')->willReturn($validContentType);
        $fullInvalidRequest->expects($this->any())->method('all')->willReturn(['data' => 'exists']);
        $fullInvalidRequest->expects($this->any())->method('header')->willReturn($invalidContentType);

        $this->assertTrue($this->guard->hasCorrectHeadersForData($emptyValidRequest));
        $this->assertTrue($this->guard->hasCorrectHeadersForData($emptyInvalidRequest));
        $this->assertTrue($this->guard->hasCorrectHeadersForData($fullValidRequest));
        $this->assertFalse($this->guard->hasCorrectHeadersForData($fullInvalidRequest));
    }

    public function testDeterminesCorrectlySetAcceptHeader()
    {
        $validWildcardAcceptRequest   = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods(['header'])->getMock();
        $validStandardAcceptRequest   = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods(['header'])->getMock();
        $invalidStandardAcceptRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods(['header'])->getMock();
        $invalidAcceptRequest         = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->setMethods(['header'])->getMock();
        $validWildcardAcceptRequest->expects($this->any())->method('header')->with('Accept')->willReturn('*/*');
        $validStandardAcceptRequest->expects($this->any())->method('header')->with('Accept')->willReturn('application/vnd.api+json, application/json');
        $invalidStandardAcceptRequest->expects($this->any())->method('header')->with('Accept')->willReturn('application/vnd.api+json; test=true, application/json');
        $invalidAcceptRequest->expects($this->any())->method('header')->with('Accept')->willReturn('application/json');

        $this->assertTrue($this->guard->hasCorrectlySetAcceptHeader($validWildcardAcceptRequest));
        $this->assertTrue($this->guard->hasCorrectlySetAcceptHeader($validStandardAcceptRequest));
        $this->assertFalse($this->guard->hasCorrectlySetAcceptHeader($invalidStandardAcceptRequest));
        $this->assertFalse($this->guard->hasCorrectlySetAcceptHeader($invalidAcceptRequest));
    }
}
