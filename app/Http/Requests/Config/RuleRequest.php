<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;

class RuleRequest extends FormRequest
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
            'role_name'=>['required','string','unique:rules,role_name'],
            'role_desc'=>['required','string'],
        ];
        if($this->isMethod('PUT') || $this->isMethod('PATCH')){
            // $rules['name'] = ['required','string','unique:permissions,name,'.$this->id];
            $rules['role_name'] = [
                'required',
                'string',
                "unique:rules,role_name,".$this->role
            ];
        }
        return $rules;
    }
}
