<?php

namespace App\Http\Resources\MasterAddress;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResponse extends JsonResource
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
            'city_name'=>$this->city_name,
            'province'=>$this->province,
            "district"=>$this->district,
        ];
    }
}
