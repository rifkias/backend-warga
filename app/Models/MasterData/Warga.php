<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warga extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "warga";
    protected $dates = ['deleted_at'];
    protected static function boot(){
        parent::boot();

        static::creating(function($model){
            if(!$model->isDirty('created_by')){
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
    protected $fillable = [
        'nik',
        'kk_number',
        'name',
        'gender',
        'birth_date',
        'birth_place',
        'religion',
        'residence_status',
        'family_status',
        'mariage_status',
        'is_alive',
        'death_date',
        'is_active',
        'ktp_address',
        'last_education',
        'job',
        'house_id',
        'father_name',
        'mother_name',
        'deleted_by',
    ];
    public function house()
    {
        return $this->hasOne(House::class,'id','house_id');
    }
}
