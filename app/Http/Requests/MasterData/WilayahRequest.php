<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;

class WilayahRequest extends FormRequest
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
        return [
            'rt'=>['numeric','required','digits:3'],
            'rw'=>['numeric','required','digits:3'],
            "kelurahan"=>['string','required'],
            "kecamatan"=>['string','required'],
            "kabupaten"=>['string','required'],
            "provinsi"=>['string','required'],
            "negara"=>['string','required'],
            "kode_pos"=>['numeric','required',"digits:5"],
        ];
    }
}
