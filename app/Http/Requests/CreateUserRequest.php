<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CreateUserRequest extends AbstractUserRequest
{
    public function rules(): array
    {
        // Need to require ID for Update requests
        $rules = parent::rules();

        // We require email to be unique, but this only applies on creates. Not updates.
        $rules['email'] = ['required', 'string', 'email', 'max:128', 'unique:users'];
        
        return $rules;
    }
}