<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
            'module_name'=>['required','string','unique:modules,module_name'],
            'module_desc'=>['required','string'],
        ];
        if($this->isMethod('PUT') || $this->isMethod('PATCH')){
            // $rules['name'] = ['required','string','unique:permissions,name,'.$this->id];
            $rules['module_name'] = [
                'required',
                'string',
                "unique:modules,module_name,".$this->module
            ];
        }
        return $rules;
    }
}
