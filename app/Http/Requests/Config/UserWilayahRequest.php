<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserWilayahRequest extends FormRequest
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
        $user_id = $this->user_id;
        $wilayah_id = $this->wilayah_id;
        return [
            'user_id'=>[
                'required',
                'exists:users,id',
                Rule::unique("user_wilayah")->where(function($query) use($user_id,$wilayah_id){
                    return $query->where("user_id",$user_id)->where("wilayah_id",$wilayah_id);
                }),
            ],
            'wilayah_id'=>['required','exists:wilayah,id'],
        ];
    }
    public function messages()
    {
        return [
            'user_id.unique'=>"User & Wilayah has already been taken",
        ];
    }
}
