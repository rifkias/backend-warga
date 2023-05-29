<?php

namespace App\Http\Resources\MasterAddress;

use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResponse extends JsonResource
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
            'distict_name'=>$this->district_name,
            'city'=>$this->city,
            "village"=>$this->village,
        ];
    }
}
