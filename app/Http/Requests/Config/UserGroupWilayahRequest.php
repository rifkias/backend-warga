<?php

namespace App\Http\Requests\Config;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserGroupWilayahRequest extends FormRequest
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
        $user_group_id = $this->user_group_id;
        $wilayah_id = $this->wilayah_id;
        return [
            'user_group_id'=>[
                'required',
                'exists:user_group,id',
                Rule::unique("user_group_wilayah")->where(function($query) use($user_group_id,$wilayah_id){
                    return $query->where("user_group_id",$user_group_id)->where("wilayah_id",$wilayah_id);
                }),
            ],
            'wilayah_id'=>['required','exists:wilayah,id'],
        ];
    }
}
