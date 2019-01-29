<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUserRequest extends FormRequest
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
        switch ($this->method())
        {
            case 'POST':
                return [
                    'name' => 'nullable|string|unique:users',
                    'phone' => ['required', 'string', 'regex:/^1[345678]\d{9}$/', 'unique:users'],
                    'password' => 'nullable|string|min:6'
                ];
                break;
            case 'PUT':
            case 'PATCH':
                $userId = $this->user->id;
                return [
                    'name' => 'required|string|unique:users',
                    'phone' => ['required', 'string', 'regex:/^1[345678]\d{9}$/', "unique:users,phone,$userId"],
                    'password' => 'nullable|string|min:6'
                ];
                break;
            default:
                return [];
                break;
        }
    }
}
