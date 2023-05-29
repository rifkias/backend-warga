<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $fillable = [
        'module_name', 'module_desc'
    ];

    public function permission()
    {
        return $this->hasMany(RolePermission::class);
    }
}
