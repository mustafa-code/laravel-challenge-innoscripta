<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiFormRequest extends FormRequest
{
    // Override the default behaviour of failed validation.
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->getMessages();
        $customResponse = [];

        foreach ($errors as $field => $message) {
            $failedRules = array_keys($validator->failed()[$field]);
            $customResponse[$field] = [
                'errors' => $message,
                'failed_rules' => $failedRules
            ];
        }

        $response = response()->error(
            key: "validation.errors",
            message: __("Validation errors, please check your input."),
            status: 422,
            data: $customResponse,
        );

        throw new HttpResponseException($response);
    }

}
