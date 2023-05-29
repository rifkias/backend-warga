<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WargaRequest extends FormRequest
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
            'nik'=>['required','numeric',"digits:16",'unique:warga,nik'],
            'kk_number'=>['required','numeric',"digits:16"],
            'name'=>['required','string'],
            'gender'=>[
                'required',
                'string',
                Rule::in(['laki','perempuan'])
            ],
            'birth_date'=>['required','date','before:tomorrow'],
            'birth_place'=>['required','string'],
            'religion'=>['required','string'],
            'residence_status'=>['required','string'],
            'family_status'=>[
                'required',
                'string',
                Rule::in(['kepala keluarga','istri','anak','menantu','cucu','orang tua','famili lain','pembantu'])
            ],
            'mariage_status'=>[
                'required',
                'string',
                Rule::in(['belum kawin','kawin belum tercatat','kawin','cerai mati','cerai hidup','cerai mati'])
            ],
            'is_alive'=>[
                'required',
                'numeric',
                'digits:1',
                Rule::in([1,0])
            ],
            'death_date'=>[
                'nullable',
                'date',
                'date_format:Y-m-d H:i:s',
                'before:tomorrow',
            ],
            'is_active'=>[
                'required',
                'numeric',
                'digits:1',
                Rule::in([1,0])
            ],
            'ktp_address'=>[
                'required',
            ],
            'last_education'=>[
                'required',
                'string',
                Rule::in([
                    'TAMAT SD / SEDERAJAT',
                    'TIDAK / BELUM SEKOLAH',
                    'SLTA / SEDERAJAT',
                    'SLTP/SEDERAJAT',
                    'BELUM TAMAT SD/SEDERAJAT',
                    'DIPLOMA IV/ STRATA I',
                    'DIPLOMA I / II',
                    'AKADEMI/ DIPLOMA III/S. MUDA',
                    'STRATA II',
                    'STRATA III'
                ])
            ],
            'job'=>[
                'required',
                'string',
            ],
            'father_name'=>['required','string'],
            'mother_name'=>['required','string'],
            'house_id'=>['required','numeric','exists:houses,id'],
        ];
        if($this->isMethod('PUT') || $this->isMethod('PATCH')){
            $rules['nik'] = [
                'required',
                'numeric',
                "digits:16",
                'unique:warga,nik,'.$this->warga
            ];
        }
        return $rules;
    }
}
