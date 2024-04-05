<?php

namespace App\Http\Resources\Config;

use Illuminate\Http\Resources\Json\JsonResource;

class UserWilayahResponse extends JsonResource
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
            'user'=>$this->user,
            'username'=>$this->user->name,
            'wilayah'=>$this->wilayah,
            'wilayah_name'=>$this->wilayah->kecamatan." - ".$this->wilayah->rt."/".$this->wilayah->rw,
        ];
    }
}
