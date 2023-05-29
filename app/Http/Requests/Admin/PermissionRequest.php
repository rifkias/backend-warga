<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class PermissionRequest extends FormRequest
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
        $rules = [
            'name' => ['required','string','unique:permissions,name'],
        ];
        if($this->isMethod('PUT') || $this->isMethod('PATCH')){
            // $rules['name'] = ['required','string','unique:permissions,name,'.$this->id];
            $rules['name'] = [
                'required',
                'string',
                Rule::unique("permissions",'name')->ignore($this->permission)
            ];
        }
       return $rules;
    }
}
