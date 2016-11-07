<?php

namespace RealPage\JsonApi\Validation;

use Illuminate\Contracts\Validation\Factory;
use Neomerx\JsonApi\Exceptions\ErrorCollection;
use RealPage\JsonApi\Requests\Request;
use Neomerx\JsonApi\Document\Error;

trait ValidatesRequests
{
    /** @var ErrorCollection */
    protected $errors;

    /** @var Factory */
    protected $validatorFactory;

    public function isValid(Request $request) : bool
    {
        $this->errors = new ErrorCollection();

        /** @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = $this->validatorFactory->make($request->json(), $this->rules(), $this->messages());
        if ($validator->fails()) {
            // @todo put this somewhere else, this is getting messy
            // @see https://jsonapi.org/examples/#error-objects-basic
            foreach ($validator->errors()->all() as $field => $errorMessage) {

                // get the pointer for an array so we can pinpoint the section
                // of json where the error occurred
                $field = '/'.str_replace('.', '/', $field).'/';

                $this->errors->add(new Error(
                    null,
                    null,
                    422,
                    null,
                    'Invalid Attribute',
                    $errorMessage,
                    [
                        'pointer' => $field,
                    ]
                ));
            }
        }
    }

    public function rules(array $rules = null) : array
    {
        $rules = $rules ?? [];

        return array_merge([
            'data'      => 'required',
            'data.type' => 'required|in:'.$this->type(),
        ], $rules);
    }

    public function messages(array $messages = null) : array
    {
        $messages = $messages ?? [];

        return array_merge([
            'data.required'      => 'Data is required in a valid json api format.',
            'data.type.required' => 'A valid resource type must be provided.',
            'data.type.in'       => 'The resource type provided does not match the expected type of "'.$this->type().'".',
        ], $messages);
    }

    public function errors() : ErrorCollection
    {
        return $this->errors;
    }

    /**
     * The type of entity this request is for.
     */
    abstract public function type() : string;

    public function setValidatorFactory(Factory $factory)
    {
        $this->validatorFactory = $factory;
    }
}
