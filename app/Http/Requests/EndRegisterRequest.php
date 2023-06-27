<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;

class EndRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'string|max:32|nullable',
            'pronouns' => 'string|max:20|regex:/.+\/.+/|nullable',
            'location' => 'string|max:32|nullable',
            'description' => 'string|max:255|nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() { // This is not working
        return [
            'name.max' => 'Your name should be no longer than 32 characters',
            'pronouns.max' => 'Your pronouns should be no longer than 20 characters',
            'pronouns.regex' => 'Your pronouns should include "/" in between',
            'location.max' => 'Your location should be no longer than 32 characters',
            'description.max' => 'Your description should be no longer than 255 characters',
        ];
    }
}