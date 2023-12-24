<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasterData\Wilayah;
class UserGroupWilayah extends Model
{
    use HasFactory;
    protected $table = "user_group_wilayah";
    protected $fillable = [
        'user_group_id', 'wilayah_id'
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
    public function userGroup() {
        return $this->hasOne(UserGroup::class, 'id', 'user_group_id');
    }
    public function wilayah(){
        return $this->hasOne(Wilayah::class,'id','wilayah_id');
    }
}
