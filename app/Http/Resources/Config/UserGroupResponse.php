<?php

namespace App\Http\Resources\Config;

use Illuminate\Http\Resources\Json\JsonResource;

class UserGroupResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        // return [
        //     'id' => $this->id,
        //     'group_code' => $this->group_code,
        //     "group_desc" => $this->group_desc,
        //     "parent_id" => $this->parent_id,
        //     "parent_code" => $this->parent_code,
        //     "parent_desc" => $this->parent_desc,
        //     "parent_parent_id" => $this->parent_parent_id,
        // ];
    }
}
