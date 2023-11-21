<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_name', 'role_desc',"seq"
    ];

    public function permission()
    {
        return $this->hasMany(RolePermission::class,'role_id','id');
    }
}
