<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class UserGroup extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;
    protected $table = "user_group";
    protected $fillable = [
        'group_desc', 'group_code','parent_id'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });
    }
    public function getParentKeyName()
    {
        return 'parent_id';
    }
    public function getLocalKeyName()
    {
        return 'id';
    }

    public function parent() {
        return $this->hasOne(UserGroup::class, 'id', 'parent_id');
    }
    public function child(){
        return $this->hasMany(UserGroup::class,'parent_id','id');
    }
}
