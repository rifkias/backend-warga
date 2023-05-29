<?php

namespace App\Http\Resources\Config;

use Illuminate\Http\Resources\Json\JsonResource;

class RolePermissionResponse extends JsonResource
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
            'module'=>$this->module,
            'role'=>$this->role,
            'pcreate'=>$this->pcreate,
            'pread'=>$this->pread,
            'pupdate'=>$this->pupdate,
            'pdelete'=>$this->pdelete,
            'pupload'=>$this->pupload,
            'pcustom1'=>$this->pcustom1,
            'pcustom2'=>$this->pcustom2,
            'pcustom3'=>$this->pcustom3,
            'pcustom4'=>$this->pcustom4,
            'pcustom5'=>$this->pcustom5,
        ];
    }
}
