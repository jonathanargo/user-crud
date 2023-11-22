<?php

namespace App\Http\Requests;

class UpdateUserRequest extends AbstractUserRequest
{
    public function rules(): array
    {   
        $rules = parent::rules();

        // Need to require ID for Update requests
        $rules['id'] = ['required', 'integer'];
        
        return $rules;
    }
}
