<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "first_name" => 'required|max:255',
            "last_name" => 'required|max:255',
            "email" => 'required|email',
            "password" => ['required', 'string', 
                Password::min(6)
                ->mixedCase()
                ->numbers()
                ->letters()
                // ->uncompromised()
            ] ,
            "confirm_password" => 'same:password|required'
        ];
    }
}
