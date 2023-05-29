<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;
    protected $fillable = [
        'wilayah_id', 'house_no','jalan'
    ];
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
    public function wilayah()
    {
        return $this->hasOne(Wilayah::class,"id","wilayah_id");
    }
    public function warga()
    {
        return $this->hasMany(Warga::class,"house_id",'id');
    }
}
