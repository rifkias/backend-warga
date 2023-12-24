<?php

namespace App\Http\Resources\Config;

use Illuminate\Http\Resources\Json\JsonResource;

class UserGroupWilayahResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            "id"=>$this->id,
            'userGroup'=>$this->userGroup,
            'wilayah'=>$this->wilayah,
            'user_group_code'=>$this->userGroup->group_code,
            'user_group_desc'=>$this->userGroup->group_desc,
            'wilayah_name'=>$this->wilayah->kecamatan." - ".$this->wilayah->rt."/".$this->wilayah->rw,
        ];
    }
}
