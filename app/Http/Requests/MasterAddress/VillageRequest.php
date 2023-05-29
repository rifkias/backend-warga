<?php

namespace App\Http\Requests\MasterAddress;

use Illuminate\Foundation\Http\FormRequest;

class VillageRequest extends FormRequest
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
            'district_id' => ['required',"exists:districts,id"],
            'village_name' => ['required','string','unique:villages,village_name'],
        ];
        return $rules;
    }
}
