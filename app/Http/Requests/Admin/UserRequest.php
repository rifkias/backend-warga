<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'name'=>['string','required','max:225'],
            'email'=>[
                'string','required','email','max:225',
                Rule::unique('users','email')->ignore($this->user),
            ],
            'role_id' => ['required',"exists:rules,id"],
            'user_group_id' => ['required', "exists:user_group,id"],
            'password'=>[
                Rule::requiredIf((empty($this->user) || $this->isMethod('POST') )), 'min:8'
            ]
        ];
        // if($this->isMethod('POST')){
        //     $rules['password'] = ['required', 'min:8'];
        // }
        // if($this->isMethod('PUT') || $this->isMethod('PATCH')){
        //     $rules['email'] = ['string','required','email','unique:users,email,'.$this->user,'max:225'];
        // }
        return $rules;
    }
}
