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
                'name' => 'required',
                'email'      => sprintf('required|email|unique:users,email,%d,id', $user_id),
                'password' => 'nullable|string|min:6|confirmed'
            ];
        }

        return [
            'name' => 'required',
            'email'      => 'required|email|unique:users',
            'password' => 'nullable|string|min:6|confirmed'
        ];
    }
}
