<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;

class UserGroupRequest extends FormRequest
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
            'group_code' => ['required', 'string', 'unique:user_group,group_code','max:45'],
            'group_desc' => ['required','string',
            'max:45'],
            'parent_id'=> ["nullable","exists:user_group,id"],
        ];
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // $rules['name'] = ['required','string','unique:permissions,name,'.$this->id];
            $rules['group_code'] = [
                'required',
                'string',
                "unique:user_group,group_code," . $this->user_group, 'max:45'
            ];
        }
        return $rules;
    }
}
