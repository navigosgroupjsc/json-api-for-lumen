<?php

namespace RealPage\JsonApi\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request as IlluminateRequest;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use RealPage\JsonApi\Validation\RequestFailedValidation;
use RealPage\JsonApi\Validation\ValidatesRequests;

class Request
{
    /** @var array */
    protected $json;

    /** @var IlluminateRequest */
    protected $request;

    /** @var Validator */
    protected $validator;

    public function __construct(IlluminateRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @throws RequestFailedValidation
     */
    public function validate()
    {
        /** @var ValidatesRequests $validator */
        $validator = $this->validator();

        if ($validator->isValid($this) === false) {
            throw new RequestFailedValidation($validator->errors());
        }
    }

    public function json(): array
    {
        if (!isset($this->json)) {
            $json = json_decode(
                $this->request()->getContent(),
                true, // associative array
                512, // depth
                JSON_UNESCAPED_SLASHES
            );

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new JsonApiException(new MalformedRequest());
            }

            $this->json = $json;
        }

        return $this->json;
    }

    public function validator() : ValidatesRequests
    {
        return $this->validator;
    }

    public function setValidator(ValidatesRequests $validator)
    {
        $this->validator = $validator;
    }

    public function request(): IlluminateRequest
    {
        return $this->request;
    }
}
