<?php

namespace RealPage\JsonApi\Validation;

use Neomerx\JsonApi\Exceptions\ErrorCollection;
use Neomerx\JsonApi\Exceptions\JsonApiException;

class RequestFailedValidationTest extends \PHPUnit\Framework\TestCase
{

    /** @var ErrorCollection */
    protected $errors;

    /** @var RequestFailedValidation */
    protected $exception;

    public function setUp()
    {
        parent::setUp();

        $this->errors = new ErrorCollection();
        $this->exception = new RequestFailedValidation($this->errors);
    }

    /** @test */
    public function isJsonApiException()
    {
        $this->assertInstanceOf(JsonApiException::class, $this->exception);
    }

    /** @test */
    public function suppliesInvalidRequestBodyResponseCode()
    {
        $this->assertEquals(422, $this->exception->getHttpCode());
    }
}
