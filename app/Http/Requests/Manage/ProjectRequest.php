<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|unique:projects',
                ];
                break;
            case 'PUT':
            case 'PATCH':
                $projectId = $this->project->id;
                return [
                    'name' => "required|string|unique:projects,name,$projectId",
                ];
                break;
            default:
                return [];
                break;
        }
    }
}
