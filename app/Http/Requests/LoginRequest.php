<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|max:255',
            'password' => 'required|max:255'
        ];
    }

    public function messages()
    {
        return [
            'email.max' => 'Câmpul email are lungimea de caractere depașită',
            'email.required' => 'Câmpul email trebuie completat',
            'password.max' => 'Câmpul parolă are lungimea de caractere depașită',
            'password.required' => 'Câmpul parolă trebuie completat'
        ];
    }
}
