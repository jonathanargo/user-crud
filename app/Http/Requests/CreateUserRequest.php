<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->user()->can('create', User::class); // TODO JSA - Test?
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:128'],
            'last_name' => ['required', 'string', 'max:128'],
            'email' => ['required', 'string', 'email', 'max:128', 'unique:users'],
            'mobile_number' => ['required', 'string', 'max:32'],
            'address' => ['required', 'string', 'max:128'],
            'city' => ['required', 'string', 'max:128'],
            'state' => ['required', 'string', 'max:2'], // TODO JSA - Custom state validation
            'zip' => ['required', 'integer'],
            'country' => ['required', 'string', 'max:2']
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'mobile_number' => 'Mobile Number',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State/Province',
            'zip' => 'Postal Code',
            'country' => 'Country'
        ];
    }
}