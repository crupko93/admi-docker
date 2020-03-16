<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $user_id = $this->input('id');
        if ($user_id) {
            return [
                'username'   => sprintf('required|unique:users,username,%d,id', $user_id),
                'first_name' => 'required',
                'last_name'  => 'required',
                'email'      => sprintf('required|email|unique:users,email,%d,id', $user_id),
                'role'       => 'required',
                'password'   => 'nullable|string|min:6|confirmed'
            ];
        }

        return [
            'username'   => 'required|unique:users',
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email|unique:users',
            'role'       => 'required',
            'password'   => 'required|string|min:6|confirmed'
        ];
    }
}
