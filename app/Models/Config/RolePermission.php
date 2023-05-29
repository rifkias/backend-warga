<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_id', 'module_id','pcreate','pread','pupdate','pdelete','pupload','pcustom1','pcustom2','pcustom3','pcustom4','pcustom5'
    ];

    public function module()
    {
        return $this->hasOne(Module::class,'id','module_id');
    }
    public function role()
    {
        return $this->hasOne(Rule::class,'id','role_id');
    }
}
