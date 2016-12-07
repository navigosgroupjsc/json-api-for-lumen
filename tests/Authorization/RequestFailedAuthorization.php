<?php

namespace RealPage\JsonApi\Authorization;

use Neomerx\JsonApi\Exceptions\ErrorCollection;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class RequestFailedAuthorizationTest extends \PHPUnit\Framework\TestCase
{

    /** @var ErrorCollection */
    protected $errors;

    /** @var RequestFailedValidation */
    protected $exception;

    public function setUp()
    {
        parent::setUp();

        $this->errors = new ErrorCollection();
        $this->exception = new RequestFailedAuthorization($this->errors);
    }

    /** @test */
    public function isJsonApiException()
    {
        $this->assertInstanceOf(JsonApiException::class, $this->exception);
    }

    /** @test */
    public function suppliesForbiddenResponseCode()
    {
        $this->assertEquals(403, $this->exception->getHttpCode());
    }
}
