<?php

namespace App\Http\Resources\MasterData;

use Illuminate\Http\Resources\Json\JsonResource;

class WargaHouseResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'nik'=>$this->nik,
            'kk_number'=>$this->kk_number,
            'name'=>$this->name,
            'gender'=>$this->gender,
            'birth_date'=>$this->birth_date,
            'birth_place'=>$this->birth_place,
            'religion'=>$this->religion,
            'residence_status'=>$this->residence_status,
            'family_status'=>$this->family_status,
            'mariage_status'=>$this->mariage_status,
            'is_alive'=>$this->is_alive,
            'death_date'=>$this->death_date,
            'is_active'=>$this->is_active,
            'ktp_address'=>$this->ktp_address,
            'last_education'=>$this->last_education,
            'job'=>$this->job,
            'house'=>$this->house,
        ];
    }
}
