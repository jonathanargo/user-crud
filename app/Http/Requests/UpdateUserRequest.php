<?php

namespace App\Http\Requests;

class UpdateUserRequest extends AbstractUserRequest
{
    public function rules(): array
    {
        // Need to require ID for Update requests
        $rules = parent::rules();
        $rules['id'] = ['required|integer'];
        return $rules;
    }
}
