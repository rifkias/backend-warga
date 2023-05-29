<?php

namespace App\Http\Resources\MasterAddress;

use Illuminate\Http\Resources\Json\JsonResource;

class VillageResponse extends JsonResource
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
            'village_name'=>$this->village_name,
            'district'=>$this->district,
        ];
    }
}
