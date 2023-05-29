<?php

namespace App\Http\Requests\MasterAddress;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'province_id' => ['required',"exists:provinces,id"],
            'city_name' => ['required','string','unique:cities,city_name'],
        ];
        return $rules;
    }
}
