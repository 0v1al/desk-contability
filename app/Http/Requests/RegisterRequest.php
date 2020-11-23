<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required|max:255|unique:users',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|max:255',
            'confirmPassword' => 'required|max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Câmpul nume trebuie completat',
            'name.max' => 'Câmpul nume are lungimea de caractere depașită',
            'name.unique' => 'Un cont cu același nume există deja',
            'email.unique' => 'Un cont cu aceeași adresă de email există deja',
            'email.required' => 'Câmpul email trebuie completat',
            'email.max' => 'Câmpul email are lungimea de caractere depașită',
            'password.required' => 'Câmpul parolă trebuie completat',
            'password.max' => 'Câmpul parolă are lungimea de caractere depașită',
            'confirmPassword.required' => 'Câmpul confirmare parolă trebuie completat',
            'confirmPassword.max' => 'Câmpul confirmare parolă are lungimea de caractere depașită'
        ];
    }
}
